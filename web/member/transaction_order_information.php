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

if ($loggedin = logged_in()) {//  Check if they are logged in

    // Set title
    $titlebar = "Order Information";
    $titlepage = "Order Information";
    $menu = "";
    $user = '' . $loggedin['username'] . '';

    if (isset($_GET['id'])) {

        // Set transaction
        $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `id_transaction` = '" . $_GET['id'] . "' LIMIT 0,1;");
        if (mysql_num_rows($transaction_query) == 0) {
            echo "<script>
                alert('Transaction not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_transaction = mysql_fetch_assoc($transaction_query);

        // Set transaction shipping
        $transaction_shipping_query = mysql_query("SELECT * FROM `transaction_shipping` WHERE `id_transaction` = '" . $row_transaction['id_transaction'] . "' LIMIT 0,1;");
        if (mysql_num_rows($transaction_shipping_query) == 0) {
            echo "<script>
                alert('Transaction shipping not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_transaction_shipping = mysql_fetch_assoc($transaction_shipping_query);

        // Set transaction shipping address
        $transaction_shipping_address_query = mysql_query("SELECT * FROM `transaction_shipping_address` WHERE `id_transaction` = '" . $row_transaction['id_transaction'] . "' LIMIT 0,1;");
        if (mysql_num_rows($transaction_shipping_address_query) == 0) {
            echo "<script>
                alert('Transaction shipping address not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_transaction_shipping_address = mysql_fetch_assoc($transaction_shipping_address_query);

        // Get cart
        $cart_query = mysql_query("SELECT `id_cart`, `id_item`, `is_custom_cart`, `qty`, `amount` FROM `cart` 
            WHERE `id_transaction` = '" . $row_transaction['id_transaction'] . "' AND `level` = '0';");
        if (mysql_num_rows($cart_query) == 0) {
            echo "<script>
                alert('Cart data not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_carts = mysql_fetch_assoc($cart_query);

        // Check transaction type bank or paypal
        if ($row_transaction['payment_method'] == 'Paypal') {

            // Set paypal
            $paypal_query = mysql_query("SELECT `paymentId`, `status`, `amount`, `description` FROM `paypals` 
                WHERE `id_transaction` = '" . $row_transaction['id_transaction'] . "' AND `level` = '0';");
            if (mysql_num_rows($paypal_query) == 0) {
                echo "<script>
                    alert('Paypal date not found');
                    window.history.back(-1);
                </script>";
                exit();
            }

        }

    } else {
        echo "<script>
            alert('Missing parameter');
            window.history.back(-1);
        </script>";
        exit();
    }

    $content = '
        <div class="content ">
            <div class=" container container-fixed-lg">
                
                <!-- START card -->
                <div class="card card-default">
                    <div class="card-block no-scroll card-toolbar">
                        Your transaction order
                        Testing
                        <a class="btn btn-xs btn-primary" href="list-transaction.php">Go to List Transaction</a>
                    </div>
                </div>
                <!-- END card -->
                
            </div>
        </div>';

    $plugin = '
    <link href="assets/plugins/summernote/css/summernote.css" rel="stylesheet" type="text/css" media="screen">
	
    <script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script src="assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
    <script src="assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/form_elements.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <script>
        
        // Function for format number
        function formatNumber(num) {
          return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \'$1,\');
        }
        
        // Set default value amount
        var amount = $("#amount");
        amount.val(formatNumber(amount.val()));
        
         // On change amount
        function changeAmount() {
            var changeAmount = amount.val();
            changeAmount = changeAmount.toString().replace(/,/g, "");
            amount.val(formatNumber(changeAmount));
        }
        
    </script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>