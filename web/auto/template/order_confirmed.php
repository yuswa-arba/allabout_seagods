<?php

function number_format_many($number, $decimals = 0)
{
    return number_format($number, $decimals, '.', ',');
}

function order_confirmed_template($transaction, $carts, $shipping, $buyer, $currency_properties, $bank_transfer = null)
{
    // Set Currency
    $currency = $currency_properties['currency'];

    // Set USD to IDR
    $USDtoIDR = $currency_properties['USDtoIDR'];

    $template = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Order Confirmed</title>
        </head>
        <body style="font-family: Helvetica, Arial, sans;color: #494949;
            font-size: 12px;margin: 0px;height: 100%">
        
        <div style="width: 600px;height: 100%;margin: 0 auto;background-color: #fff;background-repeat: no-repeat;background-position: -280px -50px;">
            <div style="background-color: #242424;padding: 25px 0px; display: flow-root;clear: both;">
                <div style="float: left;width: 30%;padding-left:30px;">
                    <img src="http://seagodswetsuit.com/new/web/images/logo.png" alt="" height="auto" width="150px">
                </div>
                <div style="float: left;width: 60%;padding-right: 30px;">
                    <h5 style="text-align: right;margin: 0px;line-height: 25px;color: #d6d6d6;font-size: 19px;">ORDER CONFIRMED</h5>
                </div>
            </div>
        
            <div style="padding: 30px 35px;border-left: 1px solid #f5f5f5;border-right: 1px solid #f5f5f5;">
                <div style="width: 100%;margin-bottom: 17px;">
                    <p style="margin: 0 0 10px;font-size: 18px;color:#000;">Hey ' . ($transaction["is_guest"] ? ($buyer['first_name'] . ' ' . $buyer['last_name']) : ($buyer['firstname'] . ' ' . $buyer['lastname'])) . '.</p>
                    <p style="margin: 0;font-size: 14px;">Thank you for your order at <a href="http://seagodswetsuit.com" style="color: #1187cc;font-weight: 600;" target="_blank">Sea Gods Wetsuit</a>, we have seen the payment transaction data, with this order status has been <b>confirmed</b> if the payment is in accordance with the price set, and this is the statement</p>
                </div>
        
                <div style="width: 100%;display: flow-root;margin-bottom: 15px;">
                    <table style="border-collapse: collapse;width: 100%;font-size: 12px;">
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                Transaction Number <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . $transaction['kode_transaction'] . '</b>
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
                                Order Amount <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . array_sum(array_column($carts, 'qty')) . '
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
                                ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many(array_sum(array_column($carts, 'amount')), 2) : number_format_many(array_sum(array_column($carts, 'amount')) * $USDtoIDR)) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Shipping<span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($shipping['amount'], 2) : number_format_many($shipping['amount'] * $USDtoIDR)) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                Total Payment<span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 16px;color: #000;">
                                <b>' . $currency . ' ' . (($transaction['payment_method'] == 'Paypal') ? number_format_many($transaction['total'], 2) : number_format_many($transaction['total'] * $USDtoIDR)) . '</b>
                            </td>
                        </tr>
        
                    </table>
                </div>
        
                <div style="width: 96%;display: flow-root;margin-bottom: 24px;background-color: #f4f4f4;padding: 10px;border-left: 3px solid #1187cc;">
                    <table style="border-collapse: collapse;width: 100%;font-size: 12px;">
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                Confirmed Date <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                <b>' . ($transaction["confirmed_at"] ? get_date($transaction["confirmed_at"])->format('d/m/Y l, H:i') : '-') . '</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%;padding-bottom: 5px;">
                                Confirmed By <span style="float: right;">:</span>
                            </td>
                            <td style="padding-left: 10px;padding-bottom: 5px;font-size: 14px;color: #000;">
                                ' . ($transaction["confirmed_by"] ? $transaction["confirmed_by"] : '-') . '
                            </td>
                        </tr>
                    </table>
                </div>
        
        
                <div style="width: 100%;margin-bottom: 5px;">
                    <p style="margin: 0 0 2px;font-size: 17px;font-weight: 700;color:#000;">INFORMATION TRANSACTION</p>
                </div>
                <div style="width: 96%;display: flow-root;margin-bottom: 24px;background-color: #f4f4f4;padding: 10px;border-left: 3px solid #1187cc;">
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
                                ' . get_date($paypal["date_add"])->format('d/m/Y l, H:i') . '
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
        
        
                <div style="width: 100%;margin-bottom: 5px;">
                    <p style="margin: 0 0 7px;font-size: 14px;">
                        In accordance with the data above, if the payment amount is correct, we will immediately proceed with the delivery process.
                    </p>
        
                    <p style="margin: 0 0 17px;font-size: 14px;">
                        Please wait for our receipt number...
                    </p>
        
                    <p style="margin: 0;font-size: 13px;">
                        Thank you for your business, <br>
                        Sea Gods Wetsuit
                    </p>
        
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