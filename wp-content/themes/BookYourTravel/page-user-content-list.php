<?php 
/* Template Name: User Content List
 * The template for displaying the user submitted content list.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $byt_theme_globals, $byt_accommodations_post_type, $byt_reviews_post_type, $current_user, $frontend_submit;
 
if ( !is_user_logged_in() || !$frontend_submit->user_has_correct_role()) {
	wp_redirect( get_home_url() );
	exit;
}

get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');

$current_user = wp_get_current_user();
$user_info = get_userdata($current_user->ID);

$enable_reviews = $byt_theme_globals->enable_reviews();
$enable_accommodations = $byt_theme_globals->enable_accommodations();
$current_user = wp_get_current_user();
$user_info = get_userdata($current_user->ID);
$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();

$my_account_page = $byt_theme_globals->get_my_account_page_url();
$submit_room_types_url = $byt_theme_globals->get_submit_room_types_url();
$submit_accommodations_url = $byt_theme_globals->get_submit_accommodations_url();
$submit_accommodation_vacancies_url = $byt_theme_globals->get_submit_accommodation_vacancies_url();
$list_user_room_types_url = $byt_theme_globals->get_list_user_room_types_url();
$list_user_accommodations_url = $byt_theme_globals->get_list_user_accommodations_url();
$list_user_accommodation_vacancies_url = $byt_theme_globals->get_list_user_accommodation_vacancies_url();

$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);
$current_url = get_permalink( $page_id );

$content_type = 'accommodation';
if (isset($page_custom_fields['user_content_type'])) {
	$content_type = $page_custom_fields['user_content_type'][0];
}

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$posts_per_page = get_option('posts_per_page');


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
				<li><a href="<?php echo esc_url($my_account_page); ?>" title="<?php esc_attr_e('Settings', 'bookyourtravel'); ?>"><?php _e('Settings', 'bookyourtravel'); ?></a></li>
				<?php if ($frontend_submit->user_has_correct_role()) { ?>
				<?php if ($enable_accommodations) { ?>
				<li <?php echo $content_type == 'room_type' ? 'class="active"' : ''; ?>><a href="<?php echo esc_url($list_user_room_types_url); ?>" title="<?php esc_attr_e('My Room Types', 'bookyourtravel'); ?>"><?php _e('My Room Types', 'bookyourtravel'); ?></a></li>
				<li <?php echo $content_type == 'accommodation' ? 'class="active"' : ''; ?>><a href="<?php echo esc_url($list_user_accommodations_url); ?>" title="<?php esc_attr_e('My Accommodations', 'bookyourtravel'); ?>"><?php _e('My Accommodations', 'bookyourtravel'); ?></a></li>
				<li <?php echo $content_type == 'vacancy' ? 'class="active"' : ''; ?>><a href="<?php echo esc_url($list_user_accommodation_vacancies_url); ?>" title="<?php esc_attr_e('My Vacancies', 'bookyourtravel'); ?>"><?php _e('My Vacancies', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($submit_room_types_url); ?>" title="<?php esc_attr_e('Submit Room Types', 'bookyourtravel'); ?>"><?php _e('Submit Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($submit_accommodations_url); ?>" title="<?php esc_attr_e('Submit Accommodations', 'bookyourtravel'); ?>"><?php _e('Submit Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($submit_accommodation_vacancies_url); ?>" title="<?php esc_attr_e('Submit Vacancies', 'bookyourtravel'); ?>"><?php _e('Submit Vacancies', 'bookyourtravel'); ?></a></li>
				<?php } ?>
				<?php } ?>
			</ul>
		</nav>
		<!--//inner navigation-->
		<?php if ($enable_accommodations) { ?>
		<?php if ($content_type == 'accommodation') { ?>
		<!--Accommodation list-->
		<section id="accommodationlist" class="tab-content initial">
			<?php
				$accommodation_results = $byt_accommodations_post_type->list_accommodations ( $paged, $posts_per_page, '', '', 0, array(), array(), array(), false, null, $current_user->ID, true );
			?>
			<div class="deals clearfix">
				<?php if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) { ?>
				<div class="inner-wrap">
				<?php
				foreach ($accommodation_results['results'] as $accommodation_result) {
					global $post, $accommodation_class;
					$post = $accommodation_result;
					setup_postdata( $post ); 
					$accommodation_class = 'full-width';
					get_template_part('includes/parts/accommodation', 'item');
				}
				?>
				</div>
				<nav class="page-navigation bottom-nav">
					<!--back up button-->
					<a href="#" class="scroll-to-top" title="<?php esc_attr_e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
					<!--//back up button-->
					<!--pager-->
					<div class="pager">
						<?php 
						$total_results = $accommodation_results['total'];
						BYT_Theme_Utils::display_pager( ceil($total_results/$posts_per_page) );
						?>
					</div>
				</nav>
			<?php } else {
					   echo '<p>' . __('You have not submitted any accommodations yet.', 'bookyourtravel') . '</p>';
				  }  // end if ( $query->have_posts() ) ?>
			</div><!--//deals clearfix-->
		</section>
		<?php } elseif ($content_type == 'vacancy') {?>
		
		<script>
		
			function accommodationSelectRedirect(accommodationId) {
				document.location = '<?php echo $list_user_accommodation_vacancies_url; ?>?accid=' + accommodationId;
			};
		
		</script>
		<?php 
			$accommodation_id = 0;
			if ( isset($_GET['accid']) ) {
				$accommodation_id = intval($_GET['accid']);
			}
			$date_format = get_option('date_format');
		?>
		<section id="accommodation-vacancy-list" class="tab-content initial">
			<div class="filter">
				<label for="filter_user_accommodations"><?php _e('Filter by', 'bookyourtravel'); ?></label>
			<?php
			$accommodation_results = $byt_accommodations_post_type->list_accommodations ( 0, -1, '', '', 0, array(), array(), array(), false, null, $current_user->ID, true );
			$select_accommodations = "<select onchange='accommodationSelectRedirect(this.value)' name='filter_user_accommodations' id='filter_user_accommodations'>";
			$select_accommodations .= "<option value=''>" . __('Select accommodation', 'bookyourtravel') . "</option>";
			if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
				foreach ($accommodation_results['results'] as $accommodation_result) {
					global $post, $accommodation_class;
					$post = $accommodation_result;
					setup_postdata( $post ); 
					$select_accommodations .= "<option " . ($post->ID == $accommodation_id ? "selected" : "") . " value='$post->ID'>$post->post_title</option>";
				}
			}
			$select_accommodations .= "</select>";
			echo $select_accommodations;
			
			if ($accommodation_id > 0) {
				$vacancy_results = $byt_accommodations_post_type->list_all_accommodation_vacancies($accommodation_id, 0, '', '', $paged, $posts_per_page);
				
				if ( count($vacancy_results) > 0 && $vacancy_results['total'] > 0 ) {
					foreach ($vacancy_results['results'] as $vacancy_result) {
						$accommodation_obj = new byt_accommodation($vacancy_result->accommodation_id);
						$is_self_catered = $accommodation_obj->get_is_self_catered();
						$is_price_per_person = $accommodation_obj->get_is_price_per_person();
						
						$room_type_obj = null;
						if (!$is_self_catered)
							$room_type_obj = new byt_room_type($vacancy_result->room_type_id);
				?>				
				<article class="bookings vacancies">
					<h1>
						<a href="<?php echo esc_url($accommodation_obj->get_permalink()); ?>"><?php echo $accommodation_obj->get_title(); ?></a>
						<span></span>
					</h1>
					<div class="b-info">
						<table>
							<tr>
								<th><?php _e('Vacancy Id', 'bookyourtravel'); ?>:</th>
								<td>
									<?php echo $vacancy_result->Id; ?>
									<?php BYT_Theme_Utils::render_link_button($submit_accommodation_vacancies_url . "?fesid=" . $vacancy_result->Id, "gradient-button", "", __('Edit', 'bookyourtravel')); ?>
								</td>
							</tr>
							<tr>
								<th><?php _e('Room type', 'bookyourtravel'); ?>:</th>
								<td><?php echo $room_type_obj == null ? __('N/A', 'bookyourtravel') : $room_type_obj->get_title(); ?></td>
							</tr>
							<tr>
								<th><?php _e('Start date', 'bookyourtravel'); ?>:</th>
								<td><?php echo date($date_format, strtotime($vacancy_result->start_date)); ?></td>
							</tr>
							<tr>
								<th><?php _e('End date', 'bookyourtravel'); ?>:</th>
								<td><?php echo date($date_format, strtotime($vacancy_result->end_date)); ?></td>
							</tr>
							<tr>
								<th><?php _e('Available rooms', 'bookyourtravel'); ?>:</th>
								<td><?php echo $room_type_obj == null ? __('N/A', 'bookyourtravel') : $vacancy_result->room_count; ?></td>
							</tr>
							<tr>
								<th><?php _e('Price', 'bookyourtravel'); ?>:</th>
								<td><?php echo $default_currency_symbol . $vacancy_result->price_per_day; ?><?php echo $is_price_per_person ? ' / ' . $default_currency_symbol . $vacancy_result->price_per_day_child : ''; ?></td>
							</tr>
						</table>
					</div>
				</article>
				<?php } ?>
				<nav class="page-navigation bottom-nav">
					<!--back up button-->
					<a href="#" class="scroll-to-top" title="<?php esc_attr_e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
					<!--//back up button-->
					<!--pager-->
					<div class="pager">
						<?php 
						$total_results = $vacancy_results['total'];
						BYT_Theme_Utils::display_pager( ceil($total_results/$posts_per_page) );
						?>
					</div>
				</nav>
				
				<?php
				} else {
				   echo '<p>' . __('You have not created any vacancies yet.', 'bookyourtravel') . '</p>';
				}
			}
			?>
			</div>
		</section>		
		
		<?php } elseif ($content_type == 'room_type' ) { ?>
		
		<script>
			window.moreInfoText = '<?php echo __('+ more info', 'bookyourtravel'); ?>';
			window.lessInfoText = '<?php echo __('+ less info', 'bookyourtravel'); ?>';
		</script>
		<!--Room list-->
		<section id="room-list" class="tab-content initial">
			<article>
				<?php
					$room_type_query = $byt_room_types_post_type->list_room_types($current_user->ID, array('publish', 'private'));
					if ($room_type_query->have_posts()) {
					?>
					<ul class="room-types">
					<?php
						while ($room_type_query->have_posts()) {
							$room_type_query->the_post();
							global $post;				
							$room_type_id = intval($post->ID);
							$room_type_obj = new byt_room_type($room_type_id);
					?>
						<li id="room_type_<?php echo $room_type_id; ?>">
							<?php if ($room_type_obj->get_main_image('medium')) { ?>
								<figure class="left"><img src="<?php echo esc_url($room_type_obj->get_main_image('medium')) ?>" alt="<?php echo esc_attr($room_type_obj->get_title()); ?>" /><a href="<?php echo esc_url($room_type_obj->get_main_image()); ?>" class="image-overlay" rel="prettyPhoto[gallery1]"></a></figure>
							<?php } ?>
							<div class="meta room_type">
								<h2><?php echo $room_type_obj->get_title(); ?> <?php if ($room_type_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?></h2>
								<?php BYT_Theme_Utils::render_field('', '', '', $room_type_obj->get_custom_field('meta'), '', true, true); ?>
								<?php BYT_Theme_Utils::render_link_button("#", "more-info", "", __('+ more info', 'bookyourtravel')); ?>
							</div>
							<div class="room-information">
								<div class="row">
									<span class="first"><?php _e('Max:', 'bookyourtravel'); ?></span>
									<span class="second">
										<?php for ( $j = 0; $j < $room_type_obj->get_custom_field('max_count'); $j++ ) { ?>
										<img src="<?php echo BYT_Theme_Utils::get_file_uri('/images/ico/person.png'); ?>" alt="" />
										<?php } ?>
									</span>
									<?php BYT_Theme_Utils::render_link_button($submit_room_types_url . "?fesid=" . $post->ID, "gradient-button", "", __('Edit', 'bookyourtravel')); ?>
								</div>
							</div>
							<div class="more-information">
								<?php BYT_Theme_Utils::render_field('', '', __('Room facilities:', 'bookyourtravel'), $room_type_obj->get_facilities_string(), '', true, true); ?>
								<?php echo $room_type_obj->get_description(); ?>
								<?php BYT_Theme_Utils::render_field('', '', __('Bed size:', 'bookyourtravel'), $room_type_obj->get_custom_field('bed_size'), '', true, true); ?>
								<?php BYT_Theme_Utils::render_field('', '', __('Room size:', 'bookyourtravel'), $room_type_obj->get_custom_field('room_size'), '', true, true); ?>
							</div>
						</li>
					<?php } ?>
					</ul>
				<?php }  else {
						   echo '<p>' . __('You have not submitted any room types yet.', 'bookyourtravel') . '</p>';
					  }?>
			</article>
		</section>
		
		<?php } // if content_type == ?>
		<?php } // if enable_accommodations ?>
	</section>
<?php
if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'right')
	get_sidebar('right');
wp_reset_postdata();
wp_reset_query();
get_footer(); 