<?php

class Jobify_WP_Job_Manager_Customizer {

    public function __construct() {
        $panels = array( 'listings' );

        foreach ( $panels as $panel ) {
            require_once( dirname( __FILE__ ) . '/panels/class-customizer-panel-' . $panel . '.php' );
        }

        add_filter( 'jobify_customizer_panels', array( $this, 'panels' ) );
        add_action( 'customize_register', array( $this, 'set_panels' ), 12 );

		// this needs to be added at 0 because widgets_init fires at 1, which could call `jobify_theme_mod`
		// first and set the defaults before we get a chance to.
		add_action( 'init', array( $this, 'filter_theme_mods' ), 0 );
    }

	/**
	 * Add `listings` panel to array of registered panels.
	 *
	 * @since 3.0.0
	 *
	 * @param object $panels
	 * @return object $panels
	 */
    public function panels( $panels ) {
        $panels[] = 'listings';

        return $panels;
    }

	/**
	 * Attach the listing panel object to the panels object.
	 * 
	 * @since 3.0.0
	 *
	 * @return object
	 */
    public function set_panels() {
        jobify_customizer()->panels->listings = new Jobify_Customizer_Panel_Listings();
    }

	/**
	 * We need to wait until `init` runs to filter our theme mod defaults.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function filter_theme_mods() {
        add_filter( 'jobify_theme_mod_defaults', array( $this, 'theme_mod_defaults' ) );
	}

    /**
	 * Set defaults for Jobs & Resumes
	 *
	 * @since 3.0.0
	 *
	 * @param array $mods
	 * @return array $mods
     */
    public function theme_mod_defaults( $mods ) {
        $mods[ 'map-behavior-trigger' ] = 'mouseover';
        $mods[ 'map-behavior-clusters' ] = 1;
        $mods[ 'map-behavior-grid-size' ] = 60;
        $mods[ 'map-behavior-autofit' ] = 1;
        $mods[ 'map-behavior-center' ] = '';
        $mods[ 'map-behavior-zoom' ] = 3;
        $mods[ 'map-behavior-max-zoom' ] = 17;
        $mods[ 'map-behavior-max-zoom-out' ] = 3;
        $mods[ 'map-behavior-scrollwheel' ] = 'on';

		$mods[ 'job-display-sidebar' ] = 'top';
		$mods[ 'job-display-sidebar-columns' ] = 3;
		$mods[ 'job-display-address-format' ] = '{city}, {state}';

		$mods[ 'resume-display-sidebar' ] = 'top';
		$mods[ 'resume-display-sidebar-columns' ] = 3;
		$mods[ 'resume-display-address-format' ] = '{city}, {state}';
		
        return $mods;
    }

}
