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

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_TITLE', 'Wirecard Checkout Page');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_DESCRIPTION', 'Wirecard Checkout Page<br>Zus&auml;tzliche Informationen &uuml;ber WirecardCEE-Produkte erhalten Sie unter http://www.wirecard.at');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS_TITLE','Wirecard Checkout Page Modul aktivieren');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_STATUS_DESC','M&ouml;chten Sie Zahlungen &uuml;ber Wirecard Checkout Page akzeptieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIG_TITLE', 'Konfiguration');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CONFIG_DESC', 'Zum Testen der Integration eine vordefinierte Konfiguration auswählen. Für Produktivsysteme \'Production\' auswählen.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID_TITLE','Kundennummer');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CUSTOMERID_DESC','Geben Sie Ihre WirecardCEE-Kundennummer ein.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID_TITLE','Shop ID');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SHOPID_DESC','Geben Sie Ihre WirecardCEE-shopID ein.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET_TITLE','Secret');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SECRET_DESC','Geben Sie den Secret (preshared key) f&uuml;r die Fingerprint-&Uuml;berpr&uuml;fung ein, den Sie von WirecardCEE erhalten haben.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME_TITLE','IFrame verwenden');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_USE_IFRAME_DESC','Startet den Wirecard Checkout Page Zahlungsprocess in einem IFrame innerhalb des Shops');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MAX_RETRIES_TITLE', 'Max. Versuche');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MAX_RETRIES_DESC', 'Maximale Anzahl an Zahlungsversuchen.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DISPLAY_TEXT_TITLE', 'Text auf der Bezahlseite');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DISPLAY_TEXT_DESC', 'Text, der dem Kunden zu den Bestelldaten angezeigt wird.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DEPOSIT_TITLE', 'Automatisches abbuchen');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DEPOSIT_DESC', 'Automatisches Abbuchen der Zahlungen. Bitte kontaktieren Sie unsere Sales-Teams um dieses Feature freizuschalten.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BASKET_TITLE', 'Warenkorbdaten des Konsumenten mitsenden');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BASKET_DESC', 'Weiterleitung des Warenkorbs des Kunden an den Finanzdienstleister.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_SHIPPING_TITLE', 'Versanddaten des Konsumenten mitsenden');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_SHIPPING_DESC', 'Weiterleitung der Versanddaten des Kunden an den Finanzdienstleister.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BILLING_TITLE', 'Verrechnungsdaten des Konsumenten mitsenden');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SEND_BILLING_DESC', 'Weiterleitung der Verrechnungsdaten des Kunden an den Finanzdienstleister.');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SELECT_TITLE','Zahlungsoption SELECT');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SELECT_DESC','Die Zahlungsmittelauswahl erfolgt auf der Wirecard Checkout Page. Wenn aktiviert, werden keine weiteren Zahlungsmodule der Wirecard Checkout Page im Shop angezeigt.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TEXT_TITLE','Zahlungsoptionstext');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TEXT_DESC','Geben Sien den Text an, der als Beschreibung f&uuml;r die Zahlungsoption SELECT dargestellt werden soll (zB MasterCard, Visa, ...).');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD_TITLE','Kreditkarte');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD_DESC','Zahlungsoption Kreditkarte aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MASTERPASS_TITLE','Masterpass');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MASTERPASS_DESC','Zahlungsoption Masterpass aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MAESTRO_TITLE','Maestro SecureCode');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MAESTRO_DESC','Zahlungsoption Maestro SecureCode aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EPS_TITLE','eps Online-&Uuml;berweisung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EPS_DESC','Zahlungsoption eps Online-&Uuml;berweisung aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PBX_TITLE','paybox');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PBX_DESC','Zahlungsoption paybox aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PSC_TITLE','paysafecard');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PSC_DESC','Zahlungsoption paysafecard aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_QUICK_TITLE','@Quick');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_QUICK_DESC','Zahlungsoption @Quick aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SEPA-DD_TITLE','SEPA Lastschrift');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SEPA-DD_DESC','Zahlungsoption SEPA Lastschrift aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PAYPAL_TITLE', 'PayPal');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PAYPAL_DESC','Zahlungsoption Paypal aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SOFORTUEBERWEISUNG_TITLE', 'SOFORT &Uuml;berweisung (PIN/TAN)');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SOFORTUEBERWEISUNG_DESC','Zahlungsoption SOFORT &Uuml;berweisung (PIN/TAN) aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_IDL_TITLE','iDEAL');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_IDL_DESC','Zahlungsoption iDEAL aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_GIROPAY_TITLE','giropay');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_GIROPAY_DESC','Zahlungsoption giropay aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INVOICE_TITLE','Kauf auf Rechnung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INVOICE_DESC','Zahlungsoption Kauf auf Rechnung aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD-MOTO_TITLE','Kreditkarte - Post / Telefonbestellung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD-MOTO_DESC','Zahlungsoption Kreditkarte - Post / Telefonbestellung aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_BANCONTACT_MISTERCASH_TITLE','Bancontact');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_BANCONTACT_MISTERCASH_DESC','Zahlungsoption Bancontact aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EKONTO_TITLE','eKonto');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EKONTO_DESC','Zahlungsoption type eKonto aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTALLMENT_TITLE','Kauf auf Raten');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTALLMENT_DESC','Zahlungsoption Kauf auf Raten aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MONETA_TITLE','moneta.ru');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MONETA_DESC','Zahlungsoption moneta.ru aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PRZELEWY24_TITLE','Przelewy24');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PRZELEWY24_DESC','Zahlungsoption Przelewy24 aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_POLI_TITLE','POLi');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_POLI_DESC','Zahlungsoption POLi aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLWALLET_TITLE','Skrill Digital Wallet');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLWALLET_DESC','Zahlungsoption Skrill Digital Wallet aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TATRAPAY_TITLE','TatraPay');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TATRAPAY_DESC','Zahlungsoption TatraPay aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TRUSTLY_TITLE','Trustly');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TRUSTLY_DESC','Zahlungsoption Trustly aktivieren?');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PENDING_TITLE', 'Ihre Zahlung wurde vom Finanzinstitut noch nicht best&auml;tigt');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PENDING_DESC', 'Die Zahlungsbest&auml;tigung ist ausst&auml;ndig, sie wird sp&auml;ter zugesendet.');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT_TITLE','Minimalbetrag Kauf auf Rechnung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MIN_AMOUNT_DESC','Geben sie den Minimalbetrag f&uuml;r Kauf auf Rechnung an.  (&euro;)');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT_TITLE','Maximalbetrag Kauf auf Rechnung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_MAX_AMOUNT_DESC','Geben sie den Maximalbetrag f&uuml;r Kauf auf Rechnung an.  (&euro;)');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT_TITLE','Minimalbetrag Kauf auf Raten');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MIN_AMOUNT_DESC','Geben sie den Minimalbetrag f&uuml;r Kauf auf Raten an.  (&euro;)');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT_TITLE','Maximalbetrag Kauf auf Raten');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INSTALLMENT_MAX_AMOUNT_DESC','Geben sie den Maximalbetrag f&uuml;r Kauf auf Raten an.  (&euro;)');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL_TITLE','URL zur Kontakt-Seite');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SERVICEURL_DESC','URL der Kontakt-Seite (Impressum) des Onlineshops.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL_TITLE','Bild URL auf der Bezahlseite');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_IMAGEURL_DESC','Url zu Ihrem Logo auf der Bezahlseite (vorzugsweise 95x65 Pixel).');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MAX_RETRIES_TITLE', 'Max. Versuche');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_MAX_RETRIES_DESC', 'Anzahl der maximalen Zahlungsversuche.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DISPLAY_TEXT_TITLE', 'Text auf der Bezahlseite');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DISPLAY_TEXT_DESC', 'Text, der dem Kunden zu den Bestelldaten angezeigt wird.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DEPOSIT_TITLE', 'Automatisches abbuchen');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_DEPOSIT_DESC', 'Automatisches Abbuchen der Zahlungen. Bitte kontaktieren Sie unsere Sales-Teams um dieses Feature freizuschalten.');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER_TITLE','Anzeigereihenfolge');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_SORT_ORDER_DESC','Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE_TITLE','Zahlungszone');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ZONE_DESC','Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID_TITLE','Bestellstatus festlegen');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_ID_DESC','Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID_TITLE','Bestellstatus für ausstehende Zahlungen festlegen');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ORDER_STATUS_PENDING_ID_DESC','Bestellungen, welche mit diesem Modul gemacht werden und im Bezahlstatus pending sind, auf diesen Status setzen.');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYMENT_TITLE', 'Bezahlvorgang');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_REDIRECTTEXT', 'Sie werden in k&uuml;rze Weitergeleitet. Wenn nicht dr&uuml;cken sie bitte auf den Button mit der Aufschrift "Weiter"');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_TEXT_DISPLAYTEXT','Herzlichen Dank fuer Ihre Bestellung.');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD_TEXT','Kreditkarte');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MASTERPASS_TEXT','Masterpass');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MAESTRO_TEXT','Maestro SecureCode');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EPS_TEXT','eps Online-&Uuml;berweisung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PBX_TEXT','paybox');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PSC_TEXT','paysafecard');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_QUICK_TEXT','@Quick');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SEPA-DD_TEXT','SEPA Lastschrift');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_IDL_TEXT','iDEAL');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PAYPAL_TEXT','PayPal');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_GIROPAY_TEXT','giropay');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SOFORTUEBERWEISUNG_TEXT','SOFORT &Uuml;berweisung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INVOICE_TEXT','Kauf auf Rechnung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_CCARD-MOTO_TEXT','Kreditkarte Moto');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_BANCONTACT_MISTERCASH_TEXT','Bancontact');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_EKONTO_TEXT','eKonto');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_INSTALLMENT_TEXT','Kauf auf Raten');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_TRUSTLY_TEXT','Trustly');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_MONETA_TEXT','moneta.ru');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_PRZELEWY24_TEXT','Przelewy24');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_POLI_TEXT','POLi');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYSYS_SKRILLWALLET_TEXT','Skrill Digital Wallet');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ERROR_TITEL', 'Zahlungsfehler');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ERROR_NOTRID', 'Keine Transaktions-Id vorhanden');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_CANCEL_TEXT', 'Sie haben die Zahlung abgebrochen.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PENDING_TEXT', 'Die Zahlungsfreigabe erfolgt zu einem späteren Zeitpunkt.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_ERROR_TEXT', 'Ihre Zahlung war leider ung&uuml;ltig!');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_FINGERPRINT_TEXT', 'Die Daten&uuml;berpr&uuml;fung ist leider fehlgeschlagen.');

define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_INVOICE_BIRTHDAY_TEXT', 'Geburtsdatum');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_CONSENT1', 'Mit der Übermittlung jener Daten an payolution, die für die Abwicklung von Zahlungen mit Kauf auf Rechnung und die Identitäts- und Bonitätsprüfung erforderlich sind, bin ich einverstanden. Meine ');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_LINK', 'Einwilligung');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_CONSENT2', ' kann ich jederzeit mit Wirkung für die Zukunft widerrufen.');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_PAYOLUTION_TERMS', 'payolution Nutzungsbedingungen');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_FINANCIAL_INSTITUTION', 'Finanzinstitut');
define('MODULE_PAYMENT_WIRECARD_CHECKOUT_PAGE_BIRTHDAY_ERROR', 'Sie müssen mindestens 18 Jahre alt sein um dieses Zahlungsmittel zu nutzen.');

?>