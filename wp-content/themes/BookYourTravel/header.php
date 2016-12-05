<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7 ]><html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 	 ]><html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if lt IE 9]><script src="<?php echo esc_url ( BYT_Theme_Utils::get_file_uri('/js/html5shiv.js') ); ?>"></script><![endif]-->
<html <?php language_attributes(); ?>>
<head>
<?php
global $byt_theme_globals;
$login_page_url = $byt_theme_globals->get_login_page_url();
$register_page_url = $byt_theme_globals->get_register_page_url();
$my_account_page_url = $byt_theme_globals->get_my_account_page_url();
$cart_page_url = $byt_theme_globals->get_cart_page_url();
if (!isset($current_user)) {
	$current_user = wp_get_current_user();
}
?>
	<meta charset="<?php echo esc_attr(get_bloginfo( 'charset' )); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php wp_title(); ?></title>
	<link rel="shortcut icon" href="<?php echo esc_url(BYT_Theme_Utils::get_file_uri('/images/favicon.ico')); ?>" />	
	<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' )); ?>" />
	<script type="text/javascript">
		window.datepickerDateFormat = "<?php echo BYT_Theme_Utils::dateformat_PHP_to_jQueryUI(get_option('date_format')) ?>";
		window.datepickerAltFormat = "<?php echo BOOKYOURTRAVEL_ALT_DATE_FORMAT ?>";
		window.themePath = '<?php echo esc_js( get_template_directory_uri() ); ?>';
		window.siteUrl = '<?php echo esc_js( $byt_theme_globals->get_site_url() ); ?>';
		window.wooCartPageUri = '<?php echo $byt_theme_globals->get_cart_page_url(); ?>';
		window.useWoocommerceForCheckout = <?php echo $byt_theme_globals->use_woocommerce_for_checkout(); ?>;
<?php
	if ($current_user->ID > 0){	?>
		window.currentUserId = '<?php echo $current_user->ID;?>';
	<?php } else { ?>	
		window.currentUserId = 0;
	<?php } ?>
<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) { ?>
		window.currentLanguage = '<?php echo ICL_LANGUAGE_CODE; ?>';
<?php } ?>
	</script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<?php
	$body_class = '';
	$content_class = '';
	if (is_page_template('byt_home.php')) {
		if (!$byt_theme_globals->frontpage_show_slider())
			$body_class = 'noslider';
	} elseif (is_page_template('page-contact.php') || is_page_template('page-contact-form-7.php')) {
		BYT_Theme_Utils::contact_form_js();
		$business_address_longitude = $byt_theme_globals->get_business_address_longitude();
		$business_address_latitude = $byt_theme_globals->get_business_address_latitude();
		$content_class = (!empty($business_address_longitude) && !empty($business_address_latitude) ? '' : 'empty');
	}	
	wp_head(); 
?>
</head>
<body <?php body_class($body_class); ?>>
	<!--header-->
	<header class="header" role="banner">
		<div class="wrap clearfix">
			<!--logo-->
			<?php $logo_title = get_bloginfo('name') . ' | ' . ( is_home() || is_front_page() ? get_bloginfo('description') : wp_title('', false)); ?>
			<div class="logo"><a href="<?php echo esc_url( get_home_url() ); ?>" title="<?php echo esc_attr($logo_title); ?>"><img src="<?php echo esc_url( $byt_theme_globals->get_theme_logo_src() ); ?>" alt="<?php echo esc_attr( $logo_title ); ?>" /></a></div>
			<!--//logo-->
			<?php if (!$byt_theme_globals->hide_header_ribbon()) { ?>
			<!--ribbon-->
			<div class="ribbon">
				<nav>
					<ul class="profile-nav">
						<?php if (!is_user_logged_in() && (!empty($login_page_url) || !empty($register_page_url) || !empty($cart_page_url))) { ?>
						<li class="active"><a href="javascript:void(0);" title="<?php esc_attr_e('My Account', 'bookyourtravel'); ?>"><?php _e('My Account', 'bookyourtravel'); ?></a></li>
						<?php if (!empty($login_page_url)) { ?>
						<li><a class="fn" onclick="toggleLightbox('login_lightbox');" href="javascript:void(0);" title="<?php esc_attr_e('Login', 'bookyourtravel'); ?>"><?php _e('Login', 'bookyourtravel'); ?></a></li>
						<?php } ?>
						<?php if (!empty($register_page_url)) { ?>
						<li><a class="fn" onclick="toggleLightbox('register_lightbox');" href="javascript:void(0);" title="<?php esc_attr_e('Register', 'bookyourtravel'); ?>"><?php _e('Register', 'bookyourtravel'); ?></a></li>
						<?php } ?>
						<?php
						if (!empty($cart_page_url)) { ?>
							<li><a class="fn" href="<?php echo esc_url( $cart_page_url ); ?>"><?php _e('Cart', 'bookyourtravel'); ?></a></li>	
						<?php } ?>
						<?php } else {?>
						<li class="active"><a href="javascript:void(0);" title="<?php esc_attr_e('My Account', 'bookyourtravel'); ?>"><?php _e('My Account', 'bookyourtravel'); ?></a></li>
						<?php if ((!empty($my_account_page_url) || !empty($cart_page_url))) { ?>
						<li><a class="fn" href="<?php echo esc_url( $my_account_page_url ); ?>" title="<?php esc_attr_e('Dashboard', 'bookyourtravel'); ?>"><?php _e('Dashboard', 'bookyourtravel'); ?></a></li>
						<?php						
						if (!empty($cart_page_url)) { ?>
							<li><a class="fn" href="<?php echo esc_url($cart_page_url); ?>"><?php _e('Cart', 'bookyourtravel'); ?></a></li>	
						<?php } ?>
						<?php } // (!empty($my_account_page_url) || !empty($cart_page_url)) ?>
						<li><a class="fn" href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', 'bookyourtravel'); ?></a></li>
						<?php } ?>
					</ul>
					<?php if (!BYT_Theme_Utils::is_woocommerce_active()) {?>
					<?php } ?>
					<?php get_sidebar('header'); ?>
				</nav>
			</div>
			<!--//ribbon-->
			<?php } // endif (!$hide_header_ribbon) ?>
			<!--search-->
			<div class="search">
				<form id="searchform" method="get" action="<?php echo esc_url( home_url() ); ?>">
					<input type="search" placeholder="<?php esc_attr_e('Search entire site here', 'bookyourtravel'); ?>" name="s" id="search" /> 
					<input type="submit" id="searchsubmit" value="" name="searchsubmit"/>
				</form>
			</div>
			<!--//search-->		
			<!--contact-->
			<div class="contact">
				<span><?php _e('24/7 Support number', 'bookyourtravel'); ?></span>
				<span class="number"><?php echo $byt_theme_globals->get_contact_phone_number(); ?></span>
			</div>
			<!--//contact-->
		</div>
		<!--primary navigation-->
		<?php  if ( has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => 'nav', 'container_class' => 'main-nav', 'container_id' => 'nav', 'menu_class' => 'wrap') );
		} else { ?>
		<nav class="main-nav">
			<ul class="wrap">
				<li class="menu-item"><a href="<?php echo esc_url ( home_url() ); ?>"><?php _e('Home', "bookyourtravel"); ?></a></li>
				<li class="menu-item"><a href="<?php echo esc_url ( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', "bookyourtravel"); ?></a></li>
			</ul>
		</nav>
		<?php } ?>
		<!--//primary navigation-->
	</header>
	<!--//header-->
	<?php 
	if (is_page_template('byt_home.php')) {
		get_template_part('includes/parts/home-page-header', 'latest'); 
	}
	?>
	<!--main-->
	<div class="main" role="main" id="primary">		
		<div class="wrap clearfix">
			<!--main content-->
			<div class="content clearfix <?php echo $content_class; ?>" id="content">
			