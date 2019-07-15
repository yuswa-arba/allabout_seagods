<?php

include("config/configuration.php");
include("config/shipping/action_raja_ongkir.php");
include("config/shipping/province_city.php");

if ($loggedin = logged_in()) { // Check if they are logged in
    header('Location: index.php');
} else {

    $save = isset($_POST['signup']) ? $_POST["signup"] : "";

    if ($save == "Create a new account") {

        $username = isset($_POST['username']) ? mysql_real_escape_string(strip_tags(trim($_POST["username"]))) : '';
        $password = isset($_POST['password']) ? mysql_real_escape_string(strip_tags(trim($_POST["password"]))) : '';
        $confirm = isset($_POST['confirm']) ? mysql_real_escape_string(strip_tags(trim($_POST["confirm"]))) : '';
        $firstname = isset($_POST['firstname']) ? mysql_real_escape_string(strip_tags(trim($_POST["firstname"]))) : '';
        $lastname = isset($_POST['lastname']) ? mysql_real_escape_string(strip_tags(trim($_POST["lastname"]))) : '';
        $email = isset($_POST['email']) ? mysql_real_escape_string(strip_tags(trim($_POST["email"]))) : '';
        $province = isset($_POST['province']) ? mysql_real_escape_string(strip_tags(trim($_POST["province"]))) : '';
        $city = isset($_POST['city']) ? mysql_real_escape_string(strip_tags(trim($_POST["city"]))) : '';
        $address = isset($_POST['address']) ? mysql_real_escape_string(strip_tags(trim($_POST["address"]))) : '';
        $notelp = isset($_POST['notelp']) ? mysql_real_escape_string(strip_tags(trim($_POST["notelp"]))) : '';

        // Check validation
        if (empty($username) || empty($password) || empty($confirm) || empty($firstname) ||
            empty($lastname) || empty($email) || empty($province) || empty($city) || empty($address) || empty($notelp)
        ) {
            echo "<script language='JavaScript'>
                alert('Missing required all parameter');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Check username query
        $check_username_query = mysql_query("SELECT * FROM `users` WHERE `username` = '$username';");
        if (mysql_num_rows($check_username_query) > 0) {
            echo "<script language='JavaScript'>
                alert('Username not available.');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Check email
        $check_email_query = mysql_query("SELECT * FROM `users` WHERE `email` = '$email';");
        if (mysql_num_rows($check_email_query) > 0) {
            echo "<script language='JavaScript'>
                alert('Email already used.');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Check password and confirm password
        if ($password != $confirm) {
            echo "<script language='JavaScript'>
                alert('Password and confirm password is not same.');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Begin transaction
        begin_transaction();

        $data_id_member = mysql_fetch_assoc(mysql_query("select `id_member` FROM `member` ORDER BY `id_member` DESC LIMIT 0,1"));
        $id_member_new = $data_id_member['id_member'] + 1;

        // Insert to users
        $insert_user_query = "INSERT INTO `users` (`username`, `password`, `email`, `group`, `lastvisit`, `online`, `blokir`, `id_member`)
            VALUES('$username', '" . md5($password) . "', '$email', 'member', NOW(), '0', 'tidak', '$id_member_new')";
        if (!mysql_query($insert_user_query)) {
            roll_back();
            echo "<script language='JavaScript'>
                alert('Unable to create user');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Insert member
        $insert_member_query = "INSERT INTO `member` (`firstname`, `lastname`, `idCountry`, `idpropinsi`, `idkota`, `alamat`, `email`, `notelp`, `new_member`, `date_add`, `date_upd`, `level`)
            VALUES ('$firstname', '$lastname', 'ID', '$province', '$city', '$address', '$email', '$notelp', NOW(), NOW(), NOW(), '0')";
        if (!mysql_query($insert_member_query)) {
            roll_back();
            echo "<script language='JavaScript'>
                alert('Unable to create member');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Commit
        commit();

        // Success
        echo "<script language='JavaScript'>
            alert('Create member successfully');
            window.location.href = 'login.php';
        </script>";
        exit();

    }


}

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <title>Registration New Member</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"/>
    <link rel="apple-touch-icon" href="pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link href="member/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
    <link href="member/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="member/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="member/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="member/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="member/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="member/pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="member/pages/css/pages.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        window.onload = function () {
            // fix for windows 8
            if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
                document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="member/pages/css/windows.chrome.fix.css" />'
        }
    </script>
</head>
<body class="fixed-header "><br>
<div class="register-container full-height sm-p-t-30">
    <div class=" flex-column full-height ">
        <img src="member/assets/img/s-logo.png" alt="logo" data-src="member/assets/img/s-logo.png"
             data-src-retina="member/assets/img/s-logo.png" width="126" height="97">
        <h3>REGISTRATION NEW MEMBER</h3>
        <p>
            Create a SeaGods account. If you already have an account, please <a href="login.php" class="text-info">sign
                in</a>
        </p>
        <form id="signup" name="signup" method="post" action="register.php" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>Username*</label>
                        <input type="text" name="username" id="username"
                               placeholder="Ex: budi-dharma" class="form-control"
                               required>
                    </div>
                </div>
                <span id="sstt"></span>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>Password*</label>
                        <input type="password" name="password" id="password"
                               placeholder="******"
                               class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>Confirm Password*</label>
                        <input type="password" name="confirm"
                               placeholder="******"
                               class="form-control" required>
                    </div>
                </div>
            </div>

            <h4 id="h4.-bootstrap-heading"><B>Personal Information</B><span class="anchorjs-icon"></span></h4><br>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-group-default">
                        <label>First Name</label>
                        <input type="text" name="firstname" placeholder="Ex: Budi"
                               class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-default">
                        <label>Last Names</label>
                        <input type="text" name="lastname" placeholder="Ex: Dharma"
                               class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>Email*</label>
                        <input type="text" name="email" id="email"
                               placeholder="Ex: budi-dharma@example.com" class="form-control" required>
                    </div>
                </div>
                </span><span id="stts">
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>No Hp*</label>
                        <input type="text" name="notelp" placeholder="Ex: 082187645xxxx"
                               class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>Provinsi</label>
                        <select name="cmbProvinsi" id="province" class="full-width" data-init-plugin="select2">
                            <option value="" hidden>-- Choose Province --</option>
                            <?php

                            // Get province
                            $get_province = get_province();

                            foreach ($get_province->rajaongkir->results as $province) {
                                echo '<option value="' . $province->province_id . '">' . $province->province . '</option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>Provinsi</label>
                        <select name="cmbKota" id="state" class="full-width" data-init-plugin="select2">
                            <option value="" hidden>-- Choose City --</option>
                            <?php

                            // Get province
                            $get_city = get_city();

                            foreach ($get_city->rajaongkir->results as $city) {
                                echo '<option value="' . $city->city_id . '">' . $city->city_name . '</option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-default">
                        <label>Address*</label>
                        <textarea name="address" placeholder="Ex: Jl. Raya Kerbokan, 388x"
                                  class="form-control" style="height: 90px;" required></textarea>
                    </div>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-lg-6">
                    <p>
                        <small>
                            I agree to the <a href="#" class="text-info">Pages Terms</a> and
                            <a href="#" class="text-info">Privacy</a>.
                        </small>
                    </p>
                </div>
                <div class="col-lg-6 text-right">
                    <a href="#" class="text-info small">Help? Contact Support</a>
                </div>
            </div>

            <input class="btn btn-primary btn-cons m-t-10" type="submit" name="signup" value="Create a new account">
        </form>
    </div>
</div>
<div class=" full-width"></div>

<!-- BEGIN VENDOR JS -->
<script src="member/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/tether/js/tether.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
<script src="member/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script type="text/javascript" src="member/assets/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="member/assets/plugins/classie/classie.js"></script>
<script src="member/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
<script src="member/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<!-- END VENDOR JS -->
<script src="member/pages/js/pages.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        jQuery('#province').on('change', function () {
            var id_province = jQuery("#province").val();
            jQuery.ajax({
                type: 'POST',
                url: "getKota.php",
                data: {
                    id_province: id_province
                },
                success: function (html) {
                    console.log(html);
                    jQuery('#state').html(html);
                }
            });
        });

        jQuery('#username').blur(function () {
            jQuery('#pesan').html('<img style="margin-left:10px; width:10px" src="loading.gif">');
            var username = jQuery(this).val();

            jQuery.ajax({
                type: 'POST',
                url: 'proses_usr.php',
                data: 'username=' + username,
                success: function (data) {
                    jQuery('#pesan').html(data);
                }
            })

        });

        jQuery('#email').blur(function () {
            jQuery('#pesan_email').html('<img style="margin-left:10px; width:10px" src="loading.gif">');
            var email = jQuery(this).val();

            jQuery.ajax({
                type: 'POST',
                url: 'proses_email.php',
                data: 'email=' + email,
                success: function (data) {
                    jQuery('#pesan_email').html(data);
                }
            })
        });

        jQuery("#signup").validate({
            rules: {
                username: {required: true, minlength: 4, maxlength: 15},
                password: {required: true, minlength: 6},
                confirm: {required: true, minlength: 6, equalTo: "#password"},
                firstname: {required: true},
                email: {required: true, minlength: 6, email: true},
                notelp: {required: true, number: true}

            },
            messages: {
                username: {required: "This field is required."},
                password: {required: "This field is required.", minlength: "Min password 6 character"},
                confirm: {
                    required: "This field is required.",
                    minlength: "Min password 6 character",
                    equalTo: "Your passwords do not match."
                },
                firstname: {required: "This field is required."},
                email: {required: "This field is required."},
                notelp: {required: "This field is required."}

            }
        });

    });
</script>
<script>
    var jq = $.noConflict();
    /* trigger when page is ready */
    jq(document).ready(function () {
        jq(".pr-password").passwordRequirements();
    });
</script>
<style>
    .sd-uid {
        margin: 0 10px 7px 0;
    }

    .sd-rCont {
        float: right;
        height: 270px;
        width: 45%;
    }

    .sd-rcc {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        width: 350px;
    }

    .sd-rcc {
        text-align: center;
    }

    .btn-scnd, a.btn-scnd, a.btn-scnd:visited, .btn.btn-scnd.btn-d:hover, a.btn.btn-scnd.btn-d:hover, .btn.btn-scnd.btn-d:focus, a.btn.btn-scnd.btn-d:focus, .btn.btn-scnd.btn-d:active, a.btn.btn-scnd.btn-d:active {
        background: -moz-linear-gradient(center top, #45AAD6, #2386C0) repeat scroll 0 0 rgba(0, 0, 0, 0);
        color: #FFFFFF;
        text-decoration: none;
    }

    .btn {
        border: 1px solid rgba(0, 0, 0, 0);
        border-radius: 3px 3px 3px 3px;
        box-shadow: 0 3px 0 rgba(0, 0, 0, 0.04);
        cursor: pointer;
        display: inline-block;
        font-size: 16px;
        font-weight: 500;
        padding: 0.5em 1.2em;
        text-align: center;
        text-decoration: none;
        vertical-align: baseline;
        white-space: nowrap;
    }

    .sd-lCont {
        border-right: 1px solid #CCCCCC;
        box-shadow: 4px 0 1px #EEEEEE;
        padding: 25px;
        width: 50%;
    }

    .sd-txtA {
        color: #333333;
        display: inline-block;
        font-size: 20px;
        font-weight: bold;
        padding-bottom: 12px;
    }

    .sd-unl {
        color: #555555;
        display: block;
        font-size: 14px;
        padding: 5px 0 3px;
    }

    input.txtBxF {
        width: 343px;
    }

    input.txtBxF {
        border: 1px solid #CCCCCC;
        border-radius: 3px 3px 3px 3px;
        color: #333333;
        font-size: 16px;
        padding: 8px 0 8px 7px;
        width: 250px;
    }

    input.captcha {
        border: 1px solid #CCCCCC;
        border-radius: 3px 3px 3px 3px;
        color: #333333;
        font-size: 16px;
        padding: 8px 0 8px 7px;
        width: 100px;
    }

    input, button, select, textarea {
        font-family: inherit;
    }

    input, select {
        vertical-align: middle;
    }

    .sd-bc {
        padding: 0;
        width: 80%;
        margin: auto;
    }

    .sd-el {
        margin: 0 0 70px;
    }

    .sd-bc {
        background: none repeat scroll 0 0 #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 3px 3px 3px 3px;
        box-shadow: 4px 4px 1px #EEEEEE;
    }

    .sd-sgn {
        color: #999999;
        padding: 3px 0 0 30px;
    }

    .sd-km {
        width: 350px;
    }

    .sd-km {
        color: #666666;
        font-size: 12px;
        margin: 0 0 5px;
    }

    .btn-prim, a.btn-prim, a.btn-prim:visited, .btn-split, a.btn-split, a.btn-split:visited, .btn.btn-prim.btn-d:hover, a.btn.btn-prim.btn-d:hover, .btn.btn-prim.btn-d:focus, a.btn.btn-prim.btn-d:focus, .btn.btn-prim.btn-d:active, a.btn.btn-prim.btn-d:active {
        background: -moz-linear-gradient(center top, #0079BC, #00509D) repeat scroll 0 0 rgba(0, 0, 0, 0);
        color: #FFFFFF;
        text-decoration: none;
    }

    .btn {
        border: 1px solid rgba(0, 0, 0, 0);
        border-radius: 3px 3px 3px 3px;
        box-shadow: 0 3px 0 rgba(0, 0, 0, 0.04);
        cursor: pointer;
        display: inline-block;
        font-size: 16px;
        font-weight: 500;
        padding: 0.5em 1.2em;
        text-align: center;
        text-decoration: none;
        vertical-align: baseline;
        white-space: nowrap;
    }
</style>
</body>
</html>