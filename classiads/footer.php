<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */
?>
<?php if ( is_page_template( 'template-homepage-v1.php' ) || is_category() ) { ?>
	<section id="partners">
		<div class="container clearfix">
			<?php 
				global $redux_demo;
				$partner1 = $redux_demo['partner1']['url'];
				$partner2 = $redux_demo['partner2']['url'];
				$partner3 = $redux_demo['partner3']['url'];
				$partner4 = $redux_demo['partner4']['url'];
				$partner5 = $redux_demo['partner5']['url'];
				$partner6 = $redux_demo['partner6']['url'];
				if(!empty($partner1)){
				?>
				<div class="partner-logo">
					<img alt="parnter-logo" src="<?php echo $partner1 ?>" />
				</div>
				<?php
				}
				if(!empty($partner2)){
				?>
				<div class="partner-logo">
					<img alt="parnter-logo" src="<?php echo $partner2 ?>" />
				</div>
				<?php
				} 
				if(!empty($partner3)){
				?>
				<div class="partner-logo">
					<img alt="parnter-logo" src="<?php echo $partner3 ?>" />
				</div>
				<?php
				} 
				if(!empty($partner4)){
				?>
				<div class="partner-logo">
					<img alt="parnter-logo" src="<?php echo $partner4 ?>" />
				</div>
				<?php
				} 
				if(!empty($partner5)){
				?>
				<div class="partner-logo">
					<img alt="parnter-logo" src="<?php echo $partner5 ?>" />
				</div>
				<?php
				} 
				if(!empty($partner6)){
				?>
				<div class="partner-logo">
					<img alt="parnter-logo" src="<?php echo $partner6 ?>" />
				</div>
				<?php
				} 
				?>

		</div>
	</section>
	<?php } ?>
	<footer>

		<div class="container">
					
			<div class="full">
				
				<?php get_sidebar( 'footer-one' ); ?>

				<?php get_sidebar( 'footer-two' ); ?>

				<?php get_sidebar( 'footer-three' ); ?>

				<?php get_sidebar( 'footer-four' ); ?>

			</div>

			

		</div>
					
	</footer>

	<section class="socket">

		<div class="container">

			<div class="site-info">
				<?php 

					global $redux_demo; 
					$footer_copyright = $redux_demo['footer_copyright'];

				?>

				<?php if(!empty($footer_copyright)) { 
						echo $footer_copyright;
					} else {
				?>
				 All copyrights reserved &#x00040; 2014 - Design &AMP; Development by <a href="#">DesignInvento</a>
				<?php } ?>
				
			</div><!-- .site-info -->
	
			<div class="bottom-social-icons">
				<?php 

					global $redux_demo; 

					$facebook_link = $redux_demo['facebook-link'];
					$twitter_link = $redux_demo['twitter-link'];
					$dribbble_link = $redux_demo['dribbble-link'];
					$flickr_link = $redux_demo['flickr-link'];
					$github_link = $redux_demo['github-link'];
					$pinterest_link = $redux_demo['pinterest-link'];
					$youtube_link = $redux_demo['youtube-link'];
					$google_plus_link = $redux_demo['google-plus-link'];
					$linkedin_link = $redux_demo['linkedin-link'];
					$tumblr_link = $redux_demo['tumblr-link'];
					$vimeo_link = $redux_demo['vimeo-link'];

				?>

				<?php if(!empty($facebook_link)) { ?>

					<a class="target-blank" href="<?php echo $facebook_link; ?>"><i class="fa fa-facebook"></i></a>

				<?php } ?>

				<?php if(!empty($twitter_link)) { ?>

					<a class="target-blank" href="<?php echo $twitter_link; ?>"><i class="fa fa-twitter"></i></a>

				<?php } ?>

				<?php if(!empty($dribbble_link)) { ?>

					<a class="target-blank" href="<?php echo $dribbble_link; ?>"><i class="fa fa-dribbble"></i></a>

				<?php } ?>

				<?php if(!empty($flickr_link)) { ?>

					<a class="target-blank" href="<?php echo $flickr_link; ?>"><i class="fa fa-flickr"></i></a>

				<?php } ?>

				<?php if(!empty($github_link)) { ?>

					<a class="target-blank" href="<?php echo $github_link; ?>"><i class="fa fa-github"></i></a>

				<?php } ?>

				<?php if(!empty($pinterest_link)) { ?>

					<a class="target-blank" href="<?php echo $pinterest_link; ?>"><i class="fa fa-pinterest"></i></a>

				<?php } ?>

				<?php if(!empty($youtube_link)) { ?>

					<a class="target-blank" href="<?php echo $youtube_link; ?>"><i class="fa fa-youtube"></i></a>

				<?php } ?>

				<?php if(!empty($google_plus_link)) { ?>

					<a class="target-blank" href="<?php echo $google_plus_link; ?>"><i class="fa fa-google-plus"></i></a>

				<?php } ?>

				<?php if(!empty($linkedin_link)) { ?>

					<a class="target-blank" href="<?php echo $linkedin_link; ?>"><i class="fa fa-linkedin"></i></a>

				<?php } ?>

				<?php if(!empty($tumblr_link)) { ?>

					<a class="target-blank" href="<?php echo $tumblr_link; ?>"><i class="fa fa-tumblr"></i></a>

				<?php } ?>

				<?php if(!empty($vimeo_link)) { ?>

					<a class="target-blank" href="<?php echo $vimeo_link; ?>"><i class="fa fa-vimeo-square"></i></a>

				<?php } ?>
			</div>

			<div class="backtop">
				<a href="#backtop"><i class="fa fa-angle-double-up"></i></a>
			</div>

		</div>

	</section>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	<?php wp_footer(); ?>
</body>
</html>