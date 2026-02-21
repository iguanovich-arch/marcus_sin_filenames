<?php
/**POSCTEMPLATE_BRAND**/

require('includes/application_top.php');
global $db;
     if(isset($_REQUEST['o_list']) && isset($_REQUEST['tname']))
	 {
		$list=trim($_REQUEST['o_list']);
		$table_name=trim($_REQUEST['tname']);
		$item_type=trim($_REQUEST['item_type']);
		$list_array=explode(',',$list);
		$i=1;
		foreach($list_array as $row)
			{
				$result=tep_db_query("update " . $table_name . " set sort_order='".$i."' where id='".$row."'");
				$i++;
			}
	 }


?>

