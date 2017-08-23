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


// move groups ids and name in javascript timeliner (to pass only groups in which the user)
echo "<script>var grs = $grs;</script>";
$user_id_gr = bp_displayed_user_id();

remove_filter( 'bp_get_the_profile_field_value',           'stripslashes' );
remove_filter( 'bp_get_the_profile_field_edit_value',      'stripslashes' );
remove_filter( 'bp_get_the_profile_field_value',           'bp_xprofile_escape_field_data', 8, 3 );
remove_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_format_field_value',         1, 2 );
remove_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_format_field_value_by_type', 8, 3 );
remove_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_link_profile_data',          9, 3 );

global $bp;
$user_id = $bp->displayed_user->id;
$verify_user = xprofile_get_field_data('Active security check', $user_id);
$text_field_empty = "<p>user has not yet added fields to profile</p>";

$experience = xprofile_get_field(56, $user_id);


if($verify_user == 'YES' && is_user_logged_in() ){

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


/******* get xp gr id experience***********/
$cur_user = wp_get_current_user();
if(!empty($cur_user->ID) && $cur_user->ID == $user_id):
	foreach (bp_xprofile_get_groups() as $xp_gr) {
		if(preg_match("/experience/i", $xp_gr->name)) { $xp_gr_experience_id = $xp_gr->id; /*break;*/ }
		if(preg_match("/details/i", $xp_gr->name)) { $xp_gr_details_id = $xp_gr->id; /*break;*/ }
	}
	$member_name = bp_core_get_username($user_id);
	$base_link = get_home_url()."/".$bp->members->root_slug."/".$member_name."/";
	$edit_link = $base_link.$bp->groups->root_slug;
	$edit_link_exp = $base_link."profile/edit//group/".$xp_gr_experience_id;
	$edit_link_details = $base_link."profile/edit//group/".$xp_gr_details_id;
	$edit_link = " <a class='btn btn-primary a21_btn_pf_edit' href='".$edit_link."'><i class='fa fa-pencil'></i> </a>";
	$edit_link_details = " <a class='btn btn-primary a21_btn_pf_edit' href='".$edit_link_details."'><i class='fa fa-pencil'></i> </a>";
	$edit_link_exp = " <a class='btn btn-primary a21_btn_pf_edit' href='".$edit_link_exp."'><i class='fa fa-pencil'></i> </a>";
endif;

?>

<?php if ( bp_has_profile() ) : ?>

	<?php
		function groups_user($edit_link ='',$text_field_empty){
			global $bp,$as21_has_group;
			$quest_id = $bp->displayed_user->id;
			// groups for auth and noauth user
			$user_groups =  groups_get_user_groups( $quest_id ); 
			if( !empty($user_groups['groups']) ) {

				$html = '<div class="bp-widget groups">';
				$html .= "<span class='field-name'>Causes".$edit_link."</span>";
				//
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
				$html .="</div>";
				return $html;
			}else {
				$as21_has_group['groups'] = false;
				$html = '<div class="bp-widget groups">
							<span class="field-name" id="tooltips-groups">Causes</span>
							'.$text_field_empty.'
						</div>';
				return $html;
			};
		}

		/* **** as21 **** */
		global $profile_template;


		/* **** as21 it is necessary to change the sequence of an output of fields and the correct html markup of the responsive blocks **** */
		$gr_name_basic_info = $profile_template->groups[0];
		$gr_name_details = $profile_template->groups[1];
		$profile_template->groups[0] = $gr_name_details;
		$profile_template->groups[1] = $gr_name_basic_info;

	?>

	<?php  $i=0; $bi=0;$det=0; while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php


			$prof_name = trim( strtolower(preg_replace("#^[0-9]+\.#i", "", bp_get_the_profile_group_name()) ));

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php echo strtolower(bp_get_the_profile_group_name()); if($prof_name=="basic info") echo " info "; if($prof_name=="details") echo " details ";  ?>">

			<?php
			?>
			<?php 
			$gr_name = bp_get_the_profile_group_name(); 
			// return 0 or 1
			$gr_social = preg_match("#social#i", $gr_name);
			$gr_basic_info = preg_match("#info#i", $gr_name);
			if( (bool)$gr_social == false && $prof_name != "security") : ?>


					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
						<?php
	
						 if( strtolower(bp_get_the_profile_field_name()) == 'total estimate hours') continue; 
						 ?>

						<?php if ( bp_field_has_data() && (bool)$gr_social == false ): ?>
							
							<?php if($prof_name == "mission"):?>	

							<div class="profile-fields mission hentry">
							<div class="entry-content">
								<?php echo stripslashes( bp_get_the_profile_field_value() );  ?>
								</div>
							</div>
							<?php elseif($prof_name == "basic info"):?>
								<?php $full_name = xprofile_get_field_data(10, $user_id);?>
								<?php if($bi < 1 && !empty($full_name)):?>

 									<?php echo a21_display_vol_availibility_sec_check($verify_user); ?>

								<?php endif;?>
								<?php $bi++; ?>								
							<?php elseif($prof_name=="details"):?>
								<?php if($det == 0):?>
									<h2><?php bp_the_profile_field_value(); echo $edit_link_details; ?></h2>
									<div class="clearfix"></div>
								<?php else:?>
									<h3><?php bp_the_profile_field_value(); ?></h3>
								<?php endif;?>
								<?php $det++; ?>	
							<?php elseif($prof_name=="experience"):?>	
								 <span class="field-name"><?php echo bp_get_the_profile_field_name();?> 
									<?php echo $edit_link_exp;?>
								</span>
								 <div class="data experience">
									 <?php
									  if( strtolower(bp_get_the_profile_field_name()) == "experience"){ 
				
									 }
									  else { bp_the_profile_field_value(); }
									   ?>									   							 	
								 </div>			
							<?php else:?>
								<div class="data"><?php bp_the_profile_field_value(); ?></div>
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


			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php $i++; endwhile; ?>

	<?php
					
		/* **** as21 if profile fields is full empty**** */		

		global $as21_has_group;

		$has_mission_group = false;
		$has_security_group = false;
		$has_details_group = false;
		foreach ($profile_template->groups as $group) {
			if( $group->id == 4) { $has_details_group = true; break; }
		}
		if(!$has_details_group) {
			$as21_has_group['name'] = false;
			$details_field = $wpdb->get_row( $wpdb->prepare( "SELECT description,name FROM {$wpdb->prefix}bp_xprofile_fields WHERE id=%d AND group_id = %d AND parent_id = %d",10, 4, 0 ) );
			echo "<div class='bp-widget 1. details details'><span class='field-name not-filled-filed' id='tooltips-name'>".$details_field->name."</span>".$details_field->description."</div>";
				?>
                <div class="bp-widget basic info info "> <?php echo a21_display_vol_availibility_sec_check($verify_user); ?></div>
                <?php
		}


		foreach ($profile_template->groups as $group) {
			if( $group->id == 5) { $has_mission_group = true; break; }
		}
	
		if(!$has_mission_group) {
			$as21_has_group['mission'] = false;
			$mission_field = $wpdb->get_row( $wpdb->prepare( "SELECT description,name FROM {$wpdb->prefix}bp_xprofile_fields WHERE group_id = %d AND parent_id = %d", 5, 0 ) );
			echo "<div class='bp-widget'><span class='field-name' id='tooltips-mission'>".$mission_field->name."</span>".$mission_field->description."</div>";
		}

		$has_interests = xprofile_get_field_data('Interests', $user_id);
		$has_mission = xprofile_get_field_data('Mission', $user_id);

		if($has_interests == "")  echo '<div class="bp-widget"><span class="field-name">Interests'.$edit_link_exp.'</span>'.$text_field_empty.'</div>';
		 $all_exper = as21_get_all_experience_from_page_edit_profile();

		 if( !empty($all_exper) ){

			 $html = '<ul id="as21_list_experiences">';
	  		$cur_auth_user = wp_get_current_user();
	  		$users_data = $wpdb->get_results("SELECT ID,display_name FROM {$wpdb->users}");
			if( !empty($users_data)):
				$select_user = '<select name="ve_notif_user_id"><option value="0" selected>None</option>';
				foreach ($users_data as $user) {
					if($user->ID == $cur_auth_user->ID ) continue;
					$select_user .= '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
				}
				$select_user .= '</select>';
			endif;


			 foreach ($all_exper as $k => $exper) {

				$dugoodr = get_userdata($exper->post_parent);
			 	if($exper->comment_count == 1) $html .= '<li>'.$exper->post_title.'<a title="'.$dugoodr->data->display_name.'" href="'.bp_core_get_user_domain($exper->post_parent).'"><img class="exper_verif" src="'.get_stylesheet_directory_uri().'/images/experience_verified.png" /></a></li>';
			 	elseif($user_id == $cur_auth_user->ID && $exper->comment_count == 0){
			 		$html .= '<li>'.$exper->post_title.'<a href="#verif_send_notif_'.$k.'" class="popup-modal-exper exper-non-verif">Get verified</a></li>';
			 		if($exper->guid == 1) {
				 		 if($exper->post_parent > 0){ // when user is registered
				 		 	$user_data = get_user_by('ID',$exper->post_parent);
					 		 $send_email = $user_data->data->user_email;
					 	}else{
					 		$send_email = $exper->post_password; // when send email non-register user
					 	}
			 		}
			 	}else{
			 		$html .= '<li>'.$exper->post_title.'</li>';
			 	}

			 		$html .= '<div id="verif_send_notif_'.$k.'" class="verif_send_notif white-popup-block mfp-hide">
			 					<!--<div class="a21-system-box">block under development</div>-->
								<div><p>Get verified via sendig notification to registered DuGoodr:</p>
									
									<form id="ve_form_notif" action="" method="post">
									<p>Select user</p>	
									'.$select_user.'
									<input type="hidden" name="ve_exper_id" value="'.$exper->ID.'" />
									<input type="hidden" name="cur_user_id" value="'.$cur_auth_user->ID.'" />
									<input type="submit" name="ve_send_notif" class="as21-send-verif-exper ve_send_notif" value="Send" />
									</form>

									<!--<h4>Invite New Members</h4>-->
									<p id="welcome-message">Get verified via email:</p>
									<form id="ve_form_via_email" action="" method="post">
									<ol id="invite-anyone-steps">
										<li>
											<div class="manual-email">
													<!--
													<p>Enter email addresses below, one per line.</p>
													<p class="description">You can invite a maximum of 5 people at a time.</p>
													<textarea name="ve_email_addresses" class="ve_email_addresses" id="invite-anyone-email-addresses"></textarea>
													-->
													<p>Enter email addresses below</p>
													<div class="ve_email_addresses_wrap">
														<input type="text" name="ve_email_addresses" class="ve_email_addresses" id="ve_email_addresses" value="'.$send_email.'"><span>x</span>
													</div>
												</div>
											</li>
											<li>
												<strong>Subject:</strong> Verification of new experience item
												<input type="hidden" id="invite-anyone-customised-subject" name="ve_custom_subject" value="Verification of new experience item" />
											</li>
											<li>
											<label for="invite-anyone-custom-message">(optional) Customize the text of the invitation.</label>
											<p class="description">The message will also contain a custom footer containing links to verify new experience item.</p>
											<textarea name="ve_custom_message" id="invite-anyone-custom-message" cols="40" rows="10">Please verify my experience. For details visit your profile</textarea>

											</li>
										</ol>
										<div class="submit">
											<input type="hidden" name="ve_exper_id" value="'.$exper->ID.'" />
											<input type="submit" name="ve_send_email" class="as21-send-verif-exper ve_send_via_email" value="Send" />
										</div>
										</form>';
								$html .= '</div>
							    <a class="mfp-close" href="#">x</a>
							</div>';

			 }
			 $html .= '</ul>';
			 echo "<div class='bp-widget'><span class='field-name'>Experience".$edit_link_exp."</span>".$html."</div>";
		}else{
			$as21_has_group['experiences'] = false;
			 echo "<div class='bp-widget'><span class='field-name' id='tooltips-experiences'>Experience".$edit_link_exp."</span>".$text_field_empty."</div>";
		}
		/* **** as21 if profile fields is full empty**** */		


	    if( !empty( groups_user()) ) echo groups_user($edit_link,$text_field_empty);

		global $wpdb;
		global $bp;
		$quest_id = (int)$bp->displayed_user->id;

		do_action("a21_bgc_message_thankyou"); 

		/* select timeline data (title,content,date etc) */

		$offset = 0;
		$count_timeline = 5;
		$fields = $wpdb->get_results( $wpdb->prepare(
			"SELECT ID, post_title, post_content, post_excerpt,post_name,menu_order,guid,comment_count
			FROM {$wpdb->posts}
			WHERE post_parent = %d
			    AND post_type = %s
			ORDER BY post_date DESC LIMIT {$offset},{$count_timeline}",
			$quest_id,
			"alex_timeline"
		) );

		$count_all_timelines = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_parent='{$quest_id}' AND post_type='alex_timeline'");       

		?>
		<div class="bp-widget">

	    <?php 
	    /* **** as21 temp - need if future for bp-group-calendar **** */
	    // do_action("a21_bgc_message_thankyou");
	    /* **** as21 temp - need if future for bp-group-calendar **** */
	    ?>

		<span class='field-name'>Timeline</span>
		<?php if( empty($fields)) echo $text_field_empty; ?>
		<div class="wrap_timeliner">
		<div id="timeliner">
		  <ul class="columns alex_timeline_wrap">
		      <?php	if( !empty($fields) ): foreach ($fields as $field):?>
				<?php 

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

					?>

					      <li>
					          <div class="timeliner_element is_event_thank_you">
					              <div class="timeliner_title">
					                  <span class="timeliner_label"><?php echo stripslashes($event->event_title);?></span>
					                  <span class="timeliner_date"><?php echo $event_time;?></span>
					              </div>
					              <div class="content">
					              	   <?php if( !empty($get_event_image) ) {
					              	    echo "<a href='".$group_permalink."/callout/".$event->event_slug."' class='event_image' target='_blank'><img src='".$get_event_image."' /></a>";
					              	    echo "<p>".stripslashes($event->thank_you)."</p>";
					              	    }else echo stripslashes($event->thank_you);
					              	    ?>
					              </div>
					              <div class="readmore">
					              	  <?php if($gr_avatar):?> <div id="alex_gr_avatar"><?php echo $gr_avatar;?></div><?php endif;?>
					              	  <?php if($group_permalink):?> <div id="alex_gr_link"><?php echo $group_permalink;?></div><?php endif;?>
					              	  <?php if($group->name):?> 
					              	  	 <div id="alex_gr_name_select"><?php echo $group->name;?></div>
					              	  <?php endif;?>
			    
		 				          </div>
					          </div>
					      </li>
					<?php else:

						$group = groups_get_group($field->menu_order);
						$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
						$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );
						$gr_avatar = bp_core_fetch_avatar($avatar_options);
 					?>

					      <li>
					          <div class="timeliner_element <?php echo !empty($field->post_name) ? $field->post_name : "teal"; ?>">
					              <div class="timeliner_title">
					                  <span class="timeliner_label"><?php echo stripslashes($field->post_title);?></span>
					                  <span class="timeliner_date"><?php echo $field->post_excerpt;?></span>
					              </div>
					              <div class="content">
					              	   <?php echo stripslashes($field->post_content);?>
					              </div>
					              <div class="readmore">
					              	  <?php if($gr_avatar):?> <div id="alex_gr_avatar"><?php echo $gr_avatar;?></div><?php endif;?>
					              	  <?php if($group_permalink):?> <div id="alex_gr_link"><?php echo $group_permalink;?></div><?php endif;?>
					              	  <?php if($group->name):?> 
					              	  	 <div id="alex_gr_name_select"><?php echo $group->name;?></div>
					              	  <?php endif;?>
					              	  <?php if($group->id):?> <div id="alex_gr_id_select"><?php echo $group->id;?></div><?php endif;?>
					              	  <span class="alex_item_id"><?php echo $field->ID;?></span>
					              	  <!-- as21 new -->
					              	  <span class="vol_hours"><?php echo $field->comment_count;?></span>
					                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
					                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
					                  <a href="#" class="btn btn-info">
					                      Read More <i class="fa fa-arrow-circle-right"></i>
					                  </a>
					              </div>
					          </div>
					      </li>
		      		<?php endif;
		      endforeach;
		      else:?>
			<li>
			    <div class="timeliner_element bricky">
			        <div class="timeliner_title"> <span class="timeliner_label"></span><span class="timeliner_date"><?php echo date("d M Y");?></span> </div>
			        <div class="content">Signing up for DuGoodr</div>
			        <div class="readmore"></div>
			    </div>
			</li>	
	       <?php endif;?>
		      <?php // do_action("a21_bgc_message_thankyou");  ?>
		   </ul> 
		
		</div>
		</div>

		<?php if($count_all_timelines > $count_timeline):?>
		<ul class="activity-list item-list">
		<li class="load-more">
			<a href="#" id="a21_load_part_timeline_data" data-offset="<?php echo $count_timeline;?>" data-user-id="<?php echo $quest_id;?>">Load More</a>
		</li>
		</ul>
		<?php endif;?>

		</div> 

	<?php //endif;  ?> 
	<!-- end timeline -->
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_after_profile_loop_content' ); ?>

<script type="text/javascript">
  // var total_hours = '<?php echo $experience_total_hours+$total_hours_every_entry;?>';
  var total_hours = '<?php echo as21_get_total_volunteer_hours_count_member();?>';
 jQuery(document).ready(function(){   
    jQuery('.popup-modal').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#username',
        modal: true
    });
});
 </script>

<?php if( !empty($sec_verify_desc) ):?>
	<div id="security_desc" class="white-popup-block mfp-hide">
		<div><?php echo wpautop($sec_verify_desc);?></div>
	    <a class="mfp-close" href="#">x</a>
	</div>
<?php endif;?>

<?php
function a21_display_vol_availibility_sec_check($verify_user = false){
        $html ='
        <div class="inner-basic-info">
        <div id="circle-dount-chart"></div>
        <div class="wrap_field-avail">
        <table class=" field-avail">
        <tr '.bp_get_field_css_class().' >
        <td class="data">
        <span>Availability</span>';
            // check registration user from facebook login,if ok,then get field default from xProfile
            $v_avail = xprofile_get_field_data('Volunteer Availability');
            if( empty($v_avail)) {
                global $wpdb;
                $default_avail = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}bp_xprofile_fields WHERE group_id = %d AND parent_id = %d AND is_default_option = %d", 1, 2,1 ) );
                $html .= "<p>".$default_avail."</p>";
            }else{  $html .= '<p>'.$v_avail.'</p>'; }
       $html .= 
       '</td>
        <td class="verify">';
        if($verify_user == 'YES') {
            if(is_user_logged_in() ) {
                $popup_s = "<a href='#security_desc' class='popup-modal'>";
                $popup_e = "</a>";
            }
            $html .= $popup_s."<img src='".get_stylesheet_directory_uri()."/images/user_verified.png' alt='Security check verified'/>".$popup_e;
            $sec_mouseover = "Security Check Verified";
        }else{
         $html .= "<img src='".get_stylesheet_directory_uri()."/images/user_not_verified.png' alt='Security check verified'/>";
            $sec_mouseover = "Security Check Non-Verified";
        }
        $html .= '
        </td>
        </tr>
        </table>
        <!-- <span>Security Check Verified</span> -->
        <span class="sec_mouseover">'.$sec_mouseover.'</span>
        </div>
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
        </script>';
     return $html;
} 
?>



<?php
/* **** as21  tooltips for new user on profile page**** */

add_action('wp_footer','as21_tooltips_for_new_user_profile',999);
function as21_tooltips_for_new_user_profile(){

	global $wpdb, $bp;
	$user_id = $bp->displayed_user->id;
	$auth_user = wp_get_current_user();
	$member_id = $auth_user->ID;

	if( $user_id == $member_id):


	  $tooltips = array();
	  $ti = 1;
	  global $as21_has_group;
	  
	if($as21_has_group['name'] === false) {

		 $tooltips[$ti] = array(
			'id'   => 'tooltips-name',
			'edge' => 'bottom', // align tooltip arrow
			'text' => '<h3>Step '.$ti.': </h3> <p> Build your Profile (image, background, name, contact details)</p>',
			'zindex' => 998
		);
		 $ti++;
	}

	if($as21_has_group['mission'] === false) {
		$tooltips[$ti] = array(
			'id'   => 'tooltips-mission',
			'edge' => 'top',
			'text' => '<h3>Step '.$ti.': </h3> <p> Add your mission statement</p>',
			'zindex' => 997
		);
		$ti++;
	}

	if( $as21_has_group['experiences'] === false ){
			$tooltips[$ti] = array(
				'id'   => 'tooltips-experiences',
				'edge' => 'top',
				'text' => '<h3>Step '.$ti.':</h3><p> Add your experiences</p>',
				'zindex' => 996
			);
			$ti++;
	}	
	if( $as21_has_group['social-links'] === false ){
			$tooltips[$ti] = array(
				'id'   => 'tooltips-socilal-links',
				'edge' => 'top',
				'text' => '<h3>Step '.$ti.':</h3><p> add your social media accounts</p>',
				'zindex' => 995
			);
			$ti++;
	}
	if( $as21_has_group['groups'] === false ){
			$tooltips[$ti] = array(
				'id'   => 'tooltips-groups',
				'edge' => 'top',
				'text' => '<h3>Step '.$ti.':</h3><p> Add causes you support and have joined',
				'zindex' => 994
			);
			$ti++;
	}



		/**** as21 get tooltips for case when all tooltips dismiss ****/

		// echo ' conditional user_id == member_id first=========';
		$status_tooltips_db = $wpdb->get_var( $wpdb->prepare(
			"SELECT meta_value
			FROM {$wpdb->postmeta}
			WHERE post_id = %d
			    AND meta_key = %s",
			intval( $user_id ),
			"as21_all_tooltips_profile"
		) );

		$html = '';
		$step = 0;
		if( $status_tooltips_db != '1'):
			foreach ($tooltips as $tip):
			   // print_r($tip);
				$tooltip_js[] = $tip;
				$step_attr = '';
				if($step != count($tooltips)-1 ) { $step_attr = '<button type="button" data-step="'.$step.'" class="button-primary advads-notices-button-subscribe" data-notice="nl_first_steps">Next</button>';}
				$html .= 
				'<div id="wp-pointer-'.$tip['id'].'" class="wp-pointer wp-pointer-'.$tip['edge'].'" style="width: 320px; position: absolute; display: none; z-index: '.$tip['zindex'].';">
				<div class="wp-pointer-content"> '.$tip['text'].'
				<div class="wp-pointer-buttons">
				'.$step_attr.'
				<a class="close" href="#">Dismiss</a></div></div><div class="wp-pointer-arrow"><div class="wp-pointer-arrow-inner"></div></div></div>';
				$step++;
			endforeach;

		/**** as21 get tooltips for case when all tooltips dismiss ****/


			$tooltip_js = json_encode($tooltip_js);
			 ?>
			  <script type="text/javascript">
			     var tooltip_js = <?php echo $tooltip_js;?>;
			     var user_id = <?php echo $user_id;?>
			 </script>
			 <?php
			echo $html;
			?>
			<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri();?>/js/tooltips-profile.js'></script>
		<?php
		endif;
  endif; // check user_id
}

/* **** as21  tooltips for new user on profile page**** */
