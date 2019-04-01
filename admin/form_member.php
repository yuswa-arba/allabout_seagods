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

    $loggedin = logged_inadmin();
    $titlebar = isset($_GET['id']) ? "Edit Member" : "Add Member";
    $titlepage = $titlebar;
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $simpan = isset($_POST["simpan"]) ? $_POST["simpan"] : '';
    $update = isset($_POST["update"]) ? $_POST["update"] : '';

    if ($simpan == "Simpan") {

        $firstname = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
        $lastname = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
        $username = isset($_POST['username']) ? strip_tags(trim($_POST["username"])) : "";
        $password = isset($_POST['password']) ? strip_tags(trim($_POST["password"])) : "";
        $confirmpassword = isset($_POST['confirmpassword']) ? strip_tags(trim($_POST["confirmpassword"])) : "";
        $email = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
        $phonenumber = isset($_POST['phonenumber']) ? strip_tags(trim($_POST["phonenumber"])) : "";
        $address = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";
        $postalcode = isset($_POST['postalcode']) ? strip_tags(trim($_POST["postalcode"])) : "";
        $photoNameUpload = '';

        if ($_FILES['photo']['tmp_name'] != "") {
            $target_path = "images/members/"; // Declaring Path for uploaded images.

            // Set memory limit in php.ini
            ini_set('memory_limit', '120M');

            // Loop to get individual element from the array
            $validextensions = array("jpeg", "jpg", "png", "PNG"); // Extensions which are allowed.
            $RandomNumber = rand(0, 9999999999); // for create name file upload
            $imageName = $RandomNumber . "-" . ($_FILES['photo']['name']);

            $ext = explode('.', $_FILES['photo']['name']); // Explode file name from dot(.)
            $file_extension = end($ext); // Store extensions in the variable.

            if (($_FILES["photo"]["size"] < 5000000) // Approx. 5Mb files can be uploaded.
                && in_array($file_extension, $validextensions)
            ) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path . $imageName)) {
                    $photoNameUpload = $imageName;
                } else { // If File Was Not Moved.
                    echo "<script language='JavaScript'>
									alert('File Was Not Moved.');
									window.history.go(-1);
							      </script>";
                }
            }
        }

        $querymember = "INSERT INTO `member` (`id_member`, `foto`, `firstname`, `lastname`, `alamat`, `notelp`, `kode_pos`, `date_add`, `date_upd`, `level`)
										VALUES (NULL, '$photoNameUpload', '$firstname', '$lastname', '$address', '$phonenumber', '$postalcode', NOW(), NOW(), '0');";

        //echo $query ;
        mysql_query($querymember, $conn) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        $sql_id_member = mysql_query("SELECT *  FROM `member` WHERE `level`='0' ORDER BY `id_member` DESC LIMIT 0,1");
        $row_id_member = mysql_fetch_array($sql_id_member);
        $id_member = $row_id_member["id_member"];

        $queryuser = "INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `group`, `lastvisit`, `online`, `blokir`, `id_member`)
						VALUES (NULL, '$username', MD5('$password'), '$email', 'member', '', '', 'tidak', '$id_member');";

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

    if ($update == "Update") {
        $idmember = isset($_POST['idmember']) ? strip_tags(trim($_POST["idmember"])) : "";
        $firstname = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
        $lastname = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
        $username = isset($_POST['username']) ? strip_tags(trim($_POST["username"])) : "";
        $password = isset($_POST['password']) ? strip_tags(trim($_POST["password"])) : "";
        $confirmpassword = isset($_POST['confirmpassword']) ? strip_tags(trim($_POST["confirmpassword"])) : "";
        $email = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
        $phonenumber = isset($_POST['phonenumber']) ? strip_tags(trim($_POST["phonenumber"])) : "";
        $address = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";
        $postalcode = isset($_POST['postalcode']) ? strip_tags(trim($_POST["postalcode"])) : "";
        $photoNameUpload = isset($_POST['oldPhoto']) ? strip_tags(trim($_POST["oldPhoto"])) : "";

        if ($_FILES['photo']['tmp_name'] != "") {
            $target_path = "images/members/"; // Declaring Path for uploaded images.

            // Set memory limit in php.ini
            ini_set('memory_limit', '120M');

            // Get member
            $queryMemberUpdate = mysql_query("SELECT * FROM `member` WHERE `id_member` = '$idmember' LIMIT 0,1;", $conn);
            $rowMemberUpdate = mysql_fetch_array($queryMemberUpdate);

            // Delete file photo old
            unlink($target_path . $rowMemberUpdate['foto']);

            // Loop to get individual element from the array
            $validextensions = array("jpeg", "jpg", "png", "PNG"); // Extensions which are allowed.
            $RandomNumber = rand(0, 9999999999); // for create name file upload
            $imageName = $RandomNumber . "-" . ($_FILES['photo']['name']);

            $ext = explode('.', $_FILES['photo']['name']); // Explode file name from dot(.)
            $file_extension = end($ext); // Store extensions in the variable.

            if (($_FILES["photo"]["size"] < 5000000) // Approx. 5Mb files can be uploaded.
                && in_array($file_extension, $validextensions)
            ) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path . $imageName)) {
                    $photoNameUpload = $imageName;
                } else { // If File Was Not Moved.
                    echo "<script language='JavaScript'>
									alert('File Was Not Moved.');
									window.history.go(-1);
							      </script>";
                }
            }
        }

        $querymember = "UPDATE `member` SET foto = '$photoNameUpload', `firstname` = '$firstname', `lastname` = '$lastname', `alamat` = '$address', 
		`notelp` = '$phonenumber', `kode_pos` = '$postalcode', `date_upd` = NOW() WHERE `id_member` = $idmember;";

        //echo $query ;
        mysql_query($querymember, $conn) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        // Set value update password
        $queryPasswordUpdate = '';
        if ($password != "") {
            $queryPasswordUpdate = "`password` = MD5('$password'),";
        }

        $queryuser = "UPDATE `users` SET $queryPasswordUpdate `username` = '$username ', `email` = '$email' WHERE `id_member` = $idmember;";

        //echo $query ;
        mysql_query($queryuser, $conn) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			alert('Data telah disimpan!');
			location.href = 'list_member.php';
            </script>";
    }

    if (isset($_GET["id"])) {

        $id_member = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
        $query = "SELECT `member`.*, `users`.`email`, `users`.`username` FROM `member`,`users` WHERE `users`.`id_member` = `member`.`id_member` AND  `member`.`level` = '0' AND `member`.`id_member` = '$id_member' ORDER BY `member`.`id_member` DESC  ;";
        $id = mysql_query($query);
        $data_member = mysql_fetch_array($id);

    }

    $content = '
        <div class=" container container-fixed-lg m-b-15">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">
                                '.$titlebar.'
                            </div>
                        </div>
                        
                        <div class="card-block">
                            <form class="" action="form_member.php" method="post"  enctype="multipart/form-data" role="form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required">
                                            <label>First name</label>
                                            <input type="text" class="form-control" required name="firstname"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["firstname"])) : "") . '">
                                            <input type="hidden" class="form-control" required name="idmember"
                                                   value="' . (isset($_GET['id']) ? $_GET['id'] : "") . '">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label>Last name</label>
                                            <input type="text" class="form-control" name="lastname"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["lastname"])) : "") . '">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required ">
                                            <label>Username</label>
                                            <input type="text" class="form-control" required name="username"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["username"])) : "") . '">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default ' . (isset($_GET['id']) ? "" : "required") . ' ">
                                            <label>Password</label>
                                            <input type="password" class="form-control" ' . (isset($_GET['id']) ? "" : "required") . ' name="password">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required ">
                                            <label>Email</label>
                                            <input type="email" class="form-control" required name="email"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["email"])) : "") . '">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required ">
                                            <label>Phone Number</label>
                                            <input type="text" class="form-control" required name="phonenumber"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["notelp"])) : "") . '">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default required ">
                                            <label>Address</label>
                                            <textarea class="form-control" required style="height:60px" name="address">' . (isset($_GET['id']) ? strip_tags(trim($data_member["alamat"])) : "") . '</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required ">
                                            <label>Postal Code</label>
                                            <input type="text" class="form-control" required name="postalcode"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["kode_pos"])) : "") . '">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required ">
                                            <label>Upload Image</label>
                                            <input type="hidden" name="oldPhoto" value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["foto"])) : "") . '">
                                            <input type="file" name="photo">
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                <button class="btn btn-primary pull-right m-l-10" 
                                        type="submit" name="' . (isset($_GET['id']) ? "update" : "simpan") . '" 
                                        value="' . (isset($_GET['id']) ? "Update" : "Simpan") . '">' . (isset($_GET['id']) ? "Update Account" : "Create a New Account") . '</button>
                                <a href="list_member.php" class="btn btn-outline-primary pull-right" name="">Back to List</a>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

    $plugin = '';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('Location: logout.php');
}

?>
