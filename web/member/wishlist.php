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

if ($loggedin = logged_in()) {

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    // Default currency
    $currency_code = CURRENCY_USD_CODE;

    // Set currency from session
    if (isset($_SESSION['currency_code'])) {
        $currency_code = $_SESSION['currency_code'];
    }

    // Set currency from database
    if ($loggedin) {
        $currency_code = $loggedin['currency_code'];
    }

    // Set currency
    $currency = get_currency($currency_code);

    // Set nominal curs from USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = "Wishlist";
    $titlepage = "Wishlist";
    $menu = "";
    $user = '' . $loggedin['username'] . '';

    $perhalaman = 10;
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    if (isset($_POST['delete'])) {
        $id_wishlist = isset($_POST['id_wishlist']) ? strip_tags(trim($_POST['id_wishlist'])) : '';

        $query_wishlist_update = mysql_query("UPDATE `wishlist` SET `level` = '1', `date_upd` = NOW() WHERE `id_wishlist` = '$id_wishlist';");
        if (!$query_wishlist_update) {
            echo "<script language='JavaScript'>
                alert('Unable to delete wishlist.!');
                window.history.back(-1);
            </script>";
        }

        echo "<script language='JavaScript'>
                window.history.back(-1);
            </script>";
    }

    $sql_wishlist = mysql_query("SELECT `wishlist`.*, `item`.`title` FROM `wishlist`, `item` 
        where `wishlist`.`code` = `item`.`code` 
        AND `wishlist`.`id_member` ='" . $loggedin["id_member"] . "'
        AND `wishlist`.`level` = '0'
        ORDER BY `wishlist`.`id_wishlist` DESC LIMIT $start,$perhalaman;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `wishlist`, `item` 
        where `wishlist`.`code` = `item`.`code` 
        AND `wishlist`.`id_member` ='" . $loggedin["id_member"] . "'
        AND `wishlist`.`level` = '0';"));

    $content = '
        <div class="page-container ">
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class="container container-fixed-lg">
                        <!-- START card -->
                        <div class="card card-transparent">
                            <div class="card-header ">
                                <div class="card-title">Wishlist</div>
                                <div class="pull-right"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th >Product Name</th>
                                            <th style="width:15%">Price</th>
                                            <th style="width:5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    while ($row_wishlist = mysql_fetch_array($sql_wishlist)) {

        $content .= '
            <tr>
                <form method="post" action="">
                    <input type="hidden" name="id_wishlist" value="' . $row_wishlist["id_wishlist"] . '">
                    <td class="v-align-middle">
                        <p>' . $row_wishlist['title'] . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? $row_wishlist['amount'] : number_format(($row_wishlist['amount'] * $USDtoIDR), 0, '.', ',')) . '</p>
                    </td>
                    <td class="v-align-middle">
                        <div class="btn-group">
                            <a href="detail-wishlist.php?id_wishlist=' . $row_wishlist["id_wishlist"] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-success"><i class="fa fa-eye"></i></a>
                            <button type="submit" class="btn btn-danger" name="delete" value="Delete"><i class="fa fa-trash-o"></i></button>
                        </div>
                    </td>
                </form>
            </tr>';

    }

    $content .= '   
                                    </tbody>
                                </table>
                                ' . (halaman($sql_total_data, $perhalaman, 1, '?')) . '
                            </div>
                        </div>
                        <!-- END card -->
                    </div>
                </div>
            </div>
        </div>';

    $plugin = '     
    <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
	<!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/datatables.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;

} else {
    header('location:../login.php');
}

?>