<?php

/*

 * Project Name: SeaGods

 * Project URI: http://seagodswetsuit.com

 * Author: GlobalXtreme.net

 * Version: 1.0  | 1 Februari 2018

 * Email: adit@globalxtreme.net

 */

include("config/configuration.php");
include("../web/config/shipping/action_raja_ongkir.php");
include("../web/config/shipping/province_city.php");
session_start();
ob_start();

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    global $conn;
    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = isset($_GET['id']) ? "Edit Member" : "Add Member";
    $titlepage = $titlebar;
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $simpan = isset($_POST["simpan"]) ? $_POST["simpan"] : '';
    $update = isset($_POST["update"]) ? $_POST["update"] : '';

    if ($simpan == "Simpan") {

        // Set parameter request
        $firstname = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
        $lastname = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
        $username = isset($_POST['username']) ? strip_tags(trim($_POST["username"])) : "";
        $password = isset($_POST['password']) ? strip_tags(trim($_POST["password"])) : "";
        $email = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
        $phonenumber = isset($_POST['phonenumber']) ? strip_tags(trim($_POST["phonenumber"])) : "";
        $address = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";
        $province = isset($_POST['province']) ? strip_tags(trim($_POST["province"])) : 0;
        $city = isset($_POST['city']) ? strip_tags(trim($_POST["city"])) : 0;
        $bank = isset($_POST['bank']) ? strip_tags(trim($_POST["bank"])) : 0;
        $account_number = isset($_POST['account_number']) ? strip_tags(trim($_POST["account_number"])) : null;
        $postalcode = isset($_POST['postalcode']) ? strip_tags(trim($_POST["postalcode"])) : "";
        $photoNameUpload = '';

        if (!empty($firstname) && !empty($lastname) && !empty($username) &&
            !empty($password) && !empty($email) && !empty($phonenumber)
        ) {

            // Check username
            $username_query = mysql_query("SELECT * FROM `users` WHERE `username` = '$username';");
            if (mysql_num_rows($username_query) > 0) {
                echo "<script language='JavaScript'>
                    alert('Username not available);
                    window.history.go(-1);
                </script>";
                exit();
            }

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

            // Begin transaction
            begin_transaction();

            $insert_member_query = "INSERT INTO `member` (`id_member`, `foto`, `firstname`, `lastname`, `idpropinsi`, `idkota`, `id_bank`, `account_number`, `alamat`, `notelp`, `kode_pos`, `date_add`, `date_upd`, `level`)
		        VALUES (NULL, '$photoNameUpload', '$firstname', '$lastname', '$province', '$city', '$bank', '$account_number', '$address', '$phonenumber', '$postalcode', NOW(), NOW(), '0');";

            // Error
            if (!mysql_query($insert_member_query)) {
                roll_back();
                echo "<script language='JavaScript'>
                    alert('Unable to create admin member');
                    window.history.go(-1);
                </script>";
                exit();
            }

            // Get id member
            $id_member_query = mysql_query("SELECT *  FROM `member` WHERE `level`='0' ORDER BY `id_member` DESC LIMIT 0,1");
            $row_id_member = mysql_fetch_array($id_member_query);
            $id_member = $row_id_member["id_member"];

            // Insert user
            $insert_user_query = "INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `group`, `lastvisit`, `online`, `blokir`, `id_member`)
	            VALUES (NULL, '$username', MD5('$password'), '$email', 'admin', '', '', 'tidak', '$id_member');";

            // Error
            if (!mysql_query($insert_user_query)) {
                roll_back();
                echo "<script language='JavaScript'>
                    alert('Unable to create user');
                    window.history.go(-1);
                </script>";
                exit();
            }

            // commit
            commit();

            echo "<script language='JavaScript'>
                alert('Admin has been created successfully');
                location.href = 'list_admin.php';
            </script>";
            exit();

        } else {
            echo "<script language='JavaScript'>
                alert('Parameter request is required');
                window.history.go(-1);
            </script>";
            exit();
        }

    }

    if ($update == "Update") {

        $id_member = isset($_POST['id_member']) ? strip_tags(trim($_POST["id_member"])) : "";
        $firstname = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
        $lastname = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
        $password = isset($_POST['password']) ? strip_tags(trim($_POST["password"])) : "";
        $confirmpassword = isset($_POST['confirmpassword']) ? strip_tags(trim($_POST["confirmpassword"])) : "";
        $email = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
        $phonenumber = isset($_POST['phonenumber']) ? strip_tags(trim($_POST["phonenumber"])) : "";
        $address = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";
        $province = isset($_POST['province']) ? strip_tags(trim($_POST["province"])) : 0;
        $city = isset($_POST['city']) ? strip_tags(trim($_POST["city"])) : 0;
        $bank = isset($_POST['bank']) ? strip_tags(trim($_POST["bank"])) : 0;
        $account_number = isset($_POST['account_number']) ? strip_tags(trim($_POST["account_number"])) : NULL;
        $postalcode = isset($_POST['postalcode']) ? strip_tags(trim($_POST["postalcode"])) : "";
        $photoNameUpload = isset($_POST['oldPhoto']) ? strip_tags(trim($_POST["oldPhoto"])) : "";

        if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($phonenumber)) {

            if ($_FILES['photo']['tmp_name'] != "") {

                $target_path = "images/members/"; // Declaring Path for uploaded images.

                // Set memory limit in php.ini
                ini_set('memory_limit', '120M');

                // Get member
                $member_query = mysql_query("SELECT * FROM `member` WHERE `id_member` = '$id_member' LIMIT 0,1;", $conn);
                $row_member = mysql_fetch_array($member_query);

                // Delete file photo old
                if ($row_member['foto'] != '' || $row_member['foto'] != NULL) {
                    if (file_exists($target_path . $row_member['foto'])) {
                        unlink($target_path . $row_member['foto']);
                    }
                }

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

            // Update member
            $update_member_query = "UPDATE `member` SET foto = '$photoNameUpload', `firstname` = '$firstname', `lastname` = '$lastname',
                `idpropinsi` = '$province', `idkota` = '$city', `id_bank` = '$bank', `account_number` = '$account_number', `alamat` = '$address',
		        `notelp` = '$phonenumber', `kode_pos` = '$postalcode', `date_upd` = NOW() WHERE `id_member` = '$id_member';";

            // Error
            if (!mysql_query($update_member_query)) {
                echo "<script language='JavaScript'>
                    alert('Unable to update admin member');
                    location.href = 'list_admin.php';
                </script>";
                exit();
            }

            // Set value update password
            $update_password_query = '';
            if ($password != "") {
                $update_password_query = "`password` = MD5('$password'),";
            }

            $update_user_query = "UPDATE `users` SET $update_password_query `email` = '$email' WHERE `id_member` = '$id_member';";

            // Error
            if (!mysql_query($update_user_query)) {
                echo "<script language='JavaScript'>
                    alert('Unable to update user');
                    location.href = 'list_admin.php';
                </script>";
                exit();
            }

            // Success
            echo "<script language='JavaScript'>
                alert('Admin has been updated successfully');
                location.href = 'list_admin.php" . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . "';
            </script>";
            exit();

        } else {
            echo "<script language='JavaScript'>
                alert('Parameter request is required');
                window.history.go(-1);
            </script>";
            exit();
        }
    }

    if (isset($_GET["id"])) {

        $id_member = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
        $query = "SELECT `member`.*, `users`.`email`, `users`.`username` FROM `member`, `users` WHERE `users`.`id_member` = `member`.`id_member` AND  `member`.`level` = '0' AND `member`.`id_member` = '$id_member' ORDER BY `member`.`id_member` DESC  ;";
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
                                ' . $titlebar . '
                            </div>
                        </div>
                       
                        <div class="card-block">
                            <form class="" action="" method="post"  enctype="multipart/form-data" role="form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required">
                                            <label>First name</label>
                                            <input type="text" class="form-control" required name="firstname"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["firstname"])) : "") . '">
                                            <input type="hidden" class="form-control" required name="id_member"
                                                   value="' . (isset($_GET['id']) ? $_GET['id'] : "") . '">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required">
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
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["username"])) : "") . '" ' . (isset($_GET['id']) ? 'disabled' : '') . '>
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
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label>Province</label>
                                            <select class="form-control" name="province" id="province">
                                                <option hidden>-- Choose Province --</option>';

    // Get province
    $get_province = get_province();

    foreach ($get_province->rajaongkir->results as $province) {
        $content .= '<option value="' . $province->province_id . '" ' . (isset($_GET['id']) ? (($province->province_id == $data_member['idpropinsi']) ? 'selected' : '') : '') . '>' . $province->province . '</option>';
    }

    $content .= '
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default ">
                                            <label>City</label>
                                            <select class="form-control" name="city" id="city">
                                                <option hidden>-- Choose City --</option>';

    // Set parameter
    $parameter = [];
    if (isset($_GET['id'])) {
        $parameter = $data_member['idpropinsi'] ? [
            'province' => $data_member['idpropinsi']
        ] : [];
    }

    // Get province
    $get_city = get_city($parameter);

    foreach ($get_city->rajaongkir->results as $city) {
        $content .= '<option value="' . $city->city_id . '" ' . (isset($_GET['id']) ? (($city->city_id == $data_member['idkota']) ? 'selected' : '') : '') . '>' . $city->city_name . '</option>';
    }

    $content .= '
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default ">
                                            <label>Address</label>
                                            <textarea class="form-control" required style="height:60px" name="address">' . (isset($_GET['id']) ? strip_tags(trim($data_member["alamat"])) : "") . '</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default ">
                                            <label>Bank</label>
                                            <select class="form-control" name="bank" id="bank">
                                                <option hidden>-- Choose Bank --</option>';

    // Bank query
    $bank_query = mysql_query("SELECT * FROM `bank_account` WHERE `level` = '0';");

    while ($row_bank = mysql_fetch_assoc($bank_query)) {
        $content .= '<option value="' . $row_bank['id'] . '" ' . (isset($_GET['id']) ? (($row_bank['id'] == $data_member['id_bank']) ? 'selected' : '') : '') . '>' . $row_bank['name'] . '</option>';
    }

    $content .= '
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default ">
                                            <label>Account Number</label>
                                            <input type="text" class="form-control" name="account_number" value="' . (isset($_GET['id']) ? $data_member['account_number'] : "") . '">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default ">
                                            <label>Postal Code</label>
                                            <input type="text" class="form-control" required name="postalcode"
                                                   value="' . (isset($_GET['id']) ? strip_tags(trim($data_member["kode_pos"])) : "") . '">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default ">
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
                                <a href="list_admin.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '" class="btn btn-outline-primary pull-right" name="">Back to List</a>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

    $plugin = '
        <script>
            
            jQuery("#province").on("change", function () {
                var id_province = jQuery("#province").val();
                jQuery.ajax({
                    type: "POST",
                    url: "../web/getKota.php",
                    data: {
                        id_province: id_province
                    },
                    success: function (html) {
                        console.log(html);
                        jQuery("#city").html(html);
                    }
                });
            });
    
        </script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('Location: logout.php');
}

?>

