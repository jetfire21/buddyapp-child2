<?php
/**
 * Review form
 */
defined( 'ABSPATH' ) || exit; ?>
<?php   $bp_member_r = new BP_Member_Reviews(); ?>
<form class="bp-user-reviews">
    <h2><?php _e('Add Review', 'bp-user-reviews'); ?></label></h2>
    <div class="bp-user-reviews-table">
        <?php if($bp_member_r->settings['criterion'] == 'single'){ ?>
        <p class="rating">
            <span class="rating"><?php echo $bp_member_r->print_stars(); ?></span>
            <input type="hidden" name="criteria" value="0">
        </p>
        <?php } else if($bp_member_r->settings['criterion'] == 'multiple') {
            foreach($bp_member_r->settings['criterions'] as $index => $criteria){
	            // Insert criterions on the String Translation
	            do_action( 'wpml_register_single_string', 'User Reviews', $criteria, $criteria );
	            $criteria = apply_filters( 'wpml_translate_single_string', $criteria, 'User Reviews', $criteria );
	            ?>
                <p class="rating">
                    <label><?php echo esc_attr($criteria); ?>:</label>
                    <span class="rating"><?php echo $bp_member_r->print_stars(); ?></span>
                    <input type="hidden" name="criteria[<?php echo esc_attr($index); ?>]" value="0">
                </p>
        <?php } } ?>
        <?php if( ! is_user_logged_in() ){ ?>
            <p class="field">
                <label for="review-name"><?php _e('Your name', 'bp-user-reviews'); ?>:</label>
                <input id="review-name" type="text" name="name" value="">
            </p>
            <p class="field">
                <label for="review-email"><?php _e('Your email', 'bp-user-reviews'); ?>:</label>
                <input id="review-email" type="email" name="email" value="">
            </p>
        <?php } ?>
    </div>

    <div id="as21-reviews-badge">
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b1.png'; ?>' data-id='1' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b2.png'; ?>' data-id='2' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b3.png'; ?>' data-id='3' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b4.png'; ?>' data-id='4' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b5.png'; ?>' data-id='5' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b6.png'; ?>' data-id='6' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b7.png'; ?>' data-id='7' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b8.png'; ?>' data-id='8' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b9.png'; ?>' data-id='9' /> 
        <img src='<?php echo get_stylesheet_directory_uri().'/images/b10.png'; ?>' data-id='10' /> 
    </div>


    <?php if($bp_member_r->settings['review'] == 'yes'){ ?>
        <textarea name="review"></textarea>
    <?php } ?>
    <input type="hidden" name="action" value="bp_user_review">
    <input type="hidden" name="user_id" value="<?php echo bp_displayed_user_id(); ?>">
    <input type="hidden" id="badge_id" name="badge_id" value="0">
    <?php wp_nonce_field('bp-user-review-new-'.bp_displayed_user_id()); ?>
    <input type="submit" value="<?php _e( 'Submit', 'bp-user-reviews' ); ?>">
</form>