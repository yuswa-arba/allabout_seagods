<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("config/currency_types.php");
session_start();
ob_start();

if ($loggedin = logged_in()) {

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

    $titlebar = "Wishlist Detail Item";
    $titlepage = "Wishlist Detail Item";
    $menu = "";
    $user = '' . $loggedin['username'] . '';

    if (isset($_GET["id_wishlist"]) && isset($_GET['id_item'])) {

        // Set value request
        $id_wishlist = isset($_GET['id_wishlist']) ? strip_tags(trim($_GET['id_wishlist'])) : "";
        $id_item = isset($_GET['id_item']) ? strip_tags(trim($_GET['id_item'])) : "";

        // Set Wishlist
        $wishlist_query = mysql_query("SELECT * FROM `wishlist` WHERE `id_wishlist` = '$id_wishlist' LIMIT 0,1;");
        if (mysql_num_rows($wishlist_query) == 0) {
            echo "<script>
                alert('Wishlist not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_wishlist = mysql_fetch_array($wishlist_query);


        // Set Item
        $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '$id_item' LIMIT 0,1;");
        if (mysql_num_rows($item_query) == 0) {
            echo "<script>
                alert('Item not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_item = mysql_fetch_array($item_query);

    } else {
        echo "<script>
            alert('Parameter can\'t empty.!!');
            window.history.back(-1);
        </script>";
        exit();
    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">';

    $query_category = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '" . $row_item["id_category"] . "' LIMIT 0,1;");
    $row_category = mysql_fetch_array($query_category);

    $query_sub_category = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '" . $row_item["id_cat"] . "' LIMIT 0,1;");
    $row_sub_category = mysql_fetch_array($query_sub_category);

    // Set nominal curs from USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    $content .= '
                <div class="card card-default">
                    <div class="card-header ">
                        <div class="card-title">
                            <h4><b>' . $row_item["title"] . '</b></h4>
                        </div>
                        <a href="detail-wishlist.php?id_wishlist=' . $row_wishlist['id_wishlist'] . '" class="btn btn-default pull-right" name="">Back to Transaction</a>
                    </div>
                </div>
                        
                <div class="card card-default">
                    <div class="card-block">
                        <div class="row">
                        
                            <div class="col-md-4 detail-container">
                                <label>Title :</label>
                                <h5><b>' . $row_item["title"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-4 detail-container">
                                <label>Code :</label>
                                <h5><b>' . $row_item["code"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Price :</label>
                                <h5><b>' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? $row_item["price"] : number_format(($row_item["price"] * $USDtoIDR), 0, '.', ',')) . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Weight :</label>
                                <h5><b>' . $row_item["weight"] . ' Kg</b></h5>
                            </div>
                            
                        </div><br>
                        
                        <div class="row">
                        
                            <div class="col-md-4 detail-container">
                                <label>Category :</label>
                                <h5><b>' . $row_category["category"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-4 detail-container">
                                <label>Sub Category :</label>
                                <h5><b>' . $row_sub_category["category"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Date Add :</label>
                                <h5><b>' . $row_item["date_add"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Date Update :</label>
                                <h5><b>' . $row_item["date_upd"] . '</b></h5>
                            </div>
                            
                        </div><br>
                        
                        <div class="row">
                        
                            <div class="col-md-4 detail-container">
                                <label>Detail :</label>
                                <h5><b>' . $row_item["detail"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-8 detail-container">
                                <label>Description :</label>
                                <h5><b>' . $row_item["description"] . '</b></h5>
                            </div>
                            
                        </div><br>
                    </div>
                </div>
                
                <div class="card card-default">
                    <div class="card-header ">
                        <div class="card-title">
                            <label><b>Photo</b></h4>
                        </div>
                    </div>
                    <div class="card-block">
                                <div class="row">';

    $query_photo = mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $row_item["id_item"] . "' AND `level` = '0';");

    while ($row_photo = mysql_fetch_array($query_photo)) {

        $content .= '
                                    <div class="col-md-4 no-padding p-r-5">
                                       <div class="form-group form-group-default">
                                           <img style="width: 100%; height: 100%; max-height: 300px;" src="../../admin/images/product/600/thumb_' . strip_tags(trim($row_photo["photo"])) . '">
                                       </div>
                                    </div>';

    }

    $content .= '
                             </div>
                        </div>';

    $content .= '
            </div>
        </div>';

    $plugin = '     
    <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
	
	<!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/datatables.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('location:../login.php');
}

?>