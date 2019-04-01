<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
session_start();
ob_start();

if ($loggedin = logged_inadmin()) { //  Check if they are logged in

    $titlebar = "Bank Account";
    $titlepage = "Bank Account";

    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET['id'])) {

        $bank_id = isset($_GET['id']) ? mysql_real_escape_string(trim($_GET['id'])) : '';

        // Get bank account
        $query_bank = mysql_query("SELECT * FROM `bank_account` WHERE `id` = '$bank_id' LIMIT 0,1;");
        if (mysql_num_rows($query_bank) == 0) {
            echo "<script>
                alert('Bank account not found!!');
                window.history.back(-1);
            </script>";
        }

        // Set row query bank
        $row_bank = mysql_fetch_array($query_bank);

    }


    // Actoun for save
    if (isset($_POST['save'])) {

        // Set value
        $name = isset($_POST['name']) ? mysql_real_escape_string(trim($_POST['name'])) : '';
        $code = isset($_POST['code']) ? mysql_real_escape_string(trim($_POST['code'])) : '';
        $account_number = isset($_POST['account_number']) ? mysql_real_escape_string(trim($_POST['account_number'])) : '';

        // Check is null or not
        if (!empty($name) && !empty($code) && !empty($account_number)) {

            // Insert data to bank_account
            $query_insert_bank = "INSERT INTO `bank_account` (`name`, `code`, `account_number`, `date_add`, `date_upd`)
                VALUES('$name', '$code', '$account_number', NOW(), NOW())";

            // If success
            if (mysql_query($query_insert_bank)) {
                echo "<script>
                    alert('Bank account has been created successfully.');
                    window.location.href = 'list_bank_account.php';
                </script>";
            } else {
                echo "<script>
                    alert('Unable to create bank account!!');
                    window.history.back(-1);
                </script>";
            }

        } else {
            echo "<script>
                alert('All Form bank can\'t empty!!');
                window.history.back(-1);
            </script>";
        }

    }


    // Actoun for update
    if (isset($_POST['update'])) {

        // Set value
        $bank_id = isset($_POST['bank_id']) ? mysql_real_escape_string(trim($_POST['bank_id'])) : '';
        $name = isset($_POST['name']) ? mysql_real_escape_string(trim($_POST['name'])) : '';
        $code = isset($_POST['code']) ? mysql_real_escape_string(trim($_POST['code'])) : '';
        $account_number = isset($_POST['account_number']) ? mysql_real_escape_string(trim($_POST['account_number'])) : '';

        // Check is null or not
        if (!empty($name) && !empty($code) && !empty($account_number)) {

            // Insert data to bank_account
            $query_update_bank = "UPDATE `bank_account` SET `name` = '$name', `code` = '$code',
                `account_number` = '$account_number', `date_upd` = NOW() WHERE `id` = '$bank_id';";

            // If success
            if (mysql_query($query_update_bank)) {
                echo "<script>
                    alert('Bank account has been updated successfully.');
                    window.location.href = 'list_bank_account.php" . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . "';
                </script>";
            } else {
                echo "<script>
                    alert('Unable to update bank account!!');
                    window.history.back(-1);
                </script>";
            }

        } else {
            echo "<script>
                alert('All Form bank can\'t empty!!');
                window.history.back(-1);
            </script>";
        }

    }

    $content = '
        <div class="content ">
            <div class=" container container-fixed-lg">
                
                <!-- START card -->
                <div class="card card-default">
                    <div class="card-block no-scroll card-toolbar">
                        <form method="post" action="" enctype="multipart/form-data" role="form">
                            <input type="hidden" name="bank_id" value="' . (isset($_GET['id']) ? $row_bank['id'] : '') . '">
                            <div class="form-group form-group-default required">
                                <label>Bank Name</label>
                                <input class="form-control" name="name" placeholder="BCA" value="' . (isset($_GET['id']) ? $row_bank['name'] : '') . '">
                            </div>
                            <div class="form-group form-group-default required">
                                <label>Bank Code</label>
                                <input class="form-control" name="code" placeholder="014" value="' . (isset($_GET['id']) ? $row_bank['code'] : '') . '">
                            </div>
                            <div class="form-group form-group-default required">
                                <label>Bank Account Number</label>
                                <input class="form-control" name="account_number" placeholder="6860 1487 55" value="' . (isset($_GET['id']) ? $row_bank['account_number'] : '') . '">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="' . (isset($_GET['id']) ? 'update' : 'save') . '">' . (isset($_GET['id']) ? 'UPDATE' : 'SAVE') . '</button>
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
    <script src="assets/js/scripts.js" type="text/javascript"></script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>