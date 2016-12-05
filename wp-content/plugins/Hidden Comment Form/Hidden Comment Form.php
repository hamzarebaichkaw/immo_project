<?php
/*
Plugin Name: Hidden Comment Form
Plugin URI: http://tarskitheme.com/help/hooks/example-plugins/
Description: Hide the comment form until the user clicks a link to reveal it.
Author: Benedict Eastaugh
Version: 1.1
Author URI: http://extralogical.net/
*/
/**
 * This function adds the jQuery library to the list of JavaScript files to be
 * loaded with the page. It is required to make the comment hiding script work.
 */
function hcf_add_jquery_on_commentables() {
	wp_enqueue_script('jquery');
}
/**
 * This JavaScript relies on the jQuery library, and the comment form having
 * an id attribute with the value 'commentform'. This is used in the default
 * theme and thus should be fairly standard across WordPress themes. Change the
 * value of the $text variable to amend the text of the toggle.
 */
function hcf_toggle_form_js() {
	$text = __('Comment on this post &raquo;', 'hidden_comment_form');
 	print <<<TOGGLE_FORM_JS
	<script type="text/javascript">
		(function() {
		  var $ = jQuery, form = $('#commentform').hide(), hidden = true;
		  form.after('<div id="cf_toggle">${text}</div>');
		  $('#cf_toggle').click(function() {
		    if (!hidden) return;
		    form.show();
		    $(this).hide();
		    hidden = false;
		  });
		})();
	</script>
TOGGLE_FORM_JS;
}
/**
 * This CSS is optimised for the Tarski theme; if you're not using Tarski, you
 * will want to either change the styling or comment out the add_action line
 * at the bottom of this file that invokes this function.
 */
function hcf_toggle_css() {
	if (!comments_open()) return;
	
	echo <<<TOGGLE_CSS
	<style type="text/css" media="screen,projection">
		#cf_toggle {
			font-family: 'Times New Roman', Times, serif;
			font-size: 1.5em;
			line-height: 1.2;
			color:  #006A80;
			cursor: pointer;
		}
	</style>
TOGGLE_CSS;
}
add_action('init', 'hcf_add_jquery_on_commentables');
add_action('wp_head', 'hcf_toggle_css');
add_action('comment_form', 'hcf_toggle_form_js');
/**
 * CHANGELOG
 *
 * Version 1.1
 *
 * - Add some explanatory comments to the various functions.
 * - CSS media attribute corrected to include projection, not projector
 *
 * Version 1.0
 *
 * - Initial release.
 */
?>