<?php
/*
 @wordpress-plugin
 Plugin Name:       BP Member Reviews
 Plugin URI:        https://wordpress.org/plugins/bp-user-reviews/
 Description:       BuddyPress plugin to enable reviews and ratings of members.
 Version:           1.2.6
 Author:            wordplus, sooskriszta
 Author URI:        https://profiles.wordpress.org/wordplus/
 Text Domain:       bp-user-reviews
 Domain Path:       /languages
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists('BP_Member_Reviews') ) :

    class BP_Member_Reviews
    {

        public $url;

        public $path;

        public $post_type;

        public $settings;

        public $version;

        public function __construct() {
            $this->url          = plugin_dir_url (__FILE__);
            $this->path         = plugin_dir_path(__FILE__);
            $this->post_type    = 'bp-user-reviews';
            $this->version      = '1.2.6';

            $defaults = array(
                'access'     => 'registered',
                'autoApprove' => 'no',
                'stars'      => 5,
                'criterion'  => 'single',
                'min_length' => 50,
                'review'     => 'no',
                'multiple'   => 'no',
                'starsColor' => '#000000',
                'date_format' => 'd.m.Y'
            );

            $args = get_option('bp-user-reviews-settings', array());

            $this->settings = wp_parse_args( $args, $defaults );

            add_action( 'init',                                     array($this, 'load_textDomain') );
            add_action( 'init',                                     array($this, 'register_post_type') );

            /**
             * Frontend Side
             */
            if(apply_filters('bp_user_reviews_visible', true)) {
                add_action('bp_setup_nav',          	 array($this, 'add_tabs'), 100);
                add_action('bp_enqueue_scripts',    	 array($this, 'bp_screen_scripts'));
                add_action('wp_enqueue_scripts',    	 array($this, 'screen_scripts'));
                add_action('wp_head',               	 array($this, 'styles'));
                add_action('wp_ajax_bp_user_review', 	 array($this, 'ajax_review'));
                if ($this->settings['access'] == 'all') {
                    add_action('wp_ajax_nopriv_bp_user_review', array($this, 'ajax_review'));
                }
            }

            add_action('bp_profile_header_meta', 	   array($this, 'embed_rating'));

            add_action('bp_directory_members_actions', array($this, 'ember_rating_directory'));

            add_action('bbp_theme_after_reply_author_details',   array($this, 'ember_forum_post') );

            /**
             * Admin side
             */
            add_action( 'admin_menu',                                array($this, 'settings_page') );
            add_action( 'admin_enqueue_scripts',                     array($this, 'admin_screen_scripts') );
            add_action( 'add_meta_boxes',                            array($this, 'register_meta_boxes') );
            add_action( 'save_post',                                 array($this, 'save_meta_box') );
            add_action( 'admin_footer-edit.php',                     array($this, 'append_post_status_listing') );
            add_action( 'admin_footer-post.php',                     array($this, 'append_post_status_list') );
            add_action( 'admin_footer-post-new.php',                 array($this, 'append_post_status_list') );
            add_action( 'manage_posts_custom_column',                array($this, 'review_columns'), 10, 2 );
            add_filter( 'post_row_actions',                          array($this, 'review_actions') );
            add_action( 'post_submitbox_minor_actions',              array($this, 'spam_button') );
            add_action( 'post_action_spam',                          array($this, 'toSpam'), 10, 1 );
            add_action( 'post_action_unspam',                        array($this, 'unSpam'), 10, 1 );
            add_action( 'post_action_unpublish',                     array($this, 'unPublish'), 10, 1 );
            add_action( 'post_action_publish',                       array($this, 'publish'), 10, 1 );


            add_filter( 'display_post_states',                       array($this, 'new_states') );
            add_filter( 'manage_'.$this->post_type.'_posts_columns', array($this, 'add_review_columns') );
            add_action( 'post_action_publish',                       array($this, 'publish'), 10, 1 );
            add_action( 'admin_init',                                array($this, 'post_type_label'));
        }

        public function load_textDomain(){
            load_plugin_textdomain( 'bp-user-reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }

        public function review_actions( $actions, $post = false ) {
            global $post;
            if($post->post_type != $this->post_type) return $actions;

            $link = add_query_arg(
                array(
                    'action' => 'publish',
                    '_nonce' => wp_create_nonce('publish_'.$post->ID)
                ), get_edit_post_link($post->ID)
            );
            if($post->post_status != 'spam' && $post->post_status != 'publish') $newActions['publish'] = '<a href="'.$link.'">'. __('Publish', 'bp-user-reviews') .'</a>';

            $link = add_query_arg(
                array(
                    'action' => 'unpublish',
                    '_nonce' => wp_create_nonce('unpublish_'.$post->ID)
                ), get_edit_post_link($post->ID)
            );
            if($post->post_status == 'publish') $newActions['unpublish'] = '<a href="'.$link.'">'. __('Unpublish', 'bp-user-reviews') .'</a>';
            if(isset($actions['edit']) && $post->post_status != 'spam') $newActions['edit'] = $actions['edit'];
            if($post->post_status != 'spam') {
                $link = add_query_arg(
                    array(
                        'action' => 'spam',
                        '_nonce' => wp_create_nonce('spam_'.$post->ID)
                    ), get_edit_post_link($post->ID)
                );
                $newActions['spam'] = '<a href="'.$link.'">'. _x( 'Spam', 'comment status' )  .'</a>';
            } else {
                $link = add_query_arg(
                    array(
                        'action' => 'unspam',
                        '_nonce' => wp_create_nonce('unspam_'.$post->ID)
                    ), get_edit_post_link($post->ID)
                );
                $newActions['unspam'] = '<a href="'.$link.'">'. _x('Not Spam', 'comment') .'</a>';
            }
            if(isset($actions['trash'])) $newActions['trash'] = $actions['trash'];
            if(isset($actions['untrash'])) $newActions['untrash'] = $actions['untrash'];
            if(isset($actions['delete'])) $newActions['trash'] = $actions['delete'];
            return $newActions;
        }

        public function review_columns( $column, $post_id ) {
            global $post;
            switch ( $column ) {
                case 'bp-user-reviews-user' :
                    echo $this->reviewed();
                    break;
                case 'bp-user-reviews-author' :
                    echo $this->author();
                    break;
                case 'bp-user-reviews-review' : ?>

                    <?php $this->print_stars_admin($post->average, $post->stars); ?><br>
                    <?php echo $post->review; ?>
                    <?php break;
            }
        }

        public function add_review_columns($columns) {
            return array(
                'cb' => '<input type="checkbox">',
                'bp-user-reviews-review' => __('Review', 'bp-user-reviews'),
                'bp-user-reviews-user' => __('User', 'bp-user-reviews'),
                'bp-user-reviews-author' => __('Author', 'bp-user-reviews'),
                'date' => __('Date', 'bp-user-reviews')
            );
        }

        /**
         * Process form
         */
        public function ajax_review(){
            $user_id = intval($_POST['user_id']);
            if( !wp_verify_nonce( $_POST['_wpnonce'], 'bp-user-review-new-'.$user_id ) ) die();

            $stars      = $this->settings['stars'];
            $criterions = $this->settings['criterions'];

            $post = array(
                'post_type'   => $this->post_type,
                'post_status' => 'pending'
            );

            if($this->settings['autoApprove'] == 'yes'){
                $post['post_status'] = 'publish';
            }

            $response = array(
                'result' => true,
                'errors' => array()
            );

            if( ! apply_filters( 'bp_members_reviews_review_allowed', true, get_current_user_id(), $user_id ) ){
                $response['result'] = false;
                $response['errors'][] = __('You can not put review for this user', 'bp-user-reviews');
            }

            if(is_user_logged_in() && (get_current_user_id() == $user_id)){
                $response['result'] = false;
                $response['errors'][] = __('You can not put yourself reviews', 'bp-user-reviews');
            }

            $review_meta = array(
                'user_id' => $user_id,
                'stars'   => $stars,
                'type'    => $this->settings['criterion'],
                'guest'   => false
            );

            if( ! is_user_logged_in() ){
                $review_meta['guest'] = true;

                if(!isset($_POST['name']) || empty($_POST['name'])){
                    $response['result'] = false;
                    $response['errors'][] = __('Name field is required', 'bp-user-reviews');
                } else {
                    $review_meta['name'] = esc_attr($_POST['name']);
                }

                if(!isset($_POST['email']) || empty($_POST['email'])){
                    $response['result'] = false;
                    $response['errors'][] = __('Email field is required', 'bp-user-reviews');
                } elseif (!is_email($_POST['email'])){
                    $response['result'] = false;
                    $response['errors'][] = __('Email is wrong', 'bp-user-reviews');
                } else {
                    $review_meta['email'] = esc_attr($_POST['email']);
                }
            }

            if($this->settings['multiple'] == 'no'){
                if(!is_user_logged_in()){
                    if($this->checkIfReviewExists($review_meta['email'], $user_id) > 0){
                        $response['result'] = false;
                        $response['errors'][] = __('Already reviewed by you', 'bp-user-reviews');
                    }
                } else {
                    if($this->checkIfReviewExists(get_current_user_id(), $user_id) > 0){
                        $response['result'] = false;
                        $response['errors'][] = __('Already reviewed by you', 'bp-user-reviews');
                    }
                }
            }

            if(!is_array($_POST['criteria'])){
                $val = esc_attr($_POST['criteria']);

                if($val < 1 || $val > $stars){
                    $response['result'] = false;
                    $response['errors']['empty'] = __('You must select all stars', 'bp-user-reviews');
                }

                $review_meta['average'] = ($val / $stars) * 100;
            } else {
                foreach($_POST['criteria'] as $index=>$val){
                    if($val < 1 || $val > $stars){
                        $response['result'] = false;
                        $response['errors']['empty'] = __('You must select all stars', 'bp-user-reviews');
                        continue;
                    }

                    $name = $criterions[$index];
                    $review_meta['criterions'][$name] = (esc_attr($val) / $stars) * 100;
                }

                $review_meta['average'] = round( array_sum($review_meta['criterions']) / count($review_meta['criterions']) );
            }


            if($this->settings['review'] == 'yes') {
                if (empty($_POST['review'])) {
                    $response['result'] = false;
                    $response['errors'][] = __('Review can`t be empty', 'bp-user-reviews');
                } elseif (mb_strlen($_POST['review']) < $this->settings['min_length']) {
                    $response['result'] = false;
                    $response['errors'][] = sprintf(__('Review must be at least %s characters', 'bp-user-reviews'), $this->settings['min_length']);
                } else {
                    $review_meta['review'] = esc_textarea($_POST['review']);
                }
            }

            if (class_exists('Akismet')){
                $review['user_ip']      = Akismet::get_ip_address();
                $review['blog']         = get_option( 'home' );
                $review['blog_lang']    = get_locale();
                $review['blog_charset'] = get_option('blog_charset');
                if(!is_user_logged_in()){
                    $review['comment_author']       = $review_meta['name'];
                    $review['comment_author_email'] = $review_meta['email'];
                } else {
                    $user = get_userdata($user_id);
                    $review['comment_author']       = $user->display_name;
                    $review['comment_author_email'] = $user->user_email;
                }
                $review['comment_content'] = esc_attr($_POST['review']);

                $valid = Akismet::http_post( Akismet::build_query( $review ), 'comment-check' )[1];

                if($valid == false){
                    $post['post_status'] = 'spam';
                }
            }

            if($response['result'] === true){
                $review_id = wp_insert_post($post);

                foreach($review_meta as $key=>$value){
                    if(is_string($value)) $value = trim($value);
                    update_post_meta($review_id, $key, $value);
                }
            }

            wp_send_json($response);
            die();
        }

        public function checkIfReviewExists($user, $user_id){
            $args = array(
                'post_type'   => $this->post_type,
                'post_status' => array( 'pending', 'publish' ),
                'posts_per_page'  => -1,
                'post_author' => $user,
                'meta_query' => array(
                    array(
                        'key' =>  'user_id',
                        'value' => $user_id
                    )
                )
            );

            if(is_email($user)){
                $args['meta_query'][] = array(
                    'key'     => 'email',
                    'value'   => $user
                );

                unset($args['post_author']);
            }

            $reviews = new WP_Query($args);

            return $reviews->post_count;
        }

        /**
         * Register meta box(es).
         */
        public function register_meta_boxes() {
            add_meta_box(
                'bp-user-review',
                __( 'Review Details', 'bp-user-reviews' ),
                array($this, 'meta_box_content'),
                $this->post_type,
                'normal'
            );
        }

        /**
         * Meta box display callback.
         *
         * @param WP_Post $post Current post object.
         */
        public function meta_box_content( $post ) {
            include($this->path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'edit.php');
        }

        /**
         * Calculates stars from percent and total stars
         *
         * @param $percent
         * @param $total
         */
        public function calc_stars($percent, $total){
            return ($percent / 100) * $total;
        }

        /**
         * Save meta box content.
         *
         * @param int $post_id Post ID
         */
        public function save_meta_box( $post_id ) {
            /*
             * We need to verify this came from the our screen and with proper authorization,
             * because save_post can be triggered at other times.
             */

            // Check if our nonce is set.
            if ( ! isset( $_POST['bp-user-reviews-edit'] ) ) {
                return $post_id;
            }

            $nonce = $_POST['bp-user-reviews-edit'];

            // Verify that the nonce is valid.
            if ( ! wp_verify_nonce( $nonce, 'bp-user-reviews-edit-'.$post_id ) ) {
                return $post_id;
            }

            /*
             * If this is an autosave, our form has not been submitted,
             * so we don't want to do anything.
             */
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
            }

            // Check the user's permissions.
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
            $type = ( ! empty( get_post_meta($post_id, 'type', true) ) ) ? get_post_meta($post_id, 'type', true) : $this->settings['criterion'];
            $stars = ( ! empty( get_post_meta($post_id, 'stars', true) ) ) ? get_post_meta($post_id, 'stars', true) : $this->settings['stars'];
            /* OK, it's safe for us to save the data now. */
            update_post_meta($post_id, 'type', $type);
            update_post_meta($post_id, 'stars', $stars);

            // Sanitize the user input.
            $review = sanitize_text_field( $_POST['review'] );

            // Update the meta field.
            update_post_meta( $post_id, 'review', $review );

            // Sanitize the user input.
            $user_id = sanitize_text_field( $_POST['user_id'] );

            // Update the meta field.
            update_post_meta( $post_id, 'user_id', $user_id );

            if( $type == 'multiple' && isset($_POST['criterions']) && is_array($_POST['criterions'])){
                update_post_meta( $post_id, 'criterions', $_POST['criterions'] );
                $average =  round( array_sum($_POST['criterions']) / count($_POST['criterions']) );
                update_post_meta( $post_id, 'average', $average );
            } else if($type == 'single' && isset($_POST['total'])){
                update_post_meta( $post_id, 'average', sanitize_text_field($_POST['total']) );
            }


            // Sanitize the user input.
            $post_author = sanitize_text_field( $_POST['post_author'] );

            remove_action( 'save_post',  array($this, 'save_meta_box') );

            wp_update_post( array(
                'ID'          => $post_id,
                'post_author' => $post_author,
            ) );

            add_action( 'save_post',  array($this, 'save_meta_box') );

            return false;

        }

        public function post_type_label($labels){
            global $menu;
            $index = false;

            foreach($menu as $i => $item){
                if($item[2] == 'edit.php?post_type=bp-user-reviews') {
                    $index = $i;
                    break;
                }
            }

            if($index !== false){
                $pending = $this->get_pending_count();
                if($pending > 0) $menu[$index][0] .= '<span class="awaiting-mod"><span class="pending-count">'.$pending.'</span></span>';
            }
            return $labels;
        }

        /**
         * Register post type
         */
        public function register_post_type(){
            register_post_type( $this->post_type, array(
                'label'              => __('User Reviews', 'bp-user-reviews' ),
                'public'             => false,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => false,
                'capability_type'    => 'post',
                'has_archive'        => false,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array('')
            ) );

            register_post_status( 'spam', array(
                'label'                     =>  _x( 'Spam', 'comment status' ),
                'public'                    => false,
                'show_in_admin_all_list'    => false,
                'show_in_admin_status_list' => true,
                'label_count'               => _nx_noop(
                    'Spam <span class="count">(%s)</span>',
                    'Spam <span class="count">(%s)</span>',
                    'comments'
                )
            ) );
        }

        function get_pending_count(){
            $pending = new WP_Query(array(
                'post_type'   => 'bp-user-reviews',
                'post_status' => 'pending',
                'posts_per_page'  => -1,
                'fields' => 'ids'
            ));

            return $pending->found_posts;
        }

        function append_post_status_list(){
            global $post;
            $complete = '';
            $label = '';
            if($post->post_type == $this->post_type || (isset($_GET['post_type']) && $_GET['post_type'] == $this->post_type)){
                if($post->post_status == 'spam'){
                    $complete = ' selected=\'selected\'';
                    $label = '<span id=\"post-status-display\"> '. _x( 'Spam', 'comment status' ).'</span>';
                }
                echo '
                <script>
                jQuery(document).ready(function($){
                    $("select#post_status").append("<option value=\'spam\' '.$complete.'>' .  _x( 'Spam', 'comment status' ) . '</option>");
                    $("select[name=\'_status\']").append("<option value=\'spam\' '.$complete.'>' . _x( 'Spam', 'comment status' ) . '</option>");
                    $(".misc-pub-section label").append("'.$label.'");
                }); 
                </script>
                ';
            }
        }
        function append_post_status_listing(){
            if(!isset($_GET['post_type']) || ($_GET['post_type'] != $this->post_type)) return;
            ?>
            <script>
                jQuery(document).ready(function($){
                    $("select[name='_status']").append("<option value='spam'><?php _e( 'Spam', 'comment status' ); ?></option>");
                    $("select[name='_status'] option[value='private'], select[name='_status'] option[value='draft']").remove();
                });
            </script>
        <?php }

        function new_states( $states ) {
            global $post;
            $arg = get_query_var( 'post_status' );
            if($arg != 'spam'){
                if($post->post_status == 'spam'){
                    return array('Spam');
                }
            }
            return $states;
        }

        public function update_settings($settings){
            if(isset($settings['criterions']) && !empty($settings['criterions'])){
                foreach($settings['criterions'] as $index=>$value){
                    if(empty($value)) unset($settings['criterions'][$index]);
                }
            }

            $this->settings = $settings;

            update_option('bp-user-reviews-settings', $this->settings);
        }

        /**
         * Settings page
         */
        public function settings_page(){
            add_submenu_page(
                'edit.php?post_type='.$this->post_type,
                __('Settings', 'bp-user-reviews'),
                __('Settings', 'bp-user-reviews'),
                'manage_options',
                'bp-user-reviews-settings',
                array($this, 'settings_page_html')
            );

            remove_submenu_page('edit.php?post_type='.$this->post_type, 'post-new.php?post_type=bp-user-reviews');
        }

        public function settings_page_html(){
            if( isset($_POST['_wpnonce'])
                && !empty($_POST['_wpnonce'])
                && wp_verify_nonce( $_POST['_wpnonce'], 'bp-user-reviews-settings' )
            ){
                unset($_POST['_wpnonce'], $_POST['_wp_http_referer']);
                $this->update_settings($_POST);
            }

            include($this->path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'settings.php');
        }

        /**
         * Add buddypress profile tab
         */
        public function add_tabs() {

            $reviews = new WP_Query(array(
                'post_type'   => 'bp-user-reviews',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key'     => 'user_id',
                        'value'   => bp_displayed_user_id()
                    )
                ),
                'posts_per_page'  => 0,
            ));

            $total = $reviews->found_posts;
            $class = ( 0 === $total ) ? 'no-count' : 'count';
            $title = sprintf( _x( 'Reviews <span class="%s">%s</span>', 'Reviews list sub nav', 'bp-user-reviews' ), esc_attr( $class ), bp_core_number_format( $total ) );

            bp_core_new_nav_item( array(
                    'name'                    => $title,
                    'slug'                    => 'reviews',
                    'screen_function'         => array($this, 'screen'),
                    'position'                => 20,
                    'default_subnav_slug'     => 'reviews',
                    'show_for_displayed_user' => true,
                )
            );
        }

        /**
         * Shows author of review
         */
        public static function author(){
            global $post;

            if($post->guest){
                echo get_avatar($post->email, 25) . " " . $post->name;
            } else {
                echo get_avatar($post->post_author, 25) . ' <a href="' . self::get_user_link( $post->post_author ) . '">' . self::get_user_display_name( $post->post_author ) .'</a>';
            }
        }

        /**
         * Shows reviewed user
         */
        public static function reviewed(){
            global $post;

            echo get_avatar($post->user_id, 25) . " " . bp_core_get_userlink( $post->user_id );
        }

        /**
         * Enqueue scripts
         */
        public function bp_screen_scripts() {
            if( bp_is_current_component('reviews') ) {
                $translation_array = array(
                    'ajax_url'   => admin_url('admin-ajax.php'),
                    'messages'   => array(
                        'success' => __('Saved successfully.', 'bp-user-reviews')
                    )
                );

                wp_register_script( 'bp-user-reviews-js', $this->url . 'assets/js/bp-user-reviews.js', array('jquery'), $this->version);
                wp_localize_script( 'bp-user-reviews-js', 'BP_User_Reviews', $translation_array );
                wp_enqueue_script( 'bp-user-reviews-js' );
            }

        }

        /**
         * Enqueue scripts
         */
        public function screen_scripts() {
            wp_enqueue_style( 'bp-user-reviews-css', $this->url . 'assets/css/bp-user-reviews.css', array(), $this->version);
            wp_enqueue_style( 'font-awesome', $this->url . 'assets/css/font-awesome.min.css');
        }

        /**
         * Admin scripts
         *
         * @param $hook
         */
        public function admin_screen_scripts($hook){
            global $post;
            if($hook === 'bp-user-reviews_page_bp-user-reviews-settings'
                || ($hook == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == $this->post_type)
                || ($hook == 'post.php' && isset($_GET['post']) && $post->post_type == $this->post_type)
                || ($hook == 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] == $this->post_type)
            ):
                $translation_array = array(
                    'ajax_url'   => admin_url('admin-ajax.php'),
                    'messages'   => array(
                        'success' => __('Saved successfully.', 'bp-user-reviews')
                    )
                );

                wp_register_script( 'bp-user-reviews-js-admin', $this->url . 'assets/js/bp-user-reviews-admin.js', array('jquery', 'wp-color-picker'));
                wp_localize_script( 'bp-user-reviews-js-admin', 'BP_User_Reviews', $translation_array );
                wp_enqueue_script( 'bp-user-reviews-js-admin' );

                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_style( 'bp-user-reviews-css-admin', $this->url . 'assets/css/bp-user-reviews-admin.css');
                wp_enqueue_style( 'font-awesome', $this->url . 'assets/css/font-awesome.min.css');
            endif;
        }

        /**
         * Register screen function
         */
        public function screen() {
            add_action( 'bp_template_content', array($this, 'screen_content') );
            bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
        }

        /*
         * Printing stars
         */
        public function print_stars($count = false){
            $stars = $this->settings['stars'];
            if($count !== false) $stars = $count;
            return str_repeat('<span class="fa star"></span>', (int) $stars);
        }

        public function print_stars_admin($result, $stars){ ?>
            <span class="stars">
            <?php echo $this->print_stars($stars); ?>
            <span class="active" style="width:<?php echo $result; ?>%">
                     <?php echo $this->print_stars($stars); ?>
                </span>
            </span><?php
        }

        /**
         * Display tab content
         */
        public function screen_content() {
            if( (($this->settings['access'] == 'registered') && is_user_logged_in()) ||  $this->settings['access'] == 'all'){
                if(get_current_user_id() != bp_displayed_user_id() &&
                   apply_filters( 'bp_members_reviews_review_allowed', true, bp_loggedin_user_id(), bp_displayed_user_id() )
                ) {
                    include($this->path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'review-form.php');
                }
            }

            include($this->path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'review-list.php');
        }

        public static function calc_rating($user_id){
            $reviews = new WP_Query(array(
                'post_type'   => 'bp-user-reviews',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key'     => 'user_id',
                        'value'   => $user_id
                    )
                ),
                'posts_per_page'  => -1,
                'fields' => 'ids'
            ));

            $count = count($reviews->posts);
            if($count == 0) {
                update_user_meta($user_id, 'bp-user-reviews', array(
                    'count' => 0,
                    'result' => '0'
                ));
                return;
            }
            $sum   = 0;
            foreach($reviews->posts as $review){
                $sum += get_post_meta($review, 'average', true);
            }

            update_user_meta($user_id, 'bp-user-reviews', array(
                'count' => $count,
                'result' => $sum / $count
            ));
        }

        public function ember_forum_post(){
            $this->embed_rating(bbp_get_reply_author_id());
        }

        public function ember_rating_directory(){
            global $members_template;
            $this->embed_rating($members_template->member->id);
        }

        public function embed_rating($user_id = false){
            if($user_id == false) $user_id = bp_displayed_user_id();
            self::calc_rating($user_id);
            $rating = get_user_meta($user_id, 'bp-user-reviews', true);

            include($this->path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'rating.php');
        }

        public static function get_username($user_id){

            if( function_exists('bp_core_get_username') ){
                return bp_core_get_username( $user_id, true, false );
            }

            $user = get_userdata($user_id);

            return $user->user_login;
        }

        public static function get_user_display_name($user_id){

            if( function_exists('bp_core_get_user_displayname') ){
                return bp_core_get_user_displayname( $user_id );
            }

            $user = get_userdata($user_id);

            return $user->display_name;
        }

        public static function get_user_link($user_id){

            if( function_exists('bp_core_get_userlink') ){
                return bp_core_get_userlink($user_id, false, true);
            }

            if( function_exists('bbp_get_user_profile_url') ){
                return bbp_get_user_profile_url($user_id);
            }

            return false;
        }

        public function toSpam($post_id){
            if(!wp_verify_nonce($_GET['_nonce'], 'spam_'.$post_id)) return false;

            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'spam'
            ));

            wp_redirect( wp_get_referer() );
            die();
        }

        public function unSpam($post_id){
            if(!wp_verify_nonce($_GET['_nonce'], 'unspam_'.$post_id)) return false;

            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'publish'
            ));

            wp_redirect( wp_get_referer() );
            die();
        }

        public function unPublish($post_id){
            if(!wp_verify_nonce($_GET['_nonce'], 'unpublish_'.$post_id)) return false;

            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'pending'
            ));

            wp_redirect( wp_get_referer() );
            die();
        }

        public function publish($post_id){
            if(!wp_verify_nonce($_GET['_nonce'], 'publish_'.$post_id)) return false;

            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'publish'
            ));

            wp_redirect( wp_get_referer() );
            die();
        }

        public function spam_button(){
            global $post;

            if($post->post_type != $this->post_type) return;

            $link = add_query_arg(
                array(
                    'action' => 'spam',
                    '_nonce' => wp_create_nonce('spam_'.$post->ID)
                )
            );
            echo '<a href="'.$link.'" class="button">'. _x( 'Spam', 'comment status' ) .'</a>';
        }

        public function styles(){ ?>
            <style type="text/css">
                <?php $color = $this->settings['starsColor']; ?>
                .bp-users-reviews-stars span.star:before,
                .bp-user-reviews span.star:before,
                .bp-users-reviews-list span.star:before{
                    color: <?php echo $color; ?>
                }
            </style>
        <?php }
    }

    add_action('plugins_loaded', function(){
        if( class_exists('BuddyPress') ) {
            global $BP_Member_Reviews;
            $BP_Member_Reviews = new BP_Member_Reviews();
        }
    });
endif;