<?php

class wirecard_checkout_page_payments {

	/**
	 * Array of paymenttypes (includes code and label)
	 *
	 * @var array
	 */
	protected $payment_types = array(
		array('code' => 'SELECT', 'label' => 'Select'),
		array('code' => 'CCARD', 'label' => 'Credit Card'),
		array('code' => 'CCARD-MOTO', 'label' => 'Credit Card - Mail Order and Telephone Order'),
		array('code' => 'MASTERPASS', 'label' => 'Masterpass' ),
		array('code' => 'MAESTRO', 'label' => 'Maestro SecureCode'),
		array('code' => 'BANCONTACT_MISTERCASH', 'label' => 'Bancontact'),
		array('code' => 'EKONTO', 'label' => 'Ekonto'),
		array('code' => 'EPAY_BG', 'label' => 'ePay.bg'),
		array('code' => 'EPS', 'label' => 'eps-Überweisung'),
		array('code' => 'GIROPAY', 'label' => 'giropay'),
		array('code' => 'IDL', 'label' => 'iDEAL'),
		array('code' => 'INSTALLMENT', 'label' => 'Installment'),
		array('code' => 'INVOICE', 'label' => 'Invoice'),
		array('code' => 'MONETA', 'label' => 'moneta.ru'),
		array('code' => 'PRZELEWY24', 'label' => 'Przelewy24'),
		array('code' => 'PAYPAL', 'label' => 'PayPal'),
		array('code' => 'PBX', 'label' => 'paybox'),
		array('code' => 'POLI', 'label' => 'POLi'),
		array('code' => 'PSC', 'label' => 'paysafecard'),
		array('code' => 'SEPA-DD', 'label' => 'SEPA Direct Debit'),
		array('code' => 'SKRILLWALLET', 'label' => 'Skrill Digital Wallet'),
		array('code' => 'SOFORTUEBERWEISUNG', 'label' => 'SOFORT Banking'),
		array('code' => 'TATRAPAY', 'label' => 'TatraPay'),
		array('code' => 'TRUSTLY', 'label' => 'Trustly'),
		array('code' => 'VOUCHER', 'label' => 'My Voucher')
	);

	/**
	 * Get paymenttype array
	 *
	 * @return array
	 */
	public function get_paymenttypes() {
		return $this->payment_types;
	}

	/**
	 * Insert paymenttypes into database
	 */
	public function install_paymenttypes() {
		$count = 100;
		foreach($this->get_paymenttypes() as $payment) {
			$enable = "'Enable " . $payment['label'] ."'";
			$module = "'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_".$payment['code'] ."'";
			$label = "'" . $payment['label'] . "'";
			tep_db_query("insert into " . TABLE_CONFIGURATION .
			             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
			             values ($enable, $module, 'False', $label, '6', '$count', 'tep_cfg_select_option(array(\'False\', \'True\'), ', now())");
			$count++;
		}
	}

	/**
	 * Insert invoice and installment configuration settings
	 */
	public function install_invoice_installment_settings() {
		$invoice_provider = "'tep_draw_pull_down_menu(\'inv_provider\', array(array(\'id\' => \'payolution\', \'text\' => \'payolution\'), array(\'id\' => \'ratepay\', \'text\' => \'RatePay\'), array(\'id\' => \'ratepay\', \'text\' => \'Wirecard\')), '";
		$installment_provider = "'tep_draw_pull_down_menu(\'inst_provider\', array(array(\'id\' => \'payolution\', \'text\' => \'payolution\'), array(\'id\' => \'ratepay\', \'text\' => \'RatePay\')), '";

		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('payolution terms', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TERMS', 'False', '', '6', '500', 'tep_cfg_select_option(array(\'True\', \'False\'), ' , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('payolution mID', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MID', '', '', '6', '501', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('Invoice provider', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_PROVIDER', 'payolution', '', '6', '502', $invoice_provider , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('Invoice billing/shipping address must be identical', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_SHIPPING', 'False', '', '6', '503', 'tep_cfg_select_option(array(\'True\', \'False\'), ' , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Allowed countries for Invoice', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_COUNTRIES', '', 'Insert allowed countries (e.g. AT,DE)', '6', '504', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Allowed currencies for Invoice', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_CURRENCIES', '', 'Insert allowed currencies (e.g. EUR,CHF)', '6', '505', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Invoice minimum amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT', '10', '', '6', '506', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Invoice maximum amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT', '3500', '', '6', '507', now())");

		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('Installment provider', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_PROVIDER', 'payolution', '', '6', '550', $installment_provider , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('Installment billing/shipping address must be identical', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_SHIPPING', 'False', '', '6', '551', 'tep_cfg_select_option(array(\'True\', \'False\'), ' , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Allowed countries for Installment', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_COUNTRIES', '', 'Insert allowed countries (e.g. AT,DE)', '6', '552', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Allowed currencies for Installment', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_CURRENCIES', '', 'Insert allowed currencies (e.g. EUR,CHF)', '6', '553', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Installment minimum amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT', '150', '','6', '551', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('Installment maximum amount', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT', '3500', '','6', '553', now())");

	}

	/**
	 * Returns paymenttype codes
	 *
	 * @return array
	 */
	public function get_paymenttypes_code() {
		$payments = array();
		foreach ($this->get_paymenttypes() as $payment) {
			array_push($payments, 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_'.$payment['code']);
		}
		return $payments;
	}

	/**
	 * Returns only paymenttypes which are enabled
	 *
	 * @return array
	 */
	public function get_enabled_paymenttypes() {
		$payments = array();
		foreach ($this->get_paymenttypes() as $payment) {
			$module = "'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_".$payment['code']."'";
			$query = tep_db_query("select configuration_value from " .TABLE_CONFIGURATION . " where configuration_key=".$module);
			$result = tep_db_fetch_array($query);
			if ($result['configuration_value'] == 'True') {
				$payments[] = $payment;
			}
		}
		return $payments;
	}

	/**
	 * Create radio buttons for payment methods and add payment fields
	 *
	 * @param $code
	 *
	 * @return string
	 */
	public function get_payment_selection( $code ) {
		$content = '';
		$count   = 0;
		$content .= '<input id="wirecard_checkout_page_payment" type="hidden" name="wirecard_checkout_page" value="select">';

		foreach ( $this->get_enabled_paymenttypes() as $payment ) {
			if ( $count == 0) {
				$content .= '</strong></td><td>';
			}
			$count++;
			$id = "wirecard_payment_table_".$count;
			$payment_code = strtolower($payment['code']);
			$js_helper = "document.getElementById('wirecard_checkout_page_payment').value='".$payment_code."'";
			$content .= '</td></tr></tbody></table><table id="'.$id.'" border="0" width="100%" cellspacing="0" cellpadding="2"><tbody><tr><td>';
			$content .= '<strong>' . $payment['label'] . '</strong></td><td align="right">'.
			'<input type="radio" name="payment" value="' .$code. '" onclick='.$js_helper.'>';
		}

		$content .= '</td></tr><tr style="display:none;">';
		$content .= '
		<script type="text/javascript">
				var checkoutTable = document.getElementById("wirecard_payment_table_1").previousElementSibling;
				checkoutTable.style.display = "none";
			</script>';

		return $content;
	}
}