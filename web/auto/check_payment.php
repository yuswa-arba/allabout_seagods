<?php

require '../config/configuration.php';
require '../config/configuration_paypal.php';

use PayPal\Api\Payment;

$query_paypal = mysql_query("SELECT * FROM `paypals` 
    WHERE `status` != 'declined'
    AND `status` != 'failed'
    AND `status` != 'reversed'
    AND `status` != 'voided'
    AND `status` != 'completed'
    AND `level` = '0'");
$row_paypal = mysql_fetch_array($query_paypal);

while ($row_paypal = mysql_fetch_array($query_paypal)) {

    $payment = Payment::get($row_paypal['paymentId'], $api);

    if ($payment) {
        $status = $payment->transactions[0]->related_resources[0]->sale->state;

        mysql_query("UPDATE `paypals` SET `status` = '$status', `date_upd` = NOW() WHERE `id_paypal` = '" . $row_paypal["id_paypal"] . "';");

        mysql_query("UPDATE `transaction` SET `status` = '$status', `date_upd` = NOW() WHERE `id_transaction` = '" . $row_paypal["id_transaction"] . "';");
    }
}
