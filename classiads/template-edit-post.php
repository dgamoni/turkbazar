<?php
/**
 * Template name: Edit Ad
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */

if ( !is_user_logged_in() ) {
								
	wp_redirect( home_url() ); exit;
								
} else { 

}

$postContent = '';
global $current_user;
		    get_currentuserinfo();

		    $userID = $current_user->ID;

$query = new WP_Query(array('post_type' => 'post', 'posts_per_page' =>'-1') );

if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
	
	if(isset($_GET['post'])) {
		
		if($_GET['post'] == $post->ID)
		{
			$author = get_the_author_ID();
			if($author != $userID) {
				wp_redirect( home_url() ); exit;
			}
			
			$current_post = $post->ID;

			$title = get_the_title();
			$content = get_the_content();

			$posttags = get_the_tags($current_post);
			if ($posttags) {
			  foreach($posttags as $tag) {
				$tags_list = $tag->name . ' '; 
			  }
			}

			$postcategory = get_the_category( $current_post );
			$category_id = $postcategory[0]->cat_ID;

			$post_category_type = get_post_meta($post->ID, 'post_category_type', true);
			$post_price = get_post_meta($post->ID, 'post_price', true);
			$post_location = get_post_meta($post->ID, 'post_location', true);
			$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
			$post_longitude = get_post_meta($post->ID, 'post_longitude', true);
			$post_price_plan_id = get_post_meta($post->ID, 'post_price_plan_id', true);
			$post_address = get_post_meta($post->ID, 'post_address', true);
			$post_video = get_post_meta($post->ID, 'post_video', true);

			$featured_post = "0";

			$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
			$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
			$todayDate = strtotime(date('d/m/Y H:i:s'));
			$expireDate = strtotime($post_price_plan_expiration_date);  

			if(!empty($post_price_plan_activation_date)) {

				if(($todayDate < $expireDate) or empty($post_price_plan_expiration_date)) {
					$featured_post = "1";
				}

			}



			if(empty($post_latitude)) {
				$post_latitude = 0;
			}

			if(empty($post_longitude)) {
				$post_longitude = 0;
				$mapZoom = 2;
			} else {
				$mapZoom = 16;
			}
			
			if ( has_post_thumbnail() ) {
			
				$post_thumbnail = get_the_post_thumbnail($current_post, 'thumbnail');
			
			} 
			
		}
	}

endwhile; endif;
wp_reset_query();

global $current_post;


$postTitleError = '';
$post_priceError = '';
$catError = '';
$featPlanMesage = '';

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



	if($hasError != true) {
	if(is_super_admin() ){
			$postStatus = 'publish';
		}elseif(!is_super_admin()){
			
			if($redux_demo['post-options-edit-on'] == 1){
				$postStatus = 'private';
			}else{
				$postStatus = 'publish';
			}
		}
	
		$post_information = array(
			'ID' => $current_post,
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

		$latitude = wp_kses($_POST['latitude'], $allowed);
		$longitude = wp_kses($_POST['longitude'], $allowed);

		if($latitude == 0) { $latitude = ""; };
		if($longitude == 0) { $longitude = ""; };

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
		update_post_meta($post_id, 'post_latitude', $latitude);
		update_post_meta($post_id, 'post_longitude', $longitude);
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

		if (isset($_POST['att_remove'])) {
			foreach ($_POST['att_remove'] as $att_id){
				wp_delete_attachment($att_id);
			}
		}
		
		wp_redirect( $permalink ); exit;

	}

} 

get_header();  ?>


	
	<?php while ( have_posts() ) : the_post(); ?>

	<div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span8 first ad-post-main">
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

							<span class="ad-detail-info"><?php _e( 'VIP Объявления', 'agrg' ); ?>
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

															<span class="ad-detail-info"><?php _e( 'VIP Объявления осталось', 'agrg' ); ?>
																<span class="ad-detail"><?php  echo $availableADS; ?></span>
															</span>

														<?php 
													}else{
														if($featuredADS == 0){
														?>
														<span class="ad-detail-info"><?php _e( 'VIP Объявления осталось', 'agrg' ); ?>
														<span class="ad-detail">0</span>
														</span>
														<?php
														} 
														$featuredADS++;
													}
												}
											}else{
										?>
										<span class="ad-detail-info"><?php _e( 'VIP Объявления осталось', 'agrg' ); ?>
										<span class="ad-detail">0</span>
										</span>
										<?php } ?>

					
						
						<?php } ?>
					</div>
					
				</div>


				<div id="upload-ad" class="ad-detail-content">

					<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

						<?php if($postTitleError != '') { ?>
							<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $postTitleError; ?></span>
							<div class="clearfix"></div>
						<?php } ?>


						<?php if($catError != '') { ?>
							<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $catError; ?></span>
							<div class="clearfix"></div>
						<?php } ?>

						

							<h2><?php echo $expireDate; ?></h2>

							<input type="text" id="postTitle" placeholder="Post Title" name="postTitle" value="<?php echo $title; ?>" size="60" maxlength="255" class="form-text required input-textarea half">


								<?php wp_dropdown_categories( 'show_option_none=Category&hide_empty=0&hierarchical=1&selected='. $category_id .'&taxonomy=category&id=catID' ); $currCatID = $category_id; ?>

							<div class="clearfix"></div>

						<?php
				        	$args = array(
				        	  'hide_empty' => false,
							  'orderby' => count,
							  'order' => 'ASC'
							);

							$inum = 0;

							$categories = get_categories($args);
							  	foreach($categories as $category) {;

							  	$inum++;

							  	global $user_id;

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

				          	<div id="cat-<?php echo $user_id; ?>" class="wrap-content" <?php if($currCatID == $user_id) { ?>style="display: block;"<?php  } else { ?>style="display: none;"<?php } ?>>

				             	<?php 

				             		$wpcrown_custom_fields = get_post_meta($current_post, 'custom_field', true);

				                	for ($i = 0; $i < (count($wpcrown_category_custom_field_option)); $i++) {

				              	?>

				               


									<input type="hidden" class="custom_field" id="custom_field[<?php echo $i; ?>][0]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][0]" value="<?php if($currCatID == $user_id) { echo $wpcrown_custom_fields[$i][0]; } ?>" size="12">

									<input type="text" placeholder="<?php if (!empty($wpcrown_category_custom_field_option[$i][0])) echo $wpcrown_category_custom_field_option[$i][0]; ?>" class="custom_field custom_field_visible input-textarea" id="custom_field[<?php echo $i; ?>][1]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][1]" value="<?php if($currCatID == $user_id) { echo $wpcrown_custom_fields[$i][1]; } ?>" size="12">

								
				              
				              	<?php 
				                	}
				              	?>

				            </div>

				      	<?php } ?>

				
							<input type="text" placeholder="Price" id="post_price" name="post_price" value="<?php echo $post_price; ?>" size="12" maxlength="10" class="form-text required input-textarea half">
							<?php
								$locations= $redux_demo['locations'];
								if(!empty($locations)){
								echo '<select name="post_location" id="post_location" >';
								echo '<option>'.$post_location.'</option>';
									$comma_separated = explode(",", $locations);
									foreach($comma_separated as $comma){
										echo '<option>'.$comma.'</option>';
									}
								echo '</select>';
								}else{
							?>
							<input type="text" placeholder="Location" id="post_location" name="post_location" value="<?php echo $post_location; ?>" size="12" maxlength="110" class="form-text required input-textarea half last">
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
									
							wp_editor( $content, 'postContent', $settings );

						?>

						<div id="map-container">

							<input id="address" placeholder="Address" name="address" type="textbox" value="<?php echo $post_address; ?>" class="input-textarea half">
							<?php

								echo "<input type='text' id='post_tags' placeholder='Tags' name='post_tags' value='";

								$posttags = get_the_tags($current_post);
								if ($posttags) {
								  foreach($posttags as $tag) {
									$tags_list = $tag->name . ', '; 
									echo $tags_list;
								  }
								}

							 	echo "' size='12' maxlength='110' class='form-text last required input-textarea half'>"; 

							 ?>

							<p class="help-block"><?php _e('Начните вводить адрес для выпадения списка.', 'agrg') ?></p>
							
						    <div id="map-canvas"></div>

						    <script type="text/javascript">

								jQuery(document).ready(function($) {

									var geocoder;
									var map;
									var marker;

									var geocoder = new google.maps.Geocoder();

									function geocodePosition(pos) {
									  geocoder.geocode({
									    latLng: pos
									  }, function(responses) {
									    if (responses && responses.length > 0) {
									      updateMarkerAddress(responses[0].formatted_address);
									    } else {
									      updateMarkerAddress('Cannot determine address at this location.');
									    }
									  });
									}

									function updateMarkerPosition(latLng) {
									  jQuery('#latitude').val(latLng.lat());
									  jQuery('#longitude').val(latLng.lng());
									}

									function updateMarkerAddress(str) {
									  jQuery('#address').val(str);
									}

									function initialize() {

									  var latlng = new google.maps.LatLng(<?php echo $post_latitude; ?>, <?php echo $post_longitude; ?>);
									  var mapOptions = {
									    zoom: <?php echo $mapZoom; ?>,
									    center: latlng
									  }

									  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

									  geocoder = new google.maps.Geocoder();

									  marker = new google.maps.Marker({
									  	position: latlng,
									    map: map,
									    draggable: true
									  });

									  // Add dragging event listeners.
									  google.maps.event.addListener(marker, 'dragstart', function() {
									    updateMarkerAddress('Dragging...');
									  });
									  
									  google.maps.event.addListener(marker, 'drag', function() {
									    updateMarkerPosition(marker.getPosition());
									  });
									  
									  google.maps.event.addListener(marker, 'dragend', function() {
									    geocodePosition(marker.getPosition());
									  });

									}

									google.maps.event.addDomListener(window, 'load', initialize);

									jQuery(document).ready(function() { 
									         
									  initialize();
									          
									  jQuery(function() {
									    jQuery("#address").autocomplete({
									      //This bit uses the geocoder to fetch address values
									      source: function(request, response) {
									        geocoder.geocode( {'address': request.term }, function(results, status) {
									          response(jQuery.map(results, function(item) {
									            return {
									              label:  item.formatted_address,
									              value: item.formatted_address,
									              latitude: item.geometry.location.lat(),
									              longitude: item.geometry.location.lng()
									            }
									          }));
									        })
									      },
									      //This bit is executed upon selection of an address
									      select: function(event, ui) {
									        jQuery("#latitude").val(ui.item.latitude);
									        jQuery("#longitude").val(ui.item.longitude);

									        var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);

									        marker.setPosition(location);
									        map.setZoom(16);
									        map.setCenter(location);

									      }
									    });
									  });
									  
									  //Add listener to marker for reverse geocoding
									  google.maps.event.addListener(marker, 'drag', function() {
									    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
									      if (status == google.maps.GeocoderStatus.OK) {
									        if (results[0]) {
									          jQuery('#address').val(results[0].formatted_address);
									          jQuery('#latitude').val(marker.getPosition().lat());
									          jQuery('#longitude').val(marker.getPosition().lng());
									        }
									      }
									    });
									  });
									  
									});

								});

						    </script>

						</div>

							<input type="text" placeholder="Latitude" id="latitude" name="latitude" value="<?php echo $post_latitude; ?>" size="12" maxlength="10" class="form-text required input-textarea half">


							<input type="text" placeholder="Longitude" id="longitude" name="longitude" value="<?php echo $post_longitude; ?>" size="12" maxlength="10" class="form-text required last input-textarea half">

						

						<fieldset class="input-title">

							<label for="edit-field-category-und" class="control-label"><?php _e('Фотографии', 'agrg') ?></label>

							<div id="edit-post-images-block">

								<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

								<?php

									$params = array( 'width' => 110, 'height' => 70, 'crop' => true );

									$attachments = get_children(array('post_parent' => $current_post,
													'post_status' => 'inherit',
													'post_type' => 'attachment',
													'post_mime_type' => 'image',
													'order' => 'ASC',
													'orderby' => 'menu_order ID'));

									foreach($attachments as $att_id => $attachment) {
													$attachment_ID = $attachment->ID;
													$full_img_url = wp_get_attachment_url($attachment->ID);
													$split_pos = strpos($full_img_url, 'wp-content');
													$split_len = (strlen($full_img_url) - $split_pos);
													$abs_img_url = substr($full_img_url, $split_pos, $split_len);
													$full_info = @getimagesize(ABSPATH.$abs_img_url);
								?>

									<div id="<?php echo $attachment_ID; ?>" class="edit-post-image-block">

										<img class="edit-post-image" src="<?php echo bfi_thumb( "$full_img_url", $params ) ?>" />

										<div class="remove-edit-post-image">
											<i class="fa fa-minus-square-o"></i>
											<span class="remImage"><?php _e('Удалить', 'agrg');?></span> 
											<input type="hidden" name="" value="<?php echo $attachment_ID; ?>">
										</div>

									</div>
								            

								<?php
									}
								?>

							</div>

						</fieldset>

						<fieldset class="input-title">

							<label for="edit-field-category-und" class="control-label"><?php _e('Загрузить', 'agrg') ?></label>
							<input id="upload-images-ad" type="file" name="upload_attachment[]" multiple />

						</fieldset>

						
						<fieldset class="input-title">

							
							<textarea name="video" placeholder="Если хотите смотреть видео, то вставьте embed код." id="video" cols="8" rows="5" ><?php echo $post_video; ?></textarea>
							<p class="help-block"><?php _e('Если хотите смотреть видео, то вставьте embed код. (youtube, vimeo, и т.д.)', 'agrg') ?></p>

						</fieldset>


						<?php 

							global $redux_demo; 

							$featured_ads_option = $redux_demo['featured-options-on'];

						?>

						<?php if($featured_ads_option == 1) { ?>

						<fieldset class="input-title">

							<label for="edit-field-category-und" class="control-label"><?php _e('Ad Type', 'agrg') ?></label>

								<?php

									$featured_post = "0";

									$post_price_plan_activation_date = get_post_meta($current_post, 'post_price_plan_activation_date', true);
									$post_price_plan_expiration_date = get_post_meta($current_post, 'post_price_plan_expiration_date', true);
									$post_price_plan_expiration_date_noarmal = get_post_meta($current_post, 'post_price_plan_expiration_date_normal', true);
									$todayDate = strtotime(date('m/d/Y h:i:s'));
								    $expireDate = $post_price_plan_expiration_date;

									if(!empty($post_price_plan_activation_date)) {

										if(($todayDate < $expireDate) or $post_price_plan_expiration_date == 0) {
											$featured_post = "1";
										}

									} 

								?>

								<?php if($featured_post == "1") { ?>

									<div class="field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">

										<label class="option checkbox control-label" for="edit-field-featured-und">
											<input style="margin-right: 10px;margin-top: -2px;" type="radio" id="feature-post" name="feature-post" value="featured" class="form-checkbox" checked><?php _e('Featured. Expires:', 'agrg') ?> <?php if($post_price_plan_expiration_date_noarmal == 0) { ?> <?php _e( 'Never', 'agrg' ); ?> <?php } else { echo $post_price_plan_expiration_date_noarmal; } ?>
										</label>

									</div>

								<?php } else { ?>

								<?php if($featPlanMesage != '') { ?>

									<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $featPlanMesage; ?></span>
									<div class="clearfix"></div>

								<?php } ?>

								<div class="field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">

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
																<input style="margin-right: 10px;margin-top: -2px;" type="radio" id="edit-feature-plan" name="edit-feature-plan" value="<?php echo $info->main_id; ?>" class="form-checkbox" ><?php echo $infoAds; ?> <?php if($infoAds>1) { ?>Ads<?php } elseif($infoAds=="Unlimited") { ?>Ads<?php } elseif($infoAds==1) { ?>Ad<?php } ?> active for <?php echo $infoDays ?> days (<?php echo $availableADS; ?> <?php if($availableADS>1) { ?>Ads<?php } elseif($availableADS=="Unlimited") { ?>Ads<?php } elseif($availableADS==1) { ?>Ad<?php } ?> available)
															</label>

													<?php }
												}
											}
										}
													
									?>

									<?php if($featuredADS != "0"){ ?>

										<label class="option checkbox control-label" for="edit-field-featured-und">
											<input style="margin-right: 10px;margin-top: -2px;" type="radio" id="edit-feature-plan" name="edit-feature-plan" value="" class="form-checkbox" <?php if($featured_post == "0") { ?>checked<?php } ?>><?php _e( 'Regular', 'agrg' ); ?>
										</label>

									<?php } ?>

									<?php 

										global $redux_demo; 
										$featured_plans = $redux_demo['featured_plans'];

									?>
									<?php if($featuredADS == "0"){ ?>
										<label class="option checkbox control-label" for="edit-field-featured-und">
											<input disabled="disabled" type="checkbox" id="edit-feature-plan" name="edit-feature-plan" value="" class="form-checkbox"><?php _e( 'Featured', 'agrg' ); ?>
										</label>
										<p><?php _e( 'Currently you have no active plan. You must purchase a ', 'agrg' ); ?><a href="<?php echo $featured_plans; ?>" target="_blank"><?php _e( 'Featured Pricing Plan', 'agrg' ); ?></a><?php _e( ' to be able to publish a Featured Ad.', 'agrg' ); ?></p>
									<?php } ?>

								</div>

							<?php } ?>

						</fieldset>

						<?php } ?>

						
						<div class="publish-ad-button">
							<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
							<input type="hidden" name="submitted" id="submitted" value="true" />
							<div class="btn-container">
								<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Обновить Объявление', 'agrg') ?></button>
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
							$post_price_plan_expiration_date_noarmal = get_post_meta($current_post, 'post_price_plan_expiration_date_normal', true);
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