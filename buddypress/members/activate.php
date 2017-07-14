<div id="buddypress">

	<?php

	/**
	 * Fires before the display of the member activation page.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_before_activation_page' ); ?>

	<div class="page" id="activate-page">

		<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
		do_action( 'template_notices' ); ?>

		<?php

		/**
		 * Fires before the display of the member activation page content.
		 *
		 * @since BuddyPress (1.1.0)
		 */
		do_action( 'bp_before_activate_content' ); ?>

		<?php if ( bp_account_was_activated() ) : ?>

			<?php if ( isset( $_GET['e'] ) ) : ?>
				<p><?php _e( 'Your account was activated successfully! Your account details have been sent to you in a separate email.', 'buddypress' ); ?></p>
			<?php else : ?>
				<p><?php printf( __( '<strong>Welcome to DuGoodr!</strong>  Your account was activated successfully!</br>Login below with the username and password you provided in registration.', 'buddypress' ), wp_login_url( bp_get_root_domain() ) ); ?></p>
			<?php endif; ?>

		<div class="login-create-account-wrapper">
			<div class="kleo-register-link">
				<div class="fb-login-link">


				<?php
					// echo do_shortcode( '[sq_login_form]' );
					$output = $style = $disable_modal = '';

					extract( shortcode_atts( array(
							'style' => '',
							// 'style' => 'white',
							'before_input' => '',
							'disable_modal' => ''
					), $atts) );

					$output .= '<div class="alex-simple-form login-page-wrap">';
					

					ob_start();
					kleo_get_template_part( 'page-parts/page-register-login-form', null, compact( 'style', 'before_input' ) );
					$output .= ob_get_clean();
					$output .= '</div>';

					if ( $disable_modal == '' ) {
						add_filter( "get_template_part_page-parts/page-register-login-form", '__return_false');
					}
					echo $output;
				 ?>

				</div>


			</div>
		</div>

		<?php else : ?>

			<p><?php _e( 'Please provide a valid activation key.', 'buddypress' ); ?></p>

			<form action="" method="get" class="standard-form" id="activation-form">

				<label for="key"><?php _e( 'Activation Key:', 'buddypress' ); ?></label>
				<input type="text" name="key" id="key" value="" />

				<p class="submit">
					<input type="submit" name="submit" value="<?php esc_attr_e( 'Activate', 'buddypress' ); ?>" />
				</p>

			</form>

		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of the member activation page content.
		 *
		 * @since BuddyPress (1.1.0)
		 */
		do_action( 'bp_after_activate_content' ); ?>

	</div><!-- .page -->

	<?php

	/**
	 * Fires after the display of the member activation page.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_after_activation_page' ); ?>

</div><!-- #buddypress -->
