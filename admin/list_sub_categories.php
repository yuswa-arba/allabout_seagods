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

    global $conn;

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $perhalaman = 10;

    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    $loggedin = logged_inadmin();
    $titlebar = "List Sub Categories";
    $titlepage = "List Sub Categories";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $simpan = isset($_POST["delete"]) ? $_POST["delete"] : '';

    if ($simpan == "Delete") {
        $id_category = isset($_POST['id_cate']) ? strip_tags(trim($_POST["id_cate"])) : "";

        $querycategory = "UPDATE `category` SET `level` = '1' WHERE `category`.`id_cat` = '$id_category';";

        //echo $querycategory ;
        mysql_query($querycategory) or die("<script language='JavaScript'>
            alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
            window.history.go(-1);
        </script>");

        echo "<script language='JavaScript'>
            alert('Data telah disimpan!');
            location.href = 'list_sub_categories.php';
        </script>";
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
                                <div class="card-title">List Sub Categories</div>
                                <div class="pull-right">
                                    <div class="col-xs-12">
                                        <a href="form_sub_category.php" id="show-modal" class="btn btn-primary btn-cons" style="color:white"><i class="fa fa-plus"></i> Add Sub Category</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                                
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th style="width:10%">No</th>
                                            <th style="width:30%">Sub Categories</th>
                                            <th style="width:30%">Category</th>
                                            <th style="width:10%">Total Item</th>
                                            <th style="width:20%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    $sql_sub_caregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` != '0' AND `level` = '0' ORDER BY `id_cat` DESC LIMIT $start,$perhalaman  ;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `category` WHERE `id_parent` != '0' AND `level` = '0' ORDER BY `id_cat`;"));

    $no = 1;
    while ($row_sub_category = mysql_fetch_array($sql_sub_caregory)) {

        $sql_caregory = mysql_query("SELECT * FROM `category` WHERE `id_cat`='$row_sub_category[id_parent]' ORDER BY `id_cat` DESC  ;");
        $row_category = mysql_fetch_array($sql_caregory);

        $query_item = mysql_query("SELECT count(`id_item`) AS total_item FROM `item` WHERE `id_cat` = '" . $row_sub_category["id_cat"] . "';", $conn);
        $row_item = mysql_fetch_array($query_item);

        $content .= '
                                        <form action="" method="post" name="list_category">
                                            <tr>
                                                <td class="v-align-middle">
                                                    ' . $no . '
                                                    <input type="hidden" name="id_cate" value="' . $row_sub_category["id_cat"] . '">
                                                </td>
                                                <td class="v-align-middle">
                                                    ' . $row_sub_category["category"] . '
                                                </td>
                                                <td class="v-align-middle">
                                                    ' . $row_category["category"] . '
                                                </td>
                                                <td class="v-align-middle">
                                                    ' . $row_item["total_item"] . '
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="form_sub_category.php?id=' . $row_sub_category["id_cat"] . '" type="button" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                                                        <button type="submit" class="btn btn-success" name="delete" value="Delete"><i class="fa fa-trash-o"></i></button>
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