<?php
/**
 * The search template file.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');	
global $byt_theme_globals;
?><!--three-fourth content-->
	<section class="three-fourth">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>			
		<!--post-->
		<article class="static-content post">
			<header class="entry-header">
				<h1><a href="<?php echo esc_url(get_the_permalink()) ?>"><?php the_title(); ?></a></h1>
				<p class="entry-meta">
					<span class="date"><?php _e('Date', 'bookyourtravel');?>: <?php the_time(get_option('date_format')); ?></span> 
					<span class="author"><?php _e('By ', 'bookyourtravel'); the_author_posts_link(); ?></span> 
					<span class="categories"><?php _e('Categories', 'bookyourtravel'); ?>: <?php the_category(' ') ?></span>
					<span class="tags"><?php the_tags(); ?></span>
					<span class="comments">
						<a href="<?php esc_url(get_comments_link()); ?>" rel="nofollow">
							<?php comments_number(__('No comments', 'bookyourtravel'), __('1 Comment', 'bookyourtravel'), __('% Comments', 'bookyourtravel')); ?>
						</a>
					</span>
				</p>
			</header>
			<?php if ( has_post_thumbnail() ) { ?>
			<div class="entry-featured">
				<a href="<?php echo esc_url(get_the_permalink()) ?>">
					<figure>
						<?php the_post_thumbnail('featured', array('title' => '')); ?>
					</figure>
				</a>
			</div>
			<?php } ?>
			<div class="entry-content">
				<?php the_excerpt(); ?>
				<a href="<?php echo esc_url(get_the_permalink()) ?>" class="gradient-button" rel="nofollow"><?php _e('Read More...', 'bookyourtravel'); ?></a>
			</div>
		</article>
		<!--//post-->			
		<?php endwhile; else: ?>
		<article class="static-content post">
			<header class="entry-header">
				<p><strong><?php _e('There has been an error.', 'bookyourtravel'); ?></strong></p>
			</header>
			<div class="entry-content">
				<p><?php _e('We apologize for any inconvenience, please hit back on your browser or if you are an admin, enter some content.', 'bookyourtravel'); ?></p>
			</div>
		</article>
		<?php endif; ?>
		<!--bottom navigation-->
		<nav class="page-navigation bottom-nav">
			<a href="#" class="scroll-to-top" title="<?php esc_attr_e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
			<div class="pager">
			<?php 	
				global $wp_query;
				BYT_Theme_Utils::display_pager($wp_query->max_num_pages); 
			?>
			</div>
		</nav>
		<!--//bottom navigation-->
	</section>
	<!--//three-fourth content-->
<?php get_sidebar('right'); ?>
<?php get_footer();