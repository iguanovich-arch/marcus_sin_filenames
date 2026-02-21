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
<footer class="footer-6">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				<!-- f-weghit -->
				<div class="f-weghit">
					<img src="<?php echo TEMPLATE_IMG_DIR.'uploads/'.$footer_logo; ?>" alt="logo" />
					<?php echo $store_address;?>
				</div>
				<!-- /f-weghit -->
			</div>
			<div class="col-lg-3">
				<!-- f-weghit2 -->
				<div class="f-weghit2">
					<h4><?php echo FOOTER_TITLE_MY_ACCOUNT; ?></h4>
					<ul>
						<li>
							<a href="<?php echo tep_href_link('account.php')?>">
								<?php echo FOOTER_TITLE_MY_ACCOUNT; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('account_history.php')?>">
								<?php echo FOOTER_TITLE_ORDER_HISTORY; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('address_book.php')?>">
								<?php echo FOOTER_TITLE_ADDRESS_BOOK; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('account_password.php')?>">
								<?php echo FOOTER_TITLE_CHANGE_PASSWORD; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('contact_us.php')?>">
								<?php echo FOOTER_TITLE_CONTACT; ?>
							</a>
						</li>
					</ul>
				</div>
				<!-- /f-weghit2 -->
			</div>
			<div class="col-lg-3">
				<!-- f-weghit2 -->
				<div class="f-weghit2">
					<h4><?php echo FOOTER_TITLE_INFORMATION; ?></h4>
					<ul>
						<li>
							<a href="<?php echo tep_href_link('shipping_info.php')?>">
								<?php echo FOOTER_TITLE_SHIP_INFO; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('terms_condition.php')?>">
								<?php echo FOOTER_TITLE_TERMS; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('privacy.php')?>">
								<?php echo FOOTER_TITLE_PRIVACY; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('refund_policy.php')?>">
								<?php echo FOOTER_TITLE_REFUND_POLICY; ?>
							</a>
						</li>
						<li>
							<a href="<?php echo tep_href_link('delivery_information.php')?>">
								<?php echo FOOTER_TITLE_DELIVERY_INFO; ?>
							</a>
						</li>          
					</ul>
				</div>
				<!-- /f-weghit2 -->
			</div>
			<?php if($display_newsletter==1) {?>
			<div class="col-lg-3">
				<!-- f-weghit -->
				<div class="f-weghit">
					<h4><?php echo POSC_HEADING_NEWSLETTER; ?></h4>
					<p><?php echo POSC_NEWSLETTER_TEXT; ?></p>
					<div class="newletter-outer">
						<?php echo $newsletter_details; ?>
					</div>
				</div>
				<!-- /f-weghit -->
			</div>
			<?php } ?>
		</div>
	</div>
	
	<!-- copyright -->
	<div class="copayright cwhite">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					&copy; <?php echo $store_copyright; ?>
				</div>
				<div class="text-right col-xs-12 col-sm-6 col-md-6">
					<div class="scoial-footer">
						<ul>
							<li><span>Follow us on:</span></li>
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
	</div>
	<!-- /copyright -->
</footer>
<!-- /footer -->