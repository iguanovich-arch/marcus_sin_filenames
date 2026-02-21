<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/


?>
<footer>
	<!-- Newsletter Section -->
	<?php if($display_newsletter==1) {?>
	<div class="newsletter">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="sing-up-input">
						<!-- Begin MailChimp Signup Form -->
						<?php echo $newsletter_details; ?>
						<!--End mc_embed_signup-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<!-- Newsletter Section Ends -->
	<!-- instagram-area-start -->
	<div class="instagram-area">
		<?php include(DIR_WS_TEMPLATE . 'define_templates/define_instagram_feed.php'); ?>
	</div>
	<!-- instagram-area-end -->	
	
	<!-- Copy Right Section -->
	<div class="container">
		<div class="row">
			<div class="copayright">
				<div class="col-md-5 ctext">
					&copy; <?php echo $store_copyright; ?>
				</div>
				<div class="col-md-3 text-center">
					<img src="<?php echo TEMPLATE_IMG_DIR.'uploads/'.$file_logo; ?>" alt="logo" />
				</div>
				<div class="col-md-4 text-right csocial">
					<ul>
						<?php if($facebook_link){?> <li><a class="fac" target="_blank" href="https://www.facebook.com/<?php echo $facebook_link; ?>"><i class="fa fa-facebook"></i></a> </li><?php } ?>
						<?php if($twitter_link){?><li><a class="twi" target="_blank" href="https://twitter.com/<?php echo $twitter_link; ?>"><i class="fa fa-twitter"></i></a></li><?php } ?>
						<?php if($google_link){ ?><li><a class="goo" target="_blank" href="<?php echo $google_link; ?>"><i class="fa fa-google-plus"></i></a></li><?php } ?>
						<?php if($pinterest_link){ ?><li><a class="pin" target="_blank" href="https://www.pinterest.com/<?php echo $pinterest_link; ?>"><i class="fa fa-pinterest"></i></a></li><?php } ?>
						<?php if($instagram_link){ ?><li><a class="insta" target="_blank" href="https://www.instagram.com/<?php echo $instagram_link; ?>"><i aria-hidden="true" class="fa fa-instagram"></i></a> </li><?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- Copy Right Section Ends -->
</footer>