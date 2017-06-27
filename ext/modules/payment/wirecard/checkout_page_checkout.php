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

chdir('../../../../');
require('includes/application_top.php');
require_once(DIR_FS_CATALOG.'ext/modules/payment/wirecard/checkout_page_payment_helper.php');
require_once (DIR_FS_CATALOG.'includes/modules/payment/wirecard_checkout_page.php');

// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
}

// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
}


if(!isset($_SESSION['wirecard_checkout_page']['form']))
{
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2);

require(DIR_WS_INCLUDES . 'template_top.php');

$iframeUrl = tep_href_link(MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IFRAME, '', 'SSL');
$windowName = MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_WINDOW_NAME;

?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <iframe src="<?php echo $iframeUrl ?>" width="100%" height="650" frameborder="0" name="<?php echo $windowName ?>">
                <p>Your browser does not support iframes.</p>
            </iframe>
        </td>
    </tr>
</table>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');