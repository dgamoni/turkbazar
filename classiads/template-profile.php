<?php
/**
 * Template name: Profile Page
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


global $redux_demo; 
$pagepermalink = get_permalink($post->ID);
if(isset($_GET['delete_id'])){
	$deleteUrl = $_GET['delete_id'];
	wp_delete_post($deleteUrl);
}
global $current_user, $user_id;
get_currentuserinfo();
$user_id = $current_user->ID; // You can set $user_id to any users, but this gets the current users ID.

get_header(); 


?>



	<?php 

		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];

	?>



    <section id="ads-profile">
	<div class="ad-title">
	
        		<h2><?php echo $user_identity; ?> </h2> 	
	</div>
        
        <div class="container">
			<div class="span8 first">
        	 <div class="full" >

        		<?php 

			    	global $redux_demo; 
					$edit = $redux_demo['edit'];

				?>       	

        		<div class="span8 first">

				<div class="account-overview clearfix">
					<h3 style="margin-top: 7px;"><?php _e('ACCOUNT OVERVIEW', 'agrg') ?></h3>
					<div class="h3-seprator"></div>
					<div class="span3 first author-avatar-edit-post">
						<?php $profile = $redux_demo['profile']; ?>
						<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>
			    			<?php 

								$author_avatar_url = get_user_meta($user_ID, "classiads_author_avatar_url", true); 

								if(!empty($author_avatar_url)) {

									$params = array( 'width' => 120, 'height' => 120, 'crop' => true );

									echo "<img class='author-avatar' src='" . bfi_thumb( "$author_avatar_url", $params ) . "' alt='' />";

								} else { 

							?>

								<?php $avatar_url = wpcook_get_avatar_url ( get_the_author_meta('user_email', $user_ID), $size = '150' ); ?>
								<img class="author-avatar" src="<?php echo $avatar_url; ?>" alt="" />

							<?php } ?>
						<span class="author-profile-ad-details"><a href="<?php echo $edit; ?>" class="button-ag large green"><span class="button-inner"><?php printf( __( 'Edit Profile', 'agrg' )); ?></span></a></span>
					</div>
					<div class="span4">					
							<span class="ad-detail-info"><?php _e( 'Регулярные объявления', 'agrg' ); ?>
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

		        	<div class="span8">
						<h3><?php _e( 'PROFILE', 'agrg' ); ?></h3>
							<div class="h3-seprator"></div>
		        		
						<div class="full profile-content">							
							<div class="span4">
								<span class="author-details"><i class="fa fa-phone"></i><?php _e( 'Телефон:', 'agrg' ); ?> <?php the_author_meta('phone', $user_id); ?></span>

								<span class="author-details"><i class="fa fa-envelope"></i><?php _e( 'Почтовый адрес:', 'agrg' ); ?> <a href="mailto:<?php echo get_the_author_meta('user_email', $$user_id); ?>"><?php echo get_the_author_meta('user_email', $user_id); ?></a></span>

								<span class="author-details"><i class="fa fa-globe"></i><?php _e( 'Вебсайт:', 'agrg' ); ?> <a href="<?php the_author_meta('user_url', $user_id); ?>"><?php the_author_meta('user_url', $user_id); ?></a></span>

								<span class="author-details"><i class="fa fa-map-marker"></i><?php _e( 'Адрес:', 'agrg' ); ?> <?php the_author_meta('address', $user_id); ?></span>
							
							</div>
							<div class="span4 description">
								<h3><?php _e( 'ОПИСАНИЕ', 'agrg' ); ?></h3>

								<div class="author-description"><?php $user_id = $current_user->ID; $author_desc = get_the_author_meta('description', $user_id); echo $author_desc; ?></div>

							</div>
						
						</div>

					</div>

					
				</div>

				

	    	</div> 

	    	

	    	
				<div class="full">

				<h3><?php _e( 'МОИ ОБЪЯВЛЕНИЯ', 'agrg' ); ?></h3>
				<div class="h3-seprator"></div>
			<div class="full" style="margin-left: 0px; padding-top: 20px;">
				<?php
				global $wp_query, $wp;

				$wp_query = new WP_Query();
						$kulPost = array(
								'post_type'  => 'post',
								'author' => $user_id,
								'posts_per_page' => 100,	
								);

						
						$wp_query = new WP_Query($kulPost);

						$current = -1;
						$current2 = 0;
						

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>

						<div class="my-ad-box clearfix">
							<div class="my-ad-image">
							<?php
									if ( has_post_thumbnail()) {
									   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
									   echo '<a target="_blank" class="ad-image" href="' .get_permalink($post->ID). '" title="' . the_title_attribute('echo=0') . '" >';
									   echo get_the_post_thumbnail($post->ID, 'thumbnail'); 
									   echo '</a>';
									 }
								?>
							</div>
							<div class="my-ad-details">
								<a target="_blank" class="my-ad-title" href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 22) ? substr($theTitle,0,22).'...' : $theTitle; echo $theTitle; ?></a>
								<span><i class="fa fa-check-square-o"></i> <?php echo get_post_status( $post->ID ); ?></span>
								<span><i class="fa fa-eye "></i> <?php echo wpb_get_post_views($post->ID); ?></span>
								<?php 
									global $redux_demo; 
									$edit_post_page_id = $redux_demo['edit_post'];
									$postID = $post->ID;

									global $wp_rewrite;
									if ($wp_rewrite->permalink_structure == ''){
									//we are using ?page_id
										$edit_post = $edit_post_page_id."&post=".$post->ID;
										$del_post = $pagepermalink."&delete_id=".$post->ID;
									}else{
									//we are using permalinks
										$edit_post = $edit_post_page_id."?post=".$post->ID;
										$del_post = $pagepermalink."?delete_id=".$post->ID;
										}
								?>
								
								<div class="delete-popup" id="examplePopup<?php echo $post->ID; ?>" style="display:none">
									<h4><?php _e( 'Вы уверены что хотите удалить?', 'agrg' ); ?></h4>
									<a class="button-ag large green" href="<?php echo $del_post; ?>">
									<span class="button-inner"><?php _e( 'Подтвердить', 'agrg' ); ?></span>
									</a>
								</div>
								<?php
								if(get_post_status( $post->ID ) !== 'private'){ ?>
								<span class="my-ad-a"><i class="fa fa-pencil"></i> <a target="_blank" href="<?php echo $edit_post; ?>"><?php _e( 'Редактировать', 'agrg' ); ?></a></span>
								<?php } ?>
								<span class="my-ad-a"><i class="fa fa-trash-o"></i> <a  class="thickbox" href="#TB_inline?height=150&amp;width=400&amp;inlineId=examplePopup<?php echo $post->ID; ?>"><?php _e( 'Удалить', 'agrg' ); ?></a></span>
								<span><i class="fa fa-clock-o"></i> <?php echo the_date('d M Y'); ?></span>
							</div>
						</div>
						
								
				

						<?php endwhile;	?>
				
																	
				<?php wp_reset_query(); ?>
					
				</div>

			</div> 





			</div>
       
			<div class="span4">

				
				<?php get_sidebar('pages'); ?>
			</div>
			
			
		</div>

    </section>


<?php get_footer(); ?>