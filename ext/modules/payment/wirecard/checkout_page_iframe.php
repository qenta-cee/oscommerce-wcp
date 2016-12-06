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
require_once (DIR_FS_CATALOG.'includes/modules/payment/wirecard_checkout_page.php');
require_once ('includes/languages/'. $_SESSION["language"] .'/modules/payment/wirecard_checkout_page.php');

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

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2);


$redirectText = $_SESSION['wirecard_checkout_page']['paypage_redirecttext'];
$checkoutForm = tep_draw_form('wirecard_checkout_page',
        MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INITIATION_URL,
        'post') . $_SESSION['wirecard_checkout_page']['form'];

//$submitButton = tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE) . "</form>\n";
$submitButton = tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, null, array('type' => 'submit', 'style' => 'height:30px;'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
    <link rel="stylesheet" type="text/css" href="ext/jquery/ui/redmond/jquery-ui-1.8.22.css" />
    <script type="text/javascript" src="ext/jquery/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="ext/jquery/ui/jquery-ui-1.8.22.min.js"></script>
    <link rel="stylesheet" type="text/css" href="stylesheet.css" />

</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center"><?php echo MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_REDIRECTTEXT; ?></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="right"><?php echo $checkoutForm ?>

            <div class="buttonSet">
                <span class="buttonAction"><?php echo $submitButton ?></span>
            </div>

            </form>
        </td>
    </tr>
</table>
<script language="JavaScript" type="text/JavaScript">
    document.wirecard_checkout_page.submit();
</script>
</body>
</html>