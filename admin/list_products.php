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

    $perhalaman = 10;
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    $titlebar = "List Products";
    $titlepage = "List Products";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $simpan = isset($_POST["delete"]) ? $_POST["delete"] : '';

    if ($simpan == "Delete") {

        $id_item = isset($_POST['id_item']) ? strip_tags(trim($_POST["id_item"])) : "";

        $queryitem = "UPDATE `item` SET `level` = '1' WHERE `item`.`id_item` = '$id_item';";

        mysql_query($queryitem) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diproses ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			alert('Produk telah dihapus!');
			location.href = 'list_products.php';
            </script>";
    }

    if (isset($_GET['id'])) {

        $id_item = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";

        $query = "SELECT `item`.* FROM `item` WHERE `item`.`level` = '0' AND `item`.`id_item` = '$id_item'  ORDER BY `item`.`id_item` DESC ;";
        $id = mysql_query($query);
        $data_item = mysql_fetch_array($id);
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
                            <form action="" method="get">
                                <div class="card-header ">
                                    <div class="card-title">List Products</div>
                                    <div class="pull-right">
                                        <div class="col-xs-12">
                                            <a href="form_products.php" id="show-modal" class="btn btn-primary btn-cons" style="color:white"><i class="fa fa-plus"></i> Add Products</a>
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info">Search</button>&nbsp;
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control" name="v">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                            
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th style="width:5%"></th>
                                            <th style="width:20%">Product Name</th>
                                            <th style="width:13%">Categories</th>
                                            <th style="width:13%">Sub </th>
                                            <th style="width:10%">Price</th>
                                            <th style="width:5%">Stock</th>
                                            <th style="width:29%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    // Set default search query var
    $search_query = '';

    // Check search text
    if (isset($_GET['v'])) {

        // Set search value
        $text_search = isset($_GET['v']) ? mysql_real_escape_string(trim($_GET['v'])) : '';

        // Set query
        $search_query = "AND (`title` LIKE '%$text_search%' OR `detail` LIKE '%$text_search%')";
    }

    $sql_item = mysql_query("SELECT `item`.*, `category`.`category` FROM `item`,`category` WHERE `item`.`id_cat` = `category`.`id_cat` AND `item`.`level` = '0' $search_query ORDER BY `id_item` DESC LIMIT $start,$perhalaman  ;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT `item`.*, `category`.`category` FROM `item`,`category` WHERE `item`.`id_cat` = `category`.`id_cat` AND `item`.`level` = '0' $search_query ORDER BY `id_item` DESC;"));

    while ($row_item = mysql_fetch_array($sql_item)) {

        $row_category = mysql_fetch_array(mysql_query("SELECT * FROM `category` WHERE `id_cat` = '" . $row_item["id_category"] . "'"));
        $row_photo = mysql_fetch_array(mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $row_item["id_item"] . "' AND `level` = '0' ORDER BY `id_item` ASC LIMIT 0,1"));

        $content .= '
                                        <form action="" method="post" name="list_category">
                                            <tr>
                                                <td class="v-align-middle">
                                                    <img src="images/product/150/thumb_' . $row_photo['photo'] . '" width="30px">
                                                    <input type="hidden" name="id_item" value="' . $row_item["id_item"] . '">
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . $row_item["title"] . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . $row_category["category"] . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . $row_item["category"] . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>$ ' . $row_item["price"] . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . $row_item["stock"] . '</p>
                                                </td>
                                                <td>
                                                    <a href="detail_product.php?id=' . $row_item["id_item"] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-xs btn-info">
                                                        Preview
                                                    </a>
                                                    <a href="form_products.php?id=' . $row_item["id_item"] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-xs btn-warning" style="color: black;">
                                                        Edit Product
                                                    </a>
                                                    <a href="form_edit_size_product.php?id=' . $row_item["id_item"] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-xs btn-warning" style="color: black;">
                                                        Edit Size
                                                    </a>
                                                    <button type="submit" class="btn btn-xs btn-danger" name="delete" value="Delete">
                                                       Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        </form>';

    }

    // Set parameter pagination
    $param_paginate = isset($_GET['v']) ? ('?v=' . $_GET['v'] . '&') : '?';

    $content .= '        
                                    </tbody>
                                </table>
                                ' . (halaman($sql_total_data, $perhalaman, 1, $param_paginate)) . '
                                
                                <div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Detail Products</h4><br>
                                            </div>
                                            <div class="modal-body">
                                                <div id="dynamic-content">
                                                    <div class="form-group form-group-default  ">
                                                        <label>Product Name</label>
                                                        <span id="title"></span>
                                                    </div>
                                                    
                                                    <div class="form-group form-group-default  ">
                                                        <label>Category</label>
                                                    </div>
                                                    
                                                    <div class="form-group form-group-default  ">
                                                        <label>Price</label>
                                                        <span id="price"></span>
                                                    </div>
                                                    
                                                    <div class="form-group form-group-default  ">
                                                        <label>Detail</label>
                                                        <span id="detail"></span>
                                                    </div>
                                                    
                                                    <div class="form-group form-group-default  ">
                                                        <label>Description</label>
                                                        <span id="description"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <script src="assets/js/scripts.js" type="text/javascript"></script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>