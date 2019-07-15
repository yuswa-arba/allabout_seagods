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
include("../config/shipping/action_raja_ongkir.php");
include("../config/shipping/province_city.php");
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

        // Set session id_province
        $_SESSION['customer']['id_province'] = $id_province;

        // Unset City
        if (isset($_SESSION['customer']['id_city'])) {
            unset($_SESSION['customer']['id_city']);
        }

        // Unset Courier
        if (isset($_SESSION['customer']['courier'])) {
            unset($_SESSION['customer']['courier']);
        }

        // Unset service
        if (isset($_SESSION['customer']['service'])) {
            unset($_SESSION['customer']['service']);
        }

        // Unset cost
        if (isset($_SESSION['customer']['courier_cost'])) {
            unset($_SESSION['customer']['courier_cost']);
        }

        // Set parameter city
        $parameter_city = [
            'province' => $id_province
        ];

        // Get city
        $get_city = get_city($parameter_city);

        // Success
        $msg = 'Set province successfully';
        echo json_encode(success_response($msg, $get_city->rajaongkir->results));
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

        // Set province in session
        $_SESSION['customer']['id_city'] = $id_city;

        // Unset Courier
        if (isset($_SESSION['customer']['courier'])) {
            unset($_SESSION['customer']['courier']);
        }

        // Unset service
        if (isset($_SESSION['customer']['service'])) {
            unset($_SESSION['customer']['service']);
        }

        // Unset cost
        if (isset($_SESSION['customer']['courier_cost'])) {
            unset($_SESSION['customer']['courier_cost']);
        }

        // Get couriers
        $couriers = get_couriers();

        // Success
        $msg = 'Set city successfully';
        echo json_encode(success_response($msg, $couriers));
        exit();

    }

}