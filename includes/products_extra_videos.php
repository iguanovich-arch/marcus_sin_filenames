<?php
  /*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Based on Extra videos 1.4 from Mikel Williams
  Thanks to Mikel Williams, StuBo, moosey_jude and Randelia
  Modifications: Xav xpavyfr@yahoo.fr

  */

  $products_extra_videos_query = tep_db_query("select p.products_id, pe.products_id, p.products_model, pe.products_extra_video, pe.products_extra_videos_id, p.manufacturers_id, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_EXTRA_VIDEOS . " pe, " . TABLE_MANUFACTURERS . " m where pe.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pe.products_id = p.products_id and p.manufacturers_id = m.manufacturers_id");

  if (tep_db_num_rows($products_extra_videos_query) >= 1){
	$rowcount_value=4;  //number of extra videos per row
	$rowcount=1;
?>
<?php
	while ($extra_videos = tep_db_fetch_array($products_extra_videos_query)) {
?>
		<div class="infoBoxContents des_video">
<?php
      $url = $extra_videos['products_extra_video'];
      $a = array();
      $i=0;
      while (isset($url{$i})) {
        $a[$i] = $url{$i};
        $i++;
      }
      $rep = array("watch?");
      $urlF = str_replace($rep, "", $url);
      $urlFinal = str_replace("=", "/", $urlF);
      echo '<iframe width="640px" height="360px" src="http://www.youtube.com/embed/'.$urlFinal.'" frameborder="0" allowfullscreen></iframe>';
?>
		</div>
<?php
	  if ($rowcount == $rowcount_value) {
	    echo '</tr><tr>'; $rowcount=1;
	  } else {
	    $rowcount=$rowcount+1;
	  }
    }
?>
<?php
  }
?>