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

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    // Set nominal curs from USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    $titlebar = "Confirmation - No Invoice";
    $titlepage = "Confirmation - No Invoice";
    $menu = "";
    $user = '' . $loggedin['username'] . '';

    // Select member
    $member_query = mysql_query("SELECT * FROM `member` WHERE `id_member` = '" . $loggedin["id_member"] . "' LIMIT 0,1;");
    $row_member = mysql_fetch_array($member_query);

    if ($row_member['id_bank']) {
        // Select bank
        $bank_member_query = mysql_query("SELECT * FROM `bank_account` WHERE `id` = '" . $row_member["id_bank"] . "' LIMIT 0,1;");
        $row_bank_member = mysql_fetch_array($bank_member_query);
    }

    // Set shipping default
    $shipping = 0;

    // Set city
    if ($row_member['idkota']) {

        // Set city
        $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '" . $row_member["idkota"] . "' LIMIT 0,1;");
        $row_city = mysql_fetch_array($city_query);

        // Set shipping
        $shipping = $row_city['ongkos_kirim'];
    }

    // Set default value
    $amount_USD = 0;
    $weight = 0;

    // Get cart
    $cart_query = mysql_query("SELECT * FROM `cart`
        WHERE ISNULL(id_transaction) AND `id_member` = '" . $row_member["id_member"] . "' AND `level` = '0' ORDER BY `id_cart` DESC;");
    while ($row_cart = mysql_fetch_array($cart_query)) {

        // Set amount USD
        $amount_USD += $row_cart['amount'];

        // Action for weight
        if ($row_cart['is_custom_cart']) {

            // From custom collection
            $weight += get_price('default-weight-custom-item');

        } else {

            // From item
            $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $row_cart["id_item"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_array($item_query);

            $weight += $row_item['weight'];
        }

    }

    // Set amount USD
    $amount_IDR = ($shipping * round($weight)) + ($amount_USD * $USDtoIDR);
    $amount_USD = (round(($shipping / $USDtoIDR), 2) * round($weight)) + $amount_USD;

    // Transaction number
    $transaction_number = generate_transaction_number();

    // Action for confirmation
    if (isset($_POST['confirmation'])) {

        // Set value request
        $transaction_number = isset($_POST['transaction_number']) ? mysql_real_escape_string(trim($_POST['transaction_number'])) : '';
        $first_name = isset($_POST['first_name']) ? mysql_real_escape_string(trim($_POST['first_name'])) : '';
        $last_name = isset($_POST['last_name']) ? mysql_real_escape_string(trim($_POST['last_name'])) : '';
        $id_bank = isset($_POST['id_bank']) ? mysql_real_escape_string(trim($_POST['id_bank'])) : '';
        $from_bank = isset($_POST['from_bank']) ? mysql_real_escape_string(trim($_POST['from_bank'])) : '';
        $account_number = isset($_POST['account_number']) ? mysql_real_escape_string(trim($_POST['account_number'])) : '';
        $amount_USD = isset($_POST['amount_USD']) ? mysql_real_escape_string(trim($_POST['amount_USD'])) : '';
        $photoNameUpload = '';

        // if required
        if (!empty($transaction_number) && !empty($first_name) && !empty($last_name)
            && !empty($id_bank) && !empty($from_bank) && !empty($account_number) && !empty($amount_USD)
        ) {

            // Begin transaction
            begin_transaction();

            // Set photo
            if ($_FILES['photo']['tmp_name'] != "") {

                $target_path = "../images/evidenceTransfer/"; // Declaring Path for uploaded images.

                // Set memory limit in php.ini
                ini_set('memory_limit', '120M');

                // Loop to get individual element from the array
                $validextensions = array("jpeg", "jpg", "png", "PNG"); // Extensions which are allowed.
                $RandomNumber = date('ymdhis') . rand(0, 9999999999); // for create name file upload
                $imageName = $RandomNumber . "-" . (str_replace(' ', '_', $_FILES['photo']['name']));

                $ext = explode('.', $_FILES['photo']['name']); // Explode file name from dot(.)
                $file_extension = end($ext); // Store extensions in the variable.

                if (($_FILES["photo"]["size"] < 5000000) // Approx. 5Mb files can be uploaded.
                    && in_array($file_extension, $validextensions)
                ) {
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path . $imageName)) {
                        $photoNameUpload = $imageName;
                    } else {
                        echo "<script language='JavaScript'>
                            alert('File Was Not Moved.');
                            window.history.go(-1);
						</script>";
                        exit();
                    }
                } else {
                    echo "<script language='JavaScript'>
                        alert('Unable to set size extension.');
                        window.history.go(-1);
				    </script>";
                    exit();
                }

            } else {
                echo "<script language='JavaScript'>
				    alert('Your photo is empty!!');
				    window.history.go(-1);
				</script>";
                exit();
            }


            // Insert transaction
            $insert_transaction_query = "INSERT INTO `transaction` (`kode_transaction`, `id_member`, `status`, `konfirm`, `payment_method`, `total`, `date_add`, `date_upd`)
                VALUES('$transaction_number', '" . $row_member["id_member"] . "', 'process', 'not confirmated', 'Bank Transfer', '$amount_USD', NOW(), NOW());";

            // Error
            if (!mysql_query($insert_transaction_query)) {
                roll_back();
                echo "<script>
                    alert('Unable to save transaction');
                    window.history.back(-1);
                </script>";
                exit();
            }

            // Select transaction
            $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `kode_transaction` = '$transaction_number' AND `id_member` = '" . $loggedin["id_member"] . "'
                AND `status` = 'process' AND `konfirm` = 'not confirmated' AND `payment_method` = 'Bank Transfer' ORDER BY `id_transaction` DESC LIMIT 0,1;");
            $row_transaction = mysql_fetch_array($transaction_query);

            // Insert bank transfer
            $insert_bank_transfer_query = "INSERT INTO `bank_transfer` (`id_transaction`, `id_bank`, `id_member`, `from_bank`, `account_number`, `amount`, `photo`, `date_add`, `date_upd`)
                VALUES('" . $row_transaction["id_transaction"] . "', '$id_bank', '" . $loggedin["id_member"] . "', '$from_bank', '$account_number', '$amount_USD', '$photoNameUpload', NOW(), NOW());";

            // Error
            if (!mysql_query($insert_bank_transfer_query)) {
                roll_back();
                echo "<script>
                    alert('Unable to save bank transfer');
                    window.history.back(-1);
                </script>";
                exit();
            }

            // Assigned transaction to cart
            $assigned_transaction_cart_query = "UPDATE `cart` SET `id_transaction` = '" . $row_transaction["id_transaction"] . "'
                WHERE ISNULL(id_transaction) AND `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0';";
            if (!mysql_query($assigned_transaction_cart_query)) {
                roll_back();
                echo "<script>
                    alert('Unable to assigned transaction in cart');
                    window.history.back(-1);
                </script>";
                exit();
            }

            // Set total shipping
            $total_shipping = (round(($shipping / $USDtoIDR), 2) * round($weight));

            // Insert shipping
            $insert_shipping_query = "INSERT INTO `transaction_shipping` (`id_transaction`, `weight`, `price`, `amount`, `date_add`, `date_upd`)
                VALUES('" . $row_transaction["id_transaction"] . "', '$weight', '$shipping', '$total_shipping', NOW(), NOW());";
            if (!mysql_query($insert_shipping_query)) {
                roll_back();
                echo "<script>
                alert('Unable to save shipping');
                window.history.back(-1);
            </script>";
                exit();
            }

            // Commit
            commit();

            // Success
            echo "<script>
                alert('Payment process successfully.');
                window.location.href = 'list-transaction.php';
            </script>";
            exit();

        } else {
            echo "<script>
                alert('All Request parameter required');
                window.history.back(-1);
            </script>";
            exit();
        }

    }

    $content = '
        <div class="content ">
            <div class=" container container-fixed-lg">
                
                <!-- START card -->
                <div class="card card-default">
                    <div class="card-block no-scroll card-toolbar">
                        <form method="post" action="" enctype="multipart/form-data" role="form">
                            <div class="form-group form-group-default ">
                                <label>Transaction number</label>
                                <input class="form-control" name="transaction_number" value="' . $transaction_number . '" readonly="readonly">
                            </div>
                            <div class="form-group form-group-default required ">
                                <label>First Name</label>
                                <input class="form-control" name="first_name" value="' . $row_member["firstname"] . '">
                            </div>
                            <div class="form-group form-group-default required">
                                <label>Last Name</label>
                                <input class="form-control" name="last_name" value="' . $row_member["lastname"] . '">
                            </div>
                            <div class="form-group form-group-default form-group-default-select2 required">
                                <label>Bank Account</label>
                                <select class="full-width" data-placeholder="Select Bank" name="id_bank" data-init-plugin="select2">';

    // Get all bank account
    $bank_query = mysql_query("SELECT * FROM `bank_account` WHERE `level` = '0';");
    while ($row_bank = mysql_fetch_array($bank_query)) {
        $content .= '<option value="' . $row_bank['id'] . '">' . $row_bank['name'] . '</option>';
    }

    $content .= '
                                </select>
                            </div>
                            <div class="form-group form-group-default required">
                                <label>From Bank (Member)</label>
                                <input class="form-control" name="from_bank" value="' . ($row_member['id_bank'] ? $row_bank_member['name'] : '') . '">
                            </div>
                            <div class="form-group form-group-default required">
                                <label>Member Account Number</label>
                                <input class="form-control" name="account_number" value="' . $row_member['account_number'] . '">
                            </div>
                            <div class="form-group form-group-default required">
                                <label>Nominal</label>
                                <input class="form-control" name="amount" id="amount" onkeyup="changeAmount();" value="' . $amount_IDR . '" disabled>
                                <input type="hidden" name="amount_USD" value="' . $amount_USD . '">
                            </div>
                            <div class="form-group form-group-default required">
                                <label>Upload Photo Evidence of Transfer</label><br/>
                                <input type="file" value="" name="photo" style="height: 31px;" /> <br/>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="confirmation">Confirmation</button>
                            </div>
                        </form>
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