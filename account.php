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

  require(DIR_WS_LANGUAGES . $language . '/' . 'account.php');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('account.php', '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div id="accountDefault" class="centerColumn">
    <div class="my_accountpage">
		<div class="panel panel-default head-wrap mb0">
			<div class="panel-heading">
				<h4 class="panel-title"><?php echo HEADING_TITLE; ?></h4>
			</div>
		</div>
        <?php
            if ($messageStack->size('account') > 0) {
                echo $messageStack->output('account');
            }
        ?>
        <div class="content shipping-outer">
			<div class="panel panel-default head-wrap">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo MY_ACCOUNT_TITLE; ?></h4>
				</div>
			</div>
            <div class="accounts_link">
                <div class="accountLinkList">
                    <div>
						<?php echo '<a href="' . tep_href_link('account_edit.php', '', 'SSL') . '">
						<i class="fa fa-arrow-circle-right"></i>'. MY_ACCOUNT_INFORMATION . '</a>'; ?>
					</div>
                    <div>
						<?php echo '<a href="' . tep_href_link('address_book.php', '', 'SSL') . '">
						<i class="fa fa-arrow-circle-right"></i>'. MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?>
					</div>
                    <div>
						<?php echo '<a href="' . tep_href_link('account_password.php', '', 'SSL') . '">
						<i class="fa fa-arrow-circle-right"></i>'. MY_ACCOUNT_PASSWORD . '</a>'; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content shipping-outer">
			<div class="panel panel-default head-wrap">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo MY_ORDERS_TITLE; ?></h4>
				</div>
			</div>
            <div class="accounts_link">
                <div class="accountLinkList">
                    <div>
						<?php echo '<a href="' . tep_href_link('account_history.php', '', 'SSL') . '">
						<i class="fa fa-arrow-circle-right"></i>'. MY_ORDERS_VIEW . '</a>'; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content shipping-outer">
			<div class="panel panel-default head-wrap">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h4>
				</div>
			</div>
            <div class="accounts_link">
                <div class="accountLinkList">
                    <div>
						<?php echo '<a href="' . tep_href_link('account_newsletters.php', '', 'SSL'). '">
						 <i class="fa fa-arrow-circle-right"></i>' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?>
                    </div>
                    <div>
						<?php echo '<a href="' . tep_href_link('account_notifications.php', '', 'SSL') . '">
						<i class="fa fa-arrow-circle-right"></i>'. EMAIL_NOTIFICATIONS_PRODUCTS . '</a>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
