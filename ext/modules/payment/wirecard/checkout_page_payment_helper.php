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

	public function get_payment_selection( $code, $title ) {
		$content = '';
		$first   = 0;
		$content .= '<input id="wirecard_checkout_page_payment" type="hidden" name="wirecard_checkout_page" value="select">';

		foreach ( $this->get_enabled_paymenttypes() as $payment ) {
			if ( $first == 0) {
				$content .= '</strong></td><td>';
			}
			$first++;
			$payment_code = strtolower($payment['code']);
			$js_helper = "document.getElementById('wirecard_checkout_page_payment').value='".$payment_code."'";
			$content .= '</td></tr></tbody></table><table border="0" width="100%" cellspacing="0" cellpadding="2"><tbody><tr><td>';
			$content .= '<strong>' . $payment['label'] . '</strong></td><td align="right">'.
			'<input type="radio" name="payment" value="' .$code. '" onclick='.$js_helper.'>';
		}

		$content .= '</td></tr><tr style="display:none;">';

		return $content;
	}
}