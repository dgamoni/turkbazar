<?php
/**
 * Template name: Full Width Page
 * 
 * The template for displaying all pages.
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

<?php 

	$page = get_page($post->ID);
	$current_page_id = $page->ID;

	$page_slider = get_post_meta($current_page_id, 'page_slider', true); 


	global $redux_demo, $maximRange; 
	$max_range = $redux_demo['max_range'];
	if(!empty($max_range)) {
		$maximRange = $max_range;
	} else {
		$maximRange = 1000;
	}

?>

<?php if($page_slider == "LayerSlider") : ?>

	<section id="layerslider clearfix">

		<?php

			$page_layer_slider_shortcode = get_post_meta($current_page_id, 'layerslider_shortcode', true);

			if(!empty($page_layer_slider_shortcode))
			{
		?>

			<?php echo do_shortcode($page_layer_slider_shortcode); ?>

		<?php } else { ?>

			<?php echo do_shortcode('[layerslider id="1"]'); ?>

		<?php } ?>

		<?php 

			global $redux_demo; 

			$header_version = $redux_demo['header-version'];

		?>

		<?php if($header_version == 2) { ?>

		<div class="container search-bar">
			<div id="advanced-search-widget-version2" class="home-search">

				<div class="container">

					<div class="advanced-search-widget-content">

						<form action="<?php echo home_url(); ?>" method="get" id="views-exposed-form-search-view-other-ads-page" accept-charset="UTF-8">
							
							<div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
								<div class="views-widget">
									<div class="control-group form-type-select form-item-field-category form-item">
										<div class="controls"> 
											<select id="edit-field-category" name="category_name" class="form-select" style="display: none;">
														
												<option value="All" selected="selected"><?php _e( 'Категория...', 'agrg' ); ?></option>
												<?php
												$args = array(
													'hierarchical' => '0',
													'hide_empty' => '0'
												);
												$categories = get_categories($args);
													foreach ($categories as $cat) {
														if ($cat->category_parent == 0) { 
															$catID = $cat->cat_ID;
														?>
															<option value="<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></option>
																				
													<?php 
														$args2 = array(
															'hide_empty' => '0',
															'parent' => $catID
														);
														$categories = get_categories($args2);
														foreach ($categories as $cat) { ?>
															<option value="<?php echo $cat->slug; ?>">- <?php echo $cat->cat_name; ?></option>
													<?php } ?>

													<?php } else { ?>
													<?php }
												} ?>

											</select>
										</div>
									</div>
								</div>
							</div>
							
							<div id="edit-ad-location-wrapper" class="views-exposed-widget views-widget-filter-field_ad_location">
								<div class="views-widget">
									<div class="control-group form-type-select form-item-ad-location form-item">
										<div class="controls"> 
											<select id="edit-ad-location" name="post_location" class="form-select" style="display: none;">
												<option value="All" selected="selected"><?php _e( 'Местоположение...', 'agrg' ); ?></option>

												<?php

													$args_location = array( 'posts_per_page' => -1 );
													$lastposts = get_posts( $args_location );

													$all_post_location = array();
													foreach( $lastposts as $post ) {
														$all_post_location[] = get_post_meta( $post->ID, 'post_location', true );
													}

													$directors = array_unique($all_post_location);
													foreach ($directors as $director) { ?>
														<option value="<?php echo $director; ?>"><?php echo $director; ?></option>
													<?php }

												?>

												<?php wp_reset_query(); ?>

											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="advanced-search-slider">							

								<div id="advance-search-slider" class="value-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
									<a class="ui-slider-handle ui-state-default ui-corner-all" href="#">
										<span class="range-pin">
											<input type="text" name="geo-radius" id="geo-radius" value="100" data-default-value="100">
										</span>
									</a>
								</div>
								<div class="geo-location-button">

									<div class="geo-location-switch off"><i class="fa fa-location-arrow"></i></div>

								</div>

							</div>


							<input type="text" name="geo-location" id="geo-location" value="off" data-default-value="off">

							<input type="text" name="geo-radius-search" id="geo-radius-search" value="500" data-default-value="500">

							<input type="text" name="geo-search-lat" id="geo-search-lat" value="0" data-default-value="0">

							<input type="text" name="geo-search-lng" id="geo-search-lng" value="0" data-default-value="0">

							<div id="edit-search-api-views-fulltext-wrapper" class="views-exposed-widget views-widget-filter-search_api_views_fulltext">
								<div class="views-widget">
									<div class="control-group form-type-textfield form-item-search-api-views-fulltext form-item">
										<div class="controls"> 
											<input placeholder="<?php _e( 'Введите слово для поиска...', 'agrg' ); ?>" type="text" id="edit-search-api-views-fulltext" name="s" value="" size="30" maxlength="128" class="form-text">
											<input type="hidden" id="hidden-keyword" name="s" value="all" size="30" maxlength="128" class="form-text">
										</div>
									</div>
								</div>
							</div>
							
							<div class="views-exposed-widget views-submit-button">
								<button class="btn btn-primary form-submit" id="edit-submit-search-view" name="" value="Search" type="submit"><i class="fa fa-search"></i></button>
							</div>

						</form>

					</div>

				</div>

		</div>
	</div>

		<?php } ?>

	</section>



<?php elseif ($page_slider == "Big Map") : ?>

	<section id="big-map">

		<div id="classiads-main-map"></div>

		<script type="text/javascript">
		var mapDiv,
			map,
			infobox;
		jQuery(document).ready(function($) {

			mapDiv = $("#classiads-main-map");
			mapDiv.height(650).gmap3({
				map: {
					options: {
						"draggable": true
						,"mapTypeControl": true
						,"mapTypeId": google.maps.MapTypeId.ROADMAP
						,"scrollwheel": false
						,"panControl": true
						,"rotateControl": false
						,"scaleControl": true
						,"streetViewControl": true
						,"zoomControl": true
						<?php global $redux_demo; $map_style = $redux_demo['map-style']; if(!empty($map_style)) { ?>,"styles": <?php echo $map_style; ?> <?php } ?>
					}
				}
				,marker: {
					values: [

					<?php


						$wp_query= null;

						$wp_query = new WP_Query();

						$wp_query->query('post_type=post&posts_per_page=-1');

						


						while ($wp_query->have_posts()) : $wp_query->the_post(); 

						$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
						$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

						$theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle;

						$post_price = get_post_meta($post->ID, 'post_price', true);


						$category = get_the_category();

						if ($category[0]->category_parent == 0) {

							$tag = $category[0]->cat_ID;

							$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
							if (isset($tag_extra_fields[$tag])) {
								$your_image_url = $tag_extra_fields[$tag]['your_image_url']; //i added this line.
							}

						} else {

							$tag = $category[0]->category_parent;

							$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
							if (isset($tag_extra_fields[$tag])) {
								$your_image_url = $tag_extra_fields[$tag]['your_image_url']; //i added this line.
							}

						}

						if(!empty($your_image_url)) {

					    	$iconPath = $your_image_url;

					    } else {

					    	$iconPath = get_template_directory_uri() .'/images/icon-services.png';

					    }

						if(!empty($post_latitude)) { ?>

							 	{
							 		<?php require_once(TEMPLATEPATH . "/inc/BFI_Thumb.php"); ?>
									<?php $params = array( "width" => 370, "height" => 240, "crop" => true ); $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "single-post-thumbnail" ); ?>

									latLng: [<?php echo $post_latitude; ?>,<?php echo $post_longitude; ?>],
									options: {
										icon: "<?php echo $iconPath; ?>",
										shadow: "<?php echo get_template_directory_uri() ?>/images/shadow.png",
									},
									data: '<div class="marker-holder"><div class="marker-content"><div class="marker-image"><img src="<?php echo bfi_thumb( "$image[0]", $params ) ?>" /></div><div class="marker-info-holder"><div class="marker-info-price"><?php echo $post_price; ?></div><div class="marker-info"><div class="marker-info-title"><a href="<?php the_permalink(); ?>"><?php echo $theTitle; ?></a></div><?php if(!empty($category_icon_code)) { ?><div class="marker-icon-box"><div class="category-icon-box" ><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div></div><?php } ?></div></div><div class="arrow-down"></div><div class="close"></div></div></div>'
								}
							,

					<?php } endwhile; ?>	

					<?php wp_reset_query(); ?>
						
					],
					options:{
						draggable: false
					},
					cluster:{
		          		radius: 20,
						// This style will be used for clusters with more than 0 markers
						0: {
							content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
							width: 62,
							height: 62
						},
						// This style will be used for clusters with more than 20 markers
						20: {
							content: "<div class='cluster cluster-2'>CLUSTER_COUNT</div>",
							width: 82,
							height: 82
						},
						// This style will be used for clusters with more than 50 markers
						50: {
							content: "<div class='cluster cluster-3'>CLUSTER_COUNT</div>",
							width: 102,
							height: 102
						},
						events: {
							click: function(cluster) {
								map.panTo(cluster.main.getPosition());
								map.setZoom(map.getZoom() + 2);
							}
						}
		          	},
					events: {
						click: function(marker, event, context){
							map.panTo(marker.getPosition());

							var ibOptions = {
							    pixelOffset: new google.maps.Size(-125, -88),
							    alignBottom: true
							};

							infobox.setOptions(ibOptions)

							infobox.setContent(context.data);
							infobox.open(map,marker);

							// if map is small
							var iWidth = 370;
							var iHeight = 370;
							if((mapDiv.width() / 2) < iWidth ){
								var offsetX = iWidth - (mapDiv.width() / 2);
								map.panBy(offsetX,0);
							}
							if((mapDiv.height() / 2) < iHeight ){
								var offsetY = -(iHeight - (mapDiv.height() / 2));
								map.panBy(0,offsetY);
							}

						}
					}
				}
				 		 	},"autofit");

			map = mapDiv.gmap3("get");
		    infobox = new InfoBox({
		    	pixelOffset: new google.maps.Size(-50, -65),
		    	closeBoxURL: '',
		    	enableEventPropagation: true
		    });
		    mapDiv.delegate('.infoBox .close','click',function () {
		    	infobox.close();
		    });

		    if (Modernizr.touch){
		    	map.setOptions({ draggable : false });
		        var draggableClass = 'inactive';
		        var draggableTitle = "Activate map";
		        var draggableButton = $('<div class="draggable-toggle-button '+draggableClass+'">'+draggableTitle+'</div>').appendTo(mapDiv);
		        draggableButton.click(function () {
		        	if($(this).hasClass('active')){
		        		$(this).removeClass('active').addClass('inactive').text("Activate map");
		        		map.setOptions({ draggable : false });
		        	} else {
		        		$(this).removeClass('inactive').addClass('active').text("Deactivate map");
		        		map.setOptions({ draggable : true });
		        	}
		        });
		    }

		jQuery( "#advance-search-slider" ).slider({
		      	range: "min",
		      	value: 500,
		      	min: 1,
		      	max: <?php echo $maximRange; ?>,
		      	slide: function( event, ui ) {
		       		jQuery( "#geo-radius" ).val( ui.value );
		       		jQuery( "#geo-radius-search" ).val( ui.value );

		       		jQuery( ".geo-location-switch" ).removeClass("off");
		      	 	jQuery( ".geo-location-switch" ).addClass("on");
		      	 	jQuery( "#geo-location" ).val("on");

		       		mapDiv.gmap3({
						getgeoloc:{
							callback : function(latLng){
								if (latLng){
									jQuery('#geo-search-lat').val(latLng.lat());
									jQuery('#geo-search-lng').val(latLng.lng());
								}
							}
						}
					});

		      	}
		    });
		    jQuery( "#geo-radius" ).val( jQuery( "#advance-search-slider" ).slider( "value" ) );
		    jQuery( "#geo-radius-search" ).val( jQuery( "#advance-search-slider" ).slider( "value" ) );

		    jQuery('.geo-location-button .fa').click(function()
			{
				
				if(jQuery('.geo-location-switch').hasClass('off'))
			    {
			        jQuery( ".geo-location-switch" ).removeClass("off");
				    jQuery( ".geo-location-switch" ).addClass("on");
				    jQuery( "#geo-location" ).val("on");

				    mapDiv.gmap3({
						getgeoloc:{
							callback : function(latLng){
								if (latLng){
									jQuery('#geo-search-lat').val(latLng.lat());
									jQuery('#geo-search-lng').val(latLng.lng());
								}
							}
						}
					});

			    } else {
			    	jQuery( ".geo-location-switch" ).removeClass("on");
				    jQuery( ".geo-location-switch" ).addClass("off");
				    jQuery( "#geo-location" ).val("off");
			    }
		           
		    });

		});
		</script>

		<?php 

			global $redux_demo; 

			$header_version = $redux_demo['header-version'];

		?>

		<?php if($header_version == 2) { ?>

		<div class="container search-bar">
		<div id="advanced-search-widget-version2" class="home-search">

			<div class="container">

				<div class="advanced-search-widget-content">

					<form action="<?php echo home_url(); ?>" method="get" id="views-exposed-form-search-view-other-ads-page" accept-charset="UTF-8">
						
						<div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
						    <div class="views-widget">
						        <div class="control-group form-type-select form-item-field-category form-item">
									<div class="controls"> 
										<select id="edit-field-category" name="category_name" class="form-select" style="display: none;">
													
											<option value="All" selected="selected"><?php _e( 'Категория...', 'agrg' ); ?></option>
											<?php
											$args = array(
												'hierarchical' => '0',
												'hide_empty' => '0'
											);
											$categories = get_categories($args);
												foreach ($categories as $cat) {
													if ($cat->category_parent == 0) { 
														$catID = $cat->cat_ID;
													?>
														<option value="<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></option>
																			
												<?php 
													$args2 = array(
														'hide_empty' => '0',
														'parent' => $catID
													);
													$categories = get_categories($args2);
													foreach ($categories as $cat) { ?>
														<option value="<?php echo $cat->slug; ?>">- <?php echo $cat->cat_name; ?></option>
												<?php } ?>

												<?php } else { ?>
												<?php }
											} ?>

										</select>
									</div>
								</div>
						    </div>
						</div>
						
						<div id="edit-ad-location-wrapper" class="views-exposed-widget views-widget-filter-field_ad_location">
						   	<div class="views-widget">
						        <div class="control-group form-type-select form-item-ad-location form-item">
									<div class="controls"> 
										<select id="edit-ad-location" name="post_location" class="form-select" style="display: none;">
											<option value="All" selected="selected"><?php _e( 'Местоположение...', 'agrg' ); ?></option>

											<?php

												$args_location = array( 'posts_per_page' => -1 );
												$lastposts = get_posts( $args_location );

												$all_post_location = array();
												foreach( $lastposts as $post ) {
													$all_post_location[] = get_post_meta( $post->ID, 'post_location', true );
												}

												$directors = array_unique($all_post_location);
												foreach ($directors as $director) { ?>
													<option value="<?php echo $director; ?>"><?php echo $director; ?></option>
												<?php }

											?>

											<?php wp_reset_query(); ?>

										</select>
									</div>
								</div>
						    </div>
						</div>

						<div class="advanced-search-slider">							

							<div id="advance-search-slider" class="value-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
								<a class="ui-slider-handle ui-state-default ui-corner-all" href="#">
									<span class="range-pin">
										<input type="text" name="geo-radius" id="geo-radius" value="100" data-default-value="100">
									</span>
								</a>
							</div>
							<div class="geo-location-button">

								<div class="geo-location-switch off"><i class="fa fa-location-arrow"></i></div>

							</div>

						</div>


						<input type="text" name="geo-location" id="geo-location" value="off" data-default-value="off">

						<input type="text" name="geo-radius-search" id="geo-radius-search" value="500" data-default-value="500">

						<input type="text" name="geo-search-lat" id="geo-search-lat" value="0" data-default-value="0">

						<input type="text" name="geo-search-lng" id="geo-search-lng" value="0" data-default-value="0">

						<div id="edit-search-api-views-fulltext-wrapper" class="views-exposed-widget views-widget-filter-search_api_views_fulltext">
					        <div class="views-widget">
					          	<div class="control-group form-type-textfield form-item-search-api-views-fulltext form-item">
									<div class="controls"> 
										<input placeholder="<?php _e( 'Введите слово для поиска...', 'agrg' ); ?>" type="text" id="edit-search-api-views-fulltext" name="s" value="" size="30" maxlength="128" class="form-text">
										<input type="hidden" id="hidden-keyword" name="s" value="all" size="30" maxlength="128" class="form-text">
									</div>
								</div>
						    </div>
						</div>
						
						<div class="views-exposed-widget views-submit-button">
						    <button class="btn btn-primary form-submit" id="edit-submit-search-view" name="" value="Search" type="submit"><i class="fa fa-search"></i></button>
						</div>

					</form>

				</div>

			</div>

		</div>
	</div>

		<?php } ?>

	</section>

<?php endif; ?>
<?php if($page_slider  != "Big Map" && $page_slider != "LayerSlider") { ?>

		<div class="container">
		<div id="advanced-search-widget-version2" class="home-search">

			<div class="container">

				<div class="advanced-search-widget-content">

					<form action="<?php echo home_url(); ?>" method="get" id="views-exposed-form-search-view-other-ads-page" accept-charset="UTF-8">
						
						<div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
						    <div class="views-widget">
						        <div class="control-group form-type-select form-item-field-category form-item">
									<div class="controls"> 
										<select id="edit-field-category" name="category_name" class="form-select" style="display: none;">
													
											<option value="All" selected="selected"><?php _e( 'Категория...', 'agrg' ); ?></option>
											<?php
											$args = array(
												'hierarchical' => '0',
												'hide_empty' => '0'
											);
											$categories = get_categories($args);
												foreach ($categories as $cat) {
													if ($cat->category_parent == 0) { 
														$catID = $cat->cat_ID;
													?>
														<option value="<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></option>
																			
												<?php 
													$args2 = array(
														'hide_empty' => '0',
														'parent' => $catID
													);
													$categories = get_categories($args2);
													foreach ($categories as $cat) { ?>
														<option value="<?php echo $cat->slug; ?>">- <?php echo $cat->cat_name; ?></option>
												<?php } ?>

												<?php } else { ?>
												<?php }
											} ?>

										</select>
									</div>
								</div>
						    </div>
						</div>
						
						<div id="edit-ad-location-wrapper" class="views-exposed-widget views-widget-filter-field_ad_location">
						   	<div class="views-widget">
						        <div class="control-group form-type-select form-item-ad-location form-item">
									<div class="controls"> 
										<select id="edit-ad-location" name="post_location" class="form-select" style="display: none;">
											<option value="All" selected="selected"><?php _e( 'Местоположение...', 'agrg' ); ?></option>

											<?php

												$args_location = array( 'posts_per_page' => -1 );
												$lastposts = get_posts( $args_location );

												$all_post_location = array();
												foreach( $lastposts as $post ) {
													$all_post_location[] = get_post_meta( $post->ID, 'post_location', true );
												}

												$directors = array_unique($all_post_location);
												foreach ($directors as $director) { ?>
													<option value="<?php echo $director; ?>"><?php echo $director; ?></option>
												<?php }

											?>

											<?php wp_reset_query(); ?>

										</select>
									</div>
								</div>
						    </div>
						</div>

						<div class="advanced-search-slider">							

							<div id="advance-search-slider" class="value-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
								<a class="ui-slider-handle ui-state-default ui-corner-all" href="#">
									<span class="range-pin">
										<input type="text" name="geo-radius" id="geo-radius" value="100" data-default-value="100">
									</span>
								</a>
							</div>
							<div class="geo-location-button">

								<div class="geo-location-switch off"><i class="fa fa-location-arrow"></i></div>

							</div>

						</div>


						<input type="text" name="geo-location" id="geo-location" value="off" data-default-value="off">

						<input type="text" name="geo-radius-search" id="geo-radius-search" value="500" data-default-value="500">

						<input type="text" name="geo-search-lat" id="geo-search-lat" value="0" data-default-value="0">

						<input type="text" name="geo-search-lng" id="geo-search-lng" value="0" data-default-value="0">

						<div id="edit-search-api-views-fulltext-wrapper" class="views-exposed-widget views-widget-filter-search_api_views_fulltext">
					        <div class="views-widget">
					          	<div class="control-group form-type-textfield form-item-search-api-views-fulltext form-item">
									<div class="controls"> 
										<input placeholder="<?php _e( 'Введите слово для поиска...', 'agrg' ); ?>" type="text" id="edit-search-api-views-fulltext" name="s" value="" size="30" maxlength="128" class="form-text">
										<input type="hidden" id="hidden-keyword" name="s" value="all" size="30" maxlength="128" class="form-text">
									</div>
								</div>
						    </div>
						</div>
						
						<div class="views-exposed-widget views-submit-button">
						    <button class="btn btn-primary form-submit" id="edit-submit-search-view" name="" value="Search" type="submit"><i class="fa fa-search"></i></button>
						</div>

					</form>

				</div>

			</div>

		</div>
	</div>

    <?php 
	}
	?>

	<div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="full" style="padding: 40px 0;">

				<div class="ad-detail-content">

	    			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
					<?php the_content(); ?>
															
					<?php endwhile; endif; ?>

	    		</div>

	    		
	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>