<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php

	/**
	 * Fires before the display of the group members content.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_before_group_members_content' ); ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the display of the group members list.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_before_group_members_list' ); ?>

	<ul id="member-list" class="item-list">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<li>
			<?php 
			// echo $cover_url = get_cover_image_from_db();
			// if( bp_is_members_component() )  $user_id = bp_get_member_user_id();
			// if( bp_is_user() ) $user_id = $bp->displayed_user->id;
			//  $user_id = bp_get_member_user_id();
			// echo "user_id ".$user_id ;
			 // deb_last_query();
			 ?>

				<div class="item-wrap">
					<!-- <div <?php // echo kleo_bp_get_member_cover_attr(); ?>> -->
					<div 
					<?php if(kleo_bp_get_member_cover_attr() == 'class="item-cover"') echo as21_get_cover_image_from_db_for_fb();
					 else echo kleo_bp_get_member_cover_attr();	?>
					>
						<div class="profile-cover-inner"></div>
						<div class="item-avatar">
							<a href="<?php bp_group_member_domain(); ?>">
								<?php bp_group_member_avatar_thumb(); ?>
							</a>
							<?php do_action('bp_member_online_status', bp_get_member_user_id()); ?>
						</div>
					</div>

					<div class="item">

						<div class="item-title">
							<?php bp_group_member_link(); ?>
						</div>

						<div class="item-meta"><span class="activity"><?php bp_group_member_joined_since(); ?></span></div>

						<?php

						/**
						 * Fires inside the listing of an individual group member listing item.
						 *
						 * @since BuddyPress (1.1.0)
						 */
						// do_action( 'bp_group_members_list_item' );

						/** copy all form members loop /wp-content/themes/buddyapp-child/buddypress/members/members-loop.php 
						the ratings stars show in their member profile, !!!! только этот цикл показывает всех имеющихся пользователей,даже тех кто не состоит в даннной группе
						*/

	 					do_action( 'bp_directory_members_item' );
	 					 ?>



						<?php if ( bp_is_active( 'friends' ) ) : ?>

							<div class="action">

								<?php //bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

								<?php

								/**
								 * Fires inside the action section of an individual group member listing item.
								 *
								 * @since BuddyPress (1.1.0)
								 */
								// do_action( 'bp_group_members_list_item_action' );
								do_action( 'bp_directory_members_actions' ); 

								 ?>

							</div>
							
						<?php endif; ?>
					</div><!-- end item -->
				</div><!-- end item-wrap -->
			</li>
		<?php endwhile; ?>

	</ul>

	<?php

	/**
	 * Fires after the display of the group members list.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires after the display of the group members content.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'No members were found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>


