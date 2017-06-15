<?php

/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter() as21 will work on search results e.g: site.com/i-am/?s=test
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php

/**
 * Fires before the display of groups from the groups loop.
 *
 * @since BuddyPress (1.2.0)
 */
do_action( 'bp_before_groups_loop' ); ?>

<?php
$search_string = esc_html($_GET['s']);
 // if (  bp_has_groups(bp_ajax_querystring( 'groups' )."&search_terms=".$search_string."&update_admin_cache=true&per_page=50" )  ) :
// echo 'grs_q='.$grs_query = bp_has_groups( bp_ajax_querystring( 'groups' )."&search_terms=".$search_string."&update_admin_cache=true&per_page=3" );
 // if (bp_has_groups( bp_ajax_querystring( 'groups' )."&search_terms=".$search_string."&update_admin_cache=true&per_page=3" )) :
 if (bp_has_groups( bp_ajax_querystring( 'groups' )."&search_terms=".$search_string."&per_page=50" )) :
  ?>
		<h5 class="text_between_memb_and_gr">Or did you mean <?php echo ucfirst(bp_get_groups_root_slug());?> such as:</h5>

	<div id="pag-top" class="pagination" xmlns="http://www.w3.org/1999/html">

		<div class="pag-count" id="group-dir-count-top">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-top">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the listing of the groups list.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_before_directory_groups_list' ); ?>
	<ul id="groups-list" class="item-list">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li <?php bp_group_class(); ?>>
			<div class="item-wrap">
			<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
				<div <?php echo kleo_bp_get_group_cover_attr();?>>
					<div class="item-avatar">
						<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
					</div>
				</div>
			<?php endif; ?>

			<div class="item">

					<div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div>
					<div class="item-meta"><span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span></div>

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

				<?php

				/**
				 * Fires inside the listing of an individual group listing item.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_directory_groups_item' ); ?>

				<div class="action">

					<?php

					/**
					 * Fires inside the action section of an individual group listing item.
					 *
					 * @since BuddyPress (1.1.0)
					 */
					do_action( 'bp_directory_groups_actions' ); ?>

				</div>
				<div class="meta">
					<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>
				</div>
				<div class="meta">
					Volunteer Jobs: <?php echo as21_wjm_get_manually_jobs_count_by_group_id( bp_get_group_id() );?>
				</div>
			</div>



			</div><!-- end item-wrap -->
		</li>

	<?php endwhile; ?>

	</ul>

	<?php

	/**
	 * Fires after the listing of the groups list.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-bottom">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of groups from the groups loop.
 *
 * @since BuddyPress (1.2.0)
 */
do_action( 'bp_after_groups_loop' ); ?>
