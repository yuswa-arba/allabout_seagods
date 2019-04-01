<?php

if (isset($_POST['imgsrc']) && $_POST['imgsrc'])
{
    $data = $_POST['imgsrc'];


    // fix for IE catching or PHP bug issue
//    header("Pragma: public");
//    header("Expires: 0"); // set expiration time
//    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    // browser must download file from server instead of cache
    // force download dialog
//    header("Content-Type: application/force-download");
//    header("Content-Type: application/octet-stream");
//    header("Content-Type: application/download");

    header('Content-type: image/png');

    // use the Content-Disposition header to supply a recommended filename and 
    // force the browser to display the save dialog. 
//    header("Content-Disposition: attachment; filename=design.png;");
    echo base64_decode($data);
}
