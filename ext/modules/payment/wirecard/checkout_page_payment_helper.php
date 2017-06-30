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
		array('code' => 'VOUCHER', 'label' => 'My Voucher'),
		array('code' => 'QUICK', 'label' => 'Quick')
	);

	/**
	 * eps financial institutions
	 *
	 * @var array
	 */
	protected static $_eps_financial_institutions = Array(
		Array( 'id' => 'ARZ|AB', 'text' => 'Apothekerbank'),
		Array( 'id' => 'ARZ|AAB', 'text' => 'Austrian Anadi Bank AG'),
		Array( 'id' => 'ARZ|BAF', 'text' => '&Auml;rztebank'),
		Array( 'id' => 'BA-CA', 'text' => 'Bank Austria'),
		Array( 'id' => 'ARZ|BCS', 'text' => 'Bankhaus Carl Sp&auml;ngler & Co. AG'),
		Array( 'id' => 'ARZ|BSS', 'text' => 'Bankhaus Schelhammer & Schattera AG'),
		Array( 'id' => 'Bawag|BG', 'text' => 'BAWAG P.S.K. AG'),
		Array( 'id' => 'ARZ|BKS', 'text' => 'BKS Bank AG'),
		Array( 'id' => 'ARZ|BKB', 'text' => 'Br&uuml;ll Kallmus Bank AG'),
		Array( 'id' => 'ARZ|BTV', 'text' => 'BTV VIER L&Auml;NDER BANK'),
		Array( 'id' => 'ARZ|CBGG', 'text' => 'Capital Bank Grawe Gruppe AG'),
		Array( 'id' => 'ARZ|VB', 'text' => 'Volksbank Gruppe'),
		Array( 'id' => 'ARZ|DB', 'text' => 'Dolomitenbank'),
		Array( 'id' => 'Bawag|EB', 'text' => 'Easybank AG'),
		Array( 'id' => 'Spardat|EBS', 'text' => 'Erste Bank und Sparkassen'),
		Array( 'id' => 'ARZ|HAA', 'text' => 'Hypo Alpe-Adria-Bank International AG'),
		Array( 'id' => 'ARZ|VLH', 'text' => 'Hypo Landesbank Vorarlberg'),
		Array( 'id' => 'ARZ|HI', 'text' => 'HYPO NOE Gruppe Bank AG'),
		Array( 'id' => 'ARZ|NLH', 'text' => 'HYPO NOE Landesbank AG'),
		Array( 'id' => 'Hypo-Racon|O', 'text' => 'Hypo Ober&ouml;sterreich'),
		Array( 'id' => 'Hypo-Racon|S', 'text' => 'Hypo Salzburg'),
		Array( 'id' => 'Hypo-Racon|St', 'text' => 'Hypo Steiermark'),
		Array( 'id' => 'ARZ|HTB', 'text' => 'Hypo Tirol Bank AG'),
		Array( 'id' => 'BB-Racon', 'text' => 'HYPO-BANK BURGENLAND Aktiengesellschaft'),
		Array( 'id' => 'ARZ|IB', 'text' => 'Immo-Bank'),
		Array( 'id' => 'ARZ|OB', 'text' => 'Oberbank AG'),
		Array( 'id' => 'Racon', 'text' => 'Raiffeisen Bankengruppe &Ouml;sterreich'),
		Array( 'id' => 'ARZ|SB', 'text' => 'Schoellerbank AG'),
		Array( 'id' => 'Bawag|SBW', 'text' => 'Sparda Bank Wien'),
		Array( 'id' => 'ARZ|SBA', 'text' => 'SPARDA-BANK AUSTRIA'),
		Array( 'id' => 'ARZ|VKB', 'text' => 'Volkskreditbank AG'),
		Array( 'id' => 'ARZ|VRB', 'text' => 'VR-Bank Braunau')
	);

	/**
	 * idl financial institutions
	 *
	 * @var array
	 */
	protected static $_idl_financial_institutions = Array(
		Array( 'id' => 'ABNAMROBANK', 'text' => 'ABN AMRO Bank'),
		Array( 'id' => 'ASNBANK', 'text' => 'ASN Bank'),
		Array( 'id' => 'BUNQ', 'text' => 'Bunq Bank'),
		Array( 'id' => 'INGBANK', 'text' => 'ING'),
		Array( 'id' => 'KNAB', 'text' => 'knab'),
		Array( 'id' => 'RABOBANK', 'text' => 'Rabobank'),
		Array( 'id' => 'SNSBANK', 'text' => 'SNS Bank'),
		Array( 'id' => 'REGIOBANK', 'text' => 'RegioBank'),
		Array( 'id' => 'TRIODOSBANK', 'text' => 'Triodos Bank'),
		Array( 'id' => 'VANLANSCHOT', 'text' => 'Van Lanschot Bankiers')
	);

	/**
	 * Pre check for Invoice (currencies, countries, amount)
	 *
	 * @return bool
	 */
	protected function preInvoiceCheck() {
		global $order, $currencies;

		$currency = $order->info['currency'];
		$total    = $order->info['total'];
		$amount   = $total;

		$currencies   = explode( ",", MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_CURRENCIES );
		$countries    = explode( ",", MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_COUNTRIES );
		$country_code = $order->billing['country']['iso_code_2'];

		if ( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_SHIPPING == "True" &&
		     $order->delivery == ! $order->billing &&
		     MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_PROVIDER == 'payolution'
		) {
			return false;
		}

		return ( ( $amount >= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT && $amount <= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT ) &&
		         ( in_array( $currency,
				         $currencies ) && strlen( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_CURRENCIES ) ) &&
		         ( in_array( $country_code,
				         $countries ) && strlen( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_COUNTRIES ) ) );

	}

	/**
	 * Pre check for Installment (currencies, countries, amount)
	 *
	 * @return bool
	 */
	protected function preInstallmentCheck() {
		global $order, $currencies;

		$currency = $order->info['currency'];
		$total    = $order->info['total'];
		$amount   = $total;

		$currencies   = explode( ",", MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_CURRENCIES );
		$countries    = explode( ",", MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_COUNTRIES );
		$country_code = $order->billing['country']['iso_code_2'];

		if ( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_SHIPPING == "True" &&
		     $order->delivery == ! $order->billing &&
		     MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_PROVIDER == 'payolution'
		) {
			return false;
		}

		return ( ( $amount >= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT && $amount <= MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT ) &&
		         ( in_array( $currency,
				         $currencies ) && strlen( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_CURRENCIES ) ) &&
		         ( in_array( $country_code,
				         $countries ) && strlen( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_COUNTRIES ) ) );

	}
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
		$invoice_provider = "'tep_cfg_select_option(array(\'payolution\', \'RatePay\', \'Wirecard\'), '";
		$installment_provider = "'tep_cfg_select_option(array(\'payolution\', \'RatePay\'), '";

		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('payolution terms', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TERMS', 'False', 'Consumer must accept payolution terms during the checkout process.', '6', '500', 'tep_cfg_select_option(array(\'True\', \'False\'), ' , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
		             values ('payolution mID', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MID', '', 'Your payolution merchant ID, non-base64-encoded.', '6', '501', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('Invoice provider', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_PROVIDER', 'payolution', 'Choose your invoice provider.', '6', '502', $invoice_provider , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('Invoice billing/shipping address must be identical', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_SHIPPING', 'False', 'Only applicable for payolution', '6', '503', 'tep_cfg_select_option(array(\'True\', \'False\'), ' , now())");
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
		             values ('Installment provider', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_PROVIDER', 'payolution', 'Choose your installment provider.', '6', '550', $installment_provider , now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		             " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) 
		             values ('Installment billing/shipping address must be identical', 'MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_SHIPPING', 'False', 'Only applicable for payolution', '6', '551', 'tep_cfg_select_option(array(\'True\', \'False\'), ' , now())");
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
			if ($payment['code'] == 'INVOICE'){
				if ( ! $this->preInvoiceCheck()) {
					continue;
				};
			}
			if ($payment['code'] == 'INSTALLMENT'){
				if ( ! $this->preInstallmentCheck()) {
					continue;
				};
			}
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

	/**
	 * Get array of eps financial institutions
	 *
	 * @return array
	 */
	public function get_eps_financial_institutions() {
		return self::$_eps_financial_institutions;
	}

	/**
	 * Get array of idl financial institutions
	 *
	 * @return array
	 */
	public function get_idl_financial_institutions() {
		return self::$_idl_financial_institutions;
	}
}