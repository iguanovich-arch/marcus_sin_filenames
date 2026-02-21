<?php
/*
  $Id: manufacturers.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// check if the catalog video directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

// populate $products_array with available product model names
  $products_array = array(array('id' => '', 'text' => TEXT_NONE));
  $products_query = tep_db_query("SELECT p.products_id, pd.products_id, CONCAT(cd.categories_name, ' -- ', pd.products_name) products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES_DESCRIPTION . " cd WHERE pd.products_id = p2c.products_id AND cd.categories_id = p2c.categories_id AND pd.products_id = p.products_id AND p.products_status = '1' ORDER BY cd.categories_name, pd.products_name");
  while ($products = tep_db_fetch_array($products_query)) {
    $products_array[] = array('id' => $products['products_id'],
                              'text' => $products['products_name']);
//  $i++;
  }

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        $sql_data_array = array('products_id' => tep_db_prepare_input($_POST['products_id']));
        if (isset($_FILES['pei_file']) && $_FILES['pei_file']['name'] != '') {
        //upload files to the videos folder on the server; if SPECIAL_VIDEO_PATH is filled then it will upload the file to that subfolder path. NOTE: make sure that subfolder path exists on the server e.g. if special_VIDEO_PATH is set to "subfolderA/" then the video will be uploaded to "videos/subfolderA/"
          $extra_video = new upload('pei_file', DIR_FS_CATALOG_IMAGES.$_POST['SPECIAL_VIDEO_PATH']);
          $sql_data_array = array_merge($sql_data_array, array('products_extra_video' => tep_db_prepare_input($_POST['SPECIAL_VIDEO_PATH'].$_FILES['pei_file']['name'])));
          // more fields -  addition
          if (isset($_FILES['pei_file1']) && $_FILES['pei_file1']['name'] != '') {
            $extra_video = new upload('pei_file1', DIR_FS_CATALOG_VIDEOS.$_POST['SPECIAL_VIDEO_PATH']);
            $sql_data_array1 = array_merge($sql_data_array, array('products_extra_video' => tep_db_prepare_input($_POST['SPECIAL_VIDEO_PATH'].$_FILES['pei_file1']['name'])));
          }

          if (isset($_FILES['pei_file2']) && $_FILES['pei_file2']['name'] != '') {
            $extra_video = new upload('pei_file2', DIR_FS_CATALOG_VIDEOS.$_POST['SPECIAL_VIDEO_PATH']);
            $sql_data_array2 = array_merge($sql_data_array, array('products_extra_video' => tep_db_prepare_input($_POST['SPECIAL_VIDEO_PATH'].$_FILES['pei_file2']['name'])));
          }

          if (isset($_FILES['pei_file3']) && $_FILES['pei_file3']['name'] != '') {
            $extra_video = new upload('pei_file3', DIR_FS_CATALOG_VIDEOS.$_POST['SPECIAL_VIDEO_PATH']);
            $sql_data_array3 = array_merge($sql_data_array, array('products_extra_video' => tep_db_prepare_input($_POST['SPECIAL_VIDEO_PATH'].$_FILES['pei_file3']['name'])));
          }
          // end of more fields - addition
        } else {//OPTION 2 Already uploaded the file and want to update the video using path to the video file on the server from the "videos/" folder e.g. if video.jpg file is in "subfolderA" then the path is "subfolderA/video.jpg"
          if (isset($_POST['pei_file']) && $_POST['pei_file']!= '') {
		    $sql_data_array = array_merge($sql_data_array, array('products_extra_video' => tep_db_prepare_input($_POST['pei_file'])));
		  }
		  if (isset($_POST['pei_file1']) && $_POST['pei_file1']!= '') {
		    $sql_data_array1 = array_merge($sql_data_array, array('products_extra_video' => tep_db_prepare_input($_POST['pei_file1'])));
		  }
		  if (isset($_POST['pei_file2']) && $_POST['pei_file2']!= '') {
		    $sql_data_array2 = array_merge($sql_data_array, array('products_extra_video' => tep_db_prepare_input($_POST['pei_file2'])));
		  }
        }
        if ($action == 'save') {
          tep_db_perform(TABLE_PRODUCTS_EXTRA_VIDEOS, $sql_data_array, 'update', 'products_extra_videos_id=' . tep_db_input($_GET['pId']));
	    } else {
          if (isset($_POST['pei_file']) && $_POST['pei_file']!= '') {
            tep_db_perform(TABLE_PRODUCTS_EXTRA_VIDEOS, $sql_data_array, 'insert');
          }
          // more fields -  addition
          if (isset($_POST['pei_file1']) && $_POST['pei_file1']!= '') {
            tep_db_perform(TABLE_PRODUCTS_EXTRA_VIDEOS, $sql_data_array1, 'insert');
          }
          if (isset($_POST['pei_file2']) && $_POST['pei_file2']!= '') {
            tep_db_perform(TABLE_PRODUCTS_EXTRA_VIDEOS, $sql_data_array2, 'insert');
          }
          // end of more fields -  addition
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $_GET['page'] . '&pId=' . $_GET['pId']));
      break;
      case 'delete':
        $sql_data_array = array('products_extra_videos_id' => tep_db_prepare_input($_GET['pId']));
        tep_db_query("DELETE FROM " . TABLE_PRODUCTS_EXTRA_VIDEOS . " WHERE products_extra_videos_id=" . $_GET['pId']);
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $_GET['page']));
      break;
    }
  }
  
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<!--[if IE]><script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/excanvas.min.js', '', 'SSL'); ?>"></script><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo tep_catalog_href_link('ext/jquery/ui/redmond/jquery-ui-1.10.4.min.css', '', 'SSL'); ?>">
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/jquery-1.11.1.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/ui/jquery-ui-1.10.4.min.js', '', 'SSL'); ?>"></script>

<?php
  if (tep_not_null(JQUERY_DATEPICKER_I18N_CODE)) {
?>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/ui/i18n/jquery.ui.datepicker-' . JQUERY_DATEPICKER_I18N_CODE . '.js', '', 'SSL'); ?>"></script>
<script type="text/javascript">
$.datepicker.setDefaults($.datepicker.regional['<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>']);
</script>
<?php
  }
?>

<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.time.min.js', '', 'SSL'); ?>"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/general.js"></script>
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (empty($action)) {
?>
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="smallText"><?php echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pInfo->products_extra_videos_id . '&action=new') . '">' . tep_image_button('button_insert.gif', VIDEO_INSERT) . '</a>'; ?></td>
      </tr>
<?php
  }
?>
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PRODUCTS_VIDEO; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PRODUCTS_NAME; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PRODUCTS_VIDEO_PATH; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>
<?php
  $page = $_GET['page'];
  if (!$page) $page = 1;
  $pId = $_GET['pId'];
  if (!$pId) unset($pId);
  $products_extra_videos_query_raw = "select pei.products_extra_video, pei.products_extra_videos_id, pei.products_id, pd.products_name,p.products_image from " . TABLE_PRODUCTS_EXTRA_VIDEOS . " pei left join " . TABLE_PRODUCTS . " p ON pei.products_id = p.products_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id order by pd.products_name";
  $products_extra_videos_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_extra_videos_query_raw, $products_extra_videos_query_numrows);
  $products_extra_videos_query = tep_db_query($products_extra_videos_query_raw);
  while ($products_extra_video = tep_db_fetch_array($products_extra_videos_query)) {
    if (!isset($pId))
      $pId = $products_extra_video['products_extra_videos_id'];
    if ($products_extra_video['products_extra_videos_id'] == $pId) {
      $pInfo = new objectInfo($products_extra_video);
      echo '  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $products_extra_video['products_extra_videos_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $products_extra_video['products_extra_videos_id']) . '\'">' . "\n";
    }
?>
				<td class="dataTableContent" align="left"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_extra_video['products_image'], $products_extra_video['products_name'], SMALL_IMAGE_WIDTH . 'size=25', SMALL_IMAGE_HEIGHT . 'size=25'); ?></td>
				<td class="dataTableContent" align="left"><?php echo $products_extra_video['products_name']; ?></td>
				<td class="dataTableContent" align="left"><?php echo '<a target="_blank" href="http://www.youtube.com/embed/'.$products_extra_video['products_extra_video'].'">www.youtube.com/embed/'.$products_extra_video['products_extra_video'].'</a>'; ?></td>
				<td class="dataTableContent" align="right"><?php if ($products_extra_video['products_extra_videos_id'] == $pId) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pID=' . $pId) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
			  </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_extra_videos_split->display_count($products_extra_videos_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_PAGING_FORMAT); ?></td>
                    <td class="smallText" align="right"><?php echo $products_extra_videos_split->display_links($products_extra_videos_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page);  ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="3" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pInfo->products_extra_videos_id . '&action=new') . '">' . tep_image_button('button_insert.gif', VIDEO_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_EXTRA_VIDEO . '</b>');

      $contents = array('form' => tep_draw_form('form_pei_insert', FILENAME_PRODUCTS_EXTRA_VIDEOS , 'action=insert', 'POST', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_NAME . '<br>');
      $contents[] = array('text' => tep_draw_pull_down_menu('products_id', $products_array, $products_extra_video['products_id']));
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_VIDEO . '<br>');
	  $contents[] = array('text' =>  'http://www.youtube.com/embed/' . tep_draw_input_field('pei_file','','size=50 value=""').'<br>' );
	  $contents[] = array('text' =>  'http://www.youtube.com/embed/' . tep_draw_input_field('pei_file1','','size=50 value=""').'<br>' );
	  $contents[] = array('text' =>  'http://www.youtube.com/embed/' . tep_draw_input_field('pei_file2','','size=50 value=""').'<br>' );
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . (count($products_array)-1));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', VIDEO_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pId) . '">' . tep_image_button('button_cancel.gif', VIDEO_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_EXTRA_VIDEO . '</b>');

      $contents = array('form' => tep_draw_form('form_pei_edit', FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pInfo->products_extra_videos_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_NAME . '<br>');
      $contents[] = array('text' => tep_draw_pull_down_menu('products_id', $products_array, $pInfo->products_id));
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_VIDEO . '<br>');
      $contents[] = array('text' =>  '<br>http://www.youtube.com/embed/' .tep_draw_input_field('pei_file',$pInfo -> pei_file,'size=25 value=' . $pInfo -> products_extra_video) );
      $contents[] = array('text' => '<br>' . $pInfo -> products_extra_video );
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . (count($products_array)-1));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', VIDEO_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pInfo->products_extra_videos_id) . '">' . tep_image_button('button_cancel.gif', VIDEO_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');

      $contents = array('form' => tep_draw_form('products_extra_video', FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pInfo->products_extra_videos_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_video', '', true) . ' ' . TEXT_DELETE_VIDEO);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', VIDEO_DELETE) . ' <a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pInfo->products_extra_videos_id) . '">' . tep_image_button('button_cancel.gif', VIDEO_CANCEL) . '</a>');
      break;
	default:
      if (isset($pId)) {
        $heading[] = array('text' => '<b>' . $pInfo -> products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pId . '&action=edit') . '">' . tep_image_button('button_edit.gif', VIDEO_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_VIDEOS, 'page=' . $page . '&pId=' . $pId . '&action=delete') . '">' . tep_image_button('button_delete.gif', VIDEO_DELETE) . '</a>');
		$pId = $_GET['pId'];

		if (isset($pId) && $pId!=''){
		  $products_extra_videos_query_raw = "select distinct( pei.products_extra_video), pei.products_extra_videos_id, pei.products_id, pd.products_name,p.products_image from " . TABLE_PRODUCTS_EXTRA_VIDEOS . " pei left join " . TABLE_PRODUCTS . " p ON pei.products_id = p.products_id and pei.products_id = ".$pId." left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id order by pd.products_name";
		  $products_extra_videos_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_extra_videos_query_raw, $products_extra_videos_query_numrows);
		  $products_extra_videos_query = tep_db_query($products_extra_videos_query_raw);
		  while ($products_extra_video = tep_db_fetch_array($products_extra_videos_query)) {
			if ($products_extra_video['products_extra_videos_id'] == $pId ){
			  $url = $products_extra_video['products_extra_video'];
			  $a = array();
			  $i=0;
			  while (isset($url{$i})) {
				$a[$i] = $url{$i};
				$i++;
			  }
			  $urlF = str_replace("watch?","", $url);
			  $urlFinal = str_replace("=", "/", $urlF);

			  $contents[] = array('align' => 'center', 'text' => '<br>' .'<iframe width="150" height="113" src="http://www.youtube.com/embed/'.$urlFinal.'" frameborder="0" allowfullscreen></iframe>');
			}
		  }
		}
        $contents[] = array('align' => 'center', 'text' => '<br>' . TEXT_PRODUCTS . ' ' . (count($products_array)-1));
      }
      break;
  } // end of switch

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '  <td width="25%" valign="top">' . "\n";
    $box = new box;
    echo $box->infoBox($heading, $contents);
    echo '  </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>