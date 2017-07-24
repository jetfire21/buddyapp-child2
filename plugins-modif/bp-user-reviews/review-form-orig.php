<?php
/**
 * Review form
 */
defined( 'ABSPATH' ) || exit; ?>
<form class="bp-user-reviews">
    <h2><?php _e('Add Review', 'bp-user-reviews'); ?></label></h2>
    <div class="bp-user-reviews-table">
        <?php if($this->settings['criterion'] == 'single'){ ?>
        <p class="rating">
            <span class="rating"><?php echo $this->print_stars(); ?></span>
            <input type="hidden" name="criteria" value="0">
        </p>
        <?php } else if($this->settings['criterion'] == 'multiple') {
            foreach($this->settings['criterions'] as $index => $criteria){
	            // Insert criterions on the String Translation
	            do_action( 'wpml_register_single_string', 'User Reviews', $criteria, $criteria );
	            $criteria = apply_filters( 'wpml_translate_single_string', $criteria, 'User Reviews', $criteria );
	            ?>
                <p class="rating">
                    <label><?php echo esc_attr($criteria); ?>:</label>
                    <span class="rating"><?php echo $this->print_stars(); ?></span>
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
    <?php if($this->settings['review'] == 'yes'){ ?>
        <textarea name="review"></textarea>
    <?php } ?>
    <input type="hidden" name="action" value="bp_user_review">
    <input type="hidden" name="user_id" value="<?php echo bp_displayed_user_id(); ?>">
    <?php wp_nonce_field('bp-user-review-new-'.bp_displayed_user_id()); ?>
    <input type="submit" value="<?php _e( 'Submit', 'bp-user-reviews' ); ?>">
</form>