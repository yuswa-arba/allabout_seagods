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
$titlebar ="Add Member";
$titlepage="Add member";
$menu="";
$user=  ''.$loggedin['firstname'].' '.$loggedin['lastname'].'';


$simpan = isset($_POST["simpan"]) ? $_POST["simpan"] : '';
$update = isset($_POST["update"]) ? $_POST["update"] : '';
	if($simpan == "Simpan"){
	    $firstname  = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
		$lastname  = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
		$username  = isset($_POST['username']) ? strip_tags(trim($_POST["username"])) : "";
		$password  = isset($_POST['password']) ? strip_tags(trim($_POST["password"])) : "";
		$confirmpassword  = isset($_POST['confirmpassword']) ? strip_tags(trim($_POST["confirmpassword"])) : "";
		$email  = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
		$phonenumber  = isset($_POST['phonenumber']) ? strip_tags(trim($_POST["phonenumber"])) : "";
		$address  = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";
		$postalcode  = isset($_POST['postalcode']) ? strip_tags(trim($_POST["postalcode"])) : "";

	    
	    $querymember =  "INSERT INTO `member` (`id_member`, `foto`, `firstname`, `lastname`, `addrs`, `notelp`, `kode_pos`, `date_add`, `date_upd`, `level`) 
										VALUES (NULL, 'images/s-logo.png', '$firstname', '$lastname', '$address', '$phonenumber', '$postalcode', NOW(), NOW(), '0');";
										
										
	    //echo $query ;
	mysql_query($querymember) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");
	
	$sql_id_member = mysql_query("SELECT *  FROM `member` WHERE `level`='0' ORDER BY `id_member` DESC LIMIT 0,1");
	$row_id_member = mysql_fetch_array($sql_id_member);
	$id_member = $row_id_member["id_member"]+1;
	
	$queryuser =  "INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `group`, `lastvisit`, `online`, `blokir`, `id_member`)
						VALUES (NULL, '$username', MD5('$password'), '$email', 'member', '', '', 'tidak', '$id_member');
";
										
										
	    //echo $query ;
	mysql_query($queryuser) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");
			
	
	
	echo "<script language='JavaScript'>
			alert('Data telah disimpan!');
			location.href = 'list_member.php';
            </script>";
	}
	
	if($update == "Update"){
	    $idmember  = isset($_POST['idmember']) ? strip_tags(trim($_POST["idmember"])) : "";		
	    $firstname  = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
		$lastname  = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
		$username  = isset($_POST['username']) ? strip_tags(trim($_POST["username"])) : "";
		$password  = isset($_POST['password']) ? strip_tags(trim($_POST["password"])) : "";
		$confirmpassword  = isset($_POST['confirmpassword']) ? strip_tags(trim($_POST["confirmpassword"])) : "";
		$email  = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
		$phonenumber  = isset($_POST['phonenumber']) ? strip_tags(trim($_POST["phonenumber"])) : "";
		$address  = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";
		$postalcode  = isset($_POST['postalcode']) ? strip_tags(trim($_POST["postalcode"])) : "";

	    
	    $querymember =  "UPDATE `seagods`.`member` SET `firstname` = '$firstname', `lastname` = '$lastname', `addrs` = '$address', 
		`notelp` = '$phonenumber', `kode_pos` = '$postalcode', `date_upd` = NOW() WHERE `member`.`id_member` = $idmember;";
										
										
	    //echo $query ;
	mysql_query($querymember) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");
	
	
	$queryuser =  "UPDATE `users` SET `username` = '$username ', `email` = '$email' WHERE `users`.`id_member` = $idmember;
";
										
										
	    //echo $query ;
	mysql_query($queryuser) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");
			
	
	
	echo "<script language='JavaScript'>
			alert('Data telah disimpan!');
			location.href = 'list_member.php';
            </script>";
	}
	
	
		if(isset($_GET["id"])){

        $id_member  	= isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
        $query          = "SELECT `member`.*, `users`.`email`, `users`.`username` FROM `member`,`users` WHERE `users`.`id_member` = `member`.`id_member` AND  `member`.`level` = '0' AND `member`.`id_member` = '$id_member' ORDER BY `member`.`id_member` DESC  ;";
		$id          	= mysql_query($query);
		$data_member	= mysql_fetch_array($id);
        
						}

$content = '
 <div class=" container    container-fixed-lg">
<div class="row">
              <div class="col-lg-12">
   <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                      ADD NEW MEMBER
                    </div>
                  </div>
				  
                  <div class="card-block">
                    <form class="" action="form_member.php" method="post"  enctype="multipart/form-data" role="form">
					 <div class="row">

                        <div class="col-md-6">
                          <div class="form-group form-group-default required">
                            <label>First name</label>
                            <input type="text" class="form-control" required name="firstname" 
							value="'.(isset($_GET['id']) ? strip_tags(trim($data_member["firstname"])) : "").'">
							
							<input type="hidden" class="form-control" required name="idmember" 
							value="'.(isset($_GET['id']) ? $_GET['id'] : "").'">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group form-group-default">
                            <label>Last name</label>
                            <input type="text" class="form-control" name="lastname"
							value="'.(isset($_GET['id']) ? strip_tags(trim($data_member["lastname"])) : "").'">
                          </div>
                        </div>
                      </div>
					  
					  <div class="form-group form-group-default required ">
                        <label>Username</label>
                        <input type="text" class="form-control" required name="username"
						value="'.(isset($_GET['id']) ? strip_tags(trim($data_member["username"])) : "").'">
                      </div>
					  
					  <div class="form-group form-group-default '.(isset($_GET['id']) ? "" : "required").' ">
                        <label>Password</label>
                        <input type="password" class="form-control" '.(isset($_GET['id']) ? "" : "required").' name="password"
						>
						
                      </div>
					'.(isset($_GET['id']) ? "  <br><span style='font-size:9px'> if your password not replaced, no need to fill this field.</span>" : "").' 
					   <div class="form-group form-group-default required " hidden>
                        <label>Confirm Password</label>
                        <input type="password" class="form-control"  name="confirmpassword">
                      </div>
					  
                      <div class="form-group form-group-default required ">
                        <label>Email</label>
                        <input type="email" class="form-control" required name="email"
						value="'.(isset($_GET['id']) ? strip_tags(trim($data_member["email"])) : "").'">
                      </div>
					  
					  <div class="form-group form-group-default required ">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" required name="phonenumber"
						value="'.(isset($_GET['id']) ? strip_tags(trim($data_member["notelp"])) : "").'">
                      </div>
					  
					  <div class="form-group form-group-default required ">
                        <label>Address</label>
						<textarea class="form-control" required style="height:60px" name="address">'.(isset($_GET['id']) ? strip_tags(trim($data_member["addrs"])) : "").'</textarea>
                      </div>
					 
					  <div class="form-group form-group-default required " style="width:500px">
                        <label>Postal Code</label>
                        <input type="text" class="form-control" required name="postalcode"
						value="'.(isset($_GET['id']) ? strip_tags(trim($data_member["kode_pos"])) : "").'">
                      </div>
					
					
					  
					  <button class="btn btn-primary" type="submit" name="'.(isset($_GET['id']) ? "update"  : "simpan").'" value="'.(isset($_GET['id']) ? "Update"  : "Simpan").'">Create a New Account</button>
					
					
                    </form>
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