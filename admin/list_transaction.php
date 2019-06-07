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

if (isset($_POST['nilai'])) {
    $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
} else {
    $_SESSION['nilai_login'] = 0;
}

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    $perhalaman = 10;
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    $titlebar = "List Transaction";
    $titlepage = "List Transaction";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $sql_transaction = mysql_query("SELECT * FROM `transaction`  ORDER BY `date_add` DESC LIMIT $start,$perhalaman");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `transaction`  ORDER BY `date_add`"));


    // Action confirm transaction
    if (isset($_POST['confirm'])) {

        // Set value request
        $id_transaction = isset($_POST['id_transaction']) ? mysql_real_escape_string(trim($_POST['id_transaction'])) : '';

        // Update confirmation status
        $update_transaction_query = "UPDATE `transaction` SET `status` = 'completed', `konfirm` = 'Confirmated', `confirmed_at` = NOW(),
            `confirmed_by` = '" . $user . "' WHERE `id_transaction` = '$id_transaction';";

        // Error
        if (!mysql_query($update_transaction_query)) {
            echo "<script>
                alert('Unable to confirm');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Success
        echo "<script>
            alert('Confirm transaction success');
            window.location.href = 'list_transaction.php" . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . "'
        </script>";

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
                                <div class="card-title">List Transaction</div>
                                <div class="pull-right"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th style="width:20%">INVOICE#</th>
                                            <th style="width:15%">CUSTOMER</th>
                                            <th style="width:15%">ORDER DATE</th>
                                            <th style="width:15%">TOTAL</th>
                                            <th style="width:10%">PAYMENT STATUS</th>
                                            <th style="width:5%">CONFIRMATION</th>
                                            <th style="width:5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    while ($row_transaction = mysql_fetch_array($sql_transaction)) {

        if ($row_transaction['is_guest']) {

            // Set guest
            $guest_query = mysql_query("SELECT * FROM `guest` WHERE `id` = '" . $row_transaction["id_guest"] . "' LIMIT 0,1;");
            $row_guest = mysql_fetch_array($guest_query);

            // Set holder name
            $holder_name = $row_guest['first_name'] . ' ' . $row_guest['last_name'];

        } else {

            // Set member
            $sql_member = mysql_query("SELECT * FROM `member` where `id_member` ='" . $row_transaction["id_member"] . "' ");
            $row_member = mysql_fetch_array($sql_member);

            // Set holder name
            $holder_name = $row_member['firstname'] . ' ' . $row_member['lastname'];

        }

        $content .= '
                                        <form method="post" action="">
                                            <tr>
                                                <input type="hidden" name="id_transaction" value="' . $row_transaction["id_transaction"] . '">
                                                <td class="v-align-middle">
                                                    ' . $row_transaction['kode_transaction'] . '
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . $holder_name . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . $row_transaction['date_add'] . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>$ ' . number_format($row_transaction['total'], 2, '.', ',') . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . $row_transaction['status'] . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    ' . (($row_transaction['konfirm'] == 'not confirmated') ?
                '<button type="submit" name="confirm" class="btn btn-danger">Unconfirmed</button>' :
                '<a class="label" style="background-color: #69b6f3; color: #ffffff">Confirmed</a>') . '
                                                </td>
                                                <td class="v-align-middle">
                                                    <div class="btn-group">
                                                        <a class="btn btn-success" href="detail_transaction.php?id_transaction=' . $row_transaction['id_transaction'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '">view</a>
                                                    <a class="btn btn-success" target="_blank" href="../web/order_pdf.php?id_transaction=' . $row_transaction['id_transaction'] . '"><i class="fa fa-file-pdf-o"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </form>';
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