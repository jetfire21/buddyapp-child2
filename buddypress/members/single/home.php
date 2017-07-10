<?php
/**
 * BuddyPress - Members Home
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div id="buddypress">

	<?php

	/**
	 * Fires before the display of member home content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_home_content' ); ?>

    <div id="item-header-wrap">
		<div class="item-scroll-header">
	    	<div id="item-header" role="complementary">

				<?php
				/**
				 * If the cover image feature is enabled, use a specific header
				 */
				if ( version_compare( BP_VERSION, '2.4', '>=' ) && bp_displayed_user_use_cover_image_header() ) :
					bp_get_template_part( 'members/single/cover-image-header' );
				else :
					bp_get_template_part( 'members/single/member-header' );
				endif;
				?>

			</div><!-- #item-header -->

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>
						<?php bp_get_displayed_user_nav(); ?>

						<?php

						/**
						 * Fires after the display of member options navigation.
						 *
						 * @since 1.2.4
						 */
						do_action( 'bp_member_options_nav' ); ?>

						<li class="more"><span> </span></li>
					</ul>
				</div>
			</div><!-- #item-nav -->

			<?php
			/* **** as21  score for profile items complete **** */

			// global $profile_template;
			// alex_debug(0,1,'xprofiles',$profile_template->groups);
			// echo "\r\n group field ids - ".bp_get_the_profile_group_field_ids()."\r\n\r\n";
			// echo "\r\n  field ids - ".bp_get_the_profile_field_ids()."\r\n\r\n";
			// $linkedin_val = xprofile_get_field_data(7, $user_id);
			// $twitter_val = xprofile_get_field(56, $user_id); // old nouse fields 56,57,18
			// alex_debug(0,1,'',$twitter_val);
			// bp_xprofile_data

			global $bp,$wpdb;
			$quest_id = $bp->displayed_user->id;
			$score = 0;

			// correctly all fields for non-logged and logged users
			$all_profile_fields = $wpdb->get_col( $wpdb->prepare(
				"SELECT value
				FROM {$wpdb->prefix}bp_xprofile_data
				WHERE user_id = %d
				ORDER BY id",
				intval( $quest_id )
			) );
			// alex_debug(0,1,'',$all_profile_fields);
			if( !empty($all_profile_fields) ){
				foreach ($all_profile_fields as $field) {
					if(!empty($field)) $score++;
				}
			}

			// echo "\r\n----Total score xprofile fields-".$score."<br>\r\n";

			// $score = 0;
			?>
			<?php  /* while( bp_profile_groups() ) : bp_the_profile_group(); ?>
				<?php if ( bp_profile_group_has_fields() ) : ?>
					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
						<?php 
							echo bp_get_the_profile_field_name()." = ".bp_get_the_profile_field_value()."<br> || \r\n"; 
							$score++;
						?>
					<?php endwhile;?>
				<?php endif; ?>
			<?php endwhile; */ ?>

			<?php 
			$count_all_timelines = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_parent='{$quest_id}' AND post_type='alex_timeline'");
			// var_dump($count_all_timelines);
			if((int)$count_all_timelines > 0) $score++;

			 $all_exper = as21_get_all_experience_from_page_edit_profile();
			 if( !empty($all_exper) ){
				 foreach ($all_exper as $exper) {
				 	// echo 'experience item = '.$exper->post_title.' || ';
				 	if( !empty($exper->post_title) ) { $score++; break; }
				 }
			  }
			/* 
			BASIC INFO-2, DETAILS-3, MISSION-1, EXPERIENCE-1, SECURITY-2 | total: 9 xprofile fields
			SOCIAL-5, EXPERIENCE-1, TIMELINE - 1  | total: 7 custom fields || THEN total - 16 fields
			Beginner,Intermediate,Advanced,Expert,All-Star // 16:5=3.2
			Mobile Number,linkdin,twitter,google+,instagram - visibility: my frends
			*/
			// echo "\r\n----Total score xprofile fields-".$score."<br>\r\n";
			?>
			<p class="a21-system-box">This block is in development !</p>
			<h5 class="profile-strength-head">Profile strength</h5>
			<div id="profile-strength">
				<?php if($score <= 3):?>	
					<div id="circle" class="c-beginner"></div>
					<div class="left-status beginner">Beginner</div>
				<?php endif;?>
				<?php if($score > 3 && $score <=6):?>	
					<div id="circle" class="c-intermediate"></div>
					<div class="left-status intermediate">Intermediate</div>
				<?php endif;?>
				<?php if($score > 6 && $score <=9):?>	
					<div id="circle" class="c-advanced"></div>
					<div class="left-status advanced">Advanced</div>
				<?php endif;?>
				<?php if($score > 9 && $score <=13):?>	
					<div id="circle" class="c-expert"></div>
					<div class="left-status expert">Expert</div>
				<?php endif;?>
				<?php if($score > 13):?>	
					<div id="circle" class="c-all-star"></div>
					<div class="left-status all-star">All-Star</div>
				<?php endif;?>
				<!-- <div class="circle"><div class="fill_beginner"></div></div> -->
				<!-- <pie class="fifty"></pie> -->
			</div>

		<?php /* **** as21  score for profile items complete **** */ ?>

		</div><!-- .item-scroll-header -->
    </div><!-- #item-header-wrap -->



	<div id="item-body">

		<?php

		/**
		 * Fires before the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_body' );

		if ( bp_is_user_activity() || !bp_current_component() ) :
			bp_get_template_part( 'members/single/activity' );

		elseif ( bp_is_user_blogs() ) :
			bp_get_template_part( 'members/single/blogs'    );

		elseif ( bp_is_user_friends() ) :
			bp_get_template_part( 'members/single/friends'  );

		elseif ( bp_is_user_groups() ) :
			bp_get_template_part( 'members/single/groups'   );

		elseif ( bp_is_user_messages() ) :
			bp_get_template_part( 'members/single/messages' );

		elseif ( bp_is_user_profile() ) :
			bp_get_template_part( 'members/single/profile'  );

		elseif ( bp_is_user_forums() ) :
			bp_get_template_part( 'members/single/forums'   );

		elseif ( bp_is_user_notifications() ) :
			bp_get_template_part( 'members/single/notifications' );

		elseif ( bp_is_user_settings() ) :
			bp_get_template_part( 'members/single/settings' );

		// If nothing sticks, load a generic template
		else :
			bp_get_template_part( 'members/single/plugins'  );

		endif;

		/**
		 * Fires after the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_body' ); ?>

	</div><!-- #item-body -->


	<?php

	/**
	 * Fires after the display of member home content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_home_content' ); ?>

</div><!-- #buddypress -->
