<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Thirteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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

	<div class="ad-title" style="margin-bottom:0px;">

        	<h2>
        		<?php

        		global $paged, $wp_query, $wp;

				$args = wp_parse_args($wp->matched_query);

				if ( !empty ( $args['paged'] ) && 0 == $paged ) {

					$wp_query->set('paged', $args['paged']);

					$paged = $args['paged'];

				}

					if ( is_day() ) :

								printf( __( 'Daily Archives: %s', 'agrg' ), get_the_date() );
								$archive_year  = get_the_date('Y'); 
								$archive_month = get_the_date('m'); 
								$archive_day   = get_the_date('d');
								global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'day' => $archive_day, 'order' => 'DESC', 'post_type' => 'post');
								global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'day' => $archive_day, 'post_type' => 'post', 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
								global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'day' => $archive_day, 'order' => 'DESC', 'post_type' => 'post', 'orderby' => 'title');

					elseif ( is_month() ) :

								printf( __( 'Monthly Archives: %s', 'agrg' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'agrg' ) ) );
								$archive_year  = get_the_date('Y'); 
								$archive_month = get_the_date('m');
								global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'order' => 'DESC', 'post_type' => 'post');
								global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'post_type' => 'post', 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
								global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'order' => 'DESC', 'post_type' => 'post', 'orderby' => 'title');

					elseif ( is_year() ) :

								printf( __( 'Yearly Archives: %s', 'agrg' ), get_the_date( _x( 'Y', 'yearly archives date format', 'agrg' ) ) );
								$archive_year  = get_the_date('Y'); 
								global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'order' => 'DESC');
								global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
								global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'order' => 'DESC', 'orderby' => 'title');

					elseif ( is_tag() ) :

								printf( __( single_tag_title('Tag: ') ) );

								global $wp_query;
								$tag = $wp_query->get_queried_object();
								$current_tag = $tag->term_id;

								global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'tag_id' => $current_tag, 'order' => 'DESC');
								global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'tag_id' => $current_tag, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
								global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'tag_id' => $current_tag, 'order' => 'DESC', 'orderby' => 'title');

					else :

								_e( 'Archives', 'agrg' );

					endif;
				?>
        	</h2>

        </div>

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

						$wp_query->query($args);

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
	<div class="container search-bar" >
		<div id="advanced-search-widget-version2" class="home-search">

			<div class="container">

				<div class="advanced-search-widget-content">

					<form action="<?php echo home_url(); ?>" method="get" id="views-exposed-form-search-view-other-ads-page" accept-charset="UTF-8">
						
						<div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
						    <div class="views-widget">
						        <div class="control-group form-type-select form-item-field-category form-item">
									<div class="controls"> 
										<select id="edit-field-category" name="category_name" class="form-select" style="display: none;">
													
											<option value="All" selected="selected"><?php _e( 'Category...', 'agrg' ); ?></option>
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
											<option value="All" selected="selected"><?php _e( 'Location...', 'agrg' ); ?></option>

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
										<input placeholder="<?php _e( 'Enter keyword...', 'agrg' ); ?>" type="text" id="edit-search-api-views-fulltext" name="s" value="" size="30" maxlength="128" class="form-text">
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

	<?php 

		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];

	?>

	<?php if($featured_ads_option == 1) { ?>

	<?php

        global $paged, $wp_query, $wp;

		$args = wp_parse_args($wp->matched_query);

		if ( !empty ( $args['paged'] ) && 0 == $paged ) {

			$wp_query->set('paged', $args['paged']);

			$paged = $args['paged'];

		}

			if ( is_day() ) :

					$archive_year  = get_the_date('Y'); 
					$archive_month = get_the_date('m'); 
					$archive_day   = get_the_date('d');

					global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'day' => $archive_day, 'order' => 'DESC', 'post_type' => 'post');

			elseif ( is_month() ) :

					$archive_year  = get_the_date('Y'); 
					$archive_month = get_the_date('m');

					global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'order' => 'DESC', 'post_type' => 'post');

			elseif ( is_year() ) :

					$archive_year  = get_the_date('Y'); 

					global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'order' => 'DESC');

			elseif ( is_tag() ) :

					global $wp_query;
					$tag = $wp_query->get_queried_object();
					$current_tag = $tag->term_id;

					global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'tag_id' => $current_tag, 'order' => 'DESC');

			else :


			endif;
	?>

    <section id="featured-abs">
        
       <div class="container" style="width:100%">            
         
            
            <div id="tabs" class="full">			    	
                <?php $cat_id = get_cat_ID(single_cat_title('', false)); ?>
			    	
               

                <div class="pane">                 
                   

					<div id="projects-carousel">

			    		<?php

							$wp_query= null;

							$wp_query = new WP_Query();

							$wp_query->query($args);

							$current = -1;

							while ($wp_query->have_posts()) : $wp_query->the_post();

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
	<div class="clearfix" style="height:30px;"></div>
    <?php } ?>

    <section id="ads-homepage" class="category-page-ads">
		
		<div class="container">

	    	<div class="span8 first ">
				<div class="clearfix" style="height:30px;"></div>

				<div class="pane latest-ads-holder">

					<div class="latest-ads-grid-holder">

						<?php

			        		global $paged, $wp_query, $wp;

							$args = wp_parse_args($wp->matched_query);

							if ( !empty ( $args['paged'] ) && 0 == $paged ) {

								$wp_query->set('paged', $args['paged']);

								$paged = $args['paged'];

							}

								if ( is_day() ) :

											
											$archive_year  = get_the_date('Y'); 
											$archive_month = get_the_date('m'); 
											$archive_day   = get_the_date('d');
											global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'day' => $archive_day, 'order' => 'DESC', 'post_type' => 'post');
											global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'day' => $archive_day, 'post_type' => 'post', 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
											global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'day' => $archive_day, 'order' => 'DESC', 'post_type' => 'post', 'orderby' => 'title');

								elseif ( is_month() ) :

											
											$archive_year  = get_the_date('Y'); 
											$archive_month = get_the_date('m');
											global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'order' => 'DESC', 'post_type' => 'post');
											global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'post_type' => 'post', 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
											global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'monthnum' => $archive_month, 'order' => 'DESC', 'post_type' => 'post', 'orderby' => 'title');

								elseif ( is_year() ) :

											
											$archive_year  = get_the_date('Y'); 
											global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'order' => 'DESC');
											global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
											global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'year' => $archive_year, 'order' => 'DESC', 'orderby' => 'title');

								elseif ( is_tag() ) :

											

											global $wp_query;
											$tag = $wp_query->get_queried_object();
											$current_tag = $tag->term_id;

											global $args; $args = array('paged' => $paged, 'posts_per_page' => 9, 'tag_id' => $current_tag, 'order' => 'DESC');
											global $args_popular; $args_popular = array('paged' => $paged, 'posts_per_page' => 9, 'tag_id' => $current_tag, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC');
											global $args_random; $args_random = array('paged' => $paged, 'posts_per_page' => 9, 'tag_id' => $current_tag, 'order' => 'DESC', 'orderby' => 'title');

								else :

								endif;
							?>

					<?php

						$wp_query= null;

						$wp_query = new WP_Query();

						$wp_query->query($args);

						$current = -1;
						$current2 = 0;

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>

						<div class="ad-box span3 latest-posts-grid <?php if($current%3 == 0) { echo 'first'; } ?>">

							<?php
								if ( has_post_thumbnail()) {
								   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
								   echo '<a class="ad-image" href="' .get_permalink($post->ID). '" title="' . the_title_attribute('echo=0') . '" >';
								   echo get_the_post_thumbnail($post->ID, '270x220'); 
								   echo '</a>';
								 }
							?>
								<?php
								$post_price = get_post_meta($post->ID, 'post_price', true);
								if(!empty($post_price)){								
								?>
								<div class="add-price"><span><?php echo $post_price; ?></span></div> 
								<?php } ?>
				    		
							<div class="post-title-cat">
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

						        	<div class="category-icon-box"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

						        	<?php } 

						        	$category_icon_code = "";

						        	?>

				    			</div>

				    			
				    			
								
				    		
								<div class="post-title">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 22) ? substr($theTitle,0,22).'...' : $theTitle; echo $theTitle; ?></a>
								</div>
							</div>

						</div>

						<?php endwhile; ?>

					</div>
												
				<!-- Begin wpcrown_pagination-->	
				<?php get_template_part('pagination'); ?>
				<!-- End wpcrown_pagination-->	
																	
				<?php wp_reset_query(); ?>

				</div>

	
	
	    	</div>

	    	<div class="span4">

		    	<?php get_sidebar('pages'); ?>

	    	</div>

    	</div>

    </section>

    <script>
		// perform JavaScript after the document is scriptable.
		jQuery(function() {
			jQuery("ul.tabs").tabs("> .pane", {effect: 'fade', fadeIn: 200});
		});
	</script>

<?php get_footer(); ?>