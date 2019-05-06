<?php
include("config/configuration.php");
session_start();
ob_start();

// Set logged in
$loggedin = logged_in();

if ($loggedin) {

    if (isset($_POST['canvasData'])) {

        // Set value image
        $imageData = $_POST['canvasData'];

        // Data Update Cart
        $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
        $wetsuit = isset($_POST["wetsuitType"]) ? $_POST["wetsuitType"] : "";
        $ankleZipper = isset($_POST["ankleZipper"]) ? $_POST["ankleZipper"] : "";
        $armZipper = isset($_POST["armZipper"]) ? $_POST["armZipper"] : "";
        $genitalZipper = isset($_POST["genitalZipper"]) ? $_POST["genitalZipper"] : "";

        // Measure value
        $measureTotalBodyHeight = isset($_POST['measureTotalBodyHeight']) ? strip_tags(trim($_POST['measureTotalBodyHeight'])) : "";
        $measureHead = isset($_POST['measureHead']) ? strip_tags(trim($_POST['measureHead'])) : "";
        $measureNeck = isset($_POST['measureNeck']) ? strip_tags(trim($_POST['measureNeck'])) : "";
        $measureBustChest = isset($_POST['measureBustChest']) ? strip_tags(trim($_POST['measureBustChest'])) : "";
        $measureWaist = isset($_POST['measureWaist']) ? strip_tags(trim($_POST['measureWaist'])) : "";
        $measureStomach = isset($_POST['measureStomach']) ? strip_tags(trim($_POST['measureStomach'])) : "";
        $measureAbdomen = isset($_POST['measureAbdomen']) ? strip_tags(trim($_POST['measureAbdomen'])) : "";
        $measureHip = isset($_POST['measureHip']) ? strip_tags(trim($_POST['measureHip'])) : "";
        $measureShoulder = isset($_POST['measureShoulder']) ? strip_tags(trim($_POST['measureShoulder'])) : "";
        $measureShoulderToElbow = isset($_POST['measureShoulderToElbow']) ? strip_tags(trim($_POST['measureShoulderToElbow'])) : "";
        $measureShoulderToWrist = isset($_POST['measureShoulderToWrist']) ? strip_tags(trim($_POST['measureShoulderToWrist'])) : "";
        $measureArmHole = isset($_POST['measureArmHole']) ? strip_tags(trim($_POST['measureArmHole'])) : "";
        $measureUpperArm = isset($_POST['measureUpperArm']) ? strip_tags(trim($_POST['measureUpperArm'])) : "";
        $measureBicep = isset($_POST['measureBicep']) ? strip_tags(trim($_POST['measureBicep'])) : "";
        $measureElbow = isset($_POST['measureElbow']) ? strip_tags(trim($_POST['measureElbow'])) : "";
        $measureForarm = isset($_POST['measureForarm']) ? strip_tags(trim($_POST['measureForarm'])) : "";
        $measureWrist = isset($_POST['measureWrist']) ? strip_tags(trim($_POST['measureWrist'])) : "";
        $measureOutsideLegLength = isset($_POST['measureOutsideLegLength']) ? strip_tags(trim($_POST['measureOutsideLegLength'])) : "";
        $measureInsideLegLength = isset($_POST['measureInsideLegLength']) ? strip_tags(trim($_POST['measureInsideLegLength'])) : "";
        $measureUpperThigh = isset($_POST['measureUpperThigh']) ? strip_tags(trim($_POST['measureUpperThigh'])) : "";
        $measureThigh = isset($_POST['measureThigh']) ? strip_tags(trim($_POST['measureThigh'])) : "";
        $measureAboveKnee = isset($_POST['measureAboveKnee']) ? strip_tags(trim($_POST['measureAboveKnee'])) : "";
        $measureKnee = isset($_POST['measureKnee']) ? strip_tags(trim($_POST['measureKnee'])) : "";
        $measureBelowKnee = isset($_POST['measureBelowKnee']) ? strip_tags(trim($_POST['measureBelowKnee'])) : "";
        $measureCalf = isset($_POST['measureCalf']) ? strip_tags(trim($_POST['measureCalf'])) : "";
        $measureBelowCalf = isset($_POST['measureBelowCalf']) ? strip_tags(trim($_POST['measureBelowCalf'])) : "";
        $measureAboveAnkle = isset($_POST['measureAboveAnkle']) ? strip_tags(trim($_POST['measureAboveAnkle'])) : "";
        $measureShoulderToBust = isset($_POST['measureShoulderToBust']) ? strip_tags(trim($_POST['measureShoulderToBust'])) : "";
        $measureShoulderToWaist = isset($_POST['measureShoulderToWaist']) ? strip_tags(trim($_POST['measureShoulderToWaist'])) : "";
        $measureShoulderToHip = isset($_POST['measureShoulderToHip']) ? strip_tags(trim($_POST['measureShoulderToHip'])) : "";
        $measureHipToKneeLength = isset($_POST['measureHipToKneeLength']) ? strip_tags(trim($_POST['measureHipToKneeLength'])) : "";
        $measureKneeToAnkleLength = isset($_POST['measureKneeToAnkleLength']) ? strip_tags(trim($_POST['measureKneeToAnkleLength'])) : "";
        $measureBackShoulder = isset($_POST['measureBackShoulder']) ? strip_tags(trim($_POST['measureBackShoulder'])) : "";
        $measureDorsum = isset($_POST['measureDorsum']) ? strip_tags(trim($_POST['measureDorsum'])) : "";
        $measureCrotchPoint = isset($_POST['measureCrotchPoint']) ? strip_tags(trim($_POST['measureCrotchPoint'])) : "";

        // Set memory limit in php.ini
        ini_set('memory_limit', '20M');

        // Remove the headers (data:,) part.
        $filteredData = substr($imageData, strpos($imageData, ",") + 1);

        // Need to decode before saving since the data we received is already base64 encoded
        $unencodedData = base64_decode($filteredData);

        $random_digit = md5(uniqid(mt_rand(), true));
        $img = "Custom" . date('ymdHis') . $random_digit . ".png";
        $directory = 'public/images/custom_cart/';

        // Save file. This example uses a hard coded filename for testing
        $fp = fopen($directory . $img, 'wb');
        fwrite($fp, $unencodedData);
        fclose($fp);

        // Custom cart number
        $custom_item_number = generate_custom_item_number();

        // Set price custom item
        $custom_price_query = mysql_query("SELECT * FROM `setting_seagods` WHERE `name` = 'price-custom-item' LIMIT 0,1;");
        $row_price = mysql_fetch_array($custom_price_query);

        // Insert to custom collection
        $query_custom_collection = mysql_query("INSERT INTO `custom_collection`
                    (`code`, `id_member`, `gender`, `wet_suit_type`, `arm_zipper`, `ankle_zipper`, `genital_zipper`, `image`, `price`, `status`, `date_add`, `date_upd`, `level`)
                    VALUES ('$custom_item_number', '" . $loggedin["id_member"] . "', '$gender', '$wetsuit', '$armZipper', '$ankleZipper', '$genitalZipper', '$img', '" . $row_price["value"] . "', 'saved', NOW(), NOW(), '0');");
        if (!$query_custom_collection) {
            $msg = 'Unable to save collection.!';
            echo json_encode(error_response($msg));
            exit();
        }

        // Select custom collection
        $custom_collection_query = mysql_query("SELECT * FROM `custom_collection`
                    WHERE `id_member` = '" . $loggedin["id_member"] . "'
                    AND `code` = '$custom_item_number'
                    AND `level` = '0'
                    ORDER BY `id_custom_collection` DESC
                    LIMIT 0,1;");
        $row_custom_collection = mysql_fetch_array($custom_collection_query);

        // Insert custom measure
        $query_custom_measure = mysql_query("INSERT INTO `custom_measure`
                    (`id_custom_collection`, `id_member`, `total_body_height`, `head`, `neck`, `bust_chest`, `waist`, `stomach`, `abdomen`, `hip`, `shoulder`, `shoulder_elbow`, `shoulder_wrist`, `arm_hole`, 
                    `upper_arm`, `bicep`, `elbow`, `forarm`, `wrist`, `outside_leg_length`, `inside_leg_length`, `upper_thigh`, `thigh`, `above_knee`, `knee`, `below_knee`, `calf`, `below_calf`, `above_ankle`, 
                    `shoulder_burst`, `shoulder_waist`, `shoulder_hip`, `hip_knee_length`, `knee_ankle_length`, `back_shoulder`, `dorsum`, `crotch_point`)
                    VALUES ('" . $row_custom_collection["id_custom_collection"] . "', '" . $loggedin["id_member"] . "', '$measureTotalBodyHeight', '$measureHead', '$measureNeck', '$measureBustChest', '$measureWaist', '$measureStomach', '$measureAbdomen', '$measureHip', '$measureShoulder', '$measureShoulderToElbow', '$measureShoulderToWrist', '$measureArmHole',
                    '$measureUpperArm', '$measureBicep', '$measureElbow', '$measureForarm', '$measureWrist', '$measureOutsideLegLength', '$measureInsideLegLength', '$measureUpperThigh', '$measureThigh', '$measureAboveKnee', '$measureKnee', '$measureBelowKnee', '$measureCalf', '$measureBelowCalf', '$measureAboveAnkle',
                    '$measureShoulderToBust', '$measureShoulderToWaist', '$measureShoulderToHip', '$measureHipToKneeLength', '$measureKneeToAnkleLength', '$measureBackShoulder', '$measureDorsum', '$measureCrotchPoint')");
        if (!$query_custom_measure) {
            $msg = 'Unable to save measure.!';
            echo json_encode(error_response($msg));
            exit();
        }

        // Success
        $msg = 'Add to project successfully';
        echo json_encode(success_response($msg));
        exit();

    } else {
        $msg = 'Canvas data parameter required';
        echo json_encode(error_response($msg));
        exit();
    }

} else {
    $msg = 'Must be login for save project';
    echo json_encode(error_response($msg));
    exit();
}

?>


