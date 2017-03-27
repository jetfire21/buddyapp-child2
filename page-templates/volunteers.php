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
<!-- <div id="a21_map"></div> -->
  <div id="a21_job_map"></div>
    <script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('a21_job_map'), {
          zoom: 3,
          center: {lat: -28.024, lng: 140.887}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = [
        {lat: -31.563910, lng: 147.154312},
        {lat: -33.718234, lng: 150.363181}
        // {lat: -33.727111, lng: 150.371124},
        // {lat: -33.848588, lng: 151.209834},
        // {lat: -33.851702, lng: 151.216968},
        // {lat: -34.671264, lng: 150.863657},
        // {lat: -35.304724, lng: 148.662905},
        // {lat: -36.817685, lng: 175.699196},
        // {lat: -36.828611, lng: 175.790222},
        // {lat: -37.750000, lng: 145.116667},
        // {lat: -37.759859, lng: 145.128708},
        // {lat: -37.765015, lng: 145.133858},
        // {lat: -37.770104, lng: 145.143299},
        // {lat: -37.773700, lng: 145.145187},
        // {lat: -37.774785, lng: 145.137978},
        // {lat: -37.819616, lng: 144.968119},
        // {lat: -38.330766, lng: 144.695692},
        // {lat: -39.927193, lng: 175.053218},
        // {lat: -41.330162, lng: 174.865694},
        // {lat: -42.734358, lng: 147.439506},
        // {lat: -42.734358, lng: 147.501315},
        // {lat: -42.735258, lng: 147.438000},
        // {lat: -43.999792, lng: 170.463352}
      ]
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJOQtVZMrDEIVLt8uNBIJbMNou5LkzT-c&callback=initMap">
    </script>

<div class="container content-area">
<!-- <div class="main-content <?php echo Kleo::get_config('container_class'); ?>"> -->

<h1>Under development</h1>

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