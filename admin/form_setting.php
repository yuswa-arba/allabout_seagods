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

    $titlebar = "Form Setting";
    $titlepage = "Form Setting";

    $menu = "";
    $user = '' . $loggedin['username'];

    if (isset($_GET['id'])) {

        // Set Setting ID
        $id_setting = isset($_GET['id']) ? mysql_real_escape_string(trim($_GET['id'])) : '';

        if (!empty($id_setting)) {

            // Setting query
            $setting_query = mysql_query("SELECT * FROM `setting_seagods` WHERE `id` = '$id_setting' LIMIT 0,1;");

            // Error
            if (mysql_num_rows($setting_query) == 0) {
                echo "<script>
                    alert('Setting not found');
                    window.history.back(-1);
                </script>";
                exit();
            }

            // Exists
            $row_setting = mysql_fetch_array($setting_query);

        } else {

            // error
            echo "<script>
                alert('Setting ID parameter required');
                window.history.back(-1);
            </script>";
            exit();

        }
    }

    // Action update
    if (isset($_POST['update'])) {

        // Set value
        $id_setting = isset($_POST['id_setting']) ? mysql_real_escape_string(trim($_POST['id_setting'])) : '';
        $value = isset($_POST['value']) ? mysql_real_escape_string(trim($_POST['value'])) : '';
        $description = isset($_POST['description']) ? mysql_real_escape_string(trim($_POST['description'])) : '';

        // Check is empty or no
        if (!empty($id_setting) && !empty($value) && !empty($description)) {

            // Setting update query
            $update_setting_query = "UPDATE `setting_seagods` SET `value` = '$value', `description` = '$description' WHERE `id` = '$id_setting';";

            // Error
            if (!mysql_query($update_setting_query)) {
                echo "<script>
                    alert('Unable to update setting');
                    window.history.back(-1);
                </script>";
                exit();
            }

            // Success
            echo "<script>
                    alert('Setting has been updated successfully');
                    window.location.href = 'settings.php';
                </script>";
            exit();

        } else {
            echo "<script>
                alert('Error: Parameter required');
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
                            <input type="hidden" name="id_setting" value="' . (isset($_GET['id']) ? $row_setting['id'] : '') . '">
                            <div class="form-group form-group-default ">
                                <label>Value</label>
                                <input class="form-control" name="value" value="' . (isset($_GET['id']) ? $row_setting['value'] : '') . '">
                            </div>
                            <div class="form-group form-group-default required ">
                                <label>Description</label>
                                <textarea class="form-control" name="description" style="height:200px">' . (isset($_GET['id']) ? $row_setting['description'] : '') . '</textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="update">Update</button>
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