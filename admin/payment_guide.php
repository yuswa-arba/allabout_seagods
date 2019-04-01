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

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    if (isset($_POST['submit'])) {

        // Set value
        $payment_guide = isset($_POST['payment_guide']) ? mysql_real_escape_string($_POST['payment_guide']) : '';

        if (!empty($payment_guide)) {

            // Payment guide query
            $payment_guide_query = "update pages set payment = '$payment_guide'";

            // If Error
            if (!mysql_query($payment_guide_query)) {
                echo "<script> 
                    alert('Failed updated');
                    window.history.back(-1);
                </script>";
                exit();
            }

            // Success
            echo "<script> 
                alert('successfully updated');
                window.location.href='payment_guide.php';
            </script>";
            exit();

        } else {
            echo "<script> 
                alert('Payment guide parameter required');
                window.history.back(-1);
            </script>";
            exit();
        }
    }

    $query = mysql_query("select * from pages");
    $data = mysql_fetch_assoc($query);
    $text = $data['payment'];

    $titlebar = "Payment Guide";
    $titlepage = "Payment Guide";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $content = '
        <div class="content ">
            <div class=" container    container-fixed-lg">
                <form method="post" action="payment_guide.php">
                
                    <!-- START card -->
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">Content Editor</div>
                            <div class="tools">
                                <a class="collapse" href="javascript:;"></a>
                                <a class="config" data-toggle="modal" href="#grid-config"></a>
                                <a class="reload" href="javascript:;"></a>
                                <a class="remove" href="javascript:;"></a>
                            </div>
                        </div>
                        <div class="card-block no-scroll card-toolbar">
                            <h5>Payment Guide Page</h5>
                            <div class="summernote-wrapper">
                                <textarea id="summernote" name="payment_guide">' . $text . '</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- END card -->
            
                    <button class="btn btn-primary" type="submit" name="submit">SAVE</button>
                </form>
            </div>
        </div>

		  
		  
		  
';

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