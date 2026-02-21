<?php 
/**
 * Posc AjxCart for Zen Cart.
 * WARNING: Do not change this file. Your changes will be lost.
 *
 * @copyright Copyright 2017 Perfectus Inc.
 * Version : Posc AjxCart 1.6
 */
?>
<?php
if(isset($_POST['products_id']) && isset($_POST['products_id'])!=''){
	$_POST['prods_id']=$_POST['products_id'];
	unset($_POST['products_id']);
}
if(isset($_POST['id']) && isset($_POST['id'])!=''){
	$_POST['aid']=$_POST['id'];
	unset($_POST['id']);
}
require ('includes/application_top.php');
require_once(DIR_WS_INCLUDES . 'posc_ajxcart_datafiles.php');
$minicart=$shippingEstimator='';
$messages=$messages_ar=array();
$pro_is_ar=false;
$lang_dir = DIR_WS_LANGUAGES . $_SESSION['language'] . '/';
	if(((isset($_POST['posc_action']) && isset($_POST['posc_action'])!='') && (isset($_POST['prods_id']) && isset($_POST['prods_id'])!='')) || (isset($_POST['qty']) && isset($_POST['qty'])!='')) 
	{
		$action=$raction= $_POST['posc_action'];
		$products_id = $_POST['prods_id'];
		$qty        = $_POST['qty'];
		
		if(in_array($action, array('add', 'prodinfo-add', 'multiprod-add'))){
			if(is_array($products_id)){
				$messages=posc_multi_addcart($products_id, $action);
			}else{
				$attr = '';
				if(isset($_POST['aid']) && is_array($_POST['aid']))
				{
					$attr = array();
					foreach($_POST['aid'] as $k=>$v) $attr[$k] = $v;
				}
				$messages['msg']=posc_addpro_cart($products_id, $qty, $attr, $action);
			}
		}else if($action=='remove' || $action=='cart-remove'){
			$_SESSION['cart']->remove($products_id);
			$messages["rid"]=$products_id;
			$messages['msg']=array('status'=>'success','message'=>'<ul class="messages"><li class="success-msg"><ul><li><span>'.POSC_AJXCART_SUCCESS_REMOVED.'</span></li></ul></li></ul>');
		}else if(in_array($action, array('multicart-update', 'cart-update'))){
			if(is_array($products_id)){
				$attr = '';
				$qty         = $_POST['cart_quantity'];
				$suid        = (isset($_POST['suid']) && $_POST['suid'] !='') ? $_POST['suid'] : '';
				if(isset($_POST['aid']) && is_array($_POST['aid']))
				{
					$attr = array();
					foreach($_POST['aid'] as $k=>$v) $attr[$k] = $v;
				}
				$messages=posc_multi_updatecart($products_id, $suid, $qty, $attr, $action);
			}
		}else if($action=='update'){
			$messages['msg']=posc_addpro_cart($products_id, intval($qty), '', $action);
		}
		
		if($messages['msg']['status']=='success'){
			if($action!='remove' ){
				$messages = posc_ajxcart_popup($products_id, $messages);
			}else{
				$messages['popcontent']=($messages['msg']['message']);
			}
			
			//minicart
			$messages['minicart']=poscAjxMinicart();

		}else{
			$messages['popcontent']=($messages['msg']['message']);
		}
		
		//ajax contents
		$_SESSION['cart']->calculate();
		require($lang_dir.'shopping_cart.php');
		$mainTotals = "";
		$shipping_weight = $_SESSION['cart']->weight;
		$cart_total = $_SESSION['cart']->show_total();
		switch (true) {
			case (SHOW_TOTALS_IN_CART == '1'):
			$mainTotals = TEXT_TOTAL_ITEMS . $_SESSION['cart']->count_contents() . TEXT_TOTAL_WEIGHT . $shipping_weight . TEXT_PRODUCT_WEIGHT_UNIT . TEXT_TOTAL_AMOUNT . $currencies->format($cart_total);
			break;
			case (SHOW_TOTALS_IN_CART == '2'):
			$mainTotals = TEXT_TOTAL_ITEMS . $_SESSION['cart']->count_contents() . ($shipping_weight > 0 ? TEXT_TOTAL_WEIGHT . $shipping_weight . TEXT_PRODUCT_WEIGHT_UNIT : '') . TEXT_TOTAL_AMOUNT . $currencies->format($cart_total);
			break;
			case (SHOW_TOTALS_IN_CART == '3'):
			$mainTotals = TEXT_TOTAL_ITEMS . $_SESSION['cart']->count_contents() . TEXT_TOTAL_AMOUNT . $currencies->format($cart_total);
			break;
		}
		
		$messages["cartcontent"]=array('cartCount' => $_SESSION['cart']->count_contents(), 'cartTotal'=> $cart_total, 'subTotal' => sprintf(POSC_CARTPAGE_SUBTOTAL, $currencies->format($cart_total)), 'mainTotals' => $mainTotals);
		
		if(SHOW_SHIPPING_ESTIMATOR_BUTTON == '2' && in_array($action, array('multicart-update', 'cart-update', 'cart-remove'))) {
			ob_start();
			require_once(DIR_WS_MODULES . zen_get_module_directory('shipping_estimator.php'));
			$shippingEstimator = ob_get_clean();
			if($shippingEstimator){
				$messages["cartcontent"]["shippingEstimator"] = $shippingEstimator;
			}
		}
		
		if($raction=='cart-remove'){
			unset($messages['popcontent']);
		}
		if(in_array($raction, array('multicart-update', 'cart-update'))){
			$cart_uproduct=posc_get_cart_list();
			if(!empty($cart_uproduct)){
				$messages["cartuproduct"]=$cart_uproduct;
			}
		}
		$messages['pop_timer'] = POSC_AJXCART_POPUP_TIMEOUT;
		
	}else{
		$messages['msg']=array('status'=>'error','message'=>POSC_AJXCART_ERROR_TRY_AGAIN);
	}
	
	if(!empty($messages)){
		echo json_encode($messages);
	}else{
		echo POSC_AJXCART_ERROR_TRY_AGAIN;
	}
	
ob_start();
require(DIR_WS_INCLUDES.'application_bottom.php');
ob_end_clean();
?>