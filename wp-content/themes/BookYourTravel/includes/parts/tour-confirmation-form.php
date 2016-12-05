<?php do_action( 'byt_show_tour_confirm_form_before' ); ?>
<form id="tour-confirmation-form" method="post" action="" class="booking" style="display:none">
	<fieldset>
		<h3><span>03 </span><?php _e('Confirmation', 'bookyourtravel') ?></h3>
		<div class="text-wrap">
			<p><?php _e('Thank you. We will get back you with regards your tour booking within 24 hours.', 'bookyourtravel') ?></p>
		</div>				
		<h3><?php _e('Traveller info', 'bookyourtravel') ?></h3>
		<div class="text-wrap">
			<div class="output">
				<p><?php _e('First name', 'bookyourtravel') ?>: </p>
				<p id="confirm_first_name"></p>
			</div>
			<div class="output">
				<p><?php _e('Last name', 'bookyourtravel') ?>: </p>
				<p id="confirm_last_name"></p>
			</div>
			<div class="output">
				<p><?php _e('Email address', 'bookyourtravel') ?>: </p>
				<p id="confirm_email_address"></p>
			</div>
			<div class="output">
				<p><?php _e('Phone', 'bookyourtravel') ?>: </p>
				<p id="confirm_phone"></p>
			</div>
			<div class="output">
				<p><?php _e('Street', 'bookyourtravel') ?>: </p>
				<p id="confirm_street"></p>
			</div>
			<div class="output">
				<p><?php _e('Town/City', 'bookyourtravel') ?>: </p>
				<p id="confirm_town"></p>
			</div>
			<div class="output">
				<p><?php _e('Zip code', 'bookyourtravel') ?>: </p>
				<p id="confirm_zip"></p>
			</div>
			<div class="output">
				<p><?php _e('Country', 'bookyourtravel') ?>:</p>
				<p id="confirm_country"></p>
			</div>
			<div class="output">
				<p><?php _e('Tour start date', 'bookyourtravel') ?>:</p>
				<p id="confirm_tour_start_date"></p>
			</div>
			<div class="output">
				<p><?php _e('Tour', 'bookyourtravel') ?>:</p>
				<p id="confirm_tour_title"></p>
			</div>
			<div class="output">
				<p><?php _e('Adults', 'bookyourtravel') ?>:</p>
				<p id="confirm_tour_adults"></p>
			</div>
			<div class="output">
				<p><?php _e('Children', 'bookyourtravel') ?>:</p>
				<p id="confirm_tour_children"></p>
			</div>
			<div class="output">
				<p><?php _e('Total price', 'bookyourtravel') ?>:</p>
				<p id="confirm_tour_total"></p>				
			</div>
		</div>			
		<h3><?php _e('Special requirements', 'bookyourtravel') ?></h3>
		<div class="text-wrap">
			<p id="confirm_requirements"></p>
		</div>				
		<div class="text-wrap">
			<p><?php echo sprintf(__('<strong>We wish you a pleasant tour</strong><br /><i>your %s team</i>', 'bookyourtravel'), of_get_option('contact_company_name', 'BookYourTravel')) ?></p>
		</div>
	</fieldset>
</form>
<?php do_action( 'byt_show_tour_confirm_form_after' );