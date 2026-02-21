<?php
ob_start();
require_once(DIR_WS_INCLUDES . 'posc_template_top.php');
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/
	
$oscTemplate->buildBlocks();
if (!$oscTemplate->hasBlocks('boxes_column_left')) {
	$oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
}
if (!$oscTemplate->hasBlocks('boxes_column_right') ) {
	$oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
}

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php if($file_favicon){ ?>
<link rel="icon" href="<?php echo TEMPLATE_IMG_DIR.'uploads/'.$file_favicon; ?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo TEMPLATE_IMG_DIR.'uploads/'.$file_favicon; ?>" type="image/x-icon" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="ext/jquery/fancybox/jquery.fancybox-1.3.4.css" />
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>jquery.mmenu.all.css" />
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>animate.min.css" />
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>merge-style.css" />
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>style.css" />
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>device.css" />
<link rel="stylesheet" type="text/css" href="ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>style_user_custom.css" />
<link rel="stylesheet" type="text/css" href="ext/posc_ajxcart/css/posc_ajxcart.css" />
<script type="text/javascript" src="ext/jquery/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="ext/jquery/jquery.elevatezoom.js"></script>
<script type="text/javascript" src="ext/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="ext/jquery/ui/jquery-ui-1.10.4.min.js"></script>
<?php 
if(file_exists(DIR_WS_TEMPLATE . 'define_templates/define_demo_config.php')){
	require(DIR_WS_TEMPLATE . 'define_templates/define_demo_config.php');
} ?>
<?php require(DIR_WS_MODULES . 'tpl_template_custom_css.php'); ?>
<?php echo $oscTemplate->getBlocks('header_tags'); ?>
</head>
<body class="<?php echo $body_classes; ?>">
	<!-- loader -->
	<?php if($page_loader!='none'){?>
		<?php if(($page_loader=="default") || ($page_loader=='custom' && $page_loader_custom=='')){ ?>
		<div id="preloader" class="loader-wrapper">
			<div id="loading" class="loader load-bar"></div>
		</div>
		<?php } else{ ?>
		<div id="preloader" class="loader-wrapper">
			<div id="loader" class="loader-custom">
				<img src="<?php echo $uploads_path.$page_loader_custom; ?>" width="auto" height="auto" alt="loader"/>
			</div>
		</div>
		<?php } ?>
	<?php } ?>
	<!-- /loader -->
	<?php require(DIR_WS_TEMPLATE . 'common/tpl_drop_menu_mobile.php'); ?>
	<?php require(DIR_WS_TEMPLATE . 'common/tpl_posc_ajxcart_mobile.php'); ?>
	<!-- maincontent-wrapper -->
	<div class="wrappper">
		<?php require(DIR_WS_INCLUDES .$header_template.'.php'); ?>
    <!-- Condition check for breadcrumb -->
	<?php if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) { ?> 
        <?php }else { 
            $newabc = $breadcrumb->trail();
            $newabc1=explode(' > ',$newabc);
            unset($newabc1[0]);
            $aarl1=implode(' > ',$newabc1);
            $newstring = str_replace("Catalog", "Home", $aarl1);
			$newstring1 = str_replace("Catalog", "", $aarl1);
            $abcstring=str_replace(' > ','',$newstring);
			$abcstring1=str_replace(' > ','',$newstring1);
        ?> 
        <!-- Breadcrumb Wrapper Starts -->
        <div class="breadcrumb"> 
            <div class="container">
                <div class="row">
					<div class="col-md-8 col-sm-7 col-xs-12"><ol><?php echo $abcstring; ?></ol></div>
					<div class="col-md-4 col-sm-5 col-xs-12 text-right"><h2><?php echo $abcstring1; ?></h2></div>
                </div>
            </div> 
        </div> 
        <!-- Breadcrumb Wrapper Ends -->
    <?php  }?>  
    <!-- Container Wrapper Starts -->
	<?php if(!($is_this_homepage && $posc_page_layout=='1column')){ ?>
			<div id="contentarea-wrapper">
				<div class="container"> 
		<?php } ?>
					<?php if($flag_disable_left == true && $flag_disable_right == true ) { ?>
					<div id="centercontent-wrapper" class="single-column">
					<?php } elseif($flag_disable_left == true) { ?> 
					<div class="row">
						<div id="centercontent-wrapper" class="col-xs-12 col-sm-12 col-md-8 col-lg-9 <?php if($page_container==2){?> col-xl-10<?php } ?> columnwith-right centerColumn pull-left"> 
					<?php } elseif($flag_disable_right == true) { ?> 
					<div class="row">
						<div id="centercontent-wrapper" class="col-xs-12 col-sm-12 col-md-8 col-lg-9 <?php if($page_container==2){?>col-xl-10<?php } ?> columnwith-left centerColumn pull-right">
					<?php }else { $class_name = 'three-columns'; ?> 
					<div class="row">
						<div id="centercontent-wrapper" class="col-md-8 col-lg-6 <?php if($page_container==2){?> col-xl-8 col-xl-push-2<?php } ?> noleft-margin two-column centerColumn col-lg-push-3">
					<?php } ?>
					 <!-- if condition for Breadcrumb Ends -->
					<?php if($is_this_homepage) { ?>
						<?php include(DIR_WS_TEMPLATE .'html_includes/define_'.$homepage_template.'.php'); ?>
					<?php } ?>
					<!-- Main Wrapper Starts --> 
