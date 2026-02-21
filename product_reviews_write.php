<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . 'product_reviews_write.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link('login.php', '', 'SSL'));
  }

  if (!isset($HTTP_GET_VARS['products_id'])) {
    tep_redirect(tep_href_link('product_reviews.php', tep_get_all_get_params(array('action'))));
  }

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link('product_reviews.php', tep_get_all_get_params(array('action'))));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }

  $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $customer = tep_db_fetch_array($customer_query);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $rating = tep_db_prepare_input($HTTP_POST_VARS['rating']);
    $review = tep_db_prepare_input($HTTP_POST_VARS['review']);

    $error = false;
    if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
      $error = true;

      $messageStack->add('review', JS_REVIEW_TEXT);
    }

    if (($rating < 1) || ($rating > 5)) {
      $error = true;

      $messageStack->add('review', JS_REVIEW_RATING);
    }

    if ($error == false) {
      tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
      $insert_id = tep_db_insert_id();

      tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");

      $messageStack->add_session('product_reviews', TEXT_REVIEW_RECEIVED, 'success');
      tep_redirect(tep_href_link('product_reviews.php', tep_get_all_get_params(array('action'))));
    }
  }

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . 
	'<span">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'];
  } else {
    $products_name = $product_info['products_name'];
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('product_reviews.php', tep_get_all_get_params()));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<script type="text/javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<div id="reviewsWrite" class="centerColumn">
	<div class="panel panel-default head-wrap">
		<div class="panel-heading">
			<h4 class="panel-title"><?php echo $products_name; ?></h4>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 reviews-info-productmain-image img_ar">
            <div id="reviewWriteMainImage" class="centeredContent back">
                <div id="productMainImage" class="centeredContent back">
                    <?php echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $product_info['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name'])) . '</a>'; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12 des_ar">
        	<div class="content">
                <div class="wk-price mt0">
					<?php echo $products_price; ?>
                </div>
                
                
                <?php
                    if ($messageStack->size('review') > 0) {
                        echo $messageStack->output('review');
                        }
                ?>
                <?php echo tep_draw_form('product_reviews_write', tep_href_link('product_reviews_write.php', 'action=process&products_id=' . $HTTP_GET_VARS['products_id']), 
                'post', 'onsubmit="return checkForm();"', true); ?>
                    
                <div id="reviewsWriteReviewer" class="review_from">
					<?php echo SUB_TITLE_WRITTEN; ?> 
                    <span class="user_name">
                        <?php echo tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']); ?>
                    </span>
                </div>
                
                <div class="review_text_ratting">
                    <?php echo "Choose a ranking for this item. 1 star is the worst and 5 stars is the best."; ?>	
                </div>
                
                <div class="ratingRow">	
                	<div class="ratings_inputradio">	
                    	<?php echo tep_draw_radio_field('rating', '1'); ?>
						<label><?php echo tep_image(DIR_WS_IMAGES.'stars_1_small.gif')?></label>
                    </div>
                    <div class="ratings_inputradio">
                        <?php echo tep_draw_radio_field('rating', '2'); ?>
						<label><?php echo tep_image(DIR_WS_IMAGES.'stars_2_small.gif')?></label>
                    </div>
                    <div class="ratings_inputradio"> 
                        <?php echo tep_draw_radio_field('rating', '3'); ?>
						<label><?php echo tep_image(DIR_WS_IMAGES.'stars_3_small.gif')?></label>
                    </div>
                    <div class="ratings_inputradio">
                        <?php echo tep_draw_radio_field('rating', '4'); ?>
						<label><?php echo tep_image(DIR_WS_IMAGES.'stars_4_small.gif')?></label>
                    </div>
                    <div class="ratings_inputradio">
                        <?php echo tep_draw_radio_field('rating', '5'); ?>
						<label><?php echo tep_image(DIR_WS_IMAGES.'stars_5_small.gif')?></label>        
                	</div>
                </div>
                <?php if (tep_not_null($product_info['products_image'])) { ?>
                <div class="cart-buttons ckpc">
                	<?php echo tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', tep_href_link(basename($PHP_SELF), 
                    tep_get_all_get_params(array('action')) . 'action=buy_now')); ?>
                </div>
                <?php } ?>
        	</div>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
			<div class="content">
                <div class="review_textarea">
                    <span class="review_title_text">
                        <?php echo SUB_TITLE_REVIEW_TEXT; ?> 
                    </span>
                    <?php echo tep_draw_textarea_field('review', 'soft', 55, 5); ?> 
                </div>
                <span class="cart-buttons ckpc bkbc"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'primary'); ?></span>
                <span class="cart-buttons ckpc"><?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('product_reviews.php', tep_get_all_get_params(array('reviews_id', 'action')))); ?></span>
                <div class="text_no_html"><?php echo TEXT_NO_HTML; ?></div>
    		</div>
        </div>
    </div>
</form>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
