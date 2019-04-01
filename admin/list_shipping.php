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

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $perhalaman = 20;
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    $titlebar = "List Shipping Cost";
    $titlepage = "List Shipping Cost";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

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
                                <div class="card-title">List Shipping Cost</div>
                                <div class="pull-right">
                                    <div class="col-xs-12">
                                        <a href="form_shipping.php" class="btn btn-primary btn-cons" style="color:white"><i class="fa fa-plus"></i> Add Shipping Cost
                                        </a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th style="width:5%"></th>
                                            <th>Kota</th>
                                            <th style="width:15%">Provinsi</th>
                                            <th style="width:10%">Ongkos USD</th>
                                            <th style="width:10%">Ongkos IDR</th>
                                            <th style="width:5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    // Get city and province
    $query_kota = mysql_query("SELECT `kota`.*, `provinsi`.`namaProvinsi` FROM `kota`, `provinsi`
        WHERE `kota`.`idProvinsi` = `provinsi`.`idProvinsi` AND `kota`.`level` = '0' ORDER BY `idKota` DESC LIMIT $start,$perhalaman;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT `kota`.*, `provinsi`.`namaProvinsi` FROM `kota`, `provinsi`
        WHERE `kota`.`idProvinsi` = `provinsi`.`idProvinsi` AND `kota`.`level` = '0' ORDER BY `idKota` DESC;"));

    // Set nominal curs from USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    $no = 1;
    while ($row_kota = mysql_fetch_array($query_kota)) {

        $content .= '
                                        <form action="" method="post" name="list_subscriber">
                                            <tr>
                                                <td class="v-align-middle">' . $no . '</td>
                                                <td class="v-align-middle">
                                                   ' . $row_kota["namaKota"] . '
                                                </td>
                                                <td class="v-align-middle">
                                                   ' . $row_kota["namaProvinsi"] . '
                                                </td>
                                                <td class="v-align-middle">
                                                   $ ' . round(($row_kota["ongkos_kirim"] / $USDtoIDR), 2) . '
                                                </td>
                                                <td class="v-align-middle">
                                                   Rp ' . number_format($row_kota["ongkos_kirim"], 0, '.', ',') . '
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="form_shipping.php?id=' . $row_kota["idKota"] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-warning"><i class="fa fa-pencil"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </form>';
        $no++;
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
    header('Location: logout.php');
}

?>