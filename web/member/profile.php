<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("../config/shipping/action_raja_ongkir.php");
include("../config/shipping/province_city.php");
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

        $firstname = isset($_POST['firstname']) ? strip_tags(trim($_POST["firstname"])) : "";
        $lastname = isset($_POST['lastname']) ? strip_tags(trim($_POST["lastname"])) : "";
        $email = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
        $notelp = isset($_POST['notelp']) ? strip_tags(trim($_POST["notelp"])) : "";
        $bank = isset($_POST['bank']) ? strip_tags(trim($_POST["bank"])) : "";
        $account_number = isset($_POST['account_number']) ? strip_tags(trim($_POST["account_number"])) : "";
        $bio = isset($_POST['bio']) ? strip_tags(trim($_POST["bio"])) : "";

        // Query update users
        $update_user_query = "UPDATE `users` SET `email` = '$email' WHERE `id_member`='" . $loggedin["id_member"] . "';";

        // Error
        if (!mysql_query($update_user_query)) {
            echo "<script language='JavaScript'>
                alert('Unable to update user');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Update member query
        $update_member_query = "UPDATE `member` SET `firstname` = '$firstname', `lastname` = '$lastname', 
            `email` = '$email', `notelp` = '$notelp', `id_bank` = '$bank', `account_number` = '$account_number', `bio` = '$bio' 
            WHERE `id_member` = '" . $loggedin["id_member"] . "';";

        // Error
        if (!mysql_query($update_member_query)) {
            echo "<script language='JavaScript'>
                alert('Unable to update member);
                window.history.go(-1);
            </script>";
            exit();
        }

        // Success
        echo "<script language='JavaScript'>
            alert('Account has been update successfully!');
            location.href = 'profile.php';
        </script>";
        exit();

    }

    $change = isset($_POST["password"]) ? $_POST["password"] : '';

    if ($change == "password") {

        // Set query parameter
        $current_password = isset($_POST['current_password']) ? strip_tags(trim($_POST["current_password"])) : "";
        $new_password = isset($_POST['new_password']) ? strip_tags(trim($_POST["new_password"])) : "";
        $confirm_password = isset($_POST['confirm_password']) ? strip_tags(trim($_POST["confirm_password"])) : "";

        // Get users
        $user_query = mysql_query("SELECT `password` FROM `users` WHERE `id_member`='" . $loggedin["id_member"] . "'");
        $row_user = mysql_fetch_assoc($user_query);

        // Set md5 current password
        $md5_current_password = md5($current_password);

        if ($md5_current_password == $row_user['password']) {

            if ($new_password == $confirm_password) {

                // Set md5 new password
                $md5_new_password = md5($new_password);

                // Update password query
                $update_password_query = "UPDATE `users` SET `password` = '$md5_new_password' WHERE `id_member`='" . $loggedin["id_member"] . "'";

                // Error
                if (!mysql_query($update_password_query)) {
                    echo "<script language='JavaScript'>
                        alert('Unable to change password');
                        window.history.go(-1);
                    </script>";
                    exit();
                }

                // Success
                echo "<script language='JavaScript'>
                    alert('Change password successfully');
                    location.href = 'profile.php';
                </script>";
                exit();

            } else {
                echo "<script language='JavaScript'>
                    alert('Password and confirm password is not same.');
                    window.history.go(-1);
                </script>";
                exit();
            }

        } else {
            echo "<script language='JavaScript'>
                alert('Current password is wrong.');
                window.history.go(-1);
            </script>";
            exit();
        }

    }

    $addrs = isset($_POST["alamat"]) ? $_POST["alamat"] : '';

    if ($addrs == "alamat") {

        // Set parameter request
        $address = isset($_POST['address']) ? strip_tags(trim($_POST["address"])) : "";
        $province = isset($_POST['province']) ? strip_tags(trim($_POST["province"])) : "";
        $city = isset($_POST['city']) ? strip_tags(trim($_POST["city"])) : "";

        // Update address query
        $update_address_query = "UPDATE `member` SET `alamat` = '$address', `idpropinsi` = '$province', `idkota` = '$city' WHERE `id_member`='" . $loggedin["id_member"] . "';";

        // Error
        if (!mysql_query($update_address_query)) {
            echo "<script language='JavaScript'>
                alert('Unable to update address');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Success
        echo "<script language='JavaScript'>
            alert('Address has been updated successfully');
            location.href = 'profile.php';
        </script>";
        exit();

    }

    // Get member query
    $member_query = mysql_query("SELECT `member`.*, `users`.* FROM `member`, `users` WHERE `member`.`id_member` = '" . $loggedin["id_member"] . "' AND `users`.`id_member` = '" . $loggedin["id_member"] . "';");
    $row_member = mysql_fetch_array($member_query);

    // Get bank
    $member_bank_query = mysql_query("SELECT * FROM `bank_account` WHERE `id` = '" . $row_member["id_bank"] . "';");
    $row_member_bank = mysql_fetch_assoc($member_bank_query);

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
                                                    
                                                    <label>Bank</label>
                                                    <h5><b>' . ($row_member_bank['name'] ? $row_member_bank['name'] : "-") . '</b></h5>
                                                    
                                                    <label>Account Number</label>
                                                    <h5><b>' . ($row_member['account_number'] ? $row_member['account_number'] : "-") . '</b></h5>
                                                    
                                                    <label>Bio</label>
                                                    <h5><b>' . ($row_member['bio'] ? $row_member['bio'] : "-") . '</b></h5>
                                                    
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
                                                            <span><input type="text" class="form-control" name="username" value="' . $row_member['username'] . '" readonly></span>
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
                                                            <label>Bank</label>
                                                            <span>
                                                                <select id="bank" name="bank" class="form-control">
                                                                    <option hidden>-- Choose Bank --</option>';

    // Get bank
    $bank_query = mysql_query("SELECT * FROM `bank_account` WHERE `level` = '0';");
    while ($row_bank = mysql_fetch_assoc($bank_query)) {
        $content .= '<option value="' . $row_bank['id'] . '" ' . (($row_bank['id'] == $row_member['id_bank']) ? 'selected' : '') . '>' . $row_bank['name'] . '</option>';
    }

    $content .= '
                                                                </select>
                                                            </span>
                                                        </div>
                                                        <div class="form-group form-group-default ">
                                                            <label>Account Number</label>
                                                            <span><input type="text" class="form-control" name="account_number" value="' . $row_member['account_number'] . '"></span>
                                                        </div>
                                                        <div class="form-group form-group-default ">
                                                            <label>Bio</label>
                                                            <span><textarea class="form-control" name="bio" placeholder="-" style="height: 80px;">' . $row_member['bio'] . '</textarea></span>
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
                                                                <span><input type="password" class="form-control" name="current_password"></span>
                                                            </div>
                                                            <div class="form-group form-group-default ">
                                                                <label>New Password</label>
                                                                <span><input type="password" class="form-control" name="new_password"></span>
                                                            </div>
                                                            <div class="form-group form-group-default ">
                                                                <label>Confirm New Password</label>
                                                                <span><input type="password" class="form-control" name="confirm_password"></span>
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
                                                        <h5><b>' . $row_member['alamat'] . '</b></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="padding-30 sm-padding-5">
                                                    <p>Update Address</p>
                                                    <form role="form" method="post" action="">
                                                        <div class="form-group-attached">
                                                            <div class="form-group form-group-default ">
                                                                <label>Bank</label>
                                                                <span>
                                                                    <select id="province" name="province" class="form-control">
                                                                        <option hidden>-- Choose Province --</option>';

    // Get province
    $get_province = get_province();

    foreach ($get_province->rajaongkir->results as $province) {
        $content .= '<option value="' . $province->province_id . '" ' . (($province->province_id == $row_member['idpropinsi']) ? 'selected' : '') . '>' . $province->province . '</option>';
    }

    $content .= '
                                                                    </select>
                                                                </span>
                                                            </div>
                                                            <div class="form-group form-group-default ">
                                                                <label>Bank</label>
                                                                <span>
                                                                    <select id="city" name="city" class="form-control">
                                                                        <option hidden>-- Choose City --</option>';

    // Get province
    $get_city = get_city();

    foreach ($get_city->rajaongkir->results as $city) {
        $content .= '<option value="' . $city->city_id . '" ' . (($city->city_id == $row_member['idkota']) ? 'selected' : '') . '>' . $city->city_name . '</option>';
    }

    $content .= '
                                                                    </select>
                                                                </span>
                                                            </div>
                                                            <div class="form-group form-group-default ">
                                                                <label>Address</label>
                                                                <span><textarea class="form-control" name="address" placeholder="Ex: Jl Raya Kerobokan, 388x" style="height: 80px;">' . $row_member['alamat'] . '</textarea></span>
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

    $plugin = '
    <script>
        
        jQuery("#province").on("change", function () {
            var id_province = jQuery("#province").val();
            jQuery.ajax({
                type: "POST",
                url: "../getKota.php",
                data: {
                    id_province: id_province
                },
                success: function (html) {
                    console.log(html);
                    jQuery("#city").html(html);
                }
            });
        });

    </script>
    ';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('location:../login.php');
}

?>