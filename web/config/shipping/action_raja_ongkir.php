<?php

// Set API key raja ongkir
define('API_KEY', '7423ce43385bd0d2340dfe6c6d55b256');

// Set base URL API
define('STARTER_API', 'https://api.rajaongkir.com/starter/');
define('BASIC_API', 'https://api.rajaongkir.com/basic');
define('PRO_API', 'https://pro.rajaongkir.com/api');

function action_get($url)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => STARTER_API . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: " . API_KEY
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $response = json_decode($response);

    return $response;
}

function action_post($url, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => STARTER_API . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: " . API_KEY
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $response = json_decode($response);

    return $response;
}

function set_parameter_or_data_request($parameters)
{
    $results = [];
    for ($a = 0; $a < count($parameters); $a++) {

        // Set splice parameter
        $splice_parameter = array_slice($parameters, $a, 1);

        // Set result
        $results[] = $splice_parameter;

    }

    return $results;
}