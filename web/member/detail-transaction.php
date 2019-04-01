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

    $titlebar = "Transaction Detail ";
    $titlepage = "Transaction Detail ";

    $menu = "";
    $user = '' . $_SESSION['user'] . '';

    if (isset($_GET["id_transaction"])) {

        $id_transaction = isset($_GET['id_transaction']) ? strip_tags(trim($_GET['id_transaction'])) : "";


        $sql_transaction = mysql_query("SELECT * FROM `transaction` WHERE `id_member`='" . $loggedin["id_member"] . "' AND `id_transaction` = '$id_transaction' ORDER BY `date_add` ");
        $row_transaction = mysql_fetch_array($sql_transaction);
        $sql_member = mysql_query("SELECT * FROM `member` where `id_member` ='" . $loggedin["id_member"] . "' ");
        $row_member = mysql_fetch_array($sql_member);

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
              <div class="card-header p-t-15">
                <div class="bold fs-16 m-b-10">TRANSACTION DETAIL</div>
                <div class="row">
                    <div class="col-md-2">BUYER <span class="pull-right">:</span></div>
                    <div class="col-md-6"><b>' . (isset($row_member['firstname']) ? $row_member['firstname'] . ' ' . $row_member['lastname'] : '-') . '</b></div>
                </div>
                <div class="row">
                    <div class="col-md-2">INVOICE NUMBER <span class="pull-right">:</span></div>
                    <div class="col-md-6"><b>' . (isset($row_transaction['kode_transaction']) ? $row_transaction['kode_transaction'] : '-') . '</b></div>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="card-block">
                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                  <thead>
                    <tr>
                      <th >Product Name</th>
                      <th style="width:10%">QTY</th>
                      <th style="width:25%">Price</th>
					  
                    </tr>
                  </thead>
                  <tbody>';

    if (isset($_GET["id_transaction"])) {

        $sql_cart = mysql_query("SELECT * FROM `cart` 
            WHERE `id_member`='" . $loggedin["id_member"] . "'
            AND `id_transaction` = '".$_GET["id_transaction"]."'
            AND `level` = '0' ORDER BY `date_add`");


        while ($row_cart = mysql_fetch_array($sql_cart)) {

            if (!$row_cart['is_custom_cart']) {
                $query_item = mysql_query("SELECT * FROM `item` 
                    WHERE `id_item` = '" . $row_cart["id_item"] . "';");
                $row_item = mysql_fetch_array($query_item);
            } else {
                $query_item = mysql_query("SELECT * FROM `custom_collection` 
                    WHERE `id_custom_collection` = '" . $row_cart["id_item"] . "';");
                $row_item = mysql_fetch_array($query_item);
            }

            $content .= '  
                    <tr>
                      <td class="v-align-middle">
                        <p>' . (isset($row_item['title']) ? $row_item['title'] : "Custom Cart") . '</p>
                      </td>
                      <td class="v-align-middle">
                        <p>' . $row_cart['qty'] . '</p>
                      </td>
                      <td class="v-align-middle">
                        <p>$ ' . $row_item['price'] . '</p>
                      </td>
					  
						
                    </tr>';
        }
    }

    $content .= '
					<tr > 
					  <td colspan="3" >
					    <h4 class="normal">
					        <span class="fs-17">Total : </span>
					        <b class="bold">$ ' . (isset($row_transaction['total']) ? $row_transaction['total'] : '-') . '</b>
					    </h4>
					  </td> 
					</tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <!-- END card -->
          </div>


		  </div></div></div>
		  
';

    $plugin = '     <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
	    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/datatables.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->
	
	
	
	';
    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;


} else {
    header('location:../login.php');
}

?>