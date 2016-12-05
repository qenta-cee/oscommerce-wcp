<?php

/**
Shop System Plugins - Terms of use

This terms of use regulates warranty and liability between Wirecard
Central Eastern Europe (subsequently referred to as WDCEE) and it's
contractual partners (subsequently referred to as customer or customers)
which are related to the use of plugins provided by WDCEE.

The Plugin is provided by WDCEE free of charge for it's customers and
must be used for the purpose of WDCEE's payment platform integration
only. It explicitly is not part of the general contract between WDCEE
and it's customer. The plugin has successfully been tested under
specific circumstances which are defined as the shopsystem's standard
configuration (vendor's delivery state). The Customer is responsible for
testing the plugin's functionality before putting it into production
enviroment.
The customer uses the plugin at own risk. WDCEE does not guarantee it's
full functionality neither does WDCEE assume liability for any
disadvantage related to the use of this plugin. By installing the plugin
into the shopsystem the customer agrees to the terms of use. Please do
not use this plugin if you do not agree to the terms of use!
 */


define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INITIATION_URL', 'https://checkout.wirecard.com/page/init.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_RETURN', 'ext/modules/payment/wirecard/checkout_page_return.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIRM', 'ext/modules/payment/wirecard/checkout_page_confirm.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CHECKOUT', 'ext/modules/payment/wirecard/checkout_page_checkout.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IFRAME', 'ext/modules/payment/wirecard/checkout_page_iframe.php');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PLUGINVERSION', '1.4.1');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE', 'wirecard_checkout_page_transaction');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_WINDOW_NAME', 'wirecardCheckoutPageIFrame');

class wirecard_checkout_page
{
    var $code, $title, $description, $enabled, $transaction_id, $displaytext;

    /**
     * constructor
     */
    function wirecard_checkout_page()
    {
        global $order, $language;

        $this->code = 'wirecard_checkout_page';
        $this->title = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_TITLE;
        $this->description = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_DESCRIPTION;
        $this->displaytext = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_DISPLAYTEXT;
        $this->sort_order = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER;
        $this->enabled = ((MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS == 'True') ? true : false);

        $this->transaction_id = '';

        if ((int)MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID > 0)
        {
            $this->order_status = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID;
        }
        else
        {
            $this->order_status = 1;
        }

        if ((int)MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID > 0)
        {
            $this->order_status_pending = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID;
        }
        else
        {
            $this->order_status_pending = 1;
        }

        if (is_object($order))
        {
            $this->update_status();
        }

        if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME == 'True')
        {
            $this->form_action_url = tep_href_link(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CHECKOUT, '', 'SSL');
        }
        else
        {
            $this->form_action_url = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INITIATION_URL;
        }
    }

    function update_status()
    {

        global $order;

        if (($this->enabled == true) && ((int)MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE > 0))
        {
            $check_flag = false;
            $sql = "select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id";

            $check_query = tep_db_query($sql);
            while ($check = tep_db_fetch_array($check_query))
            {
                if ($check['zone_id'] < 1)
                {
                    $check_flag = true;
                    break;
                }
                elseif ($check['zone_id'] == $order->billing['zone_id'])
                {
                    $check_flag = true;
                    break;
                }
            }

            if ($check_flag == false)
            {
                $this->enabled = false;
            }

            $order->info['order_status'] = $this->order_status;
        }
    }


// class methods
    function javascript_validation()
    {
        return false;
    }

    function selection()
    {
        if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SELECT == 'True')
        {
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TEXT == '')
            {
                return array('id' => $this->code,
                             'module' => $this->title);
            }
            else
            {
                return array('id' => $this->code,
                             'module' => $this->title,
                             'fields' => array(array('title' => '', 'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TEXT)));
            }
        }
        else
        {
            // we have to use a JavaScript helper function to select the main-payment type if a sub type was selected
            $subTypes = array();
            $jsHelper = 'onclick="if (document.checkout_payment.payment.length) { for (var i=0; i<document.checkout_payment.payment.length; i++) { if (document.checkout_payment.payment[i].value == \'' . $this->code . '\') { document.checkout_payment.payment[i].checked = true; break; }}};"';

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'CCARD', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MAESTRO == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'MAESTRO', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MAESTRO_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EPS == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'EPS', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EPS_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_IDEAL == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'IDL', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_IDEAL_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_WGP == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'GIROPAY', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_WGP_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SUE == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'SOFORTUEBERWEISUNG', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SUE_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PBX == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'PBX', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PBX_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PSC == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'PSC', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PSC_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_QUICK == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'QUICK', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_QUICK_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PAYPAL == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'PAYPAL', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PAYPAL_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_ELV == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'ELV', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_ELV_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_C2P == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'C2P', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_C2P_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INVOICE == 'True')
            {
                if ($this->_preInvoiceCheck())
                {
                    $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'INVOICE', false, $jsHelper),
                                        'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INVOICE_TEXT);
                }
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARDMOTO == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'CCARD-MOTO', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARDMOTO_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_BMC == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'BANCONTACT_MISTERCASH', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_BMC_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EKONTO == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'EKONTO', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EKONTO_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTALLMENT == 'True')
            {
                if ($this->_preInstallmentCheck())
                {
                    $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'INSTALLMENT', false, $jsHelper),
                                        'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTALLMENT_TEXT);
                }
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTANTBANK == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'INSTANTBANK', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTANTBANK_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MONETA == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'MONETA', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MONETA_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_P24 == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'PRZELEWY24', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_P24_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_POLI == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'POLI', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_POLI_TEXT);
            }

            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MPASS == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'MPASS', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MPASS_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLDIRECT == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'SKRILLDIRECT', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLDIRECT_TEXT);
            }
            if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLWALLET == 'True')
            {
                $subTypes[] = array('title' => tep_draw_radio_field('wirecard_checkout_page', 'SKRILLWALLET', false, $jsHelper),
                                    'field' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLWALLET_TEXT);
            }

            return array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => $subTypes);
        }
    }

    /**
     * @return bool
     */
    function _preInvoiceCheck()
    {
        global $order, $customer, $currencies;

        $consumerID = tep_session_is_registered('customer_id') ? $_SESSION['customer_id'] : "";

        $currency = $order->info['currency'];
        $total = $order->info['total'];
        $amount = tep_round($total * $currencies->get_value($currency), 2);

        $sql = 'SELECT (COUNT(*) > 0) as cnt FROM ' . TABLE_CUSTOMERS . ' WHERE DATEDIFF(NOW(), customers_dob) > 6574 AND customers_id="' . $consumerID . '"';

        $result = mysql_fetch_assoc(tep_db_query($sql));

        $ageCheck = (bool)$result['cnt'];
        $country_code = $order->billing['country']['iso_code_2'];

        return ($ageCheck &&
            ($amount >= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT && $amount <= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT) &&
            ($currency == 'EUR') &&
            (in_array($country_code, Array('AT', 'DE', 'CH'))) &&
            ($order->delivery === $order->billing));

    }

    /**
     * @return bool
     */
    function _preInstallmentCheck()
    {
        global $order, $customer, $currencies;

        $consumerID = tep_session_is_registered('customer_id') ? $_SESSION['customer_id'] : "";

        $currency = $order->info['currency'];
        $total = $order->info['total'];
        $amount = tep_round($total * $currencies->get_value($currency), 2);

        $sql = 'SELECT (COUNT(*) > 0) as cnt FROM ' . TABLE_CUSTOMERS . ' WHERE DATEDIFF(NOW(), customers_dob) > 6574 AND customers_id="' . $consumerID . '"';
        $result = mysql_fetch_assoc(tep_db_query($sql));

        $ageCheck = (bool)$result['cnt'];
        $country_code = $order->billing['country']['iso_code_2'];

        return ($ageCheck &&
            ($amount >= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT && $amount <= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT) &&
            ($currency == 'EUR') &&
            (in_array($country_code, Array('AT', 'DE', 'CH'))) &&
            ($order->delivery === $order->billing));

    }

    function pre_confirmation_check()
    {
        return false;
    }

    function confirmation()
    {
        return false;
    }

    function process_button()
    {
        global $order, $order_total_modules, $currencies, $currency, $languages_id;

        $qLanguage = $this->getLanguageCode($languages_id);

        $qCurrency = $order->info['currency'];

        $this->transaction_id = $this->generate_trid();

        // construct the orderDescription -> displayed within QENTA Payment Center
        // substitute some special characters
        $orderDescription = $this->transaction_id . ' - ' .
            $order->customer['firstname'] . ' ' .
            $order->customer['lastname'];

        // construct the amount value
        $amount = tep_round($order->info['total'] * $currencies->get_value($qCurrency), 2);

        // construct the returnUrl. we will use one url for all types of return (success, pending, cancel, failure)
        // FILENAME_CHECKOUT_PROCESS
        $returnUrl = tep_href_link(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_RETURN, '', 'SSL');
        $confirmUrl = tep_href_link(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIRM, '', 'SSL');

        // construct the real payment type. if subtype is submittet via post, we have to use them
        $paymentType = (isset($_POST["wirecard_checkout_page"]) && tep_not_null($_POST["wirecard_checkout_page"])) ? $_POST["wirecard_checkout_page"] : "SELECT";

        //add Versions of Plugin and Shop for update notifications.
        $pluginVersion = base64_encode('osCommerce; ' . PROJECT_VERSION . '; ; osCommerce; ' . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PLUGINVERSION);

        //add consumerInformation for address verification.
        if (tep_session_is_registered('customer_id'))
        {
            $consumerID = $_SESSION['customer_id'];
        }
        else
        {
            $consumerID = '';
        }
        $deliveryInformation = $order->delivery;

        if ($deliveryInformation['country']['iso_code_2'] == 'US' || $deliveryInformation['country']['iso_code_2'] == 'CA')
        {
            $deliveryState = $this->_getZoneCodeByName($deliveryInformation['state']);
        }
        else
        {
            $deliveryState = tep_get_zone_code($deliveryInformation['country']['id'], $deliveryInformation['zone_id'], '');
        }

        $billingInformation = $order->billing;
        if ($billingInformation['country']['iso_code_2'] == 'US' || $billingInformation['country']['iso_code_2'] == 'CA')
        {
            $billingState = $this->_getZoneCodeByName($billingInformation['state']);
        }
        else
        {
            $billingState = tep_get_zone_code($billingInformation['country']['id'], $billingInformation['zone_id'], '');
        }


        $sql = 'SELECT customers_dob, customers_fax FROM ' . TABLE_CUSTOMERS . ' WHERE customers_id="' . $consumerID . '" LIMIT 1;';
        $result = tep_db_query($sql);
        $consumerInformation = $result->fetch_assoc();
        if ($consumerInformation['customers_dob'] != '0000-00-00 00:00:00')
        {
            $consumerBirthDateTimestamp = strtotime($consumerInformation['customers_dob']);
            $consumerBirthDate = date('Y-m-d', $consumerBirthDateTimestamp);
        }
        else
        {
            $consumerBirthDate = '';
        }


        $postData = Array('customerId' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID,
                          'shopId' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID,
                          'imageURL' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL,
                          'amount' => $amount,
                          'paymentType' => $paymentType,
                          'currency' => $qCurrency,
                          'language' => $qLanguage,
                          'orderDescription' => $orderDescription,
                          'displayText' => $this->displaytext,
                          'successURL' => $returnUrl,
                          'failureURL' => $returnUrl,
                          'cancelURL' => $returnUrl,
                          'pendingURL' => $returnUrl,
                          'confirmURL' => $confirmUrl,
                          'serviceURL' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL,
                          'trid' => $this->transaction_id,
                          'pluginVersion' => $pluginVersion,
                          'consumerShippingFirstName' => $deliveryInformation['firstname'],
                          'consumerShippingLastName' => $deliveryInformation['lastname'],
                          'consumerShippingAddress1' => $deliveryInformation['street_address'],
                          'consumerShippingAddress2' => $deliveryInformation['suburb'],
                          'consumerShippingCity' => $deliveryInformation['city'],
                          'consumerShippingZipCode' => $deliveryInformation['postcode'],
                          'consumerShippingState' => $deliveryState,
                          'consumerShippingCountry' => $deliveryInformation['country']['iso_code_2'],
                          'consumerShippingPhone' => $order->customer['telephone'],
                          'consumerBillingFirstName' => $billingInformation['firstname'],
                          'consumerBillingLastName' => $billingInformation['lastname'],
                          'consumerBillingAddress1' => $billingInformation['street_address'],
                          'consumerBillingAddress2' => $billingInformation['suburb'],
                          'consumerBillingCity' => $billingInformation['city'],
                          'consumerBillingZipCode' => $billingInformation['postcode'],
                          'consumerBillingState' => $billingState,
                          'consumerBillingCountry' => $billingInformation['country']['iso_code_2'],
                          'consumerBillingPhone' => $order->customer['telephone'],
                          'consumerEmail' => $order->customer['email_address'],
                          'consumerBirthDate' => $consumerBirthDate,
                          'consumerMerchantCrmId' => md5($order->customer['email_address']));

        if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME == 'True')
            $postData['windowName'] = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_WINDOW_NAME;

        $requestFingerprintOrder = 'secret';
        $tempArray = array('secret' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET);
        foreach ($postData AS $parameterName => $parameterValue) {
            $requestFingerprintOrder .= ',' . $parameterName;
            $tempArray[(string)$parameterName] = (string)$parameterValue;
        }
        $requestFingerprintOrder .= ',requestFingerprintOrder';
        $tempArray['requestFingerprintOrder'] = $requestFingerprintOrder;

        $hash = hash_init('sha512', HASH_HMAC, MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET);
        foreach ($tempArray as $key => $value) {
            hash_update($hash, $value);
        }

        $postData['requestFingerprintOrder'] = $requestFingerprintOrder;
        $postData['requestFingerprint'] = hash_final($hash);

        $result = tep_db_query("INSERT INTO " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . " (TRID, PAYSYS, DATE) VALUES ('" . $this->transaction_id . "', '" . $paymentType . "', NOW())");

        $process_button_string = '';
        foreach ($postData AS $parameterName => $parameterValue)
        {
            $process_button_string .= tep_draw_hidden_field($parameterName, $parameterValue);
        }
        if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME == 'True')
        {
            $_SESSION['wirecard_checkout_page'] = array();
            $_SESSION['wirecard_checkout_page']['paypage_title'] = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYMENT_TITLE;
            $_SESSION['wirecard_checkout_page']['paypage_redirecttext'] = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_REDIRECTTEXT;
            $_SESSION['wirecard_checkout_page']['form'] = $process_button_string;
            $_SESSION['wirecard_checkout_page']['iFrame'] = true;
            return '';
        }
        else
        {
            return $process_button_string;
        }

    }

    function before_process()
    {
        global $order;

        if (get_magic_quotes_gpc() || get_magic_quotes_runtime())
        {
            $this->debug_log('magic_quotes enabled. Stripping slashes.');
            foreach ($_POST AS $key => $value)
            {
                $responseArray[$key] = stripslashes($value);
            }
        }
        else
        {
            $responseArray = $_POST;
        }

        if (isset($responseArray['trid']) && trim($responseArray['trid']) != '')
        {
            $this->transaction_id = $responseArray['trid'];
        }
        else
        {
            $redirectUrl = tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=wirecard_checkout_page&message=' . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ERROR_NOTRID, 'SSL', true, false);
            tep_redirect($redirectUrl);
        }

        $orderDesc = isset($responseArray['orderDesc']) ? $responseArray['orderDesc'] : '';
        // orderNumber is only given if paymentState=success
        $orderNumber = isset($responseArray['orderNumber']) ? $responseArray['orderNumber'] : 0;
        $paymentState = isset($responseArray['paymentState']) ? $responseArray['paymentState'] : 'FAILURE';
        $paysys = isset($responseArray['paymentType']) ? $responseArray['paymentType'] : '';
        $brand = isset($responseArray['financialInstitution']) ? $responseArray['financialInstitution'] : '';
        $message = '';
        $everythingOk = false;

        if (strcmp($paymentState, 'CANCEL') == 0)
        {
            $message = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CANCEL_TEXT;
            $this->debug_log(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CANCEL_TEXT);
        }
        else if (strcmp($paymentState, 'FAILURE') == 0)
        {
            // use the error message given from wirecard system
            $message = isset($responseArray['message']) ? $responseArray['message'] : 'No Error given by Wirecard Checkout Page.';
            $this->debug_log('Paymentstate Failure: ' . $message);
        }
        else if (strcmp($paymentState, 'SUCCESS') == 0 || strcmp($paymentState, 'PENDING') == 0)
        {
            $everythingOk = $this->verifyFingerprint($responseArray);
            if ($everythingOk === false)
            {
                $paymentState = 'FAILURE';
                $message = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_FINGERPRINT_TEXT;
            }
        }

        if ($everythingOk)
        {
            if (strcmp($paymentState, 'PENDING') == 0)
            {
                $message = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PENDING_TEXT;
                $order->info['order_status'] = $this->order_status_pending;
                $this->debug_log(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PENDING_TEXT);
            }
            else
            {
                $order->info['order_status'] = $this->order_status;
            }

            $this->debug_log('fingerprints match. orderstatus set to: ' . $order->info['order_status']);
        }

        $gatewayRefNum = empty($responseArray['gatewayReferenceNumber']) ? '' : $responseArray['gatewayReferenceNumber'];

        $aArrayToBeJSONized = $responseArray;
        unset($aArrayToBeJSONized['responseFingerprintOrder']);
        unset($aArrayToBeJSONized['responseFingerprint']);
        unset($aArrayToBeJSONized['trid']);
        unset($aArrayToBeJSONized['x']);
        unset($aArrayToBeJSONized['y']);

        $result = tep_db_query("UPDATE " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . " SET " .
            "ORDERNUMBER=" . $orderNumber . ", " .
            "ORDERDESCRIPTION='" . $orderDesc . "', " .
            "STATE='" . $paymentState . "', " .
            "MESSAGE='" . $message . "', " .
            "GATEWAY_REF_NUM='" . $gatewayRefNum . "', " .
            "RESPONSEDATA='" . json_encode($aArrayToBeJSONized) . "', " .
            (tep_not_null($paysys) ? "PAYSYS='" . $paysys . "', " : "") . // overwrite only if given in response
            "BRAND='" . $brand . "' " .
            "WHERE TRID='" . $this->transaction_id . "'");

        if ($result)
        {
            $this->debug_log('Transaction details set.');
        }

        if (!$everythingOk)
        {
            $redirectUrl = tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=wirecard_checkout_page&message=' . $message, 'SSL', true, false);
            tep_redirect($redirectUrl);
        }
    }


    /**
     * at this point, we have an order
     * dont output any data here, because checkout_process is redirecting
     */
    function after_process()
    {
        global $insert_id;

        if ($insert_id)
        {
            $this->debug_log('Orderstatus update successful');
            // Finally, insert order ID into the transaction table
            $result = tep_db_query("UPDATE " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . " SET " .
            "ORDERID=" . $insert_id . " " .
            "WHERE TRID='" . $this->transaction_id . "'");
            if ($result)
            {
                $this->debug_log('orderID set for transaction.');
            }
            /*
                        $sql_order_status_id = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID'";
                        $check_query = tep_db_query($sql_order_status_id);
                        $payment_status = tep_db_fetch_array($check_query);
                        $qStatus = $payment_status['configuration_value'];

                        if (!empty($qStatus))
                        {
                            $result1 = tep_db_query("UPDATE " . TABLE_ORDERS . " SET " .
                            "orders_status=" . $qStatus . " " .
                            "WHERE orders_id ='" . $insert_id . "'");
                        }
                        $sql_data_array = array('orders_id' => $insert_id,
                                                'orders_status_id' => $qStatus,
                                                'date_added' => 'now()',
                                                'customer_notified' => '0',
                                                'comments' => '');

                        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);*/
        }
    }

    /**
     * Server-to-server request, no session available
     */
    public function processConfirm()
    {
        $confirmReturnMessage = $this->_wirecardCheckoutPageConfirmResponse();
        if (get_magic_quotes_gpc() || get_magic_quotes_runtime())
        {
            $this->debug_log('magic_quotes enabled. Stripping slashes.');
            foreach ($_POST AS $key => $value)
            {
                $responseArray[$key] = stripslashes($value);
            }
        }
        else
        {
            $responseArray = $_POST;
        }

        if (isset($responseArray['trid']) && trim($responseArray['trid']) != '')
        {
            $this->transaction_id = $responseArray['trid'];
        }
        else
        {
            $confirmReturnMessage = $this->_wirecardCheckoutPageConfirmResponse('TransactionID not set or empty.');
            die($confirmReturnMessage);
        }

        // lets check, if you have an order-id in our transaction table
        $sql = 'SELECT ORDERID FROM ' . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . ' WHERE TRID="' . $this->transaction_id . '" LIMIT 1;';
        $result = tep_db_query($sql);
        $row = $result->fetch_assoc();
        if ($row === false || (int)$row['ORDERID'] === 0)
        {
            $this->debug_log("no order id for trid:" . $this->transaction_id);
            // nothing todo
            echo $confirmReturnMessage;
            return;
        }

        $orderId = (int)$row['ORDERID'];

        $orderDesc = isset($responseArray['orderDesc']) ? $responseArray['orderDesc'] : '';
        // orderNumber is only given if paymentState=success
        $orderNumber = isset($responseArray['orderNumber']) ? $responseArray['orderNumber'] : 0;
        $paymentState = isset($responseArray['paymentState']) ? $responseArray['paymentState'] : 'FAILURE';
        $paysys = isset($responseArray['paymentType']) ? $responseArray['paymentType'] : '';
        $brand = isset($responseArray['financialInstitution']) ? $responseArray['financialInstitution'] : '';
        $message = '';
        $everythingOk = false;

        if (strcmp($paymentState, 'CANCEL') == 0)
        {
            // use the default cancel message from the translations
            $message = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CANCEL_TEXT;
            $this->debug_log(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CANCEL_TEXT);
        }
        else if (strcmp($paymentState, 'FAILURE') == 0)
        {
            // use the error message given from wirecard system
            $message = isset($responseArray['message']) ? $responseArray['message'] : 'No Error given by Wirecard Checkout Page.';
            $this->debug_log('Paymentstate Failure: ' . $responseArray['message']);
        }
        else if (strcmp($paymentState, 'SUCCESS') == 0 || strcmp($paymentState, 'PENDING') == 0)
        {
            $everythingOk = $this->verifyFingerprint($responseArray, $confirmReturnMessage);
            if ($everythingOk === false)
            {
                $paymentState = 'FAILURE';
                $message = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_FINGERPRINT_TEXT;
            }
        }

        if ($everythingOk)
        {
            if (strcmp($paymentState, 'PENDING') == 0)
            {
                $message = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PENDING_TEXT;
                $order_status = $this->order_status_pending;
                $this->debug_log(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PENDING_TEXT);
            }
            else
            {
                $order_status = $this->order_status;
            }

            tep_db_query("update " . TABLE_ORDERS . " set orders_status = $order_status where orders_id = $orderId");

            $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
            $sql_data_array = array('orders_id' => $orderId, 'orders_status_id' => $order_status, 'date_added' => 'now()', 'customer_notified' => $customer_notification);
            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

            $this->debug_log('fingerprints match. orderstatus set to: ' . $order_status);
        }

        $gatewayRefNum = empty($responseArray['gatewayReferenceNumber']) ? '' : $responseArray['gatewayReferenceNumber'];

        $aArrayToBeJSONized = $responseArray;
        unset($aArrayToBeJSONized['responseFingerprintOrder']);
        unset($aArrayToBeJSONized['responseFingerprint']);
        unset($aArrayToBeJSONized['trid']);
        unset($aArrayToBeJSONized['x']);
        unset($aArrayToBeJSONized['y']);

        $result = tep_db_query("UPDATE " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . " SET " .
            "ORDERNUMBER=" . $orderNumber . ", " .
            "ORDERDESCRIPTION='" . $orderDesc . "', " .
            "STATE='" . $paymentState . "', " .
            "MESSAGE='" . $message . "', " .
            "GATEWAY_REF_NUM='" . $gatewayRefNum . "', " .
            "RESPONSEDATA='" . json_encode($aArrayToBeJSONized) . "', " .
            (tep_not_null($paysys) ? "PAYSYS='" . $paysys . "', " : "") . // overwrite only if given in response
            "BRAND='" . $brand . "' " .
            "WHERE TRID='" . $this->transaction_id . "'");

        if ($result)
        {
            $this->debug_log('Transaction details set.');
        }
        else
        {
            $confirmReturnMessage = $this->_wirecardCheckoutPageConfirmResponse('Transactiontable update failed.');
        }

        $this->debug_log($confirmReturnMessage);
        echo $confirmReturnMessage;
    }

    function verifyFingerprint($responseArray, &$confirmReturnMessage = '')
    {
        $tempArray = [];
        $responseFingerprintOrder = $responseArray['responseFingerprintOrder'];
        $responseFingerprint = $responseArray['responseFingerprint'];

        $mandatoryFingerprintFields = 0;
        $secretUsed = 0;

        $fieldsNeeded = 2;
        if (array_key_exists('orderNumber', $responseArray))
            $fieldsNeeded = 3;

        $keyOrder = explode(',', $responseFingerprintOrder);
        $this->debug_log('Generating responseFingerprintSeed');
        foreach ($keyOrder AS $key)
        {
            // check if there are enough fields in the responsefingerprint
            if ((strcmp($key, 'paymentState') == 0 && tep_not_null($responseArray[$key])) ||
                (strcmp($key, 'orderNumber') == 0 && tep_not_null($responseArray[$key])) ||
                (strcmp($key, 'paymentType') == 0 && tep_not_null($responseArray[$key]))
            )
            {
                $mandatoryFingerprintFields++;
            }

            if (strcmp($key, 'secret') == 0)
            {
                $tempArray[(string)$key] = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET;
                $secretUsed = 1;
            }
            else
            {
                $tempArray[(string)$key] = $responseArray[$key];
            }
        }

        $hash = hash_init('sha512', HASH_HMAC, MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET);

        foreach ($tempArray as $key => $value) {
            hash_update($hash, $value);
        }

        $responseFingerprintSeed = hash_final($hash);

        $this->debug_log('Calculated Fingerprint: ' . $responseFingerprintSeed . '. Compare with returned Fingerprint.');

        if (!$secretUsed)
        {
            $confirmReturnMessage = $this->_wirecardCheckoutPageConfirmResponse('Secret not used.');
            return false;
        }
        else if ($mandatoryFingerprintFields != $fieldsNeeded)
        {
            $confirmReturnMessage = $this->_wirecardCheckoutPageConfirmResponse('Mandatory fields not used.');
            return false;
        }
        else
        {
            if ((strcmp($responseFingerprintSeed, $responseFingerprint) != 0))
            {
                $confirmReturnMessage = $this->_wirecardCheckoutPageConfirmResponse('Fingerprint validation failed.');
                return false;
            }
        }

        return true;
    }


    function check()
    {
        if (!isset($this->_check))
        {
            $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        }
        return $this->_check;
    }

    function get_error()
    {

        // after redirecting the customer to the checkout_payment page with a payment_error, the qpay
        // class is loaded without saved data. for this reason we have to give the message from the
        // before_process function via GET-parameters and can use them here
        $message = isset($_GET["message"]) ? $_GET["message"] : MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ERROR_TEXT;

        $error = array('title' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ERROR_TITEL,
                       'error' => $message);

        return $error;
    }

    function install()
    {

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Wirecard Checkout Page Module', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS', 'True', 'Do you want to accept Wirecard Checkout Page payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CustomerId', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID', '', 'Enter the customer id you received from Wirecard CEE.', '6', '1', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ShopId', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID', '', 'Enter the shop id you received from Wirecard CEE.', '6', '2', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET', '', 'Enter the secret string (preshared key) you received from Wirecard CEE for the fingerprint-hash', '6', '3', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('IFrame', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME', 'False', 'Open Wirecard Checkout Page inside an IFrame.', '6', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Paysys-Text', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TEXT', '', 'Enter the text which should be displayed as description for the payment type SELECT (e.g. MasterCard, Visa, ...)', '6', '6', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type SELECT', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SELECT', 'True',  'The customer can select the payment type whithin Wirecard Checkout Page. If activated, no other payment type is displayed within the shop', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Credit Card', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD', 'False', 'Credit Card', '6', '202', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Maestro SecureCode', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MAESTRO', 'False', 'Maestro SecureCode', '6', '204', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type eps Online Bank Transfer', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EPS', 'False', 'eps Online Bank Transfer', '6', '206', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type iDEAL', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_IDEAL', 'False', 'iDEAL', '6', '208', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type giropay', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_WGP', 'False', 'giropay', '6', '210', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type SOFORT Banking (PIN/TAN)', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SUE', 'False', 'SOFORT Banking (PIN/TAN)', '6', '212', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Mobile Phone Invoicing', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PBX', 'False', 'Mobile Phone Invoicing', '6', '214', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type paysafecard', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PSC', 'False', 'paysafecard', '6', '216', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type @Quick', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_QUICK', 'False', '@Quick', '6', '218', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type PayPal', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PAYPAL', 'False', 'PayPal', '6', '220', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Direct Debit', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_ELV', 'False', 'Direct Debit', '6', '222', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type CLICK2PAY', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_C2P', 'False', 'CLICK2PAY', '6', '224', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Invoice', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INVOICE', 'False', 'Invoice', '6', '228', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Credit Card MoTo', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARDMOTO', 'False', 'Enable payment type Credit Card without \"Verified by Visa\" and \"MasterCard SecureCode\"', '6', '230', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Bancontact/Mister Cash', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_BMC', 'False', 'Bancontact/Mister Cash', '6', '232', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type eKonto', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EKONTO', 'False', 'eKonto', '6', '234', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Installment', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTALLMENT', 'False', 'Installment', '6', '236', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type InstantBank', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTANTBANK', 'False', 'InstantBank', '6', '238', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type moneta.ru', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MONETA', 'False', 'moneta.ru', '6', '240', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Przeleway24', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_P24', 'False', 'Przeleway24', '6', '242', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type POLi', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_POLI', 'False', 'POLi', '6', '244', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Skrill Direct', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLDIRECT', 'False', 'Skrill Direct', '6', '244', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type Skrill Digital Wallet', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLWALLET', 'False', 'Skrill Digital Wallet', '6', '244', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable payment type mpass', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MPASS', 'False', 'mpass', '6', '244', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ServiceUrl', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL', '', 'Enter the URL to your contact page.', '6', '300', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ImageUrl', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL', '', 'Enter the Url of the image which should be displayed during the payment process on the Wirecard Checkout Page.', '6', '301', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Invoice min. amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT', '100', 'Enter minimum amount for invoice. (&euro;)', '6', '310', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Invoice max. amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT', '10000', 'Enter maximum amount for invoice. (&euro;)', '6', '311', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installment min. amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT', '100', 'Enter maximum amount for installment. (&euro;)','6', '320', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installmen max. amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT', '10000', 'Enter maximum amount for installment. (&euro;)','6', '321', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first', '6', '400', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone', '6', '410', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value.', '6', '420', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status Pending', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID', '0', 'Set the status of orders made with this payment module, which are in paymentstate pending.', '6', '430', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

        tep_db_query("CREATE TABLE IF NOT EXISTS `" . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . "` (
                        `TRID` varchar(255) NOT NULL default '',
                        `DATE` datetime NOT NULL default '0000-00-00 00:00:00',
                        `PAYSYS` varchar(50) NOT NULL default '',
                        `BRAND` varchar(100) NOT NULL default '',
                        `ORDERNUMBER` int(11) unsigned NOT NULL default '0',
                        `ORDERDESCRIPTION` varchar(255) NOT NULL default '',
                        `STATE` varchar(20) NOT NULL default '',
                        `MESSAGE` varchar(255) NOT NULL default '',
                        `ORDERID` int(11) unsigned NOT NULL default '0',
                        `GATEWAY_REF_NUM` varchar(255) NULL default '',
                        PRIMARY KEY  (`TRID`)
                        )");
	tep_db_query("ALTER TABLE `" . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE. "` ADD `RESPONSEDATA` TEXT NULL DEFAULT NULL");

    }

    function remove()
    {
        $removeTXTable = isset($_GET['removeTXTable']) ? $_GET['removeTXTable'] : 'false';
        if ($removeTXTable == 'true')
        {
            tep_db_query("DROP TABLE " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE);
        }
        else
        {
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
            ?>
            <html>
            <head>
                <script language="JavaScript" type="text/JavaScript">
                    if (confirm("Do you want to remove the Wirecard Checkout Page transactions-table from your system?") == true) {
                        window.location.href = "<?php echo tep_href_link(FILENAME_MODULES, 'set=' . $_GET['set'] . '&module=wirecard_checkout_page&action=remove&removeTXTable=true'); ?>";
                    }
                    else {
                        window.location.href = "<?php echo tep_href_link(FILENAME_MODULES, 'set=' . $_GET['set'] . '&module=wirecard_checkout_page'); ?>";
                    }
                </script>
            </head>
            <body>

            </body>
            </html>
            <?php
            die();
        }
    }

    function keys()
    {
        return array('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SELECT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TEXT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MAESTRO',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EPS',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PBX',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PSC',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_QUICK',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_ELV',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PAYPAL',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_IDEAL',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SUE',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_C2P',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_WGP',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INVOICE',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARDMOTO',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_BMC',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EKONTO',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTALLMENT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTANTBANK',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MONETA',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_P24',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_POLI',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLDIRECT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLWALLET',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MPASS',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID',
                     'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE',
        );
    }

    // Parse the predefinied array to be 'module install' friendly
    // as it is used for select in the module's install() function
    function show_array($aArray)
    {
        $aFormatted = "array(";
        foreach ($aArray as $key => $sVal)
        {
            $aFormatted .= "\'$sVal\', ";
        }
        $aFormatted = substr($aFormatted, 0, strlen($aFormatted) - 2);
        return $aFormatted;
    }

    function generate_trid()
    {
        do
        {
            $trid = tep_create_random_value(16);
            $result = tep_db_query("SELECT TRID FROM " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . " WHERE TRID = '" . $trid . "'");
        } while ($result->num_rows);

        return $trid;

    }

    function getLanguageCode($languagesId)
    {
        $languagesQuery = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES . " WHERE languages_id = '" . $languagesId . "'");
        $languagesCode = '';
        while ($languages = tep_db_fetch_array($languagesQuery))
        {
            if ($languages['languages_id'] == $languagesId)
            {
                $languagesCode = $languages['code'];
                continue;
            }
        }
        return $languagesCode;
    }


    function _getZoneCodeByName($zoneName)
    {
        $sql = 'SELECT zone_code FROM ' . TABLE_ZONES . ' WHERE zone_name=\'' . $zoneName . '\' LIMIT 1;';
        $result = tep_db_query($sql);
        $resultRow = mysql_fetch_row($result);
        return $resultRow[0];
    }

    function _wirecardCheckoutPageConfirmResponse($message = null)
    {
        if ($message != null)
        {
            $this->debug_log($message);
            $value = 'result="NOK" message="' . $message . '" ';
        }
        else
        {
            $value = 'result="OK"';
        }
        return '<!--<QPAY-CONFIRMATION-RESPONSE ' . $value . ' />-->';
    }

    /**
     * confirmation debug-log.
     * Use this for debug useage only!
     *
     * @param $message
     */
    function debug_log($message)
    {
        file_put_contents('wirecard_checkout_page_notify_debug.txt', date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
    }

}