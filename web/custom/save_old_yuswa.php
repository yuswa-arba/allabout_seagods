<?php
include("config/configuration.php");
session_start();
ob_start();
if ($loggedin = logged_in()) {
    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $user = '' . $_SESSION['user'] . '';
}

if (isset($_POST['action']) && isset($_POST['canvasData'])) {
    $id_custom_item = isset($_POST['action']) ? strip_tags(trim($_POST['action'])) : '';
    $imageData = $_POST['canvasData'];

    // Set memory limit in php.ini
    ini_set('memory_limit', '20M');

    // Remove the headers (data:,) part.
    // A real application should use them according to needs such as to check image type
    $filteredData = substr($imageData, strpos($imageData, ",") + 1);

    // Need to decode before saving since the data we received is already base64 encoded
    $unencodedData = base64_decode($filteredData);

    $random_digit = md5(uniqid(mt_rand(), true));
    $img = "Custom" . $random_digit . ".png";
    $directory = 'public/images/custom_cart/';

    //echo "unencodedData".$unencodedData;

    // Save file. This example uses a hard coded filename for testing,
    // but a real application can specify filename in POST variable
    $fp = fopen($directory . $img, 'wb');
    fwrite($fp, $unencodedData);
    fclose($fp);

    function randomString($length = 50)
    {
        $str = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    // Code Card
    $codeCard = randomString();

    $query_custom_item_select = mysql_query("SELECT * FROM `custom_item` 
            WHERE `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0';");
    $row_custom_item = mysql_fetch_array($query_custom_item_select);

    if ($id_custom_item == '') {

        if (mysql_num_rows($query_custom_item_select) == 0) {
            $query_custom_item_insert = mysql_query("INSERT INTO `custom_item` (`id_custom_item`, `code`, `id_member`, `status`, `image`, `date_add`, `date_upd`, `level`) 
                VALUES ('', '" . $codeCard . "', '" . $loggedin["id_member"] . "', 'process', '$img', NOW(), NOW(), '0')");
        } else {
            $query_custom_item_upd = mysql_query("UPDATE `custom_item` SET `code` = '$codeCard', `status` = 'process', `image` = '$img', `date_upd` = NOW() WHERE `id_custom_item` = '" . $row_custom_item["id_custom_item"] . "';");
        }

    } else {
        $query_custom_item_upd = mysql_query("UPDATE `custom_item` SET `code` = '$codeCard', `image` = '$img', `date_upd` = NOW() WHERE `id_custom_item` = '$id_custom_item';");
        if ($query_custom_item_upd) {
            unlink($directory . $row_custom_item['image']);
        }
    }

    $row_id_custom_item = mysql_fetch_array(mysql_query("SELECT `id_custom_item` FROM `custom_item` 
            WHERE `id_member` = '" . $loggedin["id_member"] . "' 
            AND `level` = '0';"));

    echo json_encode(['id' => $row_id_custom_item['id_custom_item']]);
}
?>


