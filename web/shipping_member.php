<?php

require "config/configuration.php";
include("config/shipping/action_raja_ongkir.php");
include("config/shipping/province_city.php");
session_start();
ob_start();

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Set nominal curs from USD to IDR
$USDtoIDR = get_price('currency-value-usd-to-idr');

$loggedin = logged_in();

// Action select province
if (isset($_GET["select_country"]) && $_GET["select_country"] == "select_country") {

    // Set parameter request
    $id_country = isset($_GET['id_country']) ? strip_tags(trim($_GET['id_country'])) : "";

    // Set default results
    $result_province = array();

    if ($id_country == 'ID') {

        // Get province
        $get_province = get_province();

        // Set results
        foreach ($get_province->rajaongkir->results as $row_province) {
            $result_province[] = $row_province;
        }

    }

    if (count($result_province) > 0) {
        $return_province['failed'] = false;
        $return_province['results'] = $result_province;
    } else {
        $return_province['failed'] = true;
    }

    echo json_encode($return_province);
}

// Action select city
if (isset($_GET["select_province"]) && $_GET["select_province"] == "select_province") {

    // Set parameter request
    $id_province = isset($_GET['id_province']) ? strip_tags(trim($_GET['id_province'])) : "";

    // Set parameter
    $parameter = [
        'province' => $id_province
    ];

    // Get city
    $get_city = get_city($parameter);

    // Set result city
    $result_city = array();
    foreach ($get_city->rajaongkir->results as $row_city) {
        $result_city[] = $row_city;
    }

    if (count($result_city) > 0) {
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

if (isset($_POST['action']) && $_POST['action'] == 'create_transaction') {

    // Set value request
    $address = isset($_POST['address']) ? strip_tags(trim($_POST['address'])) : "";
    $id_country = isset($_POST['country_code']) ? strip_tags(trim($_POST['country_code'])) : "";
    $id_province = isset($_POST['id_province']) ? strip_tags(trim($_POST['id_province'])) : "";
    $id_city = isset($_POST['id_city']) ? strip_tags(trim($_POST['id_city'])) : "";
    $postal_code = isset($_POST['postal_code']) ? strip_tags(trim($_POST['postal_code'])) : "";
    $amount = isset($_POST['amount']) ? strip_tags(trim($_POST['amount'])) : "";
    $weight = isset($_POST['weight']) ? strip_tags(trim($_POST['weight'])) : "";
    $courier = isset($_POST['courier']) ? strip_tags(trim($_POST['courier'])) : "";
    $service = isset($_POST['service']) ? strip_tags(trim($_POST['service'])) : "";
    $price_shipping = isset($_POST['price_shipping']) ? strip_tags(trim($_POST['price_shipping'])) : "";
    $shipping_IDR = isset($_POST['shipping_IDR']) ? strip_tags(trim($_POST['shipping_IDR'])) : "";

    // Set transaction number
    $transaction_number = generate_transaction_number();

    // if required
    if (!empty($transaction_number) && !empty($address) && !empty($id_country)
        && !empty($id_province) && !empty($id_city) && !empty($postal_code) && !empty($amount)
        && !empty($courier) && !empty($service) && !empty($price_shipping) && !empty($shipping_IDR)
    ) {

        // Begin transaction
        begin_transaction();

        // Round amount
        $amount = round($amount, 2);

        // Insert transaction
        $insert_transaction_query = "INSERT INTO `transaction` (`kode_transaction`, `id_member`, `status`, `konfirm`, `payment_method`, `total`, `date_add`, `date_upd`)
                VALUES('$transaction_number', '" . $loggedin["id_member"] . "', 'process', 'not confirmated', 'Bank Transfer', '$amount', NOW(), NOW());";
        if (!mysql_query($insert_transaction_query)) {
            roll_back();
            $msg = 'Unable to save transaction';
            echo json_encode(error_response($msg));
            exit();
        }

        // Select transaction
        $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `kode_transaction` = '$transaction_number' AND `id_member` = '" . $loggedin["id_member"] . "'
                AND `status` = 'process' AND `konfirm` = 'not confirmated' AND `payment_method` = 'Bank Transfer' ORDER BY `id_transaction` DESC LIMIT 0,1;");
        $row_transaction = mysql_fetch_assoc($transaction_query);

        // Assigned transaction to cart
        $assigned_transaction_cart_query = "UPDATE `cart` SET `id_transaction` = '" . $row_transaction["id_transaction"] . "'
                WHERE ISNULL(id_transaction) AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0';";
        if (!mysql_query($assigned_transaction_cart_query)) {
            roll_back();
            $msg = 'Unable to assigned transaction in cart';
            echo json_encode(error_response($msg));
            exit();
        }

        // Insert shipping
        $insert_shipping_query = "INSERT INTO `transaction_shipping` (`id_transaction`, `courier`, `service`, `weight`, `price`, `amount`, `date_add`, `date_upd`)
                VALUES('" . $row_transaction["id_transaction"] . "', '$courier', '$service', '$weight', '$price_shipping', '$shipping_IDR', NOW(), NOW());";
        if (!mysql_query($insert_shipping_query)) {
            roll_back();
            $msg = 'Unable to save shipping';
            echo json_encode(error_response($msg));
            exit();
        }

        // Insert shipping address
        $insert_shipping_address_query = "INSERT INTO `transaction_shipping_address` (`id_transaction`, `id_city`, `id_province`, `id_country`, `address`, `zip_code`, `date_add`, `date_upd`)
            VALUES ('" . $row_transaction["id_transaction"] . "', '$id_city', '$id_province', '$id_country', '$address', '$postal_code', NOW(), NOW());";
        if (!mysql_query($insert_shipping_address_query)) {
            roll_back();
            $msg = 'Unable to save shipping address';
            echo json_encode(error_response($msg));
            exit();
        }

        // Commit
        commit();

        // Success
        $msg = 'Save payment successfully';
        echo json_encode(success_response($msg, $row_transaction));
        exit();

    } else {
        $msg = 'All parameter rquired';
        echo json_encode(error_response($msg));
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'save_payment') {

    // Set value request
    $paymentId = isset($_POST['paymentId']) ? strip_tags(trim($_POST['paymentId'])) : '';
//    $transactionId = isset($_POST['id_transaction']) ? strip_tags(trim($_POST['id_transaction'])) : '';
    $state = isset($_POST['state']) ? strip_tags(trim($_POST['state'])) : '';
    $amount = isset($_POST['amount']['total']) ? $_POST['amount']['total'] : '';
    $shipping = isset($_POST['shipping']) ? mysql_real_escape_string(trim($_POST['shipping'])) : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $weight = isset($_POST['weight']) ? mysql_real_escape_string(trim($_POST['weight'])) : '';
    $courier = isset($_POST['courier']) ? mysql_real_escape_string(trim($_POST['courier'])) : '';
    $service = isset($_POST['service']) ? mysql_real_escape_string(trim($_POST['service'])) : '';
    $price_shipping = isset($_POST['price_shipping']) ? mysql_real_escape_string(trim($_POST['price_shipping'])) : '';
    $shipping_address = isset($_POST['shipping_address']) ? mysql_real_escape_string(trim($_POST['shipping_address'])) : '';
    $shipping_country_code = isset($_POST['shipping_country_code']) ? mysql_real_escape_string(trim($_POST['shipping_country_code'])) : '';
    $shipping_province = isset($_POST['shipping_province']) ? mysql_real_escape_string(trim($_POST['shipping_province'])) : '';
    $shipping_city = isset($_POST['shipping_city']) ? mysql_real_escape_string(trim($_POST['shipping_city'])) : '';
    $shipping_postal_code = isset($_POST['shipping_postal_code']) ? mysql_real_escape_string(trim($_POST['shipping_postal_code'])) : '';

    $return_update_payment['failed'] = false;

    // Begin transaction
    begin_transaction();

    // Set transaction number
    $transaction_number = generate_transaction_number();

    // Insert transaction
    $insert_transaction_query = "INSERT INTO `transaction` (`kode_transaction`, `id_member`, `status`, `konfirm`, `payment_method`, `total`, `confirmed_at`, `confirmed_by`, `date_add`, `date_upd`) 
        VALUES('$transaction_number', '" . $loggedin["id_member"] . "', 'process', 'Confirmated', 'Paypal', '$amount', NOW(), 'When payment with paypal', NOW(), NOW());";
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
            $insert_shipping_query = "INSERT INTO `transaction_shipping` (`id_transaction`, `courier`, `service`, `weight`, `price`, `amount`, `date_add`, `date_upd`)
                VALUES('" . $row_transaction["id_transaction"] . "', '$courier', '$service', '$weight', '$price_shipping', '$shipping', NOW(), NOW());";
            if (!mysql_query($insert_shipping_query)) {
                roll_back();
                $msg = 'Unable to save shipping';
                echo json_encode(error_response($msg));
                exit();
            }

            // Insert shipping address
            $insert_shipping_address_query = "INSERT INTO `transaction_shipping_address` (`id_transaction`, `id_city`, `id_province`, `id_country`, `address`, `zip_code`, `date_add`, `date_upd`)
            VALUES ('" . $row_transaction["id_transaction"] . "', '$shipping_city', '$shipping_province', '$shipping_country_code', '$shipping_address', '$shipping_postal_code', NOW(), NOW());";
            if (!mysql_query($insert_shipping_address_query)) {
                roll_back();
                $msg = 'Unable to save shipping address';
                echo json_encode(error_response($msg));
                exit();
            }

            // SEt shipping usd
            $shipping_USD = round(($shipping / $USDtoIDR), 2);

            // Insert paypal item shipping
            $insert_paypal_item_shipping_query = "INSERT INTO `paypal_items` (`id_paypal`, `id_item`, `price`, `quantity`, `date_add`, `date_upd`, `level`)
                    VALUES ('" . $row_paypal["id_paypal"] . "', '', '$shipping_USD', '', NOW(), NOW(), '0');";
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
    echo json_encode(success_response($msg, $row_transaction));
    exit();
}
