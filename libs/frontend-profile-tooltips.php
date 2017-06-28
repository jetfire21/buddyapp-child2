<?php

// add_action( 'admin_enqueue_scripts', 'myHelpPointers' );
add_action( 'wp_head', 'myHelpPointers' );

function myHelpPointers()
{
    $pointers = array(
        array(
            'id'       => 'xyz123',
            'screen'   => 'page',
            'target'   => '#wp-content-media-buttons',
            'title'    => 'Step 1: My ToolTip 1',
            'content'  => 'My tooltips Description 1',
            'position' => array(
                'edge'  => 'top', // top, bottom, left, right
                'align' => 'left' // top, bottom, left, right, middle
            )
        ), 
        array(
            'id'       => 'ptooltips1',
            'screen'   => 'i-am',
            'target'   => '#tooltips-name',
            'title'    => 'Step1:',
            'content'  => 'Build your Profile (image, background, name, contact details)',
            'position' => array(
                'edge'  => 'left',
                'align' => 'right',
                'zindexitem' => '998'
            )
        ),
        array(
            'id'       => 'ptooltips2',
            'screen'   => 'i-am',
            'target'   => '#tooltips-mission',
            'title'    => 'Step2:',
            'content'  => 'Add your mission statement',
            'position' => array(
                'edge'  => 'top',
                'align' => 'left',
                'zindexitem' => '998'
            )
        ),
    );
    // Step2: Add your mission statement, Step3: Add your experiences, Step4, add your social media accounts, Step5: Add causes you support and have joined... etc.)
    new B5F_Admin_Pointer( $pointers );
}


class B5F_Admin_Pointer
{
    public $screen_id;
    public $valid;
    public $pointers;

    /**
     * Register variables and start up plugin
     */
    public function __construct( $pointers = array( ) )
    {
        if( get_bloginfo( 'version' ) < '3.3' )
            return;


        /* **** as21 **** */
        // print_r($screen);
        echo "-------------tooltips777-----\r\n";
        // echo $_SERVER['REQUEST_URI'];
        // exit;
        // $this->screen_id = 'sample-page';
        $this->screen_id = 'i-am';

        // $screen = get_current_screen();
        // $this->screen_id = $screen->id;
        $this->register_pointers( $pointers );
        // add_action( 'admin_enqueue_scripts', array( $this, 'add_pointers' ), 1000 );
        // add_action( 'admin_print_footer_scripts', array( $this, 'add_scripts' ) );

        add_action( 'wp_head', array( $this, 'add_pointers' ),1000);
        add_action( 'wp_footer', array( $this, 'add_scripts' ),1000 );
    }


    /**
     * Register the available pointers for the current screen
     */
    public function register_pointers( $pointers )
    {
        $screen_pointers = null;
        foreach( $pointers as $ptr )
        {
            if( $ptr['screen'] == $this->screen_id )
            {
                $options = array(
                    'content'  => sprintf(
                        '<h3> %s </h3> <p> %s </p>', 
                        __( $ptr['title'], 'plugindomain' ), 
                        __( $ptr['content'], 'plugindomain' )
                    ),
                    'position' => $ptr['position']
                );
                $screen_pointers[$ptr['id']] = array(
                    'screen'  => $ptr['screen'],
                    'target'  => $ptr['target'],
                    'options' => $options
                );
            }
        }
        $this->pointers = $screen_pointers;
    }


    /**
     * Add pointers to the current screen if they were not dismissed
     */
    public function add_pointers()
    {
        /* **** as21 **** */
        // echo 'add_pointers1==========';
        // print_r($this);

        if( !$this->pointers || !is_array( $this->pointers ) )
            return;

        // Get dismissed pointers
        $get_dismissed = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
        $dismissed = explode( ',', (string) $get_dismissed );

        // Check pointers and remove dismissed ones.
        $valid_pointers = array( );
        foreach( $this->pointers as $pointer_id => $pointer )
        {
            if(
                in_array( $pointer_id, $dismissed ) 
                || empty( $pointer ) 
                || empty( $pointer_id ) 
                || empty( $pointer['target'] ) 
                || empty( $pointer['options'] )
            )
                continue;

            $pointer['pointer_id'] = $pointer_id;
            $valid_pointers['pointers'][] = $pointer;
        }

        if( empty( $valid_pointers ) )
            return;

        $this->valid = $valid_pointers;
        wp_enqueue_style( 'wp-pointer' );
        // wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_script( 'wp-custom-pointer',get_stylesheet_directory_uri().'/libs/wp-custom-pointer.js',array( 'jquery','jquery-ui-widget', 'jquery-ui-position' ) );
         wp_localize_script( 'wp-custom-pointer', 'wpPointerL10n', array('dismiss' => 'Dismiss' ) ); 
        // wp_enqueue_script( 'wp-custom-pointer',get_stylesheet_directory_uri().'/libs/wp-custom-pointer.js',array( 'jquery','jquery-ui-widget', 'jquery-ui-position' ),'',true );
        // wp_enqueue_script( 'wp-custom-pointer', plugins_url() . '/wp-custom-pointer.js', '','', true );


        /* **** as21 **** */
         // print_r($this);
        echo 'add_pointers end==========!';

    }



    /**
     * Print JavaScript if pointers are available
     */
    public function add_scripts()
    {
        echo "add_scripts---------------";
        if( empty( $this->valid ) )
            return;

        $pointers = json_encode( $this->valid );

        echo <<<HTML
<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready( function($) {
        var WPHelpPointer = {$pointers};

        $.each(WPHelpPointer.pointers, function(i) {
            wp_help_pointer_open(i);
        });

        function wp_help_pointer_open(i) 
        {
            pointer = WPHelpPointer.pointers[i];
            $( pointer.target ).pointer( 
            {
                content: pointer.options.content,
                position: 
                {
                    edge: pointer.options.position.edge,
                    align: pointer.options.position.align,
                    zindexitem: pointer.options.position.zindexitem
                },
                close: function() 
                {
                    $.post( ajaxurl, 
                    {
                        pointer: pointer.pointer_id,
                        action: 'dismiss-wp-pointer'
                    });
                }
            }).pointer('open');
        }
    });
//]]>
</script>
HTML;
    }
    
}