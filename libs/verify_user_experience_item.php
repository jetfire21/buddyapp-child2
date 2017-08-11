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
	// Return component's with 'custom' appended
	// echo '---lala777';
	// print_r($component_names);
	// exit;
	return $component_names;
}

add_filter( 'bp_notifications_get_registered_components', 'custom_filter_notifications_get_registered_components' );

// this gets the saved item id, compiles some data and then displays the notification
function custom_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	// echo '---lala777';
	// print_r($action);
	// var_dump($item_id);
	// exit;

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
					'screen_function'     => 'listingsdisplay',
					'default_subnav_slug' => 'verification-experience',
					// 'parent_url'          => $bp->loggedin_user->domain . $bp->slug . '/',
					'parent_url'          => '',
					'parent_slug'         => $bp->slug,
					// 'show_for_displayed_user' => false,
					// 'site_admin_only'         => true, 
			) );
}

function listingsdisplay() {
	//add title and content here - last is to call the members plugin.php template
	add_action( 'bp_template_title', 'my_groups_page_function_to_show_screen_title' );
	add_action( 'bp_template_content', 'my_groups_page_function_to_show_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function my_groups_page_function_to_show_screen_title() {
	echo 'Verification of experience';
}

// temp
function my_groups_page_function_to_show_screen_content() {
	// echo 'My Tab content here';
	global $bp,$wpdb;
	$quest_id = $bp->displayed_user->id;

	// $all_exper = $wpdb->get_results( $wpdb->prepare(
	// 	"SELECT ID,post_title,menu_order,post_author
	// 	FROM {$wpdb->posts}
	// 	WHERE post_author = %d
	// 	    AND post_type = %s AND guid=%d
	// 	ORDER BY ID",
	// 	intval( $quest_id ),
	// 	'experience_volunteer',
	// 	1
	// ) );
	// alex_debug(0,1,'',$all_exper);

	// double $_POST
	// if(!empty($_POST)){
		// alex_debug(0,1,'POST',$_POST);
		if( (bool)$_POST['ve_verif'] === true && !empty($_POST['ve_exper_id']) ) {
			$wpdb->update( $wpdb->posts,
				array( 'comment_count'=> 1,'post_parent'=> $quest_id), // (comment_count - status verified of dugoodr), (post_parent-id verif of dugoodr)
				array( 'ID' => (int)$_POST['ve_exper_id'] ),
				array( '%d' ),
				array( '%d' )
			);
			// deb_last_query();
			// $ve_email_invation = $wpdb->get_var( $wpdb->prepare("SELECT user_email FROM {$wpdb->users} WHERE ID = %d ",intval( $quest_id) ));
			//$exper_id = $wpdb->get_var("SELECT menu_order FROM `{$wpdb->posts}` WHERE post_type ='invation_verif_exper' AND guid='".$_GET['ve_email']."' ");
		    $wpdb->delete( $wpdb->posts, array('post_type'=>'invation_verif_exper','menu_order'=> (int)$_POST['ve_exper_id']), array('%s','%d') );
		    // deb_last_query();
			// header('Location: http://ya.ru/');
			$ref = $_SERVER['HTTP_REFERER'];
			?>
			<script> window.location.href = '<?php echo $ref;?>';</script>
			<?php

		}
	// }

	$exper = $wpdb->get_row( $wpdb->prepare(
		"SELECT ID,post_title,menu_order,post_author,comment_count
		FROM {$wpdb->posts}
		WHERE ID = %d",
		(int)$_GET['id']
	) );
	// deb_last_query();
	// alex_debug(0,1,'',$exper);
	// echo '<ul id="as21_list_experiences"><li>'.$exper->post_title.'</li></ul>';
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
	// print_r($_POST);
	$data = $_POST;

	global $wpdb;
	// $status_get_verified = $wpdb->get_var($wpdb->prepare("SELECT guid FROM {$wpdb->posts} WHERE ID = %d ", (int)$data['ve_exper_id']));
	// // deb_last_query();
	// // var_dump($status_get_verified); exit;
	// if( $status_get_verified == '1') {
	// 	$res['warning'] = 'exist';
	// 	echo json_encode($res);
	// 	exit;
	// }

	// echo json_encode($_POST);

		global $bp;

	// $options = invite_anyone_options();

	$emails = false;

	$emails = $data['ve_email_addresses'] ;
	if ( empty( $emails ) ) {
		// bp_core_add_message( __( 'You didn\'t include any email addresses!', 'invite-anyone' ), 'error' );
		// bp_core_redirect( $bp->loggedin_user->domain . $bp->invite_anyone->slug . '/invite-new-members' );
		// die();
		// $returned_data['error_message'] = 'You didn\'t include any email addresses!';
		$res['error'] = 'You didn\'t include any email address!';
		echo json_encode($res);
		exit;
	}

		$email = $data['ve_email_addresses'] ;

		// $check = invite_anyone_validate_email( $email );
		$check = as21_ve_validate_email($email);
		switch ( $check ) {

			// case 'opt_out' :
			// 	$returned_data['error_message'] .= sprintf( __( '<strong>%s</strong> has opted out of email invitations from this site.', 'invite-anyone' ), $email );
			// 	break;

			// case 'used' :
			// 	$res['error'] = sprintf( "<strong>%s</strong> is already a registered user of the site.", $email );		
			// 	echo json_encode($res);
			// 	exit;
			// 	break;

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
		// echo $email.'-'.$check."<br>";


	if ( ! empty( $email ) ) {


	// echo '------step as21_verification_experience_process------send mail<br>';


		/* send and record invitations */

		// do_action( 'invite_anyone_process_addl_fields' );

		// $groups = ! empty( $data['invite_anyone_groups'] ) ? $data['invite_anyone_groups'] : array();
		$is_error = 0;
		global $wpdb;

			$subject = stripslashes( strip_tags( $data['ve_custom_subject']) );

			$message = stripslashes( strip_tags( $data['ve_custom_message'] ) );

			// $footer = invite_anyone_process_footer( $email );
			// $footer = invite_anyone_wildcard_replace( $footer, $email );
			// 'To accept this invite, please visit http://dugoodr2.dev/register/?iaaction=accept-invitation&email=devtest201721%40gmail.com';
			$footer = 'To accept this invite, please visit http://'.$_SERVER['HTTP_HOST'].'/register/?ve_action=ve&ve_email='.$email;
			if( $check == 'used') {
				$user = get_user_by( 'email', $email );
				$user_id = $user->data->ID;
				// print_r($user);
				// echo $user->data->ID;
				$footer = 'To accept this invite, please visit '.bp_core_get_user_domain($user_id).'verification-experience?id='.(int)$data['ve_exper_id'];
			}


			$message .= '

================
';
			$message .= $footer;

			// $to = apply_filters( 'invite_anyone_invitee_email', $email );
			$to =  $email;
			// $subject = apply_filters( 'invite_anyone_invitation_subject', $subject );
			// $message = apply_filters( 'invite_anyone_invitation_message', $message );

			// echo ' to- ';var_dump($to);
			if(wp_mail( $to, $subject, $message )) $send .= ' send email to '.$email.' - success!<br> ';
			else $send .= ' send email to '.$email.' - error!<br> ';
			$success_send_emails .= $email.'<br>';
		
		$res['tmp_info'] = $send;

		// Set a success message

		// $success_message = sprintf( "Invitations were sent successfully to the following email addresses: %s", implode( ", ", $emails ) );
		// $success_message = sprintf( "Invitations were sent successfully to the following email addresses: %s", $success_send_emails );
		// bp_core_add_message( $success_message );
		$res['success'] = 'Invitation were sent successfully to the email address: '.$success_send_emails; 

		// do_action( 'sent_email_invites', $bp->loggedin_user->id, $emails, $groups );
		if( $check != 'used') {
			$wpdb->insert(
				$wpdb->posts,
				array( 'post_type'=>'invation_verif_exper','menu_order'=> (int)$data['ve_exper_id'],'guid'=>$email),
				array( '%s','%d','%s' )
			);
		}
		if($check == 'used'){
			       $notif_id = bp_notifications_add_notification( array(
					// 'user_id'           => $user_id,
			   		'user_id'           => $user->data->ID, //	dev-test-1
					'item_id'           => (int)$data['ve_exper_id'], // 10785
					'secondary_item_id' => 0,
					'component_name'    => 'custom',
					'component_action'  => 'custom_action',
					'date_notified'     => bp_core_current_time(),
					'is_new'            => 1,
				) );
			    // deb_last_query();


			$wpdb->update( $wpdb->posts,
				array( 'guid'=> 1,'post_parent'=>$user_id), // status send 'get verified'
				array( 'ID' => (int)$data['ve_exper_id'] ),
				array( '%d','%d' ),
				array( '%d' )
			);
		}
		// deb_last_query();
	} else {
		$res['error'] =  "Please correct your errors and resubmit." ;
		// echo $returned_data['error_message'];
		// bp_core_add_message( $success_message, 'error' );
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
 	if($exper_id > 0){
 		// echo 'exper_id '.$exper_id;
 		global $wpdb;
		$status_get_verified = $wpdb->get_var($wpdb->prepare("SELECT guid FROM {$wpdb->posts} WHERE ID = %d ", $exper_id));
		// deb_last_query();
		// var_dump($status_get_verified); exit;
		if( $status_get_verified == '1') {
			$res['success'] = 'exist';
			echo json_encode($res);
			exit;
		}

		// $notif_id = bp_notifications_add_notification( $args );
		$ids = $wpdb->get_col("SELECT ID FROM {$wpdb->users}");
		// alex_debug(0,1,'',$ids);
		// deb_last_query();

		if( !empty($ids)):
			foreach ($ids as $id) {
				if($id == $cur_user_id ) continue;
			       $notif_id = bp_notifications_add_notification( array(
					// 'user_id'           => $user_id,
			   		'user_id'           => $id, //	dev-test-1
					'item_id'           => $exper_id, // 10785
					'secondary_item_id' => 0,
					'component_name'    => 'custom',
					'component_action'  => 'custom_action',
					'date_notified'     => bp_core_current_time(),
					'is_new'            => 1,
				) );
			    // deb_last_query();
			}
		endif;

		$wpdb->update( $wpdb->posts,
			array( 'guid'=> 1), // status send 'get verified'
			array( 'ID' => $exper_id ),
			array( '%d' ),
			array( '%d' )
		);
		// unset($_POST);
		$res['success'] = 'ok';
		echo json_encode($res);
	}

	exit;
}

// user input - safe
add_action('bp_complete_signup','as21_ve_go_from_mail');
function as21_ve_go_from_mail(){
	// http://dugoodr2.dev/register/?iaaction=accept-invitation&email=oenomaus2017%40mail.ru
	// http://dugoodr2.dev/register/?ve_action=ve&ve_email=oenomaus2017%40mail.ru
	// invation_verif_exper
	// alex_debug(0,1,'post',$_POST);
	// alex_debug(0,1,'get',$_GET);
	// echo 'wp_user_id===';var_dump($wp_user_id );
	// echo 'wp_user_id===';var_dump($usermeta );
	// $wp_user_id = bp_core_signup_user( $_POST['signup_username'], $_POST['signup_email'], $usermeta );

	if( !empty($_GET['ve_email']) ) $email = $_GET['ve_email'];
	if( $_GET['ve_action']=='ve' && is_email($email) ) {

		$user_id = bp_core_get_userid(sanitize_text_field($_POST['signup_username']));
		// var_dump($user_id);
			global $wpdb;
			$exper_id= $wpdb->get_var($wpdb->prepare("SELECT menu_order FROM `{$wpdb->posts}` WHERE post_type = %s AND guid=%s ",'invation_verif_exper',$email
				));
			var_dump($exper_id);

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
	// devtest201721@gmail.com
	// $_GET['ve_email'] = urlencode('devtest201721@gmail.com');
	// $email =  urlencode('devtest201721@gmail.com');
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
	// deb_last_query();
	// exit;

}
add_action( 'delete_user', 'as21_when_delete_user_reset_verfi_exper' );

/* **** as21  “Verified” for user experience item   **** */
