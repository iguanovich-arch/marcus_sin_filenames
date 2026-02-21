<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  if ($messageStack->size('header') > 0) {
    echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
  }
	
	if ( file_exists(DIR_WS_MODULES.'cat_navbar.php') ) {
                 require_once(DIR_WS_MODULES.'cat_navbar.php');
    }

?>

<!-- Header Container Starts -->
<header>
	
	<!-- Slider Starts -->
	<?php	if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) { ?>
		<?php include(DIR_WS_TEMPLATE . 'define_templates/define_main_slider.php'); ?>
	<?php } ?>
	<!-- Slider Ends Here -->
	
	<!-- Menu starts -->
	<section class="top-md-menu">			
		<div class="main-menu">
			<nav id="mainNav" class="navbar navbar-inverse navbar-default navbar-fixed-top">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<?php if($logo != NULL){?>
                        <a class="navbar-brand" href="<?php echo tep_href_link('index.php') ?>">
                            <img src="<?php echo 'images/backend_image/logo/'.$logo; ?>" alt="logo" />
                        </a>
						<?php } ?>  
					</div>
					<!-- Brand and toggle get grouped for better mobile display Ends Here -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" data-hover="dropdown" data-animations=" fadeInLeft fadeInUp fadeInRight">
						<ul class="nav navbar-nav navbar-right">
							 <li id="home"><a href="index.php"><?php printf(BOX_MANUFACTURER_INFO_HOMEPAGE,"")?></a></li>
                            <!--Superfish Horizontal Navigation bar-->
                            <?php
                                echo $before_html;	
                                echo $categories_string;
                                echo $after_html;
                            ?>
                            <!--end Superfish-->
                            </ul>
                            </li>
                            <li class="basket-holder">
                                <div class="basket">
                                    <div class="basket-icon">
                                        <a href="<?php echo tep_href_link('shopping_cart.php') ?>">
                                        <img class="svg" alt="basket" src="ext/images/icons/basket.svg">
                                        <?php echo HEADER_TITLE_CART_CONTENTS . 
                                       ($cart->count_contents() > 0 ? ' (' . $cart->count_contents() . ')' : '') ?>
                                        </a>
                                    </div>
                                </div>
                            </li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
	</section>
	<!-- Menu Ends Here -->
	
	<!-- banner -->
	<?php if((basename($PHP_SELF) == 'index.php' && $cPath == '') && !isset($_GET['manufacturers_id']) ) { ?>
	<section class="banner homebanner">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<div class="banner-img">
						<div class="ads">
							<img src="<?php echo 'images/backend_image/banner/'.$banner1; ?>"  alt="add-banner">
							<div class="ads-text">
								<?php echo $bannercaptions1; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<!-- banner-img -->
					<div class="banner-img">
						<img src="<?php echo 'images/backend_image/banner/'.$banner2; ?>"  alt="add-banner">
						<div class="ads-text">
							<?php echo $bannercaptions2; ?>
						</div>
					</div>
					<!-- /banner-img -->
					<!-- banner-img -->
					<div class="banner-img bmgr">
						<img src="<?php echo 'images/backend_image/banner/'.$banner3; ?>" alt="add-banner">
						<div class="ads-text">
							<?php echo $bannercaptions3; ?>
						</div>
					</div>
					<!-- /banner-img -->
				</div>
				<div class="col-sm-3">
					<!-- banner-img -->
					<div class="banner-img">
						<img src="<?php echo 'images/backend_image/banner/'.$banner4; ?>" alt="add-banner">
						<div class="ads-text">
							<?php echo $bannercaptions4; ?>
						</div>
					</div>
					<!-- /banner-img -->
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
	<!-- /banner -->
	
</header>
<!-- Header Container Ends -->
 
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>
