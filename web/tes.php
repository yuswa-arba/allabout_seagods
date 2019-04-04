<?php
include_once('custom/config/configuration.php');
session_start();
ob_start();
////$_SESSION['currency_code'] = 'USD';
//$item_array = array(
//    'id_item' => 22,
//    'quantity' => 3
//);
//
//// Set id_item session
//$id_item_session = array_column($_SESSION["cart_item"], 'id_item');
//
//if (in_array($item_array['id_item'], $id_item_session)) {
//    foreach ($_SESSION['cart_item'] as $key => $cart_item) {
//        if ($cart_item['id_item'] == $item_array['id_item']) {
//            $_SESSION['cart_item'][$key]['quantity'] = $cart_item['quantity'] + $item_array['quantity'];
//        }
//    }
//} else {
//    $_SESSION['cart_item'][] = $item_array;
//}
//
unset($_SESSION['cart_item']);
//$_SESSION['guest'] = ['idkota' => 1];
echo "<pre>";
print_r($_SESSION['cart_item']);
echo "</pre>";

//echo  generate_custom_item_number();