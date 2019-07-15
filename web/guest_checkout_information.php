<?php
include("config/configuration.php");
include("config/template_cart.php");
include("config/currency_types.php");
include("config/shipping/action_raja_ongkir.php");
include("config/shipping/province_city.php");
session_start();
ob_start();

$titlebar = "Guest Checkout";
$menu = "";

if (isset($_GET['id']) && isset($_GET['transaction'])) {

    // Set transaction
    $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `id_transaction` = '" . $_GET['id'] . "' 
        AND `kode_transaction` = '" . $_GET['transaction'] . "' LIMIT 0,1;");
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
    if ($row_transaction['payment_method'] == 'Bank Transfer') {

        // Set bank transfer
        $bank_transfer_query = mysql_query("SELECT `id_bank`, `from_bank`, `account_number`, `amount`, `photo` FROM `bank_transfer` 
            WHERE `id_transaction` = '" . $row_transaction['id_transaction'] . "' AND `level` = '0';");
        if (mysql_num_rows($bank_transfer_query) == 0) {
            echo "<script>
                alert('Bank transfer data not found');
                window.history.back(-1);
            </script>";
            exit();
        }

    } else {

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
    <div id="Content" class="p-t-0 page-container">
        <div class="content_wrapper clearfix">
            <div class="" style="margin: 0 auto; width: 70%">
                <div class="section mcb-section p-t-0 p-b-20  full-width">
                    <div class="section_wrapper mcb-section-inner one m-l-0 m-r-0">
                        <div class="wrap mcb-wrap one valign-top clearfix">
                                                
                            <div class="column three-fourth full-width m-b-0">
                              <h3 class="fw-500">Register Guest (Checkout Order Items)</h3>
                            </div>
                            <div class="column three-fourth full-width bg-white p-b-30">                            
                                <div class="wrap mcb-wrap full-width">
                                    
                                    <div class="full-width padding-30 wrap mcb-wrap">
                                        <p class="fs-20 fw-600 text-blue-light">Checkout as Guest</p>
                                        <div class="width-90 m-b-25">
                                            <p class="fs-12 text-black m-b-0 fw-500">
                                                Your Transaction information
                                            </p>
                                        </div>
                                        <div class="width-90 m-b-25">
                                            <a class="btn btn-sm btn-primary" href="home.php">Go to Web</a>
                                        </div>
                                        
                                    </div>
                                                                                            
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

$plugin = '';

$template = admin_template($content, $titlebar, $titlepage = "", $user = "", $menu, $plugin);

echo $template;

?>