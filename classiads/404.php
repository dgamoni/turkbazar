<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */

get_header(); 

global $redux_demo, $maximRange; 
	$max_range = $redux_demo['max_range'];
	if(!empty($max_range)) {
		$maximRange = $max_range;
	} else {
		$maximRange = 1000;
	}

?>

		 <div class="ad-title">

        	<h2><?php _e( '404 ERROR', 'agrg' ); ?></h2>

        </div>



    <section id="ads-homepage">
        
        <div class="container" style="text-align:center">

        	<img alt="404 image" src="<?php echo get_template_directory_uri() . '/images/fof.png'; ?>" />
			<br />
			<br />
			<br />
			<div class="btn-container">
				<button class="btn form-submit" id="edit-submit"><a href="#"><?php _e( 'Go back to home', 'agrg' ); ?></a></button>
			</div>
			
        </div>

    </section>

<?php get_footer(); ?>