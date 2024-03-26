<?php
/**
 * Template name: Pricing Plans
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */

$successMsg = '';

get_header(); ?>

<?php 

	$page = get_page($post->ID);
	$current_page_id = $page->ID;

	$page_slider = get_post_meta($current_page_id, 'page_slider', true); 

?>


   <div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span8 first view-pricing-plans" >

				<?php if(!empty($successMsg)) { ?>

				<div class="full" style="margin-left: 0px;"><div class="box-notification-content"><?php echo $successMsg; ?></div></div>

				<?php } ?>

				<?php

					global $paged, $wp_query, $wp;

					$args = wp_parse_args($wp->matched_query);

					$temp = $wp_query;

					$wp_query= null;

					$wp_query = new WP_Query();

					$wp_query->query('post_type=price_plan&posts_per_page=-1');

					$current = -1;
					$current2 = 0;

					?>
					<div class="clearfix"></div>
						<h3 style="margin-top: 0px;"><?php _e('PRICING PLANS', 'agrg') ?></h3>
						<div class="h3-seprator"></div>
					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>
						

					<div class="span8 first">

						<div class="product-wrapper">
							<div class="span4">
								<?php $post_price = get_post_meta($post->ID, 'plan_price', true); ?>
								
										<?php 
											global $redux_demo; 
											$currency_code = $redux_demo['currency_code']; 

											if($currency_code == "USD") {
												$currencycode = "$";
											} elseif($currency_code == "AUD") {
												$currencycode = "$";
											} elseif($currency_code == "CAD") {
												$currencycode = "$";
											} elseif($currency_code == "CZK") {
												$currencycode = "Kč";
											} elseif($currency_code == "DKK") {
												$currencycode = "kr";
											} elseif($currency_code == "EUR") {
												$currencycode = "€";
											} elseif($currency_code == "HKD") {
												$currencycode = "$";
											} elseif($currency_code == "HUF") {
												$currencycode = "Ft";
											} elseif($currency_code == "JPY") {
												$currencycode = "¥";
											} elseif($currency_code == "NOK") {
												$currencycode = "kr";
											} elseif($currency_code == "NZD") {
												$currencycode = "$";
											} elseif($currency_code == "PLN") {
												$currencycode = "zł";
											} elseif($currency_code == "GBP") {
												$currencycode = "£";
											} elseif($currency_code == "SEK") {
												$currencycode = "kr";
											} elseif($currency_code == "SGD") {
												$currencycode = "$";
											} elseif($currency_code == "CHF") {
												$currencycode = "CHF";
											}
													

											
										?>
									
									<div class="product-details">
									<?php 
										$plan_featured_ads = get_post_meta($post->ID, 'featured_ads', true); 
										$plan_days = get_post_meta($post->ID, 'plan_time', true); 
									?>

										<div class="description">
											<ul>
												<li><i class="fa fa-check"></i><?php echo $currencycode. $post_price; ?></li>
												<li><i class="fa fa-check"></i><?php echo $plan_featured_ads ?> <?php _e('Featured ads', 'agrg') ?></li>
												<li><i class="fa fa-check"></i><?php echo $plan_days ?> <?php _e('Day', 'agrg') ?></li>
												<li><i class="fa fa-check"></i><?php _e('100% Secure!', 'agrg') ?> </li>
											</ul>
											
										</div>

										

									</div>
									
							</div>
							<div class="span4 plan-last">
							
								<div class="product-title">
									<h3><?php the_title(); ?>	</h3>							
								</div>
								<div class="product-description">
									<?php the_content(); ?>							
								</div>
								<?php									if ( !is_user_logged_in() ) { 										global $redux_demo; 										$login = $redux_demo['login'];									$redirect =	$login;									}else{									$redirect = get_template_directory_uri().'/paypal/form-handler.php?func=addrow';									}									?>				    			<form method="post" action="<?php echo $redirect; ?>">
											<input type="hidden" name="AMT" value="<?php echo $post_price; ?>" />
											<input type="hidden" name="PAYMENTREQUEST_0_DESC" value="<?php the_title(); ?>" />
											<?php global $redux_demo; $currency_code = $redux_demo['currency_code']; ?>
											<input type="hidden" name="CURRENCYCODE" value="<?php echo $currency_code; ?>">

											<?php $planID = uniqid(); ?>
											<input type="hidden" name="PAYMENTREQUEST_0_CUSTOM" value="<?php echo $planID; ?>">

											<input type="hidden" name="user_ID" value="<?php echo $user_ID; ?>">

											<input type="hidden" name="plan_name" value="<?php the_title(); ?>">

											<?php $plan_ads = get_post_meta($post->ID, 'featured_ads', true); ?>
											<input type="hidden" name="plan_ads" value="<?php echo $plan_ads; ?>">

											<input type="hidden" name="plan_price" value="<?php echo $post_price; ?>">

											<?php $plan_time = get_post_meta($post->ID, 'plan_time', true); ?>
											<input type="hidden" name="plan_time" value="<?php echo $plan_time; ?>">

											<?php $date = date('d/m/Y H:i:s'); ?>
											<input type="hidden" name="date" value="<?php echo $date; ?>">

											<input type="hidden" name="url" value="<?php echo get_template_directory_uri(); ?>">

											<?php global $redux_demo; $paypal_success = $redux_demo['paypal_success']; $paypal_fail = $redux_demo['paypal_fail']; ?>
													  
											<?php if ( isset($paypal_success) ) { ?>
												<input type="hidden" name="RETURN_URL" value="<?php echo $paypal_success; ?>" />
											<?php } ?>
													  
											<?php if ( isset($paypal_fail) ) { ?>
												<input type="hidden" name="CANCEL_URL" value="<?php echo $paypal_fail; ?>" />
											<?php } ?>
													  
											<input type="hidden" name="func" value="start" />

											<button class="btn form-submit" id="submit-plan" name="op" value="Purchase Now" type="submit"><?php printf( __( 'Purchase Now', 'agrg' )); ?></button>

										</form>
								
							</div>

							

						</div>

					</div>


					<?php endwhile; ?>
															
					<?php $wp_query = null; $wp_query = $temp;?>

	    		
	    	</div>

			<div class="span4" >
	
		    	<?php get_sidebar('pages'); ?>

			</div>
	    </div>

	    </div>

    </section>

<?php get_footer(); ?>