<?php do_action( 'byt_show_car_rental_confirm_form_before' ); ?>
<form id="car_rental-confirmation-form" method="post" action="" class="booking" style="display:none">
	<fieldset>
		<h3><span>02 </span><?php _e('Confirmation', 'bookyourtravel') ?></h3>
		<div class="text-wrap">
			<p><?php _e('Thank you. We will get back you with regards your reservation within 24 hours.', 'bookyourtravel') ?></p>
		</div>				
		<h3><?php _e('Traveller info', 'bookyourtravel') ?></h3>
		<div class="text-wrap">
			<div class="output">
				<p><?php _e('First name', 'bookyourtravel') ?>: </p>
				<p id="car_confirm_first_name"></p>
			</div>
			<div class="output">
				<p><?php _e('Last name', 'bookyourtravel') ?>: </p>
				<p id="car_confirm_last_name"></p>
			</div>
			<div class="output">
				<p><?php _e('Email address', 'bookyourtravel') ?>: </p>
				<p id="car_confirm_email_address"></p>
			</div>
			<div class="output">
				<p><?php _e('Phone', 'bookyourtravel') ?>: </p>
				<p id="car_confirm_phone"></p>
			</div>
			<div class="output">
				<p><?php _e('Street', 'bookyourtravel') ?>: </p>
				<p id="car_confirm_street"></p>
			</div>
			<div class="output">
				<p><?php _e('Town/City', 'bookyourtravel') ?>: </p>
				<p id="car_confirm_town"></p>
			</div>
			<div class="output">
				<p><?php _e('Zip code', 'bookyourtravel') ?>: </p>
				<p id="car_confirm_zip"></p>
			</div>
			<div class="output">
				<p><?php _e('Country', 'bookyourtravel') ?>:</p>
				<p id="car_confirm_country"></p>
			</div>
			<div class="output">
				<p><?php _e('Car rental name', 'bookyourtravel') ?>:</p>
				<p id="car_confirm_car_rental_name"></p>
			</div>
			<div class="output">
				<p><?php _e('Date from', 'bookyourtravel') ?>:</p>
				<p id="car_confirm_date_from"></p>
			</div>
			<div class="output">
				<p><?php _e('Date to', 'bookyourtravel') ?>:</p>
				<p id="car_confirm_date_to"></p>
			</div>
			<div class="output">
				<p><?php _e('Pick up', 'bookyourtravel') ?>:</p>
				<p id="car_confirm_pick_up"></p>
			</div>
			<div class="output">
				<p><?php _e('Drop off', 'bookyourtravel') ?>:</p>
				<p id="car_confirm_drop_off"></p>
			</div>
			<div class="output">
				<p><?php _e('Total price', 'bookyourtravel') ?>:</p>
				<p id="car_confirm_total_price"></p>
			</div>
		</div>			
		<h3><?php _e('Special requirements', 'bookyourtravel') ?></h3>
		<div class="text-wrap">
			<p id="car_confirm_requirements"></p>
		</div>				
		<div class="text-wrap">
			<p><?php echo sprintf(__('<strong>We wish you a pleasant trip</strong><br /><i>your %s team</i>', 'bookyourtravel'), of_get_option('contact_company_name', 'BookYourTravel')) ?></p>
		</div>
	</fieldset>
</form>
<?php do_action( 'byt_show_car_rental_confirm_form_after' );