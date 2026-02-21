<?php
#POSC_TEMPLATE_BASE#

define('FILENAME_POSC_TEMPLATE', 'posc_template');
define('FILENAME_POSC_QUICKVIEW', 'posc_quickview.php');
define('TABLE_POSC_TEMPLATE', 'posc_template');
define('TABLE_POSC_TOPBANNER', 'posc_topbanner');
define('TABLE_POSC_BOTTOMBANNER', 'posc_bottombanner');
define('TABLE_POSC_MAINSLIDER', 'posc_mainslider');
define('DIR_WS_TEMPLATE', DIR_WS_INCLUDES.'posc_template/');
define('TEMPLATE_IMG_DIR', DIR_WS_IMAGES.'posc_template/');

require_once(DIR_WS_FUNCTIONS . 'posc_template.php');
include(DIR_WS_LANGUAGES . $language . '/posc_template.php');
require(DIR_WS_INCLUDES . 'posc_filenames.php');
global $posc_temp_data, $pzen_menu, $category_depth;
global $PHP_SELF, $currencies, $HTTP_GET_VARS, $request_type, $currency, $oscTemplate, $posc_page_layout, $flag_disable_left, $flag_disable_right, $is_this_homepage;
//get temlate data
$posc_temp_data = get_posc_template_data();
//set languages
if (!isset($lng) || (isset($lng) && !is_object($lng))) {
	include(DIR_WS_CLASSES . 'language.php');
	$lng = new language;
}
//set currency
if (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1)) {
	reset($currencies->currencies);
	$currencies_array = array();
	while (list($key, $value) = each($currencies->currencies)) {
		$currencies_array[] = array('id' => $key, 'text' => $value['title']);
	}
}

require_once(DIR_WS_TEMPLATE . 'common/tpl_template_settings.php');

require_once(DIR_WS_INCLUDES . 'posc_ajxcart_datafiles.php');

$is_this_homepage = false;
//columns settings
$posc_page_layout = '';
if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) {
	$is_this_homepage = true;
	$body_classes .= ' hm-pg';
	$posc_page_layout = $home_page_layout;
}else if((basename($PHP_SELF) == 'index.php' && ($_GET['cPath'] != '') && $category_depth=='nested')){
	$posc_page_layout = $cat_page_layout;
}elseif ($category_depth == 'products' || (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id']))){
	$posc_page_layout = $prodlist_page_layout;
}else if(basename($PHP_SELF) == 'product_info.php'){
	$posc_page_layout = $prodinfo_page_layout;
	$body_classes .= ' info-pg';
}else if(in_array(basename($PHP_SELF), array('shopping_cart.php', 'login.php', 'create_account.php'))){
	$posc_page_layout = '1column';
}else{
	$posc_page_layout = $general_page_layout;
}
$flag_disable_left = $flag_disable_right = false;
if($posc_page_layout=='2columns-left'){
	$flag_disable_left = false;
	$flag_disable_right = true;
}else if($posc_page_layout=='2columns-right'){
	$flag_disable_left = true;
	$flag_disable_right = false;
}else if($posc_page_layout=='3columns'){
	$flag_disable_left = false;
	$flag_disable_right = false;
}else if($posc_page_layout=='1column'){
	$flag_disable_left = true;
	$flag_disable_right = true;
}

if(!$is_this_homepage){
	$body_classes .= ' inn-pg';
}
?>
