<?php
session_start();

include("config/configuration.php");
include("config/dbController.php");

$dbhandle = new DBController();

if (isset($_POST['nilai'])) {
    $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
} else {
    $_SESSION['nilai_login'] = 0;
}

if ($loggedin = logged_in()) {

    $user = '' . $_SESSION['user'] . '';

    if (isset($_POST['quantity']) && !empty($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
        $id_collection = isset($_POST["id_collection"]) ? strip_tags(trim($_POST["id_collection"])) : "";

        $query_transaction_check = mysql_query("SELECT * FROM  `transaction`
                WHERE `id_member` = '" . $loggedin["id_member"] . "' 
                AND `status` = 'process'
                AND `konfirm` = 'not confirmated'
                ORDER BY `id_transaction` DESC lIMIT 0,1");

        if (isset($_POST['custom_cart'])) {
            $query_item = mysql_query("SELECT * FROM `custom_collection` 
                WHERE `id_custom_collection`='$id_collection' 
                AND `code`='" . $_GET["code"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_array($query_item);
            $id_item = $row_item["id_custom_collection"];
        } else {
            $query_item = mysql_query("SELECT * FROM `item` WHERE `code`='" . $_GET["code"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_array($query_item);
            $id_item = $row_item["id_item"];
        }

        $amount = ($quantity * $row_item['price']);

        if (mysql_num_rows($query_transaction_check) == 0) {

            $sql_checkout = mysql_query("SELECT * FROM  `transaction` ORDER BY `id_transaction` DESC lIMIT 0,1");
            $row_checkout = mysql_fetch_array($sql_checkout);

            $id_transaction = $row_checkout["id_transaction"] + 1;

            $query_transaction_insert = "INSERT INTO `transaction` (`id_transaction`, `kode_transaction`, `id_member`, `status`, `konfirm`, `total`, `date_add`, `date_upd`)
                VALUES ('$id_transaction', 'INV/" . $loggedin["id_member"] . "/" . date("Ymd") . "/" . rand_numb($id_transaction, 5) . "',
                '" . $loggedin["id_member"] . "', 'process', 'not confirmated', '$amount', NOW(), NOW());";

            mysql_query($query_transaction_insert) or die("<script language='JavaScript'>
                    alert('Unable to save transaction.!');
                    location.history.back(-1);
                </script>");

            $sql_insert_cart = "INSERT INTO `cart` (`id_cart`, `id_transaction`, `id_member`, `id_item`, `is_custom_cart`, `qty`, `amount`, `date_add`, `date_upd` , `level`) 
                VALUES ('', '" . $id_transaction . "', '" . $loggedin["id_member"] . "', '" . $id_item . "', '" . (($id_collection != "") ? 1 : 0) . "', '$quantity', '$amount', NOW(), NOW(), '0')";

            mysql_query($sql_insert_cart) or die("<script language='JavaScript'>
                        alert('Unable to save in cart.!');
                        location.history.back(-1);
                    </script>");

        } else {
            $row_transaction = mysql_fetch_array($query_transaction_check);

            $query_cart_check = mysql_query("SELECT * FROM `cart` 
                WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "'
                AND `id_item` = '" . $id_item . "'
                AND `level` = '0'
                LIMIT 0,1;");

            if (mysql_num_rows($query_cart_check) == 0) {

                $sql_insert_cart = "INSERT INTO `cart` (`id_cart`, `id_transaction`, `id_member`, `id_item`, `is_custom_cart`, `qty`, `amount`, `date_add` , `date_upd` , `level`) 
                    VALUES ('', '" . $row_transaction["id_transaction"] . "', '" . $loggedin["id_member"] . "', '" . $id_item . "', '" . (($id_collection != "") ? 1 : 0) . "', '$quantity', '$amount', NOW(), NOW(), '0')";

                mysql_query($sql_insert_cart) or die("<script language='JavaScript'>
                        alert('Unable to save in cart.!');
                        location.history.back(-1);
                    </script>");

            } else {

                $sql_update_cart = "UPDATE `cart` SET `qty` = '$quantity', `amount` = '$amount', `date_upd` = NOW()
                WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "'
                AND `id_item` = '" . $id_item . "'
                AND `level` = '0';";

                mysql_query($sql_update_cart) or die("<script language='JavaScript'>
                        alert('Unable to update cart.!');
                        location.history.back(-1);
                    </script>");

            }

            $amount_cart = mysql_fetch_array(mysql_query("SELECT SUM(`amount`) AS `amount` FROM `cart` WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "';"));

            $query_transaction_update = mysql_query("UPDATE `transaction` SET `total` = '" . $amount_cart["amount"] . "' WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "';");
            if (!$query_transaction_update) {
                echo "<script language='JavaScript'>
                        alert('Unable to update transaction.!');
                        location.history.back(-1);
                    </script>";
            }
        }

    }

    if (isset($_GET["remove"])) {
        $id = isset($_GET['remove']) ? strip_tags(trim($_GET['remove'])) : '';

        $id_explode = explode('-', $id);

        $query_cart = mysql_query("SELECT * FROM `cart` WHERE `id_cart` = '" . $id_explode[0] . "'");
        $row_cart = mysql_fetch_array($query_cart);

        $query_cart_delete = mysql_query("UPDATE `cart` SET `level` = '1', `date_upd` = NOW() WHERE `id_cart` = '" . $id_explode[0] . "'");
        if (!$query_cart_delete) {
            echo "<script language='JavaScript'>
                        alert('Unable to delete shopping item.!');
                        location.history.back(-1);
                    </script>";
        }

        $query_transaction_check = mysql_query("SELECT * FROM `transaction` 
            WHERE `id_transaction` = '" . $id_explode[1] . "' LIMIT 0,1;");
        $row_transaction = mysql_fetch_array($query_transaction_check);

        $amount = $row_transaction['total'] - $row_cart['amount'];

        $query_transaction_update = mysql_query("UPDATE `transaction` SET `total` = '$amount' WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "';");
        if (!$query_transaction_update) {
            echo "<script language='JavaScript'>
                        alert('Unable to update transaction.!');
                        location.history.back(-1);
                    </script>";
        }
    }

    if ($checkout = isset($_POST["checkout"]) ? $_POST["checkout"] : "") {

        if ($checkout == "Checkout") {
            $items = $_POST["id_item"];

            $query_transaction_check = mysql_query("SELECT * FROM  `transaction`
                WHERE `id_member` = '" . $loggedin["id_member"] . "' 
                AND `status` = 'process'
                AND `konfirm` = 'not confirmated'
                ORDER BY `id_transaction` DESC lIMIT 0,1");
            $row_transaction = mysql_fetch_array($query_transaction_check);

            $query_cart_delete = mysql_query("SELECT `id_cart`, `id_item` FROM `cart` 
                        WHERE `id_member` = '" . $loggedin["id_member"] . "'
                        AND `id_transaction` = '" . $row_transaction["id_transaction"] . "'
                        AND `level` = '0';");

            while ($row_cart_delete = mysql_fetch_array($query_cart_delete)) {

                if (!in_array($row_cart_delete['id_item'], $_POST["id_item"])) {

                    $query_cart_delete_process = mysql_query("UPDATE `cart` SET `level` = '1', `date_upd` = NOW()
                            WHERE `id_cart` = '" . $row_cart_delete["id_cart"] . "'");

                    if (!$query_cart_delete_process) {
                        echo "<script language='JavaScript'>
                                alert('Unable to processing Checkout. Update Item');
                                location.history.back(-1);
                            </script>";
                    }

                }
            }

            // TODO: Redirect ke halaman lain untuk pembayaran dengan paypal
            echo "<script language='JavaScript'>
                        location.href = 'checkout.php';
                    </script>";
        } else {
            echo "<script language='JavaScript'>
                        alert('Checkout failed');
                        location.history.back(-1);
                    </script>";
        }
    }

    $query_transaction = mysql_query("SELECT * FROM  `transaction`
                WHERE `id_member` = '" . $loggedin["id_member"] . "' 
                AND `status` = 'process'
                AND `konfirm` = 'not confirmated'
                ORDER BY `id_transaction` DESC lIMIT 0,1");
    $count_transaction = mysql_num_rows($query_transaction);
    $row_transaction = mysql_fetch_array($query_transaction);

} else {
    echo "<script language='JavaScript'>
        alert('You need to login first before transaction');
        location.href = 'login.php';
      </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <title>Cart - SeaGods Wetsuit</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"/>
    <link rel="apple-touch-icon" href="../admin/pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../admin/pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../admin/pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../admin/pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="../admin/favicon.ico"/>
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
    <link class="main-stylesheet" href="../admin/pages/css/pages.css" rel="stylesheet" type="text/css"/>
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
              --></div>
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


                <li >
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
            <div class=" container    container-fixed-lg">
                <div id="rootwizard" class="m-t-50">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator nav-stack-sm" role="tablist"
                        data-init-reponsive-tabs="dropdownfx">
                        <li class="nav-item">
                            <a class="active" data-toggle="tab" href="#tab1" role="tab"><i
                                        class="fa fa-shopping-cart tab-icon"></i> <span>Your cart</span></a>
                        </li>

                    </ul>
                    <!-- Tab panes -->

                    <form role="form" method="post" action="cart.php">
                        <div class="tab-content">
                            <div class="tab-pane padding-20 sm-no-padding active slide-left" id="tab1">
                                <div class="row row-same-height">
                                    <div class="col-md-5 b-r b-dashed b-grey sm-b-b">
                                        <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                                            <i class="fa fa-shopping-cart fa-2x hint-text"></i>
                                            <?php
                                            if ($count_transaction != 0) {
                                                echo '<h2>Your Bags are ready to check out!</h2>
                                                    <p>Thanks. Here is a list of your shopping items.</p>
                                                    <p class="small hint-text">Take the next step to go to the shipping info and billing details.</p>';
                                            } else {
                                                echo '<h2>Your Bags is empty!</h2>';
                                            }
                                            ?>

                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="padding-30 sm-padding-5">
                                            <?php
                                            if ($count_transaction != 0) {
                                                $item_total = 0;

                                                ?>
                                                <table class="table table-condensed">
                                                    <?php

                                                    $query_cart = mysql_query("SELECT * FROM `cart` 
                                                        WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "'
                                                        AND `level` = '0';");

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
                                                            <td class="col-lg-8 col-md-6 col-sm-7 ">
                                                                <a href="cart.php?remove=<?php echo $row_cart["id_cart"] . '-' . $row_cart["id_transaction"]; ?>"><i
                                                                            class="pg-close"></i></a>
                                                                <span class="m-l-10 font-montserrat fs-11 all-caps"><?php echo (isset($row_item["title"]) ? $row_item["title"] : 'Custom Cart'); ?></span>
                                                            </td>
                                                            <td class=" col-lg-2 col-md-3 col-sm-3 text-right">
                                                                <span>Qty <?php echo $row_cart['qty']; ?></span>
                                                            </td>
                                                            <td class=" col-lg-2 col-md-3 col-sm-2 text-right">
                                                                <h4 class="text-primary no-margin font-montserrat">
                                                                    $ <?php echo $row_item['price'] ?></h4>
                                                            </td>

                                                            <input type="hidden"
                                                                   value="<?php echo (isset($row_item['title']) ? $row_item['title'] : 'Custom Cart'); ?>"
                                                                   id="title_<?php echo $a; ?>" name="title[]">
                                                            <input type="hidden"
                                                                   value="<?php echo $row_cart['qty']; ?>"
                                                                   id="qty_<?php echo $a; ?>" name="qty[]">
                                                            <input type="hidden"
                                                                   value="<?php echo $row_item['price']; ?>"
                                                                   id="price_<?php echo $a; ?>" name="price[]">
                                                            <input type="hidden"
                                                                   value="<?php echo $row_cart['id_item']; ?>"
                                                                   class="items" name="id_item[]">
                                                            <input type="hidden"
                                                                   value="<?php echo $loggedin["id_member"]; ?>"
                                                                   name="id_member[]">
                                                        </tr>
                                                        <?php
                                                        $item_total += ($row_item["price"] * $row_cart["qty"]);
                                                        $a++;
                                                    }
                                                    ?>
                                                    <input type="hidden" name="amount" id="amount"
                                                           value="<?php echo $item_total; ?>">
                                                </table>

                                                <div class="row">

                                                </div>
                                                <br>
                                                <div class="row b-a b-grey no-margin">
                                                    <div class="col-md-3 p-l-10 sm-padding-15 align-items-center d-flex">
                                                        <div>
                                                            <h5 class="font-montserrat all-caps small no-margin hint-text bold"></h5>
                                                            <p class="no-margin"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7 col-middle sm-padding-15 align-items-center d-flex">
                                                        <div>
                                                            <h5 class="font-montserrat all-caps small no-margin hint-text bold"></h5>
                                                            <p class="no-margin"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-right bg-primary padding-10">
                                                        <h5 class="font-montserrat all-caps small no-margin hint-text text-white bold">
                                                            Total</h5>
                                                        <h4 class="no-margin text-white">
                                                            $ <?php echo $item_total; ?></h4>
                                                    </div>

                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="padding-20 sm-padding-5 sm-m-b-20 sm-m-t-20 bg-white clearfix">
                                <ul class="pager wizard no-style">
                                    <li class="">
                                        <input class="btn btn-primary btn-cons btn-animated from-left fa fa-cog pull-right"
                                               type="submit" name="checkout" value="Checkout">
                                    </li>
                                    <li class="">
                                        <a href="list_product.php" class="btn btn-default btn-cons pull-right"><span>Shop Again</span></a>
                                    </li>

                                </ul>
                            </div>
                    </form>

                    <div class="wizard-footer padding-20 bg-master-light">
                        <p class="small hint-text pull-left no-margin">

                            SEAGODS WETSUIT

                            <br>By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia .

                        </p>
                        <div class="pull-right">
                            <img src="member/assets/img/s-logo.png" alt="logo" data-src="member/assets/img/s-logo.png"
                                 data-src-retina="member/assets/img/s-logo.png" height="22">
                        </div>
                        <div class="clearfix"></div>
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
</body>
</html>