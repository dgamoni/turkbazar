<?php
/**
 * Template name: New Ad Page
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
								
} else { 

}

$postTitleError = '';
$post_priceError = '';
$catError = '';
$featPlanMesage = '';
$postContent = '';

if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	if(trim($_POST['postTitle']) === '') {
		$postTitleError = 'Please enter a title.';
		$hasError = true;
	} else {
		$postTitle = trim($_POST['postTitle']);
	} 

	if(trim($_POST['cat']) === '-1') {
		$catError = 'Please select a category.';
		$hasError = true;
	} 



	if($hasError != true && !empty($_POST['edit-feature-plan']) || isset($_POST['regular-ads-enable'])) {
		if(is_super_admin() ){
			$postStatus = 'publish';
		}elseif(!is_super_admin()){
			
			if($redux_demo['post-options-on'] == 1){
				$postStatus = 'private';
			}else{
				$postStatus = 'publish';
			}
		}
	
		$post_information = array(
			'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
			'post_content' => esc_attr(strip_tags($_POST['postContent'])),
			'post-type' => 'post',
			'post_category' => array($_POST['cat']),
	        'tags_input'    => explode(',', $_POST['post_tags']),
	        'comment_status' => 'open',
	        'ping_status' => 'open',
			'post_status' => $postStatus
		);
		
		
		$post_id = wp_insert_post($post_information);

		$post_price_status = trim($_POST['post_price']);

		global $redux_demo; 
		$free_listing_tag = $redux_demo['free_price_text'];

		if(empty($post_price_status)) {
			$post_price_content = $free_listing_tag;
		} else {
			$post_price_content = $post_price_status;
		}
		$catID = $_POST['cat'].'custom_field';
		$custom_fields = $_POST[$catID];
		update_post_meta($post_id, 'post_category_type', esc_attr( $_POST['post_category_type'] ) );
		update_post_meta($post_id, 'custom_field', $custom_fields);
		update_post_meta($post_id, 'post_price', $post_price_content, $allowed);
		update_post_meta($post_id, 'post_location', wp_kses($_POST['post_location'], $allowed));
		update_post_meta($post_id, 'post_latitude', wp_kses($_POST['latitude'], $allowed));
		update_post_meta($post_id, 'post_longitude', wp_kses($_POST['longitude'], $allowed));
		update_post_meta($post_id, 'post_address', wp_kses($_POST['address'], $allowed));
		update_post_meta($post_id, 'post_video', $_POST['video'], $allowed);

		$permalink = get_permalink( $post_id );


		if(trim($_POST['edit-feature-plan']) != '') {

			$featurePlanID = trim($_POST['edit-feature-plan']);

			global $wpdb;

			global $current_user;
		    get_currentuserinfo();

		    $userID = $current_user->ID;

			$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE main_id = $featurePlanID" );

			if ( $result ) {

				$featuredADS = 0;

				foreach ( $result as $info ) { 
					if($info->status != "in progress" && $info->status != "pending") {
																
						$featuredADS++;

						if(empty($info->ads)) {
							$availableADS = "Unlimited";
							$infoAds = "Unlimited";
						} else {
							$availableADS = $info->ads - $info->used;
							$infoAds = $info->ads;
						} 

						if(empty($info->days)) {
							$infoDays = "Unlimited";
						} else {
							$infoDays = $info->days;
						} 

						if($info->used != "Unlimited" && $infoAds != "Ulimited" && $info->used == $infoAds) {

							$featPlanMesage = 'Please select another plan.';

						} else {

							global $wpdb;

							$newUsed = $info->used +1;

							$update_data = array('used' => $newUsed);
						    $where = array('main_id' => $featurePlanID);
						    $update_format = array('%s');
						    $wpdb->update('wpcads_paypal', $update_data, $where, $update_format);
						    update_post_meta($post_id, 'post_price_plan_id', $featurePlanID );

							$dateActivation = date('m/d/Y H:i:s');
							update_post_meta($post_id, 'post_price_plan_activation_date', $dateActivation );
							
							$daysToExpire = $infoDays;
							$dateExpiration_Normal = date("m/d/Y H:i:s", strtotime("+ ".$daysToExpire." days"));
							update_post_meta($post_id, 'post_price_plan_expiration_date_normal', $dateExpiration_Normal );

							$dateExpiration = strtotime(date("m/d/Y H:i:s", strtotime("+ ".$daysToExpire." days")));
							update_post_meta($post_id, 'post_price_plan_expiration_date', $dateExpiration );

							update_post_meta($post_id, 'featured_post', "1" );

					    }
					}
				}
			}

		}


		if ( $_FILES ) {
			$files = $_FILES['upload_attachment'];
			foreach ($files['name'] as $key => $value) {
				if ($files['name'][$key]) {
					$file = array(
						'name'     => $files['name'][$key],
						'type'     => $files['type'][$key],
						'tmp_name' => $files['tmp_name'][$key],
						'error'    => $files['error'][$key],
						'size'     => $files['size'][$key]
					);
		 
					$_FILES = array("upload_attachment" => $file);
		 
					foreach ($_FILES as $file => $array) {
						$newupload = wpcads_insert_attachment($file,$post_id);
					}
				}
			}
		}
		
		wp_redirect( $permalink ); exit;

	}
		$featured_plans = $redux_demo['featured_plans'];
			if(empty($_POST['edit-feature-plan']) && !isset($_POST['regular-ads-enable'])) {
				if(!empty($featured_plans)) {
					wp_redirect( $featured_plans ); exit;
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

	    	<div class="span8 first ad-post-main">
				<div class="account-overview clearfix">
					<h3 style="margin-top: 7px;"><?php _e('ПРОСМОТР АККАУНТА', 'agrg') ?></h3>
					<div class="h3-seprator"></div>
					<div class="span3 first author-avatar-edit-post">
						<?php $profile = $redux_demo['profile']; ?>
						<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>
			    			<?php 

								$author_avatar_url = get_user_meta($user_ID, "classiads_author_avatar_url", true); 

								if(!empty($author_avatar_url)) {

									$params = array( 'width' => 120, 'height' => 120, 'crop' => true );

									echo "<img src='" . bfi_thumb( "$author_avatar_url", $params ) . "' alt='' />";

								} else { 

							?>

								<?php $avatar_url = wpcook_get_avatar_url ( get_the_author_meta('user_email', $user_ID), $size = '150' ); ?>
								<img src="<?php echo $avatar_url; ?>" alt="" />

							<?php } ?>
						<span class="author-profile-ad-details"><a href="<?php echo $profile; ?>" class="button-ag large green"><span class="button-inner"><?php echo get_the_author_meta('display_name', $user_ID ); ?></span></a></span>
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

							<span class="ad-detail-info"><?php _e( 'Популярные объявления', 'agrg' ); ?>
								<span class="ad-detail"><?php echo $FeaturedAdsCount ?></span>
							</span>
						 <?php
						// set the meta_key to the appropriate custom field meta key

							global $wpdb;

										$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE user_id = " . $current_user->ID." ORDER BY main_id DESC" );

											if ( $result ) {

											    $featuredADS = 0;

											    foreach ( $result as $info ) { 
								            		if($info->status != "in progress" && $info->status != "pending" && $info->status != "failed") {
																	
																	
															$featuredADS++;

															if(empty($info->ads)) {
																$availableADS = "Unlimited";
																$infoAds = "Unlimited";
															} else {
																$availableADS = $info->ads - $info->used;
																$infoAds = $info->ads;
															} 

															

																?>

															<span class="ad-detail-info"><?php _e( 'Осталось популярных объявлений', 'agrg' ); ?>
																<span class="ad-detail"><?php  echo $availableADS; ?></span>
															</span>

														<?php 
													}else{
													if($featuredADS == 0){
														?>
														<span class="ad-detail-info"><?php _e( 'Осталось популярных объявлений', 'agrg' ); ?>
														<span class="ad-detail">0</span>
														</span>
														<?php
														} 
														$featuredADS++;
													} 
												}
											}else{
										?>
										<span class="ad-detail-info"><?php _e( 'Осталось популярных объявлений', 'agrg' ); ?>
										<span class="ad-detail">0</span>
										</span>
										<?php } ?>

										
						
						<?php } ?>
					</div>
					
				</div>
				
				<div id="upload-ad" class="ad-detail-content">
					<h3 style="margin-top: 7px;"><?php _e('НОВОЕ ОБЪЯВЛЕНИЕ', 'agrg') ?></h3>
						<div class="h3-seprator"></div>
					<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

						<?php if($postTitleError != '') { ?>
							<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $postTitleError; ?></span>
							<div class="clearfix"></div>
						<?php } ?>


						<?php if($catError != '') { ?>
							<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $catError; ?></span>
							<div class="clearfix"></div>
						<?php } ?>

						
							
							<input type="text" id="postTitle" name="postTitle" placeholder="<?php _e('Заголовок объявления', 'agrg') ?>" size="60" maxlength="255" class="form-text required input-textarea half">

								<?php wp_dropdown_categories( 'show_option_none=Category&hide_empty=0&hierarchical=1&id=catID' ); ?>

							

						<?php
				        	$args = array(
				        	  'hide_empty' => false,
							  'orderby' => 'count',
							  'order' => 'ASC'
							);

							$inum = 0;

							$categories = get_categories($args);
							  	foreach($categories as $category) {;

							  	$inum++;

				          		$user_name = $category->name;
				          		$user_id = $category->term_id; 


				          		$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
								$wpcrown_category_custom_field_option = $tag_extra_fields[$user_id]['category_custom_fields'];

								if(empty($wpcrown_category_custom_field_option)) {

									$catobject = get_category($user_id,false);
									$parentcat = $catobject->category_parent;

									$wpcrown_category_custom_field_option = $tag_extra_fields[$parentcat]['category_custom_fields'];
								}
				          	?>

				          	<div id="cat-<?php echo $user_id; ?>" class="wrap-content" style="display: none;">

				             	<?php 
				                	for ($i = 0; $i < (count($wpcrown_category_custom_field_option)); $i++) {
				              	?>

				               

									
									<input type="hidden" class="custom_field" id="custom_field[<?php echo $i; ?>][0]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][0]" value="<?php echo $wpcrown_category_custom_field_option[$i][0] ?>" size="12">

									<input type="text" class="custom_field custom_field_visible input-textarea" id="custom_field[<?php echo $i; ?>][1]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][1]" onfocus="if(this.value=='<?php if (!empty($wpcrown_category_custom_field_option[$i][0])) echo $wpcrown_category_custom_field_option[$i][0]; ?>')this.value='';" onblur="if(this.value=='')this.value='<?php if (!empty($wpcrown_category_custom_field_option[$i][0])) echo $wpcrown_category_custom_field_option[$i][0]; ?>';" value="<?php if (!empty($wpcrown_category_custom_field_option[$i][0])) echo $wpcrown_category_custom_field_option[$i][0]; ?>" size="12">

								
				              
				              	<?php 
				                	}
				              	?>


				            </div>

				      	<?php } ?>

						
						<div class="clearfix"></div>
							
							<input type="text" id="post_price" name="post_price" placeholder="<?php _e('Цена', 'agrg') ?>"  size="12" class="form-text required input-textarea half">
							<?php
								$locations= $redux_demo['locations'];
								if(!empty($locations)){
								?>
								<select name="post_location" id="post_location" >
								<option value="Not Provided"><?php _e('Выбрать местоположение', 'agrg'); ?></option>
								<?php
									$comma_separated = explode(",", $locations);
									foreach($comma_separated as $comma){
										echo '<option>'.$comma.'</option>';
									}
								echo '</select>';
								}else{
							?>
							<input type="text" id="post_location" name="post_location" placeholder="<?php _e('Местоположение', 'agrg') ?>"  size="12" maxlength="110" class="form-text last required input-textarea half">
							<?php } ?>
						<?php 
								
							$settings = array(
								'wpautop' => true,
								'postContent' => 'content',
								'media_buttons' => false,
								'tinymce' => array(
									'theme_advanced_buttons1' => 'bold,italic,underline,blockquote,separator,strikethrough,bullist,numlist,justifyleft,justifycenter,justifyright,undo,redo,link,unlink,fullscreen',
									'theme_advanced_buttons2' => 'pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo',
									'theme_advanced_buttons3' => '',
									'theme_advanced_buttons4' => ''
								),
								'quicktags' => array(
									'buttons' => 'b,i,ul,ol,li,link,close'
								)
							);
									
							wp_editor( $postContent, 'postContent', $settings );

						?>

						
						
						<div id="map-container">

							<input id="address" name="address" type="textbox" placeholder="<?php _e('Адрес', 'agrg') ?>"  class="input-textarea half">
							<input type="text" id="post_tags" name="post_tags" placeholder="<?php _e('Метки', 'agrg') ?>"  size="12" maxlength="110" class="form-text required last input-textarea half">

							<p class="help-block"><?php _e('Начните писать адрес и выберите в выпавшем списке.', 'agrg') ?></p>

						  


 

						<div class="clearfix"></div>
						

							
						

						<fieldset class="input-title" style="margin-top:10px;">

							<label for="edit-field-category-und" class="control-label"><?php _e('Загрузить фото', 'agrg') ?></label>
							<input id="upload-images-ad" type="file" name="upload_attachment[]" multiple />

						</fieldset>

						

						<fieldset class="input-title" style="margin-bottom:0px;">
							
							<textarea name="video" id="video" cols="8" rows="5" placeholder="<?php _e('Сюда можно вставить видео-код, если вы хотите!', 'agrg') ?>" ></textarea>
							<p class="help-block"><?php _e('Добавить видео эмбед код сюда (youtube, vimeo, и т. д.)', 'agrg') ?></p>

						</fieldset>

						<?php 

							global $redux_demo; 

							$featured_ads_option = $redux_demo['featured-options-on'];

						?>

						<?php if($featured_ads_option == 1) { ?>

						<fieldset class="input-title">

							<label for="edit-field-category-und" class="control-label"><?php _e('Тип объявления', 'agrg') ?></label>

								<?php if($featPlanMesage != '') { ?>
									<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $featPlanMesage; ?></span>
									<div class="clearfix"></div>
								<?php } ?>

								<div class="field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">
												<?php 

													global $redux_demo; 
													$regular_ads = $redux_demo['regular-ads'];

												?>
										<?php 
				
										    global $current_user;
			      							get_currentuserinfo();

			      							$userID = $current_user->ID;

											$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE user_id = $userID ORDER BY main_id DESC" );

											if ( $result ) {

											    $featuredADS = 0;

											    foreach ( $result as $info ) { 
								            		if($info->status != "in progress" && $info->status != "pending" && $info->status != "failed") {
																	
																	
															$featuredADS++;

															if(empty($info->ads)) {
																$availableADS = "Unlimited";
																$infoAds = "Unlimited";
															} else {
																$availableADS = $info->ads - $info->used;
																$infoAds = $info->ads;
															} 

															if(empty($info->days)) {
																$infoDays = "Unlimited";
															} else {
																$infoDays = $info->days;
															} 

															if($info->used != "Unlimited" && $infoAds != "Ulimited" && $info->used == $infoAds) {

															} else {

																?>

															<label class="option checkbox control-label" for="edit-field-featured-und">
																<input style="margin-right: 10px;margin-top: -2px;" type="radio" id="edit-feature-plan" name="edit-feature-plan" value="<?php echo $info->main_id; ?>" class="form-checkbox" <?php if($regular_ads == 0 ){ echo 'checked';} ?> ><?php echo $infoAds; ?> <?php if($infoAds>1) { ?>Ads<?php } elseif($infoAds=="Unlimited") { ?>Ads<?php } elseif($infoAds==1) { ?>Ad<?php } ?> active for <?php echo $infoDays ?> days (<?php echo $availableADS; ?> <?php if($availableADS>1) { ?>Ads<?php } elseif($availableADS=="Unlimited") { ?>Ads<?php } elseif($availableADS==1) { ?>Ad<?php } ?> available)
															</label>

													<?php }
												}
											}
										}
													
									?>
									
									<?php if($regular_ads == 1 ){ ?>
										<?php if($featuredADS != "0"){ ?>

											<label class="option checkbox control-label" for="edit-field-featured-und">
												<input style="margin-right: 10px;margin-top: -2px;" type="radio" id="edit-feature-plan" name="edit-feature-plan" value="" class="form-checkbox" checked><?php _e('Регулярные', 'agrg') ?>
												<input type="hidden" name="regular-ads-enable" value=""  >
											</label>

										<?php } ?>
									<?php } ?>

									<?php 
										$featured_plans = $redux_demo['featured_plans'];
									?>
									<?php if($featuredADS == "0" || empty($result)){ ?>
										<p><?php _e('В настоящее время у вас нет активного плана. Нужно приобрести.', 'agrg') ?> <a href="<?php echo $featured_plans; ?>" target="_blank"><?php _e('Популярный тарифный план', 'agrg') ?></a> <?php _e('чтобы иметь возможность опубликовать.', 'agrg') ?></p>
									<?php } ?>

							</div>

						</fieldset>

						<?php } ?>

						

						<div class="publish-ad-button">
							<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
							<input type="hidden" name="submitted" id="submitted" value="true" />
							<div class="btn-container">		
								<button class="btn form-submit full-btn" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Разместить объявление', 'agrg') ?></button>
							</div>
						</div>

					</form>

	    		</div>

	    	</div>

	    	<div class="span4">



		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>



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