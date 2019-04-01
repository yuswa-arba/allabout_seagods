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

    // Set nilai
    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    // Set pagination
    $perhalaman = 10;
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    // Set title
    $titlebar = "List Bank";
    $titlepage = "List Bank";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    // Actoin delete
    if (isset($_POST['delete'])) {

        // Set value bank ID
        $bank_id = isset($_POST['bank_id']) ? mysql_real_escape_string(trim($_POST['bank_id'])) : '';

        // Delete bank
        $query_delete_bank = "UPDATE `bank_account` SET `level` = '1' WHERE `id` = '$bank_id';";

        // If success
        if (mysql_query($query_delete_bank)) {
            echo "<script>
                alert('Bank account has been deleted successfully');
                window.location.href = 'list_bank_account.php';
            </script>";
        } else {
            echo "<script>
                alert('Unable to deleted bank account!!');
                window.history.back();
            </script>";
        }

    }

    $content = '
        <div class="page-container ">
        
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
            
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class=" container    container-fixed-lg">
                    
                        <!-- START card -->
                        <div class="card card-transparent">
                            <div class="card-header ">
                                <div class="card-title">List News</div>
                                <div class="pull-right">
                                    <div class="col-xs-12">
                                        <a href="form_bank_account.php" id="show-modal" class="btn btn-primary btn-cons" style="color:white"><i class="fa fa-plus"></i> Add Bank
                                        </a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th style="width:5%">#</th>
                                            <th >Name</th>
                                            <th style="width:30%">Account Number</th>
                                            <th style="width:10%">Code</th>
                                            <th style="width:10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    // Get bank account
    $query_bank_account = mysql_query("SELECT * FROM `bank_account` WHERE `level` = '0' ORDER BY `id` DESC LIMIT $start,$perhalaman;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `bank_account` WHERE `level` = '0' ORDER BY `id` DESC"));

    $no = 1;
    while ($row_bank_account = mysql_fetch_array($query_bank_account)) {

        $action_with_page = isset($_GET['page']) ? '&page=' . $_GET['page'] : '';

        $content .= '
                                        <form action="" method="post">
                                            <tr>
                                                <td class="v-align-middle">' . $no . '</td>
                                                <td class="v-align-middle">' . $row_bank_account["name"] . '</td>
                                                <td class="v-align-middle">' . $row_bank_account["account_number"] . '</td>
                                                <td class="v-align-middle">' . $row_bank_account["code"] . '</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <input type="hidden" name="bank_id" value="' . $row_bank_account["id"] . '">
                                                        <a href="form_bank_account.php?id=' . $row_bank_account['id'] . $action_with_page . '" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                                                        <button type="submit" class="btn btn-danger" name="delete" value="Delete"><i class="fa fa-trash-o"></i></button>
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
                            <!-- END card -->
                            </div>
                        </div>
                        
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
    <script src="assets/js/scripts.js" type="text/javascript"></script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>