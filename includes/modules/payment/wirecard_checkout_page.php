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
if (!class_exists('wirecard_checkout_page_payments')) {
	require_once( DIR_FS_CATALOG . 'ext/modules/payment/wirecard/checkout_page_payment_helper.php' );
}
if (!class_exists('wirecard_checkout_page_configuration')) {
	require_once( DIR_FS_CATALOG . 'ext/modules/payment/wirecard/checkout_page_configuration_helper.php' );
}

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INITIATION_URL', 'https://checkout.wirecard.com/page/init.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_RETURN', 'ext/modules/payment/wirecard/checkout_page_return.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIRM', 'ext/modules/payment/wirecard/checkout_page_confirm.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CHECKOUT', 'ext/modules/payment/wirecard/checkout_page_checkout.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IFRAME', 'ext/modules/payment/wirecard/checkout_page_iframe.php');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SUCCESS', 'ext/modules/payment/wirecard/checkout_page_success.php');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PLUGINVERSION', '1.6.1');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE', 'wirecard_checkout_page_transaction');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_WINDOW_NAME', 'wirecardCheckoutPageIFrame');

class wirecard_checkout_page
{
	var $code, $title, $description, $enabled, $transaction_id;

	protected $_payments;
	protected $_config;

	/**
	 * constructor
	 */
	function wirecard_checkout_page()
	{
		global $order, $language;

		$this->code = 'wirecard_checkout_page';
		$this->title = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER;
		$this->_payments = new wirecard_checkout_page_payments();
		$this->_config = new wirecard_checkout_page_configuration();
		$this->enabled =  ((MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS == 'True') ? true : false);

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

	/**
     * Create radio buttons for enabled payment fields
     *
	 * @return array
	 */
	function selection() {

	    if ( count($this->_payments->get_enabled_paymenttypes()) ) {
		    return array(
			    'id'     => $this->code,
			    'module' => $this->_payments->get_payment_selection( $this->code )
		    );
	    }
	}

	function pre_confirmation_check()
	{
		return false;
	}

	/**
     * Prints fields before confirmation
     *
	 * @return array
	 */
	function confirmation()
	{
		$paymentType= $_POST['wirecard_checkout_page'];

		if (tep_session_is_registered('customer_id'))
		{
			$consumerID = $_SESSION['customer_id'];
		}
		if( isset( $_SESSION['wcp-consumerDeviceId'] ) ) {
			$consumerDeviceId = $_SESSION['wcp-consumerDeviceId'];
		} else {
			$timestamp = microtime();
			$consumerDeviceId = md5( $consumerID . "_" . $timestamp );
			$_SESSION['wcp-consumerDeviceId'] = $consumerDeviceId;
		}
		$ratepay = '<script language="JavaScript">var di = {t:"' . $consumerDeviceId . '",v:"WDWL",l:"Checkout"};</script>';
		$ratepay .= '<script type="text/javascript" src="//d.ratepay.com/' . $consumerDeviceId . '/di.js"></script>';
		$ratepay .= '<noscript><link rel="stylesheet" type="text/css" href="//d.ratepay.com/di.css?t=' . $consumerDeviceId . '&v=WDWL&l=Checkout"></noscript>';
		$ratepay .= '<object type="application/x-shockwave-flash" data="//d.ratepay.com/WDWL/c.swf" width="0" height="0"><param name="movie" value="//d.ratepay.com/WDWL/c.swf" /><param name="flashvars" value="t=' . $consumerDeviceId . '&v=WDWL"/><param name="AllowScriptAccess" value="always"/></object>';
		echo $ratepay;

		$sql = 'SELECT customers_dob, customers_fax FROM ' . TABLE_CUSTOMERS . ' WHERE customers_id="' . $consumerID . '" LIMIT 1;';
		$result = tep_db_query($sql);
		$consumerInformation = $result->fetch_assoc();
		$consumerBirthDate = date( 'Y' ) . "-" . date( 'm' ) . "-" . date( 'd' );
		if ($consumerInformation['customers_dob'] != '0000-00-00 00:00:00')
		{
			$consumerBirthDateTimestamp = strtotime($consumerInformation['customers_dob']);
			$consumerBirthDate = date('Y-m-d', $consumerBirthDateTimestamp);
		}

		$fields = array();
		switch ( $paymentType ) {
			case 'installment':
            case 'invoice':
			    $birthday = '<script type="text/javascript">
                                function checkBirthday(){  
                                    var m = $(\'#wcp_month\').val();
                                    var d = $(\'#wcp_day\').val();
                                    var dateStr = $(\'#wcp_year\').val() + \'-\' + m + \'-\' + d;
                                    var minAge = 18;

                                    var birthdate = new Date(dateStr);
                                    var year = birthdate.getFullYear();
                                    var today = new Date();
                                    var limit = new Date((today.getFullYear() - minAge), today.getMonth(), today.getDate());
                                    if (birthdate <= limit) {
                                        $(\'#wcp-birthday\').val(birthdate);
                                        $(\'#wcp-no-valid-birthday\').hide();
                                        $(\'#tdb5\').attr(\'disabled\', false);
                                    } else {
                                        $(\'#wcp-no-valid-birthday\').show();
                                        $(\'#tdb5\').attr(\'disabled\', true);
                                    }
		                        }
		                        window.onload = function() {
                                    $(document).ready(function() {
                                        checkBirthday();
                                    });
                                };
			                </script>';
				$birthday .= "<input type='hidden' id='wcp-birthday' name='consumerBirthDate'/><select name='wcp_day' id='wcp_day' onchange='checkBirthday();' class=''>";

				for ( $day = 31; $day > 0; $day -- ) {
					$birthday .= "<option value='$day'> $day </option>";
				}

				$birthday .= "</select>";

				$birthday .= "<select name='wcp_month' id='wcp_month' onchange='checkBirthday();' class=''>";
				for ( $month = 12; $month > 0; $month -- ) {
					$birthday .= "<option value='$month'> $month </option>";
				}
				$birthday .= "</select>";

				$birthday .= "<select name='wcp_year' id='wcp_year' onchange='checkBirthday();' class=''>";
				for ( $year = date( "Y" ); $year > 1900; $year -- ) {
					$birthday .= "<option value='$year'> $year </option>";
				}
				$birthday .= "</select><div id='wcp-no-valid-birthday'>".MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_BIRTHDAY_ERROR."</div>";
				$birthDayField = array(
					'title' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_BIRTHDAY_TEXT,
					'field' => $birthday
				);

				array_push( $fields, $birthDayField );
				array_push( $fields, array( 'title' => '', 'field' => '<br/>' ) );

				$terms    = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TERMS;
				$mId      = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MID;

				if ( $paymentType == 'installment' ) {
					$provider = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_PROVIDER;
				} else {
					$provider = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_PROVIDER;
				}

				$payolutionTerms = '<input type="checkbox" name="wcp_payolutionterms" required />&nbsp;<span>' . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_CONSENT1;
				if ( strlen( $mId ) ) {
					$payolutionTerms .= '<a id="wcp-payolutionlink" href="https://payment.payolution.com/payolution-payment/infoport/dataprivacyconsent?mId=' . $mId . '" target="_blank"><b>' . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_LINK . '</b></a>';
				} else {
					$payolutionTerms .= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_LINK;
				}
				$payolutionTerms .= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_CONSENT2 . '</span>';

				if ( $terms && $provider == 'payolution' ) {
					array_push( $fields, array(
						'title' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_TERMS,
						'field' => $payolutionTerms
					) );
				}
				break;
			case 'eps':
			    $institutions = $this->_payments->get_eps_financial_institutions();
				$institution_field = tep_draw_pull_down_menu("financialInstitution", $institutions, '', 'class="form-control" required=true');
				$field = array('title' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_FINANCIAL_INSTITUTION, 'field' => $institution_field);

				array_push($fields, $field);
				break;
			case 'idl':
				$institutions = $this->_payments->get_idl_financial_institutions();
				$institution_field = tep_draw_pull_down_menu("financialInstitution", $institutions, '', 'class="form-control" required=true');
				$field = array('title' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_FINANCIAL_INSTITUTION, 'field' => $institution_field);

				array_push($fields, $field);
				break;
            default:
                break;
		}

		return array('fields' => $fields);
	}

	function process_button()
	{
		global $order, $order_total_modules, $currencies, $currency, $languages_id;

		$this->transaction_id = $this->generate_trid();

		// construct the returnUrl. we will use one url for all types of return (success, pending, cancel, failure)
		// FILENAME_CHECKOUT_PROCESS
		$returnUrl = tep_href_link(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_RETURN, '', 'SSL');
		$confirmUrl = tep_href_link(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIRM, '', 'SSL');


		$paymentType = (isset($_POST["wirecard_checkout_page"]) && tep_not_null($_POST["wirecard_checkout_page"])) ? $_POST["wirecard_checkout_page"] : "SELECT";
		$qLanguage = $this->getLanguageCode($languages_id);
		$qCurrency = $order->info['currency'];
		$this->transaction_id = $this->generate_trid();
		$amount = tep_round($order->info['total'] * $currencies->get_value($qCurrency), 2);
		$pluginVersion = base64_encode('osCommerce; ' . PROJECT_VERSION . '; ; osCommerce; ' . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PLUGINVERSION);

		$config = $this->_config->get_client_config($qLanguage);

		$postData = Array('customerId' => $config['CUSTOMER_ID'],
		                  'shopId' => $config['SHOP_ID'],
		                  'imageURL' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL,
		                  'amount' => $amount,
		                  'paymentType' => $paymentType,
		                  'currency' => $qCurrency,
		                  'language' => $qLanguage,
		                  'orderDescription' => $this->get_order_description(),
		                  'orderReference' => $this->get_order_reference(),
		                  'customerStatement' => $this->get_customer_statement($paymentType),
		                  'successURL' => $returnUrl,
		                  'failureURL' => $returnUrl,
		                  'cancelURL' => $returnUrl,
		                  'pendingURL' => $returnUrl,
		                  'confirmURL' => $confirmUrl,
		                  'serviceURL' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL,
		                  'trid' => $this->transaction_id,
		                  'pluginVersion' => $pluginVersion,
		                  'maxRetries' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MAX_RETRIES,
		                  'displayText' => MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DISPLAY_TEXT,
		                  'consumerMerchantCrmId' => md5($order->customer['email_address']));

		if( isset( $_SESSION['wcp-consumerDeviceId'] ) ) {
			$postData['consumerDeviceId'] = $_SESSION['wcp-consumerDeviceId'];
			unset( $_SESSION['wcp-consumerDeviceId'] );
		}
		
		if ( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DEPOSIT == 'True' ) {
			$postData['autoDeposit'] = true;
		}
		if ( $paymentType == 'masterpass' ) {
			$postData['shippingProfile'] = 'NO_SHIPPING';
        }
        $consumerData = $this->create_consumer_data($paymentType);
		$postData = array_merge($postData, $consumerData);

		if ( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BASKET == 'True' ||
		     ( $paymentType == 'invoice' && MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_PROVIDER != 'payolution' ) ||
		     ( $paymentType == 'installment' && MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_PROVIDER != 'payolution' )
		) {
			$postData = array_merge( $postData, $this->create_basket_data() );
		}

		if (MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME == 'True') {
			$postData['windowName'] = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_WINDOW_NAME;
		}

		$requestFingerprintOrder = 'secret';
		$tempArray = array('secret' => $this->_config->get_client_secret());
		foreach ($postData AS $parameterName => $parameterValue) {
			$requestFingerprintOrder .= ',' . $parameterName;
			$tempArray[(string)$parameterName] = (string)$parameterValue;
		}
		$requestFingerprintOrder .= ',requestFingerprintOrder';
		$tempArray['requestFingerprintOrder'] = $requestFingerprintOrder;

		$hash = hash_init('sha512', HASH_HMAC, $this->_config->get_client_secret());
		foreach ($tempArray as $key => $value) {
			hash_update($hash, $value);
		}

		$postData['requestFingerprintOrder'] = $requestFingerprintOrder;
		$postData['requestFingerprint'] = hash_final($hash);

		$result = tep_db_query("INSERT INTO " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE . " (TRID, PAYSYS, DATE) VALUES ('" . $this->transaction_id . "', '" . $paymentType . "', NOW())");

		$process_button_string = '';
		foreach ($postData AS $parameterName => $parameterValue)
		{
			$process_button_string .= '<input type="hidden" name="'.$parameterName.'" value="'.$parameterValue.'"/>';
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

	/**
     * Creates consumer data according to config settings
     *
	 * @param $paymentType
	 *
	 * @return array consumerData
	 */
	function create_consumer_data($paymentType) {
		global $order;

		$consumerID = '';
		$consumerData = array();
		$consumerData['IpAddress'] = $_SERVER['REMOTE_ADDR'];
		$consumerData['UserAgent'] = $_SERVER['HTTP_USER_AGENT'];

		if (tep_session_is_registered('customer_id'))
		{
			$consumerID = $_SESSION['customer_id'];
		}

		$sql = 'SELECT customers_dob, customers_fax FROM ' . TABLE_CUSTOMERS . ' WHERE customers_id="' . $consumerID . '" LIMIT 1;';
		$result = tep_db_query($sql);
		$consumerInformation = $result->fetch_assoc();
		if ($consumerInformation['customers_dob'] != '0000-00-00 00:00:00')
		{
			$consumerBirthDateTimestamp = strtotime($consumerInformation['customers_dob']);
			$consumerBirthDate = date('Y-m-d', $consumerBirthDateTimestamp);
			$consumerData['consumerBirthDate'] = $consumerBirthDate;
		}

		$consumerData['consumerEmail'] = $order->customer['email_address'];

		if ( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_SHIPPING == 'True' || $paymentType == 'invoice' || $paymentType == 'installment' ) {
			$deliveryInformation = $order->delivery;

			if ($deliveryInformation['country']['iso_code_2'] == 'US' || $deliveryInformation['country']['iso_code_2'] == 'CA')
			{
				$deliveryState = $this->_getZoneCodeByName($deliveryInformation['state']);
			}
			else
			{
				$deliveryState = tep_get_zone_code($deliveryInformation['country']['id'], $deliveryInformation['zone_id'], '');
			}
			$shippingData = Array(
				'consumerShippingFirstName' => $deliveryInformation['firstname'],
				'consumerShippingLastName' => $deliveryInformation['lastname'],
				'consumerShippingAddress1' => $deliveryInformation['street_address'],
				'consumerShippingAddress2' => $deliveryInformation['suburb'],
				'consumerShippingCity' => $deliveryInformation['city'],
				'consumerShippingZipCode' => $deliveryInformation['postcode'],
				'consumerShippingState' => $deliveryState,
				'consumerShippingCountry' => $deliveryInformation['country']['iso_code_2'],
				'consumerShippingPhone' => $order->customer['telephone']);
			$consumerData = array_merge($consumerData, $shippingData);
        }

		if ( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BILLING == 'True'  || $paymentType == 'invoice' || $paymentType == 'installment' ) {
			$billingInformation = $order->billing;
			if ( $billingInformation['country']['iso_code_2'] == 'US' || $billingInformation['country']['iso_code_2'] == 'CA' ) {
				$billingState = $this->_getZoneCodeByName( $billingInformation['state'] );
			} else {
				$billingState = tep_get_zone_code( $billingInformation['country']['id'], $billingInformation['zone_id'],
					'' );
			}
			$billingData = Array(
				'consumerBillingFirstName' => $billingInformation['firstname'],
				'consumerBillingLastName'  => $billingInformation['lastname'],
				'consumerBillingAddress1'  => $billingInformation['street_address'],
				'consumerBillingAddress2'  => $billingInformation['suburb'],
				'consumerBillingCity'      => $billingInformation['city'],
				'consumerBillingZipCode'   => $billingInformation['postcode'],
				'consumerBillingState'     => $billingState,
				'consumerBillingCountry'   => $billingInformation['country']['iso_code_2'],
				'consumerBillingPhone'     => $order->customer['telephone']
			);
			$consumerData = array_merge($consumerData, $billingData);
		}
		return $consumerData;
    }

	/**
     * Returns basket including products and shipping
     *
	 * @return array
	 */
    function create_basket_data() {
	    global $order;
	    $basket_prefix = 'basketItem';
	    $basket = array();
	    $count = 0;
	    $tax = 0;

	    foreach ($order->products as $product) {
	        $count++;
		    $tax_amount = tep_calculate_tax($product['price'], $product['tax']);
		    $tax += $tax_amount;
		    $basket[$basket_prefix . $count .'articleNumber'] = $product['model'];
		    $basket[$basket_prefix . $count .'unitGrossAmount'] = tep_round($product['price'], 2);
		    $basket[$basket_prefix . $count .'unitNetAmount'] = tep_round($product['price'] - $tax_amount, 2);
		    $basket[$basket_prefix . $count .'unitTaxAmount'] = tep_round($tax_amount, 2);
		    $basket[$basket_prefix . $count .'unitTaxRate'] = $product['tax'];
		    $basket[$basket_prefix . $count .'description'] = $product['name'];
		    $basket[$basket_prefix . $count .'name'] = $product['name'];
		    $basket[$basket_prefix . $count .'quantity'] = $product['qty'];
        }

	    if (isset($order->info['shipping_method'])) {
		    $count++;
		    $basket[$basket_prefix .$count .'articleNumber'] = 'shipping';
		    $basket[$basket_prefix .$count .'unitGrossAmount'] =  tep_round($order->info['shipping_cost'], 2);
		    $basket[$basket_prefix .$count .'unitNetAmount'] =  tep_round($order->info['shipping_cost'], 2);
		    $basket[$basket_prefix .$count .'unitTaxRate'] = 0;
		    $basket[$basket_prefix .$count .'unitTaxAmount'] = 0;
		    $basket[$basket_prefix .$count .'name'] = $order->info['shipping_method'];
		    $basket[$basket_prefix .$count .'description'] = $order->info['shipping_method'];
		    $basket[$basket_prefix .$count .'quantity'] = 1;
	    }
	    $basket['basketItems'] = $count;

	    return $basket;
    }

	/**
     * Get order description
     *
	 * @return string
	 */
	function get_order_description(){
		global $order;
		return sprintf('%s %s %s', $order->customer['email_address'], $order->customer['firstname'], $order->customer['lastname']);
    }

	/**
     * Get order reference
     *
	 * @return string
	 */
	function get_order_reference() {
		return sprintf( '%010s', substr( $this->transaction_id, - 10 ) );
	}

	/**
     * create customerStatement
     *
	 * @param $payment_type
	 *
	 * @return string
	 */
	function get_customer_statement( $payment_type ) {
		$shop_name = sprintf( '%9s', substr( STORE_NAME, - 9 ) );
		$order_reference = $this->get_order_reference();
		if ( $payment_type == 'poli' ) {
			return $shop_name;
		}
		return $shop_name . ' ' . $order_reference;
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
		global $insert_id, $order, $cart;

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
		}
		if ($order->info['order_status'] == $this->order_status_pending ) {
			tep_redirect(tep_href_link(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SUCCESS, '', 'SSL'));
		} else {
			$cart->reset(true);
			tep_session_unregister('sendto');
			tep_session_unregister('billto');
			tep_session_unregister('shipping');
			tep_session_unregister('payment');
			tep_session_unregister('comments');

			tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
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
				$tempArray[(string)$key] = $this->_config->get_client_secret();
				$secretUsed = 1;
			}
			else
			{
				$tempArray[(string)$key] = $responseArray[$key];
			}
		}

		$hash = hash_init('sha512', HASH_HMAC, $this->_config->get_client_secret());

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
		$configuration = "'tep_cfg_select_option(array(\'demo\', \'test\', \'test3d\', \'production\'), '";
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Wirecard Checkout Page Module', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS', 'True', 'Do you want to accept Wirecard Checkout Page payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Configuration', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIG', 'demo', 'For integration, select predefined configuration settings or \'Production\' for live systems', '6', '0', $configuration, now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CustomerId', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID', '', 'Customer number you received from Wirecard (customerId, i.e. D2#####).', '6', '1', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ShopId', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID', '', 'Shop identifier in case of more than one shop.', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET', '', 'String which you received from Wirecard for signing and validating data to prove their authenticity.', '6', '3', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('IFrame', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME', 'False', 'Open Wirecard Checkout Page inside an IFrame.', '6', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

		$this->_payments->install_paymenttypes();

		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ServiceUrl', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL', '', 'URL to web page containing your contact information (imprint).', '6', '300', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ImageUrl', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL', '', 'Image Url for displaying an image on the Wirecard Checkout Page (95x65 pixels preferred).', '6', '301', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max. Retries', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MAX_RETRIES', '-1', 'Maximal number of payment retries.', '6', '302', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display text', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DISPLAY_TEXT', '', 'Display Text on the Wirecard Checkout Page.', '6', '302', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Automated deposit', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DEPOSIT', 'False', 'Enabling an automated deposit of payments. Please contact our sales teams to activate this feature.', '6', '302', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first', '6', '400', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone', '6', '410', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value.', '6', '420', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status Pending', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID', '0', 'Set the status of orders made with this payment module, which are in paymentstate pending.', '6', '430', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Forward basket data', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BASKET', 'False', 'Forwarding basket data to the respective financial service provider.', '6', '601', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Forward consumer shipping data', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_SHIPPING', 'False', 'Forwarding shipping data about your consumer to the respective financial service provider.', '6', '602', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Forward consumer billing data', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BILLING', 'False', 'Forwarding billing data about your consumer to the respective financial service provider.', '6', '603', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		$this->_payments->install_invoice_installment_settings();

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
			tep_db_query("DROP TABLE IF EXISTS " . MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TRANSACTION_TABLE);
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
		$keys = array('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIG',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MAX_RETRIES',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DISPLAY_TEXT',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DEPOSIT',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BASKET',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_SHIPPING',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BILLING',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE',
		);
		$invoice_installment = array(
            'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TERMS',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MID',
            'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_PROVIDER',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_SHIPPING',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_COUNTRIES',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_CURRENCIES',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_PROVIDER',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_SHIPPING',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_COUNTRIES',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_CURRENCIES',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT',
			'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT',
        );
		$keys = array_merge($keys, $this->_payments->get_paymenttypes_code());
		return array_merge($keys, $invoice_installment);
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
		$resultRow = $result->fetch_row();
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