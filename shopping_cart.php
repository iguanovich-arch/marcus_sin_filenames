<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  if ($cart->count_contents() > 0) {
    include(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment;
  }

  require(DIR_WS_LANGUAGES . $language . '/' . 'shopping_cart.php');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('shopping_cart.php'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>



<?php
  if ($cart->count_contents() > 0) {
?>
<div id="shoppingCartDefault" class="shooping-cart2">
<?php echo tep_draw_form('cart_quantity', tep_href_link('shopping_cart.php', 'action=update_product')); ?>
<section class="shopping-cart">

<?php
    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }
?>

    <table id="cartContentsDisplay">
    	<tbody>
        	<tr class="tableHeading">
            	<th><?php echo PRODUCT_TITLE_CART; ?></th>
                <th class="hide-shop"><?php echo PRODUCT_QTY_CART; ?></th>
                <th class="hide-shop"><?php echo PRODUCT_QTY_CART_PRICE; ?></th>
                <th class="hide-shop"><?php echo PRODUCT_EDIT_CART; ?></th>
                <th class="hide-shop"><?php echo ENTRY_TOTAL_CART; ?></th>
            </tr>
<?php

    for ($i=0, $n=sizeof($products); $i<$n; $i++) { ?>
     	<tr>
	    	<td>
            	<?php
      				if (STOCK_CHECK == 'true') {
        				$stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        					if (tep_not_null($stock_check)) {
          						$any_out_of_stock = 1;
          						$products_name_q = $stock_check;
        					}
      				}
				?>
				<?php 
            		if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
						reset($products[$i]['attributes']);
        				while (list($option, $value) = each($products[$i]['attributes'])) {
          					$products[$i]['name'] .= '<br /><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . 
							$products[$i][$option]['products_options_values_name'] . '</i></small>';
        				}
      			} ?>
                
				<?php echo tep_image(DIR_WS_IMAGES . $products[$i]['image'],$products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?>
				<ul class="shop-ul shop-ul2">
					<li>
						<a href="<?php echo tep_href_link('product_info.php', 'products_id=' . $products[$i]['id']);?>">
							<h5><?php echo $products[$i]['name'] . $products_name_q; ?></h5>
						</a>
					</li>
				</ul>
            </td>
      		<td>
				<?php echo tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']); ?>
            </td>
			<td><?php echo $currencies->display_price($products[$i]['price'], tep_get_tax_rate($products[$i]['tax_class_id']));?></td>
			<td class="cartRemoveItemDisplay">
				<?php echo tep_draw_button(IMAGE_BUTTON_UPDATE_SHOPPING_CART); ?>
                <a  title="Remove" class="red" href="<?php echo tep_href_link('shopping_cart.php', 'action=remove_product&products_id=' . $products[$i]['id']); ?>">
					<?php echo '<i class="material-icons">close</i>';?>
				</a>
            </td>
      		<td align="center" valign="middle" class="cartTotalDisplay">
				<?php echo $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']); ?>
            </td>
       </tr>
   		<?php  }
		?>
        </tbody>
    </table>
    <div id="cartSubTotal"><strong><?php echo SUB_TITLE_SUB_TOTAL; ?></strong> <?php echo $currencies->format($cart->show_total()); ?>
    </div>

	<?php
    	if ($any_out_of_stock == 1) {
      	if (STOCK_ALLOW_CHECKOUT == 'true') {
	?>
    <p class="stockWarning" align="center"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></p>
	<?php
      } else {
	?>
    <p class="stockWarning" align="center"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></p>
	<?php
     	 }
    	}
	?>
    <div class="buttonSet">
        <span class="cart-buttons ckpc">
			<?php echo tep_draw_button(IMAGE_BUTTON_CHECKOUT, 'triangle-1-e', tep_href_link('checkout_shipping.php', '', 'SSL'), 'primary'); ?>
        </span>
        <?php
        $initialize_checkout_methods = $payment_modules->checkout_initialization_method();
        if (!empty($initialize_checkout_methods)) {
          reset($initialize_checkout_methods);
          while (list(, $value) = each($initialize_checkout_methods)) { ?>
         <span class="alternate_text"><?php echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?> </span>
         <span class="other_options"> <?php echo $value; ?></span>
         <?php  }
        }
    ?>
	</div>
  


</section>
</form>
</div>
<?php
  } else {
?>
<div id="shoppingCartDefault" class="centerColumn">
	<h2 id="crtempty"><?php echo TEXT_CART_EMPTY; ?></h2>
</div>
<section class="grid-shop shopping-page">
	<!-- .grid-shop -->
	<!-- .shop-deails-bg -->
	<div class="shop-deails-bg3">
		<div class="container">
			<!-- purchased product -->
			<div class="row">					
				<!-- trending this week -->
				<!-- title -->
				<div class="title">
					<h2><?php echo POSC_HEADING_NEW_ARRIVALS; ?></h2>
				</div>
				<!-- /title -->
				<!-- electonics -->
				<div class="just-arrived new-arrivals pad-bot10">
					<div class="row">
						<?php echo posc_product_list('new_products.php', 'slider', 1, 5, 4, 3, 3, 2, 1); ?>
					</div>
				</div>
				<!-- /trending this week -->
				<!-- new arrivals -->
				<!-- title -->
				<div class="title">
					<h2><?php echo POSC_HEADING_FEATURED_PRODUCTS; ?></h2>							
				</div>
				<!-- /title -->
				<!-- electonics -->
				<div class="just-arrived new-arrivals pad-bot0">
					<div class="row">
						<?php echo posc_product_list(FILENAME_FEATURED, 'slider', 1, 5, 4, 3, 3, 2, 1); ?>
					</div>
				</div>
				<!-- /new arrivals -->
			</div>
			<!-- /purchased product -->
			<!-- /.pro-text -->

		</div>
	</div>
	<!-- /.shop-deails-bg -->
</section>
<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
