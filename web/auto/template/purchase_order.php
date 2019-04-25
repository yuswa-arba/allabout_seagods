<?php

function number_format_many($number, $decimals = 0)
{
    return number_format($number, $decimals, '.', ',');
}

function purchase_order_template($transaction, $carts, $shipping, $buyer, $province, $city, $currency_properties, $bank_transfer = null)
{
    // Set Currency
    $currency = $currency_properties['currency'];

    // Set USD to IDR
    $USDtoIDR = $currency_properties['USDtoIDR'];

    // Set weight round
    $weight_round = (($shipping['weight'] < 1) ? 1 : round($shipping['weight']));

    // Set subtotal if transaction with transfer bank
    if ($transaction['payment_method'] == 'Paypal') {

        // SEt subtotal
        $subtotal = array_sum(array_column(array_column($carts, 'cart'), 'amount'));

        // Set price
        $price_shipping = round(($shipping['price'] / $USDtoIDR), 2);

        // Set total shipping
        $total_shipping = ($price_shipping * $weight_round);

        // Set total transaction
        $total_transaction = ($subtotal + $total_shipping);

    } else {

        // SEt subtotal
        $subtotal = array_sum(array_column(array_column($carts, 'cart'), 'amount')) * $USDtoIDR;

        // Set price
        $price_shipping = $shipping['price'];

        // Set total shipping
        $total_shipping = ($price_shipping * $weight_round);

        // Set total transaction
        $total_transaction = ($subtotal + $total_shipping);

    }

    $template = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Purchase Order</title>
            <style>
                .btn {
                    display: inline-block;
                    font-weight: normal;
                    line-height: 1.25;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: middle;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                    border: 1px solid transparent;
                    padding: 6px 10px;
                    font-size: 1rem;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                    transition: all 0.2s ease-in-out;
                    text-decoration: none;
                }
        
                .btn-primary {
                    color: #fff;
                    background-color: #0275d8;
                    border-color: #0275d8;
                }
            </style>
        </head>
        <body style="font-family: Helvetica, Arial, sans;color: #494949;
            font-size: 12px;/*background-color: rgb(234,234,234);*/margin: 0px;height: 100%">
        
        <div style="width: 600px;height: 100%;margin: 0 auto;background-color: #fff;background-repeat: no-repeat;background-position: -280px -50px;">
            <div style="background-color: #242424;padding: 25px 0px; display: flow-root;clear: both;">
                <div style="float: left;width: 30%;padding-left:30px;">
                    <img src="http://seagodswetsuit.com/new/web/images/logo.png" alt="" height="auto" width="150px">
                </div>
                <div style="float: left;width: 60%;padding-right: 30px;">
                    <h5 style="text-align: right;margin: 0px;line-height: 25px;color: #d6d6d6;font-size: 19px;">PURCHASE ORDER</h5>
                </div>
            </div>
        
            <div style="padding: 30px 35px;border-left: 1px solid #f5f5f5;border-right: 1px solid #f5f5f5;">
                <div style="width: 100%;margin-bottom: 17px;">
                    <p style="margin: 0 0 2px;font-size: 17px;font-weight: 700;color:#000;">BUYER INFORMATION</p>
                    <p style="margin: 0px;font-size: 14px;">Thank you for your order at <a href="http://seagodswetsuit.com" style="color: #1187cc;font-weight: 600;" target="_blank">Sea Gods Wetsuit</a>, this is your order information</p>
                </div>
                <div style="width: 100%;display: flow-root;margin-bottom: 15px;">
                    <table style="border-collapse: collapse;width: 100%;font-size: 12px;">
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                Transaction Number <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . $transaction["kode_transaction"] . '</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 15px;">
                                Date <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 15px;font-size: 14px;color: #000;">
                                ' . get_date($transaction["date_add"])->format('d/m/Y l, H:i') . '
                            </td>
                        </tr>
        
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Name <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . ($transaction["is_guest"] ? ($buyer['first_name'] . ' ' . $buyer['last_name']) : ($buyer['firstname'] . ' ' . $buyer['lastname'])) . '
                            </td>
                        </tr>
        
                        <tr>
                            <td style="padding-bottom: 5px;">
                                No Telephone <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . ($transaction["is_guest"] ? $buyer['phone_no'] : $buyer['notelp']) . '
                            </td>
                        </tr>
        
                        <tr>
                            <td style="padding-bottom: 15px;vertical-align: top;">
                                Shipping Address <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 15px;font-size: 14px;vertical-align: top;color: #000;">
                                ' . ($transaction["is_guest"] ? $buyer['address'] : $buyer['alamat']) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                City <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . $city . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Province/State <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . $province . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Country <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . ($transaction["is_guest"] ? 'Indonesian' : 'Indonesian') . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Postal Code <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . ($transaction["is_guest"] ? $buyer['zip_code'] : $buyer['kode_pos']) . '
                            </td>
                        </tr>
        
        
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Total Item <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . array_sum(array_column(array_column($carts, 'cart'), 'qty')) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Weight <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . $shipping["weight"] . ' Kg
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Subtotal <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($subtotal, 2) : number_format_many($subtotal)) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Shipping<span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($total_shipping, 2) : number_format_many($total_shipping)) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Total<span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 16px;color: #000;">
                                <b>' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($total_transaction, 2) : number_format_many($total_transaction)) . '</b>
                            </td>
                        </tr>
        
                    </table>
                </div>
        
        
                <div style="width: 100%;margin-bottom: 5px;">
                    <p style="margin: 0 0 2px;font-size: 17px;font-weight: 700;color:#000;">INFORMATION TRANSACTION</p>
                </div>
                <div style="width: 96%;display: flow-root;margin-bottom: 25px;background-color: #f4f4f4;padding: 10px;border-left: 3px solid #1187cc;">
                    <table style="border-collapse: collapse;width: 100%;font-size: 12px;">
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                Payment Method <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . $transaction['payment_method'] . '</b>
                            </td>
                        </tr>';

    if ($transaction['payment_method'] == 'Bank Transfer') {

        // Set bank account
        $bank_account = $bank_transfer['bank'];

        $template .= '
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                Transfer to Bank <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . $bank_account['name'] . '</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                To Account Number <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . $bank_account['account_number'] . '</b>
                            </td>
                        </tr>
                        
                        <tr>
                            <td style="padding-bottom: 25px;vertical-align: top;">
                                Proof of Payment <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 25px;font-size: 14px;color: #000;">
                                <a href="http://seagodswetsuit.com/new/web/images/evidenceTransfer/' . $bank_transfer['photo'] . '" style="color: #069fe8;font-weight: 600;" target="_blank">URL Preview Image</a>
                            </td>
                        </tr>
                        
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                From Bank <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . $bank_transfer['from_bank'] . '</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 25px;vertical-align: top;">
                                From Acc Number <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 25px;font-size: 14px;color: #000;">
                                <b>' . $bank_transfer['account_number'] . '</b>
                            </td>
                        </tr>';

    } else {

        // Set paypal
        $paypal = $transaction['paypal'];

        $template .= '
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                PayPal ID <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . $paypal['paymentId'] . '</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%;padding-bottom: 25px;">
                                Date <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 25px;font-size: 14px;color: #000;">
                                ' . get_date($transaction["date_add"])->format('d/m/Y l, H:i') . '
                            </td>
                        </tr>';

    }

    $template .= '
                        <tr>
                            <td style="padding-bottom: 5px;font-size: 14px;font-weight: 600;">
                                Order Status <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;font-weight: 600;">
                                <span style="padding: 6px 10px; background: #000; color: #fff;">
                                    ' . (($transaction['konfirm'] == 'not confirmated') ? 'IN CONFIRMATION' : 'CONFIRMED') . '
                                </span>
                            </td>
                        </tr>
        
                    </table>
                </div>
        
        
        
                <div style="width: 100%;display: flow-root;margin-bottom: 18px;">
                    <table style="border-collapse: collapse;width: 100%;font-size: 12px;">
                        <tr>
                            <td style="padding: 10px;text-align: center;background: #000;color: #fff;font-weight: 600;">
                                Item
                            </td>
                            <td style="padding: 10px;text-align: left;background: #000;color: #fff;font-weight: 600;border-right: 1px solid #353535;border-left: 1px solid #353535;">
                                Detail
                            </td>
                            <td style="padding: 10px;text-align: left;background: #000;color: #fff;font-weight: 600;">
                                Amount
                            </td>
                        </tr>';

    foreach ($carts as $cart) {

        $template .= '
                        <tr>
                            <td style="width: 18%;border-bottom: 1px solid #000;padding: 10px;">';

        if ($cart['cart']['is_custom_cart']) {
            $template .= '<img src="http://seagodswetsuit.com/new/web/custom/public/images/custom_cart/' . $cart['item']['image'] . '" height="auto" width="95px">';
        } else {
            $template .= '<img src="http://seagodswetsuit.com/new/admin/images/product/150/thumb_' . $cart['photo']['photo'] . '" height="auto" width="95px">';
        }

        $template .= '
                            </td>
                            <td style="width: 45%;padding: 0 10px 5px;color: #000;vertical-align: top;border-bottom: 1px solid #000;border-left: 1px solid #000">
                                <div style="padding: 20px 0 0 0;">
                                    <p style="margin:10px 0;font-size: 14px;font-weight: 600;">' . ($cart['cart']['is_custom_cart'] ? 'Custom Wetsuit' : $cart['item']['title']) . '</p>
                                    <p style="margin:10px 0;font-size: 15px;font-weight: 600;color: #1187cc;">' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($cart['item']['price'], 2) : number_format_many($cart['item']['price'] * $USDtoIDR)) . '</p>
        
                                    <p style="margin:10px 0;font-size: 14px;font-weight: 500;">Quantity : <span style="font-weight: bold;font-size: 16px;">' . $cart['cart']['qty'] . '</span></p>
                                </div>
                            </td>
                            <td style="width: 37%;padding: 0 10px 5px;color: #000;vertical-align: top;border-bottom: 1px solid #000;border-left: 1px solid #000">
                                <div style="padding: 30px 0 0 0;">
                                    <p style="margin:10px 0;font-size: 15px;font-weight: 600;color: #1187cc;">' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($cart['cart']['amount'], 2) : number_format_many($cart['cart']['amount'] * $USDtoIDR)) . '</p>
                                </div>
                            </td>
                        </tr>';

    }

    $template .= '
                        <tr>
                            <td colspan="2" style="padding: 10px 0px;font-size: 16px;text-align: right;vertical-align: top;color: #000;">
                                Total <span style="margin-left: 5px;">:</span>
                            </td>
                            <td style="padding: 10px 10px 5px;font-size: 16px;font-weight: 600;color: #000;vertical-align: top;">
                                ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many(array_sum(array_column(array_column($carts, 'cart'), 'amount')), 2) : number_format_many(array_sum(array_column(array_column($carts, 'cart'), 'amount')) * $USDtoIDR)) . '
                            </td>
                        </tr>
        
                    </table>
                </div>
        
        
                <div style="width: 65%;display: flow-root;">
                    <p style="margin: 0 0 2px;font-size: 13px;font-weight: 600;color: #000;">
                        Office & Store
                    </p>
        
                    <p style="margin: 0 0 2px;font-size:13px;">By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia</p>
                    <p style="margin: 0 0 2px;font-size:13px;">Phone +62 361 27 11 99 </p>
                    <p style="margin: 0 0 2px;font-size:13px;">Whatsapp +62 8 11 534 9005</p>
                    <p style="margin: 0 0 2px;font-size:13px;">
                        Instagram <a href="https://www.instagram.com/seagodswetsuitofficial/" target="_blank" style="color: #494949;font-weight: 600;">@seagodswetsuitofficial</a>
                    </p>
                    <p style="margin: 0;font-size:14px;">Website <a href="http://seagodswetsuit.com" target="_blank" style="color: #494949;font-weight: 600;">@seagodswetsuit.com</a></p>
                </div>
            </div>
        
            <div style="background-color: #242424;padding: 15px 35px;">
                <div style="color:#adadad;font-size: 12px;">
                    <p style="margin: 0px;text-align: center;">
                        <a href="http://seagodswetsuit.com" target="_blank" style="color: #adadad;font-weight: 600;text-decoration: none;">© 2019. Sea Gods Wetsuit</a>
                    </p>
                </div>
            </div>
        
        </div>
        </body>
        </html>';
    return $template;
}