<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_REVIEWS));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>



<div id="reviewsListingDefault" class="centerColumn">

<?php
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and rd.languages_id = '" . (int)$languages_id . "' and reviews_status = 1 order by r.reviews_id DESC";
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>

  <div class="contentText">
    <p style="float: right;"><?php echo TEXT_RESULT_PAGE . ' <span class="pagination-style">' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></span></p>

    <p><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></p>
  </div>

  <br />

<?php
    } ?>
	<div class="panel panel-default head-wrap">
		<div class="panel-heading">
			<h4 class="panel-title"><?php echo HEADING_TITLE; ?></h4>
		</div>
	</div>
    <div class="reviews-list">
    <?php
    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
	
    
    	<div class="content">
        	<div class="row product-details-review">
                <div class="smallProductImage back col-lg-3 col-md-3 col-sm-3 col-xs-12 img_ar">
                	<?php echo '<a href="' . tep_href_link('product_reviews_info.php', 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $reviews['products_image'], $reviews['products_name']) . '</a>'; ?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 product-review-default des_ar">
                	<h4><?php echo '<a href="' . tep_href_link('product_reviews_info.php', 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . $reviews['products_name'] . '</a>'; ?></h4>
                    <p class="ds_ns"><i class="fa fa-quote-left"></i><?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 55, '-<br />') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : ''); ?></p>
                    <footer>
                    	<strong><?php echo TEXT_REVIEW_BY;?></strong>
                        <?php echo tep_output_string_protected($reviews['customers_name']);?>, 
						<?php echo tep_date_long($reviews['date_added']);?><br/>
                        <?php echo sprintf(tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS,$reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS,$reviews['reviews_rating']))?>
                    </footer>
                </div>
 			</div>
        </div>
<?php
    }?>
         </div>

  <?php } else {
?>

  <div class="contentText">
    <?php echo TEXT_NO_REVIEWS; ?>
  </div>

<?php
  }

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

  <br />

    <div class="pageresult_bottom">
        <div class="product-page-count">
            <div class="navSplitPagesResult">
                <?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>
            </div>
        </div>
        <div class="navSplitPagesLinks pagination-style">
            <?php echo $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params
            (array('page', 'info'))); ?>
        </div>
    </div>

<?php
  }
?>

</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
