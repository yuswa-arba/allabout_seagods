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

    $titlebar = "Custom Made ";
    $titlepage = "Custom Made ";
    $menu = "";
    $user = '' . $loggedin['username'];

    if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'deleted') {
        $id_collection = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : '';

        $query_collection_delete = mysql_query("UPDATE `custom_collection` SET `level` = '1', `date_upd` = NOW()
            WHERE `id_custom_collection` = '$id_collection';");
        if (!$query_collection_delete) {
            echo "<script language='JavaScript'>
                alert('Unable to deleted Custom Made!');
                history.back(-1);
            </script>";
        }

        $query_request_delete = mysql_query("UPDATE `custom_request` SET `level` = '1', `date_upd` = NOW()
            WHERE `id_custom_collection` = '$id_collection';");
        if (!$query_request_delete) {
            echo "<script language='JavaScript'>
                alert('Unable to deleted request data!');
                history.back(-1);
            </script>";
        }

        echo "<script language='JavaScript'>
                alert('Delete custom made successfully!');
                history.back(-1);
            </script>";
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
                                <div class="card-title">Custom Made</div>
                                <div class="pull-right"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th style="width:20%">Images</th>
                                            <th style="width:10%">Gender</th>
                                            <th style="width:15%">Wet Suit Type</th>
                                            <th style="width:15%">Arm Zipper</th>
                                            <th style="width:15%">Ankle Zipper</th>
                                            <th style="width:15%">Current Price</th>
                                            <th style="width:5%">View</th>
                                            <th style="width:10%"></th>
                                            <th style="width:5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    $query_custom_collection = mysql_query("SELECT * FROM `custom_collection` WHERE `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0' ORDER BY `id_custom_collection` DESC LIMIT $start,$perhalaman;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `custom_collection` WHERE `id_member` = '" . $loggedin["id_member"] . "' AND `level` = '0' ORDER BY `id_custom_collection` DESC;"));

    // Set custom price
    $current_price = get_price('price-custom-item');

    $key = 0;
    while ($row_collection = mysql_fetch_array($query_custom_collection)) {
        $content .= '
            <form method="post" action="../cart.php?code=' . ($row_collection["code"]) . '">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="id_collection" value="' . ($row_collection["id_custom_collection"]) . '">
                <tr>
                    <td class="v-align-middle">
                        <img style="width: 100%" src="../custom/public/images/custom_cart/' . $row_collection["image"] . '">
                    </td>
                    <td class="v-align-middle">
                        <p>' . ucfirst($row_collection["gender"]) . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>' . ucfirst($row_collection['wet_suit_type']) . ' Zipper</p>
                    </td>
                    <td class="v-align-middle">
                        <p>' . strtoupper($row_collection["arm_zipper"]) . '</p>
                    </td>
                    <td class="v-align-middle">
                        <p>' . strtoupper($row_collection["ankle_zipper"]) . '</p>
                    </td>
                    <td class="v-align-middle">
                        ' . $currency . ' ' . (($currency_code == CURRENCY_USD_CODE) ? round($current_price, 2) : number_format(($current_price * $USDtoIDR), 0, '.', ',')) . '
                    </td>
                    <td class="v-align-middle">
					    <div class="btn-group">
                            <a href="detail_custom_made.php?id=' . $row_collection["id_custom_collection"] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</a>
                        </div>
                    </td>
                    <td class="v-align-middle">
					  <div class="btn-group">
                        <button type="button" onclick="add_to_cart(' . $row_collection['id_custom_collection'] . ', ' . $key . ');" id="add_custom_cart' . $key . '" class="btn btn-primary btn-xs"><i class="fa fa-credit-card-alt"></i> Add to Cart</button>
					  <a href="?action=deleted&id=' . $row_collection["id_custom_collection"] . '" class="btn btn-danger btn-xs">Deleted</a>
					  </div>
                    </td>
                </tr>

            </form>';
        $key++;
    }

    $content .= '
                                    </tbody>
                                </table>
                                ' . (halaman($sql_total_data, $perhalaman, 1, '?')) . '
                                                <p style="font-size:12px;">
*the price is already including the custom tailoring fee, but not including ankle/arm/genital zipper</p>
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
            function request(id_collection) {
                $.ajax({
                    type: "POST",
                    url: "custom_cart_process.php",
                    data: {action: "request", id_collection: id_collection},
                    dataType: "json",
                    success: function (data) {
                        if (!data.failed) {
                            $("#request_" + id_collection).attr("disabled", true);
                            $("#status_" + id_collection).text(data.status);
                        }
                        window.alert(data.message);
                    }
                });
            }
            
            function add_to_cart(id_collection, key) {
                $.ajax({
                    type: "POST",
                    url: "action_cart.php",
                    data: {action: "add_cart_collection", id_collection: id_collection},
                    dataType: "json",
                    success: function (data) {
                        if (data.status == "error") {
                            alert(data.msg);
                        } else {
                            alert(data.msg);
                            $("#add_custom_cart"+key).attr("disabled", true);
                        }
                    }
                });
            }
        </script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;

} else {
    header('location:../login.php');
}

?>