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
			// alex_debug(1,1,'POST',$_POST);
			// var_dump(        check_admin_referer( 'bp_xprofile_edit' ) );
			$i =1;
			if( !empty($_POST['data']) ){
				foreach ($_POST['data'] as $v) {
					$post_title .= $wpdb->prepare("WHEN %d THEN %s ",(int)$v['timel_id'],sanitize_text_field($v['timel_title']));
					$post_content .= $wpdb->prepare("WHEN %d THEN %s ",(int)$v['timel_id'],sanitize_text_field($v['timel_content']));
					$post_date .= $wpdb->prepare("WHEN %d THEN %s ",(int)$v['timel_id'],sanitize_text_field($v['timel_date']));
				}
				// echo $post_title;
				$update_query = "UPDATE $wpdb->posts SET
						    post_title = CASE id {$post_title} ELSE '' END,
						    post_content = CASE id {$post_content} ELSE '' END,
						    post_excerpt = CASE id {$post_date} ELSE '' END";
				// echo $update_query."<hr>";
			   $wpdb->query($update_query);
			}

			// INSERT INTO tbl_name (a,b,c) VALUES(1,2,3),(4,5,6),(7,8,9);
		   if( !empty($_POST['new_data']) ){
				$user = wp_get_current_user();
				$member_id = $user->ID;
				foreach ($_POST['new_data'] as $item) {
					$val .= $wpdb->prepare("(%s,%s,%s,%d,%s),",sanitize_text_field($item['timel_title']),sanitize_text_field($item['timel_content']),sanitize_text_field($item['timel_date']),(int)$member_id,"alex_timeline");
				}
				$val = substr($val, 0,-1);
				$insert_query = "INSERT INTO $wpdb->posts (post_title,post_content,post_excerpt,post_parent,post_type) VALUES {$val}";
				 $wpdb->query($insert_query);
		   }
			// echo '<hr><br>';
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
			// exit;
		}
	}
}

add_action("wp_footer","a21_js_for_only_quick_editing_timeline");
function a21_js_for_only_quick_editing_timeline(){
	?>
	<script>
	(function($) {
		$(document).ready(function () {
			$("#a21_add_new_row_qedit_timel").on("click",function(){
				console.log("click add new");
				var row_i = $(".a21_js").length,el=1;
				console.log( row_i );
				if(row_i > 0) el = row_i+el;
				console.log("el "+el);
				var html = '<tr> \
				<td class="timel_title a21_js">\
					 <input type="text" required="required" placeholder="" name="new_data['+el+'][timel_title]" class="form-control" value="">\
				</td>\
				<td id="a21_wrap_datepicker">\
					 <input  data-date-orientation="right bottom" data-provide="datepicker" type="text" placeholder="" name="new_data['+el+'][timel_date]" class="form-control" required="required" data-date-format="dd M yyyy" value="">\
				</td>\
				<td><textarea placeholder="" required="required" name="new_data['+el+'][timel_content]" class="form-control"></textarea>\
				</td>\
				</tr>';
				$("#a21_timeleline_quick_edit").append(html);
			});
		});
	})(jQuery);
	</script>
	<?php
}
