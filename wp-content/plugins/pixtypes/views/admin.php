<?php
	/**
	 * Represents the view for the administration dashboard.
	 *
	 * This includes the header, options, and other information that should
	 * provide the user interface to the end user.
	 *
	 * @package   PixTypes
	 * @author    Pixelgrade <contact@pixelgrade.com>
	 * @license   GPL-2.0+
	 * @link      http://pixelgrade.com
	 * @copyright 2013 Pixel Grade Media
	 */

	$config = include pixtypes::pluginpath().'plugin-config'.EXT;

	// invoke processor
	$processor = pixtypes::processor($config);
	$status = $processor->status();
	$errors = $processor->errors(); ?>

<div class="wrap" id="pixtypes_form">

	<div id="icon-options-general" class="icon32"><br></div>

	<h2><?php _e('Pixtypes', 'pixtypes'); ?></h2>

	<?php if ($processor->ok()): ?>

		<?php if ( ! empty($errors)): ?>
			<br/>
			<p class="update-nag">
				<strong><?php _e('Unable to save settings.', 'pixtypes'); ?></strong>
				<?php _e('Please check the fields for errors and typos.', 'pixtypes'); ?>
			</p>
		<?php endif; ?>

		<?php if ($processor->performed_update()): ?>
			<br/>
			<p class="update-nag">
				<?php _e('Settings have been updated.', 'pixtypes');?>
			</p>
		<?php endif; ?>

		<?php echo $f = pixtypes::form($config, $processor);
		echo $f->field('hiddens')->render(); ?>

			<?php echo $f->field('post_types')->render(); ?>

			<?php echo $f->field('taxonomies')->render(); ?>

			<button type="submit" class="button button-primary">
				<?php _e('Save Changes', 'pixtypes'); ?>
				</button>

		<?php echo $f->endform() ?>

	<?php elseif ($status['state'] == 'error'): ?>

		<h3>Critical Error</h3>

		<p><?php echo $status['message'] ?></p>

	<?php endif; ?>

	<?php $options = get_option('pixtypes_settings');
	if ( isset( $options['themes'] ) && count($options['themes']) > 1 ) { ?>

		<div class="uninstall_area postbox">

			<div class="handlediv" title="Click to toggle"><br></div>
			<h3 class="hndle"><span>Danger Zone</span></h3>

			<div class="inside">

				<p><?php _e('If you are done with copying your content from old post types to the new ones, you can also get rid of the old post types', 'pixtypes'); ?></p>
				<form method="post" id="unset_pixypes" action="<?php echo admin_url('options-general.php?page=pixtypes') ?>" >
					<input type="hidden" class="unset_nonce" name="unset_nonce" value="<?php echo wp_create_nonce('unset_pixtype') ?>" />
					<ul>
						<?php
						if ( isset( $options['themes'] ) && count( $options['themes'] ) > 1 ) {
							foreach( $options['themes'] as $key => $theme ){
								echo '<li><button class="button delete-action" type="submit" name="unset_pixtype" value="'. $key .'">'.__('Unset', 'pixtypes').' '.$key.'</button></li>';
							}
						} ?>
					</ul>
				</form>
			</div>
		</div>
	<?php } ?>
</div>