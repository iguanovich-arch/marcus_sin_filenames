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

  require(DIR_WS_LANGUAGES . $language . '/' . 'address_book.php');

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php', '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('address_book.php', '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<div id="addressBookDefault" class="centerColumn">
	<div class="panel panel-default head-wrap mb0">
		<div class="panel-heading">
			<h4 class="panel-title"><?php echo HEADING_TITLE; ?></h4>
		</div>
	</div>
    <?php
  		if ($messageStack->size('addressbook') > 0) {
    		echo $messageStack->output('addressbook');
  		}
	?>
    <div class="content shipping-outer">
    	<div class="row primary-address-instructions">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 primary-address">
				<div class="panel panel-default head-wrap">
					<div class="panel-heading">
						<h4 class="panel-title"><?php echo PRIMARY_ADDRESS_TITLE; ?></h4>
					</div>
				</div>
                <address class="back">
					<?php echo tep_address_label($customer_id, $customer_default_address_id, true, ' ', '<br />'); ?>
            	</address>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 address-instructions">
                <div class="alert alert-info instructions">
                    <?php echo PRIMARY_ADDRESS_DESCRIPTION; ?>
                </div>
            </div>
        </div>
    </div>
        <?php
  			$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as 	
				street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as 
				country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' order by firstname, lastname");
  				while ($addresses = tep_db_fetch_array($addresses_query)) {
    			$format_id = tep_get_address_format_id($addresses['country_id']);
		?>
        <div class="content shipping-outer">
      		<div class="panel panel-default head-wrap">
				<div class="panel-heading">
					<h4 class="panel-title">
						<?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?>
						</strong><?php if ($addresses['address_book_id'] == 
						$customer_default_address_id) echo '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>'; ?>
					</h4>
				</div>
			</div>
      		<address><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br />'); ?></address>
            <span class="cart-buttons ckpc bkbc">
            	<?php echo tep_draw_button(SMALL_IMAGE_BUTTON_EDIT, 'document', tep_href_link('address_book_process.php', 'edit=' . $addresses['address_book_id'], 'SSL')); ?>
            </span>
            <span class="cart-buttons ckpc">
				<?php echo tep_draw_button(SMALL_IMAGE_BUTTON_DELETE, 'trash', tep_href_link('address_book_process.php', 'delete=' . $addresses['address_book_id'], 'SSL')); ?>
    		</span>
    	</div>

		<?php
          }
        ?>
    
    <div class="buttonSet cart-buttons ck-btn">
		<?php
  			if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
		?>
    	<span class="cart-buttons ckpc bkbc">
			<?php echo tep_draw_button(IMAGE_BUTTON_ADD_ADDRESS, 'home', tep_href_link('address_book_process.php', '', 'SSL'), 'primary'); ?>
        </span>
		<?php
  			}
		?>
        <span class="cart-buttons ckpc">
    		<?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php', '', 'SSL')); ?>
  		</span>
    </div>
  		<br /><p><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>
</div>



<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
