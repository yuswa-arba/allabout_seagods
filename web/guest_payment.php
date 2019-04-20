<?php

require "config/configuration.php";
include("config/currency_types.php");
session_start();
ob_start();

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Set default payment paypal
$payment_paypal = isset($_POST['payment_paypal']) ? $_POST['payment_paypal'] : false;

if (isset($_POST['payment_paypal']) && $payment_paypal) {

    // Set value request
    $transaction_number = isset($_POST['transaction_number']) ? mysql_real_escape_string(trim($_POST['transaction_number'])) : '';
    $first_name = isset($_POST['first_name']) ? mysql_real_escape_string(trim($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? mysql_real_escape_string(trim($_POST['last_name'])) : '';
    $phone_no = isset($_POST['phone_no']) ? mysql_real_escape_string(trim($_POST['phone_no'])) : '';
    $zip_code = isset($_POST['zip_code']) ? mysql_real_escape_string(trim($_POST['zip_code'])) : '';
    $province = isset($_POST['province']) ? mysql_real_escape_string(trim($_POST['province'])) : '';
    $city = isset($_POST['city']) ? mysql_real_escape_string(trim($_POST['city'])) : '';
    $address = isset($_POST['address']) ? mysql_real_escape_string(trim($_POST['address'])) : '';
    $email = isset($_POST['email']) ? mysql_real_escape_string(trim($_POST['email'])) : '';
    $weight = isset($_POST['weight']) ? mysql_real_escape_string(trim($_POST['weight'])) : '';
    $courier = isset($_POST['courier']) ? mysql_real_escape_string(trim($_POST['courier'])) : '';
    $service = isset($_POST['service']) ? mysql_real_escape_string(trim($_POST['service'])) : '';
    $price_shipping = isset($_POST['price_shipping']) ? mysql_real_escape_string(trim($_POST['price_shipping'])) : '';
    $state = isset($_POST['state']) ? mysql_real_escape_string(trim($_POST['state'])) : '';
    $total_paypal = isset($_POST['total_paypal']) ? mysql_real_escape_string(trim($_POST['total_paypal'])) : '';
    $shipping = isset($_POST['shipping']) ? mysql_real_escape_string(trim($_POST['shipping'])) : '';
    $shipping_USD = isset($_POST['shipping_USD']) ? mysql_real_escape_string(trim($_POST['shipping_USD'])) : '';
    $description = isset($_POST['description']) ? mysql_real_escape_string(trim($_POST['description'])) : '';
    $paymentId = isset($_POST['paymentId']) ? mysql_real_escape_string(trim($_POST['paymentId'])) : '';

    if (!empty($transaction_number) && !empty($first_name) && !empty($last_name) && !empty($phone_no) && !empty($zip_code) && !empty($province) && !empty($city)
        && !empty($address) && !empty($email) && !empty($state) && !empty($total_paypal) && !empty($shipping) && !empty($description) && !empty($paymentId)
    ) {

        // Save session guest
        $_SESSION['guest']['first_name'] = $first_name;
        $_SESSION['guest']['last_name'] = $last_name;
        $_SESSION['guest']['address'] = $address;
        $_SESSION['guest']['id_province'] = $province;
        $_SESSION['guest']['id_city'] = $city;
        $_SESSION['guest']['zip_code'] = $zip_code;
        $_SESSION['guest']['email'] = $email;
        $_SESSION['guest']['phone_no'] = $phone_no;

        // Insert guest
        $insert_guest_query = "INSERT INTO `guest` (`first_name`, `last_name`, `address`, `id_country`, `id_province`, `id_city`, `zip_code`, `email`, `phone_no`, `date_add`, `date_upd`, `level`)
            VALUES('$first_name', '$last_name', '$address', 'ID', '$province', '$city', '$zip_code', '$email', '$phone_no', NOW(), NOW(), '0');";

        // Error
        if (!mysql_query($insert_guest_query)) {
            roll_back();
            $msg = 'Unable to save guest';
            echo json_encode(error_response($msg));
            exit();
        }

        // Get guest
        $guest_query = mysql_query("SELECT * FROM `guest` WHERE `email` = '$email' AND `phone_no` = '$phone_no' ORDER BY `id` DESC LIMIT 0,1;");
        $row_guest = mysql_fetch_array($guest_query);

        // Insert to transaction
        $insert_transaction_query = "INSERT INTO `transaction` (`kode_transaction`, `is_guest`, `id_guest`, `status`, `konfirm`, `payment_method`, `total`, `confirmed_at`, `confirmed_by`, `date_add`, `date_upd`)
            VALUES('$transaction_number', '1', '" . $row_guest["id"] . "', '$state', 'Confirmated', 'Paypal', '$total_paypal', NOW(), 'When payment with paypal', NOW(), NOW())";

        // Error
        if (!mysql_query($insert_transaction_query)) {
            roll_back();
            $msg = 'Unable to save transaction';
            echo json_encode(error_response($msg));
            exit();
        }

        // Select transaction
        $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `kode_transaction` = '$transaction_number' AND ISNULL(id_member)
            AND `status` = '$state' AND `konfirm` = 'Confirmated' AND `payment_method` = 'Paypal' ORDER BY `id_transaction` DESC LIMIT 0,1;");
        $row_transaction = mysql_fetch_array($transaction_query);

        // Insert paypals
        $insert_paypal_query = "INSERT INTO `paypals` (`paymentId`, `id_transaction`, `is_guest`, `id_guest`, `status`, `amount`, `description`, `date_add`, `date_upd`, `level`)
            VALUES ('" . $paymentId . "', '" . $row_transaction["id_transaction"] . "', '1', '" . $row_guest["id"] . "', '" . $state . "', '" . $total_paypal . "', '" . $description . "', NOW(), NOW(), '0');";

        // Error
        if (!mysql_query($insert_paypal_query)) {
            roll_back();
            $msg = 'Unable to save paypal';
            echo json_encode(error_response($msg));
            exit();
        }

        // Select paypal
        $paypal_query = mysql_query("SELECT * FROM `paypals` 
                WHERE `paymentId` = '" . $paymentId . "' AND `id_transaction` = '" . $row_transaction["id_transaction"] . "' AND `level` = '0' LIMIT 0,1;");
        $row_paypal = mysql_fetch_array($paypal_query);

        // Insert to cart
        foreach ($_SESSION['cart_item'] as $key => $cart_item) {

            // check custom cart
            if ($cart_item['is_custom_cart'] || $cart_item['is_custom_cart'] == true) {

                // Set cart collection value
                $cart_collection = $cart_item['collection'];

                // Insert collection
                $insert_collection_query = "INSERT INTO `custom_collection` (`code`, `is_guest`, `id_guest`, `gender`, `wet_suit_type`, `arm_zipper`, `ankle_zipper`, `image`, `price`, `status`, `date_add`, `date_upd`, `level`)
                    VALUES('" . $cart_collection["code"] . "', '1', '" . $row_guest["id"] . "', '" . $cart_collection["gender"] . "', '" . $cart_collection["wet_suit_type"] . "', '" . $cart_collection["arm_zipper"] . "', '" . $cart_collection["ankle_zipper"] . "', '" . $cart_collection["image"] . "', '" . $cart_collection["price"] . "', '" . $cart_collection["status"] . "', NOW(), NOW(), '0');";

                // Error
                if (!mysql_query($insert_collection_query)) {
                    roll_back();
                    $msg = 'Unable to save custom collection';
                    echo json_encode(error_response($msg));
                    exit();
                }

                // Get custom collection
                $custom_collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `code` = '" . $cart_collection["code"] . "' AND ISNULL(id_member) AND `level` = '0' ORDER BY `id_custom_collection` DESC LIMIT 0,1;");
                $row_custom_collection = mysql_fetch_array($custom_collection_query);

                // Set collection measure
                $collection_measure = $cart_item['measure'];

                // Insert custom measure
                $insert_measure_query = "INSERT INTO `custom_measure` (`id_custom_collection`, `total_body_height`, `head`, `neck`, `bust_chest`, `waist`, `stomach`, `abdomen`, `hip`, `shoulder`, `shoulder_elbow`, `shoulder_wrist`, `arm_hole`, `upper_arm`,
                    `bicep`, `elbow`, `forarm`, `wrist`, `outside_leg_length`, `inside_leg_length`, `upper_thigh`, `thigh`, `above_knee`, `knee`, `below_knee`, `calf`, `below_calf`,
                    `above_ankle`, `shoulder_burst`, `shoulder_waist`, `shoulder_hip`, `hip_knee_length`, `knee_ankle_length`, `back_shoulder`, `dorsum`, `crotch_point`)
                    VALUES('" . $row_custom_collection["id_custom_collection"] . "', '" . $collection_measure["total_body_height"] . "', '" . $collection_measure["head"] . "', '" . $collection_measure["neck"] . "', '" . $collection_measure["bust_chest"] . "', '" . $collection_measure["waist"] . "', '" . $collection_measure["stomach"] . "', '" . $collection_measure["abdomen"] . "', '" . $collection_measure["hip"] . "', '" . $collection_measure["shoulder"] . "', '" . $collection_measure["shoulder_elbow"] . "', '" . $collection_measure["shoulder_wrist"] . "', '" . $collection_measure["arm_hole"] . "', '" . $collection_measure["upper_arm"] . "',
                    '" . $collection_measure["bicep"] . "', '" . $collection_measure["elbow"] . "', '" . $collection_measure["forarm"] . "', '" . $collection_measure["wrist"] . "', '" . $collection_measure["outside_leg_length"] . "', '" . $collection_measure["inside_leg_length"] . "', '" . $collection_measure["upper_thigh"] . "', '" . $collection_measure["thigh"] . "', '" . $collection_measure["above_knee"] . "', '" . $collection_measure["knee"] . "', '" . $collection_measure["below_knee"] . "', '" . $collection_measure["calf"] . "', '" . $collection_measure["below_calf"] . "',
                    '" . $collection_measure["above_ankle"] . "', '" . $collection_measure["shoulder_burst"] . "', '" . $collection_measure["shoulder_waist"] . "', '" . $collection_measure["shoulder_hip"] . "', '" . $collection_measure["hip_knee_length"] . "', '" . $collection_measure["knee_ankle_length"] . "', '" . $collection_measure["back_shoulder"] . "', '" . $collection_measure["dorsum"] . "', '" . $collection_measure["crotch_point"] . "');";

                // Error
                if (!mysql_query($insert_measure_query)) {
                    roll_back();
                    $msg = 'Unable to save custom measure';
                    echo json_encode(error_response($msg));
                    exit();
                }

                // Insert cart
                $insert_cart_query = "INSERT INTO `cart` (`id_transaction`, `is_guest`, `id_guest`, `id_item`, `is_custom_cart`, `qty`, `amount`, `date_add`, `date_upd`, `level`)
                    VALUES('" . $row_transaction["id_transaction"] . "', '1', '" . $row_guest["id"] . "', '" . $row_custom_collection["id_custom_collection"] . "', '1', '" . $cart_item["quantity"] . "', '" . $cart_item["amount"] . "', NOW(), NOW(), '0');";

                // Error
                if (!mysql_query($insert_cart_query)) {
                    roll_back();
                    $msg = 'Unable to save custom cart';
                    echo json_encode(error_response($msg));
                    exit();
                }

            } else {

                // Select Item
                $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $cart_item["id_item"] . "' LIMIT 0,1;");
                $row_item = mysql_fetch_array($item_query);

                // Add cart
                $insert_cart_query = "INSERT INTO `cart` (`id_transaction`, `is_guest`, `id_guest`, `id_item`, `qty`, `amount`, `date_add`, `date_upd`, `level`)
                    VALUES('" . $row_transaction["id_transaction"] . "', '1', '" . $row_guest["id"] . "', '" . $cart_item["id_item"] . "', '" . $cart_item["quantity"] . "', '" . $cart_item["amount"] . "', NOW(), NOW(), '0');";

                // Error
                if (!mysql_query($insert_cart_query)) {
                    roll_back();
                    $msg = 'Unable to save cart';
                    echo json_encode(error_response($msg));
                    exit();
                }

            }

            // Set price
            $price = (!$cart_item['is_custom_cart'] ? $row_item['price'] : get_price('price-custom-item'));

            // Set id item
            $id_item = (!$cart_item['is_custom_cart'] ? $row_item['id_item'] : $row_custom_collection['id_custom_collection']);

            // Insert paypal item
            $insert_paypal_item_query = "INSERT INTO `paypal_items` (`id_paypal`, `id_item`, `is_custom_cart`, `price`, `quantity`, `date_add`, `date_upd`, `level`)
                    VALUES ('" . $row_paypal["id_paypal"] . "', '$id_item', '" . $cart_item['is_custom_cart'] . "', '$price', '" . $cart_item["quantity"] . "', NOW(), NOW(), '0');";
            if (!mysql_query($insert_paypal_item_query)) {
                roll_back();
                $msg = 'Unable to save paypal items';
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

        // Remove session
        unset($_SESSION['cart_item']);

        // Commit
        commit();

        // Success
        $msg = 'Payment process successfully';
        echo json_encode(success_response($msg));
        exit();

    } else {
        $msg = 'All Request parameter required';
        echo json_encode(error_response($msg));
        exit();
    }

}
