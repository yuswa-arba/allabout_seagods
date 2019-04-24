<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
session_start();
ob_start();

$loggedin = logged_in();

if (isset($_POST['action'])) {

    // Set default result carts
    $total_carts = 0;

    // If logged in
    if ($loggedin) {

        // Get Cart
        $cart_query = mysql_query("SELECT * FROM `cart` WHERE ISNULL(id_transaction) 
            AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0';");
        while ($row_cart = mysql_fetch_assoc($cart_query)) {
            $total_carts = $total_carts + (float)$row_cart['qty'];
        }

        // Success
        $msg = "Get notification successfully";
        echo json_encode(success_response($msg, ['total' => $total_carts]));

    } else {

        if (isset($_SESSION['cart_item'])) {

            if (!empty($_SESSION['cart_item'])) {

                foreach ($_SESSION['cart_item'] as $cart_item) {
                    $total_carts = $total_carts + (float)$cart_item['quantity'];
                }

            }

        }

        // Success
        $msg = "Get notification successfully";
        echo json_encode(success_response($msg, ['total' => $total_carts]));

    }

}