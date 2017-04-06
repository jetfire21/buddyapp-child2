<?php
/**
 * After content wrap
 * Used in all templates
 */
?>
<?php
$container = apply_filters('kleo_main_container_class','container');
?>

			<?php
			/**
			 * After main content - action
			 */
			do_action('kleo_after_main_content');
			?>

			</div><!--end .content-wrap-->
		</div><!--end .main-->

	<?php if( bp_is_user() ):?>
		<div class="sidebar sidebar-colors a21-right-column-for-widgets">
		<div class="inner-content widgets-container">
			<?php if ( function_exists('dynamic_sidebar') ) dynamic_sidebar('right-sidebar-for-member');?>
		</div>
		</div>
	<?php endif;?>

	<?php if( bp_is_group() && !bp_is_group_create() && !is_404()):?>
		<div class="sidebar sidebar-colors a21-right-column-for-widgets only-for-group-sidebar">
		<div class="inner-content widgets-container">
		<?php if ( function_exists('dynamic_sidebar') ) dynamic_sidebar('right-sidebar-for-group');?>
		</div>
		</div>
	<?php endif;?>

		<?php
		/**
		 * After main content - action
		 */
		do_action('kleo_after_content');
		?>

	</div><!--end #main-container-->
</div>
<!--END MAIN SECTION-->