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

    //$loggedin = logged_inadmin();
    $titlebar = "Profile ";
    $titlepage = "Profile ";
    $menu = "";
    $user = '' . $_SESSION['user'] . '';

    $simpan = isset($_POST["submit"]) ? $_POST["submit"] : '';

    if ($simpan == "account") {

        $username = isset($_POST['username']) ? strip_tags(trim($_POST["username"])) : "";
        $firstname = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
        $lastname = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
        $email = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
        $notelp = isset($_POST['notelp']) ? strip_tags(trim($_POST["notelp"])) : "";
        $bio = isset($_POST['bio']) ? strip_tags(trim($_POST["bio"])) : "";

        $query = "UPDATE `users` set `username` = '$username' , `email` = '$email' where `id_member`='" . $loggedin["id_member"] . "'";
        $query_m = "UPDATE `member` set `namapengguna` = '$username' , `firstname` = '$firstname' , `lastname` = '$lastname'
              , `email` = '$email' , `notelp` = '$notelp' , `bio` = '$bio' where `id_member` = '" . $loggedin["id_member"] . "'";


        mysql_query($query) or die("<script language='JavaScript'>
                alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                window.history.go(-1);
            </script>");

        mysql_query($query_m) or die("<script language='JavaScript'>
                alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
                alert('Data telah disimpan!');
                location.href = 'profile.php';
            </script>";

    }

    $change = isset($_POST["password"]) ? $_POST["password"] : '';

    if ($change == "password") {
        $curpass = isset($_POST['curpass']) ? strip_tags(trim($_POST["curpass"])) : "";
        $password = isset($_POST['newpass']) ? strip_tags(trim($_POST["newpass"])) : "";

        $sql_users = mysql_query("SELECT `password` FROM `users` WHERE `id_member`='" . $loggedin["id_member"] . "'");
        $row_users = mysql_fetch_array($sql_users);

        $md5curpass = md5($curpass);
        $data_users_password = $row_users['password'];

        if ($md5curpass == $data_users_password) {
            $md5pass = md5($password);
            $query = "UPDATE `users` SET `password` = '$md5pass' WHERE `id_member`='" . $loggedin["id_member"] . "'";


            //echo $query;
            mysql_query($query) or die("<script language='JavaScript'>
                    alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                    window.history.go(-1);
                </script>");

            echo "<script language='JavaScript'>
                    alert('Your changes have been saved.Your new password is effective immediately.');
                    location.href = 'profile.php';
                </script>";
        } else {
            echo "<script language='JavaScript'>
                    alert('The passwords you\'ve entered don\'t match. Please try again.');
                    window.history.go(-1);
                </script>";
        }

    }

    $addrs = isset($_POST["alamat"]) ? $_POST["alamat"] : '';

    if ($addrs == "alamat") {
        $address = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";

        $query = "UPDATE `member` set `alamat` = '$address'  where `id_member`='" . $loggedin["id_member"] . "'";
        $query_a = "UPDATE `alamat_member` set `alamat` = '$address' where `id_member` = '" . $loggedin["id_member"] . "'";

        mysql_query($query) or die("<script language='JavaScript'>
                alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                window.history.go(-1);
            </script>");
        mysql_query($query_a) or die("<script language='JavaScript'>
                alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
                alert('Data telah disimpan!');
                location.href = 'profile.php';
            </script>";
    }

    $sql_member = mysql_query("SELECT `member`.* , `users`.* FROM `member` , `users` where `member`.`id_member` ='" . $loggedin["id_member"] . "' AND `users`.`id_member` ='" . $loggedin["id_member"] . "'") or die (mysql_error());
    $row_member = mysql_fetch_array($sql_member);


    $content = '
        <div class="page-container ">
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
            
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class="container container-fixed-lg">
                       
                        <!-- START card -->
                        <div id="rootwizard">
                        
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator nav-stack-sm" role="tablist" data-init-reponsive-tabs="dropdownfx">
                                <li class="nav-item">
                                    <a class="active" data-toggle="tab" href="#tab1" role="tab"><i class="fa fa-shopping-cart tab-icon"></i> <span>Your Account</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="" data-toggle="tab" href="#tab2" role="tab"><i class="fa fa-truck tab-icon"></i> <span>Change Password</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="" data-toggle="tab" href="#tab3" role="tab"><i class="fa fa-credit-card tab-icon"></i> <span>Address</span></a>
                                </li>
                            </ul>
                            
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane padding-20 sm-no-padding active slide-left" id="tab1">
                                    <div class="row row-same-height">
                                        <div class="col-md-5 b-r b-dashed b-grey ">
                                            <div class="padding-30 sm-padding-5">
                                                <p>Preview Account</p>
                                                <div class="form-group-attached detail-container">
                                                    <label>Username</label>
                                                    <h5><b>' . $row_member['username'] . '</b></h5>
                                                    
                                                    <div class="row clearfix no-margin">
                                                        <div class="col-sm-6">
                                                            <label>First Name</label>
                                                            <h5><b>' . $row_member['firstname'] . '</b></h5>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Last name</label>
                                                            <h5><b>' . $row_member['lastname'] . '</b></h5>
                                                        </div>
                                                    </div>
                                                    
                                                    <label>Email</label>
                                                    <h5><b>' . $row_member['email'] . '</b></h5>
                                                    
                                                    <label>Phone Number</label>
                                                    <h5><b>' . $row_member['notelp'] . '</b></h5>
                                                    
                                                    <label>Bio</label>
                                                    <h5><b>'. ($row_member['bio'] ? $row_member['bio'] : "-").'</b></h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="padding-30 sm-padding-5">
                                                <p>Update Account</p>
                                                <form role="form" method="post" action="">
                                                    <div class="form-group-attached">
                                                        <div class="form-group form-group-default ">
                                                            <label>Username</label>
                                                            <span><input type="text" class="form-control" name="username" value="' . $row_member['username'] . '"></span>
                                                        </div>
                                                        <div class="row clearfix no-margin">
                                                            <div class="col-sm-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>First Name</label>
                                                                    <span><input type="text" class="form-control" name="firstname" value="' . $row_member['firstname'] . '"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Last name</label>
                                                                    <span><input type="text" class="form-control" name="lastname" value="' . $row_member['lastname'] . '"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-default ">
                                                            <label>Email</label>
                                                            <span><input type="text" class="form-control" name="email" value="' . $row_member['email'] . '"></span>
                                                        </div>
                                                        <div class="form-group form-group-default ">
                                                            <label>Phone Number</label>
                                                            <span><input type="text" class="form-control" name="notelp" value="' . $row_member['notelp'] . '"></span>
                                                        </div>
                                                        <div class="form-group form-group-default ">
                                                            <label>Bio</label>
                                                            <span><textarea name="bio" class="form-control" name="bio">' . $row_member['bio'] . '</textarea></span>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <button type="submit" class="btn btn-primary btn-cons" name="submit" value="account" >Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab2">
                                    <div class="row row-same-height">
                                        <div class="col-md-5 b-r b-dashed b-grey"></div>
                                            <div class="col-md-7">
                                                <div class="padding-30 sm-padding-5">
                                                    <p>Password</p>
                                                    <form method="post" action="profile.php">
                                                        <div class="form-group-attached">
                                                            <div class="form-group form-group-default ">
                                                                <label>Current Password</label>
                                                                <span><input type="password" class="form-control" name="curpass"></span>
                                                            </div>
                                                            <div class="form-group form-group-default ">
                                                                <label>New Password</label>
                                                                <span><input type="password" class="form-control" name="newpass"></span>
                                                            </div>
                                                            <div class="form-group form-group-default ">
                                                                <label>Re-enter Password</label>
                                                                <span><input type="password" class="form-control" name="renewpass"></span>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <button type="submit" class="btn btn-primary btn-cons" name="password" value="password">Change</button>
                                                    </form>   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab3">
                                        <div class="row row-same-height">
                                            <div class="col-md-5 b-r b-dashed b-grey ">
                                                <div class="padding-30 sm-padding-5">
                                                    <p>Preview Address</p>
                                                    <div class="form-group-attached detail-container">
                                                        <label>Address</label>
                                                        <h5><b>'. $row_member['alamat'] .'</b></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="padding-30 sm-padding-5">
                                                    <p>Update Address</p>
                                                    <form role="form" method="post" action="">
                                                        <div class="form-group-attached">
                                                            <div class="form-group form-group-default ">
                                                                <label>Address</label>
                                                                <textarea name="address" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <button type="submit" class="btn btn-primary btn-cons" name="alamat" value="alamat">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="wizard-footer padding-20 bg-master-light">
                                    <p class="small hint-text pull-left no-margin">
                                        SEAGODS WETSUIT
                                        <br>By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia .
                                    </p>
                                    <div class="pull-right">
                                        <img src="assets/img/s-logo.png" alt="logo" data-src="assets/img/s-logo.png" data-src-retina="assets/img/s-logo.png"  height="22">
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END CONTAINER FLUID -->
                
                </div>
                <!-- END PAGE CONTENT -->
            
                <!-- START COPYRIGHT -->
            
                <!-- START CONTAINER FLUID -->
                <!-- END CONTAINER FLUID -->
            
                <!-- END COPYRIGHT -->
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>';

    $plugin = '';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('location:../login.php');
}

?>