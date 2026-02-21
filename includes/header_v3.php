<?php
	if ($messageStack->size('header') > 0) {
		echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
	}
	
	if ( file_exists(DIR_WS_MODULES.'cat_navbar.php') ) {
		require_once(DIR_WS_MODULES.'cat_navbar.php');
    }

?>
<!-- Header Container Starts -->
<header class="index3-header">
	<?php require(DIR_WS_TEMPLATE . 'common/tpl_mobile_header.php'); ?>
	<?php if($is_this_homepage){ ?>
	<!-- Menu starts -->
	<div class="main-menu hidden-xs hidden-sm">
		<nav id="mainNav" class="navbar navbar-default navbar-fixed-top affix-top">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="col-xs-3 col-sm-3">
					<a class="navbar-brand"><img alt="logo" src="ext/images/menu_icon.png"></a>
				</div>
				<div class="col-xs-6 col-sm-6 text-center">
					<?php if($file_logo != NULL){?>
					<a href="<?php echo tep_href_link('index.php') ?>">
						<img class="logo3" src="<?php echo TEMPLATE_IMG_DIR.'uploads/'.$file_logo; ?>" alt="logo" />
					</a>
					<?php } ?>
				</div>
				<div class="col-xs-3 col-sm-3">
					<div data-animations=" fadeInLeft fadeInUp fadeInRight" data-hover="dropdown" id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="header-search action">
								<a class="search-hand" href="javascript:void(0);"><span class="search-icon"><i class="material-icons"></i></span><span class="search-close"><i class="material-icons"></i></span></a>
							</li>
							<li class="cart">
								<a href="<?php echo tep_href_link('shopping_cart.php') ?>" class="dropdown-toggle cart-flyout mposc-ajxcart-action" data-toggle="dropdown"><span><i class="material-icons"></i></span> <span class="subno poscMiniCartCount" data-effect="mposc-ajxcart-block"><?php echo $cart->count_contents(); ?></span></a>
								<?php /*<div class="poscAjxCart poscMiniCartContent poscajx-minicart dropdown-menu cart-outer">
									<?php echo poscAjxMinicart(); ?>
								</div> */ ?>
							</li>
						</ul>
						<?php require(DIR_WS_TEMPLATE . 'define_templates/define_header_search.php'); ?>
						<!-- /.navbar-collapse -->
					</div>
				</div>
			</div>
			<div class="nav-click" style="display: none;">
				<div class="container">
					<nav id="mainNav" class="navbar navbar-inverse navbar-default mainmenu-nav">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<a class="navbar-brand2">x</a>
						</div>
						<?php require(DIR_WS_TEMPLATE . 'common/tpl_drop_menu.php'); ?>
					</nav>
				</div>
			</div>
		</nav>
	</div>
	<!-- Menu Ends Here -->
	<?php }else{ ?>
	<!-- Menu starts -->
	<div class="inn-top-nav">
		<div class="main-menu hidden-xs hidden-sm">
			<nav id="mainNav" class="navbar navbar-default navbar-fixed-top mainmenu-nav affix-top">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<?php if($file_logo != NULL){?>
						<a class="navbar-brand" href="<?php echo tep_href_link('index.php') ?>">
							<img src="<?php echo TEMPLATE_IMG_DIR.'uploads/'.$file_logo; ?>" alt="logo" />
						</a>
						<?php } ?>  
					</div>
					<?php require(DIR_WS_TEMPLATE . 'common/tpl_drop_menu.php'); ?>
				</div>
			</nav>
		</div>
	</div>
	<!-- Menu Ends Here -->
	<?php } ?>
	<!-- Slider Starts -->
	<?php	if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) { ?>
	<section class="header-outer">
		<?php include(DIR_WS_TEMPLATE . 'define_templates/define_main_slider.php'); ?>
	</section>
	<?php } ?>
	<!-- Slider Ends Here -->
</header>
<!-- Header Container Ends -->
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>
