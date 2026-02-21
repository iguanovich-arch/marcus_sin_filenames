<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

	require('includes/application_top.php');
	
	require(DIR_WS_LANGUAGES . $language . '/' . 'products_new.php');

	$breadcrumb->add(NAVBAR_TITLE, tep_href_link('products_new.php'));

	require(DIR_WS_INCLUDES . 'template_top.php');
?>
<?php 
$grid_list_class='';
if(!$flag_is_grid) { $grid_list_class = "row-view list-shop"; }
?>
<?php echo $gridlist_tab=posc_gridlist_tab('products_new.php'); ?>
<div class="products-container grid-shop new-arrivals <?php echo $grid_list_class; ?>">
	<div class="row">
		<div class="productslist-grid products-grid-list" <?php if($flag_is_grid){ echo  $prodgrid_data; } ?>>
		<?php
  			$products_new_array = array();
  			$products_new_query_raw = "select p.products_id, pd.products_name,pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_model, p.products_weight, 
			m.manufacturers_name, f.featured_id, s.specials_id from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";
  			$products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);
  			if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) { ?>
				<div>
					<span style="float: right;"><?php echo TEXT_RESULT_PAGE . ' <span class="pagination-style">' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, 
						tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></span>
					</span>
					<span><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?></span>
				</div>
			<?php }
			
  			if ($products_new_split->number_of_rows > 0) {
				$content = '';
				$products_new_query = tep_db_query($products_new_split->sql_query);
    			while ($product_list = tep_db_fetch_array($products_new_query)) {
					$products_description = $product_list['products_description'];
						$products_description_short = ltrim(substr($products_description, 0, 170) . '...'); //Trims and Limits the desc	
						
						if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
							$products_link = tep_href_link('product_info.php', 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $product_list['products_id']);
						} else {
							$products_link = tep_href_link('product_info.php', ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $product_list['products_id']);
						}
						
						$product_content = get_posc_product_content($product_list, 'list');
						
						if($flag_is_grid){
							$content .= '
							<div class="product-item '.$prodgrid_item_class.'">
								<div class="product-wrapper">
									<div class="pro-text">
										<div class="pro-img">
											<a href="' .$products_link. '">
												'.$product_content['products_image']. '
											</a>
											'.(($product_content['products_label'])? '<div class="new-tag">'.$product_content['products_label'].'</div>' : '').'
										</div>
										<div class="pro-text-outer">
											<a href="' . $products_link . '"><h4>'. $product_list['products_name'] . '</h4></a>
											<div class="wk-price">
												' .$product_content['products_price'].'
												<div class="in-stock mt5">'. $product_content['products_review'] .'</div>
											</div>
											<div class="prod_extra_info">
												'.((PRODUCT_LIST_BUY_NOW !=0) ? '<div class="add-btn">'.$product_content['buy_now'].'</div>' : '').'
												<a class="eys-btn" title="Product Detail" href="' . $products_link . '"><i class="material-icons"></i></a>
												'.$product_content['products_quickview'].'
											</div>
										</div>
									</div>
								</div>
							</div>
							';
						}else{
							$content .= '
								<div class="item product-item ct_dt">
									<div class="pro-text">
										<div class="col-xs-12 col-sm-5 col-lg-4 '.(($page_container==2) ? 'col-xl-3' : 'col-xl-4').' col-md-5 img_ar">
											<div class="pro-img">
												<a href="' .$products_link. '">
													'.$product_content['products_image']. '
												</a>
												'.(($product_content['products_label'])? '<div class="new-tag">'.$product_content['products_label'].'</div>' : '').'
											</div>
										</div>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 '.(($page_container==2) ? 'col-xl-9' : 'col-xl-8').' des_ar">
											<div class="pro-text-outer list-pro-text ct_lst">
												<a href="' . $products_link . '"><h4>'. $product_list['products_name'] . '</h4></a>
												<p class="wk-price">' .$product_content['products_price'].'</p>
												<div class="star2">'. $product_content['products_review'] .'</div>
												<div class="excerpt hidden-xs">'. $products_description_short. '</div>
												<div class="product_extra_attributes">';
													if($product_list['products_model']!=NULL) {
														$content .= '<div>Model : '  .$product_list['products_model']. '</div>';
													}
													if($product_list['products_weight']!=0) {
														$content .= '<div>Weight : '  .$product_list['products_weight'].'</div>';
													}
													if($product_list['manufacturers_name']!=NULL) {
														$content .= '<div>Manufacturer : '  .$product_list['manufacturers_name'].'</div>';
													}
													if($product_list['products_quantity']!=0) {
														$content .= '<div>Qty : ' . $product_list['products_quantity'] . '</div>';
													}			
												$content .='
												</div>
												<div class="prod_extra_info">
													<div class="add-btn">'.((PRODUCT_LIST_BUY_NOW != 0) ? '<div class="add-btn">'.$product_content['buy_now'].'</div>' : '').'</div>
													<a class="eys-btn" title="Product Detail" href="' . tep_href_link('product_info.php', 'products_id=' . $product_list['products_id']) . '"><i class="material-icons"></i></a>
													'.$product_content['products_quickview'].'
												</div>
											</div>
										</div>
									</div>
								</div>
								';
						}
					}
					echo $content;
			}else{ ?>
			<div class="alert alert-danger mt15"><?php echo TEXT_NO_NEW_PRODUCTS; ?></div>
			<?php } ?>
		</div>
    </div>
</div>
<?php if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) { ?>
<div class="pageresult_bottom">
	<div class="product-page-count">
		<div class="navSplitPagesResult">
			<?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?>
		</div>
	</div>
	<div class="navSplitPagesLinks pagination-style"><?php echo $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
</div>
<?php }?>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
