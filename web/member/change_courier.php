<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("../config/shipping/action_raja_ongkir.php");
include("../config/shipping/province_city.php");
session_start();
ob_start();

// Set logged in
$loggedin = logged_in();

if (isset($_POST['action'])) {

    // Set value action
    $action = isset($_POST['action']) ? mysql_real_escape_string(trim($_POST['action'])) : '';

    // Action for change courier
    if ($action == 'change_courier') {

        // Set value request
        $id_city_company = isset($_POST['id_city_company']) ? mysql_real_escape_string(trim($_POST['id_city_company'])) : '';
        $id_city = isset($_POST['id_city']) ? mysql_real_escape_string(trim($_POST['id_city'])) : '';
        $weight = isset($_POST['weight']) ? mysql_real_escape_string(trim($_POST['weight'])) : '';
        $courier = isset($_POST['courier']) ? mysql_real_escape_string(trim($_POST['courier'])) : '';

        // if empty
        if (empty($id_city)) {
            $msg = 'All request parameter required';
            echo json_encode(error_response($msg));
            exit();
        }

        // Set parameter request
        $parameter_cost = [
            'origin' => $id_city_company,
            'destination' => $id_city,
            'weight' => (($weight < 1) ? 1 : $weight),
            'courier' => $courier
        ];

        // Get courier
        $get_cost = get_cost($parameter_cost);
        if ($get_cost->rajaongkir->status->code == 400) {
            echo json_encode(error_response($get_cost->rajaongkir->status->description));
            exit();
        }

        // Set session courier
        $_SESSION['customer']['courier'] = $get_cost->rajaongkir->query->courier;

        // Unset service
        if (isset($_SESSION['customer']['service'])) {
            unset($_SESSION['customer']['service']);
        }

        // Unset cost
        if (isset($_SESSION['customer']['courier_cost'])) {
            unset($_SESSION['customer']['courier_cost']);
        }

        // Success
        $msg = 'Set city successfully';
        echo json_encode(success_response($msg, $get_cost));
        exit();

    }

    // Action for change service courier
    if ($action = 'change_service_courier') {

        // Set request parameter
        $service_courier = isset($_POST['service_courier']) ? mysql_real_escape_string(trim($_POST['service_courier'])) : '';
        $id_city_company = isset($_POST['id_city_company']) ? mysql_real_escape_string(trim($_POST['id_city_company'])) : '';
        $id_city = isset($_POST['id_city']) ? mysql_real_escape_string(trim($_POST['id_city'])) : '';
        $weight = isset($_POST['weight']) ? mysql_real_escape_string(trim($_POST['weight'])) : '';
        $courier = isset($_POST['courier']) ? mysql_real_escape_string(trim($_POST['courier'])) : '';

        // if empty
        if (empty($id_city)) {
            $msg = 'All request parameter required';
            echo json_encode(error_response($msg));
            exit();
        }

        // Set parameter request
        $parameter_cost = [
            'origin' => $id_city_company,
            'destination' => $id_city,
            'weight' => (($weight < 1) ? 1 : $weight),
            'courier' => $courier
        ];

        // Get courier
        $get_cost = get_cost($parameter_cost);
        if ($get_cost->rajaongkir->status->code == 400) {
            echo json_encode(error_response($get_cost->rajaongkir->status->description));
            exit();
        }

        // Set service cost
        $service_costs = $get_cost->rajaongkir->results[0]->costs;

        foreach ($service_costs as $service_cost) {

            // If service cost is same with request
            if ($service_cost->service == $service_courier) {
                $_SESSION['customer']['courier_cost'] = $service_cost->cost[0]->value;
            }

        }

        // Set session courier
        $_SESSION['customer']['service'] = $service_courier;

        // Success
        $msg = 'Set service courier successfully';
        echo json_encode(success_response($msg));
        exit();

    }

}

function get_cost($parameters)
{
    // Set parameter request or data request
    $parameters = set_parameter_or_data_request($parameters);

    // Set where id_province
    $action_parameter = '';
    foreach ($parameters as $key => $parameter) {

        // Set result parameter
        if ($key == 0) {
            $action_code = '';
        } else {
            $action_code = '&';
        }

        // Set key name
        $name_key = key($parameter);

        $action_parameter .= $action_code . $name_key . '=' . $parameter[$name_key];
    }

    // Get cost
    return action_post('cost', $action_parameter);
}