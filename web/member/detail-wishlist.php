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

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = "Detail Wishlist";
    $titlepage = "Detail Wishlist";
    $menu = "";
    $user = '' . $loggedin['username'] . '';

    if (isset($_GET["id_wishlist"])) {

        $id_wishlist = isset($_GET['id_wishlist']) ? strip_tags(trim($_GET['id_wishlist'])) : "";

        $sql_wishlist = mysql_query("SELECT * FROM `wishlist` WHERE `id_member`='" . $loggedin["id_member"] . "' AND `id_wishlist` = '$id_wishlist'");
        $row_wishlist = mysql_fetch_array($sql_wishlist);

        $sql_member = mysql_query("SELECT * FROM `member` where `id_member` ='" . $loggedin["id_member"] . "' ");
        $row_member = mysql_fetch_array($sql_member);

    }

    $content = '
        <div class="page-container ">
        
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
            
                <!-- START PAGE CONTENT -->
                <div class="content ">
                
                    <div class="container container-fixed-lg">
                        <!-- START card -->
                        <div class="card card-transparent">
                            <div class="card-header ">
                                <div class="card-title">Detail Transaction
                                    <br><br>Buyer  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>' . $row_member['firstname'] . ' ' . $row_member['lastname'] . ' </strong>
                                </div>
                                <div class="clearfix pull-right"><a class="btn btn-default" href="wishlist.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '">Back to List</a></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th >Product Name</th>
                                            <th style="width:15%">Qty</th>
                                            <th style="width:25%">Price</th>
                                            <th style="width:25%">Amount</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    if (isset($_GET["id_wishlist"])) {

        $query_item = mysql_query("SELECT * FROM `item` WHERE `code` = '" . $row_wishlist["code"] . "' LIMIT 0,1;");
        $row_item = mysql_fetch_array($query_item);

        $content .= '
            <tr>
                <td class="v-align-middle">
                    <p>' . $row_item['title'] . '</p>
                </td>
                <td class="v-align-middle">
                    <p>' . $row_wishlist['qty'] . '</p>
                </td>
                <td class="v-align-middle">
                    <p>' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? $row_item['price'] : number_format(($row_item['price'] * $USDtoIDR), 0, '.', ',')) . '</p>
                </td>
                <td class="v-align-middle">
                    <p>' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? $row_wishlist['amount'] : number_format(($row_wishlist['amount'] * $USDtoIDR), 0, '.', ',')) . '</p>
                </td>
                <td class="v-align-middle">
                    <a class="btn btn-xs btn-success pull-right" href="detail-wishlist-item.php?id_wishlist=' . $row_wishlist['id_wishlist'] . '&id_item=' . $row_item['id_item'] . '">Detail Item</a>
                </td>
            </tr>';

    }

    $content .= '
                                        <tr> 
                                            <td colspan="5" >Total : ' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? $row_wishlist['amount'] : number_format(($row_wishlist['amount'] * $USDtoIDR), 0, '.', ',')) . '</td> 
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END card -->
                    </div>
                </div>
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