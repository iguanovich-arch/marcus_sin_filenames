<?php 
#POSC_TEMPLATE_BASE#

global $messageStack; 
$banner_path = "../images/posc_template/banners/";
/************************************************************************************
******************************** Insert Item *******************************************
************************************************************************************/
if(isset($_REQUEST['submit_posc_banner']))
{
	$item_desc_ar = tep_db_prepare_input($_POST['item_desc']);
	$item_type = '2';
	$item_status = tep_db_prepare_input($_POST['item_status']);
	$item_group = tep_db_prepare_input($_POST['item_group']);
	$item_style = tep_db_prepare_input($_POST['item_style']);
	$image_name = tep_db_prepare_input($_FILES['item_image']['name']);
	$item_link = tep_db_prepare_input($_POST['item_link']);
	$sres=tep_db_query("SELECT MAX(sort_order) as maxid FROM ". TABLE_POSC_TOPBANNER." where item_type=".$item_type) or die(mysql_error());
	$sres = tep_db_fetch_array($sres);
	
	$maxid=$sres['maxid']+1;
	if(!is_dir($banner_path))
	{
		mkdir($banner_path);
	}
	if($image_name!='')
	{
	   $ext = pathinfo($image_name, PATHINFO_EXTENSION);
	   $onlyname=str_replace('.'.$ext,'',$image_name);
	   $prod_image= $onlyname.'_'.$time.".".$ext;
	   move_uploaded_file($_FILES['item_image']['tmp_name'],$banner_path .$prod_image);
	}
	else
	{
		 $prod_image='';
	}
	
	$sql_data_array = array(
					'sort_order' =>  $maxid,
					'item_type' => 	tep_db_prepare_input($item_type),
					'item_image' => tep_db_prepare_input($prod_image),
					'item_link' =>  tep_db_prepare_input($item_link),
					'item_group' =>  tep_db_prepare_input($item_group),
					'item_style' =>  tep_db_prepare_input($item_style),
					'item_desc' =>  base64_encode(serialize($item_desc_ar)),
					'item_status' => $item_status);
					
	tep_db_perform(TABLE_POSC_TOPBANNER, $sql_data_array);
	$messageStack->add_session('Item has been successfully saved.', 'success');
	tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
}

/************************************************************************************
******************************** Update Item *******************************************
************************************************************************************/ 
if(isset($_REQUEST['update_posc_banner']))
{
	$item_desc_ar = tep_db_prepare_input($_POST['item_desc']);
	$item_status = tep_db_prepare_input($_POST['item_status']);
	$item_type = '2';
	$item_link = tep_db_prepare_input($_POST['item_link']);
	$item_group = tep_db_prepare_input($_POST['item_group']);
	$item_style = tep_db_prepare_input($_POST['item_style']);
	$image_name = tep_db_prepare_input($_FILES['item_image']['name']);
	$sort_order = tep_db_prepare_input($_POST['sort_order']);
	$beid=$_REQUEST['beid'];
	$sres=tep_db_query("SELECT MAX(sort_order) as maxid, item_image FROM ".TABLE_POSC_TOPBANNER." where id='".(int)$beid."'") or die(mysql_error());
	$sres = tep_db_fetch_array($sres);
	if(!is_dir($banner_path))
	{	
		mkdir($banner_path);
	}
	if($image_name!='')
	{
		 $exist_image_name=$banner_path.$sres['item_image'];
		 if(file_exists($exist_image_name))
		 {
			  unlink($exist_image_name);   
		 }
		 $ext = pathinfo($image_name, PATHINFO_EXTENSION);
		 $onlyname=str_replace('.'.$ext,'',$image_name);
		 $prod_image= $onlyname.'_'.$time.".".$ext;
		 move_uploaded_file($_FILES['item_image']['tmp_name'],$banner_path .$prod_image);
	  }
	  else
	  {
		 $prod_image=$sres['item_image'];	
	  }
	  
	$sql_data_array = array(
					'sort_order'=>tep_db_prepare_input($sort_order),
					'item_type' => 	tep_db_prepare_input($item_type),
					'item_image' => tep_db_prepare_input($prod_image),
					'item_link' =>  tep_db_prepare_input($item_link),
					'item_group' =>  tep_db_prepare_input($item_group),
					'item_style' =>  tep_db_prepare_input($item_style),
					'item_desc' =>  base64_encode(serialize($item_desc_ar)),
					'item_status' => $item_status);
					
	tep_db_perform(TABLE_POSC_TOPBANNER, $sql_data_array, 'update', "id='" .(int)$beid. "'" );
	
	$messageStack->add_session('Item has been successfully Updated.', 'success');
	tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
}
if(isset($_REQUEST['brid']))
  {
	  $id=$_REQUEST['brid'];
	  $item_type='2';
	  $checkchildres =tep_db_query("select * FROM ".TABLE_POSC_TOPBANNER." where id='".$id."'");
	  $checkchildres = tep_db_fetch_array($checkchildres);
	  $filen=$banner_path.$checkchildres['item_image'];
	  if(file_exists($filen))
	  {
		  unlink($filen);	
	  }
	  $rs=tep_db_query("delete FROM ".TABLE_POSC_TOPBANNER." where id='".$id."'");
	  $messageStack->add_session('Item has been successfully Deleted.', 'success');
	  tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
  }
if(isset($_REQUEST['beid']))
{
  $beid=$_REQUEST['beid'];
  $query_result = tep_db_query('SELECT * from '.TABLE_POSC_TOPBANNER.' where id="'.$beid.'"');
  $query_result = tep_db_fetch_array($query_result);
  $item_image=$banner_path.$query_result['item_image'];
  $item_type=$query_result['item_type'];
  $item_group=$query_result['item_group'];
  $item_style=$query_result['item_style'];
  $item_status=$query_result['item_status'];
  $sort_order=$query_result['sort_order'];
  $item_link=$query_result['item_link'];
  $item_desc=unserialize(base64_decode($query_result['item_desc']));
}
if(isset($_GET['action']) && $_GET['action']=='bnew') {
	$item_type='banner';
}
?>
<h1 class="tab-header">Top Banners</h1>
<form name='frm_posc' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="frm_posc_set_submit" value="" />
	<section class="block-static single-block">
		<header class="block-header">
			<h2 class="title">Top Banners Settings</h2>
		</header>
		<div class="block-content">
			<div class="row">
				<label>Display Top Banner :</label>
				<div class="cont"><?php echo posc_draw_yesnoradio('display_top_banners'); ?></div>
			</div>
			<div class="row">
				<div class="inner_section">
					<div class="rw">
						<label>Select Top Banners Layout from below :</label>
						<div class="rw_division">
							<?php
							$topbannersstyle=get_posc_options('top_banners_style');
							if($topbannersstyle==''){$topbannersstyle="1";}
							?>
							<div class="inline-ul col-lg-12">
								<div class="col-md-4"><input type="radio" name="top_banners_style" value="1" <?php echo ($topbannersstyle=='1')? 'checked="checked"' : '' ?> /><span>Display Style - 1 </span></div>
								<div class="col-md-4"><input type="radio" name="top_banners_style" value="2" <?php echo ($topbannersstyle=='2')? 'checked="checked"' : '' ?> /><span>Display Style - 2 </span></div>
								<div class="col-md-4"><input type="radio" name="top_banners_style" value="3" <?php echo ($topbannersstyle=='3')? 'checked="checked"' : '' ?> /><span>Display Style - 3 </span></div>
							</div>
							<div class="col-lg-12">
								<div class="col-md-4"><img src="includes/posc_template/images/top-banner-style-1.png" width="100%" height="auto" /></div>
								<div class="col-md-4"><img src="includes/posc_template/images/top-banner-style-2.png" width="100%" height="auto" /></div>
								<div class="col-md-4"><img src="includes/posc_template/images/top-banner-style-3.png" width="100%" height="auto" /></div>
							</div>
							<div class="inline-ul col-lg-12">
								<div class="col-md-4"><input type="radio" name="top_banners_style" value="4" <?php echo ($topbannersstyle=='4')? 'checked="checked"' : '' ?> /><span>Display Style - 4 </span></div>
								<div class="col-md-4"><input type="radio" name="top_banners_style" value="5" <?php echo ($topbannersstyle=='5')? 'checked="checked"' : '' ?> /><span>Display Style - 5 </span></div>
							</div>
							<div class="col-lg-12">
								<div class="col-md-4"><img src="includes/posc_template/images/top-banner-style-4.png" width="100%" height="auto" /></div>
								<div class="col-md-4"><img src="includes/posc_template/images/top-banner-style-5.png" width="100%" height="auto" /></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</form>
<?php 
/************************************************************************************
******************************** Display Item *******************************************
************************************************************************************/
?>
<?php if((!isset($_GET['beid'])) && ($_GET['action']!='bnew') ){ ?>
<?php /**************************************** Topbar Banners ****************************************/ ?>
<section class="block-static slides_block single-block">
    <header class="block-header">
        <h2 class="title">Banners <a class="action_new" href="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, "action=bnew&itype=2", 'SSL'); ?>" >
        	<button title="Add" class="md-btn" type="button">Add</button></a></h2>
    </header>
    <div class="block-content">
    	<?php 
		global $db;
		$results = tep_db_query("SELECT * FROM ".TABLE_POSC_TOPBANNER." group by `sort_order` order by `sort_order`");
		$i=1; ?>
        <form id="sort-form-2" name='sort-form-2' action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
         	<input style="display:none;" type="checkbox" value="1" name="pautoSubmit" id="pautoSubmit" checked='checked' />
            <ul id="sortable-list" class="parentsortable-list-1">
            <?php 
			$porder=array();
            if ($results->num_rows > 0) {
				while($result = tep_db_fetch_array($results)) {
					$item_image=$banner_path.$result['item_image'];
					?>
					<li class="item"  id="<?php echo $result['id']; ?>">
					<table border="0" width="100%" align="center" class="slide-item" style='border-collapse: collapse;margin-top:0;float:left;'>
					<tr>
						<td width="5%" class="slide_numb" align="center"><?php echo $i++; ?></td>
						<td width="30%" align="center"><?php echo "<div style='max-width:236px;width:100%;display:inline-block;'><img style='height: auto; max-width: 100%; max-height: 90px; margin: 2px 10px;' src='".$item_image."' alt='Item Image' /></div>"; ?></td>
						<td width="20%" align="center"><?php echo ($result['item_status'])? "<img src='includes/".FILENAME_POSC_TEMPLATE_NAME."/images/enable.png'  height='20' width='20' alt='enable' />" : "<img src='includes/".FILENAME_POSC_TEMPLATE_NAME."/images/desable.png' height='20' width='20' alt='desable' />"; ?></td>
						<td width="20%" align="right" class="action_cont">
								<a href='<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, tep_get_all_get_params(array('beid')) . 'beid=' .$result['id'], 'SSL'); ?>' class="md-btn" title="Edit">Edit</a>
								<a href='<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, tep_get_all_get_params(array('brid')) . 'brid=' .$result['id'], 'SSL'); ?>' class="md-btn" title="Delete">Delete</a>
						</td>
						<td width="5%" class="drageble_cont">
							&nbsp;      
						</tr>
					</table>
					<?php $porder[] = $result['id']; ?>
					</li>
                <?php 
                }
			}
			else{
				echo "<li>Item not exists.</li>";	
			}
            ?>
        </ul>
        <input type="hidden" name="psort_order_1" id="psort_order_1" value="<?php echo implode(',',$porder); ?>" />
        </form>
	</div>
</section>
<?php } ?>
<?php 
/************************************************************************************
******************************** Edit Item *******************************************
************************************************************************************/
?>	
<?php if(isset($_GET['action']) || isset($_GET['beid'])){ ?>
<form name='frm_posc_banner' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<section class="block-static single-block">
		<header class="block-header">
			<h2 class="title"><?php echo (isset($_GET['action']) && $_GET['action']=='bnew' )? 'ADD' : 'EDIT'; ?></h2>
		</header>
		<div class="block-content">
			<div class="row">
				<label>Image</label>
				<div class="cont">
					<input type="file" name="item_image" id="item_image"  /><?php if(isset($_REQUEST['beid'])){ echo "<span class='edited_slide'><img src='$item_image' alt='item_image' /></span>"; } ?>
				</div>
			</div>
			<div class="row">
				<label>Content</label>
				<div class="cont">
					<?php
						// modified code for multi-language support
							for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
							  echo '<br />' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
							  echo tep_draw_textarea_field('item_desc[' . $languages[$i]['id'] . ']', 'Desc', 52 , 10,  $item_desc[$i+1] ,' class="noEditor md-input"', true);
							}
						// end modified code for multi-language support
					?>
				</div>
			</div>
			<div class="row">
				<label>Link</label>
				<div class="cont">
					<input type="text" name="item_link" class="md-input md-input-link" id="item_link" value="<?php if(isset($_REQUEST['beid'])){ echo $item_link; } ?>" />
				</div>
			</div>
			<?php if($posc_dev_mode==1){ ?>
			<div class="row">
				<div class="cont">
					<?php 
					if(!empty($home_layout_ar)){ ?>
					<select name="item_group">
						<?php foreach($home_layout_ar as $hk=>$hv){ ?>													
							<option value="<?php echo $hk; ?>" <?php if($item_group==$hk){ ?> selected <?php } ?>><?php echo $hv; ?></option>
						<?php } ?>
					</select>
					<?php } ?>
					<select name="item_style">
						<?php for($i=1;$i<=5;$i++){ ?>
							<option value="<?php echo $i; ?>" <?php if($item_style==$i){ ?> selected <?php } ?>>style - <?php echo $i; ?></option>
					<?php }	?>
					</select>
				</div>
			</div>
			<?php }else{ ?>
				<input type='hidden' name='item_group' value="" /> 
				<input type='hidden' name='item_style' value="" /> 
			<?php } ?>
			<div class="row">
				<label>Status</label>
				<div class="cont">
					<?php if($item_status==''){$item_status=1;} ?>
					<ul class="inline-ul">
						<li><input type="radio" name="item_status" value="1" <?php echo ($item_status==0)? '' : 'checked="checked"';  ?>  /><span>Enable</span></li>
						<li><input type="radio" name="item_status" value="0" <?php echo ($item_status==0)? 'checked="checked"' : '';  ?> /><span>Disable</span></li>
					</ul>
				</div>
			</div>
			<div class="row">
				<?php if(isset($_REQUEST['beid'])){ ?>
					<input type="submit" name="update_posc_banner" class="md-btn" value="Update" />
					<input type='hidden' name='beid' value="<?php if(isset($_REQUEST['beid'])) echo $_REQUEST['beid']; ?>" /> 
					<input type='hidden' name='sort_order' value="<?php if(isset($sort_order)) echo $sort_order ?>" /> 
				<?php }else{?>
					<input type="submit" class="md-btn" name="submit_posc_banner" value="Save" /><?php } ?>
					<input type="reset" class="md-btn" onclick="reset_function()" name="cancel_posc_slide" value="Cancle" />
			</div>
		</div>
	 </section>
</form>
 <?php } ?>
<script type="text/javascript">
/********************************************************* Drag and drop sorting for slides   ***************************************/
function reset_function(){
	window.location="<?php echo $cancel_url; ?>";	
}
// for parent slide sorting
jQuery(document).ready(function() {
	/*************************************************** sortting list 1 **************************************/
	/* grab important elements */
	var sortInput_1 = jQuery('#psort_order_1');
	//alert(JSON.stringify(sortInput));
	var submit = jQuery('#pautoSubmit');
	var messageBox = jQuery('#message-box');
	var list_1 = jQuery('.parentsortable-list-1');
	/* create requesting function to avoid duplicate code */
	if(list_1.children('li').size()>1){
		var request_1 = function(){
			jQuery.ajax({
				beforeSend: function() {
					messageBox.text('Updating the sort order in the database.');
				},
				complete: function() {
					var xmlhttp
					var table_name="<?php echo TABLE_POSC_TOPBANNER; ?>";
					var po_list=document.getElementById('psort_order_1').value;
					if (window.XMLHttpRequest)
						{
							xmlhttp=new XMLHttpRequest();
						}
						else
						{
							xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
						}
						xmlhttp.onreadystatechange=function()
						{
							if (xmlhttp.readyState==4 && xmlhttp.status==200)
							{
								//	alert(xmlhttp.responseText);
							}
						}
						xmlhttp.open("POST","posc_slide_ajax.php?o_list="+po_list+"&tname="+table_name,true);
						xmlhttp.send();
				},
				data: 'sort_order=' + sortInput_1[0].value + '&ajax=' + submit[0].checked + '&do_submit=1&byajax=1', //need [0]?
				type: 'post',
				url: '<?php echo $_SERVER["REQUEST_URI"]; ?>'
			});
		};
		
		var fnSubmit_1 = function(save) {
			var sortOrder = [];
			list_1.children('li').each(function(){
				sortOrder.push(jQuery(this).data('id'));
			});
			sortInput_1.val(sortOrder.join(','));
				console.log(sortInput_1.val());
			if(save) {
				request_1();
			}
		};
	/* store values */
		list_1.children('li').each(function() {
			var li = jQuery(this);
				li.data('id',li.attr('id')).attr('id','');
		});
		list_1.disableSelection();
	/* sortables */
		list_1.sortable({
			containment: "parent",
			opacity: 0.7,
			update: function() {
				fnSubmit_1(submit[0].checked);
			}
		});
		/* ajax form submission */
		jQuery('#sort-form-1').bind('submit',function(e) {
			if(e) e.preventDefault();
				fnSubmit_1(true);
		});
	}
});
</script>