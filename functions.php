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
return 'DuGoodr Scout';
}



/* ********** alex code ********** */ 
/* ********** code for member and groups not related,only styles and icons ********** */ 
/* ********** for page members ********** */ 

//Social Media Icons based on the profile user info
function member_social_extend(){
		// $dmember_id = $bp->displayed_user->id;
		$user = wp_get_current_user();
		$dmember_id = $user->ID;

		$fb_info = xprofile_get_field_data('Facebook Profile', $dmember_id);
		$google_info = xprofile_get_field_data('Google+', $dmember_id);
		$instagram_info = xprofile_get_field_data('Instagram', $dmember_id);
		$twitter_info = xprofile_get_field_data('Twitter', $dmember_id);
		$linkedin_info = xprofile_get_field_data('LinkedIn Profile', $dmember_id);
		echo '<div class="member-social">';
		// echo 'test';
		// if($fb_info||$google_info||$twitch_info||$twitter_info){
		// 	echo 'My Social: ';
		// }

		if ($fb_info) {
		?>
		<span class="fb-info">
		<?php
		// $img = '<img src="'.bloginfo('wpurl').'/wp-content/themes/buddyapp-child/images/f.png" />';
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/fb.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $fb_info); ?>
		</span>
	<?php
	}
		?>
		<?php
		if ($google_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/google+.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $google_info);
		  ?>
		</span>
	<?php
	}
		?>
		<?php
		if ($instagram_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/instagram.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $instagram_info);
		  ?>
		</span>
	<?php
	}
	?>
	<?php
		if ($twitter_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/twitter.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $twitter_info);
		  ?>
		</span>
	<?php
	}
	?>
	<?php
		if ($linkedin_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/linkedin.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $linkedin_info);
		  ?>
		</span>
	<?php
	}
	echo '</div>';
}
add_filter( 'bp_before_member_header_meta', 'member_social_extend' ); 

/* ********** soclinks for page groups ********** */ 

function alex_display_social_groups() {

	global $wpdb;

	// echo "gr_alex";

	// $group_id = 1;
	$gid = bp_get_group_id();
	$fields = $wpdb->get_results( $wpdb->prepare(
		"SELECT ID, post_title, post_content
		FROM {$wpdb->posts}
		WHERE post_parent = %d
		    AND post_type = %s
		ORDER BY ID ASC",
		intval( $gid ),
		"alex_gfilds"
	) );

	if(!empty($fields)) echo "<div class='wrap_soclinks'>";

	foreach ($fields as $field) {

        if(!empty($field->post_content)) $data = trim($field->post_content); 
        else $data = false;

        if( !empty($data) ){
        	// echo "data= ".$data;

        	switch ($field->post_title) {
        		case 'Facebook':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/fb.png" />';
        			break;  		
        		case 'Google+':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/google+.png" />';
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
}

add_action( 'bp_before_group_header_meta', 'alex_display_social_groups');

function alex_edit_group_fields(){

	global $bp,$wpdb;

	// info about all groups
	$groups = groups_get_groups();
	// print_r($groups);
	// echo "<br> group id: ";
	// foreach ($groups['groups'] as $gr) {
	// 	echo $gr->id.", ";
	// }
	// echo "<br>";
	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	$gid = $bp->groups->current_group->id;
	// var_dump($a);
	$fields = $wpdb->get_results( $wpdb->prepare(
		"SELECT ID, post_title, post_content, post_excerpt
		FROM {$wpdb->posts}
		WHERE post_parent = %d
		    AND post_type = %s
		ORDER BY ID ASC",
		intval( $gid ),
		"alex_gfilds"
	) );
	// echo "<pre>";
	// print_r($fields);
	// echo "</pre>";
	foreach ($fields as $field) {

		echo '<label class="" for="alex-'.$field->ID.'">'.$field->post_title.'</label>';
		echo '<input id="alex-'.$field->ID.'" name="alex-'.$field->ID.'" type="text" value="' . esc_attr( $field->post_content ) . '" />';
		// echo '<p class="description">Enter url</p>';
	}

}

// display all fields on page manage->details
add_action( 'groups_custom_group_fields_editable', 'alex_edit_group_fields');

function alex_edit_group_fields_save(){

		global $wpdb;
		// echo 'save...add to db<br>';
		// print_r($_POST);
		// exit;
		
			foreach ( $_POST as $data => $value ) {
				if ( substr( $data, 0, 5 ) === 'alex-' ) {
					$to_save[ $data ] = $value;
				}
			}
		// print_r($to_save);
		// exit;
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
}
add_action( 'groups_group_details_edited', 'alex_edit_group_fields_save' );

// without hook,for reused code
function alex_get_postid_and_fields( $wpdb = false){

	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	$fields  = array("Facebook", "Twitter","Instagram","Google+","Linkedin");
	$id = $last_post_id+1;
	$id_and_fields = array($id,$fields);

	return $id_and_fields;

}

function alex_add_soclinks_all_groups_db(){

	global $wpdb;
	// $last_post_id = $wpdb->query( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	// echo $sql = "SELECT * FROM `{$wpdb->posts}` WHERE ID=1";
	// $q = $wpdb->get_results( $sql);
	//var_dump($wpdb->posts);
	// print_r($q);  $wpdb->
	// echo "<br>";
	// var_dump($last_post_id);

	$groups = groups_get_groups();
	// print_r($groups);
	$k = 0;
	foreach ($groups['groups'] as $gr) {
		// echo $gr->id;s
		$gid[$k] = $gr->id;
		$k++;
	}

	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	$postid = $postid_and_fields[0]+1;
	$fields = $postid_and_fields[1];

	// $last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	// $fields  = ["Facebook", "Twitter","Instagram","Google+","Linkedin"];
	$g = 0;
	$total_group = count($gid);
	for( $i=0; $i < $total_group; $i++){
		foreach ($fields as $field_name) {
			$wpdb->insert(
				$wpdb->posts,
				array( 'ID' => $postid, 'post_title' => $field_name, 'post_type' => 'alex_gfilds', 'post_parent'=>$gid[$g]),
				array( '%d','%s','%s','%d' )
			);
			$postid++; 
		} 
		$g++;
	}
	// echo "<script>console.log('Fields for groups has been successfully imported! Total group: ".$total_group."');</script>";
	echo "Fields for groups has been successfully imported! Total group: ".$total_group;
}

// execute only 1 time !!! add all fields for groups in db
// add_action("wp_head","alex_add_soclinks_all_groups_db");

// Fires after the group has been successfully created.
add_action( 'groups_group_create_complete', "alex_case_creation_new_group" );

function alex_case_creation_new_group(){
	global $wpdb,$bp;

	$gid = $bp->groups->new_group_id;
	// echo 'add new group '.$gid;
	// exit;
	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	// $postid = $postid_and_fields[0];
	$fields = $postid_and_fields[1];
	foreach ($fields as $field_name) {
		$wpdb->insert(
			$wpdb->posts,
			array( 'ID' => $postid, 'post_title' => $field_name, 'post_type' => 'alex_gfilds', 'post_parent'=>$gid),
			array( '%d','%s','%s','%d' )
		);
		$postid++; 
	}
}


function buddyapp_search_shortcode() {
    $context = sq_option( 'search_context', '' );
    echo kleo_search_form(array('context' => $context));
}

// add_shortcode('buddyapp_search_shortcode','buddyapp_search_shortcode');
// use [buddyapp_search_shortcode]




/* ****** modification searchbox and signin/register form for landing page ******* */

// [vc_row el_class="alex-search-wrap"][vc_column][vc_column_text][buddyapp_search_shortcode]

// [alex_search_form]
// [/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_column_text]
// [sq_login_form before_input="User: <strong>demo</strong>   Password: <strong>demo</strong>"]
// [/vc_column_text][/vc_column][/vc_row]

// add_shortcode( 'kleo_search_form', 'kleo_search_form' );
add_shortcode( 'alex_search_form', 'alex_search_form' );
function alex_search_form( $atts = array(), $content = null ) {
	
	// print_r($_REQUEST);
	// echo "bp_active-".function_exists( 'bp_is_active' );
	// echo "<br>";

	$form_style = $type = $placeholder = $context = $hidden = $el_class = '';
	
	extract(shortcode_atts(array(
		'form_style' => 'default',
		// 'form_style' => 'groups',
		'type' => 'both',
		// 'context' => '',
		'context' => array('groups','members'),
		'action' => home_url( '/' )."members",
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

	// if ( function_exists('bp_is_active') && $context == 'members' ) {
	// 	//Buddypress members form link
	// 	$action = bp_get_members_directory_permalink();

	// } elseif ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) && $context == 'groups' ) {
	// 	//Buddypress group directory link
	// 	$action = bp_get_groups_directory_permalink();

	// } 
	// <h4>Testing intagration ajax_search</h4>

	$output = '<div class="search">
				<i> </i>
	<div class="s-bar">
	<form id="' . $el_id . '" class="' . $el_class . ' second-menu" method="get" ' . ( $search_page == 'no' ? ' onsubmit="return false;"' : '' ) . ' action="' . $action . '" data-context="' . $context  .'">';
	$output .= '<input id="' . $input_id . '" class="' . $input_class . ' ajax_s" autocomplete="off" type="text" name="' . $input_name . '" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Search\';}" value="Find your cause...">';
	 // value="" placeholder="' . $input_placeholder . '">';
	// $output .= '<button type="submit" class="' . $button_class . '"></button>';
	$output .= '<input type="submit" class="' . $button_class . '" value="Search" />';
	if ( $ajax_results == 'yes' ) {
		$output .= '<div class="kleo_ajax_results search-style-' . $form_style . '"></div>';
	}
	$output .= $hidden;
	// $output .= '</form>'.$ajax_cont.'
	$output .= '</form>
	</div>
	</div>';

	return $output;
}


add_shortcode( 'alex_nothome_search_form', 'alex_nothome_search_form' );
function alex_nothome_search_form( $atts = array(), $content = null ) {

	$form_style = $type = $placeholder = $context = $hidden = $el_class = '';
	extract(shortcode_atts(array(
		'form_style' => 'default',
		'type' => 'both',
		'context' => array('groups','members'),
		'action' => home_url( '/' )."members",
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
	// alex_debug(1,0,'s',$search);
    if ( ! empty( $search ) and ($search == 'search') ) {
	    // $query_string .= '&search_terms=admin';
	    // $query_string .= '&search_terms=cdean';
	    $query_string .= '&search_terms=';
	    $query_string .= '&user_ids=1,2,3,4,5,6,7,8,9.10,11,12,13,14,15,16,17,18,19,20';
    }
 
    return $query_string;
}
add_action( 'bp_legacy_theme_ajax_querystring', 'my_bp_loop_querystring', 100, 2 );

/* вывод системных данных в форматированном виде */
function alex_debug ( $show_text = false, $is_arr = false, $title = false, $var, $sep = "| "){

	// Example: alex_debug(1,0,'s',$search);
	$debug_text = "<br>========Debug MODE==========<br>";
	if( boolval($show_text) ) echo $debug_text;
	if( boolval($is_arr) ){
		echo $title."-";
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		echo "<hr>";
	} else echo $title."-".$var;
	if($sep == "l") echo "<hr>"; else echo $sep;
}
/* вывод системных данных в форматированном виде */


add_action("wp_head","alex_include_css_js",90);

function alex_include_css_js(){
	if( is_front_page() ){
		echo '<link href="'.get_stylesheet_directory_uri().'/search-templ/css/style2.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/search-templ/css/style.css" rel="stylesheet" type="text/css" media="all"/>';
	}

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

    // echo "profile= "; var_dump(bp_has_profile());

	// echo "--output alex code--\r\n";
	// /members/admin7/profile/edit/group/1/
	// if it is profile view page
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view = preg_match("#^/members/[a-z0-9_]+/profile/$#i", $url_s);

    // full path = http://dugoodr.com/members/admin7/profile/
    // short path, insted activity set profile http://dugoodr.dev/members/admin7/
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/members/".$member_name."/$#i", $url_s);

	// http://dugoodr.dev/members/admin7/

	// if(bp_has_profile() ){
	if($profile_view or $profile_view_notdefault){

		/* *** disable standart wordpress style ***** */

		function alex_dequeue_default_css() {
		  wp_dequeue_style('bootstrap');
		  wp_deregister_style('bootstrap');
		}
		add_action('wp_enqueue_scripts','alex_dequeue_default_css',100);

		/* *** disable standart wordpress style ***** */

		echo '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">';
		echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/libs/jqtimeliner/css/jquery-timeliner.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/libs/alex/fix-style.css" rel="stylesheet" type="text/css" media="all"/>';
	}
}


add_action("wp_footer", "alex_custom_scripts",100);

function alex_custom_scripts()
{

	if( !bp_has_profile() ) return;

	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	// print_r($bp);
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }

    // $member_name = bp_core_get_username($user_id_islogin);
    $member_name = bp_core_get_username($user_id_isnotlogin);
    // echo "profile= "; var_dump(bp_has_profile());

	// echo "--output alex code--\r\n";
	// /members/admin7/profile/edit/group/1/
	// if it is profile view page
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view = preg_match("#^/members/[a-z0-9_]+/profile/$#i", $url_s);

    // full path = http://dugoodr.com/members/admin7/profile/
    // short path, insted activity set profile http://dugoodr.dev/members/admin7/
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/members/".$member_name."/$#i", $url_s);

	// http://dugoodr.dev/members/admin7/

	// if(bp_has_profile() ){
	if($profile_view or $profile_view_notdefault){
		// echo '<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
		// echo '<script src="'.get_stylesheet_directory_uri().'/js/common.js"></script>';
		echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>';
		echo '<script type="text/javascript" src="'.get_stylesheet_directory_uri().'/libs/jqtimeliner/js/jquery-timeliner.js"></script>';
	?>
	<script type="text/javascript">
    	// jQuery(function(){
    	// 	var tl = jQuery('#timeliner').timeliner();
    	// });

		// jQuery( document ).ready(function() {
		//     var tl = jQuery('#timeliner').timeliner({
		//     	spineTpl:"ddddddddddd"
		//     });
		// });

		jQuery( document ).ready(function($) {

			function alex_onadd(_data){

					// console.log('alex onadd');
					// console.log(_data);
					// console.log(grs);
					var alex_tl_grp_id = false;
				    for (var key in grs) {
				    	if(grs[key] == _data.alex_gr_name_select) alex_tl_grp_id = key;
				    }
				    // console.log("id= "+alex_tl_grp_id);
					// return false;

					// var total_ul = $( "#timeliner ul").length;
					// console.log("t_u"+total_ul);
					// $( "#timeliner ul" ).each(function( index ) {
					//   // console.log( index + ": " + $( this ).text() );
					//   console.log(this);
					//   // may 2016
					//   var sec_date = $(this).prev().text();
					//   // cur_a 01 Nov 2017 sec_d=January 2017
					//   console.log('cur_a'+_data.date+' sec_d='+sec_date);
					//  var add_month = sec_date.slice(0,3);
					//  var add_year = sec_date.slice(-4);
					//  var add_date = add_month+add_year;
					//  console.log(add_date);
				 //  	var cur_year = _data.date.slice(-4);
					// var cur_month = _data.date.slice(3,6);
					//  var cur_date = cur_month+cur_year;
					//  console.log(cur_date);
					//  if(cur_date == add_date) {
					//  	$(this).append(cur_date);
					//  	console.log('equal');
					//  	var match_date = true;
					//  }
					//  else console.log('no_equal');
					//  console.log(index);

					//  if(index == total_ul-1 && !match_date) { 
					//  	var html = '<ul><li>'+cur_date+'</li></ul>';$(".timeliner ul").eq(1).prepend(html); }
					//  // undefined
					//  console.log("m_d=".match_date+" t_u="+total_ul);


					// });
					// return false;

					// $("#timeliner form").hide();
					// $("#timeliner form").parent().parent().hide();
					// $("#timeliner").on("submit",".form",function(){
					// 	alert('yes');
					// 	console.log('on--');
					// });


					var data = {
						'action': 'alex_add_timeline',
						'date': _data.date,
						'title': _data.title,
						'content': _data.content,
						'class': _data.class,
						'alex_tl_grp_id': alex_tl_grp_id
						// 'query': true_posts,
					};

					$.ajax({
						url:ajaxurl, // обработчик
						data:data, // данные
						type:'POST', // тип запроса
						success:function(data){
							// console.log("ajax response get success-add!");
							if( data ) { 
								// console.log(data);

								// var html = '<li>\
							  //         <div class="timeliner_element teal">\
							  //             <div class="timeliner_title">\
							  //                 <span class="timeliner_label">Event Title</span><span class="timeliner_date">03 Nov 2014</span>\
							  //             </div>\
							  //             <div class="content">after ajax request\
							  //             </div>\
							  //             <div class="readmore">\
							  //                 <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>\
							  //                 <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>\
							  //                 <a href="#" class="btn btn-info">\
							  //                     Read More <i class="fa fa-arrow-circle-right"></i>\
							  //                 </a>\
							  //             </div>\
							  //         </div>\
							  //     </li>';
						      // $("#timeliner ul:nth-child(2)").append(html);
						      // $("#timeliner").append(html);
						      // self.add(_item).render();
						      location.reload();
							} else { console.log("data send with errors!");}
						}

					 });
			}

			function alex_ondelete(_data){

				console.log("alex_ondelete");

		        if(confirm("Are you sure to delete ?")){
    				// console.log("confirm");
    				// console.log(_data);
    				// id:"111111111-13-jan-2017"
    				// console.log(_data.$html);
    				// console.log(_data.id);
    				// console.log($(this));
    				// var del = $("#qqqqqq-01-jan-2017-delete-btn").text();
    				// console.log("del el "+del);
    				$( "#timeliner" ).on( "click", ".readmore .btn-danger", function() {
					   // console.log('Dynamic!');
					   var html = $(this).parents("li");
					   var id = html.find(".alex_item_id").text();
					   // console.log("id="+id);
					   // console.log(html.html());
					   html.hide();

	   					var data = {
							'action': 'alex_del_timeline',
							'id':id
						};

						$.ajax({
							url:ajaxurl, // обработчик
							data:data, // данные
							type:'POST', // тип запроса
							success:function(data){
								// console.log("ajax response get success!");
								if( data ) { 
									// console.log(data);
								} else { console.log("data send with errors!");}
							}

						 });
						// end ajax
					});
		        }
			}

			function alex_onedit(_data){

			   // console.log("alex_ondedit");
			   // console.log(_data);
			   // console.log(html.html());

				// $( "#timeliner" ).on( "submit", ".btn-primary", function() {
				// 	console.log("btn-primary");
				// });
				// return false;
				var alex_tl_grp_id = false;
			    for (var key in grs) {
			    	if(grs[key] == _data.alex_gr_name_select) alex_tl_grp_id = key;
			    }

				var data = {
					'action': 'alex_edit_timeline',
					'id': _data.id_alex,
					'date': _data.date,
					'title': _data.title,
					'content': _data.content,
					'class': _data.class,
					'alex_tl_grp_id': alex_tl_grp_id

				};

				$.ajax({
					url:ajaxurl, // обработчик
					data:data, // данные
					type:'POST', // тип запроса
					success:function(data){
						console.log("ajax response get success!");
						if( data ) { 
							// console.log(data);
				      		location.reload();  		
						} else { console.log("data send with errors!");}
					}

				 });
				// end ajax
			    // $("#dddddddddd-edit-12-jan-2017-edit-frm").render();
    		    // jQuery('#timeliner').timeliner({onAdd:alex_onadd, onDelete:alex_ondelete, onEdit:alex_onedit});

			}

		    var tl = jQuery('#timeliner').timeliner({onAdd:alex_onadd, onDelete:alex_ondelete, onEdit:alex_onedit});

		    <?php
		    	// get user_id for logged user
		 		$user = wp_get_current_user();
				$member_id = $user->ID;
				// get user_id for notlogged user
				global $bp;
				$profile_id = $bp->displayed_user->id;

				if($member_id < 1 or ($member_id != $profile_id) ){
					// echo '$("#timeliner .btn-primary, #timeliner .btn-danger").hide();';
					echo '$("#timeliner .btn-primary, #timeliner .btn-danger").remove();';
					echo '$("#timeliner .alex_btn_add_new").hide();';
				}
		    ?>

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

add_action('wp_ajax_alex_del_timeline', 'alex_del_timeline');

function alex_del_timeline(){
	// $date = sanitize_text_field($_POST['date']);
	$id = trim( (int)$_POST['id']);
	if(!empty($id)){
		global $wpdb;
		$wpdb->delete( $wpdb->posts, array( 'ID' => $id ), array( '%d' ) ); 
		// echo $id;
		echo true;
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

	global $wpdb;
	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	// echo "==debug==<br>";
	$user = wp_get_current_user();
	$member_id = $user->ID;

	$wpdb->insert(
		$wpdb->posts,
		array( 'ID' => $last_post_id+1, 'post_title' => $title, 'post_name' => $class , 'post_content'=> $content, 'post_excerpt'=>$date, 'post_type' => 'alex_timeline', 'post_parent'=> $member_id, 'menu_order'=>$alex_tl_grp_id),
		array( '%d','%s','%s','%s','%s','%s','%d','%d' )
	);

	// $res = "==".$title.$date.$content.$class;
	$res = true;
	// $res = "user id=".$member_id;
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


	if($id > 0){
		global $wpdb;
		$wpdb->update( $wpdb->posts,
			array( 'post_title' => $title, 'post_name' => $class , 'post_content'=> $content, 'post_excerpt'=>$date,'menu_order'=>$alex_tl_grp_id ),
			array( 'ID' => $id ),
			array( '%s', '%s', '%s', '%s','%d' ),
			array( '%d' )
		);
	}
	// $res = "==".$title.$date.$content.$class;
	$res = true;
	echo $res;
	exit;
}

// add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
function my_scripts_method() {
	// wp_enqueue_script('datepi', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js' );
	// wp_enqueue_script('timel', get_stylesheet_directory_uri().'/libs/jqtimeliner/js/jquery-timeliner.js', array('jquery') );
	// wp_enqueue_script('timel', get_stylesheet_directory_uri().'/libs/jqtimeliner/js/jquery-timeliner.js' );
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

		$output .= "<a class='show_home_form' href='#'>Sign in</a>";

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

	// $test['test'] = "yes";
	// $test['class'] = $m->url;
	// $response['result'] = true;
	// $response['post'] = $_POST;
	// $response['post']['table'] = $wpdb->prefix."bp_activity";

	$table_activity = $wpdb->prefix."bp_activity";
	$to_user_id = intval($_POST['user_id']);
	$user = wp_get_current_user();
	$from_user_id = $user->ID;

	// $primary_link = bp_loggedin_user_domain();
	$primary_link = bp_core_get_userlink($to_user_id);
	$user_link = bp_core_get_userlink($from_user_id);
	$to_user_link_nohtml = bp_core_get_userlink($to_user_id, false, true);
	$date_recorded = date( 'Y-m-d H:i:s');
	$action = $primary_link.' has received a <a href="'.$to_user_link_nohtml.'reviews/">compliment</a> from '.$user_link;

	$q = $wpdb->prepare( "INSERT INTO {$table_activity} (user_id, component, type, action, content, primary_link, date_recorded, item_id, secondary_item_id, hide_sitewide, is_spam ) VALUES ( %d, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d )", $to_user_id, 'compliments', 'compliment_sent', $action, '', $to_user_link_nohtml, $date_recorded, 0, 0, 0,0);
	// INSERT INTO wp8k_bp_activity ( user_id, component, type, action, content, primary_link, date_recorded, item_id, secondary_item_id, hide_sitewide, is_spam ) VALUES ( 0, 'compliments', 'compliment_sent', '<a href=\"http://dugoodr2.dev/members/admin7/\" title=\"Admin\">Admin</a> has received a <a href=\"http://dugoodr2.dev/members/toddroberts/reviews/\">compliment</a> from <a href=\"http://dugoodr2.dev/members/toddroberts/\" title=\"Todd2\">Todd2</a>', '', '<a href=\"http://dugoodr2.dev/members/toddroberts/\" title=\"Todd2\">Todd2</a>', '2017-01-26 17:32:14', 0, 0, 0, 0 )

	$wpdb->query( $q );
	// if ( false === $wpdb->query( $q ) ) {
	// 	$response['result'] = false;
	// 	$response['post']['error'] = "error send sql";
	// 	$response['errors']['empty'] = "error send sql";
	// }

	// $response['post']['q'] = $q;
	// wp_send_json($response);
	// die();
	
	/* ****** adding a custom activity - compliment(review) ******* */

    wp_send_json($response);
    die();
	}
}

// only for debug
// add_action("wp_footer","wp_get_name_page_template");

function wp_get_name_page_template(){

    global $template,$bp;
 //    global $groups_template;
	// alex_debug(0,1,"groups_template",$groups_template);

    // echo basename($template);
    // полный путь с названием шаблона страницы
    // print_r($bp);

	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }

    // $member_name = bp_core_get_username($user_id_islogin);
    // echo "profile= "; var_dump(bp_has_profile());

    // full path = http://dugoodr.com/members/admin7/profile/
    // short path, insted activity set profile http://dugoodr.dev/members/admin7/
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/members/".$member_name."/$#i", $url_s);

	echo "has page profile= "; var_dump(bp_has_profile());

    echo "1- ".$template;
	echo "<br>2- ".$page_template = get_page_template_slug( get_queried_object_id() )." | ";
	// echo $template = get_post_meta( $post->ID, '_wp_page_template', true );
	// echo $template = get_post_meta( get_queried_object_id(), '_wp_page_template', true );
	// echo "id= ".get_queried_object_id();
	echo "<br>3- ".$_SERVER['PHP_SELF'];
	echo "<br>4- ".__FILE__;
	echo "<br>5- ".$_SERVER["SCRIPT_NAME"];
	echo "<br>6- ".$_SERVER['DOCUMENT_ROOT'];
	alex_debug(1,1,0,$_SERVER);
}


// add_filter( 'pre_user_login', function( $user )
// {
//     // var_dump( current_filter()." works fine" );
//     var_dump($user);
//     return $user;

// } );

// if (! is_admin()) {
//     // alex code
//     echo "<h1>test alex!</h1>";
// }


/* ************ additonal actions TEMP ************ */


// учесть при создании новой группы добавить 5 полей для него (прицепиться к хуку создание группы)

// $s = "INSERT INTO `wp8k_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (NULL, '1', '2016-12-21 22:26:53', '2016-12-21 22:26:53', 'Test Group Test facebook', 'Facebook', 'text', 'publish', 'closed', 'closed', '', 'facebook', '', '', '2016-12-21 22:26:53', '2016-12-21 22:26:53', '', '1', 'http://dugoodr.dev/?bpge_gfields=hello-world/facebook', '0', 'bpge_gfields', '', '0')";


// function glob_func(){
// 	echo "------global func----";
// }

// add_action("wp_head", "alex_111");

// function alex_111(){
// 	glob_func();
// }


// add_action("bp_head", 'alex_test_function');
// add_action( 'bp_before_group_body','alex_test_function');
// add_action( 'bp_after_group_body','alex_test_function');
// add_filter( 'bp_group_admin_form_action','alex_test_function');

/* ************ additonal actions ************ */


/* ************ DW actions ************ */

add_filter('bp_get_send_public_message_button', '__return_false');

function remove_wp_adminbar_profile_link() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('my-account-activity-favorites');
}
add_action( 'wp_before_admin_bar_render', 'remove_wp_adminbar_profile_link' );
add_filter( 'bp_activity_can_favorite', '__return_false' );
add_filter( 'bp_get_total_favorite_count_for_user', '__return_false' );
