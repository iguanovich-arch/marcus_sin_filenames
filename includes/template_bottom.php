<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>
</div> <!-- centercontent-wrapper end //-->
<?php /*========================================= Left Column ==================================================*/ ?>
<?php
if ((!isset($flag_disable_left) || !$flag_disable_left) && $oscTemplate->hasBlocks('boxes_column_left') ) {
	if($flag_disable_right == true) { ?>
	<div id="left-column" class="col-xs-12 col-sm-12 col-md-4 col-lg-3 <?php if($page_container==2){?> col-xl-2<?php } ?> pull-left leftColumn left columns col-sidebar">	
	<?php } else { ?>
	<div id="left-column" class="col-md-4 col-lg-3 <?php if($page_container==2){?> col-xl-2  col-xl-pull-8<?php } ?> pull-left leftColumn col-lg-pull-6 left columns col-sidebar">	
	<?php } ?>
		<?php echo $oscTemplate->getBlocks('boxes_column_left'); ?>
	</div>
<?php }	?>
<?php /*========================================= EOF Left Column ==================================================*/ ?>
<?php /*=========================================Right Column ==================================================*/ ?>
<?php
if ((!isset($flag_disable_right) || !$flag_disable_right) && $oscTemplate->hasBlocks('boxes_column_left')) {
	if($flag_disable_left == true) { ?>
	<div id="right-column" class="col-md-4 col-lg-3 <?php if($page_container==2){?> col-xl-2<?php } ?> hidden-xs hidden-sm pull-right rightColumn right columns col-sidebar">
	<?php } else { ?>
	<div id="right-column" class="col-md-4 col-lg-3 <?php if($page_container==2){?> col-xl-2<?php } ?> hidden-xs hidden-sm hidden-md pull-right rightcolumnwl rightColumn right columns col-sidebar">
	<?php } ?>
	<?php echo $oscTemplate->getBlocks('boxes_column_right'); ?>
	</div>
<?php } ?>
<?php /*=========================================EOF Right Column ==================================================*/ ?>
<?php if(!($is_this_homepage && $posc_page_layout=='1column')){ ?>
<?php if($flag_disable_left == false || $flag_disable_right == false){ ?>
</div><!-- row ends -->
<?php } ?>
</div><!-- container ends -->
</div><!-- contentarea-wrapper ends -->
<?php } ?>
<?php require(DIR_WS_INCLUDES .$footer_template.'.php'); ?>
</div> <!-- wrapper ends -->
<?php echo $oscTemplate->getBlocks('footer_scripts'); ?>
<?php if($display_newsletter_popup==1 && ((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) )){ ?>
	<?php include(DIR_WS_TEMPLATE . 'define_templates/define_newsletter_popup.php'); ?>
<?php } ?>
<p id="back-top" style="display: block;">
	<a href="#top"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</p>
<!-- JS files -->
<script type="text/javascript" src="ext/jquery/jquery.js"></script>
<script type="text/javascript" src="ext/jquery/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="ext/jquery/bootstrap.min.js"></script>
<script type="text/javascript" src="ext/jquery/bootstrap-dropdownhover.min.js"></script>
<script type="text/javascript" src="ext/jquery/jquery.easing.min.js"></script>
<script type="text/javascript" src="ext/jquery/wow.min.js"></script>
<script type="text/javascript" src="ext/jquery/owl.carousel.min.js"></script>
<script type="text/javascript" src="ext/posc_ajxcart/js/posc_ajxcart_functions.js"></script>
<?php include('ext/posc_ajxcart/js/posc_ajxcart.php'); ?>
<script type="text/javascript" src="ext/jquery/custom.js"></script>
<?php if (tep_not_null(JQUERY_DATEPICKER_I18N_CODE)) { ?>
<script type="text/javascript" src="ext/js/ui/i18n/jquery.ui.datepicker-<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>.js"></script>
<?php } ?>
</body>
</html>
