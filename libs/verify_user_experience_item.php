<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* **** as21  “Verified” for user experience item   **** */

// this is to add a fake component to BuddyPress. A registered component is needed to add notifications
function custom_filter_notifications_get_registered_components( $component_names = array() ) {
	// Force $component_names to be an array
	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}
	// Add 'custom' component to registered components array
	array_push( $component_names, 'custom' );
	return $component_names;
}

add_filter( 'bp_notifications_get_registered_components', 'custom_filter_notifications_get_registered_components' );

// this gets the saved item id, compiles some data and then displays the notification
function custom_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {

	// New custom notifications
	if ( 'custom_action' === $action ) {
	
		$user = wp_get_current_user();
		$custom_link = bp_get_members_directory_permalink().$user->data->user_nicename.'/verification-experience?id='.(int)$item_id;
		$custom_title = 'Custom title';
		$custom_text = 'New experience item for verification';
		$return = apply_filters( 'custom_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>', $custom_text, $custom_link );
		
		return $return;
		
	}
	
}


add_filter( 'bp_notifications_get_notifications_for_user', 'custom_format_buddypress_notifications', 10, 5 );

// this hooks to comment creation and saves the comment id
function bp_custom_add_notification( $comment_id, $comment_object ) {
	$post = get_post( $comment_object->comment_post_ID );
	$author_id = $post->post_author;
	bp_notifications_add_notification( array(
		'user_id'           => $author_id,
		'item_id'           => $comment_id,
		'component_name'    => 'custom',
		'component_action'  => 'custom_action',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );
	
}
// add_action( 'wp_insert_comment', 'bp_custom_add_notification', 99, 2 );



/* **** as21 buddypress add custom page tab (beside activity,profile,groups etc) **** */

add_action( 'bp_setup_nav', 'my_bp_nav_adder', 50 );

function my_bp_nav_adder() {
	global $bp;
	bp_core_new_nav_item(
			array(
					'name'                => __( 'Listings', 'buddypress' ),
					'slug'                => 'verification-experience',
					'position'            => 1,
					'screen_function'     => 'as21_listing_verif_exper',
					'default_subnav_slug' => 'verification-experience',
					'parent_url'          => '',
					'parent_slug'         => $bp->slug,
			) );
}

function as21_listing_verif_exper() {
	//add title and content here - last is to call the members plugin.php template
	add_action( 'bp_template_title', 'my_groups_page_function_to_show_screen_title' );
	add_action( 'bp_template_content', 'my_groups_page_function_to_show_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function my_groups_page_function_to_show_screen_title() {
	echo 'Verification of experience';
}

function my_groups_page_function_to_show_screen_content() {
	global $bp,$wpdb;
	$quest_id = $bp->displayed_user->id;

		if( (bool)$_POST['ve_verif'] === true && !empty($_POST['ve_exper_id']) ) {
			$wpdb->update( $wpdb->posts,
				array( 'comment_count'=> 1,'post_parent'=> $quest_id), // (comment_count - status verified of dugoodr), (post_parent-id verif of dugoodr)
				array( 'ID' => (int)$_POST['ve_exper_id'] ),
				array( '%d' ),
				array( '%d' )
			);
		    $wpdb->delete( $wpdb->posts, array('post_type'=>'invation_verif_exper','menu_order'=> (int)$_POST['ve_exper_id']), array('%s','%d') );
			$ref = $_SERVER['HTTP_REFERER'];
			?>
			<script> window.location.href = '<?php echo $ref;?>';</script>
			<?php

		}

	$exper = $wpdb->get_row( $wpdb->prepare(
		"SELECT ID,post_title,menu_order,post_author,comment_count
		FROM {$wpdb->posts}
		WHERE ID = %d",
		(int)$_GET['id']
	) );

	if( !empty($exper)):
		if( $exper->comment_count == 0 ):
			?>
			<form method="post">
			<table id="as21_experience_volunteer">
				<tr><th>Details of experience</th><th class="exper_hours">Hours</th><th>User</th><th>Approve</th></tr>
				<tr class="a21_dinam_row">
					<td><input type="text" name="as21_experiences[0][title]" value="<?php echo $exper->post_title;?>"></td>
					<td><input type="text" name="as21_experiences[0][hours]" value="<?php echo $exper->menu_order;?>"></td>
				    <td><?php echo bp_core_get_username($exper->post_author);?></td>
					<td><input type="checkbox" name="ve_verif" value="1"/></td>
					<input type="hidden" name="ve_exper_id" value="<?php echo $exper->ID;?>">
				</tr>
			</table>
			<input type="submit" data-id="'.$exper->ID.'" name="ve_send_notifs" class="as21-send-verif-exper" value="Approve" />
			</form>
			<?php
				else:
					echo '<div id="message"><p>This experience item verified!</p></div>';
		endif;
	endif;

}


/* **** as21 buddypress add custom page tab (beside activity,profile,groups etc) **** */

/** * Parses email addresses, comma-separated or line-separated, into an array */
function as21_ve_parse_addresses( $address_string ) {

	$emails = array();

	// First, split by line breaks
	$rows = explode( "\n", $address_string );

	// Then look through each row to split by comma
	foreach( $rows as $row ) {
		$row_addresses = explode( ',', $row );

		// Then walk through and add each address to the array
		foreach( $row_addresses as $row_address ) {
			$row_address_trimmed = trim( $row_address );

			// We also have to make sure that the email address isn't empty
			if ( ! empty( $row_address_trimmed ) && ! in_array( $row_address_trimmed, $emails ) )
				$emails[] = $row_address_trimmed;
		}
	}

	return apply_filters( 'as21_ve_parse_addresses', $emails, $address_string );
}

function as21_ve_validate_email( $user_email ) {

	$status = 'okay';

	if ( invite_anyone_check_is_opt_out( $user_email ) ) {
		$status = 'opt_out';
	} else if ( $user = get_user_by( 'email', $user_email ) ) {
		$status = 'used';
	} else if ( function_exists( 'is_email_address_unsafe' ) && is_email_address_unsafe( $user_email ) ) {
		$status = 'unsafe';
	} else if ( function_exists( 'is_email' ) && !is_email( $user_email ) ) {
		$status = 'invalid';
	}

	if ( function_exists( 'get_site_option' ) ) {
		if ( $limited_email_domains = get_site_option( 'limited_email_domains' ) ) {
			if ( is_array( $limited_email_domains ) && empty( $limited_email_domains ) == false ) {
				$emaildomain = strtolower( substr( $user_email, 1 + strpos( $user_email, '@' ) ) );

				$is_valid_domain = false;
				foreach ( $limited_email_domains as $led ) {
					if ( $emaildomain === strtolower( $led ) ) {
						$is_valid_domain = true;
						break;
					}
				}

				if ( ! $is_valid_domain ) {
					$status = 'limited_domain';
				}
			}
		}
	}

	return apply_filters( 'as21_ve_validate_email', $status, $user_email );
}


add_action('wp_ajax_as21_ve_send_via_email', 'as21_ve_send_via_email');
// add_action('wp_ajax_nopriv_as21_ve_send_via_email', 'as21_ve_send_via_email');

function as21_ve_send_via_email() {
	$data = $_POST;

	global $wpdb;
	global $bp;


	$emails = false;

	$emails = $data['ve_email_addresses'] ;
	if ( empty( $emails ) ) {
		$res['error'] = 'You didn\'t include any email address!';
		echo json_encode($res);
		exit;
	}

		$email = $data['ve_email_addresses'] ;

		$check = as21_ve_validate_email($email);
		switch ( $check ) {	

			case 'unsafe' :
				$res['error']  = sprintf( '<strong>%s</strong> is not a permitted email address.', $email );
				echo json_encode($res);
				exit;
				break;
			case 'invalid' :
				$res['error']  = sprintf( '<strong>%s</strong> is not a valid email address. Please make sure that you have typed it correctly.', $email );
				echo json_encode($res);
				exit;
				break;

			case 'limited_domain' :
				$res['error'] = sprintf( '<strong>%s</strong> is not a permitted email address. Please make sure that you have typed the domain name correctly.', $email );
				echo json_encode($res);
				exit;
				break;
		}


	if ( ! empty( $email ) ) {


		$is_error = 0;
		global $wpdb;

			$subject = stripslashes( strip_tags( $data['ve_custom_subject']) );

			$message = stripslashes( strip_tags( $data['ve_custom_message'] ) );

			$footer = 'To accept this invite, please visit http://'.$_SERVER['HTTP_HOST'].'/register/?ve_action=ve&ve_email='.$email;
			if( $check == 'used') {
				$user = get_user_by( 'email', $email );
				$user_id = $user->data->ID;
				$footer = 'To accept this invite, please visit '.bp_core_get_user_domain($user_id).'verification-experience?id='.(int)$data['ve_exper_id'];
			}


			$message .= '

================
';
			$message .= $footer;

			$to =  $email;
			if(wp_mail( $to, $subject, $message )) $send .= ' send email to '.$email.' - success!<br> ';
			else $send .= ' send email to '.$email.' - error!<br> ';
			$success_send_emails .= $email.'<br>';
		
		$res['tmp_info'] = $send;

		$res['success'] = 'Invitation were sent successfully to the email address: '.$success_send_emails; 

		if( $check != 'used') {
			$wpdb->update( $wpdb->posts,
				array( 'guid'=> 1,'post_parent'=>0,'post_password'=>$email), // status send 'get verified'
				array( 'ID' => (int)$data['ve_exper_id'] ),
				array( '%d','%d','%s' ),
				array( '%d' )
			);
		}
		if($check == 'used'){
			       $notif_id = bp_notifications_add_notification( array(
			   		'user_id'           => $user->data->ID, //	dev-test-1
					'item_id'           => (int)$data['ve_exper_id'], // 10785
					'secondary_item_id' => 0,
					'component_name'    => 'custom',
					'component_action'  => 'custom_action',
					'date_notified'     => bp_core_current_time(),
					'is_new'            => 1,
				) );


			$wpdb->update( $wpdb->posts,
				array( 'guid'=> 1,'post_parent'=>$user_id,'post_password'=>''), // status send 'get verified'
				array( 'ID' => (int)$data['ve_exper_id'] ),
				array( '%d','%d','%s' ),
				array( '%d' )
			);
		}
	} else {
		$res['error'] =  "Please correct your errors and resubmit." ;
	}


	echo json_encode($res);
	exit;
}

add_action('wp_ajax_as21_ve_send_notif', 'as21_ve_send_notif');
// add_action('wp_ajax_nopriv_as21_ve_send_via_email', 'as21_ve_send_via_email');

function as21_ve_send_notif() {
	// print_r($_POST);
	if(!empty($_POST['ve_exper_id'])) $exper_id = (int)$_POST['ve_exper_id'];
	if(!empty($_POST['cur_user_id'])) $cur_user_id = (int)$_POST['cur_user_id'];
	if(!empty($_POST['ve_notif_user_id'])) $user_id = (int)$_POST['ve_notif_user_id'];
 	if($exper_id > 0){
		if( $user_id == 0) {
			$res['success'] = 'nouser';
			echo json_encode($res);
			exit;
		}
 		global $wpdb;
		$status_get_verified = $wpdb->get_var($wpdb->prepare("SELECT guid FROM {$wpdb->posts} WHERE ID = %d ", $exper_id));
		if( $status_get_verified == '1') {
			$res['success'] = 'exist';
			echo json_encode($res);
			exit;
		}



       $notif_id = bp_notifications_add_notification( array(
			// 'user_id'           => $user_id,
	   		'user_id'           => $user_id, //	dev-test-1
			'item_id'           => $exper_id, // 10785
			'secondary_item_id' => 0,
			'component_name'    => 'custom',
			'component_action'  => 'custom_action',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );

		$wpdb->update( $wpdb->posts,
			array( 'guid'=> 1), // status send 'get verified'
			array( 'ID' => $exper_id ),
			array( '%d' ),
			array( '%d' )
		);
		$res['success'] = 'ok';
		echo json_encode($res);
	}

	exit;
}

// user input - safe
add_action('bp_complete_signup','as21_ve_go_from_mail');
function as21_ve_go_from_mail(){


	if( !empty($_GET['ve_email']) ) $email = $_GET['ve_email'];
	if( $_GET['ve_action']=='ve' && is_email($email) ) {

		$user_id = bp_core_get_userid(sanitize_text_field($_POST['signup_username']));
			global $wpdb;
			$exper_id= $wpdb->get_var($wpdb->prepare("SELECT ID FROM `{$wpdb->posts}` WHERE post_type = %s AND post_password=%s ",'experience_volunteer',$email
				));

	       bp_notifications_add_notification( array(
			// 'user_id'           => $user_id,
	   		'user_id'           => $user_id, //	dev-test-1
			'item_id'           => $exper_id, // 10785
			'secondary_item_id' => 0,
			'component_name'    => 'custom',
			'component_action'  => 'custom_action',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}
	// exit;
}


add_action( 'bp_after_register_page', 'as21_ve_register_screen_message' );

function as21_ve_register_screen_message(){
	if( $_GET['ve_action']=='ve' && !empty($_GET['ve_email']) ) {
		remove_filter('the_content','rs_wpss_encode_emails', 9999 ); // remove fiter WP-SpamShield plugin
		?>
		<script type="text/javascript">
		jQuery(document).ready( function() {
			jQuery("input#signup_email").val("<?php echo $_GET['ve_email'];?>");
		});
		</script>
		<?php
	}
}

function as21_when_delete_user_reset_verfi_exper( $user_id ) {
	global $wpdb;
	$wpdb->update( $wpdb->posts,
		array( 'comment_count'=> 0,'post_parent'=> 0,'guid'=> 0), // (comment_count - status verified of dugoodr), (post_parent-id verif of dugoodr)
		array( 'post_type' => 'experience_volunteer','post_parent'=>(int)$user_id),
		array( '%d','%d','%d' ),
		array( '%s','%d' )
	);

}
add_action( 'delete_user', 'as21_when_delete_user_reset_verfi_exper' );

/* **** as21  “Verified” for user experience item   **** */
