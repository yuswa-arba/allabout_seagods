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

if ($loggedin) {

    if (isset($_POST['subscribe'])) {

        // Set value request
        $subscribe = isset($_POST['subscribe']) ? mysql_real_escape_string(trim($_POST['subscribe'])) : '';

        if ($subscribe != '') {

            // Update subscribe
            $subscribe_query = "UPDATE `member` SET `subscribe` = '$subscribe' WHERE `id_member` = '" . $loggedin['id_member'] . "'";

            // Set type subscribe
            $type_subscribe = $subscribe ? 'Subscribe' : 'Unsubscribe';

            // If Error
            if (!mysql_query($subscribe_query)) {
                $msg = 'Unable to ' . strtolower($type_subscribe);
                echo json_encode(error_response($msg));
                exit();
            }

            // Success
            $msg = $type_subscribe . ' successfully';
            echo json_encode(success_response($msg));
            exit();

        } else {
            $msg = 'Subscribe parameter required';
            echo json_encode(error_response($msg));
            exit();
        }

    }

} else {
    $msg = 'Must be signed in to process';
    echo json_encode(error_response($msg));
    exit();
}

?>