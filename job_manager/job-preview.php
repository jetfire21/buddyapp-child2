
 <form method="post" id="job_preview" action="<?php echo esc_url( $form->get_action() ); ?>">
    <div class="job_listing_preview_title">
        <input type="submit" name="continue" id="job_preview_submit_button" class="button job-manager-button-submit-listing" value="<?php echo apply_filters( 'submit_job_step_preview_submit_text', __( 'Submit Listing', 'wp-job-manager' ) ); ?>" />
        <input type="submit" name="edit_job" class="button job-manager-button-edit-listing" value="<?php _e( 'Edit listing', 'wp-job-manager' ); ?>" />
        <h2><?php _e( 'Preview', 'wp-job-manager' ); ?></h2>
    </div>
    <!-- <div class="job_listing_preview single_job_listing"> -->

        <?php get_job_manager_template_part( 'content-single', 'job_listing' ); ?>

        <input type="hidden" name="job_id" value="<?php echo esc_attr( $form->get_job_id() ); ?>" />
        <input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>" />
        <input type="hidden" name="job_manager_form" value="<?php echo $form->get_form_name(); ?>" />
    <!-- </div> -->
</form>
 

<!-- 
 <form method="post" id="job_preview" action="<?php echo esc_url( $form->get_action() ); ?>">
    <div class="job_listing_preview_title">
        <input type="submit" name="continue" id="job_preview_submit_button" class="button job-manager-button-submit-listing" value="<?php echo apply_filters( 'submit_job_step_preview_submit_text', __( 'Submit Listing', 'wp-job-manager' ) ); ?>" />
        <input type="submit" name="edit_job" class="button job-manager-button-edit-listing" value="<?php _e( 'Edit listing', 'wp-job-manager' ); ?>" />
        <h2><?php _e( 'Preview', 'wp-job-manager' ); ?></h2>
    </div>

<ul class="job_listings">
<li id="job_listing-10445" class="job_listing job-type-full-time post-10445 type-job_listing status-publish has-post-thumbnail hentry job_listing_tag-php" style="visibility: visible;">

    <div class="job_listing-logo">
        <img class="company_logo" src="http://dugoodr2.dev/wp-content/uploads/2017/03/HSF_logo.png" alt="Heart and Stroke Foundation">  </div><div class="job_listing-about">

        <div class="job_listing-position job_listing__column">
            <h3 class="job_listing-title">ivan php developer</h3>

            <div class="job_listing-company">
                <strong>Heart and Stroke Foundation</strong>                            </div>
        </div>

        <div class="job_listing-location job_listing__column">
            Moscov      </div>

        <ul class="job_listing-meta job_listing__column">
            
            <li class="job_listing-type job-type full-time">full-time</li>
            <li class="job_listing-date"><date>3 days ago</date></li>

                    </ul>

    </div>
</li>
</ul>

</form>
 -->


