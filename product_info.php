<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!isset($HTTP_GET_VARS['products_id'])) {
    tep_redirect(tep_href_link('index.php'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . 'product_info.php');

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  require(DIR_WS_INCLUDES . 'template_top.php');

  if ($product_check['total'] < 1) {
?>

<div class="contentContainer">
  <div class="contentText">
    <?php echo TEXT_PRODUCT_NOT_FOUND; ?>
  </div>

  <div style="float: right;">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?>
  </div>
</div>

<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_weight, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<span class="line-through">' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])).'</span><strong>' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</strong>';
    } else {
      $products_price = '<strong>' .$currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])). '</strong>';
    }

    
      $products_model =  $product_info['products_model'];
	  $products_quantity =  $product_info['products_quantity'];
	  $products_manufacturers =  $product_info['manufacturers_name'];
	  $products_weight =  $product_info['products_weight'];
      $products_name = $product_info['products_name'];
	  
	  $pid = $product_info['products_id'];
	  
	  /*Reviews Query*/
	  $review_query = tep_db_query("select products_id, reviews_rating from ".TABLE_REVIEWS." where products_id='$pid'");
	  $review_res = tep_db_fetch_array($review_query);
	  if($review_res != NULL) {
	  $rating_stars =  sprintf(tep_image(DIR_WS_IMAGES . 'stars_' . $review_res['reviews_rating'] . '.gif', 
					  sprintf(TEXT_OF_5_STARS,$review_res['reviews_rating'])), sprintf($review_res['reviews_rating']));
	  }else {
	   $rating_stars = tep_draw_button(IMAGE_BUTTON_WRITE_REVIEW, 'comment', tep_href_link('product_reviews_write.php', tep_get_all_get_params()), 'primary');
	  }

?>
<div id="productGeneral">
	<?php echo tep_draw_form('cart_quantity', tep_href_link('product_info.php', tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
	<div class="product_info">
		<div class="contentContainer">
			<div class="contentText">
				<div class="productinfo-leftwrapper <?php echo $prod_info_img_class; ?> hidden-xs">
					<div class="main_product_image">
					<?php
					if (tep_not_null($product_info['products_image'])) {
						$pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");
						  if (tep_db_num_rows($pi_query) > 0) { ?>
								<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']),PRODUCT_INFO_IMAGE_WIDTH,PRODUCT_INFO_IMAGE_HEIGHT,' id="main_prodimage" class="product_info_gallery"');  ?>
								<div id="piGal" class="owl-carousel owl-theme">
									<div class="item">
										<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image'], '', 'NONSSL', false) . '" target="_blank"  data-image="'.DIR_WS_IMAGES . $product_info['products_image'].'" data-zoom-image="'.DIR_WS_IMAGES . $product_info['products_image'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']),SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,' id="main_prodimage" class="product_info_gallery"').'</a>';  ?>
									</div>
								<?php
									$pi_counter = 0;
									while ($pi = tep_db_fetch_array($pi_query)) {
									  $pi_counter++;
									  $pi_entry = '<div class="item"><a href="';
									  if (tep_not_null($pi['htmlcontent'])) {
										$pi_entry .= '#piGalimg_' . $pi_counter;
									  } else {
										$pi_entry .= tep_href_link(DIR_WS_IMAGES . $pi['image'], '', 'NONSSL', false);
									  }
									  $pi_entry .= '" target="_blank" data-image="'.(DIR_WS_IMAGES . $pi['image']).'" data-zoom-image="'.(DIR_WS_IMAGES . $pi['image']).'" class="gal-zoom">' . tep_image(DIR_WS_IMAGES . $pi['image'], $pi['title'],SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,' class="product_info_gallery"') . '</a></div>';

									  if (tep_not_null($pi['htmlcontent'])) {
										$pi_entry .= '<div style="display: none;"><div id="piGalimg_' . $pi_counter . '">' . $pi['htmlcontent'] . '</div></div>';
									  }
									  $pi_entry .= '';
									  echo $pi_entry;
									}
								?>
								</div>
								<script type="text/javascript">
								$(document).ready(function($){
									//initiate the plugin and pass the id of the div containing gallery images
									$("#main_prodimage").elevateZoom({gallery:'piGal', cursor: 'pointer', galleryActiveClass: 'active',zoomWindowWidth:300, zoomWindowHeight:300, scrollZoom : true, imageCrossfade: true, loadingIcon: 'ext/images/spinner.gif'});
								});
								</script>
							<?php } else{ ?>
							<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image'], '', 'NONSSL', false) . '" rel="fancybox" data-image="'.DIR_WS_IMAGES . $product_info['products_image'].'" data-zoom-image="'.DIR_WS_IMAGES . $product_info['products_image'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']),PRODUCT_INFO_IMAGE_WIDTH,PRODUCT_INFO_IMAGE_HEIGHT,' id="main_prodimage" class="product_info_gallery"').'</a>';  ?>
							<div id="piGal1" style="display:none !important;">
									<div class="item">
										<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image'], '', 'NONSSL', false) . '" target="_blank"  data-image="'.DIR_WS_IMAGES . $product_info['products_image'].'" data-zoom-image="'.DIR_WS_IMAGES . $product_info['products_image'].'">'.tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']),SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,' id="main_prodimage" class="product_info_gallery"').'</a>';  ?>
									</div>
							</div>
							<script type="text/javascript">
								$(document).ready(function($){
									//initiate the plugin and pass the id of the div containing gallery images
									$("#main_prodimage").elevateZoom({gallery:'piGal1', cursor: 'pointer', galleryActiveClass: 'active' ,zoomWindowWidth:300, zoomWindowHeight:300, scrollZoom : true, imageCrossfade: true, loadingIcon: 'ext/images/spinner.gif'});
								});
							</script>
							<?php } ?>
							
							<script type="text/javascript">
								$(document).ready(function($){
									//pass the images to Fancybox
									$("#main_prodimage").bind("click", function(e) {  
									  var ez =   $('#main_prodimage').data('elevateZoom');	
										$.fancybox(ez.getGalleryList());
									  return false;
									});
								});
							</script>
					<?php } ?>
					</div>
				</div>
				<div id="product_info_display" class="<?php echo $prod_info_content_class; ?> productinfo-rightwrapper product-detail pro-text">
					
					<h4><?php echo $products_name; ?></h4>
					<?php 
					if (tep_not_null($product_info['products_image'])) {
							$pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");
							  ?>
					<div class="mobile-gallery visible-xs">
						<div id="mobileGallery" class="owl-carousel owl-theme">
							<div class="item">
								<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']),'auto','auto',' id="main_prodimage" class="product_info_gallery"');  ?>
							</div>
							<?php 
							if (tep_db_num_rows($pi_query) > 0) { 
								$pi_counter = 0;
								while ($pi = tep_db_fetch_array($pi_query)) {
								  $pi_counter++;
								  $pi_entry = '<div class="item">' . tep_image(DIR_WS_IMAGES . $pi['image'], $pi['title'],'auto','auto','  class="product_info_gallery"') . '</div>';
								  $pi_entry .= '';
								  echo $pi_entry;
								}
							}
							?>
						</div>
					 </div>
					 <?php } ?>
					 
					<p><?php echo $products_price; ?></p>
					<div class="instock">
						<ul>
							<?php if ($products_quantity != 0) {?>	
								<li class="black-text">
									<i class="material-icons green">check_circle</i><?php echo $products_quantity; ?> <?php echo PRODUCT_INFO_UNIT_STOCK;?>
								</li>
							<?php } else { ?>
								<?php echo PRODUCT_INFO_OUT_STOCK;?>
							<?php } ?>
						</ul>
					</div>
					<div class="star2"><?php echo $rating_stars; ?></div>
					<?php if(PRODUCT_QUICK_OVER_INFO != 0 && $display_prod_short_desc==1) { ?>
						<?php 
							$products_description = $product_info['products_description'];
							$products_description_short = ltrim(substr($products_description, 0, 350) . '..'); 
							//Trims and Limits the desc
						?>
						<p><?php echo strip_tags($products_description_short); ?></p>
					<?php } ?>    
					<?php if(PRODUCT_ADDITIONAL_INFO != 0) {?>
					<div class="product_quantity">
						<ul id="productDetailsList" class="floatingBox">
							<?php if ($products_weight != 0) {?>
								<li><?php echo PRODUCT_INFO_WEIGHT;?> : <?php echo $products_weight; ?></li>
							<?php } ?>
							<?php if ($products_model != NULL) {?>
								<li><?php echo PRODUCT_INFO_MODEL;?> : <?php echo $products_model; ?></li>
							<?php } ?>
							<?php if ($products_manufacturers != NULL) {?>
								<li><?php echo PRODUCT_INFO_MAN_BY;?> : <?php echo $products_manufacturers; ?></li>
							<?php } ?>
						</ul>
					</div>
					<?php } ?>
					 <?php
					$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
					$products_attributes = tep_db_fetch_array($products_attributes_query);
					if ($products_attributes['total'] > 0) {
						?>
						<div id="productAttributes_pin" class="row">
						<?php 
			 
						$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
						while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
							$products_options_array = array();
							$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
							
							while ($products_options = tep_db_fetch_array($products_options_query)) {
							
								$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
								if ($products_options['options_values_price'] != '0') {
									$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
								}
							}
							
							if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
								$selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
							} else {
								$selected_attribute = false;
							}
							?>
						  
							<div class="attribsoptions col-lg-12 col-xs-12"><h6 class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><?php echo $products_options_name['products_options_name'] . ':'; ?></h6><div class="product_attributes col-lg-10 col-md-10 col-sm-10 col-xs-10"><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?></div></div>
						<?php } ?>
						</div>
					<?php } ?>
					<div class="cart_info">
						<?php if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) { ?>
						<p style="text-align: center;"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></p>
						<?php } ?>
						<div class="qty_box numbers-row">
							<?php if ($products_options_total['total'] >= 0) { echo tep_draw_input_field('cart_quantity','1','size="3" class="quantity-input"'); } ?>
							<div class="inc ibtn">+</div>
							<div class="dec ibtn">-</div>
						</div>
						<div class="cart_quantity">
						   <div class="add_to_cart cart-buttons addtocart2">
								<?php if ($product_info['products_price'] == CALL_FOR_PRICE_VALUE){ ?>
									<div class="cart-buttons ckpc pi"><span><a href="javascript:history.go(-1)"><?php echo TEXT_BUTTON_CONTINUE; ?></a></span></div>
									<?php
									} else { ?>
									<?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', null, 'primary'); ?>
								<?php } ?>
						   </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		$reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and reviews_status = 1");
		$reviews = tep_db_fetch_array($reviews_query);
		?>
		<div class="prod_info_tab">
			<div class="tab-bg">
				<ul>
					<li class="active"><a data-toggle="tab" href="#description">Description</a></li>
					<li><a data-toggle="tab" href="#reviews"> Reviews</a></li>
				</ul>
			</div>
			<div class="tab-content">
				<div id="description" class="tab-pane fade in active">
					 <?php echo stripslashes($product_info['products_description']); ?>
					 <?php if (DISPLAY_EXTRA_VIDEOS == 'true'){
						if ($product_check['total'] >= 1) {
							include (DIR_WS_INCLUDES . 'products_extra_videos.php');
						}
					} ?>
				</div>
				
				<div id="reviews" class="tab-pane fade">
					<?php if($reviews['count'] == 0){
							 echo '<p>' . TEXT_NO_REVIEWS . '</p>';
							 echo tep_draw_button(IMAGE_BUTTON_WRITE_REVIEW, 'comment', tep_href_link('product_reviews_write.php', tep_get_all_get_params()), 'primary');
						}
						else{
							echo tep_draw_button(IMAGE_BUTTON_REVIEWS . (($reviews['count'] > 0) ? ' (' . $reviews['count'] . ')' : ''), 
							'comment', tep_href_link('product_reviews.php', tep_get_all_get_params())); 
						}
					?>
				</div>
			</div>
		</div>
		<?php $also_prod_cont = posc_product_list('also_purchased_products.php', 'slider', 1, $show_xl_columns, $show_lg_columns, $show_md_columns, $show_sm_columns, $show_xs_columns, $show_xxs_columns); ?>
		<?php if($also_prod_cont){ ?>
		<div class="centerBoxWrapper alsoPurchased new-arrivals">
			<h2><?php echo TEXT_ALSO_PURCHASED_PRODUCTS;?></h2>
			<div class="row">
				<?php echo $also_prod_cont; ?>
			</div>
		</div>
		<?php } ?>
		<?php
		if ((USE_CACHE == 'true') && empty($SID)) {
			echo tep_cache_also_purchased(3600);
		} else {
			include(DIR_WS_MODULES . 'also_purchased_products.php');
		}
	?>
	</div>
	  
	</form>
</div>
<?php
}

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
