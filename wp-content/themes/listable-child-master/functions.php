<?php



/**



 * Listable Child functions and definitions



 *



 * Bellow you will find several ways to tackle the enqueue of static resources/files



 * It depends on the amount of customization you want to do



 * If you either wish to simply overwrite/add some CSS rules or JS code



 * Or if you want to replace certain files from the parent with your own (like style.css or main.js)



 *



 * @package ListableChild



 */



















/**



 * Setup Listable Child Theme's textdomain.



 *



 * Declare textdomain for this child theme.



 * Translations can be filed in the /languages/ directory.



 */



function listable_child_theme_setup() {



	load_child_theme_textdomain( 'listable-child-theme', get_stylesheet_directory() . '/languages' );



}



add_action( 'after_setup_theme', 'listable_child_theme_setup' );















/**



 *



 * 1. Add a Child Theme "style.css" file



 * ----------------------------------------------------------------------------



 *



 * If you want to add static resources files from the child theme, use the



 * example function written below.



 *



 */







function listable_child_enqueue_styles() {



	$theme = wp_get_theme();



	// use the parent version for cachebusting



	$parent = $theme->parent();







	if ( !is_rtl() ) {



		wp_enqueue_style( 'listable-style', get_template_directory_uri() . '/style.css', array(), $parent->get( 'Version' ) );



	} else {



		wp_enqueue_style( 'listable-style', get_template_directory_uri() . '/rtl.css', array(), $parent->get( 'Version' ) );



	}







	// Here we are adding the child style.css while still retaining



	// all of the parents assets (style.css, JS files, etc)



	wp_enqueue_style( 'listable-child-style',



		get_stylesheet_directory_uri() . '/style.css',



		array('listable-style') //make sure the the child's style.css comes after the parents so you can overwrite rules



	);



}







add_action( 'wp_enqueue_scripts', 'listable_child_enqueue_styles' );























/**



 *



 * 2. Overwrite Static Resources (eg. style.css or main.js)



 * ----------------------------------------------------------------------------



 *



 * If you want to overwrite static resources files from the parent theme



 * and use only the ones from the Child Theme, this is the way to do it.



 *



 */











/*







function listable_child_overwrite_files() {







	// 1. The "main.js" file



	//



	// Let's assume you want to completely overwrite the "main.js" file from the parent







	// First you will have to make sure the parent's file is not loaded



	// See the parent's function.php -> the listable_scripts_styles() function



	// for details like resources names







		wp_dequeue_script( 'listable-scripts' );











	// We will add the main.js from the child theme (located in assets/js/main.js)



	// with the same dependecies as the main.js in the parent



	// This is not required, but I assume you are not modifying that much :)







		wp_enqueue_script( 'listable-child-scripts',



			get_stylesheet_directory_uri() . '/assets/js/main.js',



			array( 'jquery' ),



			'1.0.0', true );















	// 2. The "style.css" file



	//



	// First, remove the parent style files



	// see the parent's function.php -> the hive_scripts_styles() function for details like resources names







		wp_dequeue_style( 'listable-style' );











	// Now you can add your own, modified version of the "style.css" file







		wp_enqueue_style( 'listable-child-style',



			get_stylesheet_directory_uri() . '/style.css'



		);



}







// Load the files from the function mentioned above:







	add_action( 'wp_enqueue_scripts', 'listable_child_overwrite_files', 11 );







// Notes:



// The 11 priority parameter is need so we do this after the function in the parent so there is something to dequeue



// The default priority of any action is 10







*/







//------------------------------------------------------------------------------------------



//-------------------------------------CUSTO------------------------------------------------



//------------------------------------------------------------------------------------------







/* Rediriger le client directement vers l'invoice si le devis est accepté */











function si_redirect_after_estimate_approval() {



	if ( 'estimate' !== si_get_doc_context() ) {



			return;



	}



	?>



		<script type="text/javascript">



			jQuery(document).on('status_updated', function(e) {



				window.location = window.location.pathname + '?redirect_after_status=1';



			});



		</script>



	<?php



}



add_action( 'si_footer', 'si_redirect_after_estimate_approval' );











function si_maybe_redirect_after_estimate_approval() {



	if ( 'estimate' !== si_get_doc_context() ) {



		return;



	}



	if ( ! isset( $_GET['redirect_after_status'] ) && $_GET['redirect_after_status'] ) {



		return;



	}



	$estimate = si_get_doc_object();



	$status = $estimate->get_status();



	// Check if approved



	if ( SI_Estimate::STATUS_APPROVED !== $status ) {



		return;



	}



	$invoice_id = $estimate->get_invoice_id();



	if ( ! $invoice_id ) {



		return;



	}



	wp_redirect( get_permalink( $invoice_id ) );



	exit();



}



add_action( 'pre_si_estimate_view', 'si_maybe_redirect_after_estimate_approval' );











/* Ne pas passer les devis au statut Temp */







add_filter( 'si_redirect_temp_status', '__return_false' );











/*-------------------------------------------------------------------*/



/* Récupérer l'email du listing owner en page listing                */



/*-------------------------------------------------------------------*/







add_filter( 'gform_field_value_email_listing_owner', 'add_listing_email', 10, 2);



function add_listing_email( $value ) {



global $post;







    $author_email = get_the_author_meta('email', $post->post_author);







    return $author_email;



}


/*-------------------------------------------------------------------*/

/* SUpprimer zone  Sign in dans form add-listing                     */

/*-------------------------------------------------------------------*/


/* add_filter( 'submit_job_form_show_signin', '__return_false' ); */



/*-------------------------------------------------------------------*/



/* Autocomplete sur plusieurs champs                                 */



/*-------------------------------------------------------------------*/



/*



function fwp_combine_sources( $params, $class ) {



    if ( 'what_to_search2' == $params['facet_name'] ) {



        $value = get_field( 'prestations' );



        $params['facet_value'] = sanitize_title( $value );



        $params['facet_display_value'] = $value;



        $class->insert( $params );



        $value = get_field( 'job_listing_category' );



        $params['facet_value'] = sanitize_title( $value );



        $params['facet_display_value'] = $value;



        $class->insert( $params );



        return false; // prevent default indexing



    }



    return $params;



}



add_filter( 'facetwp_index_row', 'fwp_combine_sources', 10, 2 );*/

