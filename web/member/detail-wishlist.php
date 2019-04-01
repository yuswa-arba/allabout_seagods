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

if ($loggedin = logged_in()) {
    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = "Detail Wishlist";
    $titlepage = "Detail Wishlist";

    $menu = "";
    $user = '' . $_SESSION['user'] . '';

    if (isset($_GET["id"])) {

        $id_wishlist = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";

        $sql_wishlist = mysql_query("SELECT * FROM `wishlist` WHERE `id_member`='" . $loggedin["id_member"] . "' AND `id_wishlist` = '$id_wishlist'");
        $row_wishlist = mysql_fetch_array($sql_wishlist);

        $sql_member = mysql_query("SELECT * FROM `member` where `id_member` ='" . $loggedin["id_member"] . "' ");
        $row_member = mysql_fetch_array($sql_member);

    }

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
                                <div class="card-title">Detail Transaction
                                    <br><br>Buyer  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>' . $row_member['firstname'] . ' ' . $row_member['lastname'] . ' </strong>
                                </div>
                                <div class="pull-right"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th >Product Name</th>
                                            <th style="width:15%">Qty</th>
                                            <th style="width:25%">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    if (isset($_GET["id"])) {

        $query_item = mysql_query("SELECT * FROM `item` WHERE `code` = '" . $row_wishlist["code"] . "' LIMIT 0,1;");
        $row_item = mysql_fetch_array($query_item);

        $content .= '
            <tr>
                <td class="v-align-middle">
                    <p>' . $row_item['title'] . '</p>
                </td>
                <td class="v-align-middle">
                    <p>' . $row_wishlist['qty'] . '</p>
                </td>
                <td class="v-align-middle">
                    <p>$ ' . $row_item['price'] . '</p>
                </td>
            </tr>';

    }

    $content .= '
                                        <tr> 
                                            <td colspan="3" >Total : $ ' . $row_wishlist['amount'] . '</td> 
                                        </tr>
                                    </tbody>
                                </table>
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