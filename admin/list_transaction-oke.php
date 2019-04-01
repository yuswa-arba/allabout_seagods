<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include ("config/configuration.php");
session_start();
ob_start();

if(isset($_POST['nilai'])){
    $_SESSION['nilai_login'] = $_POST['nilai']+1;
}else{
    $_SESSION['nilai_login'] = 0;
}

if($loggedin = logged_inadmin()){ // Check if they are logged in




//$loggedin = logged_inadmin();
$titlebar ="List Transaction";
$titlepage="List Transaction";
$menu="";
$user=  ''.$loggedin['firstname'].' '.$loggedin['lastname'].'';

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
                <div class="card-title">List Transaction 
                </div>
                <div class="pull-right">
                  
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="card-block">
                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                  <thead>
                    <tr>
					<th style="width:20%">Invoice</th>
                      <th style="width:20%">Buyers</th>
                      <th style="width:15%">Date</th>
                      <th style="width:15%">Total</th>
                      <th style="width:15%">Status</th>
					  <th style="width:5%">Detail</th>
					  <th style="width:15%"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
					<td class="v-align-middle">
                       102083627
                      </td>
                      <td class="v-align-middle">
                        <p>Luhung Haryo</p>
                      </td>
                      <td class="v-align-middle">
                        <p>24-5-1028</p>
                      </td>
                      <td class="v-align-middle">
                        <p>IDR 250</p>
                      </td>
                      <td class="v-align-middle">
                        <p>CONFIRMATED</p>
                      </td>
					  <td class="v-align-middle">
                        <a href="detail_transaction">view</a>
                      </td>
					  <td>
					  <div class="btn-group">
                          <button type="button" class="btn btn-success"><i class="fa fa-save"></i>
                          </button>
                          <button type="button" class="btn btn-success"><i class="fa fa-pencil"></i>
                          </button>
                          <button type="button" class="btn btn-success"><i class="fa fa-trash-o"></i>
                          </button>
                        </div>
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

$plugin ='     <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
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
$template = admin_template($content,$titlebar,$titlepage,$user,$menu,$plugin);
echo $template;

}else {
    header( 'Location: logout.php' ) ;
}

?>