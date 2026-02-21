<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- footer -->
<footer class="footer-4 f4">
	<div class="container">
		<div class="row">
			<div class="footer-5">
				<?php if($display_newsletter==1) {?>
				<div class="col-md-4 col-sm-5">
					<!-- f-weghit2 -->
					<div class="f-weghit3">
						<div class="newletter-outer">
							<?php echo $newsletter_details; ?>
						</div>
					</div>
					<!-- /f-weghit2 -->
				</div>
				<?php } ?>
				<div class="col-md-5 col-sm-3 text-center">
					<!-- f-weghit2 -->
					<div class="f-weghit3">
						<img src="<?php echo TEMPLATE_IMG_DIR.'uploads/'.$footer_logo; ?>" alt="logo" />
					</div>
					<!-- /f-weghit2 -->
				</div>
				<div class="col-md-3 col-sm-4">
					<!-- f-weghit2 -->
					<div class="f-weghit3">
						<div class="scoial-footer">
							<ul>
								<?php if($facebook_link){?> <li><a class="fac" target="_blank" href="https://www.facebook.com/<?php echo $facebook_link; ?>"><i class="fa fa-facebook"></i></a> </li><?php } ?>
								<?php if($twitter_link){?><li><a class="twi" target="_blank" href="https://twitter.com/<?php echo $twitter_link; ?>"><i class="fa fa-twitter"></i></a></li><?php } ?>
								<?php if($google_link){ ?><li><a class="goo" target="_blank" href="<?php echo $google_link; ?>"><i class="fa fa-google-plus"></i></a></li><?php } ?>
								<?php if($pinterest_link){ ?><li><a class="pin" target="_blank" href="https://www.pinterest.com/<?php echo $pinterest_link; ?>"><i class="fa fa-pinterest"></i></a></li><?php } ?>
								<?php if($instagram_link){ ?><li><a class="insta" target="_blank" href="https://www.instagram.com/<?php echo $instagram_link; ?>"><i aria-hidden="true" class="fa fa-instagram"></i></a> </li><?php } ?>
							</ul>
						</div>
					</div>
					<!-- /f-weghit2 -->
				</div>
			</div>
			
			<!-- copyright -->
			<div class="copayright">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							&copy; <?php echo $store_copyright; ?>
						</div>
						<div class="text-right col-xs-12 col-sm-6 col-md-6">
							<ul class="f-link">
								<li><a href="#">blog</a></li>
								<li>|</li>
								<li><a href="#">about us</a></li>
								<li>|</li>
								<li><a href="#">accessories</a></li>
								<li>|</li>
								<li><a href="#">order tracking</a></li>
								<li>|</li>
								<li><a href="#">contact</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- /copyright -->
		</div>
	</div>
</footer>
<!-- /footer -->