<?php

// class Jobify_WP_Job_Manager_Map {
class Jobify_WP_Job_Manager_Map extends WP_Job_Manager {

    public function __construct() {
        add_filter( 'jobify_listing_data', array( $this, 'job_listing_data' ) );
        add_filter( 'jobify_listing_data', array( $this, 'create_job_listing_data' ), 99 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'jobify_output_map', array( $this, 'output_map' ) );
        add_action( 'wp_footer', array( $this, 'infobubble_template' ) );
        // a21
        // echo "====alex777====";
        // exit;
    }

    public function job_listing_data( $data ) {
        global $post, $jobify_job_manager;

        $data = $output = array();

        /** Longitude */
        $long = esc_attr( $post->geolocation_long );

        if ( $long ) {
            $data[ 'longitude' ] = $long;
        }

        /** Latitude */
        $lat = esc_attr( $post->geolocation_lat );

        if ( $lat ) {
            $data[ 'latitude' ] = $lat;
        }

        /** Title */
        if ( 'job_listing' == $post->post_type ) {
            if ( $post->_company_name ) {
                $data[ 'title' ] = sprintf( __( '%s at %s', 'jobify' ), $post->post_title, $post->_company_name );
            } else {
                $data[ 'title' ] = $post->post_title;
            }
        } else {
            $data[ 'title' ] = sprintf( __( '%s - %s', 'jobify' ), $post->post_title, $post->_candidate_title );
        }

        /** Link */
        $data[ 'href' ] = get_permalink( $post->ID );

        foreach ( $data as $key => $value ) {
            $output[] .= sprintf( 'data-%s="%s"', $key, $value );
        }

        return $output;
    }

    public function create_job_listing_data( $data ) {
        return implode( ' ', $data );
    }

    public function infobubble_template() {
        // locate_template( array( 'tmpl/tmpl-infobubble.php' ), true );
        include( 'tmpl/tmpl-infobubble.php' );
    }

    public function enqueue_scripts() {
        $deps = array(
            'jquery',
            'jquery-ui-slider',
            'google-maps',
            'wp-backbone',
            'wp-job-manager-ajax-filters',
        );

        if ( class_exists( 'WP_Job_Manager_Extended_Location' ) ) {
            $deps[] = 'wpjm-extended-location';
        }

        $deps[] = 'jobify';

        $base = 'https://maps.googleapis.com/maps/api/js'; 
        $args = array(
            'v' => 3,
            'libraries' => 'geometry,places',
            'language' => get_locale() ? substr( get_locale(), 0, 2 ) : ''
        );

		// if ( '' != get_theme_mod( 'map-behavior-api-key', false ) ) {
  //           $args[ 'key' ] = get_theme_mod( 'map-behavior-api-key' );
		// }
        $args[ 'key' ] = "AIzaSyAJOQtVZMrDEIVLt8uNBIJbMNou5LkzT-c";


        // wp_enqueue_script( 'jquery-migrate','',array('jquery') );
        wp_enqueue_script( 'backbone' );
        wp_enqueue_script( 'wp-backbone' );
        // wp_enqueue_script( 'google-maps', esc_url_raw( add_query_arg( $args, $base ) ) );
        // wp_enqueue_script( 'google-maps', "https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry%2Cplaces&language=en&key=AIzaSyAJOQtVZMrDEIVLt8uNBIJbMNou5LkzT-c" );
        // wp_enqueue_script( 'jobify-app-map', jobify()->get( 'wp-job-manager' )->get_url() . 'js/map/app.min.js', $deps, '20150213', true );
        // wp_enqueue_script( 'jobify-app-map', get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/app.min.js",'','', true );
        // /themes/jobify/inc/integrations/wp-job-manager/js/map/vendor/infobubble/infobubble.js
        wp_enqueue_script( 'jobify-infobubble', get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/vendor/infobubble/infobubble.js",'','', true );
        // /home/jetfire/www/my-wp.dev/wp-content/themes/jobify/inc/integrations/wp-job-manager/js/map/vendor/markerclusterer/markerclusterer.js
        wp_enqueue_script( 'jobify-marker', get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/vendor/markerclusterer/markerclusterer.js",'','', true );
        wp_enqueue_script( 'jobify-app-richm', get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/vendor/richmarker/richmarker.js",array('jquery-deserialize','wp-job-manager-ajax-filters'),'', true );
        // /home/jetfire/www/my-wp.dev/wp-content/themes/jobify/inc/integrations/wp-job-manager/js/map/vendor/richmarker/richmarker.js
        // wp_enqueue_script( 'jobify-app-map', get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/app.js",array('jquery-deserialize','wp-job-manager-ajax-filters'),'', true );
         wp_enqueue_script( 'jobify-orig', get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/jobify.min.js",array('jquery-deserialize','wp-job-manager-ajax-filters'),'', true );

        // wp_register_script( 'kleo-app', get_template_directory_uri() . '/assets/js/functions' . $min . '.js', array('jquery', 'kleo-plugins' ));
         // buddyapp/assets/js/plugins.js
        // wp_deregister_script("kleo-app");
        // wp_deregister_script("kleo-plugins");
        wp_enqueue_script( 'jobify-app-map', get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/app.min.js",array('jquery-deserialize','wp-job-manager-ajax-filters'),'', true );
        // echo get_stylesheet_directory_uri()."/job_manager/wp-job-manager-map/js/map/app.min.js";
        // exit;

// var jobifyMapSettings = {"useClusters":"1","overlayTitle":"%d Found","autoFit":"1","trigger":"mouseover","mapOptions":{"zoom":3,"maxZoom":17,"maxZoomOut":3,"gridSize":60,"scrollwheel":true,"center":["-43.999792","170.463352"]}};
        $settings = array(
            // 'useClusters' => (bool) jobify_theme_mod( 'map-behavior-clusters' ),
            // 'overlayTitle' => __( '%d Found', 'jobify' ),
            // 'autoFit' => jobify_theme_mod( 'map-behavior-autofit' ),
            // 'trigger' => jobify_theme_mod( 'map-behavior-trigger' ),
            // 'mapOptions' => array(
            //     'zoom' => jobify_theme_mod( 'map-behavior-zoom' ),
            //     'maxZoom' => jobify_theme_mod( 'map-behavior-max-zoom' ),
            //     'maxZoomOut' => jobify_theme_mod( 'map-behavior-max-zoom-out' ),
            //     'gridSize' => jobify_theme_mod( 'map-behavior-grid-size' ),
            //     'scrollwheel' => jobify_theme_mod( 'map-behavior-scrollwheel' ) == 'on' ? true : false
            'useClusters' => true,
            'overlayTitle' => "%d Found",
            'autoFit' => true,
            'trigger' => 'mouseover',
            'mapOptions' => array(
                'zoom' => 3,
                'maxZoom' => 17,
                'maxZoomOut' => 3,
                'gridSize' => "60",
                'scrollwheel' => true
            )
        );

        // if ( '' != ( $center = jobify_theme_mod( 'map-behavior-center' ) ) ) {
        //     $settings[ 'mapOptions'][ 'center' ] = array_map( 'trim', explode( ',', $center ) );
        // }
        $settings[ 'mapOptions'][ 'center' ] = "['-43.999792','170.463352']";
        // $settings[ 'mapOptions'][ 'center' ] = '-43.999792,170.463352';

        if ( has_filter( 'job_manager_geolocation_region_cctld' ) ) {
            $settings[ 'autoComplete' ][ 'componentRestrictions' ] = array(
                'country' => $bias
            );
        }

        $settings = apply_filters( 'jobify_map_settings', $settings );

        wp_localize_script( 'jobify-app-map', 'jobifyMapSettings', apply_filters( 'jobify_map_settings', $settings ) );
    }

    public function output_map( $type = false ) {
        if ( ! $type ) {
            $type = 'job_listing';
        }

       // echo "alex777 function output map ";
       // $map = locate_template( array( 'content-job_listing-map.php' ), false, false );
       $map = "content-job_listing-map.php";
        // a21
       // exit;

        include( $map );
    }

    public function job_manager_get_listings_custom_filter_text( $text ) {
        $params = array();

        parse_str( $_POST[ 'form_data' ], $params );

        if ( ! isset( $params[ 'search_lat' ] ) || '' == $params[ 'search_lat' ] ) {
            return $text;
        }

        $text .= ' ' . sprintf( __( 'within a %d mile radius', 'classify' ), $params[ 'search_radius' ] );

        return $text;
    }

}
// a21
$GLOBALS['job_manager_map'] = new Jobify_WP_Job_Manager_Map();


// class Jobify_WP_Job_Manager_Extended_Location {

//     public function __construct() {
//         add_action( 'job_manager_settings', array( $this, 'settings' ), 11 );
//     }

//     public function settings( $settings ) {
//         unset( $settings[ 'wpjmel_settings' ][1][0] );

//         return $settings;
//     }

// }

// $_GLOBALS[ 'jobify_job_manager_extended_location' ] = new Jobify_WP_Job_Manager_Extended_Location();
