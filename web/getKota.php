<?php
//Include database configuration file
include("config/shipping/action_raja_ongkir.php");
include("config/shipping/province_city.php");

if (isset($_POST["id_province"]) && !empty($_POST["id_province"])) {

    // Set parameters
    $parameter = [
        'province' => $_POST['id_province']
    ];

    // Get province
    $get_city = get_city($parameter);

    // Set cities
    $cities = $get_city->rajaongkir->results;

    //Display states list
    if (count($cities) > 0) {

        echo '<option value="">-- Choose City --</option>';
        foreach ($cities as $city) {
            echo '<option value="' . $city->city_id . '">' . $city->city_name . '</option>';
        }

    } else {
        echo '<option value="">-- Choose City --</option>';
    }

}

?>