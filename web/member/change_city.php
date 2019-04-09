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

if (isset($_POST['action'])) {

    // Set value action
    $action = isset($_POST['action']) ? mysql_real_escape_string(trim($_POST['action'])) : '';

    // Action for change province
    if ($action == 'change_province') {

        // Set value request
        $id_province = isset($_POST['id_province']) ? mysql_real_escape_string(trim($_POST['id_province'])) : '';

        // if empty
        if (empty($id_province)) {
            $msg = 'Province ID parameter required';
            echo json_encode(error_response($msg));
            exit();
        }

        // Set province
        $province_query = mysql_query("SELECT * FROM `provinsi` WHERE `idProvinsi` = '$id_province' LIMIT 0,1;");

        // Error
        if (mysql_num_rows($province_query) == 0) {
            $msg = 'Province not found';
            echo json_encode(error_response($msg));
            exit();
        }

        // if is logged in
        if ($loggedin) {

            // Update member query
            $update_member_query = "UPDATE `member` SET `idpropinsi` = '$id_province' WHERE `id_member` = '" . $loggedin["id_member"] . "';";

            // Error
            if (!mysql_query($update_member_query)) {
                $msg = 'Unable to update province in member';
                echo json_encode(error_response($msg));
                exit();
            }

        } else {

            // Set province in session
            $_SESSION['guest']['id_province'] = $id_province;

        }

        // Set city
        $city_province_query = mysql_query("SELECT * FROM `kota` WHERE `idProvinsi` = '$id_province' AND `level` = '0';");

        // set data city
        $data_cities = [];
        while ($row_city_province = mysql_fetch_assoc($city_province_query)) {
            $data_cities[] = $row_city_province;
        }

        // Success
        $msg = 'Set province successfully';
        echo json_encode(success_response($msg, $data_cities));
        exit();

    }

    // Action for change city
    if ($action == 'change_city') {

        // Set value request
        $id_city = isset($_POST['id_city']) ? mysql_real_escape_string(trim($_POST['id_city'])) : '';

        // if empty
        if (empty($id_city)) {
            $msg = 'City ID parameter required';
            echo json_encode(error_response($msg));
            exit();
        }

        // Set city
        $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '$id_city' LIMIT 0,1;");

        // Error
        if (mysql_num_rows($city_query) == 0) {
            $msg = 'City not found';
            echo json_encode(error_response($msg));
            exit();
        }

        // if is logged in
        if ($loggedin) {

            // Update member query
            $update_member_query = "UPDATE `member` SET `idkota` = '$id_city' WHERE `id_member` = '" . $loggedin["id_member"] . "';";

            // Error
            if (!mysql_query($update_member_query)) {
                $msg = 'Unable to update city in member';
                echo json_encode(error_response($msg));
                exit();
            }

        } else {

            // Set province in session
            $_SESSION['guest']['id_city'] = $id_city;

        }

        // Success
        $msg = 'Set city successfully';
        echo json_encode(success_response($msg));
        exit();

    }

}