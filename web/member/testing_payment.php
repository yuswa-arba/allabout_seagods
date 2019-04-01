<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("config/configuration_paypal.php");
session_start();
ob_start();

if ($loggedin = logged_in()) { ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Document</title>
    </head>
    <body>
    <?php if ($loggedin['group'] == 'member'): ?>
        <p>You are a member <a href="testing_payment_process.php">Payment</a></p>
    <?php else: ?>
        <p>You are not a member</p>
    <?php endif; ?>
    <?php ?>
    </body>
    </html>

<?php } else {
    header('location:../login.php');
}
?>