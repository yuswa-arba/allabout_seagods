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

if (isset($_POST['nilai'])) {
    $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
} else {
    $_SESSION['nilai_login'] = 0;
}

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    //$loggedin = logged_inadmin();
    $titlebar = "Transaction Details";
    $titlepage = "Transaction Details";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET["id_transaction"])) {

        $id_transaction = isset($_GET['id_transaction']) ? strip_tags(trim($_GET['id_transaction'])) : "";

        $sql_transaction = mysql_query("SELECT * FROM `transaction` WHERE `id_transaction` = '$id_transaction' ORDER BY `date_add` ");
        $row_transaction = mysql_fetch_array($sql_transaction);

        $sql_member = mysql_query("SELECT * FROM `member` where `id_member` = '$row_transaction[id_member]'");
        $row_member = mysql_fetch_array($sql_member);

    }

    $content = '
        <div class="page-container ">
            
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
        
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class=" container    container-fixed-lg">
            
                        <!-- START card -->
                        <div class="card card-transparent">
                            <div class="card-header ">
                                <div class="card-title">Transaction Details
                                    <br><br>Buyer  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>' . $row_member['firstname'] . ' ' . $row_member['lastname'] . ' </strong><br>Invoice Number &nbsp;&nbsp;&nbsp;<strong>' . $row_transaction['kode_transaction'] . '</strong>
                                </div>
                                <div class="pull-right"><a href="list_transaction.php'. (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') .'" class="btn btn-outline-info">Back to List</a></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-block">
                                <form action="">
                                    <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                        <thead>
                                            <tr>
                                                <th >Product Name</th>
                                                <th style="width:10%">QTY</th>
                                                <th style="width:25%">Price</th>
                                                <th style="width:25%">Amount</th>
                                                <th style="width:5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>';

    $sql_cart = null;
    if (isset($_GET["id_transaction"])) {

        $sql_cart = mysql_query("SELECT * FROM `cart` 
                        WHERE `id_member`='" . $row_transaction["id_member"] . "'
                        AND `id_transaction` = '" . $_GET["id_transaction"] . "'
                        AND `level` = '0' ORDER BY `date_add`");

        while ($row_cart = mysql_fetch_array($sql_cart)) {

            if (!$row_cart['is_custom_cart']) {

                $query_item = mysql_query("SELECT * FROM `item` 
                    WHERE `id_item` = '" . $row_cart["id_item"] . "';");
                $row_item = mysql_fetch_array($query_item);

                // Set price
                $price = $row_item['price'];

            } else {

                $query_item = mysql_query("SELECT * FROM `custom_collection` 
                    WHERE `id_custom_collection` = '" . $row_cart["id_item"] . "';");
                $row_item = mysql_fetch_array($query_item);

                // Set price
                $price = get_price('price-custom-item');

            }

            $content .= '  
                <tr>
                    <td class="v-align-middle">
                        <p>' . (!$row_cart['is_custom_cart'] ? $row_item['title'] : "Custom Wetsuit") . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>' . $row_cart['qty'] . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>$ ' . round($price, 2) . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>$ ' . round(($row_cart['qty'] * $price), 2) . '</p>
                    </td>
                    <td class="v-align-middle">
                        <a href="detail_transaction_item.php?id_transaction=' . $row_transaction['id_transaction'] . '&id_cart=' . $row_cart['id_cart'] . '" class="btn btn-xs btn-success pull-right" name="">Detail Item</a>
                    </td>
			    </tr>';
        }
    }

    $totalTransaction = 0;
    if (isset($_GET["id_transaction"]) && $sql_cart != null) {
        if (mysql_num_rows($sql_cart) > 0) {

            // Set first cart
            $query_cart_first = mysql_query("SELECT * FROM `cart` WHERE `id_transaction` = '" . $_GET["id_transaction"] . "'
                AND `id_member`='" . $row_transaction["id_member"] . "' AND `level` = '0' LIMIT 0,1;");
            $row_cart_first = mysql_fetch_array($query_cart_first);

            // Set total transaction
            $totalTransaction = round($row_transaction['total'], 2);

        }
    }

    $content .= '
                                            <tr> 
                                                <td colspan="5" >Total : <b style="font-size: 20px;">$ ' . $totalTransaction . '</b></td> 
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
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
    header('Location: logout.php');
}

?>