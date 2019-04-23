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

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = "Transaction Detail ";
    $titlepage = "Transaction Detail ";

    $menu = "";
    $user = '' . $_SESSION['user'] . '';

    if (isset($_GET["id_transaction"])) {

        $id_transaction = isset($_GET['id_transaction']) ? strip_tags(trim($_GET['id_transaction'])) : "";


        $sql_transaction = mysql_query("SELECT * FROM `transaction` WHERE `id_member`='" . $loggedin["id_member"] . "' AND `id_transaction` = '$id_transaction' ORDER BY `date_add` ");
        $row_transaction = mysql_fetch_array($sql_transaction);
        $sql_member = mysql_query("SELECT * FROM `member` where `id_member` ='" . $loggedin["id_member"] . "' ");
        $row_member = mysql_fetch_array($sql_member);

    }

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
        <div class="page-container ">
            
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
                
                <!-- START PAGE CONTENT -->
                <div class="content ">
		            <div class=" container    container-fixed-lg">
            
                        <!-- START card -->
                        <div class="card card-transparent">
                            <div class="card-header p-t-15">
                                <div class="bold fs-16 m-b-10">Transaction Details</div>
                                <div class="row">
                                    <div class="col-md-2">Buyer <span class="pull-right">:</span></div>
                                    <div class="col-md-6"><b>' . (isset($row_member['firstname']) ? $row_member['firstname'] . ' ' . $row_member['lastname'] : '-') . '</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">Invoice Number <span class="pull-right">:</span></div>
                                    <div class="col-md-6"><b>' . (isset($row_transaction['kode_transaction']) ? $row_transaction['kode_transaction'] : '-') . '</b></div>
                                </div>
                                <div class="clearfix pull-right"><a href="list-transaction.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '" class="btn btn-default">Back to List</a></div>
                            </div>
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th >Product Name</th>
                                            <th style="width:10%">QTY</th>
                                            <th style="width:25%">Price</th>
                                            <th style="width:25%">Amount</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    $amount_transaction = 0;
    if (isset($_GET["id_transaction"])) {

        $sql_cart = mysql_query("SELECT * FROM `cart` 
            WHERE `id_member`='" . $loggedin["id_member"] . "'
            AND `id_transaction` = '" . $_GET["id_transaction"] . "'
            AND `level` = '0' ORDER BY `date_add`");

        while ($row_cart = mysql_fetch_array($sql_cart)) {

            if (!$row_cart['is_custom_cart']) {
                $query_item = mysql_query("SELECT * FROM `item` 
                    WHERE `id_item` = '" . $row_cart["id_item"] . "';");
                $row_item = mysql_fetch_array($query_item);
            } else {
                $query_item = mysql_query("SELECT * FROM `custom_collection` 
                    WHERE `id_custom_collection` = '" . $row_cart["id_item"] . "';");
                $row_item = mysql_fetch_array($query_item);
            }

            // Set amount
            $amount_cart = (($currency_code == CURRENCY_USD_CODE) ? $row_cart['amount'] : ($row_cart['amount'] * $USDtoIDR));

            $content .= '  
                                        <tr>
                                            <td class="v-align-middle">
                                                <p>' . (!$row_cart['is_custom_cart'] ? $row_item['title'] : "Custom Wetsuit") . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>' . $row_cart['qty'] . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? number_format($row_item['price'], 2, '.', ',') : number_format(($row_item['price'] * $USDtoIDR), 0, '.', ',')) . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>' . $currency . ' ' . number_format($amount_cart, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <a class="btn btn-xs btn-success pull-right" href="detail-transaction-item.php?id_transaction=' . $row_transaction['id_transaction'] . '&id_cart=' . $row_cart['id_cart'] . '">Detail Item</a>
                                            </td>
					                    </tr>';

            $amount_transaction += $amount_cart;
        }

        // Set shipping
        $shipping_query = mysql_query("SELECT * FROM `transaction_shipping` WHERE `id_transaction` = '" . $_GET["id_transaction"] . "' LIMIT 0,1;");
        if (mysql_num_rows($shipping_query) > 0) {

            // Row shipping
            $row_shipping = mysql_fetch_array($shipping_query);

            // Shipping round
            $shipping_round = (($row_shipping['weight'] < 1) ? 1 : round($row_shipping['weight']));

            // Price shipping
            $price_shipping = (($currency_code == CURRENCY_USD_CODE) ? round(($row_shipping["price"] / $USDtoIDR), 2) : $row_shipping["price"]);

            // Amount shipping
            $amount_shipping = ($shipping_round * $price_shipping);

            // Set amount transaction shipping
            $amount_transaction += $amount_shipping;

            $content .= '  
                <tr>
                    <td class="v-align-middle">
                        <p>Shipping</p>
                    </td>
                    <td class="v-align-middle">
                        <p>' . $row_shipping['weight'] . ' (Kg)</p>
                    </td>
                    <td class="v-align-middle">
                        <p>'.$currency.' ' . number_format($price_shipping, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>'.$currency.' ' . number_format($amount_shipping, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') . '</p>
                    </td>
                    <td class="v-align-middle">
                    </td>
			    </tr>';

        }

    }

    $content .= '
                                        <tr > 
                                            <td colspan="5" >
                                                <h4 class="normal">
                                                    <span class="fs-17">Total : </span>
                                                    <b class="bold">' . $currency . ' ' . (isset($row_transaction['total']) ? number_format($amount_transaction, (($currency_code == CURRENCY_USD_CODE) ? 2 : 0), '.', ',') : '-') . '</b>
                                                </h4>
                                            </td> 
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