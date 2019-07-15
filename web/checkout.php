<?php
session_start();
include("config/configuration.php");
include("config/currency_types.php");
include("config/shipping/action_raja_ongkir.php");
include("config/shipping/province_city.php");

if ($loggedin = logged_in()) {

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

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    // Default currency
    $currency_code = CURRENCY_IDR_CODE;

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

    $user = '' . $loggedin['username'] . '';
    $id_member = '' . $loggedin['id_member'] . '';

    if (isset($_GET['action']) && $_GET['action'] == 'update_member') {
        echo json_encode(['testing' => 'testing']);
    }

    if ($finish = isset($_POST["finish"]) ? $_POST["finish"] : "") {
        if ($finish == "Finish") {
            $sql_checkout = mysql_query("SELECT * FROM  `transaction` ORDER BY `id_transaction` DESC lIMIT 0,1");
            $harga_total = isset($_POST["total"]) ? $_POST["total"] : "";
            $row_checkout = mysql_fetch_array($sql_checkout);
            $id_transaction = $row_checkout["id_transaction"] + 1;
            $sql = "INSERT INTO `transaction` (`id_transaction` , `kode_transaction` , `id_member`, `status` ,`konfirm`,`total`,`date_add`,`date_upd`) VALUES ('','INV/" . $loggedin["id_member"] . "/" . date("Ymd") . "/" . rand_numb($id_transaction, 5) . "',
            '" . $loggedin["id_member"] . "' , 'prosees' , 'not confirmated' , '$harga_total', NOW(), NOW());";

            $sql1 = "UPDATE `cart` set `id_transaction` ='$id_transaction', `date_upd` = NOW() , `level` = '1' 
                 WHERE `id_member` = '$id_member' AND `level` = '0';";

            mysql_query($sql) or die(mysql_error());
            mysql_query($sql1) or die(mysql_error());

            unset($_SESSION["cart_item"]);
            echo "<script language='JavaScript'>
                alert('Your Transaction will be procced');
                location.href = 'info_pembayaran.php';
              </script>";
        }
    }


    // Set guest from session
    $guest = [];

    $row_member = array();
    $row_provinsi = array();
    $row_kota = array();
    $update_member = true;
    $kurs_IDR = 0;
    $kurs = 0;

    // Get cart
    $query_cart = mysql_query("SELECT * FROM `cart` 
          WHERE ISNULL(id_transaction) AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0';");
    $count_cart = mysql_num_rows($query_cart) > 0;

    if ($count_cart) {

        // Check session guest
        $guest = isset($_SESSION['customer']) ? $_SESSION['customer'] : [];

        $query_member = mysql_query("SELECT `member`.*, `users`.`email` FROM `member`, `users`
            WHERE `member`.`id_member` = `users`.`id_member` 
            AND `member`.`id_member` = '" . $loggedin["id_member"] . "' LIMIT 0,1;");
        $row_member = mysql_fetch_array($query_member);

        $query_country = mysql_query("SELECT * FROM `countries`
            WHERE `id_country` = '" . $row_member["idCountry"] . "' LIMIT 0,1;");
        $row_country = mysql_fetch_array($query_country);

        // Set country ID
        $id_country = ($row_member['idCountry'] ? $row_member['idCountry'] : 'ID');

        $query_provinsi = mysql_query("SELECT * FROM `provinsi`
            WHERE `idProvinsi` = '" . $row_member["idpropinsi"] . "' AND `idCountry` = '" . $id_country . "' LIMIT 0,1;");
        $row_provinsi = mysql_fetch_array($query_provinsi);

        $query_kota = mysql_query("SELECT * FROM `kota`
            WHERE `idKota` = '" . $row_member["idkota"] . "' LIMIT 0,1;");
        $row_kota = mysql_fetch_array($query_kota);
        $kurs_IDR = (isset($guest['courier_cost']) ? $guest['courier_cost'] : 0);
        $kurs = (isset($guest['courier_cost']) ? (($currency_code == CURRENCY_USD_CODE) ? round(($guest['courier_cost'] / $USDtoIDR), 2) : $guest['courier_cost']) : 0);

        if ($row_member['firstname'] && $row_member['lastname'] && $row_member['alamat'] && $row_member['kode_pos'] &&
            $row_member['idCountry'] && $row_member['idpropinsi'] && $row_member['idkota']
        ) {
            $update_member = false;
        }
    }

    // Set city id company
    $get_city_company_query = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = 'hometown' LIMIT 0,1;");
    $row_city_company = mysql_fetch_assoc($get_city_company_query);

    // Get province
    $get_province = get_province();

    // Get province
    $get_city = get_city();

} else {
    echo "<script language='JavaScript'>
        alert('You need to login first before transaction');
        location.href = 'list_product.php';
      </script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <title>Checkout - SeaGods Wetsuit</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"/>
    <link rel="apple-touch-icon" href="member/pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="member/pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="member/pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="member/pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="member/favicon.ico"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link href="member/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
    <link href="member/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="member/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="member/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="member/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="member/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="member/pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="member/pages/css/pages.css" rel="stylesheet" type="text/css"/>
</head>
<body class="fixed-header horizontal-menu horizontal-app-menu ">
<!-- START HEADER -->
<div class="header">
    <div class="container">

        <div class="header-inner header-md-height">
            <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-menu" data-toggle="horizontal-menu">
            </a>
            <div class="">
                <a href="#" class="" data-toggle="search"><i class=""></i><span class="bold"></span></a>
            </div>
            <div class="d-flex align-items-center">
                <!-- START User Info-->
                <div class="pull-left p-r-10 fs-14 font-heading hidden-md-down">
                    <span class="semi-bold">Member</span> <span class=""><?php echo $user; ?></span>
                </div>
                <div class="dropdown pull-right sm-m-r-5">
                    <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <span>
                            <img src="member/assets/img/profiles/avatar.jpg" alt="" width="32" height="32">
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                        <a href="#" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
                        <a href="#" class="dropdown-item"><i class="pg-outdent"></i> Feedback</a>
                        <a href="#" class="dropdown-item"><i class="pg-signals"></i> Help</a>
                        <a href="logout.php" class="clearfix bg-master-lighter dropdown-item">
                            <span class="pull-left">Logout</span>
                            <span class="pull-right"><i class="pg-power"></i></span>
                        </a>
                    </div>
                </div>
                <!-- END User Info
                <a href="#" class="header-icon pg pg-alt_menu btn-link m-l-10 sm-no-margin d-inline-block" data-toggle="quickview" data-toggle-element="#quickview"></a>
                -->
            </div>
        </div>
        <div class="header-inner justify-content-start header-lg-height title-bar">
            <div class="brand inline align-self-end">
                <img src="member/assets/img/s-logo.png" alt="logo" data-src="member/assets/img/s-logo.png"
                     data-src-retina="member/assets/img/s-logo.png" height="20">
            </div>
            <h2 class="page-title align-self-end">
                Checkout
            </h2>
        </div>

        <div class="menu-bar header-sm-height" data-pages-init='horizontal-menu' data-hide-extra-li="0">
            <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-menu" data-toggle="horizontal-menu">
            </a>
            <ul>
                <li class=" active">
                    <a href="member/index.php">Dashboard</a>
                </li>
                <li>
                    <a href="member/profile.php"><span class="title">Profile</span></a>
                </li>
                <li class="">
                    <a href="member/list-transaction.php">Transaction List</a>
                </li>
                <li>
                    <a href="member/wishlist.php"><span class="title">Wishlist</span></a>
                </li>
                <li>
                    <a href="member/custommade.php"><span class="title">Custom Made</span></a>
                </li>
                <li>
                    <a href="index.php" target="_blank"><span class="title">Homepage</span></a>
                </li>
                <li>
                    <a href="list_product.php" target="_blank"><span class="title">Collection</span></a>
                </li>
                <li>
                    <a href="cart.php" target="_blank"><span class="title">My Cart</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="page-container ">
    <!-- START PAGE CONTENT WRAPPER -->
    <div class="page-content-wrapper ">
        <!-- START PAGE CONTENT -->
        <div class="content ">
            <!-- START CONTAINER FLUID -->
            <div class="container container-fixed-lg">
                <div id="rootwizard" class="m-t-50">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator nav-stack-sm no-click"
                        role="tablist"
                        data-init-reponsive-tabs="dropdownfx">
                        <li class="nav-item">
                            <a class="active" id="item-your-card" data-toggle="tab" href="#" role="tab"><i
                                        class="fa fa-shopping-cart tab-icon"></i> <span>Your cart</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="" id="item-member-information" data-toggle="tab" href="#" role="tab"><i
                                        class="fa fa-m tab-icon"></i>
                                <span>Member information</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="" id="item-shipping-address" data-toggle="tab" href="#" role="tab"><i
                                        class="fa fa-m tab-icon"></i>
                                <span>Shipping address</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="" id="item-payment" data-toggle="tab" href="#" role="tab"><i
                                        class="fa fa-credit-card-alt tab-icon"></i>
                                <span>Payment</span></a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div class="tab-pane padding-20 sm-no-padding slide-left active" id="tab-your-cart">
                            <div class="row row-same-height">
                                <div class="col-md-5 b-r b-dashed b-grey sm-b-b">
                                    <?php
                                    if ($count_cart != 0) { ?>
                                        <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                            <i class="fa fa-shopping-cart fa-2x hint-text"></i>
                                            <h2>Your Bags are ready to check out!</h2>
                                            <p>Thanks. Here is a list of your shopping items.</p>
                                            <p class="small hint-text">Take the next step to go to the shipping info and
                                                billing details.</p>
                                        </div>
                                    <?php } else { ?>
                                        <div class="pedding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                            <h2>You have not ordered items!</h2>
                                            <p>Please choose the item you want to buy.</p>
                                        </div>
                                        <br>
                                        <a href="list_product.php" class="btn btn-primary pull-right"
                                           type="button">Go to Collection
                                        </a>
                                    <?php } ?>
                                </div>

                                <div class="col-md-7">
                                    <div class="padding-30 sm-padding-5">
                                        <?php
                                        if ($count_cart != 0) {
                                            $item_total = 0;
                                            $total_weight = 0;

                                            ?>
                                            <table class="table table-condensed">
                                                <?php

                                                $a = 0;
                                                while ($row_cart = mysql_fetch_array($query_cart)) {

                                                    if (!$row_cart['is_custom_cart']) {
                                                        $query_item = mysql_query("SELECT * FROM `item` 
                                                            WHERE `id_item` = '" . $row_cart["id_item"] . "' AND `level` = '0';");
                                                        $row_item = mysql_fetch_array($query_item);
                                                    } else {
                                                        $query_item = mysql_query("SELECT * FROM `custom_collection` 
                                                            WHERE `id_custom_collection` = '" . $row_cart["id_item"] . "' AND `level` = '0';");
                                                        $row_item = mysql_fetch_array($query_item);
                                                    }

                                                    ?>
                                                    <tr class="form_items[]">
                                                        <td style="width: 45%;">
                                                            <span class="m-l-10 font-montserrat fs-11 all-caps"><?php echo(isset($row_item["title"]) ? $row_item["title"] : 'Custom Wetsuit'); ?></span>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <span>Qty <?php echo $row_cart['qty']; ?></span>
                                                        </td>
                                                        <td style="width: 40%; text-align: right;">
                                                            <h5 class="text-primary no-margin font-montserrat">
                                                                <?php echo $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? round($row_cart['amount'], 2) : number_format(($row_cart['amount'] * $USDtoIDR), 0, '.', ',')) ?></h5>
                                                        </td>

                                                        <input type="hidden"
                                                               value="<?php echo(isset($row_item['title']) ? $row_item['title'] : 'Custom Wetsuit'); ?>"
                                                               id="title_<?php echo $a; ?>" name="title[]">
                                                        <input type="hidden"
                                                               value="<?php echo $row_cart['qty']; ?>"
                                                               id="qty_<?php echo $a; ?>" name="qty[]">
                                                        <input type="hidden"
                                                               value="<?php echo $row_item['price']; ?>"
                                                               id="price_<?php echo $a; ?>" name="price[]">
                                                    </tr>
                                                    <?php

                                                    // Set item total
                                                    $item_total += ($row_item["price"] * $row_cart["qty"]);

                                                    // Set weight
                                                    $weight = ($row_cart['is_custom_cart'] ? get_price('default-weight-custom-item') : $row_item["weight"]);
                                                    $total_weight += ($weight * $row_cart['qty']);

                                                    $a++;
                                                }

                                                // round out total weight
                                                $total_weight_round = (($total_weight < 1) ? 1 : round($total_weight));
                                                ?>
                                                <input type="hidden" name="subtotal" id="subtotal"
                                                       value="<?php echo $item_total; ?>">
                                                <input type="hidden"
                                                       value="<?php echo $row_transaction["id_transaction"]; ?>"
                                                       id="id_transaction">
                                                <input type="hidden"
                                                       value="<?php echo $total_weight; ?>"
                                                       id="weight">
                                            </table>

                                            <div class="row"></div>
                                            <br>
                                            <div class="row b-a b-grey no-margin">
                                                <div class="col-md-3 p-l-10 sm-padding-15 align-items-center d-flex">
                                                    <div>
                                                        <h5 class="font-montserrat all-caps small no-margin hint-text bold">
                                                            Discount</h5>
                                                        <p class="no-margin">$ 0</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-middle sm-padding-15 align-items-center d-flex">
                                                    <div>
                                                        <h5 class="font-montserrat all-caps small no-margin hint-text bold">
                                                            Shipping Cost</h5>
                                                        <p class="no-margin"><?php echo $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? ($total_weight_round * $kurs) : number_format(($total_weight_round * $kurs), 0, '.', ',')); ?></p>
                                                        <input type="hidden" name="courier" id="courier"
                                                               value="<?php echo(isset($guest['courier']) ? $guest['courier'] : ''); ?>">
                                                        <input type="hidden" name="service" id="service"
                                                               value="<?php echo(isset($guest['service']) ? $guest['service'] : ''); ?>">
                                                        <input type="hidden" name="price_shipping" id="price_shipping"
                                                               value="<?php echo $kurs_IDR; ?>">
                                                        <input type="hidden" name="shipping" id="shipping"
                                                               value="<?php echo($total_weight_round * $kurs); ?>">
                                                        <input type="hidden" name="shipping_IDR" id="shipping_IDR"
                                                               value="<?php echo($total_weight_round * $kurs_IDR); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-right bg-primary padding-10">
                                                    <h5 class="font-montserrat all-caps small no-margin hint-text text-white bold">
                                                        Total</h5>
                                                    <h5 class="no-margin text-white">
                                                        <?php echo $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? number_format(($item_total + ($total_weight_round * $kurs)), 2, '.', ',') : number_format((($item_total * $USDtoIDR) + ($total_weight_round * $kurs)), 0, '.', ',')); ?></h5>
                                                    <input type="hidden" name="amount" id="amount"
                                                           value="<?php echo $item_total + ((($currency_code == CURRENCY_USD_CODE) ? ($total_weight_round * $kurs) : (($total_weight_round * $kurs) / $USDtoIDR))); ?>">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <br>
                                        <?php
                                        if ($count_cart != 0) { ?>
                                            <button class="btn btn-primary pull-right"
                                                    id="next-information"
                                                    type="button">Next
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab-member-information">
                            <div class="row row-same-height">
                                <div class="col-md-5 b-r b-dashed b-grey ">
                                    <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                        <h2>Your Information is safe with us!</h2>
                                        <p>Please entry your personal data.</p>
                                        <p class="small hint-text">Make sure your email is active.</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="padding-30 sm-padding-5">
                                        <p>Name and Email Address</p>
                                        <div class="form-group-attached">
                                            <input type="hidden" id="id_member"
                                                   value="<?php echo $loggedin['id_member']; ?>">
                                            <div class="row clearfix">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default">
                                                        <label>First Name</label>
                                                        <input type="text"
                                                               class="form-control" <?php echo ($row_member["firstname"]) ? "readonly" : "" ?>
                                                               name="firs_name"
                                                               placeholder="First Name" id="first_name"
                                                               value="<?php echo ($count_cart != 0) ? $row_member["firstname"] : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Last Name</label>
                                                        <input type="text"
                                                               class="form-control" <?php echo ($row_member["lastname"]) ? "readonly" : "" ?>
                                                               name="last_name"
                                                               placeholder="Last Name" id="last_name"
                                                               value="<?php echo ($count_cart != 0) ? $row_member["lastname"] : ""; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Email</label>
                                                <input type="email"
                                                       class="form-control" <?php echo ($row_member["email"]) ? "readonly" : "" ?>
                                                       name="email"
                                                       placeholder="E-mail" id="email"
                                                       value="<?php echo ($count_cart != 0) ? $row_member["email"] : ""; ?>">
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Phone Number</label>
                                                <input type="text"
                                                       class="form-control" <?php echo ($row_member["notelp"]) ? "readonly" : "" ?>
                                                       name="phone_number"
                                                       placeholder="Phone Number" id="phone_number"
                                                       value="<?php echo ($count_cart != 0) ? $row_member["notelp"] : ""; ?>">
                                            </div>
                                        </div>
                                        <br>
                                        <p>Primary Address</p>
                                        <div class="form-group-attached">
                                            <div class="row clearfix">
                                                <?php if ($row_member["idCountry"]) { ?>
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label class="">Select Country</label>
                                                        <select class="full-width" id="country_code"
                                                                data-placeholder="Select Country"
                                                                name="country_code" data-init-plugin="select2"
                                                                disabled>
                                                            <option value="<?php echo $row_country["id_country"]; ?>"><?php echo $row_country["name"]; ?></option>
                                                        </select>
                                                    </div>
                                                <?php } else {
                                                    $query_country = mysql_query("SELECT * FROM `countries`"); ?>
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label class="">Select Country</label>
                                                        <select class="full-width" id="country_code"
                                                                data-placeholder="Select Country"
                                                                name="country_code" data-init-plugin="select2">
                                                            <option hidden>Chose Country</option>
                                                            <?php while ($row_country = mysql_fetch_array($query_country)) {
                                                                echo '<option value="' . $row_country["id_country"] . '">' . $row_country["name"] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="row clearfix">
                                                <?php if ($row_member["idpropinsi"]) { ?>
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label class="">Select Province</label>
                                                        <select class="full-width" id="province"
                                                                data-placeholder="Select Country"
                                                                name="province" data-init-plugin="select2" disabled>
                                                            <option value="<?php echo $row_provinsi["namaProvinsi"] . '-' . $row_provinsi["idProvinsi"]; ?>"><?php echo $row_provinsi["namaProvinsi"]; ?></option>
                                                        </select>
                                                    </div>
                                                <?php } else {
                                                    $all_province_query = mysql_query("SELECT * FROM `provinsi` WHERE `idCountry` = '" . ($row_member["idCountry"] ? $row_member["idCountry"] : "ID") . "';"); ?>
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label class="">Select Province</label>
                                                        <select class="full-width" id="province"
                                                                data-placeholder="Chose Province"
                                                                name="province" data-init-plugin="select2">
                                                            <option hidden>-- Choose Province --</option>
                                                            <?php

                                                            foreach ($get_province->rajaongkir->results as $province) {
                                                                echo '<option value="' . $province->province . '-' . $province->province_id . '">' . $province->province . '</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-9">
                                                    <?php if ($row_member["idkota"]) { ?>
                                                        <div class="form-group form-group-default form-group-default-select2">
                                                            <label class="">Select City</label>
                                                            <select class="full-width" id="city"
                                                                    data-placeholder="Select Country"
                                                                    name="city" data-init-plugin="select2"
                                                                    disabled>
                                                                <option value="<?php echo $row_kota["namaKota"] . '-' . $row_kota["idKota"]; ?>"><?php echo $row_kota["namaKota"]; ?></option>
                                                            </select>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="form-group form-group-default form-group-default-select2 required">
                                                            <label class="">Select City</label>
                                                            <select class="full-width" id="city"
                                                                    data-placeholder="Chose City"
                                                                    name="city" data-init-plugin="select2">
                                                                <option hidden>-- Choose City --</option>
                                                                <?php

                                                                foreach ($get_city->rajaongkir->results as $city) {
                                                                    echo '<option value="' . $city->city_id . '">' . $city->city_name . '</option>';
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group form-group-default">
                                                        <label>Postal Code</label>
                                                        <input type="text"
                                                               class="form-control" <?php echo ($row_member["kode_pos"]) ? "readonly" : "" ?>
                                                               name="postal_code"
                                                               placeholder="Postal Code" id="postal_code"
                                                               value="<?php echo ($count_cart != 0) ? $row_member["kode_pos"] : ""; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-default ">
                                                <label>Address</label>
                                                <textarea
                                                        class="form-control" <?php echo ($row_member["kode_pos"]) ? "readonly" : "" ?>
                                                        name="address"
                                                        placeholder="Address" id="address"
                                                        style="height: 50px;"><?php echo ($count_cart != 0) ? $row_member["alamat"] : ""; ?></textarea>
                                            </div>

                                        </div>
                                        <br>
                                        <button class="btn btn-primary pull-right"
                                                id="<?php echo $update_member ? 'update_member' : 'next_to_shipping_address'; ?>"
                                                type="button">Next
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab-shipping-address">
                            <div class="row row-same-height">
                                <div class="col-md-5 b-r b-dashed b-grey ">
                                    <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                        <h2>Your Information shipping address!</h2>
                                        <p>Please entry your shipping address.</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="padding-30 sm-padding-5">
                                        <p>Shipping Address</p>
                                        <div class="form-group-attached">
                                            <input type="hidden" value="<?php echo $row_city_company['value']; ?>" id="id_city_company">
                                            <div class="row clearfix">
                                                <div class="form-group form-group-default form-group-default-select2">
                                                    <label class="">Select Country</label>
                                                    <select class="full-width" id="shipping_country_code"
                                                            data-placeholder="Select Country"
                                                            name="shipping_country_code" data-init-plugin="select2">
                                                        <option hidden>Chose Country</option>
                                                        <?php
                                                        $query_country = mysql_query("SELECT * FROM `countries`");
                                                        while ($row_country = mysql_fetch_array($query_country)) {
                                                            echo '<option value="' . $row_country["id_country"] . '" ' . (($row_member["idCountry"] == $row_country["id_country"]) ? 'selected' : '') . '>' . $row_country["name"] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="form-group form-group-default form-group-default-select2">
                                                    <label class="">Select Province</label>
                                                    <select class="full-width" id="shipping_province"
                                                            data-placeholder="Chose Province"
                                                            name="shipping_province" data-init-plugin="select2" onchange="changeShippingProvince();">
                                                        <option hidden>-- Choose Province --</option>
                                                        <?php

                                                        // Set id_province
                                                        $id_province = (isset($guest["id_province"]) ? $guest["id_province"] : ($row_provinsi["idProvinsi"] ?: null));

                                                        foreach ($get_province->rajaongkir->results as $province) {
                                                            echo '<option value="' . $province->province_id . '" 
                                                                    ' . ($id_province ? (($id_province == $province->province_id) ? 'selected' : '') : '') . '>' . $province->province . '</option>';
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-9">
                                                    <div class="form-group form-group-default form-group-default-select2 required">
                                                        <label class="">Select City</label>
                                                        <select class="full-width" id="shipping_city"
                                                                data-placeholder="Chose City"
                                                                name="shipping_city" data-init-plugin="select2" onchange="changeShippingCity();">
                                                            <option hidden>-- Choose City --</option>
                                                            <?php

                                                            // Set id_city
                                                            $id_city = (isset($guest["id_city"]) ? $guest["id_city"] : ($row_kota["idKota"] ?: null));

                                                            foreach ($get_city->rajaongkir->results as $city) {
                                                                echo '<option value="' . $city->city_id . '" ' . ($id_city ? (($id_city == $city->city_id) ? 'selected' : '') : '') . '>' . $city->city_name . '</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group form-group-default">
                                                        <label>Postal Code</label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="shipping_postal_code"
                                                               placeholder="Postal Code" id="shipping_postal_code"
                                                               value="<?php echo $row_member["kode_pos"]; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-default ">
                                                <label>Address</label>
                                                <textarea
                                                        class="form-control"
                                                        name="shipping_address"
                                                        placeholder="Address" id="shipping_address"
                                                        style="height: 50px;"><?php echo $row_member["alamat"]; ?></textarea>
                                            </div>

                                        </div>
                                        <br>
                                        <p>Shipping Courier</p>
                                        <div class="form-group-attached">
                                            <div class="row clearfix">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label class="">Courier</label>
                                                        <select class="full-width" id="shipping_courier"
                                                                data-placeholder="Select Country"
                                                                name="shipping_courier" data-init-plugin="select2" onchange="changeShippingCourier();">
                                                            <option hidden>Chose Courier</option>
                                                            <?php

                                                            // Set Couriers
                                                            $couriers = get_couriers();

                                                            foreach ($couriers as $courier) {

                                                                // Set selected courier
                                                                $selected_courier = (isset($guest['courier']) ? (($guest['courier'] == $courier['code']) ? 'selected' : '') : '');

                                                                // Set option
                                                                echo '<option value="' . $courier['code'] . '" ' . $selected_courier . '>' . $courier['name'] . '</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default form-group-default-select2">
                                                        <label class="">Service</label>
                                                        <select class="full-width" id="shipping_service"
                                                                data-placeholder="Chose Province"
                                                                name="shipping_service" data-init-plugin="select2" onchange="changeShippingService();">
                                                            <option hidden>-- Choose Service --</option>
                                                            <?php

                                                            if (isset($guest['courier'])) {

                                                                // Set parameter request
                                                                $parameter_cost = [
                                                                    'origin' => $row_city_company['value'],
                                                                    'destination' => ($guest['id_city'] ?: ($row_kota["idKota"] ?: null)),
                                                                    'weight' => (($weight < 1) ? 1 : $weight),
                                                                    'courier' => $guest['courier']
                                                                ];

                                                                // Get courier
                                                                $get_cost = get_cost($parameter_cost);

                                                                if ($get_cost->rajaongkir->status->code == 200) {

                                                                    foreach ($get_cost->rajaongkir->results[0]->costs as $cost) {

                                                                        // Set selected courier
                                                                        $selected_service = (isset($guest['service']) ? (($guest['service'] == $cost->service) ? 'selected' : '') : '');

                                                                        // Set option
                                                                        echo '<option value="' . $cost->service . '" ' . $selected_service . '>' . $cost->service . '</option>';

                                                                    }

                                                                }

                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <p>Cost</p>
                                        <p style="font-size: 25px;;" id="shipping_courier_cost">
                                            <?php

                                            $courier_cost = 0;
                                            if (isset($guest['courier_cost'])) {

                                                // Set courier cost
                                                $courier_cost = (($currency_code == CURRENCY_USD_CODE) ? round(($guest['courier_cost'] / $USDtoIDR), 2) : $guest['courier_cost']);

                                                echo '<b>' . $currency . ' ' . number_format($courier_cost, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</b>';
                                            }

                                            ?>
                                        </p>
                                        <br>
                                        <button class="btn btn-primary pull-right"
                                                id="<?php echo(($currency_code == CURRENCY_USD_CODE) ? 'next_to_paypal' : 'payment_transaction') ?>"
                                                type="button">Payment Now
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane padding-20 sm-no-padding slide-left" id="tab-paypal">
                            <div class="row row-same-height">
                                <div class="col-md-5 b-r b-dashed b-grey sm-b-b">
                                    <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                        <h2>Please make a payment through your paypal account!</h2>
                                    </div>
                                    <div class="padding-30 sm-padding-5">
                                        <div class="from-left pull-right" id="paypal-button"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
            <!-- END CONTAINER FLUID -->
        </div>
        <!-- END PAGE CONTENT -->
        <!-- START COPYRIGHT -->
        <!-- START CONTAINER FLUID -->
        <!-- START CONTAINER FLUID -->


        <!-- END COPYRIGHT -->
    </div>
    <!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END PAGE CONTAINER -->
<!--START QUICKVIEW -->

<!-- END QUICKVIEW-->
<!-- START OVERLAY -->

<!-- END OVERLAY -->
<!-- BEGIN VENDOR JS -->
<script src="member/assets/plugins/feather-icons/feather.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/tether/js/tether.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
<script src="member/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script type="text/javascript" src="member/assets/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="member/assets/plugins/classie/classie.js"></script>
<script src="member/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript" src="member/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script type="text/javascript" src="member/assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="member/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="member/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
<script src="member/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js"
        type="text/javascript"></script>
<script src="member/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="member/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/moment/moment.min.js"></script>
<script src="member/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="member/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<!-- END VENDOR JS -->
<!-- BEGIN CORE TEMPLATE JS -->
<script src="member/pages/js/pages.min.js"></script>
<!-- END CORE TEMPLATE JS -->
<!-- BEGIN PAGE LEVEL JS -->
<script src="member/assets/js/form_wizard.js" type="text/javascript"></script>
<script src="member/assets/js/scripts.js" type="text/javascript"></script>
<!-- END PAGE LEVEL JS -->

<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script src="js/paypal/set-config.js"></script>

<script type="application/javascript">
    $(document).ready(function () {


        // Next to member information
        $('#next-information').click(function () {
            $('#item-your-card').removeClass('active');
            $('#item-member-information').addClass('active');

            $('#tab-your-cart').removeClass('active');
            $('#tab-member-information').addClass('active');
        });


        // Update country
        $('#country_code').on('change', function () {
            $.ajax({
                type: 'GET',
                url: 'shipping_member.php',
                data: {select_country: 'select_country', id_country: $('#country_code').val()},
                dataType: 'json',
                success: function (data) {
                    if (!data.failed) {
                        var province = $('#province');
                        province.html("").append('<option hidden>-- Choose Province --</option>');
                        for (var i = 0; i < data.results.length; i++) {
                            province.append(
                                '<option value="' + data.results[i].province + '-' + data.results[i].province_id + '">' + data.results[i].province + '</option>'
                            );
                        }
                    }
                }
            })
        });


        // Update province
        $('#province').on('change', function () {
            var province = $('#province').val().split('-');

            $.ajax({
                type: 'GET',
                url: 'shipping_member.php',
                data: {select_province: 'select_province', id_province: province[1]},
                dataType: 'json',
                success: function (data) {
                    if (!data.failed) {
                        $('#state').val(province[0]);
                        var city = $('#city');
                        city.html("").append('<option hidden>-- Choose City --</option>');
                        for (var i = 0; i < data.results.length; i++) {
                            city.append(
                                '<option value="' + data.results[i].city_name + '-' + data.results[i].city_id + '">' + data.results[i].city_name + '</option>'
                            );
                        }
                    }
                }
            })
        });


        // Process for update member data
        $('#update_member').click(function () {
            var id_member = $('#id_member').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var email = $('#email').val();
            var phone_number = $('#phone_number').val();
            var address = $('#address').val();
            var country_code = $('#country_code').val();
            var province = $('#province').val();
            var city = $('#city').val();
            var postal_code = $('#postal_code').val();

            if (first_name == '' ||
                last_name == '' ||
                email == '' ||
                phone_number == '' ||
                address == '' ||
                country_code == '0' ||
                province == '' ||
                city == '' ||
                postal_code == '') {

                notifyAlert('Isi semua data yang masih kosong..!!', 'danger', 'top-right');

            } else {

                province = province.split('-');
                city = city.split('-');

                $.ajax({
                    type: 'POST',
                    url: 'shipping_member.php',
                    data: {
                        action: 'update_member',
                        id_member: id_member,
                        first_name: first_name,
                        last_name: last_name,
                        email: email,
                        phone_number: phone_number,
                        address: address,
                        country_code: country_code,
                        id_province: province[1],
                        id_city: city[1],
                        postal_code: postal_code
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (!data.failed) {
                            $('#item-member-information').removeClass('active');
                            $('#item-shipping-address').addClass('active');

                            $('#tab-member-information').removeClass('active');
                            $('#tab-shipping-address').addClass('active');
                        } else {
                            notifyAlert('Gagal menyimpan data member!', 'danger', 'top-right');
                        }
                    }
                });
            }
        });


        // Next to process shipping address
        $('#next_to_shipping_address').click(function () {
            var id_member = $('#id_member').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var email = $('#email').val();
            var phone_number = $('#phone_number').val();
            var address = $('#address').val();
            var country_code = $('#country_code').val();
            var province = $('#province').val();
            var city = $('#city').val();
            var postal_code = $('#postal_code').val();

            if (first_name == '' ||
                last_name == '' ||
                email == '' ||
                phone_number == '' ||
                address == '' ||
                country_code == '0' ||
                province == '' ||
                city == '' ||
                postal_code == '') {

                notifyAlert('Isi semua data yang masih kosong..!!', 'danger', 'top-right');

            } else {
                $('#item-member-information').removeClass('active');
                $('#item-shipping-address').addClass('active');

                $('#tab-member-information').removeClass('active');
                $('#tab-shipping-address').addClass('active');
            }
        });


        // Process with bank transfer
        $("#payment_transaction").click(function () {

            // Show notification
            notifyAlert('Please wait, it\'s being processed.', 'success', 'top-right', 6000);

            // Set payment transaction var
            var payment_transaction = $("#payment_transaction");

            // Disable button
            payment_transaction.attr('disabled', true);

            // Set var
            var address = $('#shipping_address').val();
            var country_code = $('#shipping_country_code').val();
            var province = $('#shipping_province').val();
            var city = $('#shipping_city').val();
            var postal_code = $('#shipping_postal_code').val();
            var amount = $('#amount').val();
            var weight = $('#weight').val();
            var courier = $('#courier').val();
            var service = $('#service').val();
            var price_shipping = $('#price_shipping').val();
            var shipping_IDR = $('#shipping_IDR').val();

            $.ajax({
                type: 'POST',
                url: 'shipping_member.php',
                data: {
                    action: 'create_transaction',
                    address: address,
                    country_code: country_code,
                    id_province: province,
                    id_city: city,
                    postal_code: postal_code,
                    amount: amount,
                    weight: weight,
                    courier: courier,
                    service: service,
                    price_shipping: price_shipping,
                    shipping_IDR: shipping_IDR
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'success') {
                        notifyAlert(data.msg, 'success', 'top-right', 3000);
                        window.location.href = 'member/transaction_order_information.php?id=' + data.results.id_transaction;
                    } else {
                        notifyAlert(data.msg, 'error', 'top-right', 3000);
                    }
                }
            });
        });


        // Next process to paypal
        $("#next_to_paypal").click(function () {
            $('#item-shipping-address').removeClass('active');
            $('#item-payment').addClass('active');

            $('#tab-shipping-address').removeClass('active');
            $('#tab-paypal').addClass('active');
        });


        // Process with paypal
        paypal.Button.render({

            style: paypalStyle,

            env: paypalEvn,

            client: {
                sandbox: paypalTokenSandboxClientID,
                production: paypalTokenProductionClientID
            },

            commit: true,

            payment: function (data, actions) {

                var first_name = $('#first_name').val();
                var last_name = $('#last_name').val();
                var phone_number = $('#phone_number').val();

                var id_items = $('tr[class="form_items[]"]').map(function () {
                    return this;
                }).get();

                var item_list = [];

                for (var i = 0; i < id_items.length; i++) {
                    item_list.push({
                        name: $('#title_' + i).val(),
                        quantity: $('#qty_' + i).val(),
                        price: $('#price_' + i).val(),
                        sku: (i + 1),
                        currency: "USD"
                    });
                }

                return actions.payment.create({
                    payment: {
                        transactions: [{
                            amount: {
                                currency: "USD",
                                total: $('#amount').val(),
                                // akan diaktifkan bila biaya yang lain selain barang dikenakan
                                details: {
                                    shipping: $('#shipping').val(), // Diisi dengan biaya pengiriman
                                    subtotal: $('#subtotal').val(), // Akan diisi jika salah satu dari tax atau shipping diisi
                                    tax: "0" // akan diisi biaya tax jeka perlu
                                }
                            },
                            item_list: {
                                items: item_list
                                // shipping_address: {
                                //     recipient_name: "Yuswa",
                                //     line1: "Jln Raya Kerobokan 388x",
                                //     line2: "Unit #34",
                                //     city: "Badung",
                                //     country_code: "ID",
                                //     postal_code: "80361",
                                //     phone: "081290792903",
                                //     state: "Bali"
                                // }
                            }
                        }]
                    }
                })
            },

            onAuthorize: function (success, actions) {
                return actions.payment.execute().then(function () {
                    notifyAlert('Please wait, it\'s being processed.', 'success', 'top-right', 600000000);

                    $.ajax({
                        type: 'POST',
                        url: paypalApiMainUrl + '/v1/oauth2/token',
                        headers: {
                            'Authorization': 'Basic ' + btoa(paypalTokenSandboxClientID + ":" + paypalTokenSandboxSecret), // TODO: Ubah paypalTokenSandboxClientID (testing) & paypalTokenSandboxSecret (testing) => paypalTokenProductionClientID (production) & paypalTokenProductionSecret (production)
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: {grant_type: 'client_credentials'},
                        dataType: 'json',
                        success: function (token) {

                            $.ajax({
                                type: 'GET',
                                url: paypalApiMainUrl + '/v1/payments/payment/' + success.paymentID,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + token.access_token
                                },
                                success: function (payment) {
                                    $.ajax({
                                        type: 'POST',
                                        url: 'shipping_member.php',
                                        data: {
                                            action: 'save_payment',
                                            state: payment.transactions[0].related_resources[0].sale.state,
                                            amount: payment.transactions[0].related_resources[0].sale.amount,
                                            description: payment.transactions[0].description,
                                            paymentId: payment.id,
                                            weight: $('#weight').val(),
                                            courier: $('#courier').val(),
                                            service: $('#service').val(),
                                            shipping: $('#shipping_IDR').val(),
                                            price_shipping: $('#price_shipping').val(),
                                            shipping_address: $('#shipping_address').val(),
                                            shipping_country_code: $('#shipping_country_code').val(),
                                            shipping_province: $('#shipping_province').val(),
                                            shipping_city: $('#shipping_city').val(),
                                            shipping_postal_code: $('#shipping_postal_code').val()
                                        },
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data.status == "success") {
                                                notifyAlert(data.msg, 'success', 'top-right');
                                                window.location.href = 'member/transaction_order_information.php?id=' + data.results.id_transaction;
                                            } else {
                                                notifyAlert(data.msg, 'danger', 'top-right');
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });

                }).catch(function (err) {
                    window.alert('Payment failed');
                })
            }

        }, '#paypal-button');
    });


    function changeShippingProvince() {
        var id_province = $("#shipping_province").val();

        $.ajax({
            type: "POST",
            url: "member/change_city.php",
            data: {
                action: "change_province",
                id_province: id_province
            },
            dataType: "json",
            success: function (data) {
                if (data.status == "error") {
                    notifyAlert(data.msg, 'error', 'top-right', 3000);
                } else {

                    $("#shipping_courier_cost").html("");

                    var courier = $("#shipping_courier");
                    courier.html("").attr("disabled", true);
                    courier.append("<option hidden>Courier</option>");

                    var service_courier = $("#shipping_service");
                    service_courier.html("").attr("disabled", true);
                    service_courier.append("<option hidden>Service Courier</option>");

                    var city = $("#shipping_city");
                    city.html("");
                    city.append("<option hidden>city</option>");

                    for (var i = 0; i < data.results.length; i++) {
                        city.append(
                            '<option value="' + data.results[i].city_id + '">' + data.results[i].city_name + '</option>'
                        );
                    }
                }
            }
        });
    }

    function changeShippingCity() {
        var id_city = $("#shipping_city").val();

        $.ajax({
            type: "POST",
            url: "member/change_city.php",
            data: {
                action: "change_city",
                id_city: id_city
            },
            dataType: "json",
            success: function (data) {
                if (data.status == "error") {
                    notifyAlert(data.msg, 'error', 'top-right', 3000);
                } else {

                    $("#shipping_courier_cost").html("");

                    var service_courier = $("#shipping_service");
                    service_courier.html("").attr("disabled", true);
                    service_courier.append("<option hidden>Service Courier</option>");

                    var courier = $("#shipping_courier");
                    courier.html("").attr("disabled", false);
                    courier.append("<option hidden>Courier</option>");

                    for (var i = 0; i < data.results.length; i++) {
                        courier.append(
                            '<option value="' + data.results[i].code + '">' + data.results[i].name + '</option>'
                        );
                    }
                }
            }
        });
    }

    function changeShippingCourier() {
        var id_city_company = $("#id_city_company").val();
        var id_city = $("#shipping_city").val();
        var weight = $("#weight").val();
        var courier = $("#shipping_courier").val();

        $.ajax({
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
            success: function (data) {
                if (data.status == "error") {
                    notifyAlert(data.msg, 'error', 'top-right', 3000);
                } else {

                    $("#shipping_courier_cost").html("");

                    var service_courier = $("#shipping_service");
                    service_courier.html("").attr("disabled", false);
                    service_courier.append("<option hidden>Service Courier</option>");

                    var costs = data.results.rajaongkir.results[0].costs;
                    for (var i = 0; i < costs.length; i++) {
                        service_courier.append(
                            '<option value="' + costs[i].service + '">' + costs[i].service + '</option>'
                        );
                    }
                }
            }
        });
    }

    function changeShippingService() {
        var service_courier = $("#shipping_service").val();
        var id_city_company = $("#id_city_company").val();
        var id_city = $("#shipping_city").val();
        var weight = $("#weight").val();
        var courier = $("#shipping_courier").val();

        $.ajax({
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
            success: function (data) {
                if (data.status == "error") {
                    notifyAlert(data.msg, 'error', 'top-right', 3000);
                } else {
                    window.location.reload();
                }
            }
        });
    }

    function notifyAlert(message, type, position, timeout = 3600) {
        $('.page-container').pgNotification({
            style: 'flip',
            message: message,
            position: position ? position : 'top-right',
            timeout: timeout,
            type: type
        }).show();
    }

</script>
</body>
</html>

<!-- Acounts paypal for testing
 "ron@hogwarts.com",            "qwer1234"
 "sallyjones1234@gmail.com",    "p@ssword1234"
 "joe@boe.com",                 "123456789"
 "hermione@hogwarts.com",       "123456789"
 "lunalovegood@hogwarts.com",   "123456789"
 "ginnyweasley@hogwarts.com",   "123456789"
 "bellaswan@awesome.com",       "qwer1234"
 "edwardcullen@gmail.com",      "qwer1234"
 --!>