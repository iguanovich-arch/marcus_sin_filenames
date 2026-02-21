<?php 
/**
 * Posc AjxCart for Zen Cart.
 *
 * @copyright Copyright 2017 Perfectus Inc.
 * Version : Posc AjxCart 1.1
 */
?>
<?php
define('FILENAME_POSC_AJX_CART', 'posc_ajx_cart.php');
define('FILENAME_POSC_AJXCART', 'posc_ajxcart.php');
define('FILENAME_DEFINE_POSC_AJXCART', 'posc_ajxcart');

//POSC AJAX CART
include(DIR_WS_LANGUAGES . $language . '/posc_ajx_cart.php');
require_once(DIR_WS_FUNCTIONS . 'posc_ajxcart_functions.php');
require_once(DIR_WS_FUNCTIONS . 'posc_template_ajxcart_functions.php');