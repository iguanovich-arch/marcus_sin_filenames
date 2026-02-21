<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . 'shipping.php');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('shipping.php'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div id="privacy-wrapper">
	<div class="panel panel-default head-wrap">
		<div class="panel-heading mb30">
			<h4 class="panel-title"><?php echo HEADING_TITLE; ?></h4>
		</div>
	</div>
    <div class="contentContainer">
        <div class="contentText">
        	<?php echo TEXT_INFORMATION; ?>
        </div>
    </div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
