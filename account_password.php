<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link('login.php', '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . 'account_password.php');

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $password_current = tep_db_prepare_input($HTTP_POST_VARS['password_current']);
    $password_new = tep_db_prepare_input($HTTP_POST_VARS['password_new']);
    $password_confirmation = tep_db_prepare_input($HTTP_POST_VARS['password_confirmation']);

    $error = false;

    if (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR);
    } elseif ($password_new != $password_confirmation) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $check_customer_query = tep_db_query("select customers_password from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $check_customer = tep_db_fetch_array($check_customer_query);

      if (tep_validate_password($password_current, $check_customer['customers_password'])) {
        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_encrypt_password($password_new) . "' where customers_id = '" . (int)$customer_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

        $messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

        tep_redirect(tep_href_link('account.php', '', 'SSL'));
      } else {
        $error = true;

        $messageStack->add('account_password', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php', '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('account_password.php', '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
  require('includes/form_check.js.php');
?>

<div id="accountPassword" class="centerColumn">
	<div class="panel panel-default head-wrap">
		<div class="panel-heading">
			<h4 class="panel-title"><?php echo HEADING_TITLE; ?></h4>
		</div>
	</div>
    <?php
  		if ($messageStack->size('account_password') > 0) {
    		echo $messageStack->output('account_password');
  		}
	?>
	<?php echo tep_draw_form('account_password', tep_href_link('account_password.php', '', 'SSL'), 'post', 'onsubmit="return check_form(account_password);"', true) . 
		tep_draw_hidden_field('action', 'process'); ?>
	<div class="content shipping-outer nopad">
    	<span class="alert-text forward"><?php echo FORM_REQUIRED_INFORMATION; ?></span>
        <div class="contentText">
			<div class="lable">
				<?php echo ENTRY_PASSWORD_CURRENT; ?>
                <?php echo (tep_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="alert-text">' . ENTRY_PASSWORD_CURRENT_TEXT . '</span>': ''); ?>
           	</div>
            <?php echo tep_draw_password_field('password_current'); ?>
            
            <div class="lable">
				<?php echo ENTRY_PASSWORD_NEW; ?>
                <?php echo (tep_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="alert-text">' . ENTRY_PASSWORD_NEW_TEXT . '</span>': ''); ?>
            </div>
            <?php echo tep_draw_password_field('password_new'); ?>
            
            <div class="lable">
				<?php echo ENTRY_PASSWORD_CONFIRMATION; ?>
                <?php echo (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="alert-text">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?>
           	</div>
            <?php echo tep_draw_password_field('password_confirmation'); ?>
        </div>
    </div>
	<div class="cart-buttons ck-btn">
        <span class="cart-buttons bkbc">
			<?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'primary'); ?>
        </span>
        <span class="cart-buttons">
			<?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php', '', 
			'SSL')); ?>
        </span>
	</div>
</div>

</form>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
