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

		if ( is_user_logged_in() ) {
			global $bp,$wpdb;
			$quest_id = $bp->displayed_user->id;
			$score = 0;

			// correctly get all fields for non-logged and logged users,cause some fields is limited for visibility
			$all_profile_fields = $wpdb->get_col( $wpdb->prepare(
				"SELECT value
				FROM {$wpdb->prefix}bp_xprofile_data
				WHERE user_id = %d
				ORDER BY id",
				intval( $quest_id )
			) );
			if( !empty($all_profile_fields) ){
				foreach ($all_profile_fields as $field) {
					if( !empty($field) && strpos(strtolower($field),'"no"') === false) $score++;
				}
			}

			?>


			<?php 
			$count_all_timelines = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_parent='{$quest_id}' AND post_type='alex_timeline'");
			if((int)$count_all_timelines > 0) $score++;

			 $all_exper = as21_get_all_experience_from_page_edit_profile();
			 if( !empty($all_exper) ){
				 foreach ($all_exper as $exper) {
				 	if( !empty($exper->post_title) ) { $score++; break; }
				 }
			  }
			/* 
			BASIC INFO-2, DETAILS-3, MISSION-1, EXPERIENCE-1, SECURITY-2 | total: 9 xprofile fields
			SOCIAL-5, EXPERIENCE-1, TIMELINE - 1  | total: 7 custom fields || THEN total - 16 fields
			Beginner,Intermediate,Advanced,Expert,All-Star // 16:5=3.2
			causes - 1 // total - 17:5=3.4   |   participation in events of site - 1 // total - 18:5=3.6
			Mobile Number,linkdin,twitter,google+,instagram - visibility: my frends
			*/
			$member_gr_ids =  groups_get_user_groups( $quest_id ); 
			if($member_gr_ids['total'] > 0) $score++;
			$user_events_ids = $wpdb->get_col( $wpdb->prepare(
				"SELECT guid
				FROM {$wpdb->posts}
				WHERE post_parent = %d AND post_type = %s AND guid > 0",
				$quest_id,
				"alex_timeline"
			) );
			if(count($user_events_ids)>0) $score++;
			?>
			<!-- <p class="a21-system-box">This block is in development !</p> -->
			<h5 class="profile-strength-head">Profile strength</h5>
			<div id="profile-strength">
				<?php if($score < 5):?>	
					<div id="circle" class="c-beginner"></div>
					<div class="left-status beginner">Beginner</div>
				<?php endif;?>
				<?php if($score > 4 && $score < 9):?>	
					<div id="circle" class="c-intermediate"></div>
					<div class="left-status intermediate">Intermediate</div>
				<?php endif;?>
				<?php if($score > 8 && $score < 13):?>	
					<div id="circle" class="c-advanced"></div>
					<div class="left-status advanced">Advanced</div>
				<?php endif;?>
				<?php if($score > 12 && $score < 16):?>	
					<div id="circle" class="c-expert"></div>
					<div class="left-status expert">Expert</div>
				<?php endif;?>
				<?php if($score > 15):?>	
					<div id="circle" class="c-all-star"></div>
					<div class="left-status all-star">All-Star</div>
				<?php endif;?>
			</div>

    <?php } /* **** as21  score for profile items complete **** */ ?>

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
