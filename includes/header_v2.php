<?php
	if ($messageStack->size('header') > 0) {
		echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
	}
	
	if ( file_exists(DIR_WS_MODULES.'cat_navbar.php') ) {
		require_once(DIR_WS_MODULES.'cat_navbar.php');
    }

?>
<!-- Header Container Starts -->
<header class="header2 header3">
	<?php require(DIR_WS_TEMPLATE . 'common/tpl_mobile_header.php'); ?>
	<!-- Menu starts -->
	<div class="main-menu hidden-xs hidden-sm">
		<nav id="mainNav" class="navbar navbar-default navbar-fixed-top affix mainmenu-nav">
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
	<!-- Menu Ends Here -->
	<!-- Slider Starts -->
	<?php	if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) { ?>
	<section class="header-outer header-outer2">
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
