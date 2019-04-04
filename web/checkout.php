<?php
session_start();
include("config/configuration.php");
include("config/currency_types.php");

if ($loggedin = logged_in()) {

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

    $row_member = array();
    $row_provinsi = array();
    $row_kota = array();
    $update_member = true;
    $kurs = 0;

    // Get cart
    $query_cart = mysql_query("SELECT * FROM `cart` 
          WHERE ISNULL(id_transaction) AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0';");
    $count_cart = mysql_num_rows($query_cart) > 0;

    if ($count_cart) {
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
        $kurs = (($currency_code == CURRENCY_USD_CODE) ? round(($row_kota["ongkos_kirim"] / $USDtoIDR), 2) : $row_kota['ongkos_kirim']);

        if ($row_member['firstname'] && $row_member['lastname'] && $row_member['alamat'] && $row_member['kode_pos'] &&
            $row_member['idCountry'] && $row_member['idpropinsi'] && $row_member['idkota']
        ) {
            $update_member = false;
        }
    }

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
                SeaGods Wetsuit Cart
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
                    <a href="member/list-transaction.php">List Transaction</a>
                </li>
                <li>
                    <a href="member/wishlist.php"><span class="title">Wishlist</span></a>
                </li>
                <li>
                    <a href="member/custommade.php"><span class="title">Custom Made</span></a>
                </li>
                <li>
                    <a href="index.php" target="_blank"><span class="title">Homepage Website</span></a>
                </li>
                <li>
                    <a href="list_product.php" target="_blank"><span class="title">Collection</span></a>
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
                            <a class="" id="item-shipping-information" data-toggle="tab" href="#" role="tab"><i
                                        class="fa fa-m tab-icon"></i>
                                <span>Shipping information</span></a>
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
                                                $total_weight = round($total_weight);
                                                ?>
                                                <input type="hidden" name="subtotal" id="subtotal"
                                                       value="<?php echo $item_total; ?>">
                                                <input type="hidden"
                                                       value="<?php echo $row_transaction["id_transaction"]; ?>"
                                                       id="id_transaction">
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
                                                        <p class="no-margin"><?php echo $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? ($total_weight * $kurs) : number_format(($total_weight * $kurs), 0, '.', ',')); ?></p>
                                                        <input type="hidden" name="shipping" id="shipping"
                                                               value="<?php echo($total_weight * $kurs); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-right bg-primary padding-10">
                                                    <h5 class="font-montserrat all-caps small no-margin hint-text text-white bold">
                                                        Total</h5>
                                                    <h5 class="no-margin text-white">
                                                        <?php echo $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? number_format(($item_total + ($total_weight * $kurs)), 2, '.', ',') : number_format((($item_total * $USDtoIDR) + ($total_weight * $kurs)), 0, '.', ',')); ?></h5>
                                                    <input type="hidden" name="amount" id="amount"
                                                           value="<?php echo $item_total + ((($currency_code == CURRENCY_USD_CODE) ? ($total_weight * $kurs) : (($total_weight * $kurs) / $USDtoIDR))); ?>">
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

                        <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab-shipping-information">
                            <div class="row row-same-height">
                                <div class="col-md-5 b-r b-dashed b-grey ">
                                    <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                        <h2>Your Information is safe with us!</h2>
                                        <p>Please enter your data for shipping details of invoices and payments.</p>
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
                                        <p>Billing Address</p>
                                        <div class="form-group-attached">
                                            <div class="form-group form-group-default ">
                                                <label>Address</label>
                                                <input type="text"
                                                       class="form-control" <?php echo ($row_member["alamat"]) ? "readonly" : "" ?>
                                                       name="category"
                                                       placeholder="Address" id="address"
                                                       value="<?php echo ($count_cart != 0) ? $row_member["alamat"] : ""; ?>">
                                            </div>
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
                                                            <option type="hidden" value="0">Chose Country</option>
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
                                                            <option type="hidden" value="">Chose Province</option>
                                                            <?php while ($row_all_province = mysql_fetch_array($all_province_query)) {
                                                                echo '<option value="' . $row_all_province["namaProvinsi"] . '-' . $row_all_province["idProvinsi"] . '">' . $row_all_province["namaProvinsi"] . '</option>';
                                                            } ?>
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
                                                                <option type="hidden" value="">Chose City</option>
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

                                        </div>
                                        <br>
                                        <button class="btn btn-primary btn-block"
                                                id="<?php echo $update_member ? 'update_member' : 'payment_now'; ?>"
                                                type="button">Pay Now
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane padding-20 sm-no-padding slide-left" id="tab-paypal">
                            <div class="row row-same-height">
                                <?php if ($currency_code == CURRENCY_USD_CODE) {
                                    echo '
                                        <div class="col-md-5 b-r b-dashed b-grey sm-b-b">
                                            <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                                <h2>Please make a payment through your paypal account!</h2>
                                            </div>
                                            <div class="padding-30 sm-padding-5">
                                                <div class="from-left pull-right" id="paypal-button"></div>
                                            </div>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="col-md-7 b-r b-dashed b-grey sm-b-b">
                                            <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                                <h5>Or You Can Transfer To Us Via</h5>
                                            </div>
                                            <div class="padding-10 sm-padding-5">
                                                <div><img src="images/mandiri.jpg" height="30px">Account Number : <strong>145-0010-897-318</strong>
                                                </div>
                                                <br><br>
                                                <div><img src="images/bca.jpg" height="30px">Account Number : <strong>146-668-4848</strong>
                                                </div>
                                                <br><br>
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <p style="font-size:12px;">After transferring, please confirm to us
                                                                and send us the proof of payment by confirming us</p>
                                                        </td>
                                                        <td>
                                                            <a href="member/form_confirmation.php"
                                                               class="btn btn-primary pull-right"
                                                               type="button">Confirmation </a>
        
                                                        </td>
                                                    </tr>
                                                </table>
        
                                            </div>
                                        </div>';
                                } ?>


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

        $('#next-information').click(function () {
            $('#item-your-card').removeClass('active');
            $('#item-shipping-information').addClass('active');

            $('#tab-your-cart').removeClass('active');
            $('#tab-shipping-information').addClass('active');
        });

        $('#country_code').on('change', function () {
            $.ajax({
                type: 'GET',
                url: 'shipping_member.php',
                data: {select_country: 'select_country', id_country: $('#country_code').val()},
                dataType: 'json',
                success: function (data) {
                    if (!data.failed) {
                        var province = $('#province');
                        province.html("");
                        for (var i = 0; i < data.results.length; i++) {
                            province.append(
                                '<option value="' + data.results[i].namaProvinsi + '-' + data.results[i].idProvinsi + '">' + data.results[i].namaProvinsi + '</option>'
                            );
                        }
                    }
                }
            })
        });

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
                        city.html("");
                        for (var i = 0; i < data.results.length; i++) {
                            city.append(
                                '<option value="' + data.results[i].namaKota + '-' + data.results[i].idKota + '">' + data.results[i].namaKota + '</option>'
                            );
                        }
                    }
                }
            })
        });

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
                            $('#item-shipping-information').removeClass('active');
                            $('#item-payment').addClass('active');

                            $('#tab-shipping-information').removeClass('active');
                            $('#tab-paypal').addClass('active');
                        } else {
                            notifyAlert('Gagal menyimpan data member!', 'danger', 'top-right');
                        }
                    }
                });
            }
        });

        $('#payment_now').click(function () {
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
                $('#item-shipping-information').removeClass('active');
                $('#item-payment').addClass('active');

                $('#tab-shipping-information').removeClass('active');
                $('#tab-paypal').addClass('active');
            }
        });

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
                var address = $('#address').val();
                var country_code = $('#country_code').val();
                var province = $('#province').val().split('-');
                var city = $('#city').val().split('-');
                var postal_code = $('#postal_code').val();

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
                            'Authorization': 'Basic ' + btoa(paypalTokenSandboxClientID + ":" + paypalTokenSandboxSecret),
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
                                            paymentId: payment.id
                                        },
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data.status == "success") {
                                                notifyAlert(data.msg, 'success', 'top-right');
                                                window.location.href = 'member/list-transaction.php';
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