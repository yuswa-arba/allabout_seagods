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

if ($loggedin = logged_inadmin()) { //  Check if they are logged in

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    // Set nominal curs from USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    $titlebar = (isset($_GET['id']) ? "Edit Shipping" : "Add Shipping");
    $titlepage = $titlebar;

    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET['id'])) {

        // If parameter id is empty
        if (empty($_GET['id'])) {
            echo "<script>
                alert('Please Select one of city!!');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Get city
        $query_city = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '" . $_GET["id"] . "' LIMIT 0,1;");
        if (mysql_num_rows($query_city) == 0) {
            echo "<script>
                alert('City not found!!');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_city = mysql_fetch_array($query_city);
    }


    // Save shipping cost in city
    if (isset($_POST['save'])) {

        // Set value request
        $city_name = isset($_POST['city_name']) ? mysql_real_escape_string(trim($_POST['city_name'])) : '';
        $province_id = isset($_POST['province_id']) ? mysql_real_escape_string(trim($_POST['province_id'])) : '';
        $shipping_cost = isset($_POST['shipping_cost_idr']) ? mysql_real_escape_string(trim($_POST['shipping_cost_idr'])) : '';

        if (!empty($city_name) && !empty($province_id) && !empty($shipping_cost)) {

            // Insert to kota
            $query_insert_kota = "INSERT INTO `kota` (`namaKota`, `idProvinsi`, `ongkos_kirim`, `date_add`, `date_upd`, `level`)
                VALUES('$city_name', '$province_id', '$shipping_cost', NOW(), NOW(), '0')";

            // If success
            if (mysql_query($query_insert_kota)) {
                echo "<script>
                    alert('Shipping has been created successfully.');
                    window.location.href = 'list_shipping.php';
                </script>";
                exit();
            } else {
                echo "<script>
                    alert('Unable to Create shipping in this city!!');
                    window.history.back(-1);
                </script>";
                exit();
            }

        } else {
            echo "<script>
                alert('All form can\'t empty!!');
                window.history.back(-1);
            </script>";
            exit();
        }

    }


    // Update shipping cost in city
    if (isset($_POST['update'])) {

        // Set value request
        $city_id = isset($_POST['city_id']) ? mysql_real_escape_string(trim($_POST['city_id'])) : '';
        $city_name = isset($_POST['city_name']) ? mysql_real_escape_string(trim($_POST['city_name'])) : '';
        $province_id = isset($_POST['province_id']) ? mysql_real_escape_string(trim($_POST['province_id'])) : '';
        $shipping_cost = isset($_POST['shipping_cost_idr']) ? mysql_real_escape_string(trim($_POST['shipping_cost_idr'])) : '';

        if (!empty($city_id) && !empty($city_name) && !empty($province_id) && !empty($shipping_cost)) {

            // Update to kota
            $query_update_kota = "UPDATE `kota` SET `namaKota` = '$city_name', `idProvinsi` = '$province_id', `ongkos_kirim` = '$shipping_cost',
                `date_upd` = NOW() WHERE `idKota` = '$city_id';";

            // If success
            if (mysql_query($query_update_kota)) {
                echo "<script>
                    alert('Shipping has been updated successfully.');
                    window.location.href = 'list_shipping.php" . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . "';
                </script>";
                exit();
            } else {
                echo "<script>
                    alert('Unable to update shipping in city!!');
                    window.history.back(-1);
                </script>";
                exit();
            }

        } else {
            echo "<script>
                alert('All form can\'t empty!!');
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
                            <input type="hidden" name="city_id" value="' . (isset($_GET['id']) ? $row_city['idKota'] : '') . '">
                            <input type="hidden" id="usd_to_idr" value="' . $USDtoIDR . '">
                            <div class="form-group form-group-default required">
                                <label class="">City Name</label>
                                <input class="form-control" name="city_name" placeholder="Ex: Denpasar" value="' . (isset($_GET['id']) ? $row_city['namaKota'] : '') . '">
                            </div>
                            <div class="form-group form-group-default required">
                                <label class="">Province</label>
                                <select class="form-control" data-placeholder="Select Province" name="province_id">
                                    <option value="" hidden>Select Province</option>';

    $query_province = mysql_query("SELECT * FROM `provinsi`");

    while ($row_province = mysql_fetch_array($query_province)) {
        $content .= '<option value="' . $row_province['idProvinsi'] . '" ' . (isset($_GET['id']) ? ($row_province["idProvinsi"] == $row_city["idProvinsi"] ? 'selected' : '') : '') . '>' . $row_province['namaProvinsi'] . '</option>';
    }

    $content .= '
                                </select>
                            </div>
                            <div class="form-group form-group-default required">
                                <label class="">Shipping Cost USD</label>
                                <input class="form-control" id="shipping_cost_usd" name="shipping_cost_usd" placeholder="Ex: 4.12" value="' . (isset($_GET['id']) ? $row_city['ongkos_kirim'] : '') . '" disabled>
                            </div>
                            <div class="form-group form-group-default required">
                                <label class="">Shiping Cost IDR</label>
                                <input class="form-control" id="shipping_cost_idr" name="shipping_cost_idr" placeholder="Ex: 50000" value="' . (isset($_GET['id']) ? ($row_city['ongkos_kirim'] * $USDtoIDR) : '') . '">
                            </div>
                            <div class="form-group">
                                <a class="btn btn-default" href="list_shipping.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '">Cancel</a>
                                <button class="btn btn-primary" type="submit" name="' . (isset($_GET['id']) ? 'update' : 'save') . '">' . (isset($_GET['id']) ? 'UPDATE' : 'SUBMIT') . '</button>
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
        $("#shipping_cost_idr").keyup(function() {
            
            var shipping_cost_usd = $("#shipping_cost_usd").val();
            var usd_to_idr = $("#usd_to_idr").val();
            
            var shipping_cost_idr = (shipping_cost_usd * usd_to_idr);
            
            $("#shipping_cost_idr").val(shipping_cost_idr.toFixed(0));
        });
    </script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>