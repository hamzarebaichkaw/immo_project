<?php
get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');	

global $post, $byt_theme_globals;

$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

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
<!--full-width content-->
<section class="<?php echo esc_attr($section_class); ?>">
	<?php  while ( have_posts() ) : the_post(); ?>
	<article <?php post_class("static-content"); ?> id="page-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'bookyourtravel' ) ); ?>
		<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
	</article>
	<?php endwhile; ?>
</section>
<!--//full-width content--> 
<?php 
if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'right')
	get_sidebar('right');
	
get_footer();