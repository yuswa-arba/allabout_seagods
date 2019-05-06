<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("config/template_cart.php");
include("config/currency_types.php");
include("config/shipping/action_raja_ongkir.php");
include("config/shipping/province_city.php");
session_start();
ob_start();

// Set logged in
$loggedin = logged_in();

$titlebar = "Cart";
$menu = "";

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Default currency
$currency_code = CURRENCY_USD_CODE;

// Set currency from session
if (isset($_SESSION['currency_code'])) {
    $currency_code = $_SESSION['currency_code'];
}

// Set currency from database
if ($loggedin) {
    $currency_code = $loggedin['currency_code'];
}

// Set currency
$currency = get_currency($currency_code);

// Set nominal curs from USD to IDR
$USDtoIDR = get_price('currency-value-usd-to-idr');

if (isset($_POST['remove'])) {

    // Set value
    $action = isset($_POST['action']) ? mysql_real_escape_string(trim($_POST['action'])) : '';
    $id_cart = isset($_POST['id_cart']) ? mysql_real_escape_string(trim($_POST['id_cart'])) : '';

    if (!empty($action)) {

        // Action
        if ($action == 'session') {

            // If is custom cart
            if ($_SESSION['cart_item'][$id_cart]['is_custom_cart']) {

                // Image
                $image = $_SESSION['cart_item'][$id_cart]['collection']['image'];

                // Unlink
                unlink('custom/public/images/custom_cart/' . $image);
            }

            // Unset cart in session
            unset($_SESSION['cart_item'][$id_cart]);

            // Success
            echo "<script>
                alert('Remove cart success');
                window.history.back(-1);
            </script>";
            exit();

        } else {

            // Set cart
            $cart_query = mysql_query("SELECT * FROM `cart` WHERE `id_cart` = '$id_cart' LIMIT 0,1;");

            // Error
            if (!mysql_num_rows($cart_query)) {
                echo "<script>
                    alert('Cart not found');
                    window.history.back(-1);
                </script>";
                exit();
            }
            $row_cart = mysql_fetch_array($cart_query);

            // Check if 

            // Delete cart
            $delete_cart_query = "UPDATE `cart` SET `level` = '1' WHERE `id_cart` = '$id_cart';";

            // Error
            if (!mysql_query($delete_cart_query)) {
                echo "<script>
                    alert('Unable to delete cart');
                    window.history.back(-1);
                </script>";
                exit();
            }

            // Success
            echo "<script>
                alert('Remove cart success');
                window.history.back(-1);
            </script>";
            exit();

        }

    } else {
        echo "<script>
            alert('Action or cart ID parameter required.');
            window.history.back(-1);
        </script>";
        exit();
    }

}

function get_cost($parameters)
{
    // Set parameter request or data request
    $parameters = set_parameter_or_data_request($parameters);

    // Set where id_province
    $action_parameter = '';
    foreach ($parameters as $key => $parameter) {

        // Set result parameter
        if ($key == 0) {
            $action_code = '';
        } else {
            $action_code = '&';
        }

        // Set key name
        $name_key = key($parameter);

        $action_parameter .= $action_code . $name_key . '=' . $parameter[$name_key];
    }

    // Get cost
    return action_post('cost', $action_parameter);
}

$content = '
    <div id="Content" class="p-t-0">
        <div class="content_wrapper clearfix">
            <div class="" style="margin: 0 auto; width: 90%">
                <div class="section mcb-section p-t-0 p-b-20  full-width">
                    <div class="section_wrapper mcb-section-inner one m-l-0 m-r-0">
                        <div class="wrap mcb-wrap one valign-top clearfix">
                            <div class="column three-fourth full-width m-b-0">
                                <h4>Cart</h4>
                            </div>
                            
                            <div class="column three-fourth" style="width: 69%">';

$total_amount_shipping = 0;
$total_shipping = 0;
$total_weight = 0;
$total_quantity = 0;
$total_amount = 0;

// Set city id company
$get_city_company_query = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = 'hometown' LIMIT 0,1;");
$row_city_company = mysql_fetch_assoc($get_city_company_query);

if ($loggedin) {

    // Set member
    $member_query = mysql_query("SELECT * FROM `member` WHERE `id_member` = '" . $loggedin["id_member"] . "' LIMIT 0,1;");
    $row_member = mysql_fetch_array($member_query);

    // Check session guest
    $guest = isset($_SESSION['guest']) ? $_SESSION['guest'] : [];

    // Set shipping
    if ($row_member['idkota']) {

        // Set Kota
        $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '" . $row_member["idkota"] . "' AND `level` = '0' LIMIT 0,1;");
        $row_city = mysql_fetch_array($city_query);
    }

    // Get cart
    $cart_query = mysql_query("SELECT * FROM `cart` WHERE ISNULL(id_transaction) 
        AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0' ORDER BY `id_cart` DESC;");

    $key = 0;
    while ($row_cart = mysql_fetch_array($cart_query)) {

        // check cart is custom or no
        if (!$row_cart['is_custom_cart']) {

            // Set item
            $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $row_cart["id_item"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_array($item_query);

            // Set photo
            $photo_query = mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $row_item["id_item"] . "' AND `level` = '0' ORDER BY `id_item` ASC LIMIT 0,1");
            $row_photo = mysql_fetch_array($photo_query);

        } else {

            // Set collection
            $collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '" . $row_cart["id_item"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_array($collection_query);

        }

        $content .= '
                                <div class="wrap mcb-wrap one valign-top clearfix bg-white m-b-10 border-grey box-shadow-hover">
                                    <div class="column one-fifth the_content_wrapper m-b-5 m-t-5">';

        $content .= ($row_cart['is_custom_cart'] ?
            '<img src="custom/public/images/custom_cart/' . $row_item['image'] . '" />' :
            '<img src="../admin/images/product/150/thumb_' . $row_photo['photo'] . '" />');

        $content .= '
                                    </div>
                                    <div class="column one-second the_content_wrapper m-t-35 p-l-0 b-r-grey m-b-10 p-r-5" style="width: 45%">
                                        <h4 class="title m-t-0 m-b-5 break-word fs-17">' . (!$row_cart['is_custom_cart'] ? $row_item['title'] : 'Custom Wetsuit') . '</h4>
                                        <p class="fs-18 bold m-b-5 fs-13">
                                            <span class="woocommerce-Price-amount text-blue">
                                                <span class="woocommerce-Price-currencySymbol">' . $currency . '</span>
                                                ' . (($currency_code == CURRENCY_USD_CODE) ? $row_item['price'] : number_format(($row_item['price'] * $USDtoIDR), 0, '.', ',')) . '
                                            </span>
                                        </p>
                                        
                                        <div class="quantity">
                                            <label class="text-grey fw-500 fs-13">Quantity</label>
                                            <span class="woocommerce-Price-amount"><b>' . $row_cart['qty'] . '</b></span>
                                        </div>
                                    </div>
                                    <div class="column one-fifth the_content_wrapper m-t-35 p-l-0" style="width: 29%">
                                        <p class="fs-13 fw-500 m-b-0 text-grey">Amount</p>
                                        <p class="fs-14 fw-600 text-black break-word">
                                            <span class="woocommerce-Price-amount">
                                                <span class="woocommerce-Price-currencySymbol">' . $currency . '</span> 
                                                ' . (($currency_code == CURRENCY_USD_CODE) ? $row_cart['amount'] : number_format(($row_cart['amount'] * $USDtoIDR), 0, '.', ',')) . '
                                            </span>
                                        </p>
                                        <div class="full-width">
                                            <form action="" method="post">
                                                <input type="hidden" name="action" value="cart">
                                                <input type="hidden" name="id_cart" value="' . $row_cart['id_cart'] . '">
                                                <button type="submit" class="btn-red-light pull-left m-l-5 fs-12" name="remove">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                               </div>';

        // Set weight
        $weight = (!$row_cart['is_custom_cart'] ? ($row_cart['qty'] * $row_item['weight']) : ($row_cart['qty'] * get_price('default-weight-custom-item')));

        // Set total weight
        $total_weight = round(($total_weight + $weight), 2);

        // Set quantity amount
        $total_quantity = ($total_quantity + $row_cart['qty']);
        $total_amount = $total_amount + (($currency_code == CURRENCY_USD_CODE) ? $row_cart['amount'] : $row_cart['amount'] * $USDtoIDR);

        $key++;
    }

    if ($total_amount != 0) {

        // content for shipping
        $content .= '
                                <div class="wrap mcb-wrap one valign-top clearfix bg-white m-l-0 m-r-0 m-b-10 p-l-15 p-t-35 p-r-0 border-grey box-shadow-hover">
                                    <div class="column one-second the_content_wrapper m-t-5 p-l-0 b-r-grey m-b-20 p-r-5" style="width: 45%">
                                        <p class="fs-14 fw-600 text-black m-b-0">Shipping Address</p>
                                        <label class="fs-11 fw-500 m-b-0">Province <span class="text-black fw-600">*</span></label>
                                        <p class="fs-13 text-black fw-600 m-b-0">
                                            <select name="selectItem" class="m-b-0 full-width" id="province" onchange="changeProvince()">
                                                <option hidden>province</option>';

        // Get city
        $get_province = get_province();

        // Set results
        $result_provinces = $get_province->rajaongkir->results;

        foreach ($result_provinces as $result_province) {

            // Set selected province
            $selected_province = (isset($row_member['idpropinsi']) ? (($result_province->province_id == $row_member['idpropinsi']) ? 'selected' : '') : '');

            // Set option
            $content .= '<option value="' . $result_province->province_id . '" ' . $selected_province . '>' . $result_province->province . '</option>';

        }

        $content .= '
                                            </select>
                                        </p>
                                        
                                        <label class="fs-11 fw-500 m-b-0">City <span class="text-black fw-600">*</span></label>
                                        <p class="fs-13 text-black fw-600">
                                            <select name="selectItem" class="m-b-0 full-width" id="city" onchange="changeCity()">
                                                <option hidden>city</option>';

        // Set parameter
        $parameter_city = isset($row_member['idpropinsi']) ? [
            'province' => $row_member['idpropinsi']
        ] : [];

        // Get city
        $get_city = get_city($parameter_city);

        // Set results
        $result_cities = $get_city->rajaongkir->results;

        foreach ($result_cities as $result_city) {

            // Set selected province
            $selected_city = (isset($row_member['idkota']) ? (($result_city->city_id == $row_member['idkota']) ? 'selected' : '') : '');

            // Set option
            $content .= '<option value="' . $result_city->city_id . '" ' . $selected_city . '>' . $result_city->city_name . '</option>';

        }

        $content .= '
                                            
                                            </select>
                                        </p>
                                    </div>
                                    <div class="column one-fifth the_content_wrapper m-t-5 p-l-0 p-r-0" style="width: 48%">
                                        <p class="fs-14 fw-600 text-black m-b-0">Delivery Service</p>
                                        <label class="fs-11 fw-500 m-b-0">Courier <span class="text-black fw-600">*</span></label>
                                        <div class="full-width clearfix p-b-10 m-b-10">
                                            <p class="fs-13 text-black fw-600 p-r-0 pull-left" style="width: 50%">
                                                <select name="selectItem" class="m-b-0 full-width" id="courier" onchange="changeCourier()" ' . (isset($row_member['idkota']) ? '' : 'disabled') . '>
                                                    <option hidden>Courier</option>';

        if (isset($row_member['idkota'])) {

            // Set Couriers
            $couriers = get_couriers();

            foreach ($couriers as $courier) {

                // Set selected courier
                $selected_courier = (isset($guest['courier']) ? (($guest['courier'] == $courier['code']) ? 'selected' : '') : '');

                // Set option
                $content .= '<option value="' . $courier['code'] . '" ' . $selected_courier . '>' . $courier['name'] . '</option>';
            }

        }

        $content .= '
                                                </select>
                                            </p>
                                            <p class="fs-13 text-black fw-600 p-r-0 pull-right" style="width: 48%">
                                                <select name="selectItem" class="m-b-0 full-width" id="service_courier" onchange="changeService()" ' . (isset($guest['courier']) ? '' : 'disabled') . '>
                                                    <option hidden>Service Courier</option>';

        if (isset($guest['courier'])) {

            // Set parameter request
            $parameter_cost = [
                'origin' => $row_city_company['value'],
                'destination' => $row_member['idkota'],
                'weight' => (($total_weight < 1) ? 1 : $total_weight),
                'courier' => $guest['courier']
            ];

            // Get courier
            $get_cost = get_cost($parameter_cost);

            if ($get_cost->rajaongkir->status->code == 200) {

                foreach ($get_cost->rajaongkir->results[0]->costs as $cost) {

                    // Set selected courier
                    $selected_service = (isset($guest['service']) ? (($guest['service'] == $cost->service) ? 'selected' : '') : '');

                    // Set option
                    $content .= '<option value="' . $cost->service . '" ' . $selected_service . '>' . $cost->service . '</option>';

                }

            }

        }

        $content .= '
                                                </select>
                                            </p>
                                        </div>
                                        <p class="fs-14 text-black fw-600 pull-left m-b-0" id="courier_cost">';

        $courier_cost = 0;
        if (isset($guest['courier_cost'])) {

            // Set courier cost
            $courier_cost = (($currency_code == CURRENCY_USD_CODE) ? round(($guest['courier_cost'] / $USDtoIDR), 2) : $guest['courier_cost']);

            $content .= '
                                            <span class="woocommerce-Price-currencySymbol">' . $currency . '</span> 
                                            <span class="woocommerce-Price-amount"> ' . number_format($courier_cost, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</span>';

        }

        $content .= '
                                        </p>
                                    </div>
                                </div>';

        // Set total
        $total_shipping = (((round($total_weight) < 1) ? 1 : round($total_weight)) * (float)$courier_cost);
        $total_amount_shipping = $total_amount + (!empty($total_shipping) ? $total_shipping : 0);

    }

} else {

    if (!empty($_SESSION['cart_item'])) {

        // Check session guest
        $guest = isset($_SESSION['guest']) ? $_SESSION['guest'] : [];

        // Set shipping
        if (isset($guest['id_city'])) {

            // Set Kota
            $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '" . $guest["id_city"] . "' AND `level` = '0' LIMIT 0,1;");
            $row_city = mysql_fetch_array($city_query);
        }

        foreach ($_SESSION['cart_item'] as $key => $cart_item) {

            // check cart is custom or no
            if (!$cart_item['is_custom_cart']) {

                // Set item
                $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $cart_item["id_item"] . "' LIMIT 0,1;");
                $row_item = mysql_fetch_array($item_query);

                // Set photo
                $photo_query = mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $row_item["id_item"] . "' AND `level` = '0' ORDER BY `id_item` ASC LIMIT 0,1");
                $row_photo = mysql_fetch_array($photo_query);

            } else {

                // Set collection
                $row_item = $cart_item['collection'];

            }

            $content .= '
                                <div class="wrap mcb-wrap one valign-top clearfix bg-white m-b-10 border-grey box-shadow-hover">
                                    <div class="column one-fifth the_content_wrapper m-b-5 m-t-5">';

            $content .= ($cart_item['is_custom_cart'] ?
                '<img src="custom/public/images/custom_cart/' . $row_item['image'] . '" />' :
                '<img src="../admin/images/product/150/thumb_' . $row_photo['photo'] . '" />');

            $content .= '
                                    </div>
                                    <div class="column one-second the_content_wrapper m-t-35 p-l-0 b-r-grey m-b-10 p-r-5" style="width: 45%">
                                        <h4 class="title m-t-0 m-b-5 break-word fs-17">' . (!$cart_item['is_custom_cart'] ? $row_item['title'] : 'Custom Wetsuit') . '</h4>
                                        <p class="fs-18 bold m-b-5 fs-13">
                                            <span class="woocommerce-Price-amount text-blue">
                                                <span class="woocommerce-Price-currencySymbol">' . $currency . '</span>
                                                ' . (($currency_code == CURRENCY_USD_CODE) ? $row_item['price'] : number_format(($row_item['price'] * $USDtoIDR), 0, '.', ',')) . '
                                            </span>
                                        </p>
                                        
                                        <div class="quantity">
                                            <label class="text-grey fw-500 fs-13">Quantity</label>
                                            <span class="woocommerce-Price-amount"><b>' . $cart_item['quantity'] . '</b></span>
                                        </div>
                                    </div>
                                    <div class="column one-fifth the_content_wrapper m-t-35 p-l-0" style="width: 29%">
                                        <p class="fs-13 fw-500 m-b-0 text-grey">Amount</p>
                                        <p class="fs-14 fw-600 text-black break-word">
                                            <span class="woocommerce-Price-amount">
                                                <span class="woocommerce-Price-currencySymbol">' . $currency . '</span> 
                                                ' . (($currency_code == CURRENCY_USD_CODE) ? $cart_item['amount'] : number_format(($cart_item['amount'] * $USDtoIDR), 0, '.', ',')) . '
                                            </span>
                                        </p>
                                        <div class="full-width">
                                            <form action="" method="post">
                                                <input type="hidden" name="action" value="session">
                                                <input type="hidden" name="id_cart" value="' . $key . '">
                                                <button type="submit" class="btn-red-light pull-left m-l-5 fs-12" name="remove">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                               </div>';

            // Set weight
            $weight = (!$cart_item['is_custom_cart'] ? ($cart_item['quantity'] * $row_item['weight']) : ($cart_item['quantity'] * get_price('default-weight-custom-item')));

            // Set total weight
            $total_weight = round(($total_weight + $weight), 2);

            // Set quantity amount
            $total_quantity = ($total_quantity + $cart_item['quantity']);
            $total_amount = $total_amount + (($currency_code == CURRENCY_USD_CODE) ? $cart_item['amount'] : $cart_item['amount'] * $USDtoIDR);

            $key++;
        }

        // content for shipping
        $content .= '
                                <div class="wrap mcb-wrap one valign-top clearfix bg-white m-l-0 m-r-0 m-b-10 p-l-15 p-t-35 p-r-0 border-grey box-shadow-hover">
                                    <div class="column one-second the_content_wrapper m-t-5 p-l-0 b-r-grey m-b-20 p-r-5" style="width: 45%">
                                        <p class="fs-14 fw-600 text-black m-b-0">Shipping Address</p>
                                        <label class="fs-11 fw-500 m-b-0">Province <span class="text-black fw-600">*</span></label>
                                        <p class="fs-13 text-black fw-600 m-b-0">
                                            <select name="selectItem" class="m-b-0 full-width" id="province" onchange="changeProvince()">
                                                <option hidden>province</option>';

        // Get city
        $get_province = get_province();

        // Set results
        $result_provinces = $get_province->rajaongkir->results;

        foreach ($result_provinces as $result_province) {

            // Set selected province
            $selected_province = (isset($guest['id_province']) ? (($result_province->province_id == $guest['id_province']) ? 'selected' : '') : '');

            // Set option
            $content .= '<option value="' . $result_province->province_id . '" ' . $selected_province . '>' . $result_province->province . '</option>';

        }

        $content .= '
                                            </select>
                                        </p>
                                        
                                        <label class="fs-11 fw-500 m-b-0">City <span class="text-black fw-600">*</span></label>
                                        <p class="fs-13 text-black fw-600">
                                            <select name="selectItem" class="m-b-0 full-width" id="city" onchange="changeCity()">
                                                <option hidden>city</option>';

        // Set parameter
        $parameter_city = isset($guest['id_province']) ? [
            'province' => $guest['id_province']
        ] : [];

        // Get city
        $get_city = get_city($parameter_city);

        // Set results
        $result_cities = $get_city->rajaongkir->results;

        foreach ($result_cities as $result_city) {

            // Set selected province
            $selected_city = (isset($guest['id_city']) ? (($result_city->city_id == $guest['id_city']) ? 'selected' : '') : '');

            // Set option
            $content .= '<option value="' . $result_city->city_id . '" ' . $selected_city . '>' . $result_city->city_name . '</option>';

        }

        $content .= '
                                            
                                            </select>
                                        </p>
                                    </div>
                                    <div class="column one-fifth the_content_wrapper m-t-5 p-l-0 p-r-0" style="width: 48%">
                                        <p class="fs-14 fw-600 text-black m-b-0">Delivery Service</p>
                                        <label class="fs-11 fw-500 m-b-0">Courier <span class="text-black fw-600">*</span></label>
                                        <div class="full-width clearfix p-b-10 m-b-10">
                                            <p class="fs-13 text-black fw-600 p-r-0 pull-left" style="width: 50%">
                                                <select name="selectItem" class="m-b-0 full-width" id="courier" onchange="changeCourier()" ' . (isset($guest['id_city']) ? '' : 'disabled') . '>
                                                    <option hidden>Courier</option>';

        if (isset($guest['id_city'])) {

            // Set Couriers
            $couriers = get_couriers();

            foreach ($couriers as $courier) {

                // Set selected courier
                $selected_courier = (isset($guest['courier']) ? (($guest['courier'] == $courier['code']) ? 'selected' : '') : '');

                // Set option
                $content .= '<option value="' . $courier['code'] . '" ' . $selected_courier . '>' . $courier['name'] . '</option>';
            }

        }

        $content .= '
                                                </select>
                                            </p>
                                            <p class="fs-13 text-black fw-600 p-r-0 pull-right" style="width: 48%">
                                                <select name="selectItem" class="m-b-0 full-width" id="service_courier" onchange="changeService()" ' . (isset($guest['courier']) ? '' : 'disabled') . '>
                                                    <option hidden>Service Courier</option>';

        if (isset($guest['courier'])) {

            // Set parameter request
            $parameter_cost = [
                'origin' => $row_city_company['value'],
                'destination' => $guest['id_city'],
                'weight' => (($total_weight < 1) ? 1 : $total_weight),
                'courier' => $guest['courier']
            ];

            // Get courier
            $get_cost = get_cost($parameter_cost);

            if ($get_cost->rajaongkir->status->code == 200) {

                foreach ($get_cost->rajaongkir->results[0]->costs as $cost) {

                    // Set selected courier
                    $selected_service = (isset($guest['service']) ? (($guest['service'] == $cost->service) ? 'selected' : '') : '');

                    // Set option
                    $content .= '<option value="' . $cost->service . '" ' . $selected_service . '>' . $cost->service . '</option>';

                }

            }

        }

        $content .= '
                                                </select>
                                            </p>
                                        </div>
                                        <p class="fs-14 text-black fw-600 pull-left m-b-0" id="courier_cost">';

        $courier_cost = 0;
        if (isset($guest['courier_cost'])) {

            // Set courier cost
            $courier_cost = (($currency_code == CURRENCY_USD_CODE) ? round(($guest['courier_cost'] / $USDtoIDR), 2) : $guest['courier_cost']);

            $content .= '
                                            <span class="woocommerce-Price-currencySymbol">' . $currency . '</span> 
                                            <span class="woocommerce-Price-amount"> ' . number_format($courier_cost, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</span>';

        }

        $content .= '
                                        </p>
                                    </div>
                                </div>';

        // Set total
        $total_shipping = (((round($total_weight) < 1) ? 1 : round($total_weight)) * (float)$courier_cost);
        $total_amount_shipping = $total_amount + (!empty($total_shipping) ? $total_shipping : 0);

    }

}

$content .= '               </div>';

if ($total_amount != 0) {

    $content .= '
                            <input type="hidden" value="' . $row_city_company['value'] . '" id="id_city_company">
                            <div class="column one-fourth bg-white m-l-0 m-r-0 p-l-15 p-t-35 p-r-15 border-grey" style="width: 25%">
                            
                                <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                    <label class="fs-11 fw-500 m-b-0 pull-left">Total Item</label>
                                    <p class="fs-14 text-black fw-600 pull-right m-b-0">' . $total_quantity . '</p>
                                </div>
                                
                                <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                    <label class="fs-11 fw-500 m-b-0 pull-left">Total Weight <span class="text-black">(Kg)</span></label>
                                    <p class="fs-14 text-black fw-600 pull-right m-b-0">
                                        <span class="woocommerce-Price-amount">' . $total_weight . '</span>
                                        <input type="hidden" value="' . $total_weight . '" id="weight">
                                    </p>
                                </div>
                                
                                <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                    <label class="fs-11 fw-500 m-b-0 pull-left">Shipping Costs</label>
                                    <p class="fs-14 text-black fw-600 pull-right m-b-0">
                                        <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">' . (isset($guest['courier_cost']) ? $currency : '') . '</span> ' . (!empty($total_shipping) ? number_format($total_shipping, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') : 'You have not Courier') . '</span>
                                    </p>
                                </div>

                                <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                    <label class="fs-11 fw-500 m-b-0 pull-left">Subtotal</label>
                                    <p class="fs-14 text-black fw-600 pull-right m-b-0">
                                        <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . number_format($total_amount, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</span>
                                    </p>
                                </div>
                                        
                                <div class="full-width clearfix p-b-5 m-b-20">
                                    <label class="fs-14 fw-500 m-b-0 pull-left">Total</label>
                                    <p class="fs-16 text-black fw-600 pull-right m-b-0">
                                        <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . number_format($total_amount_shipping, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</span>
                                    </p>
                                </div>
                                        
                                <div class="full-width m-b-15">
                                    <a href="' . ($loggedin ? 'checkout.php' : 'login_checkout.php?action=checkout') . '" class="btn btn-blue-light full-width wrap mcb-wrap" '.(empty($total_shipping) ? 'style="pointer-events: none; cursor: default; background-color: lightskyblue"' : '').'>Check Out</a>
                                    <p class="fs-14 text-black">
                                        <span class="fs-12 text-red fw-700 m-b-0">*</span>
                                        please choose courier and service before checkout
                                    </p>
                                </div>
                                                                    
                                <div class="full-width wrap mcb-wrap  clearfix border-grey padding-15 m-b-10">
                                    <p class="fs-15 text-black text-center m-b-10 fw-600">You can transfer to us via</p>
                                    
                                    <p class="m-b-0">
                                        <img src="images/mandiri.jpg" width="40%">
                                    </p>
                                    <p class="fs-18 fw-500 text-black b-b-grey p-b-10">
                                        145-0010-897-318
                                    </p>
                                    
                                    <p class="m-b-0">
                                        <img src="images/bca.jpg" width="32%">
                                    </p>
                                    <p class="fs-18 fw-500 text-black">
                                        146-668-4848
                                    </p>
                                    
                                </div>
                                                                    
                                <div class="full-width">
                                    <p class="fs-12 text-red fw-700 m-b-0">Noted *</p>
                                    <p class="fs-14 text-black">
                                        It is expected to save proof of transfer, to be uploaded as proof of payment
                                    </p>
                                    
                                    <p class="fs-13 text-black m-b-0">
                                        Contact us for more info
                                    </p>
                                    <p class="fs-16 text-black fw-600 m-b-40">
                                        +62 361 27 11 99
                                    </p>
                                    
                                </div>
                            </div>';

} else {

    $content .= '<div class="column three-fourth wrap mcb-wrap one width-90 p-l-10">
                    <div class="wrap mcb-wrap one valign-top clearfix bg-white m-b-10 border-grey padding-30">
                        <p class="fs-20 fw-600 text-black m-b-0">Your cart is empty!</p>
                        <p class="fs-4">Please Go to Page Collection and add new items</p>
                        <a href="list_product.php?id_cats=9" class="btn-blue-light bg-black bg-black-hover">Go to Collection</a>
                    </div>
                </div>';

}

$content .= '
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

$plugin = '
    <script>
        
        function changeProvince() {
            var id_province = jQuery("#province").val();
            
            jQuery.ajax({
                type: "POST",
                url: "member/change_city.php",
                data: {
                    action: "change_province",
                    id_province: id_province
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == "error") {
                        alert(data.msg);
                    } else {
                        
                        jQuery("#courier_cost").html("");
                        
                        var courier = jQuery("#courier");
                        courier.html("").attr("disabled", true);
                        courier.append("<option hidden>Courier</option>");
                        
                        var service_courier = jQuery("#service_courier");
                        service_courier.html("").attr("disabled", true);
                        service_courier.append("<option hidden>Service Courier</option>");
                        
                        var city = jQuery("#city");
                        city.html("");
                        city.append("<option hidden>city</option>");
                        
                        for (var i = 0; i < data.results.length; i++) {
                            city.append(
                                \'<option value="\' + data.results[i].city_id + \'">\' + data.results[i].city_name + \'</option>\'
                            );
                        }
                    }
                }
            });
        }
        
        function changeCity() {
            var id_city = jQuery("#city").val();
            
            jQuery.ajax({
                type: "POST",
                url: "member/change_city.php",
                data: {
                    action: "change_city",
                    id_city: id_city
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == "error") {
                        alert(data.msg);
                    } else {
                        
                        jQuery("#courier_cost").html("");
                        
                        var service_courier = jQuery("#service_courier");
                        service_courier.html("").attr("disabled", true);
                        service_courier.append("<option hidden>Service Courier</option>");
                        
                        var courier = jQuery("#courier");
                        courier.html("").attr("disabled", false);
                        courier.append("<option hidden>Courier</option>");
                        
                        for (var i = 0; i < data.results.length; i++) {
                            courier.append(
                                \'<option value="\' + data.results[i].code + \'">\' + data.results[i].name + \'</option>\'
                            );
                        }
                    }
                }
            });
        }   
        
        function changeCourier() {
            var id_city_company = jQuery("#id_city_company").val();
            var id_city = jQuery("#city").val();
            var weight = jQuery("#weight").val();
            var courier = jQuery("#courier").val();
            
            jQuery.ajax({
                type: "POST",
                url: "member/change_courier.php",
                data: {
                    action: "change_courier",
                    id_city_company: id_city_company,
                    id_city: id_city,
                    weight: weight,
                    courier: courier
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == "error") {
                        alert(data.msg);
                    } else {
                        
                        jQuery("#courier_cost").html("");
                        
                        var service_courier = jQuery("#service_courier");
                        service_courier.html("").attr("disabled", false);
                        service_courier.append("<option hidden>Service Courier</option>");
                        
                        var costs = data.results.rajaongkir.results[0].costs;
                        for (var i = 0; i < costs.length; i++) {
                            service_courier.append(
                                \'<option value="\' + costs[i].service + \'">\' + costs[i].service + \'</option>\'
                            );
                        }
                    }
                }
            });
        }
        
        function changeService() {
            var service_courier = jQuery("#service_courier").val();
            var id_city_company = jQuery("#id_city_company").val();
            var id_city = jQuery("#city").val();
            var weight = jQuery("#weight").val();
            var courier = jQuery("#courier").val();
            
            jQuery.ajax({
                type: "POST",
                url: "member/change_courier.php",
                data: {
                    action: "change_service_courier",
                    service_courier: service_courier,
                    id_city_company: id_city_company,
                    id_city: id_city,
                    weight: weight,
                    courier: courier
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == "error") {
                        alert(data.msg);
                    } else {
                        console.log(data);
                        window.location.reload();
                    }
                }
            });
        }
        
    </script>';

$template = admin_template($content, $titlebar, $titlepage = "", $user = "", $menu, $plugin);

echo $template;

?>