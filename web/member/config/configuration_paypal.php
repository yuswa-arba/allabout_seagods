<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require __DIR__ . '/../../vendor/autoload.php';

$api = new ApiContext(
    new OAuthTokenCredential(
        'AfO3INSO7c--IdCQTzsj8lvrq77BE8occDyInufSJ52IV6GzXp_ScUw0BRBLjc6nW6w_xWycPOUe46RB',
        'EA6N1tLq93vIWUIaW9RzHK1OiGzWNB-Hd_J11RTrixMmhnMRBxVNrE0fj-sidoNTXjzlmUhis94_0jHM'
    )
);

$api->setConfig([
    'mode' => 'sandbox',
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false,
    'log.FileName' => '',
    'log.LogLevel' => 'FINE',
    'validation' => 'log'
]);

?>