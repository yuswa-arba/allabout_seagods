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

    $perhalaman = 20;

    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    $loggedin = logged_inadmin();
    $titlebar = "List Subscriber";
    $titlepage = "List Subscriber";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    // Unsubscribe
    if (isset($_POST['unsubscribe'])) {

        // Set value request
        $id_member = isset($_POST['id_member']) ? mysql_real_escape_string(trim($_POST['id_member'])) : '';

        if (!empty($id_member)) {

            // Update subscribe
            $query_unsubscribe = "UPDATE `member` SET `subscribe` = '0' WHERE `id_member` = '$id_member';";

            // If error
            if (!mysql_query($query_unsubscribe)) {
                echo "<script>
                    alert('Unable to unsubscribe member!!');
                    window.history.back(-1);
                </script>";
                exit();
            }

            echo "<script>
                alert('Member has been unsubscribe successfully');
                window.location.href = 'list_subscriber.php';
            </script>";

        } else {
            echo "<script>
                alert('Member ID is empty!!');
                window.history.back(-1);
            </script>";
            exit();
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
                                <div class="card-title">List Subscriber</div>
                                <div class="pull-right">
                                    <div class="col-xs-12">
                                        <a href="form_subscriber.php" id="show-modal" class="btn btn-primary btn-cons" style="color:white"><i class="fa fa-plus"></i> Input Manual New Subsriber
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th style="width:5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    // Get member
    $query_member = mysql_query("SELECT `member`.*, `users`.`group` FROM `member`, `users` 
        WHERE `member`.`id_member` = `users`.`id_member` AND `member`.`subscribe` = '1' 
        AND `member`.`level` = '0' AND `users`.`group` != 'admin'
        ORDER BY `member`.`id_member` DESC LIMIT $start,$perhalaman;");

    // Set total member
    $sql_total_data = mysql_num_rows(mysql_query("SELECT `member`.*, `users`.`group` FROM `member`, `users` 
        WHERE `member`.`id_member` = `users`.`id_member` AND `member`.`subscribe` = '1' 
        AND `member`.`level` = '0' AND `users`.`group` != 'admin'
        ORDER BY `member`.`id_member` DESC"));

    $no = 1;
    while ($row_member = mysql_fetch_array($query_member)) {

        $content .= '
                                        <form action="" method="post" name="list_subscriber">
                                            <tr>
                                                <input type="hidden" name="id_member" value="' . $row_member['id_member'] . '">
                                                <td class="v-align-middle">' . $no . '</td>
                                                <td class="v-align-middle">' . $row_member['firstname'] . ' ' . $row_member['lastname'] . '</td>
                                                <td class="v-align-middle">' . $row_member['email'] . '</td>
                                                <td class="v-align-middle">' . $row_member['notelp'] . '</td>
                                                <td>
                                                    <button type="submit" class="btn btn-danger" name="unsubscribe">Unsubscribe</button>
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
                <!-- END PAGE CONTENT -->
                
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
            
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