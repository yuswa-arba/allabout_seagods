<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
session_start();
ob_start();

include_once("config/configuration.php");

if ($loggedin = logged_in()) { // Check if they are logged in
    header('Location: member/index.php');
} else { // If not logged in 

    //was there a reCAPTCHA response?
    if (isset($_POST['login'])) { // Check if submit button has been pressed.#fdf1f1

        /** CHECK COOKIES **/
        echo check_phpsessid();

        $_POST = protection($_POST); // Protect the $_POST variable.
        $_GET = protection($_GET); // Protect the $_GET variable.

        if (empty($_POST['username']) || empty($_POST['password'])) { // Check if the form fields are empty or not.

            echo '<script language="JavaScript">
			  alert("Login failed. Check your username and/or password.");
			  window.location.href ="home.php";			  
			</script>'; // If there empty show error message.

        } else {

            // Set value request
            $username = isset($_POST['username']) ? mysql_real_escape_string(strip_tags($_POST['username'])) : "";
            $redirect = isset($_GET['r']) ? mysql_real_escape_string(strip_tags($_GET['r'])) : "";

            // Check if the username and password are correct.
            $chkuser = mysql_query("SELECT * FROM `users` WHERE `username` = '" . $_POST['username'] . "' && `password` = '" . md5($_POST['password']) . "' AND `group`='member' LIMIT 0, 1");

            // Check if they are correct
            if (mysql_num_rows($chkuser)) {

                // Get the information
                $vcu = mysql_fetch_array($chkuser);

                $jam = date("H");
                $waktu = date("Y-m-d ") . ($jam - 1) . date(":i:s");

                // Insert the session id and user id into the sessions table to create the login.
                $results = mysql_query("INSERT into `sessions` (`sess_id`, `uid`, `logged`, `ip`, `waktu`) values ('" . $_COOKIE['PHPSESSID'] . "', '" . $vcu['id_user'] . "', '0', '" . $_SERVER['REMOTE_ADDR'] . "', '$waktu') ");

                $sql = mysql_query("UPDATE `users` SET `online` = '0', `lastvisit` = now()  WHERE `id_user` = '" . $vcu['id_user'] . "' ");

                if ($results) {

                    // If it submitted it then success.
                    if ($sql) {

                        // If $_GET['r'] is blank redirect the user to index.php after login if not redirect the user to the url indicated in login.php?r=http://www.google.com
                        if (empty($_GET['r'])) {

                            $_SESSION['user'] = $_POST['username'];
                            $_SESSION['id_member'] = $vcu['id_member'];
                            login_validate();

                            // Set id member
                            $id_member = $vcu['id_member'];

                            // Move item in session to database
                            if (!empty($_SESSION['cart_item'])) {

                                foreach ($_SESSION['cart_item'] as $key => $cart_item) {

                                    // Set size item
                                    $size_item = null;
                                    if (isset($cart_item['size'])) {
                                        $size_item = $cart_item['size'];
                                    }

                                    // Check if cart is custom
                                    if ($cart_item['is_custom_cart'] || $cart_item['is_custom_cart'] == true) {

                                        // Set cart collection value
                                        $cart_collection = $cart_item['collection'];

                                        // Insert custom collection
                                        mysql_query("INSERT INTO `custom_collection` (`code`, `id_member`, `is_guest`, `gender`, `wet_suit_type`, `arm_zipper`, `ankle_zipper`, `genital_zipper`, `image`, `price`, `status`, `date_add`, `date_upd`, `level`)
                                            VALUES('" . $cart_collection["code"] . "', '$id_member', '0', '" . $cart_collection["gender"] . "', '" . $cart_collection["wet_suit_type"] . "', '" . $cart_collection["arm_zipper"] . "', '" . $cart_collection["ankle_zipper"] . "', '" . $cart_collection["genital_zipper"] . "', '" . $cart_collection["image"] . "', '" . $cart_collection["price"] . "', '" . $cart_collection["status"] . "', NOW(), NOW(), '0');");

                                        // Get custom collection
                                        $custom_collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `code` = '" . $cart_collection["code"] . "' AND `id_member` = '$id_member' AND `level` = '0' ORDER BY `id_custom_collection` DESC LIMIT 0,1;");
                                        $row_custom_collection = mysql_fetch_array($custom_collection_query);

                                        // Set collection measure
                                        $collection_measure = $cart_item['measure'];

                                        // Insert custom measure
                                        mysql_query("INSERT INTO `custom_measure` (`id_custom_collection`, `id_member`, `total_body_height`, `head`, `neck`, `bust_chest`, `waist`, `stomach`, `abdomen`, `hip`, `shoulder`, `shoulder_elbow`, `shoulder_wrist`, `arm_hole`, `upper_arm`, 
                                            `bicep`, `elbow`, `forarm`, `wrist`, `outside_leg_length`, `inside_leg_length`, `upper_thigh`, `thigh`, `above_knee`, `knee`, `below_knee`, `calf`, `below_calf`, 
                                            `above_ankle`, `shoulder_burst`, `shoulder_waist`, `shoulder_hip`, `hip_knee_length`, `knee_ankle_length`, `back_shoulder`, `dorsum`, `crotch_point`)
                                            VALUES('" . $row_custom_collection["id_custom_collection"] . "', '$id_member', '" . $collection_measure["total_body_height"] . "', '" . $collection_measure["head"] . "', '" . $collection_measure["neck"] . "', '" . $collection_measure["bust_chest"] . "', '" . $collection_measure["waist"] . "', '" . $collection_measure["stomach"] . "', '" . $collection_measure["abdomen"] . "', '" . $collection_measure["hip"] . "', '" . $collection_measure["shoulder"] . "', '" . $collection_measure["shoulder_elbow"] . "', '" . $collection_measure["shoulder_wrist"] . "', '" . $collection_measure["arm_hole"] . "', '" . $collection_measure["upper_arm"] . "', 
                                            '" . $collection_measure["bicep"] . "', '" . $collection_measure["elbow"] . "', '" . $collection_measure["forarm"] . "', '" . $collection_measure["wrist"] . "', '" . $collection_measure["outside_leg_length"] . "', '" . $collection_measure["inside_leg_length"] . "', '" . $collection_measure["upper_thigh"] . "', '" . $collection_measure["thigh"] . "', '" . $collection_measure["above_knee"] . "', '" . $collection_measure["knee"] . "', '" . $collection_measure["below_knee"] . "', '" . $collection_measure["calf"] . "', '" . $collection_measure["below_calf"] . "', 
                                            '" . $collection_measure["above_ankle"] . "', '" . $collection_measure["shoulder_burst"] . "', '" . $collection_measure["shoulder_waist"] . "', '" . $collection_measure["shoulder_hip"] . "', '" . $collection_measure["hip_knee_length"] . "', '" . $collection_measure["knee_ankle_length"] . "', '" . $collection_measure["back_shoulder"] . "', '" . $collection_measure["dorsum"] . "', '" . $collection_measure["crotch_point"] . "');");

                                        // Insert cart
                                        mysql_query("INSERT INTO `cart` (`id_item`, `id_member`, `is_custom_cart`, `qty`, `amount`, `size`, `date_add`, `date_upd`, `level`)
                                            VALUES('" . $row_custom_collection["id_custom_collection"] . "', '$id_member', '1', '" . $cart_item["quantity"] . "', '" . $cart_item["amount"] . "', '$size_item', NOW(), NOW(), '0')");

                                        // Remove session
                                        unset($_SESSION['cart_item'][$key]);

                                    } else {

                                        // Check cart
                                        $cart_query = mysql_query("SELECT * FROM `cart` 
                                            WHERE ISNULL(id_transaction) AND `id_item` = '" . $cart_item["id_item"] . "' AND `id_member` = '$id_member' AND `level` = '0' LIMIT 0,1;");
                                        if (mysql_num_rows($cart_query) == 0) {

                                            // Add cart
                                            mysql_query("INSERT INTO `cart` (`id_item`, `qty`, `amount`, `size`, `id_member`, `date_add`, `date_upd`, `level`)
                                                VALUES('" . $cart_item["id_item"] . "', '" . $cart_item["quantity"] . "', '" . $cart_item["amount"] . "', '$size_item', '$id_member', NOW(), NOW(), '0');");

                                            // Remove session
                                            unset($_SESSION['cart_item'][$key]);

                                        } else {

                                            // Set row cart
                                            $row_cart = mysql_fetch_array($cart_query);

                                            // Set last amount cart and quantity
                                            $last_amount_cart = (float)$cart_item['amount'] + (float)$row_cart['amount'];
                                            $last_quantity = (float)$cart_item['quantity'] + (float)$row_cart['qty'];

                                            // Update cart
                                            mysql_query("UPDATE `cart` SET `qty` = '$last_quantity', `amount` = '$last_amount_cart', `size` = '$size_item', `date_upd` = NOW()
                                                WHERE `id_cart` = '" . $row_cart["id_cart"] . "';");

                                            // Remove session
                                            unset($_SESSION['cart_item'][$key]);

                                        }

                                    }

                                }

                            }

                            // Unset guest
                            if (isset($_SESSION['guest'])) {
                                unset($_SESSION['guest']);
                            }

                            if (isset($_GET['action'])) {
                                if ($_GET['action'] == 'checkout') {
                                    header("Location: checkout.php");
                                } else {
                                    header("Location: member/index.php");
                                }
                            } else {
                                header("Location: member/index.php");
                            }
                        } else {
                            header("Location: " . $_GET['r']);
                        }

                    } else { // If couldnt submit into sessions table then show error message

                        echo '<script language="JavaScript">
						    alert("Unknown Error sql.");
						    window.location.href ="login.php";
						</script>';

                    }
                } else { // If couldnt submit into sessions table then show error message

                    echo '<script language="JavaScript">
					    alert("Unknown Error result.");
					    window.location.href ="login.php";
				    </script>';

                }

            } else {

                echo '<script language="JavaScript">
			        alert("Incorrect Username Or Password.");
			        window.location.href ="login.php";
			    </script>';

            }
        }

    }

    function move_cart_session($id_member)
    {

    }

}


?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <title>Member Login - SeaGods Wetsuit</title>
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


<body class="fixed-header ">
<div class="login-wrapper ">
    <!-- START Login Background Pic Wrapper-->
    <div class="bg-pic">
        <!-- START Background Pic-->
        <img src="member/assets/img/demo/undersea.jpg" data-src="member/assets/img/demo/undersea.jpg"
             data-src-retina="member/assets/img/demo/undersea.jpg" alt="" class="lazy">
        <!-- END Background Pic-->
        <!-- START Background Caption-->
        <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
            <h2 class="semi-bold text-white">
                SEAGODS WETSUIT</h2>
            <p class="small">

                By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia © 2018.
            </p>
        </div>
        <!-- END Background Caption-->
    </div>
    <!-- END Login Background Pic Wrapper-->
    <!-- START Login Right Container-->
    <div class="login-container bg-white">
        <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
            <img src="member/assets/img/s-logo.png" alt="logo" data-src="member/assets/img/s-logo.png"
                 data-src-retina="member/assets/img/s-logo.png" height="22px"> <b>SEAGODS WETSUIT</b>
            <p class="p-t-35">Sign into your pages account</p>
            <!-- START Login Form -->
            <form id="form-login" class="p-t-15" role="form" method="post" action="<?php echo (isset($_GET['action']) ? '?action=' . $_GET['action'] : '');?>">
                <!-- START Form Control-->
                <div class="form-group form-group-default">
                    <label>Login</label>
                    <div class="controls">
                        <input type="text" name="username" placeholder="User Name" class="form-control" required>
                    </div>
                </div>
                <!-- END Form Control-->
                <!-- START Form Control-->
                <div class="form-group form-group-default">
                    <label>Password</label>
                    <div class="controls">
                        <input type="password" class="form-control" name="password" placeholder="Credentials" required>
                    </div>
                </div>
                <!-- START Form Control-->
                <div class="row">
                    <div class="col-md-6 no-padding sm-p-l-10">
                        <!-- <div class="checkbox ">
                          <input type="checkbox" value="1" id="checkbox1">
                          <label for="checkbox1">Keep Me Signed in</label>
                        </div>-->
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                        <a href="register.php" class="text-info small">Register New Member</a>
                    </div>
                </div>
                <!-- END Form Control-->
                <button class="btn btn-primary btn-cons m-t-10" type="submit" name="login" value="Login">Sign in
                </button>
            </form>
            <!--END Login Form-->
            <div class="pull-bottom sm-pull-bottom">
                <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                    <div class="col-sm-3 col-md-2 no-padding">
                        <!--<img alt="" class="m-t-5" data-src="assets/img/demo/pages_icon.png" data-src-retina="assets/img/demo/pages_icon_2x.png" height="60" src="assets/img/demo/pages_icon.png" width="60">
                      --></div>
                    <div class="col-sm-9 no-padding m-t-10">
                        <!--<p>
                          <small>
                                            Create a pages account. If you have a facebook account, log into it for this
                                            process. Sign in with <a href="#" class="text-info">Facebook</a> or <a href="#"
                                                                                                                   class="text-info">Google</a>
                                        </small>
                        </p>
                      --></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Login Right Container-->
</div>
<!-- START OVERLAY -->
<div class="overlay hide" data-pages="search">
    <!-- BEGIN Overlay Content !-->
    <div class="overlay-content has-results m-t-20">
        <!-- BEGIN Overlay Header !-->
        <div class="container-fluid">
            <!-- BEGIN Overlay Logo !-->
            <img class="overlay-brand" src="member/assets/img/logo.png" alt="logo" data-src="member/assets/img/logo.png"
                 data-src-retina="member/assets/img/logo_2x.png" width="78" height="22">
            <!-- END Overlay Logo !-->
            <!-- BEGIN Overlay Close !-->
            <a href="#" class="close-icon-light overlay-close text-black fs-16">
                <i class="pg-close"></i>
            </a>
            <!-- END Overlay Close !-->
        </div>
        <!-- END Overlay Header !-->
        <div class="container-fluid">
            <!-- BEGIN Overlay Controls !-->
            <input id="overlay-search" class="no-border overlay-search bg-transparent" placeholder="Search..."
                   autocomplete="off" spellcheck="false">
            <br>
            <div class="inline-block">
                <div class="checkbox right">
                    <input id="checkboxn" type="checkbox" value="1" checked="checked">
                    <label for="checkboxn"><i class="fa fa-search"></i> Search within page</label>
                </div>
            </div>
            <div class="inline-block m-l-10">
                <p class="fs-13">Press enter to search</p>
            </div>
            <!-- END Overlay Controls !-->
        </div>
        <!-- BEGIN Overlay Search Results, This part is for demo purpose, you can add anything you like !-->
        <div class="container-fluid">
          <span>
                <strong>suggestions :</strong>
            </span>
            <span id="overlay-suggestions"></span>
            <br>
            <div class="search-results m-t-40">
                <p class="bold">Pages Search Results</p>
                <div class="row">
                    <div class="col-md-6">
                        <!-- BEGIN Search Result Item !-->
                        <div class="">
                            <!-- BEGIN Search Result Item Thumbnail !-->
                            <div class="thumbnail-wrapper d48 circular bg-success text-white inline m-t-10">
                                <div>
                                    <img width="50" height="50" src="member/assets/img/profiles/avatar.jpg"
                                         data-src="member/assets/img/profiles/avatar.jpg"
                                         data-src-retina="member/assets/img/profiles/avatar2x.jpg" alt="">
                                </div>
                            </div>
                            <!-- END Search Result Item Thumbnail !-->
                            <div class="p-l-10 inline p-t-5">
                                <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> on pages</h5>
                                <p class="hint-text">via john smith</p>
                            </div>
                        </div>
                        <!-- END Search Result Item !-->
                        <!-- BEGIN Search Result Item !-->
                        <div class="">
                            <!-- BEGIN Search Result Item Thumbnail !-->
                            <div class="thumbnail-wrapper d48 circular bg-success text-white inline m-t-10">
                                <div>T</div>
                            </div>
                            <!-- END Search Result Item Thumbnail !-->
                            <div class="p-l-10 inline p-t-5">
                                <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> related topics
                                </h5>
                                <p class="hint-text">via pages</p>
                            </div>
                        </div>
                        <!-- END Search Result Item !-->
                        <!-- BEGIN Search Result Item !-->
                        <div class="">
                            <!-- BEGIN Search Result Item Thumbnail !-->
                            <div class="thumbnail-wrapper d48 circular bg-success text-white inline m-t-10">
                                <div><i class="fa fa-headphones large-text "></i>
                                </div>
                            </div>
                            <!-- END Search Result Item Thumbnail !-->
                            <div class="p-l-10 inline p-t-5">
                                <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> music</h5>
                                <p class="hint-text">via pagesmix</p>
                            </div>
                        </div>
                        <!-- END Search Result Item !-->
                    </div>
                    <div class="col-md-6">
                        <!-- BEGIN Search Result Item !-->
                        <div class="">
                            <!-- BEGIN Search Result Item Thumbnail !-->
                            <div class="thumbnail-wrapper d48 circular bg-info text-white inline m-t-10">
                                <div><i class="fa fa-facebook large-text "></i>
                                </div>
                            </div>
                            <!-- END Search Result Item Thumbnail !-->
                            <div class="p-l-10 inline p-t-5">
                                <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> on facebook</h5>
                                <p class="hint-text">via facebook</p>
                            </div>
                        </div>
                        <!-- END Search Result Item !-->
                        <!-- BEGIN Search Result Item !-->
                        <div class="">
                            <!-- BEGIN Search Result Item Thumbnail !-->
                            <div class="thumbnail-wrapper d48 circular bg-complete text-white inline m-t-10">
                                <div><i class="fa fa-twitter large-text "></i>
                                </div>
                            </div>
                            <!-- END Search Result Item Thumbnail !-->
                            <div class="p-l-10 inline p-t-5">
                                <h5 class="m-b-5">Tweats on<span class="semi-bold result-name"> ice cream</span></h5>
                                <p class="hint-text">via twitter</p>
                            </div>
                        </div>
                        <!-- END Search Result Item !-->
                        <!-- BEGIN Search Result Item !-->
                        <div class="">
                            <!-- BEGIN Search Result Item Thumbnail !-->
                            <div class="thumbnail-wrapper d48 circular text-white bg-danger inline m-t-10">
                                <div><i class="fa fa-google-plus large-text "></i>
                                </div>
                            </div>
                            <!-- END Search Result Item Thumbnail !-->
                            <div class="p-l-10 inline p-t-5">
                                <h5 class="m-b-5">Circles on<span class="semi-bold result-name"> ice cream</span></h5>
                                <p class="hint-text">via google plus</p>
                            </div>
                        </div>
                        <!-- END Search Result Item !-->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Overlay Search Results !-->
    </div>
    <!-- END Overlay Content !-->
</div>
<!-- END OVERLAY -->
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
<script>
    $(function () {
        $('#form-login').validate()
    })
</script>
</body>
</html>