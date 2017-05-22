<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* ***** function for work with page editing profile field timeline ***** */

// add_action( 'xprofile_screen_edit_profile','a21_just_test1',999 );
add_action( 'xprofile_profile_field_data_updated','a21_profile_edit_save_changes_timeline');
function a21_profile_edit_save_changes_timeline(){

	// get all name,id,order fields xprofile group
	$data_groups = BP_XProfile_Group::get( $args );
	foreach ($data_groups as $k => $v) {
		if( preg_match("#timeline#i",strtolower($v->name)) ) $xprofile_group_id = $v->id;
	}

	if( $xprofile_group_id ==bp_get_current_profile_group_id() ) {
		if(!empty($_POST) && check_admin_referer( 'bp_xprofile_edit' )){
			global $wpdb;
			// alex_debug(1,1,'POST',$_POST); exit;
			// var_dump(        check_admin_referer( 'bp_xprofile_edit' ) );
			$i =1;
			if( !empty($_POST['data']) ){
				foreach ($_POST['data'] as $v) {
					$post_title .= $wpdb->prepare("WHEN %d THEN %s ",(int)$v['timel_id'],sanitize_text_field($v['timel_title']));
					$post_content .= $wpdb->prepare("WHEN %d THEN %s ",(int)$v['timel_id'],sanitize_text_field($v['timel_content']));
					$post_date .= $wpdb->prepare("WHEN %d THEN %s ",(int)$v['timel_id'],sanitize_text_field($v['timel_date']));
					$post_name .= $wpdb->prepare("WHEN %d THEN %s ",(int)$v['timel_id'],sanitize_text_field($v['timel_class']));
					$post_id .= (int)$v['timel_id'].",";
				}
				$post_id = substr($post_id, 0,-1);
				// echo $post_title;
				$update_query = "UPDATE $wpdb->posts SET
						    post_title = CASE id {$post_title} END,
						    post_content = CASE id {$post_content} END,
						    post_excerpt = CASE id {$post_date} END,
						    post_name = CASE id {$post_name} END WHERE id IN({$post_id})";
				// echo $update_query."<hr>";
			   $wpdb->query($update_query);
			   // deb_last_query(); exit;
			}

			// INSERT INTO tbl_name (a,b,c) VALUES(1,2,3),(4,5,6),(7,8,9);
		   if( !empty($_POST['new_data']) ){
				$user = wp_get_current_user();
				$member_id = $user->ID;
				foreach ($_POST['new_data'] as $item) {
			   		$sort_date = date("Y-m-d",strtotime(sanitize_text_field($item['timel_date'])) ); // 2017-10-1 for sorting
			   		// echo "----".sanitize_text_field($item['timel_date']); echo $sort_date;  exit;
					$val .= $wpdb->prepare("(%s,%s,%s,%s,%s,%d, %s),", $sort_date, sanitize_text_field($item['timel_title']), sanitize_text_field($item['timel_content']),sanitize_text_field($item['timel_date']), sanitize_text_field($item['timel_class']), (int)$member_id, "alex_timeline");
				}
				$val = substr($val, 0,-1);
				$insert_query = "INSERT INTO $wpdb->posts (post_date,post_title,post_content,post_excerpt,post_name,post_parent,post_type) VALUES {$val}";
				// echo $insert_query;
				$wpdb->query($insert_query);
				// deb_last_query();exit;
		   }
			// echo '<hr><br>';
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
			// exit;
		}
	}
}
