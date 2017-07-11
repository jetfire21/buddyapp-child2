<?php
/**
 * @package WordPress
 * @subpackage BuddyApp
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since BuddyApp 1.0
 */

/**
 * BuddyApp Child Theme Functions
 * Add custom code below
*/ 

add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
 
function new_mail_from($old) {
return 'volunteer@dugoodr.com';
}
function new_mail_from_name($old) {
// return 'DuGoodr Scout';
return 'Justa DuGoodr';
}


/* ********** code for member and groups not related,only styles and icons ********** */ 
/* ********** for page members ********** */ 

//Social Media Icons based on the profile user info
function member_social_extend(){

		// $dmember_id = $bp->displayed_user->id;
		// $user = wp_get_current_user();
		// $dmember_id = $user->ID;
		global $bp;
		$member_id = $bp->displayed_user->id;


		$website_info = xprofile_get_field_data('Website', $dmember_id);
		$fb_info = xprofile_get_field_data('Facebook Profile', $dmember_id);
		$google_info = xprofile_get_field_data('Google+', $dmember_id);
		$instagram_info = xprofile_get_field_data('Instagram', $dmember_id);
		$twitter_info = xprofile_get_field_data('Twitter', $dmember_id);
		$linkedin_info = xprofile_get_field_data('LinkedIn Profile', $dmember_id);

		if($website_info === false) $has_social .= ''; else $has_social = true;
		if($fb_info == '<a href="" rel="nofollow"></a>') $has_social .= ''; else $has_social = true;
		if($google_info == '<a href="" rel="nofollow"></a>') $has_social .= ''; else $has_social = true;
		if($instagram_info == '<a href="" rel="nofollow"></a>') $has_social .= ''; else $has_social = true;
		if($twitter_info == '<a href="" rel="nofollow"></a>') $has_social .= ''; else $has_social = true;
		if($linkedin_info == '<a href="" rel="nofollow"></a>') $has_social .= ''; else $has_social = true;
		// echo "has_s ";var_dump($has_social);

		$html = false;
		if (  $website_info) {
			$html .= '<span class="fb-info">';
			$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/website.png" />';
			 $html .= preg_replace("/>[^<]+/i", " target='blank'>$img", $website_info);
			 $html .= '</span>';
		}
		if ( $fb_info){
			$html .= '<span class="fb-info">';
			// $img = '<img src="'.bloginfo('wpurl').'/wp-content/themes/buddyapp-child/images/f.png" />';
			$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/fb.png" />';
			 $html .= preg_replace("/>[^<]+/i", " target='blank'>$img", $fb_info);
			 $html .= '</span>';
		}
		if ( $google_info) {
			$html .= '<span class="fb-info">';
			$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/google+.png" />';
			 $html .= preg_replace("/>[^<]+/i", " target='blank'>$img", $google_info);
			 $html .= '</span>';
		}
		if ( $instagram_info) {
			$html .= '<span class="fb-info">';
			$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/instagram.png" />';
			$html .= preg_replace("/>[^<]+/i", " target='blank'>$img", $instagram_info);
			$html .= '</span>';
		}
		if ( $twitter_info) {
			$html .= '<span class="fb-info">';
			$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/twitter.png" />';
			$html .= preg_replace("/>[^<]+/i", " target='blank'>$img", $twitter_info);
			 $html .= '</span>';
		}
		if ( $linkedin_info ){
			$html .= '<span class="fb-info">';
			$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/linkedin.png" />';
			$html .= preg_replace("/>[^<]+/i", " target='blank'>$img", $linkedin_info);
			$html .= '</span>';
		}
	if( $has_social === true ) echo '<div class="member-social">'.$html.'</div>';
	else echo '<div class="member-social" id="tooltips-socilal-links" style="width:100px;"></div>';
}
add_filter( 'bp_before_member_header_meta', 'member_social_extend' ); 

/* ********** soclinks for page groups ********** */ 

function alex_display_social_groups() {

	global $wpdb;
	$gid = bp_get_group_id();
	$fields = $wpdb->get_results( $wpdb->prepare(
		"SELECT ID, post_title, post_content
		FROM {$wpdb->posts}
		WHERE post_parent = %d
		    AND post_type = %s
		ORDER BY ID ASC",
		intval( $gid ),
		"alex_grsoclink"
		// "alex_gfilds"
	) );

	if(!empty($fields)) echo "<div class='wrap_soclinks'>";

	foreach ($fields as $field) {

        if(!empty($field->post_content)) $data = trim($field->post_content); 
        else $data = false;

        if( !empty($data) ){
        	// var_dump($data);
        	// var_dump($field->post_title);
        	// var_dump(preg_match("#google#i", $field->post_title));
			if(preg_match("#google#i", $field->post_title)) {
				$field->post_title = preg_replace("#google#i", "Youtube", $field->post_title);
				$field->post_title = str_replace("+", "", $field->post_title);
			}
        	// var_dump($field->post_title);

        	switch ($field->post_title) {
        		case 'Website':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/website.png" />';
        			break;  		
        		case 'Facebook':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/fb.png" />';
        			break;  		
        		// case 'Google+':
        		case 'Youtube':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/youtube.png" />';
        			break;
        		case 'Twitter':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/twitter.png" />';
        			break;
        		case 'Instagram':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/instagram.png" />';
        			break;
        		case 'Linkedin':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/linkedin.png" />';
        			break;
        	}

	        // and now display field content
	        echo '<span class="fb-info groups-soc-links"><a href="'.sanitize_text_field($data).'" target="_blank">'.$img.'</a></span>';
        }

    }
    if(!empty($fields)) echo "</div>";

    // display city/state on group page
    $table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
	$city = $wpdb->get_results( $wpdb->prepare(
		"SELECT meta_value
		FROM {$table_grmeta}
		WHERE group_id = %d
		    AND meta_key = %s
		",
		intval( $gid ),
		"city_state"
	) );
	if( !empty($city[0]->meta_value) ) {
		$html = '<div class="city_state">';
		$html .= $city[0]->meta_value;
		$html .= '</div>';
		echo $html;
	}
}

// add fields social links on page site.ru/causes/create/step/group-details/ and site.ru/causes/group_name/admin/edit-details/
add_action( 'bp_before_group_header_meta', 'alex_display_social_groups');

function alex_edit_group_fields(){

	global $bp,$wpdb;
	$gid = $bp->groups->current_group->id;

	if( !bp_is_group_creation_step( 'group-details' ) ){
	    $table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
		$city = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM {$table_grmeta} WHERE group_id = %d AND meta_key = %s",
			intval( $gid ),	"city_state"
		) );
		//$job_board_link = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM {$table_grmeta} WHERE group_id = %d AND meta_key = %s",
		//intval( $gid ),	"job_board_link"
		//) );

		echo '<label class="" for="city_state">City, Province/State</label>';
		echo '<input id="city_state" name="city_state" type="text" value="' . esc_attr($city) . '" />';

		//echo '<label class="" for="job_board_link">Add link to your websites Job Board:</label>';
		//echo '<input id="job_board_link" name="job_board_link" type="url" value="' . esc_attr($job_board_link) . '" />';
	

		// info about all groups
		$groups = groups_get_groups();
		$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
		// var_dump($a);
		$fields = $wpdb->get_results( $wpdb->prepare(
			"SELECT ID, post_title, post_content, post_excerpt
			FROM {$wpdb->posts}
			WHERE post_parent = %d
			    AND post_type = %s
			ORDER BY ID ASC",
			intval( $gid ),
			"alex_grsoclink"
			// "alex_gfilds"
		) );

		// as21_system_message();	alex_debug(0,1,'',$fields);

		foreach ($fields as $field) {
			// var_dump($field);

			// if(preg_match("#google+#i", $field->post_title) ) 
			// $field->post_title  =  (preg_replace("#google\+#i", "Youtube", $field->post_title) );
			if(preg_match("#google#i", $field->post_title)) {
				$field->post_title = preg_replace("#google#i", "Youtube", $field->post_title);
				$field->post_title = str_replace("+", "", $field->post_title);
			}

			echo '<label class="" for="alex-'.$field->ID.'">'.$field->post_title.'</label>';
			echo '<input id="alex-'.$field->ID.'" name="alex-'.$field->ID.'" type="url" value="' . esc_attr( $field->post_content ) . '" />';
		}

	}
}

// display all fields on page manage->details
add_action( 'groups_custom_group_fields_editable', 'alex_edit_group_fields');

function alex_edit_group_fields_save(){

		global $wpdb;
		// alex_debug(0,1,'',$_POST);		exit;
		
		foreach ( $_POST as $data => $value ) {
			if ( substr( $data, 0, 5 ) === 'alex-' ) {
				$to_save[ $data ] = $value;
			}
		}

		foreach ( $to_save as $ID => $value ) {
				$ID = substr( $ID, 5 );

				$wpdb->update(
					$wpdb->posts,
					array(
						'post_content' => $value,    // [data]
					),
					array( 'ID' => $ID ),           // [where]
					array( '%s' ),                  // data format
					array( '%d' )                   // where format
				);
		}
		// update city for group page
		$gid = (int)$_POST['group-id'];
		$table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
		$res = $wpdb->update($table_grmeta,array(
				'meta_value' => sanitize_text_field($_POST['city_state']),    
			),
			array( 'meta_key' => 'city_state', 'group_id' => $gid),          
			array('%s'), array('%s','%d')                   
		);

		$job_board_link = sanitize_text_field($_POST['job_board_link']);
		$is_job_board_link = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_grmeta WHERE group_id=%d AND meta_key=%s",$gid,'job_board_link'));

		if( is_null($is_job_board_link)) 
		{	
			$add_jbl = $wpdb->insert(
				$table_grmeta,
				array( 'group_id'=>$gid,'meta_key'=>'job_board_link','meta_value'=>$job_board_link),
				array( '%d','%s','%s' )
			);
		}else{
			$up_job_board_link = $wpdb->update($table_grmeta,array(
					'meta_value' => $job_board_link,    
				),
				array( 'meta_key' => 'job_board_link', 'group_id' => $gid),          
				array('%s'), array('%s','%d')                   
			);
		}
}

add_action( 'groups_group_details_edited', 'alex_edit_group_fields_save' );


/* *********** */

function add_soclinks_only_for_one_group_db(){


	global $wpdb;
	$gr_last_id = $wpdb->get_row("SELECT id FROM `{$wpdb->prefix}bp_groups` ORDER BY date_created DESC");
	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	$postid = $postid_and_fields[0]+1;
	$fields = $postid_and_fields[1];

	// alex_debug(0,1,"fie",$fields);
	// alex_debug(0,1,"fie",$_COOKIE);
	// exit;

	// echo "=========add_soclinks_only_for_one_group_db=======";
	// echo $gr_last_id->id;
	$is_first_soclink = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type=%s AND post_parent=%d AND post_title=%s",'alex_grsoclink',$gr_last_id->id,"Facebook"));
	// deb_last_query();
	// var_dump($is_first_soclink);
	// exit;

	if( is_null($is_first_soclink)){
		foreach ($fields as $field_name) {

			if( !empty($_COOKIE['alex-'.$field_name]) ) {
				$post_content = sanitize_text_field($_COOKIE['alex-'.$field_name]);
			}
			else $post_content = '';

			if( $field_name == "Google+" && !empty($_COOKIE['alex-Youtube']) ){
				 $post_content = sanitize_text_field($_COOKIE['alex-Youtube']);			
			}

			// if(preg_match("#google#i", $field_name)) { $field_name = str_replace("+", "", $field_name); $field_name = $field_name."+"; }
			
			// echo $field_name." - ";

			// if( !empty($post_content)){
			
				$wpdb->insert(
					$wpdb->posts,
					array( 'ID'=>$postid, 'post_title'=>$field_name, 'post_type' => 'alex_grsoclink', 'post_parent'=>$gr_last_id->id, 'post_content'=> $post_content),
					// array( 'ID'=>$postid, 'post_title'=>$field_name, 'post_type' => 'alex_gfilds', 'post_parent'=>$gr_last_id->id, 'post_content'=> $post_content),
					array( '%d','%s','%s','%d', '%s' )
				);
				$postid++; 
			
			// }
				// deb_last_query();

		} 
	}
	foreach ($fields as $field_name) {
		// unset($_COOKIE['alex-'.$field_name]);
		// delete cookie
		setcookie( 'alex-'.$field_name, false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	}
	// exit;
}

// Fires after the group has been successfully created (variation 1)
// add_action( 'groups_group_create_complete','add_soclinks_only_for_one_group_db');
add_action( 'bp_after_group_settings_creation_step','add_soclinks_only_for_one_group_db');

add_action( 'groups_create_group_step_save_group-details','alex_save_socialinks_cookies' );
function alex_save_socialinks_cookies(){
	foreach ($_POST as $k => $v) {
		$k = str_replace("+", "", $k);
		$v = sanitize_text_field($v);
		if(  preg_match("#^alex-#i", $k) === 1) setcookie($k, $v,8 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN,is_ssl());	
	}
}

// add fieldss social links on page site.ru/causes/create/step/group-details/
add_action( 'bp_after_group_details_creation_step',"alex_group_create_add_socialinks" );

function alex_group_create_add_socialinks(){

	global $bp,$wpdb;

	// echo "===group-details=====";
	// get fields social links
	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	$fields = $postid_and_fields[1];
	// alex_debug(0,1,'',$_COOKIE);

	foreach ($fields as $field) {

		if( !empty($_COOKIE['alex-'.$field]) ) {
			$user_fill = $_COOKIE['alex-'.$field];
		}
		else $user_fill = '';

		// if(preg_match("#google#i", $field) === 1) $field = $field."+";
		// if(preg_match("#google\+#i", $field) === 1) $field = preg_replace("#google\+#i", "Youtube", $field);
		if(preg_match("#google#i", $field)) {
				$field = preg_replace("#google#i", "Youtube", $field);
				$field = str_replace("+", "", $field);
		}

		echo '<label class="" for="alex-'.$field.'">'.$field.'</label>';
		echo '<input id="alex-'.$field.'" name="alex-'.$field.'" type="url" value="'.$user_fill.'" />';
	}
}

// delete fields for social links when group deleted 
add_action( 'groups_delete_group', 'alex_group_delete_fields_soclinks');
function alex_group_delete_fields_soclinks(){
	global $wpdb;
	$gid = (int)$_GET['gid'];
    // $wpdb->delete( $wpdb->posts, array('post_type'=>'alex_gfilds','post_parent'=> $gid), array('%s','%d') );
    $wpdb->delete( $wpdb->posts, array('post_type'=>'alex_grsoclink','post_parent'=> $gid), array('%s','%d') );
}

add_action( 'groups_create_group_step_save_group-details','alex_add_city_for_group' );
function alex_add_city_for_group(){
	global $bp,$wpdb;
	$city_state = sanitize_text_field($_POST['city_state']);
	$wpdb->insert(
		$wpdb->prefix."bp_groups_groupmeta",
		array( 'group_id' => $bp->groups->new_group_id, 'meta_key' => 'city_state', 'meta_value'=> $city_state),
		array( '%d','%s','%s' )
	);
}

function buddyapp_search_shortcode() {
    $context = sq_option( 'search_context', '' );
    echo kleo_search_form(array('context' => $context));
}

// add_shortcode('buddyapp_search_shortcode','buddyapp_search_shortcode');
// use [buddyapp_search_shortcode]


/* ****** modification searchbox and signin/register form for landing page ******* */

// add_shortcode( 'kleo_search_form', 'kleo_search_form' );
add_shortcode( 'alex_search_form', 'alex_search_form' );
function alex_search_form( $atts = array(), $content = null ) {

	$form_style = $type = $placeholder = $context = $hidden = $el_class = '';
	global $bp;

	extract(shortcode_atts(array(
		'form_style' => 'default',
		// 'form_style' => 'groups',
		'type' => 'both',
		// 'context' => '',
		'context' => array('groups','members'),
		// 'action' => home_url( '/' )."members",
		'action' => home_url( '/' ).$bp->members->root_slug,
		'el_id' => 'searchform',
		'el_class' => 'search-form',
		'input_id' => 'main-search',
		'input_class' => 'header-search',
		'input_name' => 's',
		'input_placeholder' => __( 'Search', 'buddyapp' ),
		'button_class' => 'header-search-button',
		'hidden' => '',
	), $atts));

	$el_class .= ' kleo-search-wrap kleo-search-form ';

	if ( is_array( $context ) ) {
		$context = implode( ',', $context );
	}

	$ajax_results = 'yes';
	$search_page = 'yes';

	if ( $type == 'ajax' ) {
		$search_page = 'no';
	} elseif ( $type == 'form_submit' ) {
		$ajax_results = 'no';
	}

	$output = '<div class="search">
				<i> </i>
	<div class="s-bar">
	<form id="' . $el_id . '" class="' . $el_class . ' second-menu" method="get" ' . ( $search_page == 'no' ? ' onsubmit="return false;"' : '' ) . ' action="' . $action . '" data-context="' . $context  .'">';
	$output .= '<input id="' . $input_id . '" class="' . $input_class . ' ajax_s" autocomplete="off" type="text" name="' . $input_name . '" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'e.g. Awesome Todd\';}" value="e.g. Awesome Todd">';
	$output .= '<input type="submit" class="' . $button_class . '" value="Search" />';
	//if ( $ajax_results == 'yes' ) {
	//$output .= '<div class="kleo_ajax_results search-style-' . $form_style . '"></div>';
	//}
	$output .= $hidden;
	$output .= '</form>
	</div>
	</div>';

	return $output;
}


add_shortcode( 'alex_nothome_search_form', 'alex_nothome_search_form' );
function alex_nothome_search_form( $atts = array(), $content = null ) {

	global $bp;
	$form_style = $type = $placeholder = $context = $hidden = $el_class = '';
	extract(shortcode_atts(array(
		'form_style' => 'default',
		'type' => 'both',
		'context' => array('groups','members'),
		// 'action' => home_url( '/' )."members",
		'action' => home_url( '/' ).$bp->members->root_slug,
		'el_id' => 'searchform',
		'el_class' => 'search-form',
		'input_id' => 'main-search',
		'input_class' => 'header-search',
		'input_name' => 's',
		'input_placeholder' => __( 'Search', 'buddyapp' ),
		'button_class' => 'header-search-button',
		'hidden' => '',
	), $atts));

	$el_class .= ' kleo-search-wrap kleo-search-form ';

	if ( is_array( $context ) ) {
		$context = implode( ',', $context );
	}

	$ajax_results = 'yes';
	$search_page = 'yes';

	if ( $type == 'ajax' ) {
		$search_page = 'no';
	} elseif ( $type == 'form_submit' ) {
		$ajax_results = 'no';
	}

	if ( function_exists('bp_is_active') && $context == 'members' ) {
		//Buddypress members form link
		$action = bp_get_members_directory_permalink();

	} elseif ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) && $context == 'groups' ) {
		//Buddypress group directory link
		$action = bp_get_groups_directory_permalink();

	} elseif ( class_exists('bbPress') && $context == 'forum' ) {
		$action = bbp_get_search_url();
		$input_name = 'bbp_search';

	} elseif ( $context == 'product' ) {
		$hidden .= '<input type="hidden" name="post_type" value="product">';
	}

	$output = '<form id="' . $el_id . '" class="' . $el_class . '" method="get" ' . ( $search_page == 'no' ? ' onsubmit="return false;"' : '' ) . ' action="' . $action . '" data-context="' . $context  .'">';
	$output .= '<input id="' . $input_id . '" class="' . $input_class . ' ajax_s" autocomplete="off" type="text" name="' . $input_name . '" value="" placeholder="' . $input_placeholder . '">';
	$output .= '<button type="submit" class="' . $button_class . '"></button>';
	if ( $ajax_results == 'yes' ) {
		$output .= '<div class="kleo_ajax_results search-style-' . $form_style . '"></div>';
	}
	$output .= $hidden;
	$output .= '</form>';

	return $output;
}

add_action('bp_before_register_page' ,"alex_add_icon_close_for_register_page");
function alex_add_icon_close_for_register_page(){
	echo '<div class="wrap-reg-close"><a class="reg-close" href="/">×</a></div>';
}

// all $args value show buddypress function bp_has_members()
// show first 20 exists members if value serach empty for click search button 
function my_bp_loop_querystring( $query_string, $object ) {

	$search = mb_strtolower( strip_tags( trim($_REQUEST['s']) ) );
    if ( ! empty( $search ) and ($search == 'search') ) {
	    $query_string .= '&search_terms=';
	    $query_string .= '&user_ids=1,2,3,4,5,6,7,8,9.10,11,12,13,14,15,16,17,18,19,20';
    }
 
    return $query_string;
}
add_action( 'bp_legacy_theme_ajax_querystring', 'my_bp_loop_querystring', 100, 2 );


add_action("wp_head","alex_include_css_js",90);

function alex_include_css_js(){
	if( is_front_page() ){
		echo '<link href="'.get_stylesheet_directory_uri().'/search-templ/css/style2.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/search-templ/css/style.css" rel="stylesheet" type="text/css" media="all"/>';
	}

	// if(function_exists('bp_has_profile')):
	// 	if( !bp_has_profile() ) return;
	// else
	// 	return;
	// endif;
	if( !bp_has_profile() ) return;

	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }

    // $member_name = bp_core_get_username($user_id_islogin);
    $member_name = bp_core_get_username($user_id_isnotlogin);

	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view = preg_match("#^/i-am/[a-z0-9_]+/profile/$#i", $url_s);
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/i-am/".$member_name."/$#i", $url_s);

	if($profile_view or $profile_view_notdefault){

		/* *** disable standart wordpress style ***** */

		function alex_dequeue_default_css() {
		  wp_dequeue_style('bootstrap');
		  wp_deregister_style('bootstrap');
		}
		add_action('wp_enqueue_scripts','alex_dequeue_default_css',100);

		/* *** disable standart wordpress style ***** */

		// echo '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>';
		// echo '<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">';
		// echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/libs/jqtimeliner/css/jquery-timeliner.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/libs/alex/fix-style.css" rel="stylesheet" type="text/css" media="all"/>';
	}

}

add_action('wp_enqueue_scripts','a21_inc_styles_for_timeline',999);
function a21_inc_styles_for_timeline(){
	
	// if(function_exists('bp_is_active')):
	if( bp_is_user_profile()) {

	 if( !preg_match("/edit/i", $_SERVER['REQUEST_URI']) ){

		wp_enqueue_style( 'bootstrap-2', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',array('bootstrap'));
		// wp_enqueue_style( 'font-awesome-a21', 'http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',array('bootstrap-2'));
		wp_enqueue_style( 'datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css',array('bootstrap-2'));
		}else{
			
			wp_enqueue_style( 'datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css',array('bootstrap'));
		   wp_enqueue_script('datepicker',"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js",array('jquery'),'',true);
		}
	}
	// endif;

	if( is_page("jobs")) {
	// if( is_page("jobs") || is_page("post-a-job")) {

		wp_deregister_style("wp-job-manager-frontend");
   		// wp_enqueue_style( 'a21-wp-job-inline', get_stylesheet_directory_uri()."/css/a21-wp-job-inline.css",array("kleo-style"));
   		wp_enqueue_style( 'a21-job-manager-fonts', "https://fonts.googleapis.com/css?family=Montserrat:400,700|Varela+Round&#038;subset=latin");
   		wp_enqueue_style( 'a21-jobify', get_stylesheet_directory_uri()."/css/a21-jobify.css",array("kleo-style"));
   		wp_enqueue_style( 'a21-job-manager-custom', get_stylesheet_directory_uri()."/css/a21-job-manager-custom.css",array("a21-jobify"));

	}

	if(is_page("post-a-job")):
		if(JOB_MANAGER_PLUGIN_URL){
		 // echo JOB_MANAGER_PLUGIN_URL . '/assets/css/frontend.css';
		 wp_enqueue_style( 'wp-job-manager-frontend2', JOB_MANAGER_PLUGIN_URL . '/assets/css/frontend.css' );
		}
	endif;
  
   wp_enqueue_script('a21_common',get_stylesheet_directory_uri().'/js/a21_common.js',array('jquery'),'',true);
}

/*
add_action("wp_enqueue_scripts","a21_get_wpjm_for_page_preview");
function a21_get_wpjm_for_page_preview(){
	// var_dump(is_page("post-a-job"));

	if(is_page("post-a-job")):
		if(JOB_MANAGER_PLUGIN_URL){
		 // echo JOB_MANAGER_PLUGIN_URL . '/assets/css/frontend.css';
		 wp_enqueue_style( 'wp-job-manager-frontend2', JOB_MANAGER_PLUGIN_URL . '/assets/css/frontend.css' );
		}
	endif;
	// exit;

}
*/

add_action('wp_enqueue_scripts','as21_include_custom_js_css');
function as21_include_custom_js_css(){
	if( bp_is_user_profile() && strpos($_SERVER['REQUEST_URI'],'edit') === false  ){
		 wp_enqueue_script('circle-donut-chart',get_stylesheet_directory_uri().'/libs/circle-dount-chart/circleDonutChart.js',array('jquery'),'',true);
		 wp_enqueue_script('common-profile',get_stylesheet_directory_uri().'/js/common-profile.js',array('circle-donut-chart'),'',true);
	}
	// echo "test777===";var_dump(bp_is_user_profile() );
}

add_filter('body_class','a21_my_class_names');
function a21_my_class_names( $classes ) {
	// добавим класс 'class-name' в массив классов $classes
	if( is_page("jobs") )
		$classes[] = ' wp-job-manager-categories-enabled wp-resume-manager-categories-enabled wp-job-manager wp-job-manager-resumes wp-job-manager-wc-paid-listings wp-job-manager-bookmarks wp-job-manager-tags ninjaforms-contact-resume-form wp-job-manager-contact-listing ';

	return $classes;
}

// /wp-content/themes/buddyapp-child/job_manager/wp-job-manager-tags/wp-job-manager-tags.php
if(class_exists('WP_Job_Manager')) include_once( 'job_manager/wp-job-manager-tags/wp-job-manager-tags.php' );

// /themes/buddyapp-child/job_manager/wp-job-manager-map/class-wp-job-manager.php
// if(class_exists('WP_Job_Manager')) include_once( 'job_manager/wp-job-manager-map/class-wp-job-manager.php' );
if($_GET['dev'] != 1) if(class_exists('WP_Job_Manager')) include_once( 'job_manager/wp-job-manager-map/class-wp-job-manager-map.php' );



add_action("wp_footer", "alex_custom_scripts",100);

function alex_custom_scripts()
{

	if( !bp_has_profile() ) return;

	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }

    $member_name = bp_core_get_username($user_id_isnotlogin);
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view = preg_match("#^/i-am/[a-z0-9_]+/profile/$#i", $url_s);

    // full path = http://dugoodr.com/i-ams/admin7/profile/

    // short path, insted activity set profile http://dugoodr.dev/i-am/admin7/
	$url_s = $_SERVER['REQUEST_URI'];

	$profile_view_notdefault = preg_match("#^/i-am/".$member_name."/$#i", $url_s);


	if($profile_view or $profile_view_notdefault){
		echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>';
		echo '<script type="text/javascript" src="'.get_stylesheet_directory_uri().'/libs/jqtimeliner/js/jquery-timeliner.js"></script>';
		echo '<script type="text/javascript" src="'.get_stylesheet_directory_uri().'/libs/jqtimeliner/js/ext_load_ajax.js"></script>';
	?>
	<script type="text/javascript">
		jQuery( document ).ready(function($) {
		    <?php echo as21_user_is_logged_id_manage_timeliner(); ?>

		    <?php if( !is_user_logged_in()):?>
				 $(document).scroll(function(){ 
				 	// console.log("fired scroll");
				 	// $("#timeliner .date_separator").hide();
	 			 });
		    <?php endif;?>

		});

	</script>
	<?php
	}
}

function as21_user_is_logged_id_manage_timeliner($echo = true){
	// get user_id for logged user
	$user = wp_get_current_user();
	$member_id = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$profile_id = $bp->displayed_user->id;
	$js = '';
	if($member_id < 1 or ($member_id != $profile_id) ){
		if($echo) $js = '$("#timeliner .btn-primary, #timeliner .btn-danger").remove(); $("#timeliner .alex_btn_add_new").hide();';
		else $js = true; // for use in js code
	}
	return $js;
}

add_action('wp_ajax_alex_del_timeline', 'alex_del_timeline');

function alex_del_timeline(){
	$id = trim( (int)$_POST['id']);
	if(!empty($id)){
		global $wpdb;
		$wpdb->delete( $wpdb->posts, array( 'ID' => $id ), array( '%d' ) ); 
		echo true;
	}
	exit;
}

// this code was take from buddyapp-child/buddypress/members/single/profile/profile-loop.php
function as21_get_all_limit_entrys_timeline($fields){

	// $html .= '--------step: as21_get_all_limit_entrys_timeline()------';
  	if( !empty($fields) ): foreach ($fields as $field):
  		global $wpdb;
		if( !empty($field->guid) ):
			$event = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bp_groups_calendars WHERE id = %d", (int)$field->guid ) );

			$get_event_image = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM " . $wpdb->base_prefix . "bp_groups_groupmeta
        	WHERE group_id=%d AND meta_key=%s LIMIT 1", (int)$event->id, 'a21_bgc_event_image') );

			$event_time = strtotime($event->event_time);
			$event_time = date("d M Y",$event_time);
			$group = groups_get_group(array( 'group_id' => $event->group_id ));
			$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
			$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );
			$gr_avatar = bp_core_fetch_avatar($avatar_options);


		      $html .= '<li>
		          <div class="timeliner_element is_event_thank_you">
		              <div class="timeliner_title">
		                  <span class="timeliner_label">'.stripslashes($event->event_title).'</span>
		                  <span class="timeliner_date">'.$event_time.'</span>
		              </div>
		              <div class="content">';
		              	    if( !empty($get_event_image) ) {
		              	    $html .= "<a href='".$group_permalink."/helpers/".$event->event_slug."' class='event_image' target='_blank'><img src='".$get_event_image."' /></a>";
		              	    $html .= "<p>".stripslashes($event->thank_you)."</p>";
		              	    }else $html .= stripslashes($event->thank_you);
		              	    
		       $html .='</div>
		              <div class="readmore">';
		              	  if($gr_avatar): $html .= '<div id="alex_gr_avatar">'.$gr_avatar.'</div>.'; endif;
			               if($group_permalink): $html .= '<div id="alex_gr_link">'.$group_permalink.'</div>'; endif;
			               if($group->name): $html .= '<div id="alex_gr_name_select">'.$group->name.'</div>'; endif;
			  $html .= '</div>
		          </div>
		      </li>';

		else:

			$group = groups_get_group($field->menu_order);
			$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
			$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );
			$gr_avatar = bp_core_fetch_avatar($avatar_options);

		    $html .= '
		    	<li>
		          <div class="timeliner_element ';
		           $html .= (!empty($field->post_name)) ? $field->post_name : "teal";
		          $html .= '">
		              <div class="timeliner_title">
		                  <span class="timeliner_label">'.stripslashes($field->post_title).'</span>
		                  <span class="timeliner_date">'.$field->post_excerpt.'</span>
		              </div>
		              <div class="content">'.stripslashes($field->post_content).'</div>
		              <div class="readmore">';
		              	  if($gr_avatar): $html .= '<div id="alex_gr_avatar">'.$gr_avatar.'</div>'; endif;
		              	  if($group_permalink): $html .= '<div id="alex_gr_link">'.$group_permalink.'</div>'; endif;
		              	  if($group->name):  $html .= '<div id="alex_gr_name_select">'.$group->name.'</div>'; endif;
		              	  if($group->id): $html .= '<div id="alex_gr_id_select">'.$group->id.'</div>'; endif;
		              	  $html .= '<span class="alex_item_id">'.$field->ID.'</span>
		              	  <span class="vol_hours">'.$field->comment_count.'</span>
		                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
		                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
		                  <a href="#" class="btn btn-info">
		                      Read More <i class="fa fa-arrow-circle-right"></i>
		                  </a>
		              </div>
		          </div>
		      </li>';
  		 endif;
  endforeach;
  	return $html;
  else:
  	return $html = false;
  endif;
  // do_action("a21_bgc_message_thankyou");  
}

add_action('wp_ajax_a21_load_part_timeline_data', 'a21_load_part_timeline_data');
add_action('wp_ajax_nopriv_a21_load_part_timeline_data', 'a21_load_part_timeline_data');

function a21_load_part_timeline_data() {

	// alex_debug(0,1,"",$_POST);
	$user_id = ( !empty($_POST['user_id']) ) ? (int)$_POST['user_id'] : '' ;
	$offset = ( !empty($_POST['offset']) ) ? (int)$_POST['offset'] : '' ;

	if( !empty( $user_id) && !empty( $offset) ){

		global $wpdb;
		$count_timeline = 5;

		$fields = $wpdb->get_results( $wpdb->prepare(
			"SELECT ID, post_title, post_content, post_excerpt,post_name,menu_order,guid,comment_count
			FROM {$wpdb->posts}
			WHERE post_parent = %d
			    AND post_type = %s
			ORDER BY post_date DESC LIMIT {$offset},{$count_timeline}",
			$user_id,
			"alex_timeline"
		) );
	     
	     /* **** as21 new**** */
	     $data['html'] = as21_get_all_limit_entrys_timeline($fields);
	     // var_dump($html);
	     // exit;
	     $data['manage_timeliner'] = as21_user_is_logged_id_manage_timeliner(false);
	     echo json_encode($data);
	}
	exit;
}


add_action('wp_ajax_alex_add_timeline', 'alex_add_timeline');

function alex_add_timeline() {

	$date = sanitize_text_field($_POST['date']);
	$title = sanitize_text_field($_POST['title']);
	$content = sanitize_text_field($_POST['content']);
	$class = sanitize_text_field($_POST['class']);
	$alex_tl_grp_id = (int)($_POST['alex_tl_grp_id']);
	$sort_date = date("Y-m-d",strtotime($date) ); // 2017-10-1 for sorting

	global $wpdb;
	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	$user = wp_get_current_user();
	$member_id = $user->ID;

	$wpdb->insert(
		$wpdb->posts,
		array( 'post_date'=>$sort_date ,'ID' => $last_post_id+1, 'post_title' => $title, 'post_name' => $class , 'post_content'=> $content, 'post_excerpt'=>$date, 'post_type' => 'alex_timeline', 'post_parent'=> $member_id, 'menu_order'=>$alex_tl_grp_id),
		array( '%s','%d','%s','%s','%s','%s','%s','%d','%d' )
	);
	$res = true;
	echo $res;
	exit;
}

add_action('wp_ajax_alex_edit_timeline', 'alex_edit_timeline');

function alex_edit_timeline() {

	$id = (int)($_POST['id']);
	$date = sanitize_text_field($_POST['date']);
	$title = sanitize_text_field($_POST['title']);
	$content = sanitize_text_field($_POST['content']);
	$class = sanitize_text_field($_POST['class']);
	$alex_tl_grp_id = (int)($_POST['alex_tl_grp_id']);
	$vol_hours = (int)($_POST['vol_hours']);
	if( preg_match('#none#i', strtolower($class)) ) $class = "";
	// alex_debug(0,1,"rrr",$_POST); exit;
	// echo $class."--"; exit;

	$sort_date = date("Y-m-d",strtotime($date) ); // 2017-10-1 for sorting

	if($id > 0){
		global $wpdb;
		$wpdb->update( $wpdb->posts,
			array( 'post_date' => $sort_date, 'post_title' => $title, 'post_name' => $class , 'post_content'=> $content, 'post_excerpt'=>$date,'menu_order'=>$alex_tl_grp_id,'comment_count'=>$vol_hours ),
			array( 'ID' => $id ),
			array( '%s','%s', '%s', '%s', '%s','%d','%d' ),
			array( '%d' )
		);
	}
	$res = true;
	echo $res;
	exit;
}

// delete jquery-migrate for correct work Responsive Dynamic Timeline Plugin For jQuery - Timeliner
add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );

function remove_jquery_migrate( &$scripts){
    // if(!is_admin()){
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ) );
    // }
}

/* Simple theme specific login form - OVERRIDE STANDARD FORM */
// add_shortcode( 'sq_login_form', 'sq_login_form_func' );
add_shortcode( 'alex_sq_login_form', 'alex_sq_login_form_func' );

/**
 * Return login form for shortcode
 * @param $atts
 * @param null $content
 * @return string
 */
function alex_sq_login_form_func( $atts, $content = null ) {

	if( !is_user_logged_in()){

		$output = $style = $disable_modal = '';

		extract( shortcode_atts( array(
				'style' => 'white',
				'before_input' => '',
				'disable_modal' => ''
		), $atts) );

		$output .= '<div class="login-page-wrap">';
		ob_start();
		kleo_get_template_part( 'page-parts/login-form', null, compact( 'style', 'before_input' ) );
		$output .= ob_get_clean();
		$output .= '</div>';

		if ( $disable_modal == '' ) {
			add_filter( "get_template_part_page-parts/login-form", '__return_false');
		}

		$output .= "<a class='show_home_form' href='#'>Sign in  /  Sign up</a>";

		add_action("wp_footer", "alex_custom_scripts_login_form",110);

		function alex_custom_scripts_login_form(){
			?>
			<script type="text/javascript">
				jQuery( document ).ready(function($) {
					$(".home-page .home_form_close").click(function(){
						$(".home-page .login-page-wrap").hide();
						$(".home-page .show_home_form").css({"display":"block"});
					});
					$(".home-page .show_home_form").click(function(){
						$(".home-page .login-page-wrap").show();
						$(".home-page .show_home_form").hide();
					});
				});
			</script>
			<?php
		}

		return $output;
	}
}

// add_action("bp_before_activity_loop","alex_custom_before_activity_loop");
function alex_custom_before_activity_loop(){
	alex_debug(1,0,0,0);
}


/* ****** adding a custom activity - compliment(review) - override method ajax_review() for BP Member Reviews ******* */

// add_action('wp_ajax_bp_user_review',   array($this, 'ajax_review'),300);
// add_action('wp_ajax_bp_user_review',   array("BP_Member_Reviews", 'ajax_review'),300);

add_action('wp_ajax_bp_user_review','ajax_review_override',1);
function ajax_review_override(){

if ( class_exists('BP_Member_Reviews') ){
	
    $user_id = intval($_POST['user_id']);
    if( !wp_verify_nonce( $_POST['_wpnonce'], 'bp-user-review-new-'.$user_id ) ) die();

    // alex code
	global $wpdb;
	$bp_member_r = new BP_Member_Reviews();

    $stars      = $bp_member_r->settings['stars'];
    $criterions = $bp_member_r->settings['criterions'];

    $post = array(
        'post_type'   => $bp_member_r->post_type,
        'post_status' => 'pending'
    );

    if($bp_member_r->settings['autoApprove'] == 'yes'){
        $post['post_status'] = 'publish';
    }

    $response = array(
        'result' => true,
        'errors' => array()
    );

    if( ! apply_filters( 'bp_members_reviews_review_allowed', true, get_current_user_id(), $user_id ) ){
        $response['result'] = false;
        $response['errors'][] = __('You can not put review for this user', 'bp-user-reviews');
    }

    if(is_user_logged_in() && (get_current_user_id() == $user_id)){
        $response['result'] = false;
        $response['errors'][] = __('You can not put yourself reviews', 'bp-user-reviews');
    }

    $review_meta = array(
        'user_id' => $user_id,
        'stars'   => $stars,
        'type'    => $bp_member_r->settings['criterion'],
        'guest'   => false
    );

    if( ! is_user_logged_in() ){
        $review_meta['guest'] = true;

        if(!isset($_POST['name']) || empty($_POST['name'])){
            $response['result'] = false;
            $response['errors'][] = __('Name field is required', 'bp-user-reviews');
        } else {
            $review_meta['name'] = esc_attr($_POST['name']);
        }

        if(!isset($_POST['email']) || empty($_POST['email'])){
            $response['result'] = false;
            $response['errors'][] = __('Email field is required', 'bp-user-reviews');
        } elseif (!is_email($_POST['email'])){
            $response['result'] = false;
            $response['errors'][] = __('Email is wrong', 'bp-user-reviews');
        } else {
            $review_meta['email'] = esc_attr($_POST['email']);
        }
    }

    if($bp_member_r->settings['multiple'] == 'no'){
        if(!is_user_logged_in()){
            if($bp_member_r->checkIfReviewExists($review_meta['email'], $user_id) > 0){
                $response['result'] = false;
                $response['errors'][] = __('Already reviewed by you', 'bp-user-reviews');
            }
        } else {
            if($bp_member_r->checkIfReviewExists(get_current_user_id(), $user_id) > 0){
                $response['result'] = false;
                $response['errors'][] = __('Already reviewed by you', 'bp-user-reviews');
            }
        }
    }

    if(!is_array($_POST['criteria'])){
        $val = esc_attr($_POST['criteria']);

        if($val < 1 || $val > $stars){
            $response['result'] = false;
            $response['errors']['empty'] = __('You must select all stars', 'bp-user-reviews');
        }

        $review_meta['average'] = ($val / $stars) * 100;
    } else {
        foreach($_POST['criteria'] as $index=>$val){
            if($val < 1 || $val > $stars){
                $response['result'] = false;
                $response['errors']['empty'] = __('You must select all stars', 'bp-user-reviews');
                continue;
            }

            $name = $criterions[$index];
            $review_meta['criterions'][$name] = (esc_attr($val) / $stars) * 100;
        }

        $review_meta['average'] = round( array_sum($review_meta['criterions']) / count($review_meta['criterions']) );
    }


    if($bp_member_r->settings['review'] == 'yes') {
        if (empty($_POST['review'])) {
            $response['result'] = false;
            $response['errors'][] = __('Review can`t be empty', 'bp-user-reviews');
        } elseif (mb_strlen($_POST['review']) < $bp_member_r->settings['min_length']) {
            $response['result'] = false;
            $response['errors'][] = sprintf(__('Review must be at least %s characters', 'bp-user-reviews'), $bp_member_r->settings['min_length']);
        } else {
            $review_meta['review'] = esc_attr($_POST['review']);
        }
    }

    if (class_exists('Akismet')){
        $review['user_ip']      = Akismet::get_ip_address();
        $review['blog']         = get_option( 'home' );
        $review['blog_lang']    = get_locale();
        $review['blog_charset'] = get_option('blog_charset');
        if(!is_user_logged_in()){
            $review['comment_author']       = $review_meta['name'];
            $review['comment_author_email'] = $review_meta['email'];
        } else {
            $user = get_userdata($user_id);
            $review['comment_author']       = $user->display_name;
            $review['comment_author_email'] = $user->user_email;
        }
        $review['comment_content'] = esc_attr($_POST['review']);

        $valid = Akismet::http_post( Akismet::build_query( $review ), 'comment-check' )[1];

        if($valid == false){
            $post['post_status'] = 'spam';
        }
    }

    if($response['result'] === true){
        $review_id = wp_insert_post($post);

        foreach($review_meta as $key=>$value){
            if(is_string($value)) $value = trim($value);
            update_post_meta($review_id, $key, $value);
        }
    }

	/* ****** adding a custom activity - compliment(review) ******* */

	$table_activity = $wpdb->prefix."bp_activity";
	$to_user_id = intval($_POST['user_id']);
	$user = wp_get_current_user();
	$from_user_id = $user->ID;

	$primary_link = bp_core_get_userlink($to_user_id);
	$user_link = bp_core_get_userlink($from_user_id);
	$to_user_link_nohtml = bp_core_get_userlink($to_user_id, false, true);
	$date_recorded = date( 'Y-m-d H:i:s');
	$action = $primary_link.' has received a <a href="'.$to_user_link_nohtml.'reviews/">compliment</a> from '.$user_link;

	$q = $wpdb->prepare( "INSERT INTO {$table_activity} (user_id, component, type, action, content, primary_link, date_recorded, item_id, secondary_item_id, hide_sitewide, is_spam ) VALUES ( %d, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d )", $to_user_id, 'compliments', 'compliment_sent', $action, '', $to_user_link_nohtml, $date_recorded, 0, 0, 0,0);

	$wpdb->query( $q );	
	/* ****** adding a custom activity - compliment(review) ******* */
    wp_send_json($response);
    die();
	}
}

function register_widgets_for_groups_pages(){
	register_sidebar( array(
		'name' => "Groups sidebar",
		'id' => 'right-sidebar-for-group',
		'description' => 'Right sidebar for widgets',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
		'before_widget' =>  '<div id="%1$s" class="widget %2$s">',
		'after_widget' =>  '</div>',
	) );
}
add_action( 'widgets_init', 'register_widgets_for_groups_pages' );

function register_widgets_for_member_pages(){
	register_sidebar( array(
		'name' => "Member sidebar",
		'id' => 'right-sidebar-for-member',
		'description' => 'Right sidebar for widgets',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
		'before_widget' =>  '<div id="%1$s" class="widget %2$s">',
		'after_widget' =>  '</div>',
	) );
}
add_action( 'widgets_init', 'register_widgets_for_member_pages' );


/* for work with bp group revies on header group */
remove_action( 'bp_group_header_meta', 'bpgr_render_review' );

add_action("bp_group_header_meta","alex_add_rating_header_group");
function alex_add_rating_header_group(){

	if(class_exists('BP_Group_Reviews')){
		global $bp;

		// Don't show for groups that have reviews turned off
		if ( !BP_Group_Reviews::current_group_is_available() )
			return;

		// Rendering the full span so you can avoid editing your group-header.php template
		// If you don't like it you can call bpgr_review_html() yourself and unhook this function ;)

		$gid = bp_get_group_id();
		$group = groups_get_group($gid);
		$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
		$rating_score  = isset( $bp->groups->current_group->rating_avg_score ) ? $bp->groups->current_group->rating_avg_score : '';
		$rating_number = isset( $bp->groups->current_group->rating_number ) ? $bp->groups->current_group->rating_number : '';
	
		global $wpdb;
		$website = $wpdb->get_results( $wpdb->prepare(
			"SELECT post_content
			FROM {$wpdb->posts}
			WHERE post_parent = %d
			    AND post_type = %s
			    AND post_title = %s
			ORDER BY ID ASC",
			intval( $gid ),
			// "alex_gfilds",
			"alex_grsoclink",
			'Website'
		) );
		$ext_url = $website[0]->post_content;
		?>
		<span class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
	    <span itemprop="ratingValue"  content="<?php echo $rating_score;?>"></span>
	    <span itemprop="bestRating"   content="5"></span>
	    <span itemprop="ratingCount"  content="<?php echo $rating_number;?>"></span>
	    <span itemprop="itemReviewed" content="Group"></span>
	    <span itemprop="name" content="<?php echo $group->slug;?>"></span>
	    <span itemprop="url" content="<?php echo $group_permalink;?>"></span>
	    <?php if( !empty($ext_url) ):?>
	    <span itemprop="sameAs" content="<?php echo $ext_url;?>"></span>
		<?php endif;?>
		<?php echo bpgr_review_html(); ?>
		</span>
	<?php
	}
}

// for schema.org on google (there was 1 error - missing url breadcrumb)
// unhoock old function
function alex_remove_junk() { remove_action( 'bp_before_group_body','kleo_bp_group_title', 1 ); }
add_action( 'after_setup_theme', 'alex_remove_junk', 999 );

add_action( 'bp_before_group_body','alex_kleo_bp_group_title',10);
function alex_kleo_bp_group_title() {
?>
    <div class="bp-title-section">
        <h1 class="bp-page-title"><?php echo kleo_bp_get_group_page_title();?></h1>
        <?php 
	        $gid = bp_get_group_id();
			$group = groups_get_group($gid);
			$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
	        $breadcrumb = kleo_breadcrumb( array( 'container' => 'ol', 'separator' => '', 'show_browse' => false, 'echo' => false ) );
	        echo $bc_replace = str_replace('a href=""', 'a href="'.$group_permalink.'"', $breadcrumb);
         ?>
    </div>
<?php
}

/* for work with bp group revies on header group -110 */


add_action('wp_footer',"group_pages_scroll_to_anchor",999);
function group_pages_scroll_to_anchor(){
	// echo $d = "<div id='alex-s'>dddddddd</div>";
	// var_dump(bp_is_groups_component());
	// if page related group
	if( bp_is_groups_component() && !bp_is_user() ) {
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {
	    	var scroll = (jQuery('#item-nav').offset().top)-410;
	    	// jQuery(document.body).scrollTop(scroll);
		    	jQuery(document.body).scrollTop(scroll);
	    	  	// window.scrollTo(0,1000);
	    	// console.log("width groups: "+jQuery(window).width());
	    });
		</script>
		<?
	}
	if( bp_is_user() ) {
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {
	    	if( jQuery(window).width() < 768 ){
		    	var scroll = (jQuery('#item-nav').offset().top)-110;
		    	// jQuery(document.body).scrollTop(scroll);
		    	setTimeout(function(){ 
			    	jQuery(document.body).scrollTop(scroll);
		    	  	// window.scrollTo(0,1000);
		    	}, 50);
	    	}
	    	// console.log("width members: "+jQuery(window).width());
	    });
		</script>
		<?
	}
}


add_action('wp_footer',"highlight_group_interest_links_on_profile_member");
function highlight_group_interest_links_on_profile_member(){
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {

	    	var link = jQuery(".profile .bp-widget a");

			// возвращает cookie с именем name, если есть, если нет, то undefined
			function getCookie(name) {
			  var matches = document.cookie.match(new RegExp(
			    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			  ));
			  return matches ? decodeURIComponent(matches[1]) : undefined;
			}

			link.each(function( i,item) {
				var cur_link = jQuery(this).text();
				if( getCookie( cur_link ) == 1) { jQuery(this).css({"color":"#ca0532"}); }
			});

	    	jQuery(link).click(function(e){
	    		// e.preventDefault();
	    		var cur_link = jQuery(this);
				// time no set,to delete cookie if close browser
				document.cookie = cur_link.text()+"=1; path=/;";
	    	});
	    });
		</script>
		<?php
}

/* ********** Load modules ******** */

$kleo_modules = array(
    'new_facebook-login.php'
);

$kleo_modules = apply_filters( 'kleo_modules', $kleo_modules );
// var_dump($kleo_modules);
// var_dump(KLEO_LIB_DIR);
// var_dump(THEME_DIR);
// echo trailingslashit(get_stylesheet_directory_uri());
// /* Sets the path to the theme library folder. */
// define( 'KLEO_LIB_DIR', trailingslashit( THEME_DIR ) . 'lib' );
// get absolute path /home/jetfire/www/dugoodr.dev/wp-content/themes/buddyapp
 $theme_url = get_template_directory()."-child/";
 // /home/jetfire/www/dugoodr.dev/wp-content/themes/buddyapp/kleo-framework/lib/function-core.php
 include_once get_template_directory()."/kleo-framework/lib/function-core.php";
// exit;

// if (sq_option( 'facebook_login', false ) ) {
    // add_action('kleo_after_body', 'kleo_fb_head');
    // add_action('login_head', 'kleo_fb_head');
    // add_action('login_head', 'kleo_fb_loginform_script');
// }


function alex_remove_junk2() { 
    remove_action('wp_footer', 'kleo_fb_footer');
    remove_action('login_footer', 'kleo_fb_footer');
 }
add_action( 'after_setup_theme', 'alex_remove_junk2', 999 );

foreach ( $kleo_modules as $module ) {
    $file_path = $theme_url. 'lib/modules/' . $module;
    include_once $file_path;
}

/* ********** Load modules ******** */


function as21_get_cover_image_from_db_for_fb( $only_image=false ){

	global $wpdb,$bp;

	// if( bp_is_members_component() ) $user_id = bp_get_member_user_id();
	if( bp_is_user() ) $user_id = $bp->displayed_user->id;
	else $user_id = bp_get_member_user_id();
    // array( 'user_id' => $user_ID, 'meta_key'=>'_afbdata', 'meta_value'=>$ser_fb_data),
    // var_dump($user_id);
    if( !empty( $user_id) ){
		$table = $wpdb->prefix."usermeta";
		$get_fb_data = $wpdb->get_results( $wpdb->prepare(
			"SELECT meta_value
			FROM {$table}
			WHERE user_id = %d
			    AND meta_key = %s",
			intval( $user_id ),
			"_afbdata"
		) );
		if( !empty($get_fb_data[0]->meta_value) ) { 
			$cover_url = unserialize($get_fb_data[0]->meta_value); 
			// return $cover_url['cover']; 
			if( !empty($cover_url['cover']) && $only_image ) return $cover_url['cover']; 
			if( !empty($cover_url['cover']) ) return 'class="item-cover has-cover" style="background:url('. $cover_url['cover'].') no-repeat center center;background-size:cover;"';
			else return ' class="item-cover" ';
		}else return ' class="item-cover" ';
	}
}

// add_action("bp_after_member_home_content",'get_cover_image_from_fbuser');

add_action("bp_before_member_header",'get_cover_image_from_fbuser');
function get_cover_image_from_fbuser(){

	// $cover_url = get_cover_image_from_db();
	global $bp;
	$user_id = $bp->displayed_user->id;
	// kleo_bp_get_member_cover_attr($user_id); echo 'as21_777 ------------'; var_dump($cover_image);
	if(kleo_bp_get_member_cover_attr($user_id) == 'class="item-cover"') $cover_url = trim(as21_get_cover_image_from_db_for_fb(true));
	// else echo kleo_bp_get_member_cover_attr();

	// $cover_url = trim(as21_get_cover_image_from_db_for_fb(true));
	// var_dump($cover_url);
	if( !empty($cover_url) && $cover_url != 'class="item-cover"' ){
	?>
	<script type="text/javascript">
		var e = document.getElementById("header-cover-image");
		e.style.background = "url(<?php echo $cover_url;?>) no-repeat center center";
	</script>
	<?php
	}
}


/* ************ DW actions ************ */

add_filter('bp_get_send_public_message_button', '__return_false');

function remove_wp_adminbar_profile_link() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('my-account-activity-favorites');
}
add_action( 'wp_before_admin_bar_render', 'remove_wp_adminbar_profile_link' );
add_filter( 'bp_activity_can_favorite', '__return_false' );
add_filter( 'bp_get_total_favorite_count_for_user', '__return_false' );

/* **************** */

function a_redirect_if_changed_group_page() {

	global $bp;
	$bp->members->slug; // выводит- members
	// выводит например- new_members...это page_name  (если компонент ассоциировали с нестандартной страницей в http://dugoodr.dev/wp-admin/admin.php?page=bp-page-settings)
	$root_slug = $bp->members->root_slug;
	$uri = $_SERVER['REQUEST_URI'];  // /i-am/admin7/groups/
	$has_members_slug = preg_match("/{$root_slug}/i", $uri);
	$has_groups_slug = preg_match("/groups/i", $uri);

	if($has_members_slug && $has_groups_slug) {
		get_template_part("404");
		exit;
	}
}
add_action( 'kleo_header', 'a_redirect_if_changed_group_page', 999 );


add_action("bp_after_directory_members","a_show_groups_search_result_on_members");
// add_action("bp_after_members_loop","a_show_groups_search_result_on_members");
function a_show_groups_search_result_on_members(){

	global $groups_template;
	$search_string = esc_html($_GET['s']);

	// $groups = groups_get_groups(array('search_terms' => $search_string, 'per_page' => 100, 'populate_extras' => false));
	// alex_debug(0,1,"all gr",$groups);
	// echo "total gr=".$count_groups = $groups['total'];

	// var_dump( bp_has_groups(bp_ajax_querystring( 'groups' )."&search_terms=".$search_string."&update_admin_cache=true&per_page=3" ) );
	// alex_debug(0,1,"gr_temp", $groups_template); // work only call bp_has_groups

	// echo 'grs_q='.$grs_query = bp_has_groups( bp_ajax_querystring( 'groups' )."&search_terms=".$search_string."&update_admin_cache=true&per_page=50" );
	// var_dump( bp_has_groups("&search_terms=".$search_string."&per_page=2" ) );
	// $groups_template->current_group = -1; // for some reason by default set 0,though in original set -1,shows the correct count of groups

	// echo '<br><h3>начало цикла группы</h3>';	
	// bp_get_template_part( 'groups/groups-loop-on-member-dir' );
	// echo '<br><h3>конец цикла группы</h3>';

	if( !empty($search_string) ):?>
		<form action="" method="post" id="groups-directory-form" class="dir-form">
			<div id="groups-dir-list" class="groups dir-list">
			<?php bp_get_template_part( 'groups/groups-loop-on-member-dir' );?>
			</div>
		</form>
	<?php endif;
}


// add_action("bp_after_groups_loop","alex_t02");
// function alex_t02(){
// 	global $groups_template;
// 	echo "groups_template";
//  	alex_debug(1,1,"g ",$groups_template);
//  	$group_type = bp_get_current_group_directory_type();
//  	var_dump($group_type);
// }

require_once 'libs/twitter/main_functions.php';

add_action("wp_footer","a21_js_for_only_group_right_sidebar");
function a21_js_for_only_group_right_sidebar(){
	?>
	<script>
	// only-for-group-sidebar
	function calc_sidebar(){
		if(jQuery(window).width()>751){
			// console.log("window:"+jQuery(window).width());
			var header_h = jQuery(".groups #item-header-wrap").height();
			var sidebar_h = jQuery(".groups .only-for-group-sidebar");
			// console.log(header_h);
			jQuery(".groups .only-for-group-sidebar").css({"top":(header_h)});
			jQuery(".groups #item-body").css({"min-height":(sidebar_h.height()+30)});
			// console.log(sidebar_h.height());
		}else{ 	jQuery(".groups #item-body").css({"min-height":""});}
	}
	calc_sidebar();
	jQuery(window).resize(function(){ calc_sidebar();});
	</script>
	<?php
}

require_once 'libs/theme_func/quick_edit_timeline.php';


add_action('after_setup_theme', function(){
	register_nav_menus( array(
		'job_menu' => 'Top Job Header Menu'
	) );
});



add_action( 'wp_enqueue_scripts', 'a21_kleo_frontend_files',999 );
function a21_kleo_frontend_files(){
    // wp_deregister_script("kleo-app");
    // wp_deregister_script("kleo-plugins");


}
add_action( 'wp_enqueue_scripts', 'a21_kleo_frontend_files2',1 );
function a21_kleo_frontend_files2(){
    wp_enqueue_script( 'google-maps', "https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry%2Cplaces&language=en&key=AIzaSyAJOQtVZMrDEIVLt8uNBIJbMNou5LkzT-c" );
	// wp_enqueue_script("jquery-ui-core",array("jquery"));
	// wp_enqueue_script("jquery-ui-widget",array("jquery"));
	// wp_enqueue_script("jquery-ui-mouse",array("jquery"));
	// wp_enqueue_script("jquery-ui-slider",array("jquery"));
}

/* **** as21  extension for wp-job-manager **** */

function as21_output_space($length_start = 4, $real_str){

	$length_real_str = strlen($real_str);
	if($length_real_str < $length_start) {
		$length_add_str = $length_start - $length_real_str;
		$add_char = '';
		for($i=0; $i<$length_add_str;$i++){ $add_char .= " "; }
		return $add_char;
	}
}

// permissin should be is least 666 for write
function as21_wjm_write_file_jobs_count($filename,$text){

	chmod($filename, 0777);
	$fp = fopen($filename, "w"); 
	$write = fwrite($fp, $text); 
	// var_dump($write);
	fclose($fp); 
	// echo "\r\n as21_wjm_write_file_jobs_count";
}

function as21_wjm_get_display_count_plus_by_group_id($group_id){
	$filename = AS21_PATH_JOBS_COUNT_TXT;
	if( file_exists($filename)) {

		// on hosting immediately convert valid array
		$file = file($filename); 
		// if($_GET['dev']==1) { alex_debug(0,1,'file',$file);}
		
		 /* //need for correctly work on localhost
		 $file = explode("\r", $file[0]); */
		 if( !isset($file[1]) ) $file = explode("\r", $file[0]);

		// if($_GET['dev']==1) { alex_debug(0,1,'file2',$file);}
		foreach ($file as $k => $v) {
			if($k == 0) continue;
			$line = explode("|", $v); 
			$f_group_id = $line[0];
			$dcp = $line[3];
			// if($_GET['dev']==1)  alex_debug(0,1,'',$line);
			if($f_group_id == $group_id) { /* echo $f_group_id.'-'.$dcp."<br>"; */ break; }
		}
		// if($_GET['dev']==1) { alex_debug(0,1,'file3',$file); echo "; dcp--------".$dcp; }
		return $dcp;
		// return $dipsplay_count_plus = $dipsplay_count_plus[1];
		// echo 'as21_jobs_get_display_count_plus_txt ';
	}

}

function as21_wjm_get_all_display_count_plus(){
	$filename = AS21_PATH_JOBS_COUNT_TXT;
	if( file_exists($filename)) {

		// [0] - one string,sometime valid array
		// $file = file($filename,FILE_IGNORE_NEW_LINES); 
		$file = file($filename); 
		// if file[0] as string
		if( !isset($file[1]) ) $file = explode("\r", $file[0]);

		// $dipsplay_count_plus = explode("|", $file[0]);
		// alex_debug(0,1,'file',$file);
		// if($_GET['dev']==1) { alex_debug(0,1,'file2',$file);}

		foreach ($file as $k => $v) {
			if($k == 0) continue;
			$line = explode("|", $v); 
			$dcps[trim($line[0])] = $line[3];
			// alex_debug(0,1,'',$line);
		}
		// if($_GET['dev']==1) { alex_debug(0,1,'dcps',$dcps); exit;}
		// return $dipsplay_count_plus = $dipsplay_count_plus[1];
		// echo 'as21_jobs_get_display_count_plus_txt ';
		return $dcps;
	}

}

function as21_wjm_write_file_all_groups($dcp = false){

	$filename = AS21_PATH_JOBS_COUNT_TXT;
	if( file_exists($filename)) {

		/* *** get all public (not hidden) groups and write in file **** */
		$groups = BP_Groups_Group::get(array('type'=>'alphabetical'));
		// alex_debug(0,1,'',$groups);
		// if($dcp) { $dcp_val = as21_jobs_get_display_count_plus_txt(); $text = "Displayed Count Plus | ".$dcp_val."\r"; }
		// else $text = "Displayed Count Plus | \r";
		$text = "id".as21_output_space(5,'id')."| group name".as21_output_space(55,'group name')."| real count".as21_output_space(14,'real count')."| total count \r";
		if($dcp) $dcps = as21_wjm_get_all_display_count_plus();
		$i = 1;
		// echo count($groups['groups']);
		foreach ($groups['groups'] as $group) {
			$length_gr_id = as21_output_space(5, $group->id);
			$length_gr = as21_output_space(55, $group->name);
			$length_jobs_count = as21_output_space(14, as21_get_jobs_count_current_group($group->id));
			
			$text .= $group->id.$length_gr_id.'| '.$group->name.$length_gr."| ".as21_get_jobs_count_current_group($group->id).$length_jobs_count."|"; 
			if($dcp && isset($dcps[$group->id]) ) $text .= $dcps[$group->id];
			if( count($groups['groups']) != $i) $text .=  "\r";
			$i++;
		}
		// if($_GET['dev']==1) { alex_debug(0,1,'dcps',$dcps); echo $text; exit('---------alfjlkdf----'); }
		as21_wjm_write_file_jobs_count($filename,$text);
		// echo "\r\n DEBUG: end work as21_wjm_write_file_all_groups! ".$text;
	}

}

function as21_get_jobs_count_current_group($group_id = false){

	global $bp,$wpdb;
	if(!$group_id) $group_id = (int)$bp->groups->current_group->id;
	else $group_id = (int)$group_id;
	$ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_job_group_a21' AND meta_value='$group_id' ");
	// var_dump($ids);
	$jobs_count_gr = (count($ids)>0) ? count($ids) : 0 ;
	return $jobs_count_gr;
}

// work on page site.com/i-am/?s=ottawa and page site.com/causes/
function as21_wjm_get_manually_jobs_count_by_group_id($group_id = false){
	global $bp,$wpdb;
	$group_id = (!$group_id) ? (int)$bp->groups->current_group->id : $group_id;
	$jobs_total_count_gr = (int)as21_wjm_get_display_count_plus_by_group_id($group_id);
	// if($_GET['dev']==1) { var_dump($jobs_total_count_gr); echo "gr_id= ".$group_id."; "; }
	if( empty($jobs_total_count_gr)) $jobs_total_count_gr = as21_get_jobs_count_current_group($group_id);
	// echo bp_get_group_id();
	return $jobs_total_count_gr;
}


if ( class_exists( 'BP_Group_Extension' ) ) :
	class a21_job_nav_tab_in_group extends BP_Group_Extension {
	
			function __construct() {
				/*
				global $bp,$wpdb;
				$group_id = (int)$bp->groups->current_group->id;
				$jobs_total_count_gr = (int)as21_wjm_get_display_count_plus_by_group_id($group_id);
				// if($_GET['dev']==1) var_dump($jobs_total_count_gr);
				if( empty($jobs_total_count_gr)) $jobs_total_count_gr = as21_get_jobs_count_current_group();
				// if($_GET['dev']==1) var_dump($jobs_total_count_gr);
				*/
				$args = array(
					'slug' => 'a21-jobs',
					// 'name' => 'Jobs <span>'.$jobs_count_gr.'</span>',
					'name' => 'Jobs <span>'.as21_wjm_get_manually_jobs_count_by_group_id().'</span>',
					'nav_item_position' => 105,
					);
				parent::init( $args );
			}
		}
	
		bp_register_group_extension( 'a21_job_nav_tab_in_group' );
		
endif;

/* **** as21  extension for wp-job-manager **** */

add_action("bp_group_options_nav",'as21_add_group_new_nav_link');
function as21_add_group_new_nav_link(){

	global $bp,$wpdb;
	$group_id = $bp->groups->current_group->id;
	// $group = groups_get_group($group_id);
	$job_board_link = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}bp_groups_groupmeta WHERE group_id = %d AND meta_key = %s",
		intval( $group_id ),"job_board_link"
	) );

	if(!empty($job_board_link)) echo '<li id="job-board-groups-li"><a href="'.$job_board_link.'" target="_blank">JobBoard Link</a></li>';
}


if(class_exists("WP_Job_Manager_Field_Editor")) require_once 'job_manager/wp-job-manager-groups/index.php';

// override only due add image property 
if(class_exists('BP_Member_Reviews')){

	global $BP_Member_Reviews;
	// alex_debug(1,1,"BP_Member_Reviews",$BP_Member_Reviews);

	remove_action('bp_profile_header_meta', array($BP_Member_Reviews, 'embed_rating'));

	add_action("bp_profile_header_meta","a21_override_bp_mr_embed_rating");

	/*
	function a21_override_bp_mr_embed_rating(){
		global $BP_Member_Reviews, $bp;
	    $user_id = bp_displayed_user_id();
        $BP_Member_Reviews->calc_rating($user_id);
        $rating = get_user_meta($user_id, 'bp-user-reviews', true);
       
        $user_avatar = bp_core_fetch_avatar( array('item_id'=>$user_id, 'html'=>false));
		?>
		<div class="bp-users-reviews-stars" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		    <span itemprop="ratingValue"  content="<?php echo $rating['result']; ?>"></span>
		    <span itemprop="bestRating"   content="100"></span>
		    <span itemprop="ratingCount"  content="<?php echo $rating['count']; ?>"></span>
		    <span itemprop="itemReviewed" content="Person"></span>
		    <span itemprop="name" content="<?php echo $BP_Member_Reviews->get_username($user_id); ?>"></span>
		    <!--<span itemprop="url" content="<?php echo $BP_Member_Reviews->get_user_link($user_id); ?>"></span>-->
		    <span itemprop="url" content="<?php echo $BP_Member_Reviews->get_user_link($user_id); ?>reviews/"></span>
		    <?php // override only due add image property ?>
		    <span itemprop="image" content="<?php echo $user_avatar; ?>"></span>
		    <?php echo $BP_Member_Reviews->print_stars($BP_Member_Reviews->settings['stars']); ?>
		    <div class="active" style="width:<?php echo $rating['result']; ?>%">
		        <?php echo $BP_Member_Reviews->print_stars($BP_Member_Reviews->settings['stars']); ?>
		    </div>
		</div>
		<?
	}
	*/
	
	function a21_override_bp_mr_embed_rating(){
		global $BP_Member_Reviews, $bp;
		// echo "a21 new_html========";
	    $user_id = bp_displayed_user_id();
        $BP_Member_Reviews->calc_rating($user_id);
        $rating = get_user_meta($user_id, 'bp-user-reviews', true);
       
        $user_avatar = bp_core_fetch_avatar( array('item_id'=>$user_id, 'html'=>false));
		?>
		<div class="bp-users-reviews-stars">
			<a href="<?php echo $BP_Member_Reviews->get_user_link($user_id); ?>reviews/">
		    <span   content="<?php echo $rating['result']; ?>"></span>
		    <span  content="100"></span>
		    <span content="<?php echo $rating['count']; ?>"></span>
		    <span content="Person"></span>
		    <span content="<?php echo $BP_Member_Reviews->get_username($user_id); ?>"></span>
		    <!--<span itemprop="url" content="<?php echo $BP_Member_Reviews->get_user_link($user_id); ?>"></span>-->
		    <span  content="<?php echo $BP_Member_Reviews->get_user_link($user_id); ?>reviews/"></span>
		    <?php // override only due add image property ?>
		    <span content="<?php echo $user_avatar; ?>"></span>

		    <?php echo $BP_Member_Reviews->print_stars($BP_Member_Reviews->settings['stars']); ?>
		    <div class="active" style="width:<?php echo $rating['result']; ?>%">
		        <?php echo $BP_Member_Reviews->print_stars($BP_Member_Reviews->settings['stars']); ?>
		    </div>
		    </a>
		</div>

		<?
	}
	
	add_action("wp_head","as21_add_microdata");
	function as21_add_microdata(){
		global $BP_Member_Reviews, $bp,$wpdb;
	    $user_id = bp_displayed_user_id();
        $BP_Member_Reviews->calc_rating($user_id);
        $rating = get_user_meta($user_id, 'bp-user-reviews', true);
        $user_avatar = bp_core_fetch_avatar( array('item_id'=>$user_id, 'html'=>false));
        $r = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key='user_id' AND meta_value='{$user_id}' ");
        $first_review = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='review' AND post_id='".$r[0]->post_id."' ");
        // $r = get_post_meta($user_id, 'bp-user-reviews', true);
        // deb_last_query();
        // echo $first_review;
        // alex_debug(0,1,"dddddd",$r); exit;
        // alex_debug(0,1,"",$BP_Member_Reviews);
        // var_dump($BP_Member_Reviews->calc_rating($user_id ));
        // var_dump( $BP_Member_Reviews->calc_stars( $rating['result'],$rating['count'] ));
        $rating_5 = ceil( $rating['result']*$BP_Member_Reviews->settings['stars']/100 );

        $url =  $_SERVER['REQUEST_URI'];
        $is_profile = strpos($url, 'i-am') ;
        $is_page_reviews = strpos($url, 'reviews');
        // echo "======777";  var_dump(bp_is_user_profile());
        // if ( (bool)$is_profile !== false && (bool)$is_page_reviews === false ){
        if(bp_is_user_profile()){
				// if( strpos($url,'awesome') !== false) echo '<meta name="description" content="Todd has done amazing things as an Ottawa-based volunteer. Always available to contribute to a community event that helps the">';
        	if(!empty($first_review)) echo '<meta name="description" content="'.substr($first_review,0,97)."...".'">';
			?>
			<script type="application/ld+json">
			{
			  "@context": "http://schema.org",
			  "@type": "AggregateRating",
			  "name": "<?php echo bp_core_get_user_displayname($user_id);?>",
			  "ratingCount": <?php echo $rating['count']; ?>,
			  "image": {
			  "@type": "ImageObject",
			  "name": "Image",
			  "url": "<?php echo $user_avatar; ?>",
			  "height": "50",
			  "width": "50"
			  },
			  "bestRating": "5",
			  "ratingValue": "<?php echo $rating_5;?>",
			  "itemReviewed": {
			    "@type": "Thing",
			    "name": "Person"
			  }
			}
			</script>
		    <!--<script async src="https://cdn.ampproject.org/v0.js"></script>-->
   		 <?php
   		}
	}

}

add_filter('breadcrumb_trail_args', "as21_disable_rich_snippet");
function as21_disable_rich_snippet($args){
	$args['rich_snippet']=false;
	// alex_debug(0,1,"",$args);
	// exit;
	return $args;
}

add_filter('post_class',"a21_css_class");
function a21_css_class($classes){
	// alex_debug(1,1,"",$classes);
	foreach($classes as $k=>$v){ if($v == "hentry") unset($classes[$k]); }
	// alex_debug(1,1,"",$classes);
	// exit;
	return $classes;
}

// without hook,for reused code
function alex_get_postid_and_fields( $wpdb = false){

	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	$fields  = array("Website","Facebook","Twitter","Instagram","Google+","Linkedin");
	// $fields  = array("Website","Facebook","Twitter","Instagram","Youtube","Linkedin");
	$id = $last_post_id+1;
	$id_and_fields = array($id,$fields);
	return $id_and_fields;
}

add_action( 'groups_group_create_complete',"as21_delete_cookies_for_group_soclinks" );
function as21_delete_cookies_for_group_soclinks(){
	// echo "=====8787=====";
	// not exist google
	// alex_debug(0,1,'',$_COOKIE);
	foreach ($_COOKIE as $k => $v) {
		if( strpos($k,'alex-') !== false) {
			setcookie( $k, false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
		}
	}
	// alex_debug(0,1,'',$_COOKIE);
	// exit;
}

// labels for top nav (groups,members) parts is buddyapp-child/page-parts/header-top.php override top nav
add_filter( 'nav_menu_link_attributes', 'as21_1',1 );
function as21_1($atts){

if( strtolower( $atts['title']) != 'jobs') { 
		$atts['data-title']=$atts['title']; 
		$atts['class'] = 'as21-link-label';
		unset($atts['title']); 
  }
  return $atts;
}

function as21_get_total_volunteer_hours_count_member($user_id = false){
	global $bp,$wpdb;
	$quest_id = (!$user_id) ? $quest_id = $bp->displayed_user->id : $user_id;
	// $total_estimate_hours = xprofile_get_field(57, $quest_id);
	// alex_debug(0,1,'',$total_estimate_hours);
	// $experience_total_hours = (!empty($total_estimate_hours->data->value)) ? $total_estimate_hours->data->value : 0 ;
	$experience_total_hours = $wpdb->get_var($wpdb->prepare("SELECT SUM(menu_order) FROM {$wpdb->posts} WHERE post_author = %d  AND post_type = %s ",(int)$quest_id,"experience_volunteer"));
	// var_dump($experience_total_hours);exit;
	$experience_total_hours = (!empty($experience_total_hours)) ? $experience_total_hours : 0 ;
	$total_hours_every_entry = $wpdb->get_var($wpdb->prepare("SELECT SUM(comment_count) FROM {$wpdb->posts} WHERE post_parent = %d  AND post_type = %s ",(int)$quest_id,"alex_timeline"));
	return $total_hours = $experience_total_hours+$total_hours_every_entry;
}

add_action('bp_directory_members_actions','as21_get_total_hours_for_member_cards');
function as21_get_total_hours_for_member_cards(){
	echo '<div class="meta">'.as21_get_total_volunteer_hours_count_member(bp_get_member_user_id() ).' Hours</div>';
}

// Profile Fields tab 4.Experience : total estimate hours and Experience deleted form dashboard
add_action( 'xprofile_profile_field_data_updated','a21_profile_edit_save_changes_experience');
function a21_profile_edit_save_changes_experience(){
	// alex_debug(1,1,'post',$_POST);
	global $bp,$wpdb;
	$user_id = $bp->displayed_user->id;

	if( !empty($_POST['as21_new_experiences']) ){

		foreach ($_POST['as21_new_experiences'] as $k => $v) {
			if( !empty( $v['title']) ) $val .= $wpdb->prepare("(%d,%s,%s,%d),",(int)$user_id, sanitize_text_field($v['title']), 'experience_volunteer', (int)$v['hours']);
		}
		$val = substr($val, 0,-1);
		$insert_query = "INSERT INTO $wpdb->posts (post_author, post_title, post_type, menu_order) VALUES {$val}";
		// echo $insert_query;
		$wpdb->query($insert_query);

		// deb_last_query();
	}
	unset($_POST['as21_new_experiences']);

	if( !empty( $_POST['as21_experiences']) ){

		foreach ($_POST['as21_experiences'] as $k => $v) {
			$exper_id = (int)$v['exper_id'];
			if($exper_id>0){
				$menu_order .= $wpdb->prepare("WHEN %d THEN %s ",$exper_id, (int)$v['hours']);
				$post_title .= $wpdb->prepare("WHEN %d THEN %s ",$exper_id, sanitize_text_field($v['title']));
				$post_id .= $exper_id.",";
			}
		}
		// echo "as21_exper =========".$post_author;
		// exit;
		if( !empty($post_id) ){
			$post_id = substr($post_id, 0,-1);
			// echo $post_title;
			$update_query = "UPDATE $wpdb->posts SET
					    post_title = CASE id {$post_title} END,
					    menu_order = CASE id {$menu_order} END WHERE id IN({$post_id})";
			// echo $update_query."<hr>";
		   $wpdb->query($update_query);
		   // deb_last_query();
		}
	}
	 // exit;
}
// post_title | menu_order	| 	post_author | post_tye
// name_exper |exper_hours |user_id        | experience_volunteer
// при выводе удалить слэш

add_action('wp_ajax_as21_experience_del', 'as21_experience_del');

function as21_experience_del(){
	$id = (!empty($_POST['id'])) ? (int)$_POST['id'] : false;
	echo $id;
	if($id>0){
		global $wpdb;
		$wpdb->delete( $wpdb->posts, array( 'ID' => $id ), array( '%d' ) ); 
		// deb_last_query();
	}
	exit;
}

function as21_get_all_experience_from_page_edit_profile(){
	global $bp,$wpdb;
	$quest_id = $bp->displayed_user->id;

	$fields = $wpdb->get_results( $wpdb->prepare(
		"SELECT ID,post_title,menu_order
		FROM {$wpdb->posts}
		WHERE post_author = %d
		    AND post_type = %s
		ORDER BY ID",
		intval( $quest_id ),
		'experience_volunteer'
	) );
	// alex_debug(1,1,'',$fields);
	// alex_debug(0,1,'post',$_POST);
	return $fields;
}

/* **** as21  tooltips for new user on profile page**** */

add_action('wp_enqueue_scripts','a21_include_wp_pointer_css');
function a21_include_wp_pointer_css(){
	
	if( bp_is_user_profile()) {
		wp_enqueue_style( 'wp-pointer');
		// wp_enqueue_style( 'toolt', get_stylesheet_directory_uri().'/libs/tooltipify.css');
	   // wp_enqueue_script('a21_tooltipify',get_stylesheet_directory_uri().'/libs/jquery-tooltipify.js',array('jquery'));
	   // /home/jetfire/www/dugoodr2.dev/wp-content/themes/buddyapp-child/libs/jquery-tooltipify.js
	}
}

add_action('wp_ajax_as21_dismiss_tooltip', 'as21_dismiss_tooltip');
// add_action('wp_ajax_nopriv_as21_dismiss_tooltip', 'as21_dismiss_tooltip');
function as21_dismiss_tooltip(){
	// print_r($_POST);
	// echo '--------php handler wp-ajax';
	$id_user = (int)$_POST['id_user'];
	$id_target = $_POST['id_target'] ? sanitize_text_field($_POST['id_target']) : false;
	if($id_user > 0 && !empty($id_target)){
		// echo ' id_user+id_target exist!';
		global $wpdb;
		$wpdb->insert(
			$wpdb->postmeta,
			array( 'post_id' => $id_user, 'meta_key' => 'as21_tooltips_profile', 'meta_value'=> $id_target),
			array( '%d','%s','%s' )
		);
		// deb_last_query();
		// echo 'success';
	}
	exit;
}
// require_once 'libs/frontend-profile-tooltips.php';

/* **** as21  tooltips for new user on profile page**** */

add_action('wp_ajax_as21_dismiss_all_tooltips', 'as21_dismiss_all_tooltips');
// add_action('wp_ajax_nopriv_as21_dismiss_tooltip', 'as21_dismiss_tooltip');
function as21_dismiss_all_tooltips(){
	$id_user = (int)$_POST['id_user'];
	if($id_user > 0){
		global $wpdb;
		$wpdb->insert(
			$wpdb->postmeta,
			array( 'post_id' => $id_user, 'meta_key' => 'as21_all_tooltips_profile', 'meta_value'=> 1),
			array( '%d','%s','%d' )
		);
		// deb_last_query();
	}
	exit;
}
// require_once 'libs/frontend-profile-tooltips.php';

/* **** as21  tooltips for new user on profile page**** */


remove_action('wp_head','_wp_render_title_tag',1);
add_action('wp_head','_wp_render_title_tag2',1);
function _wp_render_title_tag2() {
	if ( ! current_theme_supports( 'title-tag' ) ) return;
	echo '<title>' . wp_get_document_title() . ' ('.as21_get_total_volunteer_hours_count_member().' hours)</title>' . "\n";
}

require_once 'debug_functions.php';