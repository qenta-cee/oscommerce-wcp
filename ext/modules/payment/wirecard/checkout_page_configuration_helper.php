<?php
class wirecard_checkout_page_configuration {

	/**
	 * Test/Demo configuration
	 *
	 * @var array
	 */
	protected $_presets = array(
		'demo'   => array(
			'customer_id' => 'D200001',
			'shop_id'     => '',
			'secret'      => 'B8AKTPWBRMNBV455FG6M2DANE99WU2',
			'backendpw'   => 'jcv45z'
		),
		'test'   => array(
			'customer_id' => 'D200411',
			'shop_id'     => '',
			'secret'      => 'CHCSH7UGHVVX2P7EHDHSY4T2S4CGYK4QBE4M5YUUG2ND5BEZWNRZW5EJYVJQ',
			'backendpw'   => '2g4f9q2m'
		),
		'test3d' => array(
			'customer_id' => 'D200411',
			'shop_id'     => '3D',
			'secret'      => 'DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F',
			'backendpw'   => '2g4f9q2m'
		)
	);

	/**
	 * Handles configuration modi and returns specific config array for Frontendclient
	 *
	 * @return array
	 */
	public function get_client_config($language = 'en') {
		$config_mode = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIG;

		if ( array_key_exists( $config_mode, $this->_presets ) ) {
			return Array(
				'CUSTOMER_ID' => $this->_presets[ $config_mode ]['customer_id'],
				'SHOP_ID'     => $this->_presets[ $config_mode ]['shop_id'],
				'SECRET'      => $this->_presets[ $config_mode ]['secret'],
				'LANGUAGE'    => $language
			);
		} else {
			return Array(
				'CUSTOMER_ID' => trim( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID ),
				'SHOP_ID'     => trim( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID ),
				'SECRET'      => trim( MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET ),
				'LANGUAGE'    => $language
			);
		}
	}

	/**
	 * Provides the configured secret from config array or from secret field
	 *
	 * @return mixed
	 */
	public function get_client_secret() {
		$curr_config = $this->get_client_config();
		return $curr_config['SECRET'];
	}
}