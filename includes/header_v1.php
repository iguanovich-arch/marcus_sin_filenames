<?php
	if ($messageStack->size('header') > 0) {
		echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
	}
	
	if ( file_exists(DIR_WS_MODULES.'cat_navbar.php') ) {
		require_once(DIR_WS_MODULES.'cat_navbar.php');
    }

?>
<!-- Header Container Starts -->
<?php 
if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) {
	$header_class ='header-6';
}else{
	$header_class ='header-outer6 header-outer';
}?>
<header class="<?php echo $header_class; ?>">
	<?php require(DIR_WS_TEMPLATE . 'common/tpl_mobile_header.php'); ?>
	<!-- Menu starts -->
	<div class="main-menu hidden-xs hidden-sm">
		<nav id="mainNav" class="navbar navbar-default navbar-fixed-top affix-top mainmenu-nav">
			<div class="top-header">
				<div class="container">
					<div class="row">
						<div class="col-lg-6">
							<div class="top-header-left">
								<ul>
									<?php if($header_store_contact){ ?><li><span class="material-icons">call</span> <?php echo $header_store_contact; ?> </li><?php } ?>
									<?php if($header_store_time){ ?><li><span class="material-icons">query_builder</span> <?php echo $header_store_time; ?> </li><?php } ?>
								</ul>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="top-header-right">
								<ul class="top-header-rlink"> 
								<?php if(sizeof($currencies_array) > 1){ ?>
								<li class="currency dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown">
										<?php echo $_SESSION['currency']; ?><span class="caret"></span>
									</a>
									<ul class="dropdown-menu dropdown-menu-full">
										<?php require(DIR_WS_TEMPLATE . 'common/tpl_header_currencies.php'); ?>
									</ul>
								</li>
								<?php } ?>
								<?php if (count($lng->catalog_languages) > 1) { ?>
								<li class="language dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown">
										<?php echo $_SESSION['language']; ?><span class="caret"></span>
									</a>
									<ul class="dropdown-menu dropdown-menu-full">
										<?php require(DIR_WS_TEMPLATE . 'common/tpl_header_languages.php'); ?>
									</ul>
								</li>
								<?php } ?>
								<?php require(DIR_WS_TEMPLATE . 'define_templates/define_top_links.php'); ?>
								</ul>
								<div class="scoial-footer">
									<ul>
										<?php if($facebook_link){?> <li><a class="fac" target="_blank" href="https://www.facebook.com/<?php echo $facebook_link; ?>"><i class="fa fa-facebook"></i></a> </li><?php } ?>
										<?php if($twitter_link){?><li><a class="twi" target="_blank" href="https://twitter.com/<?php echo $twitter_link; ?>"><i class="fa fa-twitter"></i></a></li><?php } ?>
										<?php if($google_link){ ?><li><a class="goo" target="_blank" href="<?php echo $google_link; ?>"><i class="fa fa-google-plus"></i></a></li><?php } ?>
										<?php if($pinterest_link){ ?><li><a class="pin" target="_blank" href="https://www.pinterest.com/<?php echo $pinterest_link; ?>"><i class="fa fa-pinterest"></i></a></li><?php } ?>
										<?php if($instagram_link){ ?><li><a class="insta" target="_blank" href="https://www.instagram.com/<?php echo $instagram_link; ?>"><i aria-hidden="true" class="fa fa-instagram"></i></a> </li><?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container mid-header">
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
	<!-- Menu Ends Here -->
	<!-- Slider Starts -->
	<?php	if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) { ?>
	<section class="header-outer header-outer6">
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
