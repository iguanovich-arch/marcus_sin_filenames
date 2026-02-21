<?php 
/**
 * Posc AjxCart for Zen Cart.
 *
 * @copyright Copyright 2017 Perfectus Inc.
 * Version : Posc AjxCart 1.1
 */
?>
<?php 
require('includes/application_top.php');

	if (!isset($HTTP_GET_VARS['products_id'])) {
		tep_redirect(tep_href_link('index.php'));
	}

	require(DIR_WS_LANGUAGES . $language . '/' . 'product_info.php');

	$product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
	$product_check = tep_db_fetch_array($product_check_query);

	if ($product_check['total'] < 1) {  ?>
	<div class="contentContainer">
		<div class="contentText"><?php echo TEXT_PRODUCT_NOT_FOUND; ?></div>
		<div style="float: right;"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?></div>
	</div>
<?php } else {
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
	$products_image = $product_info['products_image'];
	  
	$products_id = $product_info['products_id'];
	
?>

<div class="centerColumn product-shop posc-ajxcartattr-wrapper" id="productGeneral">
	<?php /*
	<div class="ajxcart-popup-top">
		<h2><?php echo POSC_AJXCART_QCK_SELECT_OPTION; ?></h2>
	</div>*/ ?>
	<!--bof Form start-->
	<?php echo tep_draw_form('cart_quantity', tep_href_link('product_info.php', tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
	<!--eof Form start-->
	<?php if ($messageStack->size('product_info') > 0) echo $messageStack->output('product_info'); ?>
	<div class="ajxcart-info">
		<div class="product_image">
			<!--bof Main Product Image -->
			<?php  if (tep_not_null($products_image)) {  ?>
				<div id="productMainImage" class="centeredConten">
				<?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']),POSC_AJXCART_POPUP_IMAGE_WIDTH,POSC_AJXCART_POPUP_IMAGE_HEIGHT,' id="main_prodimage" class="product_info_gallery"'); ?>
				</div>
			<?php  }?>
			<!--eof Main Product Image-->
		</div>
		<div class="product-info-main">
			<div class="pinfo-right product-info">
				<h1 id="productName" class="productGeneral"><?php echo $products_name; ?></h1>
				<!--bof Product details list  -->
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
				<!--eof Product details list -->
				<!--bof Product Price block -->
				<div class="product-price">
					<h2 id="productPrices" class="productGeneral"><?php echo $products_price; ?></h2>
				</div>
				<!--eof Product Price block -->
				<div class="ajxcart-info-attr">
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
				</div>
				<div class="cart_info">
					<?php if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {	?>
						<p class="text-center"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></p>
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
	</form>
<?php } ?>
</div>