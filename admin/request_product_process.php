<?php

require "config/configuration.php";

if (isset($_POST['action']) && $_POST['action'] == 'confirm') {
    $price = isset($_POST['price']) ? strip_tags(trim($_POST['price'])) : '';
    $id_request = isset($_POST['id_request']) ? strip_tags(trim($_POST['id_request'])) : '';

    $query_request = mysql_query("SELECT * FROM `custom_request` WHERE `id_custom_request` = '$id_request' LIMIT 0,1;");
    $row_request = mysql_fetch_array($query_request);

    if (mysql_num_rows($query_request) == 0) {
        $return_request['failed'] = true;
        $return_request['message'] = 'Request data not found.!';
        exit(json_encode($return_request));
    }

    $query_request_update = mysql_query("UPDATE `custom_request` SET `price` = '$price', `status` = 'confirm admin', `date_upd` = NOW() 
        WHERE `id_custom_request` = '$id_request';");
    if (!$query_request_update) {
        $return_request['failed'] = true;
        $return_request['message'] = 'Unable to update custom request.!';
        exit(json_encode($return_request));
    }

    $query_collection_update = mysql_query("UPDATE `custom_collection` SET `price` = '$price', `status` = 'confirmated', `date_upd` = NOW()
        WHERE `id_custom_collection` = '".$row_request["id_custom_collection"]."';");
    if (!$query_collection_update) {
        $return_request['failed'] = true;
        $return_request['message'] = 'Unable to update custom collection.!';
        exit(json_encode($return_request));
    }

    $return_request['failed'] = false;
    $return_request['message'] = 'Confirmation request price successfully.';
    $return_request['price'] = $price;
    $return_request['status'] = 'Confirm admin';
    exit(json_encode($return_request));
}