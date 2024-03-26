<?php
/**
 * The template for forum.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */

get_header(); ?>

    <div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span8 first" style="padding: 40px 0;">

				<div class="ad-detail-content">

	    			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
					<?php the_content(); ?>
															
					<?php endwhile; endif; ?>

	    		</div>

	    		
	    	</div>

	    	<div class="span4">

		    	<?php get_sidebar('forum'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>