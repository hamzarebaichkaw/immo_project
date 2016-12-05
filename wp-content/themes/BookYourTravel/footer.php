<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
global $byt_theme_globals;
?></div><!--//main content-->
</div><!--//wrap-->
<?php get_sidebar('above-footer'); ?>
</div><!--//main-->
<!--footer-->
<footer class="footer" role="contentinfo">
	<?php get_sidebar('footer'); ?>
	<div class="wrap clearfix">		
		<section class="bottom">
			<p class="copy"><?php echo $byt_theme_globals->get_copyright_footer(); ?></p>				
			<!--footer navigation-->				
			<?php if ( has_nav_menu( 'footer-menu' ) ) {
				wp_nav_menu( array( 
					'theme_location' => 'footer-menu', 
					'container' => 'nav', 
				) ); 
			} else { ?>
			<nav class="menu-main-menu-container">
				<ul class="menu">
					<li class="menu-item"><a href="<?php echo esc_url(home_url()); ?>"><?php _e('Home', "bookyourtravel"); ?></a></li>
					<li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', "bookyourtravel"); ?></a></li>
				</ul>
			</nav>
			<?php } ?>
			<!--//footer navigation-->
		</section>
	</div>
</footer>
<!--//footer-->
<?php 

get_template_part('includes/parts/login', 'lightbox');
get_template_part('includes/parts/register', 'lightbox'); 
wp_footer();
if (WP_DEBUG) {
	$num_queries = get_num_queries();
	$timer = timer_stop(0);
	echo '<!-- ' . $num_queries . ' queries in ' . $timer . ' seconds. -->';
} 
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    Array.prototype.inArray = function(element) {
        for(var i=0, limit = this.length; i < limit; i++) {
            if(this[i] == element) { return true; }
        }
        return false;
    };
    Array.prototype.pushIfNotExist = function(element) {
        if (!this.inArray(element)) {
            this.push(element);
        }
    };
    var days = document.getElementsByClassName('parent-day');
    var btnsMargin = [];
    for (var i = 0, limit = days.length; i < limit; i++) {
        btnsMargin = days[i].getElementsByClassName('btn-hover');
        for (var j = 0, max = btnsMargin.length; j < max; j++) {
            var envent = parseInt(btnsMargin[j].getAttribute('event-in-month'));
           //btnsMargin[j].style.marginTop = (9 ) + 'px';
        }
        //days[i].style.height = (3) + 'px';
        //days[i].style.width = (1) + 'px';
    }
    var btns = document.getElementsByClassName('btn-hover');
    var allDaysEvents = [];
    for (var i = 0, limit = btns.length; i < limit; i++) {
        var classes = btns[i].className.split(/\s/);
        for (var j = 0, max = classes.length; j < max; j++) {
            if (classes[j].search(/^btn\-[\d]+$/) > -1) {
                allDaysEvents[i] = document.getElementsByClassName(classes[j]);
            }
        }
        btns[i].addEventListener('mouseover', function(i){
            return function(){
                for (var j = 0, max = allDaysEvents[i].length; j < max; j++) {
                    allDaysEvents[i][j].className = allDaysEvents[i][j].className.replace(/\bbtn-(success|warning|danger|info)\b/, 'btn-primary $1');
                }
            }
        }(i), false);
        btns[i].addEventListener('mouseout', function(i){
            return function(){
                for (var j = 0, max = allDaysEvents[i].length; j < max; j++) {
                    allDaysEvents[i][j].className = allDaysEvents[i][j].className.replace(/\bbtn-primary (success|warning|danger|info)\b/, 'btn-$1');
                }
            }
        }(i), false);
    }
</script>
</body>
</html>