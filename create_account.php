<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . 'create_account.php');

  $process = false;
  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') {
      if (isset($HTTP_POST_VARS['gender'])) {
        $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
      if (isset($HTTP_POST_VARS['zone_id'])) {
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
    if (isset($HTTP_POST_VARS['newsletter'])) {
      $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if ((strlen($dob) < ENTRY_DOB_MIN_LENGTH) || (!empty($dob) && (!is_numeric(tep_date_raw($dob)) || !@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))))) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
      $check_email = tep_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      }
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => tep_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }

      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');

// reset session token
      $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());

// restore cart contents
      $cart->restore_contents();

// build the message content
      $name = $firstname . ' ' . $lastname;

      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
         } else {
           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
         }
      } else {
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
      }

      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      tep_redirect(tep_href_link('create_account_success.php', '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('create_account.php', '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
  require('includes/form_check.js.php');
?>

<div class="createaccount-wrapper">
	<div class="panel panel-default head-wrap"><div class="panel-heading"><h4 class="panel-title"><?php echo HEADING_TITLE; ?></h4></div></div>
	<p class="alert-text forward"><?php echo FORM_REQUIRED_INFORMATION; ?></p>
	<?php if ($messageStack->size('create_account') > 0) {
        echo $messageStack->output('create_account');
      } ?>
	<?php echo tep_draw_form('create_account', tep_href_link('create_account.php', '', 'SSL'), 'post', 'onsubmit="return check_form(create_account);"', true) . tep_draw_hidden_field('action', 'process'); ?>
	<div class="row create-account-page">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="card card--padding">
				<h4><?php echo TABLE_HEADING_ADDRESS_DETAILS; ?></h4>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 gender">
						<?php
						  if (ACCOUNT_GENDER == 'true') {
						?>
						<?php echo tep_draw_radio_field('gender', 'm', '', 'id="gender-male"') . '<label class="radioButtonLabel" for="gender-male">' . MALE . '</label>' . tep_draw_radio_field('gender', 'f', '', 'id="gender-female"') . '<label class="radioButtonLabel" for="gender-female">' . FEMALE . '</label>' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="alert-text">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
						<br class="clearBoth" />
						<?php
						  }
						?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 first-name">
						<label class="inputLabel" for="firstname">
							<?php echo ENTRY_FIRST_NAME; ?>
							<?php echo tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="alert-text">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_input_field('firstname', '', 'id="firstname" placeholder="' . ENTRY_FIRST_NAME_TEXT . '"' . ((int)ENTRY_FIRST_NAME_MIN_LENGTH > 0 ? ' required' : '')); ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 last-name">    
						<label class="inputLabel" for="lastname">
							<?php echo ENTRY_LAST_NAME; ?>
							<?php echo tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="alert-text">' . ENTRY_LAST_NAME_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_input_field('lastname', '',' id="lastname" placeholder="' . ENTRY_LAST_NAME_TEXT . '"'. ((int)ENTRY_LAST_NAME_MIN_LENGTH > 0 ? ' required' : '')); ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 emailaddress">
						<label class="inputLabel" for="email-address">
							<?php echo ENTRY_EMAIL_ADDRESS; ?>
							<?php echo tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="alert-text">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_input_field('email_address', '',' id="email-address" placeholder="' . ENTRY_EMAIL_ADDRESS_TEXT . '"' . ((int)ENTRY_EMAIL_ADDRESS_MIN_LENGTH > 0 ? ' required' : ''), 'email'); ?>
					</div>
					<?php echo tep_draw_input_field('should_be_empty', '', ' size="40" id="CAAS" style="visibility:hidden; display:none;" autocomplete="off"'); ?>
					
					<?php if (ACCOUNT_DOB == 'true') { ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dob">
						<label class="inputLabel" for="dob">
							<?php echo ENTRY_DATE_OF_BIRTH; ?> 
							<?php echo tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="alert-text">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_input_field('dob','', 'id="dob" placeholder="' . ENTRY_DATE_OF_BIRTH_TEXT . '"' . (ACCOUNT_DOB == 'true' && (int)ENTRY_DOB_MIN_LENGTH != 0 ? ' required' : '')); ?>
						<script type="text/javascript">$('#dob').datepicker({dateFormat: '<?php echo JQUERY_DATEPICKER_FORMAT; ?>', changeMonth: true, changeYear: true, yearRange: '-100:+0'});</script>
					</div>
					<?php } ?>
					<div class="street-add1 col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="inputLabel" for="street-address">
							<?php echo ENTRY_STREET_ADDRESS; ?>
							<?php echo tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="alert-text">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_input_field('street_address', '',' id="street-address" placeholder="' . ENTRY_STREET_ADDRESS_TEXT . '"'. ((int)ENTRY_STREET_ADDRESS_MIN_LENGTH > 0 ? ' required' : '')); ?>
					</div>
					<?php  if (ACCOUNT_SUBURB == 'true') {	?>
					 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 suburb">
						<label class="inputLabel" for="suburb"><?php echo ENTRY_SUBURB; ?></label>
						<?php echo tep_draw_input_field('suburb', '', ' id="suburb" placeholder="' . ENTRY_SUBURB_TEXT . '"'); ?>
					</div>
					<?php  }?>
					
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 city">
						<label class="inputLabel" for="city">
							<?php echo ENTRY_CITY; ?>
							<?php echo tep_not_null(ENTRY_CITY_TEXT) ? '<span class="alert-text">' . ENTRY_CITY_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_input_field('city', '',' id="city" placeholder="' . ENTRY_CITY_TEXT . '"'. ((int)ENTRY_CITY_MIN_LENGTH > 0 ? ' required' : '')); ?>
					</div>
					<?php if (ACCOUNT_STATE == 'true') { ?>
                   	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 statezone">
						<div class="lable">
							<?php echo ENTRY_STATE; ?>
							<?php echo tep_not_null(ENTRY_STATE_TEXT) ? '<span class="alert-text">' . ENTRY_STATE_TEXT . '</span>': ''; ?>                     
						</div>					
						<?php
                            if ($process == true) {
                              if ($entry_state_has_zones == true) {
                                $zones_array = array();
                                $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
                                while ($zones_values = tep_db_fetch_array($zones_query)) {
                                  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
                                }
                                echo tep_draw_pull_down_menu('state', $zones_array);
                              } else {
                                echo tep_draw_input_field('state');
                              }
                            } else {
                              echo tep_draw_input_field('state');
                            }
                        ?>
                    </div>    
            		<?php } ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zip-code">
						<label class="inputLabel" for="postcode">
							<?php echo ENTRY_POST_CODE; ?>
							<?php echo tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="alert-text">' . ENTRY_POST_CODE_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_input_field('postcode', '', ' id="postcode" placeholder="' . ENTRY_POST_CODE_TEXT . '"' . ((int)ENTRY_POSTCODE_MIN_LENGTH > 0 ? ' required' : '')); ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 country">
						<label class="inputLabel" for="country">
							<?php echo ENTRY_COUNTRY; ?>
							<?php echo tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="alert-text">' . ENTRY_COUNTRY_TEXT . '</span>': ''; ?>
						</label>
						<div class="select-wrapper">
						<?php echo tep_get_country_list('country','', 'id="country" ' . ($flag_show_pulldown_states == true ? 'onchange="update_zone(this.form);"' : '')) . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="alert">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if (ACCOUNT_COMPANY == 'true') {  ?>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 company-details">
			<div class="card card--padding">
				<h4><?php echo CATEGORY_COMPANY; ?></h4>
				<div class="company-details">
					<label class="inputLabel" for="company"><?php echo ENTRY_COMPANY; ?></label>
					<?php echo tep_draw_input_field('company', '', ' id="company" placeholder="' . ENTRY_COMPANY_TEXT . '"'); ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 phone-details">
			<div class="card card--padding">
				<h4><?php echo TABLE_HEADING_PHONE_FAX_DETAILS; ?></h4>
				<div class="row telephone-fax">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 telephone">
						<label class="inputLabel" for="telephone">
						<?php echo ENTRY_TELEPHONE_NUMBER; ?> 
						<?php echo tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="alert-text">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''; ?></label>
					   <?php echo tep_draw_input_field('telephone', '', ' id="telephone" placeholder="' . ENTRY_TELEPHONE_NUMBER_TEXT . '"' . ((int)ENTRY_TELEPHONE_MIN_LENGTH > 0 ? ' required' : ''), 'tel'); ?>
					</div>
					<?php
						if (ACCOUNT_FAX_NUMBER == 'true') {
					?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 fax-number">
						<label class="inputLabel" for="fax">
						<?php echo ENTRY_FAX_NUMBER; ?>
						<?php echo tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="alert-text">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''; ?></label>
						<?php echo tep_draw_input_field('fax', '', 'id="fax" placeholder="' . ENTRY_FAX_NUMBER_TEXT . '"', 'tel'); ?>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 login-details">
			<div class="card card--padding">
				<h4><?php echo TABLE_HEADING_LOGIN_DETAILS; ?></h4>
				<div class="row password-details">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 password-entry">
						<label class="inputLabel" for="password-new">
							<?php echo ENTRY_PASSWORD; ?>
							<?php echo tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="alert-text">' . ENTRY_PASSWORD_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_password_field('password', '', ' id="password-new" autocomplete="off" placeholder="' . ENTRY_PASSWORD_TEXT . '"'. ((int)ENTRY_PASSWORD_MIN_LENGTH > 0 ? ' required' : '')); ?>
				   </div>
				   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 confirm-password">
						<label class="inputLabel" for="password-confirm">
							<?php echo ENTRY_PASSWORD_CONFIRMATION; ?>
							<?php echo tep_not_null(ENTRY_PASSOWRD_CONFIRMATION_TEXT) ? '<span class="alert-text">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''; ?>
						</label>
						<?php echo tep_draw_password_field('confirmation', '', ' id="password-confirm" autocomplete="off" placeholder="' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '"'. ((int)ENTRY_PASSWORD_MIN_LENGTH > 0 ? ' required' : '')); ?>
				   </div>
				</div>
			</div>
		</div>
		<?php
		  if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
		?>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 privacy-condition-info">
			<div class="card card--padding">
				<h4><?php echo TABLE_HEADING_PRIVACY_CONDITIONS; ?></h4>
				<div class="information"><?php echo TEXT_PRIVACY_CONDITIONS_DESCRIPTION;?></div>
				<?php echo tep_draw_checkbox_field('privacy_conditions', '1', false, 'id="privacy" required');?>
				<label class="checkboxLabel" for="privacy"><?php echo TEXT_PRIVACY_CONDITIONS_CONFIRM;?></label>
			</div>
		</div>
		<?php
			}
		?>
		<?php if ($siteKey != NULL || $secret != NULL) { ?>
		<!-- bo Google reCAPTCHA  -->
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 recaptcha-details">
			<div class="card card--padding">
				<label><?php echo GOOGLE_RECAPTCHA . '<span class="alertrequired">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?></label>
				<div class="g-recaptcha" data-sitekey="<?php echo $siteKey;?>"></div>
			</div>
		</div>
		<!-- eo Google reCAPTCHA  -->
		<?php } ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
			<div>
				<div class="customers_referral row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 customers_referral">
						<?php
							  if (CUSTOMERS_REFERRAL_STATUS == 2) {
							?>
							<fieldset>

							<legend><?php echo TABLE_HEADING_REFERRAL_DETAILS; ?></legend>
							<label class="inputLabel" for="customers_referral"><?php echo ENTRY_CUSTOMERS_REFERRAL; ?></label>
							<?php echo tep_draw_input_field('customers_referral', '', ' id="customers_referral"'); ?>
							<br class="clearBoth" />
							</fieldset>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 newsletter-details-signup">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<?php echo tep_draw_checkbox_field('newsletter', '1'); ?>&nbsp;<label><?php echo SUBSCRIBE_NEWSLETTER_TEXT; ?>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 submit-info">
					<div class="buttonSet">
						<span class="cart-buttons pull-right">
							<?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'person', null, 'primary'); ?>
						</span>
					</div>
				</div>  
			</div>
		</div>
	</div>
</div>
</form>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
