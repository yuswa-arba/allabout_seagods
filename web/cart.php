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

$content = '
    <div class="sidebar four columns">
         <div class="widget-area">
             <div class="widget woocommerce widget_product_categories">
                 <h3>CATEGORIES</h3>
                 <ul class="product-categories">';

$sql_caregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '0' AND `level` = '0' ORDER BY `no_order` ASC  ;");

while ($row_category = mysql_fetch_array($sql_caregory)) {

    $content .= '<li><a href="list_product.php?id_cats=' . $row_category['id_cat'] . '">' . $row_category["category"] . '';

    $sql_subcaregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '$row_category[id_cat]' AND `level` = '0' ORDER BY `no_order` ASC  ;");
    while ($row_sucategory = mysql_fetch_array($sql_subcaregory)) {

        $content .= '<ul><li><a href="list_product.php?id_cat=' . $row_sucategory['id_cat'] . '">' . $row_sucategory["category"] . '</a></li></ul>';

    }

    $content .= '</li>';
}

$content .= '
                </ul>
            </div>
        </div>
    </div>
    <div id="Content">
        <div class="content_wrapper clearfix">
            <div class="sections_group">
                <div class="section">
                    <div class="section_wrapper clearfix">
                        <div class="items_group clearfix">
                            <div class="column one woocommerce-content">
                                <div class="product has-post-thumbnail">
                                    <div class="post-content">
                                        <div class="section mcb-section" style="padding-top:0px; padding-bottom:20px">
                                            <div class="section_wrapper mcb-section-inner">
                                                <div class="wrap mcb-wrap one valign-top clearfix">
                                                    <div class="mcb-wrap-inner">
                                                        <table class="table table-condensed">';

$total_amount_shipping = 0;
$total_shipping = 0;
$total_weight = 0;
$total_quantity = 0;
$total_amount = 0;
if ($loggedin) {

    // Set member
    $member_query = mysql_query("SELECT * FROM `member` WHERE `id_member` = '" . $loggedin["id_member"] . "' LIMIT 0,1;");
    $row_member = mysql_fetch_array($member_query);

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
                                                            <tr>
                                                                <td style="width: 20%;">';

        $content .= ($row_cart['is_custom_cart'] ?
            '<img src="custom/public/images/custom_cart/' . $row_item['image'] . '" style="width: 100px;" />' :
            '<img src="../admin/images/product/150/thumb_' . $row_photo['photo'] . '" style="width: 100px;" />');

        $content .= '
                                                                </td>
                                                                <td >
                                                                    <span>' . (!$row_cart['is_custom_cart'] ? $row_item['title'] : 'Custom Wetsuit') . '</span>
                                                                </td>
                                                                <td style="width: 10%;">
                                                                    <input type="number" onchange="changeQuantity(' . $row_cart['id_cart'] . ', ' . $key . ')" id="quantity' . $key . '" class="form-control" style="width: 60px;" value="' . $row_cart['qty'] . '">
                                                                </td>
                                                                <td style="width: 30%;">
                                                                    <h4 class="text-primary no-margin font-montserrat">' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? $row_cart['amount'] : number_format(($row_cart['amount'] * $USDtoIDR), 0, '.', ',')) . '</h4>
                                                                </td>
                                                            </tr>';

        // Set weight
        $weight = (!$row_cart['is_custom_cart'] ? ($row_cart['qty'] * $row_item['weight']) : ($row_cart['qty'] * get_price('default-weight-custom-item')));

        // Set total weight
        $total_weight = round(($total_weight + $weight), 2);

        // Set quantity amount
        $total_quantity = ($total_quantity + $row_cart['qty']);
        $total_amount = $total_amount + (($currency_code == CURRENCY_USD_CODE) ? $row_cart['amount'] : $row_cart['amount'] * $USDtoIDR);

        $key++;
    }

    // Shipping
    $shipping = ($row_member['idkota']) ? (($currency_code == CURRENCY_USD_CODE) ? round(($row_city['ongkos_kirim'] / $USDtoIDR), 2) : $row_city['ongkos_kirim']) : 0;

    // Set total
    $total_shipping = (((round($total_weight) < 1) ? 1 : round($total_weight)) * (float)$shipping);
    $total_amount_shipping = $total_amount + (!empty($total_shipping) ? $total_shipping : 0);

} else {

    if (!empty($_SESSION['cart_item'])) {

        // Check session guest
        $guest = isset($_SESSION['guest']) ? $_SESSION['guest'] : [];

        // Set shipping
        if (isset($guest['idkota'])) {

            // Set Kota
            $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '" . $guest["idkota"] . "' AND `level` = '0' LIMIT 0,1;");
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
                                                            <tr>
                                                                <td style="width: 20%;">';

            $content .= ($cart_item['is_custom_cart'] ?
                '<img src="custom/public/images/custom_cart/' . $row_item['image'] . '" style="width: 100px;" />' :
                '<img src="../admin/images/product/150/thumb_' . $row_photo['photo'] . '" style="width: 100px;" />');

            $content .= '
                                                                </td>
                                                                <td >
                                                                    <span>' . (!$cart_item['is_custom_cart'] ? $row_item['title'] : 'Custom Wetsuit') . '</span>
                                                                </td>
                                                                <td style="width: 10%;">
                                                                    <input type="number" onchange="changeQuantity(' . null . ', ' . $key . ')" id="quantity' . $key . '" class="form-control" style="width: 60px;" value="' . $cart_item['quantity'] . '">
                                                                </td>
                                                                <td style="width: 30%;">
                                                                    <h4 class="text-primary no-margin font-montserrat">' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? round($cart_item['amount'], 2) : number_format(($cart_item['amount'] * $USDtoIDR), 2, '.', ',')) . '</h4>
                                                                </td>
                                                            </tr>';

            // Set weight
            $weight = (!$cart_item['is_custom_cart'] ? ($cart_item['quantity'] * $row_item['weight']) : ($cart_item['quantity'] * get_price('default-weight-custom-item')));

            // Set total weight
            $total_weight = round(($total_weight + $weight), 2);

            // Set quantity amount
            $total_quantity = ($total_quantity + $cart_item['quantity']);
            $total_amount = $total_amount + (($currency_code == CURRENCY_USD_CODE) ? $cart_item['amount'] : $cart_item['amount'] * $USDtoIDR);

        }

        // Shipping
        $shipping = (isset($guest['idkota'])) ? (($currency_code == CURRENCY_USD_CODE) ? round(($row_city['ongkos_kirim'] / $USDtoIDR), 2) : $row_city['ongkos_kirim']) : 0;

        // Set total
        $total_shipping = (((round($total_weight) < 1) ? 1 : round($total_weight)) * (float)$shipping);
        $total_amount_shipping = $total_amount + (!empty($total_shipping) ? $total_shipping : 0);

    }

}

if ($total_amount_shipping != 0) {

    $content .= '
                                                            <tr>
                                                                <td colspan="2" style="text-align: left;"><b>Shipping</b></td>
                                                                <td><b>' . $total_weight . ' Kg</b></td>';

    if (isset($row_city['idKota'])) {
        $content .= '
                                                                <td>' . (!empty($total_shipping) ? '<h4>' . $currency . ' ' . number_format($total_shipping, 2, '.', ',') . '</h4>' : 'You have not registered your city') . '</td>';
    } else {
        $content .= '
                                                                <td>
                                                                    <select class="full-width" data-placeholder="Select Category" id="province" data-init-plugin="select2" onchange="changeProvince()">
                                                                        <option>Choose province for shipping</option>';

        $all_province_query = mysql_query("SELECT * FROM `provinsi`;");
        while ($row_all_province = mysql_fetch_array($all_province_query)) {
            $content .= '<option value="' . $row_all_province['idProvinsi'] . '">' . $row_all_province['namaProvinsi'] . '</option>';
        }

        $content .= '
                                                                    </select>
                                                                    <select class="full-width" data-placeholder="Select Category" id="city" data-init-plugin="select2" onchange="changeCity()">
                                                                        <option>Choose city for shipping</option>';

        $all_city_query = mysql_query("SELECT * FROM `kota` WHERE `level` = '0';");
        while ($row_all_city = mysql_fetch_array($all_city_query)) {
            $content .= '<option value="' . $row_all_city['idKota'] . '">' . $row_all_city['namaKota'] . '</option>';
        }

        $content .= '
                                                                    </select>
                                                                </td>';
    }

    $content .= '
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" style="text-align: left;"><b>Total</b></td>
                                                                <td><b>' . $total_quantity . '</b></td>
                                                                <td><h4>' . $currency . ' ' . number_format($total_amount_shipping, 2, '.', ',') . '</h4></td>
                                                            </tr>';

}

$content .= '
                                                        </table>
                                                    </div>
                                                <a class="btn btn-primary" href="' . ($loggedin ? 'checkout.php' : 'login_checkout.php') . '">Checkout</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            console.log(id_province);
            
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
                        var city = jQuery("#city");
                        city.html("");
                        city.append("<option>Choose city for shipping</option>");
                        for (var i = 0; i < data.results.length; i++) {
                            city.append(
                                \'<option value="\' + data.results[i].idKota + \'">\' + data.results[i].namaKota + \'</option>\'
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
                        alert(data.msg);
                        window.location.reload();
                    }
                }
            });
        }    
    </script>';

$template = admin_template($content, $titlebar, $titlepage = "", $user = "", $menu, $plugin);

echo $template;

?>