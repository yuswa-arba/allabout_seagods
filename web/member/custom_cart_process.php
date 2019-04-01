<?php

require "config/configuration.php";

if (isset($_POST['action']) && $_POST['action'] == 'request') {
    $id_collection = isset($_POST['id_collection']) ? strip_tags(trim($_POST['id_collection'])) : '';

    $query_collection = mysql_query("SELECT * FROM `custom_collection`
        WHERE `id_custom_collection` = '$id_collection'
        AND `level` = '0' LIMIT 0,1;");
    $row_collection = mysql_fetch_array($query_collection);

    if (mysql_num_rows($query_collection) == 0) {
        $return_request['failed'] = true;
        $return_request['message'] = 'Product custom not found.';
        exit(json_encode($return_request));
    }

    $query_request = mysql_query("INSERT INTO `custom_request` 
        (`id_custom_request`, `id_custom_collection`, `status`, `date_add`, `date_upd`, `level`)
        VALUES ('', '" . $row_collection["id_custom_collection"] . "', 'new request', NOW(), NOW(), '0');");
    if (!$query_request) {
        $return_request['failed'] = true;
        $return_request['message'] = 'Unable to send request product custom.';
        exit(json_encode($return_request));
    }

    $query_collection_update = mysql_query("UPDATE `custom_collection` SET `requested` = '1', `status` = 'requested', `date_upd` = NOW() 
        WHERE `id_custom_collection` = '" . $row_collection["id_custom_collection"] . "';");
    if (!$query_collection_update) {
        $return_request['failed'] = true;
        $return_request['message'] = 'Unable to update product custom.';
        exit(json_encode($return_request));
    }

    $return_request['failed'] = false;
    $return_request['message'] = 'Request successfully. Waiting for the admin to process.';
    $return_request['status'] = 'Requested';
    exit(json_encode($return_request));
}