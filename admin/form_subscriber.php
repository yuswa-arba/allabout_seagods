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

    $titlebar = "Add Manual Subscriber";
    $titlepage = "Add Manual Subscriber";

    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    // Action save
    if (isset($_POST['save'])) {

        // Begin transaction
        begin_transaction();

        // Set value request
        $total_member = isset($_POST['total_member']) ? mysql_real_escape_string(trim($_POST['total_member'])) : 0;
        $member = isset($_POST['member']) ? $_POST['member'] : [];

        // If total member is 0
        if ($total_member == 0) {
            echo "<script>
                alert('Nothing member available for made subscriber!!');
                window.history.back(-1);
            </script>";
            exit();
        }

        $total_selected = count($member);
        if ($total_selected == 0) {
            echo "<script>
                alert('Nothing members selected!!');
                window.history.back(-1);
            </script>";
            exit();
        }

        for ($a = 0; $a < $total_selected; $a++) {

            // Update subscribe
            $query_member_update = "UPDATE `member` SET `subscribe` = '1' WHERE `id_member` = '$member[$a]';";

            // If error
            if (!mysql_query($query_member_update)) {
                roll_back();
                echo "<script>
                    alert('Unable to add subscriber!!');
                    window.history.back(-1);
                </script>";
                exit();
            }

        }

        // commit
        commit();

        // Success
        echo "<script>
            alert('Subscriber has been added successfully');
            window.location.href = 'list_subscriber.php';
        </script>";

    }

    $content = '
        <div class="content ">
            <div class=" container container-fixed-lg">
                
                <!-- START card -->
                <div class="card card-default">
                
                    <div class="card-header">
                        <div class="card-title">New Subscriber</div>
                    </div><br>
                    
                    <div class="card-block no-scroll card-toolbar">
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group form-group-default">
                                        <input class="form-control" name="key" placeholder="Search with email or full name" value="">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-success" name="search">Search</button>
                                </div>
                                <div class="col-2">
                                    <a href="list_subscriber.php" class="btn btn-default" type="submit" name="save">Cancel</a>
                                    <button class="btn btn-primary" type="submit" name="save">SAVE</button>
                                </div>
                            </div><hr>';

    if (isset($_POST['search'])) {

        // Set query search
        $search_email = "`email` LIKE '%" . $_POST["key"] . "%' ";
        $search_full_name = "OR `firstname` LIKE '%" . $_POST["key"] . "%' OR  `lastname` LIKE '%" . $_POST["key"] . "%'";

        $query_member = mysql_query("SELECT * FROM `member` WHERE `level` = 0 AND `subscribe` = '0' AND `level` = '0'
            AND ($search_email $search_full_name) LIMIT 0,20;");
        $total_member = mysql_num_rows($query_member);

    } else {

        $query_member = mysql_query("SELECT * FROM `member` WHERE `level` = 0 AND `subscribe` = '0' AND `level` = '0' LIMIT 0,20;");
        $total_member = mysql_num_rows($query_member);

    }

    $no = 1;
    while ($row_member = mysql_fetch_array($query_member)) {

        // Check province
        $total_province = 0;
        $row_province = [];
        if ($row_member['idpropinsi'] != '' || $row_member['idpropinsi'] != null) {

            // Set province
            $query_province = mysql_query("SELECT `namaProvinsi` FROM `provinsi` WHERE `idProvinsi` = '" . $row_member["idpropinsi"] . "' LIMIT 0,1;");
            $total_province = mysql_num_rows($query_province);

            // Set variable province
            if ($total_province > 0) {
                $row_province = mysql_fetch_array($query_province);
            }

        }

        // Check City
        $total_city = 0;
        $row_city = [];
        if ($row_member['idkota'] != '' || $row_member['idkota'] != null) {

            // Set city
            $query_city = mysql_query("SELECT `namaKota` FROM `kota` WHERE `idKota` = '" . $row_member["idkota"] . "' AND `level` = '0' LIMIT 0,1;");
            $total_city = mysql_num_rows($query_city);

            // Set variable province
            if ($total_city > 0) {
                $row_city = mysql_fetch_array($query_city);
            }

        }

        $content .= '
                            <div class="row">
                                <div class="col-3">
                                    <div class="row">
                                         <div class="checkbox check-success border-bottom-grey m-t-0 m-l-10">
                                             <input type="checkbox" id="member_' . $no . '" name="member[]" value="' . $row_member['id_member'] . '">
                                             <label for="member_' . $no . '" class="bold fs-16 text-black"></label>
                                         </div>
                                         <label class="bold fs-16 text-black">' . $row_member["firstname"] . ' ' . $row_member["lastname"] . '</label>
                                     </div>
                                </div>
                                <div class="col-3">
                                     <label class="bold fs-16 text-black" ' . (($total_city == 0) ? 'style="color: #9fb0b6 !important; font-style: italic;"' : '') . '>' . (($total_city > 0) ? $row_city['namaKota'] : "Tidak ada Kota") . '</label>
                                </div>
                                <div class="col-3">
                                     <label class="bold fs-16 text-black" ' . (($total_province == 0) ? 'style="color: #9fb0b6 !important; font-style: italic;"' : '') . '>' . (($total_province > 0) ? $row_province['namaProvinsi'] : "Tidak ada Provinsi") . '</label>
                                </div>
                                <div class="col-3">
                                     <label class="bold fs-16 text-black" ' . (($row_member["email"] == '' || $row_member["email"] == null) ? 'style="color: #9fb0b6 !important; font-style: italic;"' : '') . '>' . (($row_member["email"] != '' || $row_member["email"] != null) ? $row_member["email"] : "Member ini tidak memiliki email") . '</label>
                                </div>
                            </div><hr>';
        $no++;

    }

    if ($total_member == 0) {
        $content .= '
                            <div class="row">
                                <div class="col-12 text-center">
                                    <label class="bold fs-16 text-black">'.(isset($_POST["search"]) ? "Member in search for subscribe not found" : "Member for subscribe does not available").'</label>
                                </div>
                            </div>
                            <hr>';
    }

    $content .= '
                            <br>
                            <div class="form-group">
                                <input type="hidden" name="total_member" value="' . $total_member . '">
                                <button class="btn btn-primary" type="submit" name="save">SAVE</button>
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