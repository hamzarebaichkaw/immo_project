<?php global $cruise_obj, $byt_theme_globals;$price_decimal_places = $byt_theme_globals->get_price_decimal_places();$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();$enc_key = $byt_theme_globals->get_enc_key();$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();$c_val_1_cru = mt_rand(1, 20);$c_val_2_cru = mt_rand(1, 20);$c_val_1_cru_str = BYT_Theme_Utils::encrypt($c_val_1_cru, $enc_key);$c_val_2_cru_str = BYT_Theme_Utils::encrypt($c_val_2_cru, $enc_key);?><script>	window.currencySymbol = <?php echo json_encode($default_currency_symbol); ?>;	window.currencySymbolShowAfter = <?php echo json_encode($show_currency_symbol_after); ?>;	window.bookingFormFirstNameError = <?php echo json_encode(__('Please enter your first name', 'bookyourtravel')); ?>;	window.bookingFormLastNameError = <?php echo json_encode(__('Please enter your last name', 'bookyourtravel')); ?>;	window.bookingFormEmailError = <?php echo json_encode(__('Please enter valid email address', 'bookyourtravel')); ?>;	window.bookingFormConfirmEmailError1 = <?php echo json_encode(__('Please provide a confirm email', 'bookyourtravel')); ?>;	window.bookingFormConfirmEmailError2 = <?php echo json_encode(__('Please enter the same email as above', 'bookyourtravel')); ?>;	window.bookingFormAddressError = <?php echo json_encode(__('Please enter your address', 'bookyourtravel')); ?>;	window.bookingFormCityError = <?php echo json_encode(__('Please enter your city', 'bookyourtravel')); ?>;			window.bookingFormZipError = <?php echo json_encode(__('Please enter your zip code', 'bookyourtravel')); ?>;			window.bookingFormCountryError = <?php echo json_encode(__('Please enter your country', 'bookyourtravel')); ?>;		window.InvalidCaptchaMessage = <?php echo json_encode(__('Invalid captcha, please try again!', 'bookyourtravel')); ?>;	window.bookingFormStartDateError = <?php echo json_encode(__('Please select a valid start date!', 'bookyourtravel')); ?>;</script><?php do_action( 'byt_show_cruise_booking_form_before' ); ?><form id="cruise-booking-form" method="post" action="" class="booking" style="display:none">	<fieldset>			<h3><span>01 </span><?php _e('Booking details', 'bookyourtravel') ?></h3>		<div class="row">			<div class="output">				<p><?php _e('Adults', 'bookyourtravel') ?></p>				<p class="step_1_adults_holder"></p>			</div>			<div class="output">				<p><?php _e('Children', 'bookyourtravel') ?></p>				<p class="step_1_children_holder"></p>			</div>			<div class="output">				<p><?php _e('Cruise date', 'bookyourtravel') ?></p>				<p class="step_1_cruise_date_holder"></p>			</div>			<div class="output">				<p><?php _e('Total', 'bookyourtravel') ?></p>				<p class="step_1_total_holder"></p>			</div>		</div>			<h3><span>02 </span><?php _e('Submit cruise booking', 'bookyourtravel') ?></h3>		<div class="error" style="display:none;"><div><p></p></div></div>		<div class="row twins">			<div class="f-item">				<label for="first_name"><?php _e('First name', 'bookyourtravel') ?></label>				<input type="text" id="first_name" name="first_name" data-required />			</div>			<div class="f-item">				<label for="last_name"><?php _e('Last name', 'bookyourtravel') ?></label>				<input type="text" id="last_name" name="last_name" data-required />			</div>		</div>					<div class="row twins">			<div class="f-item">				<label for="email"><?php _e('Email address', 'bookyourtravel') ?></label>				<input type="email" id="email" name="email" data-required />			</div>			<div class="f-item">				<label for="confirm_email"><?php _e('Confirm email address', 'bookyourtravel') ?></label>				<input type="email" id="confirm_email" name="confirm_email" data-required data-conditional="confirm" />			</div>			<span class="info"><?php _e('You\'ll receive a confirmation email', 'bookyourtravel') ?></span>		</div>					<div class="row">			<div class="f-item">				<label for="phone"><?php _e('Phone', 'bookyourtravel') ?></label>				<input type="text" id="phone" name="phone" data-required />			</div>		</div>				<div class="row twins">			<div class="f-item">				<label for="address"><?php _e('Street Address and Number', 'bookyourtravel') ?></label>				<input type="text" id="address" name="address" data-required />			</div>			<div class="f-item">				<label for="town"><?php _e('Town / City', 'bookyourtravel') ?></label>				<input type="text" id="town" name="town" data-required />			</div>		</div>		<div class="row twins">			<div class="f-item">				<label for="zip"><?php _e('ZIP Code', 'bookyourtravel') ?></label>				<input type="text" id="zip" name="zip" data-required />			</div>			<div class="f-item">				<label for="country"><?php _e('Country', 'bookyourtravel') ?></label>				<input type="text" id="country" name="country" data-required />			</div>		</div>					<div class="row ">			<div class="f-item">				<label for="cruise_name"><?php _e('Cruise name', 'bookyourtravel') ?></label>				<span id="cruise_name"></span>			</div>		</div>				<div class="row">			<div class="f-item">				<label><?php _e('Special requirements: <span>(Not Guaranteed)</span>', 'bookyourtravel') ?></label>				<textarea id="requirements" name="requirements" rows="10" cols="10"></textarea>			</div>			<span class="info"><?php _e('Please write your requests in English.', 'bookyourtravel') ?></span>		</div>		<?php if ($add_captcha_to_forms) { ?>		<div class="row captcha">			<div class="f-item">				<label><?php echo sprintf(__('How much is %d + %d', 'bookyourtravel'), $c_val_1_cru, $c_val_2_cru) ?>?</label>				<input type="text" required="required" id="c_val_s_cru" name="c_val_s_cru" />				<input type="hidden" name="c_val_1_cru" id="c_val_1_cru" value="<?php echo esc_attr($c_val_1_cru_str); ?>" />				<input type="hidden" name="c_val_2_cru" id="c_val_2_cru" value="<?php echo esc_attr($c_val_2_cru_str); ?>" />			</div>		</div>		<?php } ?>		<input type="hidden" name="cruise_schedule_id" id="cruise_schedule_id" />		<?php BYT_Theme_Utils::render_submit_button("gradient-button", "submit-cruise-booking", __('Submit booking', 'bookyourtravel')); ?>		<?php BYT_Theme_Utils::render_link_button("#", "gradient-button cancel-cruise-booking", "cancel-cruise-booking", __('Cancel', 'bookyourtravel')); ?>	</fieldset></form><div class="loading" id="wait_loading" style="display:none">	<div class="ball"></div>	<div class="ball1"></div></div><?php do_action( 'byt_show_cruise_booking_form_after' );