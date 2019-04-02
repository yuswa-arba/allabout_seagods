<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("config/currency_types.php");
session_start();
ob_start();

// Set logged in
$loggedin = logged_in();

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Check action
if (isset($_POST['action'])) {

    // Set action
    $action = isset($_POST['action']) ? mysql_real_escape_string(trim($_POST['action'])) : '';

    // Action for add cart
    if (isset($_POST['id_item']) && $action == 'add_cart') {

        try {

            // Set value request
            $id_item = isset($_POST['id_item']) ? mysql_real_escape_string(trim($_POST['id_item'])) : '';
            $quantity = isset($_POST['quantity']) ? mysql_real_escape_string(trim($_POST['quantity'])) : 1;

            // Check item
            $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '$id_item' AND `level` = '0' LIMIT 0,1;");

            // Error
            if (mysql_num_rows($item_query) == 0) {
                $msg = 'Item not found.';
                echo json_encode(error_response($msg));
                exit();
            }

            // Set row item
            $row_item = mysql_fetch_array($item_query);

            // If is member and is loading
            if ($loggedin) {

                // Set amount new cart
                $amount = (float)$row_item['price'] * (float)$quantity;

                // Check cart
                $cart_query = mysql_query("SELECT * FROM `cart` 
                    WHERE ISNULL(id_transaction) AND `id_item` = '$id_item' AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0' LIMIT 0,1");
                if (mysql_num_rows($cart_query) == 0) {

                    // Add cart
                    $insert_cart_query = "INSERT INTO `cart` (`id_item`, `qty`, `amount`, `id_member`, `date_add`, `date_upd`, `level`)
                    VALUES('$id_item', '$quantity', '$amount', '" . $loggedin["id_member"] . "', NOW(), NOW(), '0')";

                    // Error
                    if (!mysql_query($insert_cart_query)) {
                        $msg = 'Unable to add item in cart';
                        echo json_encode(error_response($msg));
                        exit();
                    }

                } else {

                    // Set row cart
                    $row_cart = mysql_fetch_array($cart_query);

                    // Set last amount cart and quantity
                    $last_amount_cart = $amount + (float)$row_cart['amount'];
                    $last_quantity = (float)$quantity + (float)$row_cart['qty'];

                    // Update cart
                    $update_cart_query = "UPDATE `cart` SET `qty` = '$last_quantity', `amount` = '$last_amount_cart', `date_upd` = NOW()
                        WHERE `id_cart` = '" . $row_cart["id_cart"] . "';";

                    // Error
                    if (!mysql_query($update_cart_query)) {
                        $msg = 'Unable to add item in cart';
                        echo json_encode(error_response($msg));
                        exit();
                    }

                }

                // Success
                $msg = 'Success add item to cart';
                echo json_encode(success_response($msg));
                exit();

            } else {

                // Set amount
                $amount = (float)$quantity * (float)$row_item['price'];

                // Set value for session
                $item_array = array(
                    'is_custom_cart' => false,
                    'id_item' => $id_item,
                    'quantity' => $quantity,
                    'amount' => $amount
                );

                if (!empty($_SESSION['cart_item'])) {

                    // Set id_item session
                    $id_item_session = array_column($_SESSION["cart_item"], 'id_item');

                    if (in_array($id_item, $id_item_session)) {

                        foreach ($_SESSION["cart_item"] as $key => $cart_item) {

                            // If item is exists in session
                            if ($cart_item['id_item'] == $id_item) {
                                $_SESSION["cart_item"][$key]['quantity'] = (float)$cart_item["quantity"] + (float)$quantity;
                                $_SESSION["cart_item"][$key]['amount'] = (float)$cart_item['amount'] + $amount;
                            }

                        }

                    } else {

                        // Save session
                        $_SESSION['cart_item'][] = $item_array;

                    }
                } else {
                    $_SESSION["cart_item"][] = $item_array;
                }

                // Success
                $msg = 'Success add item to cart';
                echo json_encode(success_response($msg, $item_array));
                exit();

            }

        } catch (\Exception $exception) {
            $msg = 'Unable to add item in cart';
            echo json_encode(error_response($msg));
            exit();
        }

    }

    // Action for add cart from collection
    if (isset($_POST['id_collection']) && $action == 'add_cart_collection') {

        // Check login
        if ($loggedin) {

            try {

                // Set value id collection
                $id_collection = isset($_POST['id_collection']) ? mysql_real_escape_string(trim($_POST['id_collection'])) : '';

                if (!empty($id_collection)) {

                    // Set collection
                    $collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '$id_collection' AND `level` = '0' LIMIT 0,1;");

                    // Error
                    if (mysql_num_rows($collection_query) == 0) {
                        $msg = 'Custom collection not found';
                        echo json_encode(error_response($msg));
                        exit();
                    }

                    // Set custom price
                    $current_price = get_price('price-custom-item');

                    // Check cart exists or no
                    $custom_cart_query = mysql_query("SELECT * FROM `cart` 
                        WHERE ISNULL(id_transaction) AND `is_custom_cart` = '1' AND `id_item` = '$id_collection' AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0' LIMIT 0,1;");
                    if (mysql_num_rows($custom_cart_query) == 0) {

                        // Insert cart
                        $insert_cart_query = "INSERT INTO `cart` (`id_member`, `id_item`, `is_custom_cart`, `qty`, `amount`, `date_add`, `date_upd`, `level`)
                            VALUES('" . $loggedin["id_member"] . "', '$id_collection', '1', '1', '$current_price', NOW(), NOW(), '0');";

                        // Error
                        if (!mysql_query($insert_cart_query)) {
                            $msg = 'Unable to add collection in cart';
                            echo json_encode(error_response($msg));
                        }

                    } else {

                        // Set row cart
                        $row_custom_cart = mysql_fetch_array($custom_cart_query);

                        // Set last amount cart and quantity
                        $last_amount_cart = (float)$current_price + (float)$row_custom_cart['amount'];
                        $last_quantity = (float)1 + (float)$row_custom_cart['qty'];

                        // Update cart
                        $update_cart_query = "UPDATE `cart` SET `qty` = '$last_quantity', `amount` = '$last_amount_cart', `date_upd` = NOW()
                        WHERE `id_cart` = '" . $row_custom_cart["id_cart"] . "';";

                        // Error
                        if (!mysql_query($update_cart_query)) {
                            $msg = 'Unable to add collection in cart';
                            echo json_encode(error_response($msg));
                            exit();
                        }

                    }

                    // Success
                    $msg = 'Success add collection to cart';
                    echo json_encode(success_response($msg));
                    exit();

                } else {
                    $msg = 'Collection ID parameter required';
                    echo json_encode(error_response($msg));
                    exit();
                }

            } catch (\Exception $exception) {
                $msg = 'Unable to add collection cart.';
                echo json_encode(error_response($msg));
                exit();
            }

        } else {
            $msg = 'Must be signed in to process';
            echo json_encode(error_response($msg));
            exit();
        }

    }

}
