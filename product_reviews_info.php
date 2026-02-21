<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (isset($HTTP_GET_VARS['reviews_id']) && tep_not_null($HTTP_GET_VARS['reviews_id']) && isset($HTTP_GET_VARS['products_id']) && tep_not_null($HTTP_GET_VARS['products_id'])) {
    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.reviews_status = 1");
    $review_check = tep_db_fetch_array($review_check_query);

    if ($review_check['total'] < 1) {
      tep_redirect(tep_href_link('product_reviews.php', tep_get_all_get_params(array('reviews_id'))));
    }
  } else {
    tep_redirect(tep_href_link('product_reviews.php', tep_get_all_get_params(array('reviews_id'))));
  }

  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "'");

  $review_query = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.customers_name, r.date_added, r.reviews_read, p.products_id, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.products_id = p.products_id and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '". (int)$languages_id . "'");
  $review = tep_db_fetch_array($review_query);

  if ($new_price = tep_get_products_special_price($review['products_id'])) {
    $products_price = $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id'])). 
	'<span>' . $currencies->display_price($new_price, tep_get_tax_rate($review['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id']));
  }

  if (tep_not_null($review['products_model'])) {
    $products_name = $review['products_name'];
  } else {
    $products_name = $review['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . 'product_reviews_info.php');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('product_reviews.php', tep_get_all_get_params()));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div id="reviewsInfoDefault" class="centerColumn">
	<div class="panel panel-default head-wrap">
		<div class="panel-heading">
			<h4 class="panel-title"><?php echo $products_name; ?></h4>
		</div>
	</div>
	<div class="content">
		<div class="row">
        	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 reviews-info-productmain-image img_ar">
            	<div id="reviewsInfoDefaultProductImage" class="centeredContent back">
					<div id="productMainImage" class="centeredContent back">
                    	<?php echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $review['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $review['products_image'], addslashes($review['products_name'])) . '</a>'; ?>
                    </div>
                </div>
            </div>
			<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 reviews-list des_ar">
            	<div class="reviews-description">
					<p class="ds_ns">
                    	<i class="fa fa-quote-left fa-lg"></i>
						<?php echo tep_break_string(nl2br(tep_output_string_protected($review['reviews_text'])
						), 70, '-<br />'); ?>
                    </p>
                    <footer>
                    	<strong><?php echo TEXT_REVIEW_BY; ?></strong>
						<?php echo tep_output_string_protected($review['customers_name']); ?>, 
						<?php echo tep_date_long($review['date_added']); ?><br/>
                        <?php echo sprintf(tep_image(DIR_WS_IMAGES . 'stars_' . $review['reviews_rating'] . '.gif', 
							sprintf(TEXT_OF_5_STARS,$review['reviews_rating'])), sprintf(TEXT_OF_5_STARS,$review['reviews_rating'])); ?>
                    </footer>
                    <div class="wk-price mt15">
						<?php echo $products_price; ?>
                	</div> 
                    <div class="forward productpage_links ds_ys">
						<div id="reviewsWriteProductPageLink" class="buttonRow mt15">
                        	<div class="cart-buttons ds_ns bkbc">
								<?php echo tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', tep_href_link(basename(
								$PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now')); ?>
                            </div>
                            <div class="cart-buttons bkbc">
								<?php echo tep_draw_button(IMAGE_BUTTON_WRITE_REVIEW, 'comment', 
                                tep_href_link('product_reviews_write.php', tep_get_all_get_params(array(
                                'reviews_id'))), 'primary'); ?>
                            </div>
                            <div class="cart-buttons">
                            	<?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link(
								'product_reviews.php', tep_get_all_get_params(array('reviews_id')))); ?>
                            </div>
                        </div>
                    </div>       
				</div>
            </div>
    	</div>
    </div>
</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
