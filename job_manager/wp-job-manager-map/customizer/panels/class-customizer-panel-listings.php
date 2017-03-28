<?php
/**
 * Job Listings Panel
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Panel_Listings extends Jobify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'id' => 'listings',
            'title' => jobify()->get( 'wp-job-manager-resumes' ) ? __( 'Jobs & Resumes', 'jobify' ) : __( 'Jobs', 'jobify' ),
            'priority' => 5
        ) );

		$this->sections = array();

		$this->sections[ 'jobs' ] = array(
			'title' => __( 'Jobs', 'jobify' ),
			'controls' => array(
				'job-display-sidebar' => array(
					'label' => __( 'Widget Area Location', 'jobify' ),
					'type' => 'select',
					'choices' => array(
						'top' => __( 'Top', 'jobify' ),
						'side' => __( 'Sidebar', 'jobify' )
					)
				),
				'job-display-sidebar-columns' => array(
					'label' => __( 'Top Widget Area Columns', 'jobify' ),
					'type' => 'select',
					'choices' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4 )
				),
				'job-display-address-format' => array(
					'label' => __( 'Address Format', 'jobify' ),
					'description' => __( 'Choose between {address_1}, {address_2}, {postcode}, {city}, {state}, {state_code}, {country}. Leave empty to use location as entered.', 'jobify' )
				)
			)
		);

		// this should go in the resume integration
		if ( jobify()->get( 'wp-job-manager-resumes' ) ) {
			$this->sections[ 'resumes' ] = array(
				'title' => __( 'Resumes', 'jobify' ),
				'controls' => array(
					'resume-display-sidebar' => array(
						'label' => __( 'Widget Area Location', 'jobify' ),
						'type' => 'select',
						'choices' => array(
							'top' => __( 'Top', 'jobify' ),
							'side' => __( 'Sidebar', 'jobify' )
						)
					),
					'resume-display-sidebar-columns' => array(
						'label' => __( 'Top Widget Area Columns', 'jobify' ),
						'type' => 'select',
						'choices' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4 )
					),
					'resume-display-address-format' => array(
						'label' => __( 'Address Format', 'jobify' ),
						'description' => __( 'Choose between {address_1}, {address_2}, {postcode}, {city}, {state}, {state_code}, {country}. Leave empty to use location as entered.', 'jobify' )
					)
				)
			);
		}

		$this->sections[ 'map-behavior' ] = array(
			'title' => __( 'Map Settings', 'jobify' ),
			'controls' => array(
				'map-behavior-api-key' => array(
					'label' => __( 'Google Maps API Key', 'jobify' ),
					'description' => sprintf( __( 'Learn how to %s', 'jobify' ), '<a href="http://jobify.astoundify.com/article/1023-create-a-google-maps-api-key" target="_blank">' . __( 'create a Google Maps API key', 'jobify' ) . '</a>' ),
				),
				'map-behavior-trigger' => array(
					'label' => __( 'Info Bubble Trigger', 'jobify' ),
					'type' => 'select',
					'choices' => array(
						'mouseover' => __( 'Hover', 'jobify' ),
						'click' => __( 'Click', 'jobify' )
					)
				),
				'map-behavior-clusters' => array(
					'label' => __( 'Use Clusters', 'jobify' ),
					'type' => 'checkbox',
				),
				'map-behavior-grid-size' => array(
					'label' => __( 'Cluster Grid Size (px)', 'jobify' )
				),
				'map-behavior-autofit' => array(
					'label' => __( 'Autofit on load', 'jobify' ),
					'type' => 'checkbox'
				),
				'map-behavior-center' => array(
					'label' => __( 'Default Center Coordinate', 'jobify' )
				),
				'map-behavior-zoom' => array(
					'label' => __( 'Default Zoom Level', 'jobify' ),
					'type' => 'select',
					'choices' => $this->map_zoom_ints()
				),
				'map-behavior-max-zoom' => array(
					'label' => __( 'Max Zoom In Level', 'jobify' ),
					'type' => 'select',
					'choices' => $this->map_zoom_ints()
				),
				'map-behavior-max-zoom-out' => array(
					'label' => __( 'Max Zoom Out Level', 'jobify' ),
					'type' => 'select',
					'choices' => $this->map_zoom_ints()
				),
				'map-behavior-scrollwheel' => array(
					'label' => __( 'Zoom with Scrollwheel', 'jobify' ),
					'type' => 'checkbox'
				)
			)
		);


		return $this->sections;
    }

    public function map_zoom_ints() {
        return array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18' );
    }

}
