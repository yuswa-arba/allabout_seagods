<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require __DIR__ . '/../vendor/autoload.php';

// Access with sandbox
$sandboxClientId = 'AV1rngNO32yw87iDNr2G7nC67FNU8ashq1EBqc39zDgAgjrQy9PMFNuMr4ys8HO4IIPZZvN3g3TEsSpY';
$sandboxSecret = 'EHFUpL7tx2QjpwQOxIvAcXUytZlFi5_rWbVtb3bBfUp5zwd_2E92G5oZ7vouA6pWVh-w2_autUAUkg9t';

// Access with Production
$productionClientId = 'ATU84RpAReTMjTSw8Kcid9fqnYjD4bgLMj9t9T8pFbMSTCmbNYK3dDSAsmGNaSw2aRVWR3DDCVsr3hwh';
$productionSecret = 'EGAchFE36H4IxFT3BQbk8V9FJ8dFJE5kDTNit9hXMF9uog_QJx2el-ilarDLpW-FIGAr1f9tHMOuTL9u';

$api = new ApiContext(
    new OAuthTokenCredential(
        $sandboxClientId,  // TODO: Ubah $sandboxClientId (testing) => $productionClientId (production)
        $sandboxSecret  // TODO: Ubah $sandboxSecret (testing) => $productionSecret (production)
    )
);

$api->setConfig([
    'mode' => 'sandbox', // TODO: Ubah sandbox (testing) => production (production)
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false,
    'log.FileName' => '',
    'log.LogLevel' => 'FINE',
    'validation' => 'log'
]);

?>

