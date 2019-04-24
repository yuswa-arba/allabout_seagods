<?php
require '../config/configuration.php';

// Get photo
$photo_query = mysql_query("SELECT * FROM `photo` WHERE `level` = '0'");

while ($row_photo = mysql_fetch_assoc($photo_query)) {

    // Set new name
    $new_name = str_replace(' ', '_', $row_photo['photo']);

    try {

        // Set path
        $photo_path = "../../admin/images/product/";
        $photo_150_path = "../../admin/images/product/150/thumb_";
        $photo_600_path = "../../admin/images/product/600/thumb_";

        if (file_exists($photo_path . $row_photo['photo'])) {
            rename($photo_path . $row_photo['photo'], $photo_path . $new_name);
        }

        if (file_exists($photo_150_path . $row_photo['photo'])) {
            rename($photo_150_path . $row_photo['photo'], $photo_150_path . $new_name);
        }

        if (file_exists($photo_600_path . $row_photo['photo'])) {
            rename($photo_600_path . $row_photo['photo'], $photo_600_path . $new_name);
        }

        // Update name photo
        mysql_query("UPDATE `photo` SET `photo` = '$new_name' WHERE `id_photo` = '" . $row_photo["id_photo"] . "';");

    } catch (\Exception $exception) {
        continue;
    }
}