<?php 
#POSC_TEMPLATE_BASE#

global $messageStack; 
$banner_path = "../images/posc_template/banners/";

/************************************************************************************
******************************** Insert Item *******************************************
************************************************************************************/
if(isset($_REQUEST['submit_posc_botbanner']))
{
	$botitem_desc_ar = tep_db_prepare_input($_POST['botitem_desc']);
	$botitem_type = tep_db_prepare_input($_POST['botitem_type']);
	$botitem_status = tep_db_prepare_input($_POST['botitem_status']);
	$image_name = tep_db_prepare_input($_FILES['botitem_image']['name']);
	$botitem_link = tep_db_prepare_input($_POST['botitem_link']);
	$botitem_group = tep_db_prepare_input($_POST['botitem_group']);
	$botitem_style = tep_db_prepare_input($_POST['botitem_style']);
	$sres=tep_db_query("SELECT MAX(sort_order) as maxid FROM ". TABLE_POSC_BOTTOMBANNER." where item_type=".$botitem_type) or die(mysql_error());
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
	   move_uploaded_file($_FILES['botitem_image']['tmp_name'],$banner_path .$prod_image);
	}
	else
	{
		 $prod_image='';
	}
	
	$sql_data_array = array(
					'sort_order' =>  $maxid,
					'item_type' => 	tep_db_prepare_input($botitem_type),
					'item_image' => tep_db_prepare_input($prod_image),
					'item_link' =>  tep_db_prepare_input($botitem_link),
					'item_group' =>  tep_db_prepare_input($botitem_group),
					'item_style' =>  tep_db_prepare_input($botitem_style),
					'item_desc' =>  base64_encode(serialize($botitem_desc_ar)),
					'item_status' => $botitem_status);
					
	tep_db_perform(TABLE_POSC_BOTTOMBANNER, $sql_data_array);
	$messageStack->add_session('Item has been successfully saved.', 'success');
	tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
}

/************************************************************************************
******************************** Update Item *******************************************
************************************************************************************/ 
if(isset($_REQUEST['update_posc_botbanner']))
{
	$botitem_desc_ar = tep_db_prepare_input($_POST['botitem_desc']);
	$botitem_status = tep_db_prepare_input($_POST['botitem_status']);
	$botitem_type = tep_db_prepare_input($_POST['botitem_type']);
	$botitem_link = tep_db_prepare_input($_POST['botitem_link']);
	$botitem_group = tep_db_prepare_input($_POST['botitem_group']);
	$botitem_style = tep_db_prepare_input($_POST['botitem_style']);
	$image_name = tep_db_prepare_input($_FILES['botitem_image']['name']);
	$sort_order = tep_db_prepare_input($_POST['sort_order']);
	$botbeid=$_REQUEST['botbeid'];
	$sres=tep_db_query("SELECT MAX(sort_order) as maxid, item_image FROM ".TABLE_POSC_BOTTOMBANNER." where id='".(int)$botbeid."'") or die(mysql_error());
	$sres = tep_db_fetch_array($sres);
	if(!is_dir($banner_path))
	{	
		mkdir($banner_path);
	}
	if($image_name!='')
	{
		$exist_image_name=$banner_path.$sres['item_image'];
		if(file_exists($exist_image_name)){
			unlink($exist_image_name);   
		}
		$ext = pathinfo($image_name, PATHINFO_EXTENSION);
		$onlyname=str_replace('.'.$ext,'',$image_name);
		$prod_image= $onlyname.'_'.$time.".".$ext;
		move_uploaded_file($_FILES['botitem_image']['tmp_name'],$banner_path .$prod_image);
	}else{
		$prod_image=$sres['item_image'];	
	}
	  
	$sql_data_array = array(
					'sort_order'=>tep_db_prepare_input($sort_order),
					'item_type' => 	tep_db_prepare_input($botitem_type),
					'item_image' => tep_db_prepare_input($prod_image),
					'item_link' =>  tep_db_prepare_input($botitem_link),
					'item_group' =>  tep_db_prepare_input($botitem_group),
					'item_style' =>  tep_db_prepare_input($botitem_style),
					'item_desc' =>  base64_encode(serialize($botitem_desc_ar)),
					'item_status' => $botitem_status);
					
	tep_db_perform(TABLE_POSC_BOTTOMBANNER, $sql_data_array, 'update', "id='" .(int)$botbeid. "'" );
	
	$messageStack->add_session('Item has been successfully Updated.', 'success');
	tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
}
if(isset($_REQUEST['botbrid'])){
	  $id=$_REQUEST['botbrid'];
	  $botitem_type=$_REQUEST['itype'];
	  $checkchildres =tep_db_query("select * FROM ".TABLE_POSC_BOTTOMBANNER." where id='".$id."'");
	  $checkchildres = tep_db_fetch_array($checkchildres);
	  $filen=$banner_path.$checkchildres['item_image'];
	  if(file_exists($filen))
	  {
		  unlink($filen);	
	  }
	  $rs=tep_db_query("delete FROM ".TABLE_POSC_BOTTOMBANNER." where id='".$id."'");
	  $messageStack->add_session('Item has been successfully Deleted.', 'success');
	  tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
  }
if(isset($_REQUEST['botbeid']))
{
  $botbeid=$_REQUEST['botbeid'];
  $itype=$_REQUEST['itype'];
  $query_result = tep_db_query('SELECT * from '.TABLE_POSC_BOTTOMBANNER.' where id="'.$botbeid.'"');
  $query_result = tep_db_fetch_array($query_result);
  $botitem_image=$banner_path.$query_result['item_image'];
  $botitem_type=$query_result['item_type'];
  $botitem_status=$query_result['item_status'];
  $sort_order=$query_result['sort_order'];
  $botitem_link=$query_result['item_link'];
  $botitem_group=$query_result['item_group'];
  $botitem_style=$query_result['item_style'];
  $bot=($query_result['item_desc']);
  $botitem_desc=unserialize(base64_decode($query_result['item_desc']));
}
if(isset($_GET['action']) && $_GET['action']=='botbnew') {
	$botitem_type=$_GET['itype'];
}

?>
<h1 class="tab-header">Bottom Banners</h1>
<form name='frm_posc' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<section class="block single-block">
		<header class="block-header">
			<h2 class="title">Bottom Banner Settings</h2>
		</header>
		<div class="block-content">
			<div class="row">
				<label>Display Bottom Banners</label>
				<div class="cont"><?php echo posc_draw_yesnoradio('display_bottom_banners'); ?></div>
			</div>
			<div class="row">
				<div class="inner_section">
					<div class="rw">
						<label>Select Bottom Banners Layout from below :</label>
						<div class="rw_division">
							<?php 
							$bottom_banners_style=get_posc_options('bottom_banners_style');
							if($bottom_banners_style==''){$bottom_banners_style="1";}
							?>
							<div class="inline-ul col-lg-12">
								<div class="col-md-4">
									<input type="radio" name="bottom_banners_style" value="1" <?php echo ($bottom_banners_style=='1')? 'checked="checked"' : '' ?> /><span>Display Style - 1 </span>
								</div>
							</div>
							<div class="rw">
								<div class="col-md-4"><img src="includes/posc_template/images/bottom-banner-style-1.png" width="100%" height="auto" /></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<input type="hidden" name="frm_posc_set_submit" value="" />
</form>
<?php 
/************************************************************************************
******************************** Display Item *******************************************
************************************************************************************/
?>
<?php if((!isset($_GET['botbeid'])) && ($_GET['action']!='botbnew') ){ ?>

<?php /**************************************** Bottom Banners ****************************************/ ?>
<section class="block-static slides_block single-block">
    <header class="block-header">
        <h2 class="title">Banners <a class="action_new" href="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, "action=botbnew&itype=2", 'SSL'); ?>" >
        	<button title="Add" class="md-btn" type="button">Add</button></a></h2>
    </header>
    <div class="block-content">
    	<?php 
		global $db;
		$results = tep_db_query("SELECT * FROM ".TABLE_POSC_BOTTOMBANNER." where item_type='2' group by `sort_order` order by `sort_order`");
		$i=1; ?>
        <form id="sort-botbform-2" name='sort-botbform-2' action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
         	<input style="display:none;" type="checkbox" value="1" name="pautoSubmit" id="pautoSubmit" checked='checked' />
            <ul id="sortable-list" class="parentsortable-botb-list-2">
            <?php 
			$porder=array();
             if ($results->num_rows > 0) {
				while($result = tep_db_fetch_array($results)) {
					$botitem_image=$banner_path.$result['item_image'];
					?>
					<li class="item"  id="<?php echo $result['id']; ?>">
					<table border="0" width="100%" align="center" class="slide-item" style='border-collapse: collapse;margin-top:0;float:left;'>
					<tr>
						<td width="5%" class="slide_numb" align="center"><?php echo $i++; ?></td>
						<td width="30%" align="center"><?php echo "<div style='max-width:236px;width:100%;display:inline-block;'><img style='height: auto; max-width: 100%; max-height: 90px; margin: 2px 10px;' src='".$botitem_image."' alt='Item Image' /></div>"; ?></td>
						<td width="20%" align="center"><?php echo ($result['item_status'])? "<img src='includes/".FILENAME_POSC_TEMPLATE_NAME."/images/enable.png'  height='20' width='20' alt='enable' />" : "<img src='includes/".FILENAME_POSC_TEMPLATE_NAME."/images/desable.png' height='20' width='20' alt='desable' />"; ?></td>
						<td width="30%" align="right" class="action_cont">
								<a href='<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, tep_get_all_get_params(array('botbeid')) . 'botbeid=' .$result['id'], 'SSL'); ?>' class="md-btn" title="EDIT">Edit</a>
								 <a href='<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, tep_get_all_get_params(array('botbrid')) . 'botbrid=' .$result['id'], 'SSL'); ?>' class="md-btn" title="DELETE">Delete</a>
								
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
        <input type="hidden" name="psort_botb_order_2" id="psort_botb_order_2" value="<?php echo implode(',',$porder); ?>" />
        </form>
	</div>
</section>
<?php } ?>
<?php 
/************************************************************************************
******************************** Edit Item *******************************************
************************************************************************************/
?>	
<form name='frm_posc_banner' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['action']) || isset($_GET['botbeid'])){ ?>
<section class="block-static single-block">
    <header class="block-header">
        <h2 class="title"><?php echo (isset($_GET['action']) && $_GET['action']=='botbnew' )? 'ADD' : 'EDIT'; ?></h2>
    </header>
    <div class="block-content">
        <div class="row">
            <label>Image</label>
            <div class="cont">
				<input type="file" name="botitem_image" id="botitem_image"  /><?php if(isset($_REQUEST['botbeid'])){ echo "<span class='edited_slide'><img src='$botitem_image' alt='botitem_image' /></span>"; } ?>
            </div>
        </div>
        <div class="row">
            <label>Content</label>
            <div class="cont">
				<?php
					// modified code for multi-language support
						for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
						  echo '<br />' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
						  echo tep_draw_textarea_field('botitem_desc[' . $languages[$i]['id'] . ']', 'Desc', 52 , 10,  $botitem_desc[$i+1] ,' class="noEditor md-input"', true);
						}
					// end modified code for multi-language support
				?>
            </div>
        </div>
		<div class="row">
			<label>Link</label>
			<div class="cont">
				<input type="text" name="botitem_link" class="md-input md-input-link" id="botitem_link" value="<?php if(isset($_REQUEST['botbeid'])){ echo $botitem_link; } ?>"  />
			</div>
		</div>
		<?php if($posc_dev_mode==1){ ?>
		<div class="row">
			<div class="cont">
				<?php 
				if(!empty($home_layout_ar)){ ?>
				<select name="botitem_group">
					<?php foreach($home_layout_ar as $hk=>$hv){ ?>													
						<option value="<?php echo $hk; ?>" <?php if($botitem_group==$hk){ ?> selected <?php } ?>><?php echo $hv; ?></option>
					<?php } ?>
				</select>
				<?php } ?>
				<select name="botitem_style">
					<?php for($i=1;$i<=1;$i++){ ?>
						<option value="<?php echo $i; ?>" <?php if($botitem_style==$i){ ?> selected <?php } ?>>style - <?php echo $i; ?></option>
				<?php }	?>
				</select>
			</div>
		</div>
		<?php }else{ ?>
			<input type='hidden' name='botitem_group' value="" /> 
			<input type='hidden' name='botitem_style' value="" /> 
		<?php } ?>
        <div class="row">
            <label>Status</label>
            <div class="cont">
				<?php if($botitem_status==''){$botitem_status=1;} ?>
            	<ul class="inline-ul">
                    <li><input type="radio" name="botitem_status" value="1" <?php echo ($botitem_status==0)? '' : 'checked="checked"';  ?>  /><span>Enable</span></li>
                    <li><input type="radio" name="botitem_status" value="0" <?php echo ($botitem_status==0)? 'checked="checked"' : '';  ?> /><span>Disable</span></li>
                </ul>
			</div>
        </div>
		<input type="hidden" name="botitem_type" value="2" />
        <div class="row">
        	<?php if(isset($_REQUEST['botbeid'])){ ?>
				<input type="submit" name="update_posc_botbanner" class="md-btn" value="Update" />
				<input type='hidden' name='botbeid' value="<?php if(isset($_REQUEST['botbeid'])) echo $_REQUEST['botbeid']; ?>" /> 
				<input type='hidden' name='sort_order' value="<?php if(isset($sort_order)) echo $sort_order ?>" /> 
			<?php }else{?>
				<input type="submit" class="md-btn" name="submit_posc_botbanner" value="Save" /><?php } ?>
				<input type="reset" class="md-btn" onclick="reset_function()" name="cancel_mzen_slide" value="Cancel" />
        </div>
    </div>
 </section>
 <?php } ?>
 </form>
<script type="text/javascript">
/********************************************************* Drag and drop sorting for slides   ***************************************/
function reset_function(){
	window.location="<?php echo $cancel_url; ?>";	
}
// for parent slide sorting
jQuery(document).ready(function() {
	/* grab important elements */
	var sortInput_3 = jQuery('#psort_botb_order_2');
	//alert(JSON.stringify(sortInput));
	var submit = jQuery('#pautoSubmit');
	var messageBox = jQuery('#message-box');
	var list_3 = jQuery('.parentsortable-botb-list-2');
	if(list_3.children('li').size()>1){
		/* create requesting function to avoid duplicate code */
		var request_3 = function(){
			jQuery.ajax({
				beforeSend: function() {
					messageBox.text('Updating the sort order in the database.');
				},
				complete: function() {
					var xmlhttp
					var table_name="<?php echo TABLE_POSC_BOTTOMBANNER; ?>";
					var po_list=document.getElementById('psort_botb_order_2').value;
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
				data: 'sort_order=' + sortInput_3[0].value + '&ajax=' + submit[0].checked + '&do_submit=1&byajax=1', //need [0]?
				type: 'post',
				url: '<?php echo $_SERVER["REQUEST_URI"]; ?>'
			});
		};
		var fnSubmit_3 = function(save) {
			var sortOrder = [];
			list_3.children('li').each(function(){
				sortOrder.push(jQuery(this).data('id'));
			});
			sortInput_3.val(sortOrder.join(','));
				console.log(sortInput_3.val());
			if(save) {
				request_3();
			}
		};
	/* store values */
		list_3.children('li').each(function() {
			var li = jQuery(this);
				li.data('id',li.attr('id')).attr('id','');
		});
		list_3.disableSelection();
	/* sortables */
		list_3.sortable({
			containment: "parent",
			opacity: 0.7,
			update: function() {
				fnSubmit_3(submit[0].checked);
			}
		});
	
		/* ajax form submission */
		jQuery('#sort--botb-form-2').bind('submit',function(e) {
			if(e) e.preventDefault();
				fnSubmit_3(true);
		});
	}
});
</script>