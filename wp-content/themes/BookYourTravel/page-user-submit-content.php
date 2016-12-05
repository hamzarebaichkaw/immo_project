<?php
/*	Template Name: User Submit Content
 * The template for displaying submit forms for front-end content submission
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

global $post;
$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id );
$current_url = get_permalink( $page_id );

$content_type = 'accommodation';
if (isset($page_custom_fields['frontend_submit_content_type'])) {
	$content_type = $page_custom_fields['frontend_submit_content_type'][0];
	$frontend_submit->prepare_form($content_type);
}

$login_page_url = $byt_theme_globals->get_login_page_url();
$register_page_url = $byt_theme_globals->get_register_page_url();
$my_account_page_url = $byt_theme_globals->get_my_account_page_url();

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
	<section class="<?php echo esc_attr($section_class); ?>">
		<nav class="inner-nav">
			<ul>
				<li><a href="<?php echo esc_url($my_account_page); ?>" title="<?php esc_attr_e('Settings', 'bookyourtravel'); ?>"><?php _e('Settings', 'bookyourtravel'); ?></a></li>
				<?php if ($frontend_submit->user_has_correct_role()) { ?>
				<?php if ($enable_accommodations) { ?>
				<li><a href="<?php echo esc_url($list_user_room_types_url); ?>" title="<?php esc_attr_e('My Room Types', 'bookyourtravel'); ?>"><?php _e('My Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($list_user_accommodations_url); ?>" title="<?php esc_attr_e('My Accommodations', 'bookyourtravel'); ?>"><?php _e('My Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo esc_url($list_user_accommodation_vacancies_url); ?>" title="<?php esc_attr_e('My Vacancies', 'bookyourtravel'); ?>"><?php _e('My Vacancies', 'bookyourtravel'); ?></a></li>
				<li <?php echo $current_url == $submit_room_types_url ? 'class="active"' : ''; ?>><a href="<?php echo esc_url($submit_room_types_url); ?>" title="<?php esc_attr_e('Submit Room Types', 'bookyourtravel'); ?>"><?php _e('Submit Room Types', 'bookyourtravel'); ?></a></li>
				<li <?php echo $current_url == $submit_accommodations_url ? 'class="active"' : ''; ?>><a href="<?php echo esc_url($submit_accommodations_url); ?>" title="<?php esc_attr_e('Submit Accommodations', 'bookyourtravel'); ?>"><?php _e('Submit Accommodations', 'bookyourtravel'); ?></a></li>
				<li <?php echo $current_url == $submit_accommodation_vacancies_url ? 'class="active"' : ''; ?>><a href="<?php echo esc_url($submit_accommodation_vacancies_url); ?>" title="<?php esc_attr_e('Submit Vacancies', 'bookyourtravel'); ?>"><?php _e('Submit Vacancies', 'bookyourtravel'); ?></a></li>
				<?php } ?>
				<?php } ?>
			</ul>
		</nav>
		<!--//inner navigation-->	
		<section id="Submit" class="tab-content initial">
			<?php  while ( have_posts() ) : the_post(); ?>
			<article id="page-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'bookyourtravel' ) ); ?>
				<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
				<?php echo $frontend_submit->upload_form($content_type); ?>
			</article>		
			<?php endwhile; ?>
		</section>
	</section>
<?php
wp_reset_postdata();
wp_reset_query();

if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'right')
	get_sidebar('right');

get_footer();