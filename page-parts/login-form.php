<?php
if ( ! isset( $style ) ) {
    $style = 'light';
}
$style = $style . '-login';

if ( ! isset( $before_input ) ) {
    $before_input = '';
}
?>

<div class="kleo-login-wrap <?php echo esc_attr( $style );?>"> <!--add .dark-login for the dark version-->
    <div class="login-shadow-wrapper">

        <div class="before-login-form-wrapper">
                <button class="home_form_close" type="button">×</button>
            <?php do_action( 'kleo_before_login_form' );?>
        </div>
 
        <?php if( get_option( 'users_can_register' ) ) : ?>

            <div class="login-create-account-wrapper">

                <div class="kleo-register-link"><?php esc_html_e( "Don't have an account yet?", "buddyapp"); ?>
                    <a href="<?php if (function_exists('bp_is_active')) bp_signup_page(); else echo esc_url( home_url() ) . "/wp-login.php?action=register"; ?>" class="new-account">
                        <?php esc_html_e( "Create an account", "buddyapp" ); ?>
                    </a>
                </div>

            </div>

        <?php endif; ?>
        
    </div>

</div>




