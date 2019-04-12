<?php

function get_province($id_province = null)
{
    // Set where id_province
    $parameter = ($id_province ? '?id=' . $id_province : '');
    $url_province = 'province' . $parameter;

    // Get province
    return action_get($url_province);
}

function get_city($parameters = [])
{
    // Set parameter request or data request
    $parameters = set_parameter_or_data_request($parameters);

    // Set where id_province
    $action_parameter = '';
    foreach ($parameters as $key => $parameter) {

        // Set result parameter
        if ($key == 0) {
            $action_code = '?';
        } else {
            $action_code = '&';
        }

        // Set key name
        $name_key = key($parameter);

        $action_parameter .= $action_code . $name_key . '=' . $parameter[$name_key];
    }
    $url_city = 'city' . $action_parameter;

    // Get province
    return action_get($url_city);
}
