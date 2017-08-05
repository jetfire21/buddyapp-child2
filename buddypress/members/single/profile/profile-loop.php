<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_before_profile_loop_content' ); ?>

<?php 
// echo "loguser777===".bp_loggedin_user_id();
// echo "is_pr_gr "; 
// var_dump( bp_profile_groups() );

$group_ids =  groups_get_user_groups( bp_loggedin_user_id() ); 	
// print_r($group_ids);
foreach($group_ids["groups"] as $group_id) { 
	$group = groups_get_group(array( 'group_id' => $group_id ));
	$grs .= $group->id.':"'.$group->name.'",';
}


$grs = substr($grs,0,-1);
$grs = "{".$grs."}";


// move groups ids and name in javascript timeliner (to pass only groups in which the user)
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
$text_field_empty = "<p>user has not yet added fields to profile</p>";

// var_dump($verify_user);
// xprofile_get_field_data('Experience', $user_id);
$experience = xprofile_get_field(56, $user_id);
// alex_debug(0,1,'',$experience);
// if( !empty($experience) ) echo $experience->data->value;


// if($verify_user[0] == 'YES' && is_user_logged_in() ){
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

/* **** as21 **** */
// $a = xprofile_get_field_data('Description', $user_id);
// $a = xprofile_get_field_data(44, $user_id);
// echo 'testing------';
// var_dump($a);

// echo "<h1>test</h1>";
// $t1 = xprofile_get_field(44, $user_id);
// // // $t1 = xprofile_get_field(44, $user_id);
// // // // default description under field
// echo $t1 = wpautop($t1->data->value);
// // // // echo $t1->description;
// // // // echo wpautop( $some_long_text );
// print_r($t1);

/******* get xp gr id experience***********/
$cur_user = wp_get_current_user();
if(!empty($cur_user->ID) && $cur_user->ID == $user_id):
	// alex_debug(0,1,"",bp_xprofile_get_groups());
	foreach (bp_xprofile_get_groups() as $xp_gr) {
		if(preg_match("/experience/i", $xp_gr->name)) { $xp_gr_experience_id = $xp_gr->id; /*break;*/ }
		if(preg_match("/details/i", $xp_gr->name)) { $xp_gr_details_id = $xp_gr->id; /*break;*/ }
		// echo $xp_gr->name;
	}
	// http://dugoodr2.dev/i-am/admin/profile/edit/group/6/
	// http://dugoodr2.dev/i-am/oenomaus2013/causes/
	// http://dugoodr2.dev/i-am/admin/profile/edit/group/4/
	$member_name = bp_core_get_username($user_id);
	$base_link = get_home_url()."/".$bp->members->root_slug."/".$member_name."/";
	$edit_link = $base_link.$bp->groups->root_slug;
	$edit_link_exp = $base_link."profile/edit//group/".$xp_gr_experience_id;
	$edit_link_details = $base_link."profile/edit//group/".$xp_gr_details_id;
	$edit_link = " <a class='btn btn-primary a21_btn_pf_edit' href='".$edit_link."'><i class='fa fa-pencil'></i> </a>";
	$edit_link_details = " <a class='btn btn-primary a21_btn_pf_edit' href='".$edit_link_details."'><i class='fa fa-pencil'></i> </a>";
	$edit_link_exp = " <a class='btn btn-primary a21_btn_pf_edit' href='".$edit_link_exp."'><i class='fa fa-pencil'></i> </a>";
endif;

// echo "===".xprofile_get_field_id_from_name("4. Experience");
// global $wpdb;
// echo $wpdb->prefix;
// $xprofile_gr_id = $wpdb->get_var( "SELECT id FROM {$wpdb->prefix}bp_xprofile_groups");

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
			// }else return false;
			}else {
				$as21_has_group['groups'] = false;
				$html = '<div class="bp-widget groups">
							<span class="field-name" id="tooltips-groups">Causes</span>
							'.$text_field_empty.'
						</div>';
				return $html;
			};
			// alex_debug(1,1,"grs",$grs_notimeline);
		}

		/* **** as21 **** */
		global $profile_template;
		// $profile_groups = BP_XProfile_Group::get( array( 'fetch_fields' => true	) );
		// alex_debug(0,1,'',$profile_groups);
		// alex_debug(0,1,'',$profile_template->groups[0]);

		/* **** as21 it is necessary to change the sequence of an output of fields and the correct html markup of the responsive blocks **** */
		$gr_name_basic_info = $profile_template->groups[0];
		$gr_name_details = $profile_template->groups[1];
		$profile_template->groups[0] = $gr_name_details;
		$profile_template->groups[1] = $gr_name_basic_info;
		// alex_debug(0,1,'',$profile_template->groups[0]);
		// alex_debug(0,1,'',$profile_template->groups);
	?>

	<?php  $i=0; $bi=0;$det=0; while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php
			/* **** as21 **** */


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
			// var_dump($gr_social);
			$gr_basic_info = preg_match("#info#i", $gr_name);
			if( (bool)$gr_social == false && $prof_name != "security") : ?>

				<!-- <h4><?php bp_the_profile_group_name(); ?></h4> -->				

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
						<?php
						// $as21_all_fields .= bp_get_the_profile_field_value().' | ';
						// if( !empty(bp_get_the_profile_field_value()) ) $score++;
						 /*echo $prof_name; */	
						 // echo bp_get_the_profile_field_name(); 
						 if( strtolower(bp_get_the_profile_field_name()) == 'total estimate hours') continue; 
						//if ( bp_field_has_data() && $gr_social != "social" ):
						 ?>

						<?php if ( bp_field_has_data() && (bool)$gr_social == false ): ?>
							
							<?php if($prof_name == "mission"):?>	
<!--  								<table class="profile-fields mission">
								<td class="data"><?php // bp_the_profile_field_value(); ?></td>
								</tr>
								</table>
 -->
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
									  	// with text formatting
									 	// if( !empty($experience) ) echo stripslashes( wpautop($experience->data->value ));
									 	// bp_the_profile_field_value();
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

			<?php  // echo "<br>iteration ".$i."<br>"; ?>
			</div>
			<!-- end .bp-widget -->

				<!--<h4><?php echo bp_get_the_profile_group_name();?></h4>-->

			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php $i++; endwhile; ?>

	<?php
					
		/* **** as21 if profile fields is full empty**** */		

		// if((bool)$_GET['dev'] == true ) alex_debug(1,1,'',$profile_template->groups);
		global $as21_has_group;

		$has_mission_group = false;
		$has_security_group = false;
		$has_details_group = false;
		foreach ($profile_template->groups as $group) {
			if( $group->id == 4) { $has_details_group = true; break; }
		}
		if(!$has_details_group) {
		// if(!$has_details_group && $LALA) {
			$as21_has_group['name'] = false;
			$details_field = $wpdb->get_row( $wpdb->prepare( "SELECT description,name FROM {$wpdb->prefix}bp_xprofile_fields WHERE id=%d AND group_id = %d AND parent_id = %d",10, 4, 0 ) );
			echo "<div class='bp-widget 1. details details'><span class='field-name not-filled-filed' id='tooltips-name'>".$details_field->name."</span>".$details_field->description."</div>";
				?>
                <div class="bp-widget basic info info "> <?php echo a21_display_vol_availibility_sec_check($verify_user); ?></div>
                <?php
		}

		// $vol_availability = xprofile_get_field(2, $user_id);
		// alex_debug(0,1,'',$vol_availability);
		// echo $vol_availability->data->value;
		/*
		foreach ($profile_template->groups as $group) {
			if( $group->id == 7) { $has_security_group = true; break; }
		}

		if(!$has_security_group) {
			// echo '--sec not EXIST---';
			$security_field = $wpdb->get_var( $wpdb->prepare( "SELECT description FROM {$wpdb->prefix}bp_xprofile_fields WHERE id=%d AND group_id = %d AND parent_id = %d",44, 7, 0 ) );
			echo "<div class='bp-widget'><span class='field-name'>Security</span>".$security_field."</div>";
		}	
		*/
		foreach ($profile_template->groups as $group) {
			if( $group->id == 5) { $has_mission_group = true; break; }
		}
	
		if(!$has_mission_group) {
			// echo '--Mission not EXIST---';
			$as21_has_group['mission'] = false;
			$mission_field = $wpdb->get_row( $wpdb->prepare( "SELECT description,name FROM {$wpdb->prefix}bp_xprofile_fields WHERE group_id = %d AND parent_id = %d", 5, 0 ) );
			// deb_last_query();
			echo "<div class='bp-widget'><span class='field-name' id='tooltips-mission'>".$mission_field->name."</span>".$mission_field->description."</div>";
		}

		$has_interests = xprofile_get_field_data('Interests', $user_id);
		// $has_experience = xprofile_get_field_data('Experience', $user_id);
		$has_mission = xprofile_get_field_data('Mission', $user_id);
		// echo "new code----"; var_dump($has_mission);

		if($has_interests == "")  echo '<div class="bp-widget"><span class="field-name">Interests'.$edit_link_exp.'</span>'.$text_field_empty.'</div>';
		// if($has_experience == "")  echo "<div class='bp-widget'><span class='field-name'>Experience".$edit_link_exp."</span>".$text_field_empty."</div>";
		// echo "development mode exper";
		 $all_exper = as21_get_all_experience_from_page_edit_profile();
		 // alex_debug(0,1,'',$all_exper);

		 if( !empty($all_exper) ){
		 	// alex_debug(0,1,'',$all_exper);
		 	// alex_debug(0,1,'',$_POST);

		 	/*
		 	if(!empty($_POST['ve_send_notif'])){

		 		// $notif_id = bp_notifications_add_notification( $args );
				$ids = $wpdb->get_col("SELECT ID FROM {$wpdb->users}");
				// alex_debug(0,1,'',$ids);

				if( !empty($ids)):
					foreach ($ids as $id) {
						if($id == $user_id ) continue;
				       $notif_id = bp_notifications_add_notification( array(
						// 'user_id'           => $user_id,
				   		'user_id'           => $id, //	dev-test-1
						'item_id'           => $_POST['ve_exper_id'], // 10785
						'secondary_item_id' => 0,
						'component_name'    => 'custom',
						'component_action'  => 'custom_action',
						'date_notified'     => bp_core_current_time(),
						'is_new'            => 1,
					) );
					}
				endif;


				$wpdb->update( $wpdb->posts,
					array( 'guid'=> 1), // status send 'get verified'
					array( 'ID' => $_POST['ve_exper_id'] ),
					array( '%d' ),
					array( '%d' )
				);
				// unset($_POST);
						$ref = $_SERVER['HTTP_REFERER'];
				?>
				<script>window.location.href = '<?php echo $ref;?>';</script>
				<?php
		 	}
		 	*/
		 	/*
		 	if(!empty($_POST['ve_send_email'])){

		 		alex_debug(0,1,'',$_POST);

		 		as21_verification_experience_process($_POST);

		 	}
		 	*/

			 $html = '<ul id="as21_list_experiences">';
			 	// $quest_id = (!$user_id) ? $quest_id = $bp->displayed_user->id : $user_id;
	  		$cur_auth_user = wp_get_current_user();
	  		// echo $user_id.'-'.$cur_auth_user->ID;

			 foreach ($all_exper as $k => $exper) {

			 	// if($k == 0) $html .= '<li>'.$exper->post_title.'<img class="exper_verif" src="'.get_stylesheet_directory_uri().'/images/experience_verified.png" /></li>';
			 	// else $html .= '<li>'.$exper->post_title.'<a href="#verif_send_notif_'.$k.'" class="popup-modal-exper exper-non-verif">Get verified</a></li>';
				$dugoodr = get_userdata($exper->post_parent);
			 	if($exper->comment_count == 1) $html .= '<li>'.$exper->post_title.'<a title="'.$dugoodr->data->display_name.'" href="'.bp_core_get_user_domain($exper->post_parent).'"><img class="exper_verif" src="'.get_stylesheet_directory_uri().'/images/experience_verified.png" /></a></li>';
			 	elseif($user_id == $cur_auth_user->ID && $exper->comment_count == 0){
			 		$html .= '<li>'.$exper->post_title.'<a href="#verif_send_notif_'.$k.'" class="popup-modal-exper exper-non-verif">Get verified</a></li>';
			 	}else{
			 		$html .= '<li>'.$exper->post_title.'</li>';
			 	}

			 	// $html .= '<li>'.$exper->post_title.'</li>';
			 		$html .= '<div id="verif_send_notif_'.$k.'" class="verif_send_notif white-popup-block mfp-hide">
			 					<!--<div class="a21-system-box">block under development</div>-->
								<div><p>Get verified via sendig notification to all registered DuGoodrs </p>
									
									<form id="ve_form_notif" action="" method="post">				
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
												<p>
													Enter email addresses below, one per line.									</p>
													<p class="description">You can invite a maximum of 5 people at a time.</p>
													<textarea name="ve_email_addresses" class="ve_email_addresses" id="invite-anyone-email-addresses"></textarea>
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
			 // $score++;
		}else{
			$as21_has_group['experiences'] = false;
			 echo "<div class='bp-widget'><span class='field-name' id='tooltips-experiences'>Experience".$edit_link_exp."</span>".$text_field_empty."</div>";
		}
		// <input type="hidden" name="exper_id" data-id="'.$exper->ID.'" />
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
        
		// print_r($fields);
		// $total_hours_every_t = $wpdb->get_col($wpdb->prepare("SELECT comment_count FROM {$wpdb->posts} WHERE post_parent = %d  AND post_type = %s ",$quest_id,"alex_timeline"));
		// alex_debug(0,1,'ddd',$total_hours_every_t);
		// deb_last_query();

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
				<?php //

					//
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
								<!-- <span class="timeliner_element2 <?php // echo $group->name;?>"></span> -->						        
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
		<!-- 			            
									 <?php if($group->id):?> <div id="alex_gr_id_select"><?php echo $group->id;?></div><?php endif;?>
					              	  <span class="alex_item_id"><?php echo $field->ID;?></span>
					                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
					                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
					                  <a href="#" class="btn btn-info">
					                      Read More <i class="fa fa-arrow-circle-right"></i>
					                  </a>
		 -->			    
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
								<!-- <span class="timeliner_element2 <?php // echo $group->name;?>"></span> -->						        
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
			<!-- <a href="http://dugoodr2.dev/i-am/admin/activity/?acpage=2">Load More</a> -->
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

 <!-- 4:05 -->
<?php //echo "TOTAL HOURS (experience_total_hours+total_hours_every_entry)=".$experience_total_hours.'+'.$total_hours_every_entry;?>
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

<!-- <a href="#security_desc" class="popup-modal">click me!</a>-->
<?php if( !empty($sec_verify_desc) ):?>
	<div id="security_desc" class="white-popup-block mfp-hide">
		<!-- <div> Demo text Demo text Demo text Demo text Demo text Demo text</div> -->
		<div><?php echo wpautop($sec_verify_desc);?></div>
	    <!-- <a class="popup-modal-dismiss" href="#">x</a> -->
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
            // $vol_availability = xprofile_get_field_data(2, $user_id);
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
	// echo ' user_id-'.$user_id; echo ' member_id-'.$member_id;

	if( $user_id == $member_id):

		/*
	    // print_r($status_tooltips_db);
		$tooltips = array(
			array(
			'id'   => 'tooltips-name',
			'edge' => 'bottom', // align tooltip arrow
			'text' => '<h3>Step1: </h3> <p> Build your Profile (image, background, name, contact details)</p>',
			'zindex' => 998),
			array(
			'id'   => 'tooltips-mission',
			'edge' => 'top',
			'text' => '<h3>Step2: </h3> <p> Add your mission statement</p>',
			'zindex' => 997),
			array(
			'id'   => 'tooltips-experiences',
			'edge' => 'top',
			'text' => '<h3>Step3:</h3><p> Add your experiences</p>',
			'zindex' => 996),
			array(
			'id'   => 'tooltips-socilal-links',
			'edge' => 'top',
			'text' => '<h3>Step4:</h3><p> add your social media accounts</p>',
			'zindex' => 995),
			array(
			'id'   => 'tooltips-groups',
			'edge' => 'top',
			'text' => '<h3>Step 5:</h3><p> Add causes you support and have joined</p>',
			'zindex' => 994)
		  );
			*/
	  $tooltips = array();
	  $ti = 1;
	  global $as21_has_group;
	  // echo '====888';
	  // var_dump($as21_has_group);
	  
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

	// alex_debug(0,1,'',$tooltips);

		/* **** as21 get tooltips for case when only one tooltip dismiss ****
		// echo ' conditional user_id == member_id first=========';
		$status_tooltips_db = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
			FROM {$wpdb->postmeta}
			WHERE post_id = %d
			    AND meta_key = %s
			ORDER BY meta_value ASC",
			intval( $user_id ),
			"as21_tooltips_profile"
		),'ARRAY_A' );


		// print_r($tooltips);
		$html = '';
		$step = 0;
		foreach ($tooltips as $tip):
			// print_r($tip);
			$has_tooltip = false;
			foreach ($status_tooltips_db as $tooltip) {
				// print_r($tooltip); 
				// echo $tooltip['meta_value'];
				// echo $tip['id'];
				// var_dump( in_array($tip['id'], $tooltip) );
				if(in_array($tip['id'], $tooltip) === true) { $has_tooltip = true; break; }
			}
			// var_dump( in_array($tip['id'], $status_tooltips_db) );
			if($has_tooltip !== true){
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
			}
		endforeach;
		 **** as21 get tooltips for case when only one tooltip dismiss **** */

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
		// deb_last_query();
		// echo 'hide all tooltips'; var_dump($status_tooltips_db);

		// print_r($tooltips);
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

			// print_r($tooltip_js);
			// echo '===count tooltip_js==='.count($tooltips);
			$tooltip_js = json_encode($tooltip_js);
			 ?>
			  <script type="text/javascript">
			     var tooltip_js = <?php echo $tooltip_js;?>;
			     var user_id = <?php echo $user_id;?>
			 </script>
			 <?php
			echo $html;
		    // echo 'include js form other place';
			  // add_action('wp_enqueue_scripts','a21_tip1');
			  // function a21_tip1(){
			  // 		   wp_enqueue_script('tooltips-profile',__DIR__	."/tooltips-profile.js",array('jquery'),'',true);
			  // }
			?>
			<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri();?>/js/tooltips-profile.js'></script>
		<?php
		endif;
  endif; // check user_id
}

/* **** as21  tooltips for new user on profile page**** */
