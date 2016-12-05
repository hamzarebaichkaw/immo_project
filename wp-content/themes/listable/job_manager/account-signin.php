<?php 	if ( is_user_logged_in() ) : ?>

	<fieldset>
		<label><?php _e( 'Your account', 'wp-job-manager' ); ?></label>
		<div class="field account-sign-in">
			<?php
				$user = wp_get_current_user();
				printf( __( 'You are currently signed in as <strong>%s</strong>.', 'wp-job-manager' ), $user->user_login );
			?>

			<a class="button" href="<?php echo apply_filters( 'submit_job_form_logout_url', wp_logout_url( get_permalink() ) ); ?>"><?php _e( 'Sign out', 'wp-job-manager' ); ?></a>
		</div>
	</fieldset>

<?php else :
	$account_required             = job_manager_user_requires_account();
	$registration_enabled         = job_manager_enable_registration();
	$generate_username_from_email = job_manager_generate_username_from_email();

	$login_url = listable_get_login_url();
	$classes = listable_get_login_link_class( 'button' );
	?>
	<fieldset>
		<label><?php _e( 'Have an account?', 'wp-job-manager' ); ?></label>
		<div class="field account-sign-in <?php echo ( listable_using_lwa() ) ? 'lwa' : ''; ?>">
			<a class="<?php echo $classes; ?>" href="<?php echo apply_filters( 'submit_job_form_login_url', $login_url ); ?>"><?php _e( 'Sign in', 'wp-job-manager' ); ?></a>

			<?php if ( $registration_enabled ) : ?>

				<?php printf( __( 'If you don&rsquo;t have an account you can %screate one below by entering your email address/username. Your account details will be confirmed via email.', 'wp-job-manager' ), $account_required ? '' : __( 'optionally', 'wp-job-manager' ) . ' ' ); ?>

			<?php elseif ( $account_required ) : ?>

				<?php echo apply_filters( 'submit_job_form_login_required_message',  __('You must sign in to create a new listing.', 'wp-job-manager' ) ); ?>

			<?php endif; ?>

		</div>
	</fieldset>
	<?php if ( $registration_enabled ) : ?>
		<?php if ( ! $generate_username_from_email ) : ?>
			<fieldset>
				<label><?php _e( 'Username', 'wp-job-manager' ); ?> <?php echo apply_filters( 'submit_job_form_required_label', ( ! $account_required ) ? ' <small>' . __( '(optional)', 'wp-job-manager' ) . '</small>' : '' ); ?></label>
				<div class="field">
					<input type="text" class="input-text" name="create_account_username" id="account_username" value="<?php echo empty( $_POST['create_account_username'] ) ? '' : esc_attr( sanitize_text_field( stripslashes( $_POST['create_account_username'] ) ) ); ?>" />
				</div>
			</fieldset>
		<?php endif; ?>
		<fieldset>
			<label><?php _e( 'Your email', 'wp-job-manager' ); ?> <?php echo apply_filters( 'submit_job_form_required_label', ( ! $account_required ) ? ' <small>' . __( '(optional)', 'wp-job-manager' ) . '</small>' : '' ); ?></label>
			<div class="field">
				<input type="email" class="input-text" name="create_account_email" id="account_email" placeholder="<?php esc_attr_e( 'you@yourdomain.com', 'wp-job-manager' ); ?>" value="<?php echo empty( $_POST['create_account_email'] ) ? '' : esc_attr( sanitize_text_field( stripslashes( $_POST['create_account_email'] ) ) ); ?>" />
			</div>
		</fieldset>
		<fieldset>
			<label for="account_first_name"><?php _e( 'first_name', 'woocommerce' ); ?><span class="required">*</span></label>
			<div class="field">
				<input type="text" class="input-text" name="create_account_first_name" id="account_first_name" value="<?php echo empty( $_POST['create_account_first_name'] ) ? '' : esc_attr( sanitize_text_field( stripslashes( $_POST['create_account_first_name'] ) ) ); ?>" />
			</div>
		</fieldset>
		<fieldset>
			<label for="account_name"><?php _e( 'name', 'woocommerce' ); ?> <span class="required">*</span></label>
			<div class="field">
				<input type="text" class="input-text" name="create_account_name" id="account_name" value="<?php echo empty( $_POST['create_account_name'] ) ? '' : esc_attr( sanitize_text_field( stripslashes( $_POST['create_account_name'] ) ) ); ?>" />
			</div>
		</fieldset>
		<fieldset>
			<label for="billing_phone"><?php _e( 'billing_phone', 'woocommerce' ); ?> <span class="required">*</span></label>
			<div class="field">
				<input type="text" class="input-text" name="create_account_billing_phone" id="account_billing_phone" value="<?php echo empty( $_POST['create_account_billing_phone'] ) ? '' : esc_attr( sanitize_text_field( stripslashes( $_POST['create_account_billing_phone'] ) ) ); ?>" />
			</div>
		</fieldset>
		<?php do_action( 'job_manager_register_form' ); ?>
	<?php endif; ?>

<?php endif; ?>
