<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include ("config/configuration.php");
//include ("config/fungsi_thumb.php");
session_start();
ob_start();

if($loggedin = logged_inadmin()){ // Check if they are logged in


if(isset($_POST['nilai'])){
    $_SESSION['nilai_login'] = $_POST['nilai']+1;
}else{
    $_SESSION['nilai_login'] = 0;
}

$loggedin = logged_inadmin();
$titlebar ="View Detail";
$titlepage="View Detail";
$menu="";
$user=  ''.$loggedin['firstname'].' '.$loggedin['lastname'].'';
	  
	
	if(isset($_GET['id'])){
  
	    $id_item      = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
	    $query          = "SELECT `item`.* , `photo`.`id_item` , `photo`.`photo` , `photo`.`level` FROM `item`,`photo` WHERE `item`.`level` = '0' AND `item`.`id_item` = '$id_item' OR `photo`.`id_item` = '$id_item' ORDER BY `item`.`id_item` DESC ;";
	    $id           = mysql_query($query);
	    $data_item    = mysql_fetch_array($id);
	}
	
$content = '
 <div class=" container    container-fixed-lg">
<div class="row">
              <div class="col-lg-12">
   <div class="card card-default">
                  <div class="card-header ">	
                    <div class="card-title">
                      View Detail					
                    </div>
                  </div>
                  <div class="card-block">
                      <div class="form-group form-group-default  ">
                        <label>Product Name</label>
                        '.(isset($_GET['id']) ? strip_tags(trim($data_item["title"])) : "").'
                      </div>

					  <div class="form-group form-group-default  ">
                        <label>Category</label>';
                        $sql 	= mysql_fetch_array(mysql_query("SELECT `item`.* , `category`.`id_cat` , `category`.`category` FROM `item`, `category` WHERE `item`.`level` = '0' AND `category`.`id_cat` = '".$data_item['id_cat']."';"));

                      $content .='
                      	'.(isset($_GET['id']) ? strip_tags(trim($sql["category"])) : "").'
                      </div>

					  <div class="form-group form-group-default  ">
                        <label>Price</label>
                        $'.(isset($_GET['id']) ? strip_tags(trim($data_item["price"])) : "").'
                      </div>
                     <div class="form-group form-group-default ">
                     <label>Images</label>';
                    if(isset($_GET["id"])){
                     $photo = mysql_query("SELECT `photo`.* from `photo` where `photo`.`id_item` = '$id_item' AND `photo`.`level` = '0';");
					 while ($rows = mysql_fetch_array($photo)){
                     	$content .=' 
	                     	<img style="height:200px" src="images/product/150/thumb_'.(isset($_GET['id']) ? strip_tags(trim($rows["photo"] )) : "").'">
	                     	';

                     }
                    }
                    
                     $content .='
                      </div>
                       <div class="form-group form-group-default  ">
                        <label>Detail</label>
						'.(isset($_GET['id']) ? strip_tags(trim($data_item["detail"])) : "").'
                      </div>
					
					<div class="form-group form-group-default  ">
                        <label>Description</label>
						'.(isset($_GET['id']) ? strip_tags(trim($data_item["description"])) : "").'
                      </div>
					
                    
                  </div>
                </div>
				</div>
				</div>
				</div>
		  
';

$plugin ='     <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
	    <!--  BEGIN PAGE LEVEL JS  -->
    <script src="assets/js/datatables.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->
	
	 <!-- upload -->

	<script language="javascript">
	jq = $.noConflict();
	jq(document).ready(function(){
		fields = 0;
		jq(\'.add_more\').click(function(e){
			e.preventDefault();
		
		if (fields != 4) {
			jq(".textADD").append(\'Gambar : <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />\');
			fields += 1;
		} else {
			jq(".textADD").append(\'<br />Hanya 4 upload fields yang diijinkan.\');
			document.product.add_more.disabled= true;
		}
		});
	});
	</script>
		<!-- End upload -->
	
	';
$template = admin_template($content,$titlebar,$titlepage,$user,$menu,$plugin);
echo $template;

}else {
    header( 'Location: logout.php' ) ;
}

?>