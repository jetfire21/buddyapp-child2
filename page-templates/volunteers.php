<?php
/**
 * Template Name: Volunteers
 *
 * Description: Template withour sidebar for volunteers
 *
 * @package WordPress
 * @subpackage BuddyApp
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since BuddyApp 1.0
 */

get_header(); ?>

<?php
//create full width template
kleo_switch_layout('full');
?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php

remove_action( 'kleo_page_title_section', 'kleo_show_title_tagline', 14 ); 
remove_action( 'kleo_page_title_section', 'kleo_show_page_title', 12 );
add_action( 'kleo_page_title_section', 'a21_kleo_show_page_title', 12 ); 
function a21_kleo_show_page_title(){
	?>
	<div class="a21_inner_page_title">
	<h1>VOLUNTEER JOB BOARD</h1>
	<span>Where DuGoodrs impact causes most</span>
	</div>
	<?php
	wp_nav_menu( array(
		'theme_location'  => 'job_menu',
		'menu'            => '', 
		'container'       => '', 
		'container_class' => '', 
		'container_id'    => '',
		'menu_class'      => '', 
		'menu_id'         => 'a21_job_menu',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => '',
	) );

}

// <div class="item-list-tabs no-ajax" id="subnav" role="navigation">
?>

<?php get_template_part( 'page-parts/page-title' ); ?>




<?php
    global $wpdb;
    $website = $wpdb->get_results( $wpdb->prepare(
      "SELECT post_content
      FROM {$wpdb->postmeta}
      WHERE post_parent = %d
          AND post_type = %s
          AND post_title = %s
      ORDER BY ID ASC",
      intval( $gid ),
      // "alex_gfilds",
      "alex_grsoclink",
      'Website'
    ) );

?>
 <?php if( is_page('jobs') ) do_action( 'jobify_output_map' ); ?>

<div class="container content-area">
<!-- <div class="main-content <?php echo Kleo::get_config('container_class'); ?>"> -->

<!-- <h1>Under development</h1> -->

	<?php
	if ( have_posts() ) :
		// Start the Loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			get_template_part( 'content', 'page' );

		endwhile;

	endif;
	?>

</div>
        
<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>