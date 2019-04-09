<?php

require "config/configuration.php";
session_start();
ob_start();

$loggedin = logged_in();

if (isset($_GET["select_country"]) && $_GET["select_country"] == "select_country") {
    $id_country = isset($_GET['id_country']) ? strip_tags(trim($_GET['id_country'])) : "";

    $query_province = mysql_query("SELECT * FROM `provinsi` WHERE `idCountry` = '$id_country'");

    $result_province = array();
    while ($row_province = mysql_fetch_array($query_province)) {
        $result_province[] = $row_province;
    }

    $count_province = mysql_num_rows($query_province);
    if ($count_province > 0) {
        $return_province['failed'] = false;
        $return_province['results'] = $result_province;
    } else {
        $return_province['failed'] = true;
    }

    echo json_encode($return_province);
}

if (isset($_GET["select_province"]) && $_GET["select_province"] == "select_province") {
    $id_province = isset($_GET['id_province']) ? strip_tags(trim($_GET['id_province'])) : "";

    $query_city = mysql_query("SELECT * FROM `kota` WHERE `idProvinsi` = '$id_province';");

    $result_city = array();
    while ($row_city = mysql_fetch_array($query_city)) {
        $result_city[] = $row_city;
    }

    $count_city = mysql_num_rows($query_city);
    if ($count_city > 0) {
        $return_city['failed'] = false;
        $return_city['results'] = $result_city;
    } else {
        $return_city['failed'] = true;
    }

    echo json_encode($return_city);
}

if (isset($_POST['action']) && $_POST['action'] == 'update_member') {
    $id_member = isset($_POST['id_member']) ? strip_tags(trim($_POST['id_member'])) : "";
    $first_name = isset($_POST['first_name']) ? strip_tags(trim($_POST['first_name'])) : "";
    $last_name = isset($_POST['last_name']) ? strip_tags(trim($_POST['last_name'])) : "";
    $email = isset($_POST['email']) ? strip_tags(trim($_POST['email'])) : "";
    $phone_number = isset($_POST['phone_number']) ? strip_tags(trim($_POST['phone_number'])) : "";
    $address = isset($_POST['address']) ? strip_tags(trim($_POST['address'])) : "";
    $country_code = isset($_POST['country_code']) ? strip_tags(trim($_POST['country_code'])) : "";
    $id_province = isset($_POST['id_province']) ? strip_tags(trim($_POST['id_province'])) : "";
    $id_city = isset($_POST['id_city']) ? strip_tags(trim($_POST['id_city'])) : "";
    $postal_code = isset($_POST['postal_code']) ? strip_tags(trim($_POST['postal_code'])) : "";

    $member_update = mysql_query("UPDATE `member` SET `firstname` = '" . $first_name . "', `lastname` = '" . $last_name . "', `notelp` = '" . $phone_number . "',
        `alamat` = '" . $address . "', `idCountry` = '" . $country_code . "', `idpropinsi` = '" . $id_province . "', `idkota` = '" . $id_city . "',
        `kode_pos` = '" . $postal_code . "' WHERE `id_member` = '" . $id_member . "';");

    if ($member_update) {
        $return_member['failed'] = false;
        $return_member['result'] = $member_update;
    } else {
        $return_member['failed'] = true;
        $return_member['result'] = $member_update;
    }

    echo json_encode($return_member);
}

if (isset($_POST['action']) && $_POST['action'] == 'save_payment') {

    // Set value request
    $paymentId = isset($_POST['paymentId']) ? strip_tags(trim($_POST['paymentId'])) : '';
//    $transactionId = isset($_POST['id_transaction']) ? strip_tags(trim($_POST['id_transaction'])) : '';
    $state = isset($_POST['state']) ? strip_tags(trim($_POST['state'])) : '';
    $amount = isset($_POST['amount']['total']) ? $_POST['amount']['total'] : '';
    $shipping = isset($_POST['amount']['details']['shipping']) ? $_POST['amount']['details']['shipping'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $weight = isset($_POST['weight']) ? mysql_real_escape_string(trim($_POST['weight'])) : '';
    $price_shipping = isset($_POST['price_shipping']) ? mysql_real_escape_string(trim($_POST['price_shipping'])) : '';

    $return_update_payment['failed'] = false;

    // Begin transaction
    begin_transaction();

    // Set transaction number
    $transaction_number = generate_transaction_number();

    // Insert transaction
    $insert_transaction_query = "INSERT INTO `transaction` (`kode_transaction`, `id_member`, `status`, `konfirm`, `payment_method`, `total`, `date_add`, `date_upd`) 
        VALUES('$transaction_number', '" . $loggedin["id_member"] . "', 'process', 'Confirmated', 'Paypal', '$amount', NOW(), NOW());";
    if (!mysql_query($insert_transaction_query)) {
        roll_back();
        $msg = 'Unable to save transaction';
        echo json_encode(error_response($msg));
        exit();
    }

    // Select transaction
    $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `kode_transaction` = '$transaction_number' AND `id_member` = '" . $loggedin["id_member"] . "' 
        AND `status` = 'process' AND `konfirm` = 'Confirmated' ORDER BY `id_transaction` DESC LIMIT 0,1;");
    $row_transaction = mysql_fetch_array($transaction_query);

    if ($paymentId != '' && $state != '' && $amount != '') {

        // Update transaction
        $query_transaction_update = "UPDATE `transaction` SET `status` = '$state' WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "';";
        if (!mysql_query($query_transaction_update)) {
            roll_back();
            $msg = 'Unable to update status transaction';
            echo json_encode(error_response($msg));
            exit();
        }

        // Insert paypals
        $insert_paypal_query = "INSERT INTO `paypals` (`paymentId`, `id_transaction`, `id_member`, `status`, `amount`, `description`, `date_add`, `date_upd`, `level`)
                VALUES ('" . $paymentId . "', '" . $row_transaction["id_transaction"] . "', '" . $row_transaction["id_member"] . "', '" . $state . "', '" . $amount . "', '" . $description . "', NOW(), NOW(), '0');";
        if (!mysql_query($insert_paypal_query)) {
            roll_back();
            $msg = 'Unable to save paypal';
            echo json_encode(error_response($msg));
            exit();
        }

        // Select paypal
        $paypal_query = mysql_query("SELECT * FROM `paypals` 
                WHERE `paymentId` = '" . $paymentId . "' AND `id_transaction` = '" . $row_transaction["id_transaction"] . "' AND `level` = '0' LIMIT 0,1;");
        if (mysql_num_rows($paypal_query) == 0) {
            roll_back();
            $msg = 'Paypal not found';
            echo json_encode(error_response($msg));
            exit();
        }
        $row_paypal = mysql_fetch_array($paypal_query);

        // Assigned transaction to cart
        $assigned_transaction_cart_query = "UPDATE `cart` SET `id_transaction` = '" . $row_transaction["id_transaction"] . "'
            WHERE ISNULL(id_transaction) AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0';";
        if (!mysql_query($assigned_transaction_cart_query)) {
            roll_back();
            $msg = 'Unable to assigned transaction in cart';
            echo json_encode(error_response($msg));
            exit();
        }

        // Get cart
        $cart_query = mysql_query("SELECT * FROM `cart` WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "' AND `level` = '0';");
        if (mysql_num_rows($cart_query) == 0) {
            roll_back();
            $msg = 'Cart not found';
            echo json_encode(error_response($msg));
            exit();
        }
        while ($row_cart = mysql_fetch_array($cart_query)) {

            if ($row_cart['is_custom_cart']) {

                // Set item collection
                $collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '" . $row_cart["id_item"] . "';");
                if (mysql_num_rows($collection_query) == 0) {
                    roll_back();
                    $msg = 'Item not found';
                    echo json_encode(error_response($msg));
                    exit();
                }
                $row_item = mysql_fetch_array($collection_query);

            } else {

                // Set item
                $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $row_cart["id_item"] . "';");
                if (mysql_num_rows($item_query) == 0) {
                    roll_back();
                    $msg = 'Item not found';
                    echo json_encode(error_response($msg));
                    exit();
                }
                $row_item = mysql_fetch_array($item_query);

            }
            // Insert paypal item
            $insert_paypal_item_query = "INSERT INTO `paypal_items` (`id_paypal`, `id_item`, `price`, `quantity`, `date_add`, `date_upd`, `level`)
                    VALUES ('" . $row_paypal["id_paypal"] . "', '" . $row_cart["id_item"] . "', '" . $row_item["price"] . "', '" . $row_cart["qty"] . "', NOW(), NOW(), '0');";
            if (!mysql_query($insert_paypal_item_query)) {
                roll_back();
                $msg = 'Unable to save paypal item';
                echo json_encode(error_response($msg));
                exit();
            }
        }

        // Save shipping
        if ($shipping != '') {

            // Insert shipping
            $insert_shipping_query = "INSERT INTO `transaction_shipping` (`id_transaction`, `weight`, `price`, `amount`, `date_add`, `date_upd`)
                VALUES('" . $row_transaction["id_transaction"] . "', '$weight', '$price_shipping', '$shipping', NOW(), NOW());";
            if (!mysql_query($insert_shipping_query)) {
                roll_back();
                $msg = 'Unable to save shipping';
                echo json_encode(error_response($msg));
                exit();
            }

            // Insert paypal item shipping
            $insert_paypal_item_shipping_query = "INSERT INTO `paypal_items` (`id_paypal`, `id_item`, `price`, `quantity`, `date_add`, `date_upd`, `level`)
                    VALUES ('" . $row_paypal["id_paypal"] . "', '', '" . $shipping . "', '', NOW(), NOW(), '0');";
            if (!mysql_query($insert_paypal_item_shipping_query)) {
                roll_back();
                $msg = 'Unable to save shipping in paypal item';
                echo json_encode(error_response($msg));
                exit();
            }
        }

    } else {
        roll_back();
        $msg = 'All parameter rquired';
        echo json_encode(error_response($msg));
        exit();
    }

    // commit
    commit();

    // Success
    $msg = 'Save payment successfully';
    echo json_encode(success_response($msg));
    exit();
}
