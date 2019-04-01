<?php
//db details
$dbHost = 'localhost';
$dbUsername = 'u9814486_seagx2018';
$dbPassword = 'backupweb123';
$dbName = 'u9814486_seagx2018';

//Connect and select the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>