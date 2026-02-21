<?php 
ob_start();
#POSC_TEMPLATE_BASE#

define('STRICT_ERROR_REPORTING', true);

require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'template_top.php');
require(DIR_WS_FUNCTIONS . 'posc_template.php');
$url=$_SERVER['REQUEST_URI'];
$cancel_url= preg_replace("/".FILENAME_POSC_TEMPLATE.".*/", FILENAME_POSC_TEMPLATE, $url);
$time=time();
$languages = tep_get_languages();			
$uploads_path="../images/posc_template/uploads/";
//create table function
//posc_create_table_sql();
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Marcus Template Settings</title>
<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800,400italic,300italic' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="../ext/css/<?php echo ((stripos(HTML_PARAMS, 'dir="rtl"') !== false) ? 'rtl_' : ''); ?>admin.css" />
<link rel="stylesheet" type="text/css" href="includes/posc_template/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="includes/posc_template/css/style.css">
<link rel="stylesheet" type="text/css" href="includes/posc_template/css/tabcontent.css" />
<link rel="stylesheet" type="text/css" href="includes/posc_template/css/mcColorPicker.css" />
<link rel="stylesheet" type="text/css" href="includes/posc_template/css/accordian.css">
<script src="includes/posc_template/js/tabcontent.js" type="text/javascript"></script>
<script src="includes/posc_template/js/mcColorPicker.js" type="text/javascript"></script>
<script src="includes/posc_template/js/jquery.min.js" type="text/javascript"></script>
<script src="includes/posc_template/js/custom.js" type="text/javascript"></script>
<?php if ($editor_handler != '') include ($editor_handler); ?>
</head>

<!-- body //-->
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="init()">
<div id="spiffycalendar" class="text"></div>
<?php 
if(isset($_POST['frm_posc_set_submit'])){
	unset($_POST['frm_posc_set_submit']);
	if(isset($_POST['news_id']) || isset($_POST['news_image'])) {
		global $db;
		$news_ids = explode("-",tep_db_prepare_input($_POST['news_id']));
		$news_id=$news_ids[0];
		$lang_id=$news_ids[1];
		$news_image = $_FILES['news_image']['name'];
		$news_image_tmp = $_FILES['news_image']['tmp_name'];
		
		if(($news_image != NULL)) {
			$time=time();
			$flext = pathinfo($news_image, PATHINFO_EXTENSION);
			$fnl=str_replace('.'.$flext,'',$news_image);
			$news_image= $fnl.'_'.$time.".".$flext;
			$news_image_update = "UPDATE " . TABLE_BOX_NEWS_CONTENT . " SET news_image='$news_image' where box_news_id='$news_id' and languages_id='$lang_id' ";
			$news_image_update_result = tep_db_query($news_image_update);
			move_uploaded_file($news_image_tmp,posc_temp_dir('temp_dir').'/images/news/'. $news_image);
		}
		unset($_POST['news_id']);
		unset($_POST['news_image']);
		unset($_FILES['news_image']);
	}
	foreach($_POST as $k=>$v){
		if(is_array($v)){
			foreach($v as $k1=>$v1){
				posc_update_options($k,tep_db_prepare_input($v1),$k1);
			}
		}else{
			posc_update_options($k,tep_db_prepare_input($v));
		}
	}
	foreach($_FILES as $k=>$v){
		posc_fileupload($k,$k);
	}
	$messageStack->add_session('Template settings has been successfully saved.', 'success');
	tep_redirect(tep_href_link(FILENAME_POSC_TEMPLATE,'','SSL')); 
}
$home_layout_ar= array(
'homepage_v1' =>'Homepage - 1',
'homepage_v2' =>'Homepage - 2',
'homepage_v3' =>'Homepage - 3',
'homepage_v4' =>'Homepage - 4',
'homepage_v5' =>'Homepage - 5',
'homepage_v6' =>'Homepage - 6',
'homepage_v7' =>'Homepage - 7'
)
?>
<!-- body //-->
<section class="main_wrapper">
   <div class="container">
   	  <div class="content">
   		<header>
        	<div class="logo">
            	<img class="logo" title="Image" alt="Image" src="includes/posc_template/images/logo.png" />
            </div>
			<a class="helpdoc_lnk" href="https://perfectusinc.com/oscommerce/marcus/documentations/" target="_blank">Help Document</a>
        </header>
        <div class="tab-wrapper">
            <ul class="tabs" data-persist="true">
				<li><a href="#view1">General</a></li>
				<li><a href="#view20">Home Page</a></li>
				<li><a href="#view2">Header</a></li> 
				<li><a href="#view3">Footer</a></li>
				<li><a href="#view4">Main Menu</a></li>
				<li><a href="#view11">Main Slider</a></li>
				<li><a href="#view12">Top Banners</a></li>
				<li><a href="#view13">Middle Banner</a></li>
				<li><a href="#view14">Bottom Banner</a></li>
				<li><a href="#view17">Category Page</a></li>
				<li><a href="#view18">Products List Page</a></li>
				<li><a href="#view19">Products Info Page</a></li>
            </ul> 
            <div class="tabcontents"> 
                <div id="view1" class="tab-content">
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<h1 class="tab-header">General</h1>
						<div class="sec_accordian">
							<section class="block">
								<header class="block-header">
									<h2 class="title">General Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Page Container :</label>
										<div class="cont">
											<?php 
												$page_container=get_posc_options('page_container');
												if($page_container==''){$page_container="1";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="page_container" value="1" <?php echo ($page_container=='1')? 'checked="checked"' : '' ?> /><span>Default (1170px)</span></li>
												<li><input type="radio" name="page_container" value="2" <?php echo ($page_container=='2')? 'checked="checked"' : '' ?> /><span>Full Page (1740px)</span></li>
											</ul>
										</div>
									</div>
									<div class="rw">
										<label>General Page Layout :</label>
										<div class="cont">
											<?php 
												$general_page_layout=get_posc_options('general_page_layout');
												if($general_page_layout==''){$general_page_layout="2columns-left";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="general_page_layout" value="1column" <?php echo ($general_page_layout=='1column')? 'checked="checked"' : '' ?> /><span>1 Columns</span></li>
												<li><input type="radio" name="general_page_layout" value="2columns-left" <?php echo ($general_page_layout=='2columns-left')? 'checked="checked"' : '' ?> /><span>2 Columns - left</span></li>
												<li><input type="radio" name="general_page_layout" value="2columns-right" <?php echo ($general_page_layout=='2columns-right')? 'checked="checked"' : '' ?> /><span>2 Columns - Right</span></li>
												<li><input type="radio" name="general_page_layout" value="3columns" <?php echo ($general_page_layout=='3columns')? 'checked="checked"' : '' ?> /><span>3 Columns</span></li>
											</ul>
										</div>
									</div>
									<div class="rw">
										<label>Page Loader :</label>
										<div class="cont">
											<?php 
												$page_loader=get_posc_options('page_loader');
												if($page_loader==''){$page_loader='default';}
											?>
											<ul class="inline-ul">
												<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_page_loader" name="page_loader" value="none" <?php echo ($page_loader=='none')? 'checked="checked"' : '' ?> /><span>NONE</span></li>
												<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_page_loader"  name="page_loader" value="default" <?php echo ($page_loader=='default')? 'checked="checked"' : '' ?> /><span>DEFAULT</span></li>
												<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_page_loader" data-target="inner_pageloader_custom" name="page_loader" value="custom" <?php echo ($page_loader=="custom")? 'checked="checked"' : '' ?> /><span>CUSTOM</span></li>
											</ul>
										</div>
										<div id="inner_pageloader_custom" class="inner_section inner_sec_page_loader" style="<?php echo ($page_loader=='custom')? 'display:block;' : 'display:none;'; ?>">
										<div class="rw">
											<div class="cont">
												<div class="rw_full">
													<div class="rw">
														<label>Loader Image :</label>
														<div class="con">
															<input type="file" value="" id="" name="page_loader_custom">
															<?php if(get_posc_options('page_loader_custom')!=''){ ?>
															<div class="file_content">
																<img src="<?php echo $uploads_path.get_posc_options('page_loader_custom'); ?>" height="auto" width="auto" title="Image" />
															</div>
															<?php } ?>
														</div>
													</div>
													<p class="notice">Please Upload Gif File.</p>
												</div>
											</div>
										</div>
									</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Colors</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Theme Color :</label>
										<div class="cont"><?php echo posc_draw_color_inputbox('theme_color'); ?></div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Fonts Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>General Font Family :</label>
										<div class="cont">
											<?php $general_font_family=get_posc_options('general_font_family'); ?>
											<?php echo posc_generate_fontfamily_pull_down('general_font_family',$general_font_family,''); ?>
											<p class="notice">Font preview is available on <a href="http://www.google.com/webfonts">Google Web Fonts</a></p>
										</div>
									</div>
									<div class="rw">
										<label>Heading Font Family :</label>
										<div class="cont">
											<?php $heading_font_family=get_posc_options('heading_font_family'); ?>
											<?php echo posc_generate_fontfamily_pull_down('heading_font_family',$heading_font_family,''); ?>
											<p class="notice">Font preview is available on <a href="http://www.google.com/webfonts">Google Web Fonts</a></p>
										</div>
									</div>
									<div class="rw">
										<label>Character Set: Latin Extended :</label>
										<div class="cont">
											<?php $font_latin_charset_extended=get_posc_options('font_latin_charset_extended'); 
												if($font_latin_charset_extended==''){ $font_latin_charset_extended=0;}
											?>
											<select name='font_latin_charset_extended'>
												<option <?php echo ($font_latin_charset_extended==1) ? 'selected="selected"' : '' ; ?>  value="1">Enable</option>
												<option <?php echo ($font_latin_charset_extended==0) ? 'selected="selected"' : '' ; ?> value="0">Disable</option>
											</select>
											<p class="notice">Only selected fonts support extended character sets. For a complete list of available fonts and font subsets please refer to <a href="http://www.google.com/webfonts">Google Web Fonts</a>.</p>
										</div>
									</div>
									<div class="rw">
										<label>Custom Character Subset :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('font_custom_charset'); ?>
											<p class="notice">Only selected fonts support character sets. For a complete list of available fonts and font subsets please refer to <a href="http://www.google.com/webfonts">Google Web Fonts</a>.  eg: greek,greek-ext </p>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Product Slider</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label class="col-md-5">Additional Image Style :</label>
										<div class="cont col-lg-7">
											<?php 
												$prod_slider_addtionalimg_style=get_posc_options('prod_slider_addtionalimg_style');
												if($prod_slider_addtionalimg_style==''){$prod_slider_addtionalimg_style=1;}
											?>
											<ul class="inline-ul">
												<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_addimgtype" name="prod_slider_addtionalimg_style" value="1" <?php echo ($prod_slider_addtionalimg_style==1)? 'checked="checked"' : '' ?> /><span>Default</span></li>
												<li><input type="radio" class="lnk_action" data-target="prod_slider_imghover_effects"  name="prod_slider_addtionalimg_style" value="2" <?php echo ($prod_slider_addtionalimg_style==2)? 'checked="checked"' : '' ?> /><span>Hover Effect</span></li>
											</ul>
										</div>
									</div>
									<div class="rw">
										<label class="col-md-5">Display Rattings :</label>
										<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_slider_rattings'); ?></div>
									</div>
									<div class="rw">
										<label class="col-md-5">Display Price :</label>
										<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_slider_price'); ?></div>
									</div>
									<div class="rw">
										<label class="col-md-5">Display Addtocart Button :</label>
										<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_slider_addtocart'); ?></div>
									</div>
									<div class="rw">
										<label class="col-md-5">Display Quickview :</label>
										<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_slider_quickview'); ?></div>
									</div>
									<div class="rw">
										<label class="col-md-5">Display Label :</label>
										<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_slider_label'); ?></div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Social Links</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Facebook :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('facebook_link'); ?>
											<p class="notice">(e.g : envato). Leave text-box empty to hide the Facebook link.</p>
										</div>
									</div>
									<div class="rw">
										<label>Twitter :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('twitter_link'); ?>
											<p class="notice">(e.g : envato). Leave text-box empty to hide the Twitter link. </p>
										</div>
									</div>
									<div class="rw">
										<label>Pinterest :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('pinterest_link'); ?>
											<p class="notice">(e.g : envato). Leave text-box empty to hide the Pinterest link. </p>
										</div>
									</div>
									<div class="rw">
										<label>Google Plus :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('google_link'); ?>
											<p class="notice">(e.g : https://plus.google.com/yourpage). Leave text-box empty to hide the Google Plus link.</p>
										</div>
									</div>
									<div class="rw">
										<label>Instagram Link :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('instagram_link'); ?>
											<p class="notice">(e.g : https://www.instagram.com/instagram). Leave text-box empty to hide the Instagram link.</p>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Instagram Feed</h2>
								</header>
								<div class="block-content">                	
									<div class="rw">
										<label>Display Instagram Feed</label>
										<div class="cont">
											<?php echo posc_draw_yesnoradio('display_instagramfeed'); ?>
										</div>
									</div>
									<div class="rw">
										<label>User Id :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('instafeed_user_id'); ?>
										</div>
									</div>
									<div class="rw">
										<label>Client Id :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('instafeed_client_id'); ?>
										</div>
									</div>
									<div class="rw">
										<label>Access Token :</label>
										<div class="cont">
											<?php echo posc_draw_inputbox('instafeed_access_token'); ?>
										</div>
									</div>
									<div class="rw">
										<label>Instagram Content :</label>
										<div class="cont">
											<?php echo posc_draw_textarea('instafeed_content'); ?>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Newsletter</h2>
								</header>
								<div class="block-content">                	
									<div class="rw">
										<label>Display Newsletter</label>
										<div class="cont">
											<?php echo posc_draw_yesnoradio('display_newsletter'); ?>
										</div>
									</div>
									<div class="rw">
										<label>Newsletter Subcribe Code for your Store (Mail Chimp Account) :</label>
										<div class="cont">
											<?php echo posc_draw_textarea('newsletter_details'); ?>
											<p class="notice">Get this code from your Mail Chimp Account. Follow instructions in Documentation to get the code.</p>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Newsletter Popup</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Display Newsletter Popup</label>
										<div class="cont">
											<?php echo posc_draw_yesnoradio('display_newsletter_popup','data-target="inner_sec_newsletterpopup" class="innersec_action"'); ?>
											<?php 
												$display_newsletter_popup=get_posc_options('display_newsletter_popup');
												if($display_newsletter_popup==''){$display_newsletter_popup=1;}
											?>
										</div>
										<div class="row">
											<div id="inner_sec_newsletterpopup" class="inner_section" style="<?php echo ($display_newsletter_popup==0)? 'display:none;': ''; ?>">
												<div class="rw">
													<label>Newsletter Popup Section</label>
													<div class="rw_division">
														<div class="rw">
															<strong class="col-md-3">Newsletter Image</strong>
															<div class="col-md-9">
																<input type="file" name="newsletter_logo" size="30">
																<?php if(get_posc_options('newsletter_logo')!=''){ ?>
																<div class="file_content">
																	<img src="<?php echo $uploads_path.get_posc_options('newsletter_logo'); ?>" title="Image" />
																</div>
																<?php } ?>
															</div>
														</div>
														<div class="rw">&nbsp;</div>
														<div class="rw">
															<strong class="col-md-3">Newsletter Text</strong>
															<div class="col-md-9">
																<?php echo posc_draw_textarea('newsletter_pop_content'); ?>
																<p class="notice">Leave empty to remove it.</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Google Map</h2>
								</header>
								<div class="block-content">                  	
									<div class="rw">
										<label>Google Map iframe code for your Store :</label>
										<div class="cont">
											<div class="cont"><?php echo posc_draw_textarea('google_map'); ?></div>
										</div>
									</div>
									<div class="row noborder">
										<p class="notice">Get this iframe code from Google Maps. Leave blank to remove Google Map from Contact Us page.</p>		
									</div>
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
                </div>
				<div id="view20" class="tab-content">
					<h1 class="tab-header">Home Page</h1>
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<div class="sec_accordian">
							<section class="block">
								<header class="block-header">
									<h2 class="title">Homepage Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Homepage Version :</label>
										<div class="cont">
											<?php 
											$homepage_version=get_posc_options('homepage_version');
											if($homepage_version==''){$homepage_version="homepage_v1";}
											if(!empty($home_layout_ar)){ ?>
											<ul class="inline-ul">
												<?php foreach($home_layout_ar as $k=>$v){ ?>													
													<li><input type="radio" name="homepage_version" value="<?php echo $k; ?>" <?php echo ($homepage_version==$k)? 'checked="checked"' : '' ?> /><span><?php echo $v; ?></span></li>
												<?php } ?>
											</ul>
											<?php }	?>
										</div>
									</div>
									<div class="rw">
										<label>Home Page Layout :</label>
										<div class="cont">
											<?php 
												$home_page_layout=get_posc_options('home_page_layout');
												if($home_page_layout==''){$home_page_layout="2columns-left";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="home_page_layout" value="1column" <?php echo ($home_page_layout=='1column')? 'checked="checked"' : '' ?> /><span>1 Columns</span></li>
												<li><input type="radio" name="home_page_layout" value="2columns-left" <?php echo ($home_page_layout=='2columns-left')? 'checked="checked"' : '' ?> /><span>2 Columns - left</span></li>
												<li><input type="radio" name="home_page_layout" value="2columns-right" <?php echo ($home_page_layout=='2columns-right')? 'checked="checked"' : '' ?> /><span>2 Columns - Right</span></li>
												<li><input type="radio" name="home_page_layout" value="3columns" <?php echo ($home_page_layout=='3columns')? 'checked="checked"' : '' ?> /><span>3 Columns</span></li>
											</ul>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Services</h2>
								</header>
								<div class="block-content">                  	
									<div class="rw">
										<label>Services Content</label>
										<div class="cont">
											<?php echo posc_draw_textarea('services_content'); ?>
											<p class="notice">Leave empty to hide the services content.</p>
										</div>
									</div>
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
				</div>
				<div id="view2" class="tab-content">
					<h1 class="tab-header">Header</h1>
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<div class="sec_accordian">
							<section class="block">
								<header class="block-header">
									<h2 class="title">Header</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Header Style :</label>
										<div class="cont">
											<?php 
												$headerstyle=get_posc_options('header_style');
												if($headerstyle==''){$headerstyle="header_v1";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="header_style" value="header_v1" <?php echo ($headerstyle=='header_v1')? 'checked="checked"' : '' ?> /><span>Header Style - 1</span></li>
												<li><input type="radio" name="header_style" value="header_v2" <?php echo ($headerstyle=='header_v2')? 'checked="checked"' : '' ?> /><span>Header Style - 2</span></li>
												<li><input type="radio" name="header_style" value="header_v3" <?php echo ($headerstyle=='header_v3')? 'checked="checked"' : '' ?> /><span>Header Style - 3</span></li>
												<li><input type="radio" name="header_style" value="header_v4" <?php echo ($headerstyle=='header_v4')? 'checked="checked"' : '' ?> /><span>Header Style - 4</span></li>
												<li><input type="radio" name="header_style" value="header_v5" <?php echo ($headerstyle=='header_v5')? 'checked="checked"' : '' ?> /><span>Header Style - 5</span></li>
												<li><input type="radio" name="header_style" value="header_v6" <?php echo ($headerstyle=='header_v6')? 'checked="checked"' : '' ?> /><span>Header Style - 6</span></li>
											</ul>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Store Logos</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Logo</label>
										<div class="cont">
											<input type="file" value="logo.png" id="file_logo" name="file_logo" size="30">
											<?php if(get_posc_options('file_logo')!=''){ ?>
											<div class="file_content">
												<img src="<?php echo $uploads_path.get_posc_options('file_logo'); ?>" title="Site Logo" />
											</div>
											<?php } ?>
										</div>
									</div>
									<div class="rw">
										<label>Favicon</label>
										<div class="cont">
											<input type="file" value="file_favicon.png" id="file_favicon" name="file_favicon" size="30">
											<?php if(get_posc_options('file_favicon')!=''){ ?>
											<div class="file_content">
												<img src="<?php echo $uploads_path.get_posc_options('file_favicon'); ?>"  title="Site Favicon Icon" />
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Store Info</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Contact Number :</label>
										<div class="cont"><?php echo posc_draw_inputbox('header_store_contact'); ?></div>
									</div>
									<div class="rw">
										<label>Time :</label>
										<div class="cont"><?php echo posc_draw_inputbox('header_store_time'); ?></div>
									</div>
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
				</div>
				<div id="view3" class="tab-content">
                    <h1 class="tab-header">Footer</h1>
					<div class="sec_accordian">
						<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
							<section class="block">
								<header class="block-header">
									<h2 class="title">Footer Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Footer Style</label>
										<div class="cont">
											<?php 
												$footerstyle=get_posc_options('footer_style');
												if($footerstyle==''){$footerstyle="footer_v1";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="footer_style" value="footer_v1" <?php echo ($footerstyle=='footer_v1')? 'checked="checked"' : '' ?> /><span>Foooter Style - 1</span></li>
												<li><input type="radio" name="footer_style" value="footer_v2" <?php echo ($footerstyle=='footer_v2')? 'checked="checked"' : '' ?> /><span>Foooter Style - 2</span></li>
												<li><input type="radio" name="footer_style" value="footer_v3" <?php echo ($footerstyle=='footer_v3')? 'checked="checked"' : '' ?> /><span>Foooter Style - 3</span></li>
												<li><input type="radio" name="footer_style" value="footer_v4" <?php echo ($footerstyle=='footer_v4')? 'checked="checked"' : '' ?> /><span>Foooter Style - 4</span></li>
												<li><input type="radio" name="footer_style" value="footer_v5" <?php echo ($footerstyle=='footer_v5')? 'checked="checked"' : '' ?> /><span>Foooter Style - 5</span></li>
												<li><input type="radio" name="footer_style" value="footer_v6" <?php echo ($footerstyle=='footer_v6')? 'checked="checked"' : '' ?> /><span>Foooter Style - 6</span></li>
											</ul>
										</div>
									</div>
									<div class="rw">
										<label>Payment Image</label>
										<div class="cont">
											<input type="file" value="payment_image.png" id="payment_image" name="payment_image" size="30">
											<?php if(get_posc_options('payment_image')!=''){ ?>
											<div class="file_content">
												<img src="<?php echo $uploads_path.get_posc_options('payment_image'); ?>" title="Payment Image" />
											</div>
											<?php } ?>
										</div>
									</div>
									<div class="rw">
										<label>Copyrights Text</label>
									   <div class="cont">
											<div class="cont"><?php echo posc_draw_textarea('store_copyright'); ?></div>
										</div>
									</div>
								</div>
							</section>
							<section class="block">
								<header class="block-header">
									<h2 class="title">Store Info</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Footer Logo</label>
										<div class="cont">
											<input type="file" value="footer_logo.png" id="footer_logo" name="footer_logo" size="30">
											<?php if(get_posc_options('footer_logo')!=''){ ?>
											<div class="file_content">
												<img src="<?php echo $uploads_path.get_posc_options('footer_logo'); ?>" title="Footer Logo" />
											</div>
											<?php } ?>
										</div>
									</div>
									<div class="rw">
										<label>Store Address :</label>
										<div class="cont">
											<div class="cont"><?php echo posc_draw_textarea('store_address'); ?></div>
										</div>
									</div>
									<?php /*<div class="rw">
										<label>Contact Number :</label>
										<div class="cont"><?php echo posc_draw_inputbox('store_contact'); ?></div>
									</div>
									<div class="rw">
										<label>Fax :</label>
										<div class="cont"><?php echo posc_draw_inputbox('store_fax'); ?></div>
									</div>
									<div class="rw">
										<label>Skype :</label>
										<div class="cont"><?php echo posc_draw_inputbox('store_skype'); ?></div>
									</div>
									<div class="rw">
										<label>Email :</label>
										<div class="cont"><?php echo posc_draw_inputbox('store_email'); ?></div>
									</div>*/ ?>
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
                </div>
				<div id="view4"class="main-menu tab-content">
					<h1 class="tab-header">Main Menu</h1>
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<section class="block-static single-block">
								<header class="block-header">
									<h2 class="title">Menu Settings</h2>
								</header>
								<div class="block-content">
									<div class="row">
										<label>Menu Type :</label>
										<div class="cont">
											<?php 
												$menutype=get_posc_options('menu_type');
												if($menutype==''){$menutype=1;}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="menu_type" value="1" <?php echo ($menutype==1)? 'checked="checked"' : '' ?> /><span>Simple Menu</span></li>
												<li><input type="radio" name="menu_type" value="2" <?php echo ($menutype==2)? 'checked="checked"' : '' ?> /><span>Mega Menu</span></li>
											</ul>
										</div>
									</div>
								</div>
						</section>
						<div class="sec_accordian">
						<?php 
							global $languages_id, $db;
							$cat_array = array();
							$result = tep_db_query('select c.categories_id, cd.categories_name, c.parent_id from ' . TABLE_CATEGORIES . ' c, ' . TABLE_CATEGORIES_DESCRIPTION . ' cd where c.categories_id = cd.categories_id and cd.language_id="' . (int)$languages_id .'" order by c.parent_id, sort_order, cd.categories_name');
    
							while ($row = tep_db_fetch_array($result)) {				
								$cat_array[$row['parent_id']][$row['categories_id']] = array('name' => $row['categories_name'], 'count' => 0);  ;
							}
						?>
						<?php foreach($cat_array[0] as $k0=>$v0){ ?>
							<section class="block">
								<header class="block-header">
									<h2 class="title"><?php echo $v0['name']; ?></h2>
								</header>
								<div class="block-content">
									<div class="row">
										<label>Display In Menu :</label>
										<div class="cont">
											<?php echo posc_draw_yesnoradio('display_in_hor_menu_'.$k0); ?>
										</div>
									</div>
									<div class="row">
										<label>Menu Type :</label>
										<div class="cont">
											<?php $menu_type=get_posc_options('menu_type_'.$k0); 
												if($menu_type==''){ $menu_type=1;}
											?>
											<select name='menu_type_<?php echo $k0; ?>'>
												<option <?php echo ($menu_type==1) ? 'selected="selected"' : '' ; ?>  value="1">Megamenu</option>
												<option <?php echo ($menu_type==2) ? 'selected="selected"' : '' ; ?> value="2">Classic</option>
											</select>
										</div>
									</div>
									<div class="row">
										<label>Badge :</label>
										<div class="cont">
											<?php 
												$badge_type=get_posc_options('badge_type_'.$k0);
												if($badge_type==''){$badge_type=0;}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="badge_type_<?php echo $k0; ?>" value="1" <?php echo ($badge_type==1)? 'checked="checked"' : '' ?> /><span>New</span></li>
												<li><input type="radio" name="badge_type_<?php echo $k0; ?>" value="2" <?php echo ($badge_type==2)? 'checked="checked"' : '' ?> /><span>Sale</span></li>
												<li><input type="radio" name="badge_type_<?php echo $k0; ?>" value="0" <?php echo ($badge_type==0)? 'checked="checked"' : '' ?> /><span>None</span></li>
											</ul>
										</div>
									</div>
									<?php /*
									<div class="row">
										<label><?php echo PZEN_LABEL_; ?>Subcategory Display Mark :</label>
										<div class="cont"><?php echo pzen_draw_yesnoradio('subcat_marked_'.$k0); ?></div>
									</div> */?>
									<div class="row">
										<label>Display Subcategory Image:</label>
										<div class="cont"><?php echo posc_draw_yesnoradio('subcat_imgstatus_'.$k0); ?></div>
									</div>
									<div class="row">
										<label>Megamenu Side Block:</label>
										<div class="cont">
											<?php 
												$mg_blk_type=get_posc_options('megamenu_btype_'.$k0);
												if($mg_blk_type==''){$mg_blk_type=0;}
											?>
											<ul class="inline-ul">
												<li><input class="innersec_action" type="radio" name="megamenu_btype_<?php echo $k0; ?>" value="1" data-target="inner_sec_sdblk_<?php echo $k0; ?>" <?php echo ($mg_blk_type==1)? 'checked="checked"' : '' ?> /><span>Special</span></li>
												<li><input class="innersec_action" type="radio" name="megamenu_btype_<?php echo $k0; ?>" value="2" data-target="inner_sec_sdblk_<?php echo $k0; ?>" <?php echo ($mg_blk_type==2)? 'checked="checked"' : '' ?> /><span>Featured</span></li>
												<li><input class="innersec_action" type="radio" name="megamenu_btype_<?php echo $k0; ?>" value="0" data-target="inner_sec_sdblk_<?php echo $k0; ?>" <?php echo ($mg_blk_type==0)? 'checked="checked"' : '' ?> /><span>None</span></li>
											</ul>
											<div id="inner_sec_sdblk_<?php echo $k0; ?>" class="inner_section" style="<?php echo ($mg_blk_type==0)? 'display:none;': ''; ?>">
												<div class="noborder">
													<p class="notice">Please select below options to view side blocks.</p>
												</div>
												<?php 
													$mg_blk_type_view=get_posc_options('megamenu_btype_view_'.$k0);
													if($mg_blk_type_view==''){$mg_blk_type_view=2;}
												?>
												<ul class="inline-ul">
													<li><input type="radio" name="megamenu_btype_view_<?php echo $k0; ?>" value="1" <?php echo ($mg_blk_type_view==1)? 'checked="checked"' : '' ?> /><span>Display as Slider</span></li>
													<li><input type="radio" name="megamenu_btype_view_<?php echo $k0; ?>" value="2" <?php echo ($mg_blk_type_view==2)? 'checked="checked"' : '' ?> /><span>Display without Slider</span></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="row">
										<label>Megamenu Bottom Block :</label>
										<div class="cont">
											<?php echo posc_draw_yesnoradio('megamenu_bottom_block_'.$k0,'data-target="inner_sec_botblk_'.$k0.'" class="innersec_action"'); ?>
											<?php 
													$mb_bt_block=get_posc_options('megamenu_bottom_block_'.$k0);
													if($mb_bt_block==''){$mb_bt_block=0;}
												?>
											<div id="inner_sec_botblk_<?php echo $k0; ?>" class="inner_section" style="<?php echo ($mb_bt_block==0)? 'display:none;': ''; ?>">
												<div class="rw">
													<label>Banner Content</label>
													<div class="cont">
														<div class="rw_division">
															<div class="rw">
																<label>Image</label>
																<input type="file" value="" id="" name="mg_botban_cont_0_<?php echo $k0; ?>_img" size="30">
																<?php if(get_posc_options('mg_botban_cont_0_'.$k0.'_img')!=''){ ?>
																<div class="file_content">
																	<img src="<?php echo $uploads_path.get_posc_options('mg_botban_cont_0_'.$k0.'_img'); ?>" height="auto" width="auto" title="Image" />
																</div>
																<?php } ?>
															</div>
															<div class="rw">
																<label>Link</label>
																<?php echo posc_draw_inputbox('mg_botban_cont_0_'.$k0.'_link'); ?>
															</div>
														</div>
														<div class="rw_division">
															<div class="rw">
																<label>Image</label>
																<input type="file" value="" id="" name="mg_botban_cont_1_<?php echo $k0; ?>_img" size="30">
																<?php if(get_posc_options('mg_botban_cont_1_'.$k0.'_img')!=''){ ?>
																<div class="file_content">
																	<img src="<?php echo $uploads_path.get_posc_options('mg_botban_cont_1_'.$k0.'_img'); ?>" height="auto" width="auto" title="Image" />
																</div>
																<?php } ?>
															</div>
															<div class="rw">
																<label>Link</label>
																<?php echo posc_draw_inputbox('mg_botban_cont_1_'.$k0.'_link'); ?>
															</div>
														</div>
														
												</div>
											</div>
										</div>
									</div>
							</section>
						<?php } ?>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
                </div>
				<div id="view11"class="main_slider tab-content">
					<?php 
						require_once('posc_mainslider.php');
					?>
                </div>
				<div id="view12"class="topbanner_slider tab-content">
					<?php 
						require_once('posc_topbanner.php');
					?>
                </div>
				<div id="view13"class="middlebanner_content tab-content">
                    <h1 class="tab-header">Middle Banner</h1>
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<div class="sec_accordian">
							<section class="block">
								<header class="block-header">
									<h2 class="title">Middle Banner Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Display Middle Banner</label>
										<div class="cont">
											<?php echo posc_draw_yesnoradio('display_middle_banner','data-target="inner_sec_midbannerstatus" class="innersec_action"'); ?>
											<?php 
												$midbanner_status=get_posc_options('display_middle_banner');
												if($midbanner_status==''){$midbanner_status=1;}
											?>
										</div>
										<div class="row">
											<div id="inner_sec_midbannerstatus" class="inner_section" style="<?php echo ($midbanner_status==0)? 'display:none;': ''; ?>">
												<div class="rw">
													<label>Middle Banner Section</label>
													<div class="rw_division">
														<div class="rw">
															<strong class="col-md-3">Banner Image</strong>
															<div class="col-md-9">
																<input type="file" id="middle_banner_image" name="middle_banner_image" size="30">
																<?php if(get_posc_options('middle_banner_image')!=''){ ?>
																<div class="file_content">
																	<img src="<?php echo $uploads_path.get_posc_options('middle_banner_image'); ?>" title="Image" />
																</div>
																<?php } ?>
															</div>
														</div>
														<div class="rw">&nbsp;</div>
														<div class="rw">
															<strong class="col-md-3">Content</strong>
															<div class="col-md-9">
																<?php echo posc_draw_langtextarea('middle_banner_caption'); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="rw">
										<label>Display Middle Banner2</label>
										<div class="cont">
											<?php echo posc_draw_yesnoradio('display_middle_banner2','data-target="inner_sec_midbannerstatus2" class="innersec_action"'); ?>
											<?php 
												$midbanner_status2=get_posc_options('display_middle_banner2');
												if($midbanner_status2==''){$midbanner_status2=1;}
											?>
										</div>
										<div class="row">
											<div id="inner_sec_midbannerstatus2" class="inner_section" style="<?php echo ($midbanner_status2==0)? 'display:none;': ''; ?>">
												<div class="rw">
													<label>Middle Banner Section2</label>
													<div class="rw_division">
														<div class="rw">
															<strong class="col-md-3">Banner Image</strong>
															<div class="col-md-9">
																<input type="file" id="middle_banner_image2" name="middle_banner_image2" size="30">
																<?php if(get_posc_options('middle_banner_image2')!=''){ ?>
																<div class="file_content">
																	<img src="<?php echo $uploads_path.get_posc_options('middle_banner_image2'); ?>" title="Image" />
																</div>
																<?php } ?>
															</div>
														</div>
														<div class="rw">&nbsp;</div>
														<div class="rw">
															<strong class="col-md-3">Content</strong>
															<div class="col-md-9">
																<?php echo posc_draw_langtextarea('middle_banner_caption2'); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
                </div>
				<div id="view14"class="bottombanner_slider tab-content">
					<?php 
						require('posc_bottombanner.php');
					?> 
                </div>
				<div id="view17" class="tab-content">
					<h1 class="tab-header">Categories</h1>
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<div class="sec_accordian">
							<section class="block">
								<header class="block-header">
									<h2 class="title">Categories Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Category Page Layout :</label>
										<div class="cont">
											<?php 
												$cat_page_layout=get_posc_options('cat_page_layout');
												if($cat_page_layout==''){$cat_page_layout="2columns-left";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="cat_page_layout" value="1column" <?php echo ($cat_page_layout=='1column')? 'checked="checked"' : '' ?> /><span>1 Columns</span></li>
												<li><input type="radio" name="cat_page_layout" value="2columns-left" <?php echo ($cat_page_layout=='2columns-left')? 'checked="checked"' : '' ?> /><span>2 Columns - left</span></li>
												<li><input type="radio" name="cat_page_layout" value="2columns-right" <?php echo ($cat_page_layout=='2columns-right')? 'checked="checked"' : '' ?> /><span>2 Columns - Right</span></li>
												<li><input type="radio" name="cat_page_layout" value="3columns" <?php echo ($prodlist_page_layout=='3columns')? 'checked="checked"' : '' ?> /><span>3 Columns</span></li>
											</ul>
										</div>
									</div>
									<div class="row">
										<div id="" class="inner_section" style="<?php echo ($midbanner_status==0)? 'display:none;': ''; ?>">
											<div class="rw">
												<label>Categories List</label>
												<?php $column_nums_ar =  array(
															1 => 1,
															2 => 2,
															3 => 3,
															4 => 4,
															5 => 5,
															6 => 6,
															7 => 7,
															8 => 8,
															); 
															?>
												<div class="rw_division block-content">
													<div class="rw">
														<strong class="col-md-6">Number of Columns Above 1740px</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('catgrid_col_xl', $column_nums_ar, get_posc_options('catgrid_col_xl')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (1199px - 1740px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('catgrid_col_lg', $column_nums_ar, get_posc_options('catgrid_col_lg')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (992px - 1198px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('catgrid_col_md', $column_nums_ar, get_posc_options('catgrid_col_md')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (768px - 991px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('catgrid_col_sm', $column_nums_ar, get_posc_options('catgrid_col_sm')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (480px - 767px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('catgrid_col_xs', $column_nums_ar, get_posc_options('catgrid_col_xs') ); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns Below 480px</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('catgrid_col_xxs', $column_nums_ar, get_posc_options('catgrid_col_xxs')); ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
				</div>
				<div id="view18" class="tab-content">
					<h1 class="tab-header">Products List Page</h1>
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<div class="sec_accordian">
							<section class="block">
								<header class="block-header">
									<h2 class="title">Products List Page Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Product List Page Layout :</label>
										<div class="cont">
											<?php 
												$prodlist_page_layout=get_posc_options('prodlist_page_layout');
												if($prodlist_page_layout==''){$prodlist_page_layout="2columns-left";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="prodlist_page_layout" value="1column" <?php echo ($prodlist_page_layout=='1column')? 'checked="checked"' : '' ?> /><span>1 Columns</span></li>
												<li><input type="radio" name="prodlist_page_layout" value="2columns-left" <?php echo ($prodlist_page_layout=='2columns-left')? 'checked="checked"' : '' ?> /><span>2 Columns - left</span></li>
												<li><input type="radio" name="prodlist_page_layout" value="2columns-right" <?php echo ($prodlist_page_layout=='2columns-right')? 'checked="checked"' : '' ?> /><span>2 Columns - Right</span></li>
												<li><input type="radio" name="prodlist_page_layout" value="3columns" <?php echo ($prodlist_page_layout=='3columns')? 'checked="checked"' : '' ?> /><span>3 Columns</span></li>
											</ul>
										</div>
									</div>
									<div class="row">
										<div id="" class="inner_section">
											<div class="rw">
												<label>Products List</label>
												<?php $column_nums_ar =  array(
															1 => 1,
															2 => 2,
															3 => 3,
															4 => 4,
															5 => 5,
															6 => 6,
															7 => 7,
															8 => 8,
															); 
															?>
												<div class="rw_division block-content">
													<div class="rw">
														<strong class="col-md-6">Number of Columns Above 1740px</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodgrid_col_xl', $column_nums_ar, get_posc_options('prodgrid_col_xl')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (1199px - 1740px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodgrid_col_lg', $column_nums_ar, get_posc_options('prodgrid_col_lg')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (992px - 1198px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodgrid_col_md', $column_nums_ar, get_posc_options('prodgrid_col_md')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (768px - 991px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodgrid_col_sm', $column_nums_ar, get_posc_options('prodgrid_col_sm')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (480px - 767px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodgrid_col_xs', $column_nums_ar, get_posc_options('prodgrid_col_xs') ); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns Below 480px</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodgrid_col_xxs', $column_nums_ar, get_posc_options('prodgrid_col_xxs')); ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div id="" class="inner_section" style="<?php echo ($midbanner_status==0)? 'display:none;': ''; ?>">
											<div class="rw">
												<label>Product Configuration</label>
												<div class="rw_division block-content">
													<div class="rw">
														<label class="col-md-6">Additional Image Style :</label>
														<div class="col-md-6">
															<?php 
																$prod_list_addtionalimg_style=get_posc_options('prod_list_addtionalimg_style');
																if($prod_list_addtionalimg_style==''){$prod_list_addtionalimg_style=1;}
															?>
															<ul class="inline-ul">
																<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_prodlist_addimgtype" name="prod_list_addtionalimg_style" value="1" <?php echo ($prod_list_addtionalimg_style==1)? 'checked="checked"' : '' ?> /><span>None</span></li>
																<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_prodlist_addimgtype"  name="prod_list_addtionalimg_style" data-target="inner_sec_prodlist_hover_effect" value="2" <?php echo ($prod_list_addtionalimg_style==2)? 'checked="checked"' : '' ?> /><span>Hover Effect</span></li>
															</ul>
														</div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Rattings :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_list_rattings'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Price :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_list_price'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Addtocart Button :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_list_addtocart'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Quickview :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_list_quickview'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Label on Product :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_list_label'); ?></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
				</div>
				<div id="view19" class="tab-content">
					<h1 class="tab-header">Products Info Page</h1>
					<form name='frm_posc' action="<?php echo tep_href_link(FILENAME_POSC_TEMPLATE, '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
						<div class="sec_accordian">
							<section class="block">
								<header class="block-header">
									<h2 class="title">Products Info Page Settings</h2>
								</header>
								<div class="block-content">
									<div class="rw">
										<label>Product Info Page Layout :</label>
										<div class="cont">
											<?php 
												$prodinfo_page_layout=get_posc_options('prodinfo_page_layout');
												if($prodinfo_page_layout==''){$prodinfo_page_layout="2columns-left";}
											?>
											<ul class="inline-ul">
												<li><input type="radio" name="prodinfo_page_layout" value="1column" <?php echo ($prodinfo_page_layout=='1column')? 'checked="checked"' : '' ?> /><span>1 Columns</span></li>
												<li><input type="radio" name="prodinfo_page_layout" value="2columns-left" <?php echo ($prodinfo_page_layout=='2columns-left')? 'checked="checked"' : '' ?> /><span>2 Columns - left</span></li>
												<li><input type="radio" name="prodinfo_page_layout" value="2columns-right" <?php echo ($prodinfo_page_layout=='2columns-right')? 'checked="checked"' : '' ?> /><span>2 Columns - Right</span></li>
												<li><input type="radio" name="prodinfo_page_layout" value="3columns" <?php echo ($prodinfo_page_layout=='3columns')? 'checked="checked"' : '' ?> /><span>3 Columns</span></li>
											</ul>
										</div>
									</div>
									<div class="rw">
										<label>Product Image Layout :</label>
										<?php 
											$prod_img_layout=get_posc_options('prod_img_layout');
											if($prod_img_layout==''){$prod_img_layout=1;}
										?>
										<ul class="inline-ul">
											<li><input type="radio" class="lnk_action" name="prod_img_layout" value="1" <?php echo ($prod_img_layout==1)? 'checked="checked"' : '' ?> /><span>Big Size</span></li>
											<li><input type="radio" class="lnk_action" name="prod_img_layout" value="2" <?php echo ($prod_img_layout==2)? 'checked="checked"' : '' ?> /><span>Mediaum Size</span></li>
											<li><input type="radio" class="lnk_action" name="prod_img_layout" value="3" <?php echo ($prod_img_layout==3)? 'checked="checked"' : '' ?> /><span>Small Size</span></li>
										</ul>
									</div>
									<div class="row">
										<div id="" class="inner_section">
											<div class="rw">
												<label>Product Configuration</label>
												<div class="rw_division block-content">
													<div class="rw">
														<label class="col-md-5">Display Short Description :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_short_desc'); ?></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div id="" class="inner_section">
											<div class="rw">
												<label>Products Slider</label>
												<?php $column_nums_ar =  array(
															1 => 1,
															2 => 2,
															3 => 3,
															4 => 4,
															5 => 5,
															6 => 6,
															7 => 7,
															8 => 8,
															); 
															?>
												<div class="rw_division block-content">
													<div class="rw">
														<strong class="col-md-6">Number of Columns Above 1740px</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodinfo_col_xl', $column_nums_ar, get_posc_options('prodinfo_col_xl')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (1199px - 1740px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodinfo_col_lg', $column_nums_ar, get_posc_options('prodinfo_col_lg')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (992px - 1198px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodinfo_col_md', $column_nums_ar, get_posc_options('prodinfo_col_md')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (768px - 991px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodinfo_col_sm', $column_nums_ar, get_posc_options('prodinfo_col_sm')); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns within (480px - 767px)</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodinfo_col_xs', $column_nums_ar, get_posc_options('prodinfo_col_xs') ); ?>
														</div>
													</div>
													<div class="rw">
														<strong class="col-md-6">Number of Columns Below 480px</strong>
														<div class="col-md-6">
															<?php echo posc_draw_selectbox('prodinfo_col_xxs', $column_nums_ar, get_posc_options('prodinfo_col_xxs')); ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div id="" class="inner_section">
											<div class="rw">
												<label>Product Slider Configuration</label>
												<div class="rw_division block-content">
													<div class="rw">
														<label class="col-md-6">Additional Image Style :</label>
														<div class="col-md-6">
															<?php 
																$prod_info_addtionalimg_style=get_posc_options('prod_info_addtionalimg_style');
																if($prod_info_addtionalimg_style==''){$prod_info_addtionalimg_style=1;}
															?>
															<ul class="inline-ul">
																<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_prodlist_addimgtype" name="prod_info_addtionalimg_style" value="1" <?php echo ($prod_info_addtionalimg_style==1)? 'checked="checked"' : '' ?> /><span>None</span></li>
																<li><input type="radio" class="lnk_action" data-tarlnk="inner_sec_prodlist_addimgtype"  name="prod_info_addtionalimg_style" data-target="inner_sec_prodlist_hover_effect" value="2" <?php echo ($prod_info_addtionalimg_style==2)? 'checked="checked"' : '' ?> /><span>Hover Effect</span></li>
															</ul>
														</div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Price :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_info_price'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Rattings :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_info_rattings'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Addtocart Button :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_info_addtocart'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Quickview :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_info_quickview'); ?></div>
													</div>
													<div class="rw">
														<label class="col-md-5">Display Label on Product :</label>
														<div class="col-lg-7"><?php echo posc_draw_yesnoradio('display_prod_info_salelabel'); ?></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<input type="hidden" name="frm_posc_set_submit" value="" />
					</form>
				</div>
            </div>
        </div>
        <footer>
			<?php if((!isset($_GET['botbeid'])) && (!isset($_GET['beid'])) && (!isset($_GET['slideshow_eid'])) && (!isset($_GET['action']))){ ?>
				<input type="button" class="md-btn posc_save_settings" name="frm_posc_set_submit" value="Save Settings" />
			<?php } ?>
			<br/><br/>
			<div class="alert alert-danger">
            	<strong>Kindly Note : </strong>For any CSS changes in the template, please add your custom CSS in <strong>style_user_custom.css</strong> file, which can be found under <strong>ext/css</strong> directory. Changes done in any other template defined CSS files may lost in future theme updates.
            </div>
        </footer>
   	 </div>
	</div>
</section>
<script>
	$(document).ready(function(){
		$(".posc_save_settings").click(function(){
			$('.tab-content[style="display: block;"]').find('form[name="frm_posc"]').submit();
		});
	});
</script>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<!-- footer_eof //-->
<script src="includes/posc_template/js/jquery-ui.js" type="text/javascript"></script>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
<?php ob_flush(); ?>