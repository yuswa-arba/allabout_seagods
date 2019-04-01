<?php
include("config/configuration.php");
session_start();
ob_start();

if (isset($_POST['canvasData'])) {

    // Set value image
    $imageData = $_POST['canvasData'];

    // Data Update Cart
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $wetsuit = isset($_POST["wetsuitType"]) ? $_POST["wetsuitType"] : "";
    $ankleZipper = isset($_POST["ankleZipper"]) ? $_POST["ankleZipper"] : "";
    $armZipper = isset($_POST["armZipper"]) ? $_POST["armZipper"] : "";

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
    $img = "Custom" . $random_digit . ".png";
    $directory = 'public/images/custom_cart/';

    // Save file. This example uses a hard coded filename for testing
    $fp = fopen($directory . $img, 'wb');
    fwrite($fp, $unencodedData);
    fclose($fp);

    // Custom cart number
    $custom_item_number = generate_custom_item_number();

    $_SESSION['cart_item'][] = [
        'collection' => [
            'code' => $custom_item_number,
            'gender' => $gender,
            'wet_suit_type' => $wetsuit,
            'arm_zipper' => $armZipper,
            'ankle_zipper' => $ankleZipper,
        ],
        'measure' => []
    ];

    // Success
    $msg = 'Add to project successfully';
    echo json_encode(success_response($msg, $_POST));
    exit();

} else {
    $msg = 'Canvas data parameter required';
    echo json_encode(error_response($msg));
    exit();
}

?>
