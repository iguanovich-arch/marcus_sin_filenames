<?php 
##POSCTEMPLATE_BRAND##

global $db;
global $template_dir, $messageStack;
$slider_path = "../images/posc_template/slider/";
if(isset($_REQUEST['submit_posc_slide']))
 {
	$slide_caption = tep_db_prepare_input($_POST['slide_caption']);
	$slide_status = tep_db_prepare_input($_POST['slide_status']);
	$slide_link = tep_db_prepare_input($_POST['slide_link']);
	$slide_group = tep_db_prepare_input($_POST['slide_group']);
	$slide_style = tep_db_prepare_input($_POST['slide_style']);
	$image_name = tep_db_prepare_input($_FILES['slide_image']['name']);
	$sres=tep_db_query("SELECT MAX(sort_order) as maxid FROM ". TABLE_POSC_MAINSLIDER) or die(mysql_error());
	$sres = tep_db_fetch_array($sres);
	$maxid=$sres['maxid']+1;
	if(!is_dir($slider_path))
	{
		mkdir($slider_path);
	}
	if($image_name!='')
	{
	   $ext = pathinfo($image_name, PATHINFO_EXTENSION);
	   $onlyname=str_replace('.'.$ext,'',$image_name);
	   $prod_image= $onlyname.'_'.$time.".".$ext;
	   move_uploaded_file($_FILES['slide_image']['tmp_name'],$slider_path .$prod_image);
	}
	else
	{
		 $prod_image='';
	}
	
	$sql_data_array = array( 'sort_order' =>  $maxid,
							'slide_image' => tep_db_prepare_input($prod_image),
							'slide_caption'=> base64_encode(serialize($slide_caption)),
							'slide_group' => tep_db_prepare_input($slide_group),
							'slide_style' => tep_db_prepare_input($slide_style),
							'slide_link' => tep_db_prepare_input($slide_link),
							'slide_status' => $slide_status);
	tep_db_perform(TABLE_POSC_MAINSLIDER, $sql_data_array);

	$messageStack->add_session('Item has been successfully saved.', 'success');
	tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
 }
if(isset($_REQUEST['update_posc_slide']))
{
	$slide_caption = tep_db_prepare_input($_POST['slide_caption']);
	$slide_status = tep_db_prepare_input($_POST['slide_status']);
	$slide_link = tep_db_prepare_input($_POST['slide_link']);
	$slide_group = tep_db_prepare_input($_POST['slide_group']);
	$slide_style = tep_db_prepare_input($_POST['slide_style']);
	$image_name = tep_db_prepare_input($_FILES['slide_image']['name']);
	$sort_order = tep_db_prepare_input($_POST['sort_order']);
	 
	$slide_eid=$_REQUEST['slide_eid'];
	 
	$sres=tep_db_query("SELECT MAX(sort_order) as maxid, slide_image FROM ".TABLE_POSC_MAINSLIDER." where id='".$slide_eid."'") or die(mysql_error());
	$sres = tep_db_fetch_array($sres);
	$maxid=$sres['maxid']+1;
	if(!is_dir($slider_path))
	{
		mkdir($slider_path);
	}
	if($image_name!='')
	{
		$exist_image_name=$slider_path.$sres['slide_image'];
		if(file_exists($exist_image_name)){unlink($exist_image_name);}
		$ext = pathinfo($image_name, PATHINFO_EXTENSION);
		$onlyname=str_replace('.'.$ext,'',$image_name);
		$prod_image= $onlyname.'_'.$time.".".$ext;
		move_uploaded_file($_FILES['slide_image']['tmp_name'],$slider_path .$prod_image);
	}else{
		$prod_image=$sres['slide_image'];	
	}
	
	$sql_data_array = array(
		'sort_order'=>tep_db_prepare_input($sort_order),
		'slide_image' => tep_db_prepare_input($prod_image),
		'slide_link' => tep_db_prepare_input($slide_link),
		'slide_group' => tep_db_prepare_input($slide_group),
		'slide_style' => tep_db_prepare_input($slide_style),
		'slide_caption'=> base64_encode(serialize($slide_caption)),
		'slide_status' => $slide_status);
		
		tep_db_perform(TABLE_POSC_MAINSLIDER, $sql_data_array, 'update', "id = '" . (int)$slide_eid . "'" );
		
	$messageStack->add_session('Item has been successfully Updated.', 'success');
	tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
}
if(isset($_GET['slide_rid']))
  {
	  $id=$_REQUEST['slide_rid'];
	  $checkchildres =tep_db_query("select * FROM ".TABLE_POSC_MAINSLIDER." where id='".$id."'");
	  $checkchildres = tep_db_fetch_array($checkchildres);
	  $filen=$slider_path.$checkchildres['slide_image'];
	  if(file_exists($filen))
	  {
		  unlink($filen);	
	  }
	  $rs=tep_db_query("delete FROM ".TABLE_POSC_MAINSLIDER." where sort_order='".$id."'");
	  $messageStack->add_session('Item has been successfully Deleted.', 'success');
	  tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL'));
  }
if(isset($_GET['slide_eid']))
{
	$slide_eid=$_REQUEST['slide_eid'];
	$query_result = tep_db_query('SELECT * from '.TABLE_POSC_MAINSLIDER.' where id="'.$slide_eid.'"');
	$query_result = tep_db_fetch_array($query_result);
	$slide_image=$slider_path.$query_result['slide_image'];
	$slide_status=$query_result['slide_status'];
	$slide_link=$query_result['slide_link'];
	$slide_group=$query_result['slide_group'];
	$slide_style=$query_result['slide_style'];
	$sort_order=$query_result['sort_order'];
	$slide_caption = unserialize(base64_decode($query_result['slide_caption']));
}
?>
<h1 class="tab-header">Main Slider</h1>
<form name='frm_posc' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="frm_posc_set_submit" value="" />
	<section class="block-static single-block">
		<header class="block-header">
			<h2 class="title">Main Slider</h2>
		</header>
		<div class="block-content">
			<div class="row">
				<label>Display Mainslider</label>
				<div class="cont"><?php echo posc_draw_yesnoradio('display_main_slideshow'); ?></div>
			</div>
			<div class="row">
				<div class="inner_section">
					<div class="rw">
						<label>Main Slider Style :</label>
						<div class="rw_division">
							<?php 
							$mainslider_style=get_posc_options('mainslider_style');
							if($mainslider_style==''){$mainslider_style="1";}
							?>
							<div class="inline-ul col-lg-12">
								<div class="col-md-4">
									<input type="radio" name="mainslider_style" value="1" <?php echo ($mainslider_style=='1')? 'checked="checked"' : '' ?> /><span>Display Style - 1 </span>
								</div>
								<div class="col-md-4">
									<input type="radio" name="mainslider_style" value="2" <?php echo ($mainslider_style=='2')? 'checked="checked"' : '' ?> /><span>Display Style - 2 </span>
								</div>
								<div class="col-md-4">
									<input type="radio" name="mainslider_style" value="3" <?php echo ($mainslider_style=='3')? 'checked="checked"' : '' ?> /><span>Display Style - 3 </span>
								</div>
							</div>
							<div class="rw">
								<div class="col-md-4"><img src="includes/posc_template/images/mainslider-style-1.png" width="100%" height="auto" /></div>
								<div class="col-md-4"><img src="includes/posc_template/images/mainslider-style-2.png" width="100%" height="auto" /></div>
								<div class="col-md-4"><img src="includes/posc_template/images/mainslider-style-3.png" width="100%" height="auto" /></div>
							</div>
							<div class="inline-ul col-lg-12">
								<div class="col-md-4">
									<input type="radio" name="mainslider_style" value="4" <?php echo ($mainslider_style=='4')? 'checked="checked"' : '' ?> /><span>Display Style - 4 </span>
								</div>
								<div class="col-md-4">
									<input type="radio" name="mainslider_style" value="5" <?php echo ($mainslider_style=='5')? 'checked="checked"' : '' ?> /><span>Display Style - 5 </span>
								</div>
								<div class="col-md-4">
									<input type="radio" name="mainslider_style" value="6" <?php echo ($mainslider_style=='6')? 'checked="checked"' : '' ?> /><span>Display Style - 6 </span>
								</div>
							</div>
							<div class="rw">
								<div class="col-md-4"><img src="includes/posc_template/images/mainslider-style-4.png" width="100%" height="auto" /></div>
								<div class="col-md-4"><img src="includes/posc_template/images/mainslider-style-5.png" width="100%" height="auto" /></div>
								<div class="col-md-4"><img src="includes/posc_template/images/mainslider-style-6.png" width="100%" height="auto" /></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</form>
<?php if($mainslider_style!='layer'){ ?>
<?php if((!isset($_GET['slide_eid'])) && ($_GET['action']!='slide_new')){ ?>
<section class="block-static slides_block">
    <header class="block-header">
        <h2 class="title">Slides
		<?php if($mainslider_style!='layer'){ ?>
		<a class="action_new" href="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE,"action=slide_new", 'SSL'); ?>" ><button title="Add Slide" class="md-btn" type="button">Add Slide</button></a>
		<?php } ?>
	</h2>
    </header>
    <div class="block-content">
    	 <?php 
		 global $db;
    	$results = tep_db_query("SELECT * FROM ".TABLE_POSC_MAINSLIDER." group by `sort_order` order by `sort_order`");
    $i=1;
    ?>
        <form id="sort_form_mainslider" name='sort_form_mainslider' action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
         	<input style="display:none;" type="checkbox" value="1" name="pautoSubmit" id="pautoSubmit" checked='checked' />
            <ul id="sortable-list" class="sort_list_mainslider">
            <?php 
            $porder=array();
            if ($results->num_rows > 0) {
				while($result = tep_db_fetch_array($results)) {
					$slide_image=$slider_path.$result['slide_image'];
					?>
					<li class="item" title="<?php echo $result['slide_title']; ?>" id="<?php echo $result['id']; ?>">
					<table border="0" width="100%" align="center" class="slide-item" style='border-collapse: collapse;margin-top:0;float:left;'>
					<tr>
						<td width="5%" class="slide_numb" align="center"><?php echo $i++; ?></td>
						<td width="5%" align="center"><?php echo $result['slide_title']; ?></td>
						<td width="30%" align="center"><?php echo "<div style='max-width:236px;width:100%;display:inline-block;'><img style='height: auto; max-width: 100%; max-height: 90px; margin: 2px 10px;' src='".$slide_image."' alt=".$result['slide_title']." /></div>"; ?></td>
						<td width="20%" align="center"><?php echo ($result['slide_status'])? "<img src='includes/".FILENAME_POSC_TEMPLATE_NAME."/images/enable.png'  height='20' width='20' alt='enable' />" : "<img src='includes/".FILENAME_POSC_TEMPLATE_NAME."/images/desable.png' height='20' width='20' alt='desable' />"; ?></td>
						<td width="30%" align="right" class="action_cont">
							<a href='<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, tep_get_all_get_params(array('slide_eid')) . 'slide_eid=' .$result['id'], 'SSL'); ?>' class="md-btn" title="Edit">Edit</a>
							<a href='<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, tep_get_all_get_params(array('slide_rid')) . 'slide_rid=' .$result['id'], 'SSL'); ?>' class="md-btn" title="Delete">Delete</a>
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
        <input type="hidden" name="psort_mainslider_order" id="psort_mainslider_order" value="<?php echo implode(',',$porder); ?>" />
        </form>
	</div>
</section>
<?php } ?>
<?php if(isset($_GET['action']) || isset($_GET['slide_eid'])){ ?>
<form name='frm_posc' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<section class="block">
		<header class="block-header">
			<h2 class="title"><?php echo (isset($_GET['action']) && $_GET['action']=='slide_new' )? 'Add Slide' : 'Edit Slide'; ?></h2>
		</header>
		<div class="block-content">
			<div class="row">
				<label>Image</label>
				<div class="cont">
					<input type="file" name="slide_image" id="slide_image"  /><?php if(isset($_GET['slide_eid'])){ echo "<span class='edited_slide'><img src='$slide_image' alt='slide_image' /></span>"; } ?>
				</div>
			</div>
			<div class="row">
				<label>Content</label>
				<div class="cont">
					<?php
						// modified code for multi-language support
							for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
							  echo '<br />' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
							  echo tep_draw_textarea_field('slide_caption[' . $languages[$i]['id'] . ']', 'Desc', 38 , 5,  $slide_caption[$i+1] ,' class="noEditor md-input"', true);
							}
						// end modified code for multi-language support
					?>				
				</div>
			</div>
			<div class="row">
				<label>Link</label>
				<div class="cont">
					<input type="text" name="slide_link" class="md-input md-input-link" id="slide_link" value="<?php if(isset($_REQUEST['slide_eid'])){ echo $slide_link; } ?>" />
				</div>
			</div>
			<?php if($posc_dev_mode==1){ ?>
			<div class="row">
				<div class="cont">
					<?php 
					if(!empty($home_layout_ar)){ ?>
					<select name="slide_group">
						<?php foreach($home_layout_ar as $hk=>$hv){ ?>													
							<option value="<?php echo $hk; ?>" <?php if($slide_group==$hk){ ?> selected <?php } ?>><?php echo $hv; ?></option>
						<?php } ?>
					</select>
					<?php } ?>
					<select name="slide_style">
						<?php for($i=1;$i<=6;$i++){ ?>
							<option value="<?php echo $i; ?>" <?php if($slide_style==$i){ ?> selected <?php } ?>>style - <?php echo $i; ?></option>
					<?php }	?>
					</select>
				</div>
			</div>
			<?php }else{ ?>
				<input type='hidden' name='slide_group' value="" /> 
				<input type='hidden' name='slide_style' value="" /> 
			<?php } ?>
			<div class="row">
				<label>Status</label>
				<div class="cont">
					<?php if($banner_status==''){$banner_status=1;} ?>
					<ul class="inline-ul">
						<li><input type="radio" name="slide_status" value="1" <?php echo ($slide_status==0)? '' : 'checked="checked"';  ?>  /><span>Enable</span></li>
						<li><input type="radio" name="slide_status" value="0" <?php echo ($slide_status==0)? 'checked="checked"' : '';  ?> /><span>Disable</span></li>
					</ul>
				</div>
			</div>
			<div class="row">
				<?php if(isset($_REQUEST['slide_eid'])){ ?>
				<input type="submit" name="update_posc_slide" class="md-btn" value="Update" />
				<input type='hidden' name='slide_eid' value="<?php if(isset($_REQUEST['slide_eid'])) echo $_REQUEST['slide_eid']; ?>" />
				<input type='hidden' name='sort_order' value="<?php if($sort_order) echo $sort_order; ?>" /> 
				<?php }else{?>
				<input type="submit" class="md-btn" name="submit_posc_slide" value="Save" /><?php } ?>
				<input type="reset" class="md-btn" onclick="reset_function()" name="cancel_posc_slide" value="Cancel" />
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
	/* grab important elements */
	var sortInput_mainslider = jQuery('#psort_mainslider_order');
	//alert(JSON.stringify(sortInput));
	var submit = jQuery('#pautoSubmit');
	var messageBox = jQuery('#message-box');
	var list_mainslider = jQuery('.sort_list_mainslider');
	if(list_mainslider.children('li').size()>1){
		/* create requesting function to avoid duplicate code */
		var request_mainslider = function(){
			jQuery.ajax({
				beforeSend: function() {
					messageBox.text('Updating the sort order in the database.');
				},
				complete: function() {
					var xmlhttp
					var table_name="<?php echo TABLE_POSC_MAINSLIDER; ?>";
					var po_list=document.getElementById('psort_mainslider_order').value;
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
				data: 'sort_order=' + sortInput_mainslider[0].value + '&ajax=' + submit[0].checked + '&do_submit=1&byajax=1', //need [0]?
				type: 'post',
				url: '<?php echo $_SERVER["REQUEST_URI"]; ?>'
			});
		};
		var fnSubmit_mainslider = function(save) {
			var sortOrder = [];
			list_mainslider.children('li').each(function(){
				sortOrder.push(jQuery(this).data('id'));
			});
			sortInput_mainslider.val(sortOrder.join(','));
				console.log(sortInput_mainslider.val());
			if(save) {
				request_mainslider();
			}
		};
	/* store values */
		list_mainslider.children('li').each(function() {
			var li = jQuery(this);
				li.data('id',li.attr('id')).attr('id','');
		});
		list_mainslider.disableSelection();
	/* sortables */
		list_mainslider.sortable({
			containment: "parent",
			opacity: 0.7,
			update: function() {
				fnSubmit_mainslider(submit[0].checked);
			}
		});
		
	/* ajax form submission */
		jQuery('#sort_form_mainslider').bind('submit',function(e) {
			if(e) e.preventDefault();
				fnSubmit_mainslider(true);
		});
	}
});
</script>
<?php } ?>