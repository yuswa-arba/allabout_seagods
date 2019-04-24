<?php

require '../config/configuration.php';
require '../config/configuration_paypal.php';

use PayPal\Api\Payment;

global $api;

// Get paypal query
$query_paypal = mysql_query("SELECT * FROM `paypals`
    WHERE `status` != 'declined'
    AND `status` != 'failed'
    AND `status` != 'reversed'
    AND `status` != 'voided'
    AND `status` != 'completed'
    AND `level` = '0'");

while ($row_paypal = mysql_fetch_array($query_paypal)) {

    try {

        // Get detail payment in paypal
        $payment = Payment::get($row_paypal['paymentId'], $api);

        // If true
        if ($payment) {

            // Set status
            $status = $payment->transactions[0]->related_resources[0]->sale->state;

            // Update status in paypal
            mysql_query("UPDATE `paypals` SET `status` = '$status', `date_upd` = NOW() WHERE `id_paypal` = '" . $row_paypal["id_paypal"] . "';");

            // Update status in transaction
            mysql_query("UPDATE `transaction` SET `status` = '$status', `date_upd` = NOW() WHERE `id_transaction` = '" . $row_paypal["id_transaction"] . "';");
        }

    } catch (\Exception $exception) {
        continue;
    }

}
