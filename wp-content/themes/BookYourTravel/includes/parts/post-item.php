<?php
	global $post, $post_class, $display_mode, $byt_theme_globals, $byt_posts_post_type;
	
	$post_id = $post->ID;
	$post_obj = new byt_post($post);
	$base_id = $post_obj->get_base_id();
	
	$post_image = $post_obj->get_main_image();	
	if (empty($post_image)) {
		$post_image = BYT_Theme_Utils::get_file_uri('/images/uploads/img.jpg');
	}
	
	if (empty($display_mode) || $display_mode == 'card') {
?>
<!--post-->
<article class=" <?php echo $post_class; ?>">
	<div>
		<?php if (!empty($post_image)) { ?>
		<figure>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo esc_url($post_image); ?>" alt="<?php the_title(); ?>" /></a>
		</figure>
		<?php } ?>
		<div class="details">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><h4><?php the_title(); ?></h4></a>
			<div class='actions'>
				<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e('More info', 'bookyourtravel'); ?>" class=" gradient-button"><?php _e('More info', 'bookyourtravel'); ?></a>
			</div>
		</div>
	</div>
</article>
<!--//post-->
<?php 
	} else {
?>
	<li>
		<a href="<?php echo esc_url($post_obj->get_permalink()); ?>">
			<h3><?php echo $post_obj->get_title(); ?> <?php if ($post_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?>
			</h3>
		</a>
	</li>
<?php }