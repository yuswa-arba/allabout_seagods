<?php
include("../config/configuration.php");
include("../config/currency_types.php");
include("../plugins/mailer/class.phpmailer.php");
include("template/purchase_order.php");

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Get transaction
$transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `send_order_email` = '0';");

while ($row_transaction = mysql_fetch_array($transaction_query)) {

    // Set shipping
    $shipping_query = mysql_query("SELECT * FROM `transaction_shipping` WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "' LIMIT 0,1;");
    $row_shipping = mysql_fetch_array($shipping_query);

    // Get cart
    $cart_query = mysql_query("SELECT * FROM `cart` WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "' AND `level` = '0';");
    $result_carts = [];
    while ($row_cart = mysql_fetch_assoc($cart_query)) {

        // Set photo default
        $row_photo = [];

        // Select item
        if ($row_cart['is_custom_cart']) {

            // Set collection
            $collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '" . $row_cart['id_item'] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_assoc($collection_query);

            // Set custom price
            $row_item['price'] = get_price('price-custom-item');

        } else {

            // Set collection
            $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $row_cart['id_item'] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_assoc($item_query);

            // Set photo
            $photo_query = mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $row_item["id_item"] . "' AND `level` = '0' LIMIT 0,1;");
            $row_photo = ['photo' => mysql_fetch_assoc($photo_query)];

        }

        $result_carts[] = [
                'cart' => $row_cart,
                'item' => $row_item,
            ] + $row_photo;
    }

    // Set buyer
    if ($row_transaction['is_guest']) {

        // Set Guest
        $guest_query = mysql_query("SELECT * FROM `guest` WHERE `id` = '" . $row_transaction["id_guest"] . "' LIMIT 0,1");
        $row_buyer = mysql_fetch_assoc($guest_query);

    } else {

        // Set Guest
        $member_query = mysql_query("SELECT * FROM `member` WHERE `id_member` = '" . $row_transaction["id_member"] . "' LIMIT 0,1");
        $row_buyer = mysql_fetch_assoc($member_query);

    }

    // If payment with bank transfer
    $row_bank_transfer = null;
    if ($row_transaction['payment_method'] == 'Bank Transfer') {

        // Set bank transfer
        $bank_transfer_query = mysql_query("SELECT * FROM `bank_transfer` WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "' AND `level` = '0' LIMIT 0,1;");
        $row_bank_transfer = mysql_fetch_assoc($bank_transfer_query);

        // Set bank
        $bank_query = mysql_query("SELECT * FROM `bank_account` WHERE `id` = '" . $row_bank_transfer['id_bank'] . "' AND `level` = '0' LIMIT 0,1;");
        $row_bank_transfer['bank'] = mysql_fetch_assoc($bank_query);

    } else {

        // Set payment
        $payment_query = mysql_query("SELECT * FROM `paypals` WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "' AND `level` = '0' LIMIT 0,1;");
        $row_transaction['paypal'] = mysql_fetch_assoc($payment_query);

    }

    // Set province ID
    $id_province = ($row_transaction['is_guest'] ? $row_buyer['id_province'] : $row_buyer['idpropinsi']);

    // Set province
    $province_query = mysql_query("SELECT `namaProvinsi` FROM `provinsi` WHERE `idProvinsi` = '$id_province' LIMIT 0,1;");
    $row_province = mysql_fetch_array($province_query);
    $province = $row_province['namaProvinsi'];

    // Set city ID
    $id_city = ($row_transaction['is_guest'] ? $row_buyer['id_city'] : $row_buyer['idkota']);

    // Set city
    $city_query = mysql_query("SELECT `namaKota` FROM `kota` WHERE `idKota` = '$id_city' LIMIT 0,1;");
    $row_city = mysql_fetch_array($city_query);
    $city = $row_city['namaKota'];

    // Set Currency and USD to IDR
    $currency_properties['currency'] = (($row_transaction['payment_method'] == 'Paypal') ? CURRENCY_USD : CURRENCY_IDR);
    $currency_properties['USDtoIDR'] = get_price('currency-value-usd-to-idr');

    if ($row_buyer['email']) {


//        $mail = new PHPMailer(); // defaults to using php "mail()"
//        $mail->IsSMTP();
//        $mail->SMTPDebug = 0; // set mailer to use SMTP
//        $mail->Timeout = 120;     // set longer timeout for latency or servers that take a while to respond
//
//        //smtp.dps.globalxtreme.net
//        $mail->Host = "202.58.203.26";        // specify main and backup server
//        $mail->Port = 2505;
//        $mail->SMTPAuth = false;    // turn on or off SMTP authentication
//
//        try {
//
            $message_template = purchase_order_template(
                $row_transaction,
                $result_carts,
                $row_shipping,
                $row_buyer,
                $province,
                $city,
                $currency_properties,
                $row_bank_transfer
            );
            echo $message_template;
//
//            // Set Holder name
//            $holder_name = ($row_transaction['is_guest'] ? ($row_buyer['first_name'] . ' ' . $row_buyer['last_name']) : ($row_buyer['firstname'] . ' ' . $row_buyer['lastname']));
//
//            $mail->AddAddress($row_buyer['email'], $holder_name);
//            $mail->SetFrom('info@seagodswetsuit.com', 'Seagods Wetsuit');
//
//            $mail->Subject = 'Purchase Order - Seagods Wetsuit';
//            $mail->MsgHTML($message_template);
//
//            if ($mail->Send()) {
//
//                // Set Update transaction
//                mysql_query("UPDATE `transaction` SET `send_order_email` = '1' WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "';");
//
//            }
//
//        } catch (phpmailerException $e) {
//            // Error
//        }

    }
}
