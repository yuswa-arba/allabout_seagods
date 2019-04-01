<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include('config/configuration.php');
session_start();
ob_start();

// Check login
$loggedin = logged_in();

if (isset($_POST['currency_code'])) {

    // Set value request
    $currency_code = isset($_POST['currency_code']) ? mysql_real_escape_string(trim($_POST['currency_code'])) : '';

    if (!empty($currency_code)) {

        // If is logged in
        if ($loggedin) {

            // Set member
            $member_query = mysql_query("SELECT * FROM `member` WHERE `id_member` = '" . $loggedin["id_member"] . "' LIMIT 0,1;");
            if (mysql_num_rows($member_query) == 0) {
                $msg = 'Member not found';
                echo json_encode(error_response($msg));
                exit();
            }

            // Update Member
            $update_currency_query = "UPDATE `member` SET `currency_code` = '$currency_code' WHERE `id_member` = '" . $loggedin["id_member"] . "'";

            // If Error
            if (!mysql_query($update_currency_query)) {
                $msg = 'Unable to update currency';
                echo json_encode(error_response($msg));
                exit();
            }

            // Set session
            $_SESSION['currency_code'] = $currency_code;

            // Success
            $msg = 'Success update currency';
            echo json_encode(success_response($msg));
            exit();

        } else {

            // Set session
            $_SESSION['currency_code'] = $currency_code;

            // Success
            $msg = 'Success update currency';
            echo json_encode(success_response($msg));
            exit();

        }

    } else {
        $msg = 'Currency code parameter required';
        echo json_encode(error_response($msg));
        exit();
    }
}
