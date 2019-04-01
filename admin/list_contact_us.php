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

if($loggedin = logged_inadmin()){ // Check if they are logged in

if(isset($_POST['nilai'])){
    $_SESSION['nilai_login'] = $_POST['nilai']+1;
}else{
    $_SESSION['nilai_login'] = 0;
}

$loggedin = logged_inadmin();
$titlebar ="List Contact Us";
$titlepage="List Contact Us";
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
                <div class="card-title">List Incoming Messages
                </div>
                <div class="pull-right">
                  
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="card-block">
                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                  <thead>
                    <tr>
					<th style="width:15%">Name</th>
                      <th style="width:15%">Email</th>
                      <th style="width:25%">Subject</th>
                      <th style="width:30%">Message </th>
                    </tr>
                  </thead>
                  <tbody>';
				  $sql_item = mysql_query("SELECT * FROM `contactus`  ORDER BY `id` DESC  ;");
				  while ($row_item = mysql_fetch_array($sql_item)){
				  $content .='
                    <tr>
					<td class="v-align-middle">
                        <p>'.$row_item["name"].'</p>
                      </td>
                      <td class="v-align-middle">
                        <p>'.$row_item["email"].'</p>
                      </td>
                      <td class="v-align-middle">
                        <p>'.$row_item["subject"].'</p>
                      </td>
                      <td class="v-align-middle">
                        <p>'.$row_item["messages"].'</p>
                      </td>
                      </td>
						
                    </tr>';
					
				
				  }
				  
				  $content .='
                    
                    
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
	
	<!-- RETINA POP UP -->
	<link rel="stylesheet" href="assets/plugins/colorbox-master/example1/colorbox.css" />
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="assets/plugins/colorbox-master/jquery.colorbox.js"></script>
	<script>
		jq = $.noConflict();
		jq(document).ready(function(){
			jq(\'.retina\').colorbox({iframe:true, width:"80%", height:"80%"});
		});
	</script>
	<!-- END RETINA POP UP -->
	';
$template = admin_template($content,$titlebar,$titlepage,$user,$menu,$plugin);
echo $template;

}else {
    header( 'Location: logout.php' ) ;
}

?>