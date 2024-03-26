<?php
/**
 * Template name: Edit Profile
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */

if ( !is_user_logged_in() ) { 

	global $redux_demo; 
	$login = $redux_demo['login'];
	wp_redirect( $login ); exit;

}

global $user_ID, $user_identity, $user_level;

if ($user_ID) {

	if($_POST) 

	{

		$message = "Your profile updated successfully.";

		$first = $wpdb->escape($_POST['first_name']);

		$last = $wpdb->escape($_POST['last_name']);

		$email = $wpdb->escape($_POST['email']);

		$user_url = $wpdb->escape($_POST['website']);

		$user_phone = $wpdb->escape($_POST['phone']);

		$user_address = $wpdb->escape($_POST['address']);

		$description = $wpdb->escape($_POST['desc']);

		$password = $wpdb->escape($_POST['pwd']);

		$confirm_password = $wpdb->escape($_POST['confirm']);
$your_image_url = $wpdb->escape($_POST['your_author_image_url']);
		

		update_user_meta( $user_ID, 'first_name', $first );

		update_user_meta( $user_ID, 'last_name', $last );

		update_user_meta( $user_ID, 'phone', $user_phone );

		update_user_meta( $user_ID, 'address', $user_address );

		update_user_meta( $user_ID, 'description', $description );
		update_user_meta( $user_ID, 'classiads_author_avatar_url', $your_image_url );

		wp_update_user( array ('ID' => $user_ID, 'user_url' => $user_url) );

		

		if(isset($email)) {

			if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)){ 

				wp_update_user( array ('ID' => $user_ID, 'user_email' => $email) ) ;

			}

			else { $message = "<div id='error'>Please enter a valid email id.</div>"; }

		}

		if($password) {

			if (strlen($password) < 5 || strlen($password) > 15) {

				$message = "<div id='error'>Password must be 5 to 15 characters in length.</div>";

				}

			//elseif( $password == $confirm_password ) {

			elseif(isset($password) && $password != $confirm_password) {

				$message = "<div class='error'>Password Mismatch</div>";

			} elseif ( isset($password) && !empty($password) ) {

				$update = wp_set_password( $password, $user_ID );

				$message = "<div id='success'>Your profile updated successfully.</div>";

			}

		}

				

	}

}

get_header(); ?>
	
	<?php while ( have_posts() ) : the_post(); ?>

	<div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span8 first">
			
				<div class="account-overview clearfix">
					<h3 style="margin-top: 7px;"><?php _e('ACCOUNT OVERVIEW', 'agrg') ?></h3>
					<div class="h3-seprator"></div>
					<div class="span3 first author-avatar-edit-post">
						<?php $profile = $redux_demo['profile']; ?>
								<div class="my-account-author-image">

									<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

									<?php 

										$author_avatar_url = get_user_meta($user_ID, "classiads_author_avatar_url", true); 

										if(!empty($author_avatar_url)) {

											$params = array( 'width' => 130, 'height' => 130, 'crop' => true );

											echo "<img class='author-avatar' src='" . bfi_thumb( "$author_avatar_url", $params ) . "' alt='' />";

										} else { 

									?>

										<?php $avatar_url = wpcook_get_avatar_url ( get_the_author_meta('user_email', $user_ID), $size = '130' ); ?>
										<img class="author-avatar" src="<?php echo $avatar_url; ?>" alt="" />

									<?php } ?>
								</div>
								
								
								<span class="delete-image-btn"><a href="#" class="delete-author-image"><i class="fa fa-times"></i></a></span>
						<span class="author-profile-ad-details"><a href="#" class="button-ag large green upload-author-image"><span class="button-inner"><?php _e('Add New Image', 'agrg') ?></span></a></span>
					</div>
					<div class="span4">					
							<span class="ad-detail-info"><?php _e( 'Regular Ads', 'agrg' ); ?>
							<span class="ad-detail"><?php echo $user_post_count = count_user_posts( $user_ID ); ?></span>
						</span>

						<?php 

							global $redux_demo; 

							$featured_ads_option = $redux_demo['featured-options-on'];

						?>

						<?php if($featured_ads_option == 1) { ?>

						<?php

							global $paged, $wp_query, $wp;

							$args = wp_parse_args($wp->matched_query);

							$temp = $wp_query;

							$wp_query= null;

							$wp_query = new WP_Query();

							$wp_query->query('post_type=post&posts_per_page=-1&author='.$user_ID);

							$FeaturedAdsCount = 0;

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 

							$featured_post = "0";

							$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
							$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
							$todayDate = strtotime(date('d/m/Y H:i:s'));
							$expireDate = strtotime($post_price_plan_expiration_date);  

							if(!empty($post_price_plan_activation_date)) {

								if(($todayDate < $expireDate) or empty($post_price_plan_expiration_date)) {
									$featured_post = "1";
								}

						} ?>

							<?php if($featured_post == "1") { $FeaturedAdsCount++; } ?>
							<?php endwhile; ?>
							<?php $wp_query = null; $wp_query = $temp;?>

							<span class="ad-detail-info"><?php _e( 'Featured Ads', 'agrg' ); ?>
								<span class="ad-detail"><?php echo $FeaturedAdsCount ?></span>
							</span>
						 <?php
						// set the meta_key to the appropriate custom field meta key

								global $wpdb;

										$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE user_id = " . $current_user->ID." ORDER BY main_id DESC" );

											if ( $result ) {

											    $featuredADS = 0;

											    foreach ( $result as $info ) { 
								            		if($info->status != "in progress" && $info->status != "pending" && $info->status != "faild") {
																	
																	
															$featuredADS++;

															if(empty($info->ads)) {
																$availableADS = "Unlimited";
																$infoAds = "Unlimited";
															} else {
																$availableADS = $info->ads - $info->used;
																$infoAds = $info->ads;
															} 

															

																?>

															<span class="ad-detail-info"><?php _e( 'Featured Ads left', 'agrg' ); ?>
																<span class="ad-detail"><?php  echo $availableADS; ?></span>
															</span>

														<?php 
													}else{
														if($featuredADS == 0){
														?>
														<span class="ad-detail-info"><?php _e( 'Featured Ads left', 'agrg' ); ?>
														<span class="ad-detail">0</span>
														</span>
														<?php
														} 
														$featuredADS++;
													}
												}
											}else{
										?>
										<span class="ad-detail-info"><?php _e( 'Featured Ads left', 'agrg' ); ?>
										<span class="ad-detail">0</span>
										</span>
										<?php } ?>

					
						
						<?php } ?>
					</div>
					
				</div>

				<div id="edit-profile" class="ad-detail-content">
					<h3><?php _e( 'РЕДАКТИРОВАТЬ ПРОФИЛЬ', 'agrg' ); ?></h3>
							<div class="h3-seprator"></div>
					<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

						<?php if ($user_ID) {

							$user_info = get_userdata($user_ID);

						?>

							<?php if($_POST) { 

								echo "<span class='error' style='color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;'>".$message."</span><div class='clearfix'></div>";

							} ?>
								<input class="criteria-image-url" id="your_image_url" type="text" size="36" name="your_author_image_url" style="display: none;" value="your_author_image_url" />
						        <input class="criteria-image-id" id="your_image_id" type="text" size="36" name="your_author_image_id" style="display: none;" value="your_author_image_id" />
							
								<input type="text" id="contactName" placeholder="First Name" name="first_name" class="text input-textarea half" value="<?php echo $user_info->first_name; ?>" />

							

				
								<input type="text" id="contactName" placeholder="Last Name" name="last_name" class="text last input-textarea half" value="<?php echo $user_info->last_name; ?>"/> 

							

							
								<input type="text" id="email" placeholder="Email" name="email" class="text input-textarea half" value="<?php echo $user_info->user_email; ?>" />

							

								<input type="text" id="website" placeholder="WebSite" name="website" class="text last input-textarea half" value="<?php echo $user_info->user_url; ?>"/>

							

								<input type="text" id="phone" placeholder="Phone" name="phone" class="text input-textarea half" value="<?php echo $user_info->phone; ?>" /> 

							

								<input type="text" id="address" placeholder="Address" name="address" class="text last input-textarea half" value="<?php echo $user_info->address; ?>" /> 

							


								<textarea name="desc" id="video" placeholder="Author Bio" class="text" rows="10"><?php echo $user_info->description; ?></textarea>



								<input type="password" placeholder="Password" id="password" name="pwd" class="text input-textarea half" maxlength="15" />

								<input type="password" placeholder="Confirm Password" id="password" name="confirm" class="text last input-textarea half" maxlength="15" />

								<p class="help-block"><?php _e('Если вы хотите изменить пароль на новый. В противном случае оставьте поле пустым.', 'agrg') ?></p>


							<div class="publish-ad-button">
								<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
								<input type="hidden" name="submitted" id="submitted" value="true" />
								<div class="clearfix"></div>
								<div class="btn-container">
									<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Сохранить', 'agrg') ?></button>
								</div>
							</div>

						<?php } else { 

							$redirect_to = home_url()."/login";//change this to your custom login url

							wp_safe_redirect($redirect_to);	

						} ?>

					</form>

	    		</div>

	    	</div>

	    	<div class="span4">


		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>
	<script>
									var image_custom_uploader;
									var $thisItem = '';

									jQuery(document).on('click','.upload-author-image', function(e) {
										e.preventDefault();

										$thisItem = jQuery(this);
										$form = jQuery('#primaryPostForm');

										//If the uploader object has already been created, reopen the dialog
										if (image_custom_uploader) {
										    image_custom_uploader.open();
										    return;
										}

										//Extend the wp.media object
										image_custom_uploader = wp.media.frames.file_frame = wp.media({
										    title: 'Choose Image',
										    button: {
										        text: 'Choose Image'
										    },
										    multiple: false
										});

										//When a file is selected, grab the URL and set it as the text field's value
										image_custom_uploader.on('select', function() {
										    attachment = image_custom_uploader.state().get('selection').first().toJSON();
										    var url = '';
										    url = attachment['url'];
										    var attachId = '';
										    attachId = attachment['id'];
										    $thisItem.parent().parent().find( "img.author-avatar" ).attr({
										        src: url
										    });
										  $form.parent().parent().find( ".criteria-image-url" ).attr({
										        value: url
										    });
										    $form.parent().parent().find( ".criteria-image-id" ).attr({
										        value: attachId
										    });
										});

										//Open the uploader dialog
										image_custom_uploader.open();
									});

									jQuery(document).on('click','.delete-author-image', function(e) {
										jQuery(this).parent().parent().find( ".criteria-image-url" ).attr({
										   value: ''
										});
										jQuery(this).parent().parent().find( "img.author-avatar" ).attr({
										     src: ''
										});
									});
								</script>


    <?php endwhile; ?>
	<?php 

		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];

	?>

	<?php if($featured_ads_option == 1) { ?>

    <section id="featured-abs">
        
        <div class="container" style="width:100%">
            
            <div id="tabs" class="full">
			    	
                <?php $cat_id = get_cat_ID(single_cat_title('', false)); ?>
			    

                <div class="pane">
                 
                  	<div id="projects-carousel">

			    		<?php

							global $paged, $wp_query, $wp;

							$args = wp_parse_args($wp->matched_query);

							$temp = $wp_query;

							$wp_query= null;

							$wp_query = new WP_Query();

							$wp_query->query('post_type=post&posts_per_page=-1');

							$current = -1;

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post();

							$featured_post = "0";

							$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
							$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
							$post_price_plan_expiration_date_noarmal = get_post_meta($post->ID, 'post_price_plan_expiration_date_normal', true);
							$todayDate = strtotime(date('m/d/Y h:i:s'));
							$expireDate = $post_price_plan_expiration_date;

							if(!empty($post_price_plan_activation_date)) {

								if(($todayDate < $expireDate) or $post_price_plan_expiration_date == 0) {
									$featured_post = "1";
								}

						} ?>

						<?php if($featured_post == "1") { 

							$current++;

						?>

						<div class="ad-box span3">
							<?php
								if ( has_post_thumbnail()) {
								   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
								   echo '<a class="ad-image" href="' .get_permalink($post->ID). '" title="' . the_title_attribute('echo=0') . '" >';
								   echo get_the_post_thumbnail($post->ID, 'premium-post-image'); 
								   echo '</a>';
								 }
							?>
			    			

			    			<div class="ad-hover-content">
			    				<div class="ad-category">
			    					
			    					<?php
 
						        		$category = get_the_category();

						        		if ($category[0]->category_parent == 0) {

											$tag = $category[0]->cat_ID;

											$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
											if (isset($tag_extra_fields[$tag])) {
											    $category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
											    $category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
											}

										} else {

											$tag = $category[0]->category_parent;

											$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
											if (isset($tag_extra_fields[$tag])) {
											    $category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
											    $category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
											}

										}

										if(!empty($category_icon_code)) {

									?>

					        		<div class="category-icon-box" ><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

					        		<?php } 

					        		$category_icon_code = "";

					        		?>

			    				</div>
								
								
								<div class="post-title">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle; echo $theTitle; ?></a>
								</div>
								
							</div>	
								<?php
								$post_price = get_post_meta($post->ID, 'post_price', true);
								if(!empty($post_price)){								
								?>
								<div class="add-price"><span><?php echo $post_price; ?></span></div> 
								<?php } ?>
								
			    				

			    			

						</div>

			    		<?php } ?>

			    		<?php endwhile; ?>	
												
						<?php wp_reset_query(); ?>

			    	</div>

			    	<?php wp_enqueue_script( 'jquery-carousel', get_template_directory_uri().'/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'),'',true); ?>
										
					<script>

						jQuery(document).ready(function () {

							jQuery('#projects-carousel').carouFredSel({
								auto: true,
								prev: '#carousel-prev',
								next: '#carousel-next',
								pagination: "#carousel-pagination",
								mousewheel: true,
								scroll: 2,
								swipe: {
									onMouse: true,
									onTouch: true
								}
							});

						});
											
					</script>
					<!-- end scripts -->

			    </div>

			    

			</div>
        
        </div>

    </section>

    <?php } ?>

<?php get_footer(); ?>