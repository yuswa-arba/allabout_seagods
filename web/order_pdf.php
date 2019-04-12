<?php
include('config/configuration.php');
include('config/currency_types.php');
include('config/shipping/action_raja_ongkir.php');
include('config/shipping/province_city.php');
include('plugins/mpdf/mpdf.php');

// Check transaction ID
if (isset($_GET['id_transaction'])) {

    // Set transaction ID
    $id_transaction = isset($_GET['id_transaction']) ? mysql_real_escape_string(trim($_GET['id_transaction'])) : '';

    // Set transaction
    $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `id_transaction` = '$id_transaction' LIMIT 0,1;");
    if (mysql_num_rows($transaction_query) == 0) {
        echo "<script>
            alert('Transaction not found');
            window.history.back(-1);
        </script>";
        exit();
    }
    $row_transaction = mysql_fetch_assoc($transaction_query);

} else {
    echo "<script>
        alert('Transaction ID paramter required');
        window.history.back(-1);
    </script>";
    exit();
}

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

function number_format_many($number, $decimals = 0)
{
    return number_format($number, $decimals, '.', ',');
}

// Set template
$template_order = template_order($row_transaction);

$mpdf = new mPDF();
$mpdf->WriteHTML($template_order);
$mpdf->Output();

function template_order($transaction)
{
    // Set currency
    $currency = (($transaction['payment_method'] == 'Paypal') ? CURRENCY_USD : CURRENCY_IDR);

    // Set USD ro IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    // Set buyer
    if ($transaction['is_guest']) {

        // Set Guest
        $guest_query = mysql_query("SELECT * FROM `guest` WHERE `id` = '" . $transaction["id_guest"] . "' LIMIT 0,1");
        $row_buyer = mysql_fetch_assoc($guest_query);

    } else {

        // Set Guest
        $member_query = mysql_query("SELECT * FROM `member` WHERE `id_member` = '" . $transaction["id_member"] . "' LIMIT 0,1");
        $row_buyer = mysql_fetch_assoc($member_query);

    }

    $template = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Order</title>
        
            <style>
                body {
                    font-family: Helvetica, Arial, sans;
                    color: #494949;
                    font-size: 12px;
                    /*background-color: rgb(234,234,234);*/
                    margin: 0px;
                    height: 100%
                }
        
                h1 {
                    font-size: 30px;
                    font-weight: 800;
                }
        
                h2 {
                    font-size: 23px;
                    font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                }
        
                p {
                    padding: 0;
                    margin: 0;
                }
        
                /*Table*/
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
        
                table thead tr th, table tbody tr td, table tfoot tr td {
                    padding: 0;
                }
        
                table.header-invoice tbody tr td {
                    padding-top: 2px;
                    padding-bottom: 2px;
                    padding-left: 0;
                    padding-right: 0;
                }
        
        
                /*Width %*/
                .w-100 {
                    width: 100%;
                }
                .w-95 {
                    width: 95%;
                }
                .w-90 {
                    width: 90%;
                }
                .w-85 {
                    width: 85%;
                }
                .w-80 {
                    width: 80%;
                }
                .w-75 {
                    width: 75%;
                }
                .w-70 {
                    width: 70%;
                }
                .w-60 {
                    width: 60%;
                }
                .w-55 {
                    width: 55%;
                }
                .w-50 {
                    width: 50%;
                }
                .w-40 {
                    width: 40%;
                }
                .w-35 {
                    width: 35%;
                }
                .w-30 {
                    width: 30%;
                }
                .w-25 {
                    width: 25%;
                }
                .w-20 {
                    width: 20%;
                }
                .w-15 {
                    width: 15%;
                }
        
        
                /*Background*/
                .bg-black-dop {
                    background-color: #242424;
                }
                .bg-blue-base {
                    background-color: #1187cc;
                }
        
                /*Color*/
                .color-white {
                    color: #fff;
                }
                .color-silver {
                    color: #d6d6d6;
                }
                .color-black-dop {
                    color: #333;
                }
                .color-black {
                    color: #000;
                }
        
                /*Position*/
                .pull-left {
                    float: left;
                }
                .pull-right {
                    float: right;
                }
        
                .text-center {
                    text-align: center;
                }
                .text-left {
                    text-align: left;
                }
                .text-right {
                    text-align: right;
                }
                .text-v-top {
                    vertical-align: top;
                }
                .text-v-center {
                    vertical-align: middle;
                }
                .text-v-bottom {
                    vertical-align: bottom;
                }
        
                .display-block {
                    display: block;
                }
                .display-flow-root {
                    display: flow-root;
                }
        
        
                /*padding*/
                .padding-5 {
                    padding: 5px;
                }
                .padding-10 {
                    padding: 10px;
                }
                .padding-15 {
                    padding: 15px;
                }
                .padding-20 {
                    padding: 20px;
                }
                .padding-25 {
                    padding: 25px;
                }
                .padding-30 {
                    padding: 30px;
                }
                .padding-35 {
                    padding: 35px;
                }
        
                .p-t-0 {
                    padding-top: 0;
                }
                .p-t-5 {
                    padding-top: 5px;
                }
                .p-t-10 {
                    padding-top: 10px;
                }
                .p-t-15 {
                    padding-top: 15px;
                }
                .p-t-20 {
                    padding-top: 20px;
                }
        
                .p-l-5 {
                    padding-left: 5px;
                }
                .p-l-10 {
                    padding-left: 10px;
                }
                .p-l-15 {
                    padding-left: 15px;
                }
                .p-l-20 {
                    padding-left: 20px;
                }
                .p-l-25 {
                    padding-left: 25px;
                }
                .p-l-30 {
                    padding-left: 30px;
                }
        
                .p-r-5 {
                    padding-right: 5px;
                }
                .p-r-10 {
                    padding-right: 10px;
                }
                .p-r-15 {
                    padding-right: 15px;
                }
                .p-r-20 {
                    padding-right: 20px;
                }
                .p-r-25 {
                    padding-right: 25px;
                }
                .p-r-30 {
                    padding-right: 30px;
                }
        
                .p-b-0 {
                    padding-bottom: 0;
                }
                .p-b-5 {
                    padding-bottom: 5px;
                }
                .p-b-10 {
                    padding-bottom: 10px;
                }
                .p-b-15 {
                    padding-bottom: 15px;
                }
        
        
                /*Margin*/
                .m-t-0 {
                    margin-top: 0;
                }
                .m-t-5 {
                    margin-top: 5px;
                }
                .m-t-10 {
                    margin-top: 10px;
                }
                .m-t-15 {
                    margin-top: 15px;
                }
                .m-t-20 {
                    margin-top: 20px;
                }
                .m-t-25 {
                    margin-top: 25px;
                }
        
        
                .fs-12 {
                    font-size: 12px;
                }
                .fs-13 {
                    font-size: 13px;
                }
                .fs-14 {
                    font-size: 14px;
                }
                .fs-15 {
                    font-size: 15px;
                }
                .fs-16 {
                    font-size: 16px;
                }
                .fs-18 {
                    font-size: 18px;
                }
                .fs-30 {
                    font-size: 30px;
                }
                .fs-35 {
                    font-size: 35px;
                }
        
        
                .no-padding {
                    padding: 0 !important;
                }
                .no-margin {
                    margin: 0 !important;
                }
        
        
                .b-b-grey {
                    border-bottom: 1px solid #b7bec6;
                }
        
                .b-b-black-second {
                    border-bottom: 1px solid #333;
                }
        
                .b-black-second {
                    border: 2px solid #333;
                }
        
        
                .block-grey {
                    background-color: #f4f4f4;
                    padding: 10px;
                    border-left: 3px solid #1187cc;
                }
        
                .header {
                    display: flex;
                    align-items: center;
                    /*background-color: #242424;*/
                    padding: 0 0 8px;
                    /*border-bottom: 1px solid #333;*/
                }
        
                .clear-both {
                    clear: both;
                }
        
            </style>
        </head>
        <body>
        <div class="header">
            <div class="w-65" style="padding-top: 8px;">
                <img src="http://seagodswetsuit.com/new/web/images/logo.png" alt="" height="auto">
            </div>
            <div class="pull-right w-35 text-left">
                <p class="fs-35 color-black-dop no-margin"><b>Order</b></p>
                <table class="color-black-dop">
                    <tr>
                        <td class="w-20">No </td>
                        <td class="p-l-5">
                            <span class="pull-right color-black">: </span>
                            <b>' . $transaction["kode_transaction"] . '</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Date </td>
                        <td class="p-l-5">
                            <span class="pull-right color-black">: </span>
                            <b>' . get_date($transaction['date_add'])->format('d/m/Y l, H:i') . '</b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="w-100 m-t-15">
        
            <div class="w-85">
                <table>
                    <tr>
                        <td class="w-15 p-b-5">
                            Name 
                        </td>
                        <td class="p-l-10 p-b-5 fs-14 color-black">
                            <span class="pull-right">: </span>
                            ' . ($transaction['is_guest'] ? ($row_buyer['first_name'] . ' ' . $row_buyer['last_name']) : ($row_buyer['firstname'] . ' ' . $row_buyer['lastname'])) . '
                        </td>
                    </tr>
        
                    <tr>
                        <td class="p-b-5">
                            No Telephone 
                        </td>
                        <td class="p-l-10 p-b-5 fs-14 color-black">
                            <span class="pull-right">: </span>
                            ' . ($transaction['is_guest'] ? $row_buyer['phone_no'] : $row_buyer['notelp']) . '
                        </td>
                    </tr>
        
                    <tr>
                        <td class="p-b-5">
                            Province 
                        </td>
                        <td class="p-l-10 p-b-5 fs-14 color-black">
                            <span class="pull-right">: </span>';

    // Set province ID
    $id_province = ($transaction['is_guest'] ? $row_buyer['id_province'] : $row_buyer['idpropinsi']);

    // Get province
    $get_province = get_province($id_province);

    // Result province
    $template .= $get_province->rajaongkir->results->province;

    $template .= '
                        </td>
                    </tr>
                    <tr>
                        <td class="p-b-5">
                            City 
                        </td>
                        <td class="p-l-10 p-b-5 fs-14 color-black">
                            <span class="pull-right">: </span>';

    // Set city ID
    $id_city = ($transaction['is_guest'] ? $row_buyer['id_city'] : $row_buyer['idkota']);

    // Set parameter
    $parameter_city = [
        'id' => $id_city,
        'province' => $id_province
    ];

    // Get province
    $get_province = get_city($parameter_city);

    // Result province
    $template .= $get_province->rajaongkir->results->city_name;

    $template .= '
                        </td>
                    </tr>
                    <tr>
                        <td class="p-b-5">
                            Postal Code 
                        </td>
                        <td class="p-l-10 p-b-5 fs-14 color-black">
                            <span class="pull-right">: </span>
                            ' . ($transaction['is_guest'] ? $row_buyer['zip_code'] : $row_buyer['kode_pos']) . '
                        </td>
                    </tr>
                    <tr>
                        <td class="text-v-top">
                            Address 
                        </td>
                        <td class="p-l-10 fs-14 color-black">
                            <span class="pull-right">: </span>
                            ' . ($transaction['is_guest'] ? $row_buyer['address'] : $row_buyer['alamat']) . '
                        </td>
                    </tr>
        
                </table>
            </div>
        
            <div class="w-100 m-t-25">
                <table>
                    <tr>
                        <td class="padding-10 bg-blue-base w-15 color-white fs-13">
                            <b>Image</b>
                        </td>
                        <td class="padding-10 bg-blue-base w-30 color-white fs-13">
                            <b>Item</b>
                        </td>
                        <td class="padding-10 bg-blue-base w-23 color-white fs-13">
                            <b>Price</b>
                        </td>
                        <td class="padding-10 bg-blue-base w-7 color-white fs-13 text-center">
                            <b>Qty</b>
                        </td>
                        <td class="padding-10 bg-blue-base w-25 text-right color-white fs-13">
                            <b>Amount</b>
                        </td>
                    </tr>';

    // Get carts
    $cart_query = mysql_query("SELECT * FROM `cart` WHERE `id_transaction` = '" . $transaction['id_transaction'] . "' AND `level` = '0';");

    $total_amount = 0;
    $total_quantity = 0;
    while ($row_cart = mysql_fetch_assoc($cart_query)) {

        // Set item or collection
        if ($row_cart['is_custom_cart']) {

            // Set collection
            $collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '" . $row_cart["id_item"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_assoc($collection_query);

        } else {

            // Set collection
            $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $row_cart["id_item"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_assoc($item_query);

            // Set photo
            $photo_query = mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $row_item["id_item"] . "' LIMIT 0,1;");
            $row_photo = mysql_fetch_assoc($photo_query);
        }

        $template .= '
                    <tr>
                        <td class="padding-10 b-b-black-second">';

        if ($row_cart['is_custom_cart']) {
            $template .= '<img src="http://seagodswetsuit.com/new/web/custom/public/images/custom_cart/' . $row_item['image'] . '" height="auto" width="95px">';
        } else {
            $template .= '<img src="http://seagodswetsuit.com/new/admin/images/product/150/thumb_' . $row_photo['photo'] . '" height="auto"  width="95px">';
        }

        $template .= '
                            </td>
                        <td class="padding-10 b-b-black-second">
                            ' . ($row_cart['is_custom_cart'] ? 'Custom Wetsuit' : $row_item['title']) . '
                        </td>
                        <td class="padding-10 b-b-black-second">
                            ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($row_item['price'], 2) : number_format_many(($row_item['price'] * $USDtoIDR))) . '
                        </td>
                        <td class="padding-10 b-b-black-second text-center">
                            ' . $row_cart['qty'] . '
                        </td>
                        <td class="padding-10 b-b-black-second text-right">
                            ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($row_cart['amount'], 2) : number_format_many(($row_cart['amount'] * $USDtoIDR))) . '
                        </td>
                    </tr>';

        // Set total amount
        $total_amount += $row_cart['amount'];

        // Set total quantity
        $total_quantity += $row_cart['qty'];

    }

    // Set transaction shipping
    $shipping_query = mysql_query("SELECT * FROM `transaction_shipping` WHERE `id_transaction` = '" . $transaction["id_transaction"] . "' LIMIT 0,1;");
    $row_shipping = mysql_fetch_assoc($shipping_query);

    $template .= '
        
                    <tfoot>
                        <tr>
                            <td class="padding-10 text-right fs-12" colspan="4">
                                Sub Total 
                            </td>
                            <td class="padding-10 fs-16 text-right">
                                <span class="pull-right">: </span>
                                <b>' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($total_amount, 2) : number_format_many(($total_amount * $USDtoIDR))) . '</b>
                            </td>
                        </tr>
                    </tfoot>
        
                </table>
            </div>
            
            <div class="w-100 m-t-15">
                <div class="w-60">
                    <p class="no-margin fs-16 color-black"><b>Billing Info</b></p>
                    <div class="padding-10 b-black-second m-t-5">
                        <table>
                            <tr>
                                <td class="w-25 p-b-5">
                                    Qty 
                                </td>
                                <td class="p-l-10 p-b-5 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    ' . $total_quantity . ' Item
                                </td>
                            </tr>
                            <tr>
                                <td class="p-b-5">
                                    Weight 
                                </td>
                                <td class="p-l-10 p-b-5 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    ' . $row_shipping['weight'] . ' Kg
                                </td>
                            </tr>
                            <tr>
                                <td class="p-b-5">
                                    Sub Total 
                                </td>
                                <td class="p-l-10 p-b-5 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($total_amount, 2) : number_format_many(($total_amount * $USDtoIDR))) . '
                                </td>
                            </tr>
                            <tr>
                                <td class="p-b-5">
                                    Shipping Costs 
                                </td>
                                <td class="p-l-10 p-b-5 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many(($row_shipping['amount'] / $USDtoIDR), 2) : number_format_many($row_shipping['amount'])) . '
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    Total Payment 
                                </td>
                                <td class="p-l-10 fs-16 color-black">
                                    <span class="pull-right">: </span>
                                    <b>' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($transaction['total'], 2) : number_format_many($transaction['total'] * $USDtoIDR)) . '</b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
        
                <div class="w-60 m-t-15">
                    <p class="no-margin fs-16 color-black"><b>Information Transaction</b></p>
                    <div class="padding-10 b-black-second m-t-5">
                        <table>
                            <tr>
                                <td class="w-25 p-b-5">
                                    Payment Method 
                                </td>
                                <td class="p-l-10 p-b-5 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    <b>' . $transaction['payment_method'] . '</b>
                                </td>
                            </tr>';

    if ($transaction['payment_method'] == 'Paypal') {

        // Set paypal
        $paypal_query = mysql_query("SELECT * FROM `paypals` WHERE `id_transaction` = '" . $transaction["id_transaction"] . "' AND `level` = '0' LIMIT 0,1;");
        $row_paypal = mysql_fetch_assoc($paypal_query);

        $template .= '
                            <tr>
                                <td class="p-b-5">
                                    PayPal ID 
                                </td>
                                <td class="p-l-10 p-b-5 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    <b>' . $row_paypal['paymentId'] . '</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Date 
                                </td>
                                <td class="p-l-10 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    <b>' . get_date($row_paypal["date_add"])->format('d/m/Y l, H:i') . '</b>
                                </td>
                            </tr>';

    } else {

        // Set bank transfer
        $bank_transfer_query = mysql_query("SELECT * FROM `bank_transfer` WHERE `id_transaction` = '" . $transaction["id_transaction"] . "' AND `level` = '0' LIMIT 0,1;");
        $row_bank_transfer = mysql_fetch_assoc($bank_transfer_query);

        // Set bank account
        $bank_account_query = mysql_query("SELECT * FROM `bank_account` WHERE `id` = '" . $row_bank_transfer["id_bank"] . "' LIMIT 0,1;");
        $row_bank_account = mysql_fetch_assoc($bank_account_query);

        $template .= '
                            <tr>
                                <td class="p-b-5">
                                    Transfer to Bank 
                                </td>
                                <td class="p-l-10 p-b-5 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    <b>' . $row_bank_account['name'] . '</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 25px;">
                                    To account Number 
                                </td>
                                <td class="p-l-10 fs-14 color-black" style="padding-bottom: 25px;">
                                    <span class="pull-right">: </span>
                                    <b>' . $row_bank_account['account_number'] . '</b>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    From Bank 
                                </td>
                                <td class="p-l-10 fs-14 color-black">
                                    <span class="pull-right">: </span>
                                    <b>' . $row_bank_transfer['from_bank'] . '</b>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="padding-bottom: 25px;">
                                    From Acc Number 
                                </td>
                                <td class="p-l-10 fs-14 color-black" style="padding-bottom: 25px;">
                                    <span class="pull-right">: </span>
                                    <b>' . $row_bank_transfer['account_number'] . '</b>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="2">
                                    <img src="http://seagodswetsuit.com/new/web/images/evidenceTransfer/' . $row_bank_transfer['photo'] . '" height="auto" width="350px">
                                </td>
                            </tr>';

    }

    $template .= '
        
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        
        </body>
        </html>';
    return $template;
}