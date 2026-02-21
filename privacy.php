<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . 'privacy.php');
  $page_query = tep_db_query("select p.pages_id, s.pages_title, s.pages_html_text from " . TABLE_PAGES . " p LEFT JOIN " .TABLE_PAGES_DESCRIPTION . " s on p.pages_id = s.pages_id where s.language_id = '" . (int)$languages_id . "' and p.page_type = 3");
$page_check = tep_db_fetch_array($page_query);
$pagetext=stripslashes($page_check[pages_html_text]);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('privacy.php'));

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
        	<?php echo $pagetext; ?>
        </div>
    </div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
