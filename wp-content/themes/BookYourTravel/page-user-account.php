<?php 
/* Template Name: User Account Page
 * The template for displaying the user account page.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
 
if ( !is_user_logged_in() ) {
	wp_redirect( get_home_url() );
	exit;
}

global $byt_theme_globals, $byt_accommodations_post_type, $byt_reviews_post_type, $current_user, $frontend_submit;

get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');

$enable_reviews = $byt_theme_globals->enable_reviews();
$enable_accommodations = $byt_theme_globals->enable_accommodations();
$current_user = wp_get_current_user();
$user_info = get_userdata($current_user->ID);
$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();

$submit_room_types_url = $byt_theme_globals->get_submit_room_types_url();
$submit_accommodations_url = $byt_theme_globals->get_submit_accommodations_url();
$submit_accommodation_vacancies_url = $byt_theme_globals->get_submit_accommodation_vacancies_url();
$list_user_room_types_url = $byt_theme_globals->get_list_user_room_types_url();
$list_user_accommodations_url = $byt_theme_globals->get_list_user_accommodations_url();
$list_user_accommodation_vacancies_url = $byt_theme_globals->get_list_user_accommodation_vacancies_url();

global $post;

$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$is_partner_page = false;
if (isset($page_custom_fields['user_account_is_partner_page'])) {
	$is_partner_page = $page_custom_fields['user_account_is_partner_page'][0] == '1' ? true : false;
}

$page_sidebar_positioning = null;
if (isset($page_custom_fields['page_sidebar_positioning'])) {
	$page_sidebar_positioning = $page_custom_fields['page_sidebar_positioning'][0];
	$page_sidebar_positioning = empty($page_sidebar_positioning) ? '' : $page_sidebar_positioning;
}

$section_class = 'full-width';
if ($page_sidebar_positioning == 'both')
	$section_class = 'one-half';
else if ($page_sidebar_positioning == 'left' || $page_sidebar_positioning == 'right') 
	$section_class = 'three-fourth';

if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'left')
	get_sidebar('left');
?>
	<!--three-fourth content-->
	<section class="<?php echo esc_attr($section_class); ?>">
		<h1><?php _e('My account', 'bookyourtravel'); ?></h1>
		<!--inner navigation-->
		<nav class="inner-nav">
			<ul>
				<li class="active"><a href="#settings" title="<?php esc_attr_e('Settings', 'bookyourtravel'); ?>"><?php _e('Settings', 'bookyourtravel'); ?></a></li>
				<?php if (!$is_partner_page && $enable_accommodations) { ?>
				<li><a href="#bookings" title="<?php esc_attr_e('My Accommodation Bookings', 'bookyourtravel'); ?>"><?php _e('My Accommodation Bookings', 'bookyourtravel'); ?></a></li>
				<?php } ?>
				<?php if (!$is_partner_page && $enable_reviews) { ?>
				<li><a href="#reviews" title="<?php esc_attr_e('My Reviews', 'bookyourtravel'); ?>"><?php _e('My Reviews', 'bookyourtravel'); ?></a></li>
				<?php } ?>
				<?php if ($is_partner_page && $frontend_submit->user_has_correct_role()) { ?>
				<?php if ($enable_accommodations) { ?>
				<li><a href="<?php echo esc_url($list_user_room_types_url); ?>" title="<?php esc_attr_e('My Room Types', 'bookyourtravel'); ?>"><?php _e('My Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($list_user_accommodations_url); ?>" title="<?php esc_attr_e('My Accommodations', 'bookyourtravel'); ?>"><?php _e('My Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($list_user_accommodation_vacancies_url); ?>" title="<?php esc_attr_e('My Vacancies', 'bookyourtravel'); ?>"><?php _e('My Vacancies', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($submit_room_types_url); ?>" title="<?php esc_attr_e('Submit Room Types', 'bookyourtravel'); ?>"><?php _e('Submit Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($submit_accommodations_url); ?>" title="<?php esc_attr_e('Submit Accommodations', 'bookyourtravel'); ?>"><?php _e('Submit Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($submit_accommodation_vacancies_url); ?>" title="<?php esc_attr_e('Submit Vacancies', 'bookyourtravel'); ?>"><?php _e('Submit Vacancies', 'bookyourtravel'); ?></a></li>
				<?php } ?>
				<?php } ?>
			</ul>
		</nav>
		<!--//inner navigation-->		
		<!--MySettings-->
		<section id="settings" class="tab-content initial">
			<script type="text/javascript">
			
				window.settingsFirstNameError = '<?php _e('First name is a required field!', 'bookyourtravel'); ?>';
				window.settingsLastNameError = '<?php _e('Last name is a required field!', 'bookyourtravel'); ?>';
				window.settingsEmailError = '<?php _e('Please enter valid email address!', 'bookyourtravel'); ?>';
				window.settingsPasswordError = '<?php _e('Password is a required field!', 'bookyourtravel'); ?>';
				window.settingsOldPasswordError = '<?php _e('Old password is a required field!', 'bookyourtravel'); ?>';
			
			</script>		
			<article class="mysettings">
				<h1><?php _e('Personal details', 'bookyourtravel'); ?></h1>
				<table>
					<tr>
						<th><?php _e('First name', 'bookyourtravel'); ?></th>
						<td><span id="span_first_name"><?php echo $user_info->user_firstname;?></span>						
							<div style="display:none;" class="edit_field field_first_name">
								<form id="settings-first-name-form" method="post" action="" class="settings">
									<label for="first_name"><?php _e('First name', 'bookyourtravel'); ?>:</label>
									<input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($user_info->user_firstname);?>"/>
									<input type="submit" value="save" class="gradient-button save_first_name"/>
									<a class="hide_edit_field" href="javascript:void(0);"><?php _e('Cancel', 'bookyourtravel'); ?></a>
								</form>
							</div>
						</td>
						<td><a class="edit_button" href="javascript:void(0);"><?php _e('Edit', 'bookyourtravel'); ?></a></td>
					</tr>
					<tr>
						<th><?php _e('Last name', 'bookyourtravel'); ?>:</th>
						<td><span id="span_last_name"><?php echo $user_info->user_lastname;?></span>	
							<div style="display:none;" class="edit_field field_last_name">
								<form id="settings-last-name-form" method="post" action="" class="settings">
									<label for="last_name"><?php _e('Last name', 'bookyourtravel'); ?>:</label>
									<input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($user_info->user_lastname);?>" />
									<input type="submit" value="save" class="gradient-button save_last_name"/>
									<a class="hide_edit_field" href="javascript:void(0);"><?php _e('Cancel', 'bookyourtravel'); ?></a>
								</form>
							</div>						
						</td>
						<td><a class="edit_button" href="javascript:void(0);"><?php _e('Edit', 'bookyourtravel'); ?></a></td>
					</tr>
					<tr>
						<th><?php _e('Email address', 'bookyourtravel'); ?>:</th>
						<td><span id="span_email"><?php echo $user_info->user_email;?></span>	
							<div style="display:none;" class="edit_field field_email">
								<form id="settings-email-form" method="post" action="" class="settings">
									<label for="email"><?php _e('Email', 'bookyourtravel'); ?>:</label>
									<input type="text" id="email" name="email" value="<?php echo esc_attr($user_info->user_email);?>" />
									<input type="submit" value="save" class="gradient-button save_email"/>
									<a class="hide_edit_field" href="javascript:void(0);"><?php _e('Cancel', 'bookyourtravel'); ?></a>
								</form>
							</div>
						</td>
						<td><a class="edit_button" href="javascript:void(0);"><?php _e('Edit', 'bookyourtravel'); ?></a></td>
					</tr>
					<tr>
						<th><?php _e('Password', 'bookyourtravel'); ?>:</th>
						<td><span id="span_email">**************</span>
							<div style="display:none;" class="edit_field field_password">		
								<form id="settings-password-form" method="post" action="" class="settings">
									<label for="old_password"><?php _e('Current password', 'bookyourtravel'); ?>:</label>
									<input type="password" id="old_password" name="old_password" />
									<label for="password"><?php _e('New password', 'bookyourtravel'); ?>:</label>
									<input type="password" id="password" name="password" />
									<input type="submit" value="save" class="gradient-button save_password"/>
									<a class="hide_edit_field" href="javascript:void(0);"><?php _e('Cancel', 'bookyourtravel'); ?></a>
								</form>
							</div></td>
						<td><a class="edit_button" href="javascript:void(0);"><?php _e('Edit', 'bookyourtravel'); ?></a></td>
					</tr>
				</table>
			</article>
		</section>
		<!--//MySettings-->
		<?php if (!$is_partner_page && $enable_accommodations) { ?>
		<!--My Bookings-->
		<section id="bookings" class="tab-content">
			<?php
				$date_format = get_option('date_format');
				$bookings_results = $byt_accommodations_post_type->list_accommodation_bookings(null, 0, 'Id', 'ASC', null, $current_user->ID);
				if ( count($bookings_results) > 0 && $bookings_results['total'] > 0 ) {
					foreach ($bookings_results['results'] as $bookings_result) {
						$booking_id = $bookings_result->Id;
						$booking_date_from =  date($date_format, strtotime($bookings_result->date_from));
						$booking_date_to =  date($date_format, strtotime($bookings_result->date_to)); 
						$booking_price =  $bookings_result->total_price;
						$accommodation = $bookings_result->accommodation_name;
						$room_type = $bookings_result->room_type;
						$adults = $bookings_result->adults;
						$children = $bookings_result->children;
			?>
			<!--booking-->
			<article class="bookings">
				<h1><a href="<?php echo get_permalink($bookings_result->accommodation_id); ?>"><?php echo $accommodation; ?></a></h1>
				<div class="b-info">
					<table>
						<tr>
							<th><?php _e('Booking number', 'bookyourtravel'); ?>:</th>
							<td><?php echo $booking_id; ?></td>
						</tr>
						<?php if (isset($room_type) && !empty($room_type)) { ?>
						<tr>
							<th><?php _e('Room type', 'bookyourtravel'); ?>:</th>
							<td><?php echo $room_type; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<th><?php _e('Check-in date', 'bookyourtravel'); ?>:</th>
							<td><?php echo $booking_date_from; ?></td>
						</tr>
						<tr>
							<th><?php _e('Check-out date', 'bookyourtravel'); ?>:</th>
							<td><?php echo $booking_date_to; ?></td>
						</tr>
						<tr>
							<th><?php _e('Adults', 'bookyourtravel'); ?>:</th>
							<td><?php echo $adults; ?></td>
						</tr>
						<tr>
							<th><?php _e('Children', 'bookyourtravel'); ?>:</th>
							<td><?php echo $children; ?></td>
						</tr>
						<tr>
							<th><?php _e('Total price', 'bookyourtravel'); ?>:</th>
							<td>
								<div class="second price">
									<em>
										<?php if (!$show_currency_symbol_after) { ?>
										<span class="curr"><?php echo $default_currency_symbol; ?></span>
										<span class="amount"><?php echo number_format_i18n( $booking_price, $price_decimal_places ); ?></span>
										<?php } else { ?>
										<span class="amount"><?php echo number_format_i18n( $booking_price, $price_decimal_places ); ?></span>
										<span class="curr"><?php echo $default_currency_symbol; ?></span>
										<?php } ?>
									</em>
								<div>
							</td>
						</tr>
					</table>
				</div>
			</article>
			<!--//booking-->
			<?php }
			} else { ?>
			<article class="bookings"><p><?php echo __('You have not made any bookings yet!', 'bookyourtravel'); ?></p></article>
			<?php } ?>
		</section>
		<!--//My Bookings-->
		<?php } ?>
		<?php if (!$is_partner_page && $enable_reviews) { ?>		
		<!--MyReviews-->
		<section id="reviews" class="tab-content">
			<?php 
			$reviews_query = $byt_reviews_post_type->list_user_reviews($current_user->ID);

			if ($reviews_query->have_posts()) { 
				while ($reviews_query->have_posts()) { 
				global $post;
				$reviews_query->the_post();
				$review = $post;
				$review_id = $review->ID;
				$review_custom_fields = get_post_custom($review_id);
				$reviewed_post_id = 0;
				if (isset($review_custom_fields['review_post_id'])) 
					$reviewed_post_id = $review_custom_fields['review_post_id'][0];
				if ($reviewed_post_id > 0) {
					$reviewed_item = get_post($reviewed_post_id);

					$reviews_score = 0;
					$reviews_possible_score = 10 * 7;
					$reviews_score = $byt_reviews_post_type->sum_user_review_meta_values($review_id, $current_user->ID, $reviewed_item->post_type);
					$score_out_of_10 = 0;
					if ($reviews_possible_score > 0) {
						$score_out_of_10 = intval(($reviews_score / $reviews_possible_score) * 10);
					}

					$likes = $review_custom_fields['review_likes'][0];
					$dislikes = $review_custom_fields['review_dislikes'][0]; ?>
				<article class="myreviews">	
					<?php if ($reviewed_item->post_type == 'accommodation') { ?>
						<h1><?php echo sprintf(__('Your review of accommodation %s', 'bookyourtravel'), $reviewed_item ? $reviewed_item->post_title : ''); ?></h1>
					<?php } else if ($reviewed_item->post_type == 'tour') { ?>
						<h1><?php echo sprintf(__('Your review of tour %s', 'bookyourtravel'), $reviewed_item ? $reviewed_item->post_title : ''); ?></h1>
					<?php } ?>
					<div class="score">
						<span class="achieved"><?php echo $score_out_of_10; ?></span>
						<span> / 10</span>
					</div>
					<div class="reviews">
						<div class="pro"><p><?php echo $likes; ?></p></div>
						<div class="con"><p><?php echo $dislikes; ?></p></div>
					</div>
				</article>			
			<?php 
					}
				} 
			} else { ?>
			<article class="myreviews"><p><?php echo __('You have not left any reviews yet!', 'bookyourtravel'); ?></p></article>
			<?php }			
			// Reset Loop Post Data
			?>
		</section>
		<!--//MyReviews-->
<?php } // if ($enable_reviews) ?>		
	</section>
	<!--//three-fourth content-->
 <?php 
 if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'right')
	get_sidebar('right');
 wp_reset_postdata(); 
  get_footer(); ?>