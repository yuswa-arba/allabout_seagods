<?php

require "config/configuration.php";

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
    $paymentId = isset($_POST['paymentId']) ? strip_tags(trim($_POST['paymentId'])) : '';
    $transactionId = isset($_POST['id_transaction']) ? strip_tags(trim($_POST['id_transaction'])) : '';
    $state = isset($_POST['state']) ? strip_tags(trim($_POST['state'])) : '';
    $amount = isset($_POST['amount']['total']) ? $_POST['amount']['total'] : '';
    $shipping = isset($_POST['amount']['details']['shipping']) ? $_POST['amount']['details']['shipping'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    $return_update_payment['failed'] = false;

    if ($paymentId != '' && $transactionId != '' && $state != '' && $amount != '') {
        $query_transaction = mysql_query("SELECT * FROM `transaction` WHERE `id_transaction` = '$transactionId' LIMIT 0,1;");
        $row_transaction = mysql_fetch_array($query_transaction);

        if (mysql_num_rows($query_transaction)) {

            $query_transaction_update = mysql_query("UPDATE `transaction` SET `status` = '$state', `konfirm` = 'Confirmated'
                    WHERE `id_transaction` = '$transactionId';");
            if (!$query_transaction_update) {
                $return_update_payment['failed'] = true;
            }

            $query_paypal = mysql_query("INSERT INTO `paypals` (`paymentId`, `id_transaction`, `id_member`, `status`, `amount`, `description`, `date_add`, `date_upd`, `level`)
                VALUES ('" . $paymentId . "', '" . $transactionId . "', '" . $row_transaction["id_member"] . "', '" . $state . "', '" . $amount . "', '" . $description . "', NOW(), NOW(), '0')");
            if (!$query_paypal) {
                $return_update_payment['failed'] = true;
            }

            $query_paypal_get = mysql_query("SELECT * FROM `paypals` 
                WHERE `paymentId` = '" . $paymentId . "' AND `id_transaction` = '" . $transactionId . "' AND `level` = '0';");
            $row_paypal = mysql_fetch_array($query_paypal_get);

            $query_cart = mysql_query("SELECT * FROM `cart` WHERE `id_transaction` = '" . $transactionId . "' AND `level` = '0';");
            while ($row_cart = mysql_fetch_array($query_cart)) {

                $query_item = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $row_cart["id_item"] . "';");
                $row_item = mysql_fetch_array($query_item);

                $query_paypal_item = mysql_query("INSERT INTO `paypal_items` (`id_paypal`, `id_item`, `price`, `quantity`, `date_add`, `date_upd`, `level`)
                    VALUES ('" . $row_paypal["id_paypal"] . "', '" . $row_cart["id_item"] . "', '" . $row_item["price"] . "', '" . $row_cart["qty"] . "', NOW(), NOW(), '0')");
                if (!$query_paypal_item) {
                    echo json_encode(['failed' => true]);
                }
            }

            if ($shipping != '') {
                $query_paypal_shipping = mysql_query("INSERT INTO `paypal_items` (`id_paypal`, `id_item`, `price`, `quantity`, `date_add`, `date_upd`, `level`)
                    VALUES ('" . $row_paypal["id_paypal"] . "', '', '" . $shipping . "', '', NOW(), NOW(), '0')");
                if (!$query_paypal_shipping) {
                    echo json_encode(['failed' => true]);
                }
            }
        }
    }

    echo json_encode($return_update_payment);
}
