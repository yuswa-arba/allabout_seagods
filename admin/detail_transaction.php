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

    // Set USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    //$loggedin = logged_inadmin();
    $titlebar = "Transaction Details";
    $titlepage = "Transaction Details";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET["id_transaction"])) {

        $id_transaction = isset($_GET['id_transaction']) ? strip_tags(trim($_GET['id_transaction'])) : "";

        $sql_transaction = mysql_query("SELECT * FROM `transaction` WHERE `id_transaction` = '$id_transaction' ORDER BY `date_add` ");
        $row_transaction = mysql_fetch_array($sql_transaction);

        if ($row_transaction['is_guest']) {

            // Set guest
            $guest_query = mysql_query("SELECT * FROM `guest` WHERE `id` = '" . $row_transaction["id_guest"] . "' LIMIT 0,1;");
            $row_guest = mysql_fetch_array($guest_query);

            // Set holder name
            $holder_name = $row_guest['first_name'] . ' ' . $row_guest['last_name'];

        } else {

            // Set member
            $sql_member = mysql_query("SELECT * FROM `member` where `id_member` ='" . $row_transaction["id_member"] . "' ");
            $row_member = mysql_fetch_array($sql_member);

            // Set holder name
            $holder_name = $row_member['firstname'] . ' ' . $row_member['lastname'];

        }


        if ($row_transaction['payment_method'] == 'Bank Transfer') {

            // Set bank transfer
            $bank_transfer_query = mysql_query("SELECT * FROM `bank_transfer` WHERE `id_transaction` = '" . $row_transaction["id_transaction"] . "' AND `level` = '0' ORDER BY `id` DESC LIMIT 0,1;");
            $row_bank_transfer = mysql_fetch_array($bank_transfer_query);

            // Set bank account
            $bank_query = mysql_query("SELECT * FROM `bank_account` WHERE `id` = '" . $row_bank_transfer["id_bank"] . "' LIMIT 0,1;");
            $row_bank = mysql_fetch_array($bank_query);

        }


    } else {
        echo "<script>
            alert('Transaction ID parameter required');
            window.history.back(-1);
        </script>";
        exit();
    }

    $content = '
        <div class="page-container ">
            
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
        
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class=" container container-fixed-lg">
                    
                        <div class="col-md-12">
                            <div class="card card-default">
                                <div class="card-header ">
                                    <div class="card-title">
                                        <h4><b>Transaction</b> ' . $row_transaction['kode_transaction'] . '</h4>
                                    </div>
                                    <a href="list_transaction.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '" class="btn btn-default pull-right" name="">Back to Transaction</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        
                            <div class="col-md-6">
                                <div class="container">
                                    <div class="card card-default filter-item">
                                        <div class="card-header ">
                                            <div class="card-title">Detail Transaction *</div>
                                        </div>
                                        <div class="card-block">
                                            <div class="col-md-12 show-details">
                                                <label>Transaction Number</label>
                                                <h5><b>' . $row_transaction['kode_transaction'] . '</b></h5>
                        
                                                <label>Status</label>
                                                <h5><b>' . $row_transaction['status'] . '</b></h5>
                        
                                                <label>Status Confirm</label>
                                                <h5><b>' . (($row_transaction['konfirm'] == 'not confirmated') ? "Not yet confirmed" : "Confirmed") . '</b></h5>
                        
                                                <label>Payment Method</label>
                                                <h5><b>' . $row_transaction['payment_method'] . '</b></h5>';

    if ($row_transaction['payment_method'] == 'Bank Transfer') {

        $content .= '
                                                <label>From Bank</label>
                                                <h5><b>' . $row_bank_transfer['from_bank'] . '</b></h5>
                                                
                                                <label>To Bank</label>
                                                <h5><b>' . $row_bank['name'] . ' (' . $row_bank['account_number'] . ')' . '</b></h5>
                                                
                                                <label>Confirmed At</label>
                                                <h5><b>' . ($row_transaction["confirmed_at"] ? $row_transaction["confirmed_at"] : "-") . '</b></h5>
                        
                                                <label>Confirmed By</label>
                                                <h5><b>' . ($row_transaction["confirmed_by"] ? $row_transaction["confirmed_by"] : "-") . '</b></h5>
                        
                                                <label>Proof Transfer</label>
                                                <img style="width: 400px" src="../web/images/evidenceTransfer/' . $row_bank_transfer["photo"] . '">';

    }

    $content .= '
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class=" container">
                                    <div class="card card-default filter-item">
                                        <div class="card-header ">
                                            <div class="card-title">Detail Buyer *</div>
                                        </div>
                                        <div class="card-block">
                                            <div class="col-md-12 show-details">
                                                <label>First Name</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? $row_guest["first_name"] : $row_member["firstname"]) . '</b></h5>
                        
                                                <label>Last Name</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? $row_guest["last_name"] : $row_member["lastname"]) . '</b></h5>
                        
                                                <label>Email</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? $row_guest["email"] : $row_member["email"]) . '</b></h5>
                        
                                                <label>Phone Number</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? $row_guest["phone_no"] : $row_member["notelp"]) . '</b></h5>';

    // Set Province ID
    $id_province = ($row_transaction['is_guest'] ? $row_guest["id_province"] : $row_member["idpropinsi"]);

    $province_query = mysql_query("SELECT * FROM `provinsi` WHERE `idProvinsi` = '$id_province' LIMIT 0,1;");
    $row_province = mysql_fetch_array($province_query);

    // Set City ID
    $id_city = ($row_transaction['is_guest'] ? $row_guest["id_city"] : $row_member["idkota"]);

    // Set City ID
    $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '$id_city' LIMIT 0,1;");
    $row_city = mysql_fetch_array($city_query);

    $content .= '
                                                <label>Province</label>
                                                <h5><b>' . ($row_province["namaProvinsi"] ? $row_province["namaProvinsi"] : "-") . '</b></h5>
                                                
                                                <label>City</label>
                                                <h5><b>' . ($row_city["namaKota"] ? $row_city["namaKota"] : "-") . '</b></h5>
                                                
                                                <label>Address</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? $row_guest["address"] : $row_member["alamat"]) . '</b></h5>
                                                
                                                <label>Address 2</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? "-" : $row_member["alamat2"]) . '</b></h5>
                        
                                                <label>Zip Code</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? $row_guest["zip_code"] : $row_member["kode_pos"]) . '</b></h5>
                                                
                                                <label>Account Number</label>
                                                <h5><b>' . ($row_transaction['is_guest'] ? $row_guest["zip_code"] : $row_member["kode_pos"]) . '</b></h5>
                                                
                                                <label>Account Number</label>
                                                <h5><b>' . (($row_transaction['payment_method'] == 'Bank Transfer') ? $row_bank_transfer["account_number"] : ($row_transaction['is_guest'] ? $row_guest["account_number"] : $row_member["account_number"])) . '</b></h5>
                                                   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
            
                        <!-- START card -->
                        <div class="card card-transparent">
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
                        WHERE `id_transaction` = '" . $_GET["id_transaction"] . "'
                        AND `level` = '0' ORDER BY `date_add` ASC");

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
                        <p>$ ' . number_format(round($price, 2), 2, '.', ',') . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>$ ' . number_format(round(($row_cart['qty'] * $price), 2), 2, '.', ',') . '</p>
                    </td>
                    <td class="v-align-middle">
                        <a href="detail_transaction_item.php?id_transaction=' . $row_transaction['id_transaction'] . '&id_cart=' . $row_cart['id_cart'] . '" class="btn btn-xs btn-success pull-right" name="">Detail Item</a>
                    </td>
			    </tr>';
        }

        // Set shipping
        $shipping_query = mysql_query("SELECT * FROM `transaction_shipping` WHERE `id_transaction` = '" . $_GET["id_transaction"] . "' LIMIT 0,1;");
        if (mysql_num_rows($shipping_query) > 0) {

            // Row shipping
            $row_shipping = mysql_fetch_array($shipping_query);

            // Shipping round
            $shipping_round = round($row_shipping['weight']);

            // Price shipping
            $price_shipping = round(($row_shipping["price"] / $USDtoIDR), 2);

            // Amount shipping
            $amount_shipping = ($shipping_round * $price_shipping);

            $content .= '  
                <tr>
                    <td class="v-align-middle">
                        <p>Shipping</p>
                    </td>
                    <td class="v-align-middle">
                        <p>' . $row_shipping['weight'] . ' (Kg)</p>
                    </td>
                    <td class="v-align-middle">
                        <p>$ ' . number_format($price_shipping, 2, '.', ',') . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>$ ' . number_format($amount_shipping, 2, '.', ',') . '</p>
                    </td>
                    <td class="v-align-middle">
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
                                                <td colspan="5" >Total : <b style="font-size: 20px;">$ ' . number_format($totalTransaction, 2, '.', ',') . '</b></td> 
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