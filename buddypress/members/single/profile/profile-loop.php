<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_before_profile_loop_content' ); ?>

<?php 
$group_ids =  groups_get_user_groups( bp_loggedin_user_id() ); 	
foreach($group_ids["groups"] as $group_id) { 
	$group = groups_get_group(array( 'group_id' => $group_id ));
	$grs .= $group->id.':"'.$group->name.'",';
}


$grs = substr($grs,0,-1);
$grs = "{".$grs."}";


// move groups ids and name in javascript timeliner
echo "<script>var grs = $grs;</script>";
$user_id_gr = bp_displayed_user_id();

// ---
remove_filter( 'bp_get_the_profile_field_value',           'stripslashes' );
remove_filter( 'bp_get_the_profile_field_edit_value',      'stripslashes' );
remove_filter( 'bp_get_the_profile_field_value',           'bp_xprofile_escape_field_data', 8, 3 );
remove_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_format_field_value',         1, 2 );
remove_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_format_field_value_by_type', 8, 3 );
remove_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_link_profile_data',          9, 3 );

global $bp;
$user_id = $bp->displayed_user->id;
$verify_user = xprofile_get_field_data('Active security check', $user_id);

if($verify_user[0] == 'YES' && is_user_logged_in() ){

	$sec_verify_desc = xprofile_get_field_data('Description', $user_id);
	if( empty($sec_verify_desc) ){
		// tab Security field Description,wich id=44
		$sec_verify_desc = xprofile_get_field(44, $user_id);
		// default description under field
		$sec_verify_desc = $sec_verify_desc->description;
	}else 		{
		$sec_verify_desc = xprofile_get_field(44, $user_id);
		$sec_verify_desc = $sec_verify_desc->data->value;
	}
}

// echo "<h1>test</h1>";
// $t1 = xprofile_get_field(44, $user_id);
// // // $t1 = xprofile_get_field(44, $user_id);
// // // // default description under field
// echo $t1 = wpautop($t1->data->value);
// // // // echo $t1->description;
// // // // echo wpautop( $some_long_text );
// print_r($t1);

?>

<?php if ( bp_has_profile() ) : ?>

	<?php
		function groups_user(){
			global $bp;
			$quest_id = $bp->displayed_user->id;
			// groups for auth and noauth user
			$user_groups =  groups_get_user_groups( $quest_id ); 
			$html = '<div class="bp-widget">';
			$html .= "<span class='field-name'>Causes</span>";	
			$html .= '<div class="bp-widget">';
			foreach($user_groups["groups"] as $group_id) { 
				$group = groups_get_group(array( 'group_id' => $group_id ));
				$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
				$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );
				$gr_avatar = bp_core_fetch_avatar($avatar_options);
				$html .='<div id="alex_groups_user">
							<a href="'.$group_permalink.'"><img src="'.$gr_avatar.'"/></a>
							<a href="'.$group_permalink.'">'.$group->name.'</a>
					  	</div>';
			}
			$html .="</div>
			</div>";
			// alex_debug(1,1,"grs",$grs_notimeline);
			echo $html;
		}
	?>

	<?php $i=0; $bi=0;$det=0; while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php

			$prof_name = trim( strtolower(preg_replace("#^[0-9]+\.#i", "", bp_get_the_profile_group_name()) ));

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php bp_the_profile_group_slug(); if($prof_name=="basic info") echo " info "; if($prof_name=="details") echo " details ";  ?>">

			<?php
			?>
			<?php 
			$gr_name = bp_get_the_profile_group_name(); 
			// return 0 or 1
			$gr_social = preg_match("#social#i", $gr_name);
			// var_dump($gr_social);
			$gr_basic_info = preg_match("#info#i", $gr_name);
			if( (bool)$gr_social == false && $prof_name != "security") : ?>

				<!-- <h4><?php bp_the_profile_group_name(); ?></h4> -->
				<?php if($prof_name == "interests"):?>
				<span class="field-name"><?php echo $prof_name;?></span>
				<?php endif;?>
				

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
						<?php //echo $prof_name; ?>
						<?php //if ( bp_field_has_data() && $gr_social != "social" ): ?>
						<?php if ( bp_field_has_data() && (bool)$gr_social == false ): ?>
							
							<?php if($prof_name == "mission"):?>	
								<table class="profile-fields mission">
								<td class="data"><?php bp_the_profile_field_value(); ?></td>
								</tr>
								</table>
							<?php elseif($prof_name == "basic info"):?>
								<?php if($bi < 1):?>
									<div class="wrap_field-avail">
									<table class=" field-avail">
									<tr<?php bp_field_css_class(); ?>>
									<td class="data">
									<span>Availability</span>
									<?php
										// check registration user from facebook login,if ok,then get field default from xProfile
										$v_avail = xprofile_get_field_data('Volunteer Availability');
										if( empty($v_avail)) {
											global $wpdb;
											$table = $wpdb->prefix."bp_xprofile_fields";
											$option = $wpdb->get_results( $wpdb->prepare(
												"SELECT name
												FROM {$table}
												WHERE group_id = %d
												    AND parent_id = %d
												    AND is_default_option = %d",
												1,2,1
											) );
											echo "<p><a href='#'>".$option[0]->name."</a>";
										}else{	bp_the_profile_field_value(); }
									?>
									</td>
									<td class="verify">
									<?php

									if($verify_user[0] == 'YES') {
										if(is_user_logged_in() ) {
											$popup_s = "<a href='#security_desc' class='popup-modal'>";
											$popup_e = "</a>";
										}
										echo $popup_s."<img src='".get_stylesheet_directory_uri()."/images/user_verified.png' alt='Security check verified'/>".$popup_e;
										$sec_mouseover = "Security Check Verified";
									}else{
									 echo "<img src='".get_stylesheet_directory_uri()."/images/user_not_verified.png' alt='Security check verified'/>";
									 	$sec_mouseover = "Security Check Non-Verified";
									}
									?>
									</td>
									</tr>
									</table>
									<!-- <span>Security Check Verified</span> -->
									<span class="sec_mouseover"><?php echo $sec_mouseover;?></span>
									</div>
									<script type="text/javascript">
									jQuery(document).ready(function() { 
										jQuery( ".field-avail td.verify" ).mouseover(function() { 
											jQuery(".wrap_field-avail .sec_mouseover").css({"display":"block"});
										});
										jQuery( ".field-avail td.verify" ).mouseout(function() { 
											jQuery(".wrap_field-avail .sec_mouseover").css({"display":"none"});
										});
									});
									</script>
								<?php endif;?>
								<?php $bi++; ?>								
							<?php elseif($prof_name=="details"):?>
								<?php if($det == 0):?>
									<h2><?php bp_the_profile_field_value(); ?></h2>
								<?php else:?>
									<h3><?php bp_the_profile_field_value(); ?></h3>
								<?php endif;?>
								<?php $det++; ?>								
							<?php else:?>
								<p class="data"><?php bp_the_profile_field_value(); ?></p>
							<?php endif;?>

						<?php endif; ?>

						<?php

						/**
						 * Fires after the display of a field table row for profile data.
						 *
						 * @since BuddyPress (1.1.0)
						 */
						do_action( 'bp_profile_field_item' ); ?>

					<?php endwhile;?>
				</table>

			<?php endif; // if not SOCIAL ?>

			</div>
			<!-- end .bp-widget -->

			<?php if($i==4) groups_user(); ?>


				<!--<h4><?php echo bp_get_the_profile_group_name();?></h4>-->

				<?php if($i == 4): ?>
					<div class="bp-widget">
					<span class='field-name'>Timeline</span>
					<div id="timeliner">
					  <ul class="columns alex_timeline_wrap">
					      <?php
							global $wpdb;
					 		$user = wp_get_current_user();
							global $bp;
							$quest_id = $bp->displayed_user->id;

							/* select timeline data */

							$fields = $wpdb->get_results( $wpdb->prepare(
								"SELECT ID, post_title, post_content, post_excerpt,post_name,menu_order
								FROM {$wpdb->posts}
								WHERE post_parent = %d
								    AND post_type = %s
								ORDER BY ID ASC",
								intval( $quest_id ),
								"alex_timeline"
							) );

							foreach ($fields as $field):?>
							<?php //
								$group = groups_get_group($field->menu_order);
								$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
								$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );
								$gr_avatar = bp_core_fetch_avatar($avatar_options);

							 ?>
						      <li>
						          <div class="timeliner_element <?php echo !empty($field->post_name) ? $field->post_name : "teal"; ?>">
									<!-- <span class="timeliner_element2 <?php // echo $group->name;?>"></span> -->						        
						              <div class="timeliner_title">
						                  <span class="timeliner_label"><?php echo $field->post_title;?></span>
						                  <span class="timeliner_date"><?php echo $field->post_excerpt;?></span>
						              </div>
						              <div class="content">
						              	  <?php echo $field->post_content;?>
						              </div>
						              <div class="readmore">
						              	  <?php if($gr_avatar):?> <div id="alex_gr_avatar"><?php echo $gr_avatar;?></div><?php endif;?>
						              	  <?php if($group_permalink):?> <div id="alex_gr_link"><?php echo $group_permalink;?></div><?php endif;?>
						              	  <?php if($group->name):?> 
						              	  	 <div id="alex_gr_name_select"><?php echo $group->name;?></div>
						              	  <?php endif;?>
						              	  <?php if($group->id):?> <div id="alex_gr_id_select"><?php echo $group->id;?></div><?php endif;?>
						              	  <span class="alex_item_id"><?php echo $field->ID;?></span>
						                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
						                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
						                  <a href="#" class="btn btn-info">
						                      Read More <i class="fa fa-arrow-circle-right"></i>
						                  </a>
						              </div>
						          </div>
						      </li>
					      <?php endforeach;?>
					   </ul> 
					</div>
					</div>

				<?php endif;  ?> 
				<!-- end timeline -->

			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php $i++; endwhile; ?>

	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_after_profile_loop_content' ); ?>

 <!-- 4:05 -->

<script type="text/javascript">
 jQuery(document).ready(function(){   
    jQuery('.popup-modal').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#username',
        modal: true
    });
});
 </script>

<!-- <a href="#security_desc" class="popup-modal">click me!</a>-->
<?php if( !empty($sec_verify_desc) ):?>
	<div id="security_desc" class="white-popup-block mfp-hide">
		<!-- <div> Demo text Demo text Demo text Demo text Demo text Demo text</div> -->
		<div><?php echo wpautop($sec_verify_desc);?></div>
	    <!-- <a class="popup-modal-dismiss" href="#">x</a> -->
	    <a class="mfp-close" href="#">x</a>
	</div>
<?php endif;?>