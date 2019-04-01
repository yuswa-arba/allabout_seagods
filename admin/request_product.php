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


//$loggedin = logged_inadmin();
    $titlebar = "List Transaction";
    $titlepage = "List Transaction";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $query_request = mysql_query("SELECT * FROM `custom_request` WHERE `level` = '0' ORDER BY `id_custom_request` DESC LIMIT $start,$perhalaman;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `custom_request` WHERE `level` = '0' ORDER BY `id_custom_request` DESC;"));

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
                                            <th style="width:20%">IMAGE</th>
                                            <th style="width:15%">MEMBER NAME</th>
                                            <th style="width:10%">DATE</th>
                                            <th style="width:10%">PRICE</th>
                                            <th style="width:10%">STATUS</th>
                                            <th style="width:7%">DETAIL</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    while ($row_request = mysql_fetch_array($query_request)) {

        $query_collection = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '" . $row_request["id_custom_collection"] . "';");
        $row_collection = mysql_fetch_array($query_collection);

        $query_member = mysql_query("SELECT * FROM `member` WHERE `id_member` = '" . $row_collection["id_member"] . "';");
        $row_member = mysql_fetch_array($query_member);

        $content .= '
            <tr>
                <td class="v-align-middle">
                    <img style="width: 100%" src="../web/custom/public/images/custom_cart/' . $row_collection["image"] . '">
                </td>
                <td class="v-align-middle">
                    <p>' . $row_member["firstname"] . ' ' . $row_member["lastname"] . '</p>
                </td>
                <td class="v-align-middle">
                    <p>' . $row_request['date_add'] . '</p>
                </td>
                <td class="v-align-middle">
                    <p id="price_' . $row_request['id_custom_request'] . '">$ ' . ($row_request["price"] ?: 0) . '</p>
                </td>
                <td class="v-align-middle">
                    <p id="status_' . $row_request['id_custom_request'] . '">' . ucfirst($row_request["status"]) . '</p>
                </td>
                <td class="v-align-middle">
                    <a href="detail_request_product.php?id=' . $row_request['id_custom_request'] . '"><i class="fa fa-eye"></i> View</a>
                </td>
                <td class="v-align-middle">
                    <button type="button" onclick="confirm(' . $row_request["id_custom_request"] . ')" class="btn btn-primary">Confirm</button>
                </td>
            </tr>';
    }

    $content .= '
                                    </tbody>
                                </table>
                                ' . (halaman($sql_total_data, $perhalaman, 1, '?')) . '
                                
                                <div class="modal fade" id="confirm_modal">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h5>Price</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <div class="form-group required">
                                                            <input type="number" class="form-control" id="price" placeholder="Price Ex:10.99" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button type="button" class="btn btn-default" id="confirm">Process</button>
                                                    </div>
                                                </div>
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
	
    <script>
        function confirm(id_request) {
            $("#confirm_modal").modal("show");
            $("#confirm").val(id_request);
        }
        
        $("#confirm").on("click", function() {
            var id_request = $("#confirm").val();
            var price = $("#price").val();
            
            if (price != "" && id_request != "") {
                $.ajax({
                    type: "POST",
                    url: "request_product_process.php",
                    data: {action: "confirm", id_request: id_request, price: price},
                    dataType: "json",
                    success: function (data) {
                        $("#confirm_modal").modal("hide");
                        if (!data.failed) {
                            $("#price").val("");
                            $("#price_" + id_request).text("$ " + data.price);
                            $("#status_" + id_request).text(data.status);
                        }
                        window.alert(data.message);
                    }
                });
            } else {
                window.alert("Price data and ID cannot be empty");
            }
        });
    </script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('Location: logout.php');
}

?>