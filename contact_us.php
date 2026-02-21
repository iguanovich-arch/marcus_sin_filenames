<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . 'contact_us.php');

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $error = false;

    $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
    $enquiry = tep_db_prepare_input($HTTP_POST_VARS['enquiry']);

    if (!tep_validate_email($email_address)) {
      $error = true;

      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $actionRecorder = new actionRecorder('ar_contact_us', (tep_session_is_registered('customer_id') ? $customer_id : null), $name);
    if (!$actionRecorder->canPerform()) {
      $error = true;

      $actionRecorder->record(false);

      $messageStack->add('contact', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES') ? (int)MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES : 15)));
    }

    if ($error == false) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);

      $actionRecorder->record();

      tep_redirect(tep_href_link('contact_us.php', 'action=success'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('contact_us.php'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div id="contactus-wrapper">
	<?php if($store_map != "") { ?>
	<div class="contact-details">
		<div class="contact-info">
			<div class="row">
				<div class="map-container col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<?php echo $store_map; ?>
				</div>
			</div>
		</div>
	</div>
    <?php } ?>
<?php
  if ($messageStack->size('contact') > 0) {
    echo $messageStack->output('contact');
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) {
?>

<div class="contentContainer">
  <div class="contentText">
    <?php echo TEXT_SUCCESS; ?>
  </div>

  <div style="float: right;">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?>
  </div>
</div>

<?php
  } else {
?>

<?php echo tep_draw_form('contact_us', tep_href_link('contact_us.php', 'action=send'), 'post', '', true); ?>

<div class="row">
	<div class="store-contact-form col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="shipping-outer nopad">
			<div class="row sender-name-email">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 sender-name">
					<div class="lable"><?php echo ENTRY_NAME; ?></div>
					<?php echo tep_draw_input_field('name'); ?>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 sender-email" for="email-address">
					<div class="lable"><?php echo ENTRY_EMAIL; ?></div>
					<?php echo tep_draw_input_field('email'); ?>
				</div>
			</div>
			<div class="row message-detail">
				<div class="col-lg-12 contactus-message" for="enquiry">
					<div class="lable"><?php echo ENTRY_ENQUIRY; ?></div>
					<?php echo tep_draw_textarea_field('enquiry', 'soft', 50, 4); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row contactus-sendbutton">
	<div class="col-lg-12">
		<a class="cart-buttons ck-btn"><?php echo tep_draw_button(IMAGE_BUTTON_SEND_NOW, 'triangle-1-e', null, 'primary'); ?></a>
	</div>
</div>
	
</div>
</form>

<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
