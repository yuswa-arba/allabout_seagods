<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require __DIR__ . '/../vendor/autoload.php';

// Access with sandbox
$sandboxClientId = 'AU7qnuopCNgOrHZd1Dmpir5NwKTTpmO-lJ2x6d85Th6m-RAuZrTLchW5VgMvACgCvBf6Y_FlVBpf4Rea';
$sandboxSecret = 'EA818dIpMBRh8vZ3Fq4HhTA-7CIANsaojG8CAJ-OrlBQkJ7V81gY1V8funDXuJ4SB262_3uyR5rDzVxL';

// Access with Production
$productionClientId = 'AR6FGeTTxwJgQlv9WcAyJIN0-ct2xdl2qdj-U5m8u-dIOw61nbu68vZuLMJzBBXYNhJjn0nvFjguBmxa';
$productionSecret = 'EJ43dekCpQZ7r1gGrnMG_UznvkJ3Hhu0jHNERohoVyi9HjJCgwZgbwog0pinCp_hcfhCczJMLW_WXQgQ';

$api = new ApiContext(
    new OAuthTokenCredential(
        $sandboxClientId,
        $sandboxSecret
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

