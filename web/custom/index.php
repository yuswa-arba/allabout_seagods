<?php
include("config/configuration.php");

session_start();
ob_start();

$id_custom_item = '';

if ($loggedin = logged_in()) {
    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $user = '' . $_SESSION['user'] . '';

}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="public/images/logo.png">

    <title>SeaGods Wetsuit</title>
    <link href="http://fonts.googleapis.com/css?family=Poppins:300,700" rel="stylesheet" type="text/css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Style Sheet-->
    <link rel="stylesheet" type="text/css" href="public/css/font-awesome-4.6.3/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="public/css/jquery-ui.css" media="all">
    <link rel="stylesheet"
          href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" media="all">
    <link rel="stylesheet" href="public/js/owl-carousel/owl.carousel.css" media="all">
    <link rel="stylesheet" href="public/js/owl-carousel/owl.theme.css" media="all">
    <link rel="stylesheet" href="public/css/style.css" media="all">
    <link rel="stylesheet" href="public/css/mystyle.css" media="all">
    <link rel="stylesheet" href="public/css/sakarioka.css" media="all">

    <!-- Scripts -->
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="../js/html2canvas.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fileDownload/1.4.2/jquery.fileDownload.min.js"></script>-->
    <script src="https://fastcdn.org/FileSaver.js/1.1.20151003/FileSaver.min.js"></script>
    <script src="public/js/less.min.js"></script>
    <script src="public/js/owl-carousel/owl.carousel.min.js"></script>
    <script src="public/js/sns-extend.js"></script>
    <script src="public/js/custom.js?v=20180120"></script>
    <script src="public/js/list-grid.js"></script>
    <script src="https://www.marvinj.org/releases/marvinj-0.8.js"></script>
    <style>
        .sns_header_top {
            height: 25px !important;
        }

        .sns_header_top img {
            height: 25px;
            padding: 5px 0;
        }
    </style>


    <link rel="shortcut icon" href="../images/favicon.ico">

    <!-- FONTS -->
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Patua+One:100,300,400,400italic,700'>
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic,900'>
    <link rel='stylesheet'
          href='http://fonts.googleapis.com/css?family=Montserrat:100,300,400,400italic,500,700,700italic'>
    <link rel='stylesheet'
          href='http://fonts.googleapis.com/css?family=Asap+Condensed:100,300,400,400italic,500,700,700italic'>
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:100,300,400,400italic,500,700,700italic'>

    <!-- CSS -->
    <link rel='stylesheet' href='css-menu-custom/global.css'>
    <link rel='stylesheet' href='css-menu-custom/structure.css'>
    <link rel='stylesheet' href='css-menu-custom/denim.css'>

    <script type="text/javascript">

        //****************************************************************
        // Example function save save canvas content into image file.
        // www.permadi.com
        //****************************************************************
        function saveViaAJAX() {
            // Filter Canvas
            var selectValue = $('[name="gender"]').val();
            if (selectValue == 'male') {
                var canvas = document.getElementById("canvas");
            } else if (selectValue == 'female') {
                var canvas = document.getElementById("canvas2");
            } else if (selectValue == 'unisex') {
                var canvas = document.getElementById("canvas3");
            } else {
                alert('Select Gender value is null!');
            }

            // Filter canvas which like name id canvas
            if (selectValue == 'male' || selectValue == 'female' || selectValue == 'unisex') {

                var canvasData = canvas.toDataURL("image/png", 0.2);

                $.ajax({
                    type: 'POST',
                    url: 'save.php',
                    data: {
                        canvasData: canvasData,
                        gender: $('[name="gender"]').val(),
                        wetsuitType: $('[name="wetsuitType"]').val(),
                        ankleZipper: $('[name="ankleZipper"]').val(),
                        armZipper: $('[name="armZipper"]').val(),
                        measureTotalBodyHeight: $('[name="measureTotalBodyHeight"]').val(),
                        measureHead: $('[name="measureHead"]').val(),
                        measureNeck: $('[name="measureNeck"]').val(),
                        measureBustChest: $('[name="measureBustChest"]').val(),
                        measureWaist: $('[name="measureWaist"]').val(),
                        measureStomach: $('[name="measureStomach"]').val(),
                        measureAbdomen: $('[name="measureAbdomen"]').val(),
                        measureHip: $('[name="measureHip"]').val(),
                        measureShoulder: $('[name="measureShoulder"]').val(),
                        measureShoulderToElbow: $('[name="measureShoulderToElbow"]').val(),
                        measureShoulderToWrist: $('[name="measureShoulderToWrist"]').val(),
                        measureArmHole: $('[name="measureArmHole"]').val(),
                        measureUpperArm: $('[name="measureUpperArm"]').val(),
                        measureBicep: $('[name="measureBicep"]').val(),
                        measureElbow: $('[name="measureElbow"]').val(),
                        measureForarm: $('[name="measureForarm"]').val(),
                        measureWrist: $('[name="measureWrist"]').val(),
                        measureOutsideLegLength: $('[name="measureOutsideLegLength"]').val(),
                        measureInsideLegLength: $('[name="measureInsideLegLength"]').val(),
                        measureUpperThigh: $('[name="measureUpperThigh"]').val(),
                        measureThigh: $('[name="measureThigh"]').val(),
                        measureAboveKnee: $('[name="measureAboveKnee"]').val(),
                        measureKnee: $('[name="measureKnee"]').val(),
                        measureBelowKnee: $('[name="measureBelowKnee"]').val(),
                        measureCalf: $('[name="measureCalf"]').val(),
                        measureBelowCalf: $('[name="measureBelowCalf"]').val(),
                        measureAboveAnkle: $('[name="measureAboveAnkle"]').val(),
                        measureShoulderToBust: $('[name="measureShoulderToBust"]').val(),
                        measureShoulderToWaist: $('[name="measureShoulderToWaist"]').val(),
                        measureShoulderToHip: $('[name="measureShoulderToHip"]').val(),
                        measureHipToKneeLength: $('[name="measureHipToKneeLength"]').val(),
                        measureKneeToAnkleLength: $('[name="measureKneeToAnkleLength"]').val(),
                        measureBackShoulder: $('[name="measureBackShoulder"]').val(),
                        measureDorsum: $('[name="measureDorsum"]').val(),
                        measureCrotchPoint: $('[name="measureCrotchPoint"]').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 'error') {
                            alert(data.msg);
                        } else {
                            alert(data.msg);
                            window.location.href = '../member/custommade.php';
                        }
                    }
                });
            }

        }

        function addGuestCart() {
            // Filter Canvas
            var selectValue = $('[name="gender"]').val();
            if (selectValue == 'male') {
                var canvas = document.getElementById("canvas");
            } else if (selectValue == 'female') {
                var canvas = document.getElementById("canvas2");
            } else if (selectValue == 'unisex') {
                var canvas = document.getElementById("canvas3");
            } else {
                alert('Select Gender value is null!');
            }

            // Filter canvas which like name id canvas
            if (selectValue == 'male' || selectValue == 'female' || selectValue == 'unisex') {

                var canvasData = canvas.toDataURL("image/png", 0.2);

                $.ajax({
                    type: 'POST',
                    url: 'add_guest_cart.php',
                    data: {
                        canvasData: canvasData,
                        gender: $('[name="gender"]').val(),
                        wetsuitType: $('[name="wetsuitType"]').val(),
                        ankleZipper: $('[name="ankleZipper"]').val(),
                        armZipper: $('[name="armZipper"]').val(),
                        measureTotalBodyHeight: $('[name="measureTotalBodyHeight"]').val(),
                        measureHead: $('[name="measureHead"]').val(),
                        measureNeck: $('[name="measureNeck"]').val(),
                        measureBustChest: $('[name="measureBustChest"]').val(),
                        measureWaist: $('[name="measureWaist"]').val(),
                        measureStomach: $('[name="measureStomach"]').val(),
                        measureAbdomen: $('[name="measureAbdomen"]').val(),
                        measureHip: $('[name="measureHip"]').val(),
                        measureShoulder: $('[name="measureShoulder"]').val(),
                        measureShoulderToElbow: $('[name="measureShoulderToElbow"]').val(),
                        measureShoulderToWrist: $('[name="measureShoulderToWrist"]').val(),
                        measureArmHole: $('[name="measureArmHole"]').val(),
                        measureUpperArm: $('[name="measureUpperArm"]').val(),
                        measureBicep: $('[name="measureBicep"]').val(),
                        measureElbow: $('[name="measureElbow"]').val(),
                        measureForarm: $('[name="measureForarm"]').val(),
                        measureWrist: $('[name="measureWrist"]').val(),
                        measureOutsideLegLength: $('[name="measureOutsideLegLength"]').val(),
                        measureInsideLegLength: $('[name="measureInsideLegLength"]').val(),
                        measureUpperThigh: $('[name="measureUpperThigh"]').val(),
                        measureThigh: $('[name="measureThigh"]').val(),
                        measureAboveKnee: $('[name="measureAboveKnee"]').val(),
                        measureKnee: $('[name="measureKnee"]').val(),
                        measureBelowKnee: $('[name="measureBelowKnee"]').val(),
                        measureCalf: $('[name="measureCalf"]').val(),
                        measureBelowCalf: $('[name="measureBelowCalf"]').val(),
                        measureAboveAnkle: $('[name="measureAboveAnkle"]').val(),
                        measureShoulderToBust: $('[name="measureShoulderToBust"]').val(),
                        measureShoulderToWaist: $('[name="measureShoulderToWaist"]').val(),
                        measureShoulderToHip: $('[name="measureShoulderToHip"]').val(),
                        measureHipToKneeLength: $('[name="measureHipToKneeLength"]').val(),
                        measureKneeToAnkleLength: $('[name="measureKneeToAnkleLength"]').val(),
                        measureBackShoulder: $('[name="measureBackShoulder"]').val(),
                        measureDorsum: $('[name="measureDorsum"]').val(),
                        measureCrotchPoint: $('[name="measureCrotchPoint"]').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                    }
                });
            }

        }

    </script>
</head>
<body id="bd" class="cms-index-index4 header-style4 prd-detail cms-simen-home-page-v2 default cmspage">
<div id="sns_wrapper">
    <!-- HEADER -->
    <header id="Header" style="margin-top:0;">

        <div id="Top_bar" style="margin-top:0;">
            <div class="container" style="margin-top:0;">

                <div class="top_bar_left clearfix">
                    <div class="logo">
                        <a id="logo" href="home"
                           title="Sea Gods" data-height="60" data-padding="0">
                            <img class="logo-main scale-with-grid" src="../images/logo.png"
                                 data-retina="../images/logo.png" data-height="21" alt="sea gods">
                            <img class="logo-sticky scale-with-grid" src="../images/logo.png"
                                 data-retina="../images/logo.png" data-height="21" alt="sea gods">
                            <img class="logo-mobile scale-with-grid" src="../images/logo.png"
                                 data-retina="../images/logo.png" data-height="21" alt="sea gods">
                            <img class="logo-mobile-sticky scale-with-grid" src="../images/logo.png"
                                 data-retina="../images/logo.png" data-height="21" alt="sea gods">
                        </a>
                    </div>
                    <div class="menu_wrapper">
                        <nav id="menu">
                            <ul id="menu-main-menu" class="menu menu-main">
                                <li><a href="index.php"><span>Home</span></a></li>
                                <li><a href="../about_us.php"><span>About Us</span></a></li>
                                <li><a href="../buyers_guide.php"><span>Guide</span></a></li>
                                <li><a href="../list_product.php?id_cats=9"><span>Collection</span></a></li>
                                <li class="current-menu-item"><a href=""><span>Custom</span></a></li>
                                <li><a href="../blog.php"><span>Blog</span></a></li>
                                <li><a href="../member/index.php" target="_blank"><span>Memberpage</span></a></li>
                            </ul>
                        </nav>

                    </div>
                </div>


            </div>
        </div>

    </header>
    <!-- AND HEADER -->
</div>
<link rel="stylesheet" href="public/custom/css/custom_style.css" media="all">
<link rel="stylesheet" href="public/custom/css/print.css" media="print">

<style>
    input[type="radio"] {
        position: initial !important;
    }

    select.form-control {
        padding: 0px 6px;
    }

    .radio {
        margin-top: 0;
    }

    #custom-change {
        background-color: #fff;
    }

    .wetsuit-box {
        position: relative;

        border-radius: 10%;
        height: 450px;
        background-color: #fff;
    }

    .wetsuit-box > img {
        width: 100%;
        position: absolute;
        top: 0px;
        left: 0px;
        padding: 25px;
    }

    .custom-box {
        margin: 20px 0;
    }
</style>

<form action="index.php" method="post">
    <input type="hidden" name="kode_custom_cart" value="">
    <main style="margin-bottom: 200px; min-height: 330px;">
        <div class="container">
            <div id="select-gender">
                <div class="row" style="margin-top: 50px;">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <h2 class="text-center" style="margin-bottom: 20px;">Select Gender</h2>
                    </div>
                    <div class="col-xs-4 col-md-2 col-md-offset-3">
                        <a href="javascript:hide_gender('male');">
                            <img src="public/custom/images/male.png" alt=""
                                 style="width: 100%; padding: 20px; border: 1px solid rgb(51, 51, 51); border-radius: 10%;">
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-2">
                        <a href="javascript:hide_gender('unisex');">
                            <img src="public/custom/images/unisex.png" alt=""
                                 style="width: 100%; padding: 20px; border: 1px solid rgb(51, 51, 51); border-radius: 10%;">
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-2">
                        <a href="javascript:hide_gender('female');">
                            <img src="public/custom/images/female.png" alt=""
                                 style="width: 100%; padding: 20px; border: 1px solid rgb(51, 51, 51); border-radius: 10%;">
                        </a>
                    </div>
                </div>
            </div>
            <div id="custom" style="display: none;">
                <div class="row">
                    <div class="col-sm-2 custom-box">
                        <div class="form-group">
                            <label>Selected Gender</label>
                            <select name="gender" class="form-control" onchange="change_gender(this.value);">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="unisex">Unisex</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Wet Suit Type <i class="fa fa-fw fa-clear fa-question-circle-o"
                                                    title="choose to use front or back zipper for the wet suit"
                                                    data-toggle="tooltip"></i></label>
                            <select name="wetsuitType" class="form-control" onchange="change_suit_type(this.value);">
                                <option value="back">Back Zipper</option>
                                <option value="front" selected>Front Zipper</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>1<sup>st</sup> Layer</label>
                            <select name="shoulderLayer" id="1-layer-thickness" class="form-control">
                                <option value="">Select Thickness</option>
                                <option value="2.0 MM">2.0 MM</option>
                                <option value="5.0 MM">5.0 MM</option>
                                <option value="3.0 MM">3.0 MM</option>
                            </select>
                            <div>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'black', this);"
                                   title="Black">
                                    <div class="color" active style="background-color: #1f1f24;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'blue', this);"
                                   title="Blue">
                                    <div class="color " style="background-color: #345aa0;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'green', this);"
                                   title="Green">
                                    <div class="color " style="background-color: #538f75;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'light_blue', this);"
                                   title="Light_Blue">
                                    <div class="color " style="background-color: #4475a8;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'orange', this);"
                                   title="Orange">
                                    <div class="color " style="background-color: #be5a2c;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'pink', this);"
                                   title="Pink">
                                    <div class="color " style="background-color: #c15c68;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'purple', this);"
                                   title="Purple">
                                    <div class="color " style="background-color: #563b9e;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'red', this);" title="Red">
                                    <div class="color " style="background-color: #aa0828;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'yellow', this);"
                                   title="Yellow">
                                    <div class="color " style="background-color: #ffd93e;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'lime', this);"
                                   title="Lime">
                                    <div class="color " style="background-color: #29d136;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'gold', this);"
                                   title="Gold">
                                    <div class="color " style="background-color: #ce9909;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'navy', this);"
                                   title="Navy">
                                    <div class="color " style="background-color: #2b335a;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'tangerine_1', this);"
                                   title="Tangerine_1">
                                    <div class="color " style="background-color: #ff7143;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'white', this);"
                                   title="White">
                                    <div class="color " style="background-color: #ffffff;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var0', 'tangerine_2', this);"
                                   title="Tangerine_2">
                                    <div class="color " style="background-color: #ee6545;"></div>
                                </a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>2<sup>nd</sup> Layer</label>
                            <select name="shoulderLayer" id="1-layer-thickness" class="form-control">
                                <option value="">Select Thickness</option>
                                <option value="2.0 MM">2.0 MM</option>
                                <option value="5.0 MM">5.0 MM</option>
                                <option value="3.0 MM">3.0 MM</option>
                            </select>
                            <div>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'black', this);"
                                   title="Black">
                                    <div class="color" active style="background-color: #1f1f24;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'blue', this);"
                                   title="Blue">
                                    <div class="color " style="background-color: #345aa0;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'green', this);"
                                   title="Green">
                                    <div class="color " style="background-color: #538f75;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'light_blue', this);"
                                   title="Light_Blue">
                                    <div class="color " style="background-color: #4475a8;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'orange', this);"
                                   title="Orange">
                                    <div class="color " style="background-color: #be5a2c;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'pink', this);"
                                   title="Pink">
                                    <div class="color " style="background-color: #c15c68;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'purple', this);"
                                   title="Purple">
                                    <div class="color " style="background-color: #563b9e;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'red', this);" title="Red">
                                    <div class="color " style="background-color: #aa0828;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'yellow', this);"
                                   title="Yellow">
                                    <div class="color " style="background-color: #ffd93e;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'lime', this);"
                                   title="Lime">
                                    <div class="color " style="background-color: #29d136;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'gold', this);"
                                   title="Gold">
                                    <div class="color " style="background-color: #ce9909;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'navy', this);"
                                   title="Navy">
                                    <div class="color " style="background-color: #2b335a;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'tangerine_1', this);"
                                   title="Tangerine_1">
                                    <div class="color " style="background-color: #ff7143;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'white', this);"
                                   title="White">
                                    <div class="color " style="background-color: #ffffff;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var1', 'tangerine_2', this);"
                                   title="Tangerine_2">
                                    <div class="color " style="background-color: #ee6545;"></div>
                                </a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>3<sup>rd</sup> Layer</label>
                            <select name="shoulderLayer" id="2-layer-thickness" class="form-control">
                                <option value="">Select Thickness</option>
                                <option value="2.0 MM">2.0 MM</option>
                                <option value="3.0 MM" selected>3.0 MM</option>
                                <option value="5.0 MM">5.0 MM</option>
                            </select>
                            <div>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'black', this);"
                                   title="Black">
                                    <div class="color active" style="background-color: #1f1f24;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'blue', this);"
                                   title="Blue">
                                    <div class="color " style="background-color: #345aa0;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'green', this);"
                                   title="Green">
                                    <div class="color " style="background-color: #538f75;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'light_blue', this);"
                                   title="Light_Blue">
                                    <div class="color " style="background-color: #4475a8;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'orange', this);"
                                   title="Orange">
                                    <div class="color " style="background-color: #be5a2c;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'pink', this);"
                                   title="Pink">
                                    <div class="color " style="background-color: #c15c68;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'purple', this);"
                                   title="Purple">
                                    <div class="color " style="background-color: #563b9e;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'red', this);" title="Red">
                                    <div class="color " style="background-color: #aa0828;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'yellow', this);"
                                   title="Yellow">
                                    <div class="color " style="background-color: #ffd93e;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'lime', this);"
                                   title="Lime">
                                    <div class="color " style="background-color: #29d136;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'gold', this);"
                                   title="Gold">
                                    <div class="color " style="background-color: #ce9909;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'navy', this);"
                                   title="Navy">
                                    <div class="color " style="background-color: #2b335a;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'tangerine_1', this);"
                                   title="Tangerine_1">
                                    <div class="color " style="background-color: #ff7143;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'white', this);"
                                   title="White">
                                    <div class="color " style="background-color: #ffffff;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('var2', 'tangerine_2', this);"
                                   title="Tangerine_2">
                                    <div class="color " style="background-color: #ee6545;"></div>
                                </a>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-8 col-sm-2 custom-box">
                        <div id="custom-change">

                            <div id="custom-male" class="row custom-gender" style="display: none;">
                                <div class="col-sm-12">
                                    <div class="wetsuit-box">
                                        <img src="public/custom/wetsuit/male-new/default.png" alt="">
                                        <img class="var0-var0" id="var0"
                                             src="public/custom/wetsuit/male-new/var0/var0/black.png" alt="">
                                        <img class="var1-var1" id="var1"
                                             src="public/custom/wetsuit/male-new/var1/var1/black.png" alt="">
                                        <img class="var2-var2" id="var2"
                                             src="public/custom/wetsuit/male-new/var2/var2/black.png" alt="">
                                        <img class="var1-thread" id="thread"
                                             src="public/custom/wetsuit/male-new/var1/thread/white.png" alt="">
                                        <img class="var1-hand_pad" id="hand_pad" src="" alt="">
                                        <img class="var1-knee_pad" id="knee_pad" src="" alt="">
                                        <img class="side-front_zip" id="side-front_zip"
                                             src="public/custom/wetsuit/male-new/back_zip.png" alt=""
                                             style="display: none;">
                                        <img class="side-hand_zip" id="side-hand_zip"
                                             src="public/custom/wetsuit/male-new/hand_zip.png" alt=""
                                             style="display: none;">
                                        <img class="side-leg_zip" id="side-leg_zip"
                                             src="public/custom/wetsuit/male-new/leg_zip.png" alt=""
                                             style="display: none;">
                                        <!--<img src="public/custom/wetsuit/male-new/neck_line.png" alt="" id="neck_line">-->
                                        <img src="public/custom/wetsuit/male-new/logo.png" alt="" id="logo_of_male">

                                    </div>
                                    <!--<button id="test" type="button" class="btn btn-default text-center">
                                      Hide/Show
                                    </button>-->
                                    <canvas id="canvas" style="display:none;"></canvas>
                                </div>

                            </div>

                            <div id="custom-female" class="row custom-gender" style="display: none;">
                                <div class="col-sm-12">
                                    <div class="wetsuit-box">
                                        <img src="public/custom/wetsuit/female-new/default.png" alt="">
                                        <img class="var0-var0" id="var3"
                                             src="public/custom/wetsuit/female-new/var0/var0/black.png" alt="">
                                        <img class="var1-var1" id="var4"
                                             src="public/custom/wetsuit/female-new/var1/var1/black.png" alt="">
                                        <img class="var2-var2" id="var5"
                                             src="public/custom/wetsuit/female-new/var2/var2/black.png" alt="">
                                        <img class="var1-thread" id="thread-2"
                                             src="public/custom/wetsuit/female-new/var1/thread/white.png" alt="">
                                        <img class="var1-hand_pad" id="hand_pad-2" src="" alt="">
                                        <img class="var1-knee_pad" id="knee_pad-2" src="" alt="">
                                        <img class="side-front_zip" id="side-front_zip-2"
                                             src="public/custom/wetsuit/female-new/back_zip.png"
                                             alt="" style="display: none;">
                                        <img class="side-hand_zip" id="side-hand_zip-2"
                                             src="public/custom/wetsuit/female-new/hand_zip.png"
                                             alt="" style="display: none;">
                                        <img class="side-leg_zip" id="side-leg_zip-2"
                                             src="public/custom/wetsuit/female-new/leg_zip.png"
                                             alt="" style="display: none;">
                                        <!--img src="public/custom/wetsuit/female-new/neck_line.png" alt=""-->
                                        <img src="public/custom/wetsuit/female-new/logo.png" id="logo_of_female" alt="">
                                    </div>
                                    <canvas id="canvas2" style="display:none;"></canvas>
                                </div>
                            </div>

                            <div id="custom-unisex" class="row custom-gender" style="display: none;">
                                <div class="col-sm-12">
                                    <div class="wetsuit-box">
                                        <img src="public/custom/wetsuit/unisex_new/default.png" alt="">
                                        <img class="var0-var0" id="var6"
                                             src="public/custom/wetsuit/unisex_new/var0/var0/black.png" alt="">
                                        <img class="var1-var1" id="var7"
                                             src="public/custom/wetsuit/unisex_new/var1/var1/black.png" alt="">
                                        <img class="var1-thread" id="thread-3"
                                             src="public/custom/wetsuit/unisex_new/var1/thread/white.png" alt="">
                                        <img class="var1-hand_pad" id="hand_pad-3" src="" alt="">
                                        <img class="var1-knee_pad" id="knee_pad-3" src="" alt="">
                                        <img class="side-front_zip" id="side-front_zip-3"
                                             src="public/custom/wetsuit/unisex_new/back_zip.png"
                                             alt="" style="display: none;">
                                        <img class="side-hand_zip" id="side-hand_zip-3"
                                             src="public/custom/wetsuit/unisex_new/hand_zip.png"
                                             alt="" style="display: none;">
                                        <img class="side-leg_zip" id="side-leg_zip-3"
                                             src="public/custom/wetsuit/unisex_new/leg_zip.png"
                                             alt="" style="display: none;">
                                        <!--img src="public/custom/wetsuit/unisex_new/neck_line.png" alt=""-->
                                        <img src="public/custom/wetsuit/unisex_new/logo.png" id="logo_of_unisex" alt="">
                                    </div>
                                    <canvas id="canvas3" style="display:none;"></canvas>
                                </div>
                            </div>

                        </div>
                        <div class="text-center form-group">
                            <button type="button" data-target="#measurement" data-toggle="modal"
                                    class="btn btn-default">
                                <i class="fa fa-fw fa-clear fa-compress"></i>
                                Measure
                            </button>
                            <button type="button" id="saveimage" class="btn btn-default">
                                <i class="fa fa-fw fa-clear fa-download"></i>
                                Save Design
                            </button>
                            <button type="button" id="printimage" class="btn btn-default">
                                <i class="fa fa-fw fa-clear fa-download"></i>
                                Print Design
                            </button>
                            <input type="hidden" name="chest" value="black">
                            <input type="hidden" name="shoulder" value="black">
                            <input type="hidden" name="waist" value="black">
                            <input type="hidden" name="thread" value="white">
                            <input type="hidden" name="hand_pad" value="">
                            <input type="hidden" name="knee_pad" value="">
                            <?php
                            if (isset($user) == '') {
                                echo '<a href="../login.php" class="btn btn-default text-center">
                  <i class="fa fa-fw fa-clear fa-compress"></i>
                  Login First
                </a>
                  <button  type="button" class="btn btn-primary"  name="id_custom_item"
                        onclick="addGuestCart();" id="save_project" value="Add to Project">Add to Guest Cart</button>';
                            } else {
                                echo '
                  <button  type="button" class="btn btn-primary"  name="id_custom_item"
                        onclick="saveViaAJAX();" id="save_project" value="Add to Project">Add to Project</button>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-2 hidden-sm hidden-xs custom-box">
                        <div class="form-group">
                            <label>Elbow Pad</label>
                            <div>
                                <a href="javascript:void(0);" onclick="change_colors('hand_pad', 'black', this);"
                                   title="Black">
                                    <div class="color" style="background-color: #1f1f24;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('hand_pad', 'blue', this);"
                                   title="Blue">
                                    <div class="color" style="background-color: #345aa0;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('hand_pad', 'white', this);"
                                   title="White">
                                    <div class="color" style="background-color: #ffffff;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('hand_pad', '', this);"
                                   title="none">
                                    <div class="color active"
                                         style="background-image: url('public/images/sakarioka/x.jpg')"></div>
                                </a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Knee Pad</label>
                            <div>
                                <a href="javascript:void(0);" onclick="change_colors('knee_pad', 'black', this);"
                                   title="Black">
                                    <div class="color" style="background-color: #1f1f24;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('knee_pad', 'blue', this);"
                                   title="Blue">
                                    <div class="color" style="background-color: #345aa0;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('knee_pad', 'white', this);"
                                   title="White">
                                    <div class="color" style="background-color: #ffffff;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('knee_pad', '', this);"
                                   title="none">
                                    <div class="color active"
                                         style="background-image: url('public/images/sakarioka/x.jpg')"></div>
                                </a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Thread Color</label>
                            <div>
                                <a href="javascript:void(0);" onclick="change_colors('thread', 'black', this);"
                                   title="Black">
                                    <div class="color " style="background-color: #1f1f24;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('thread', 'blue', this);"
                                   title="Blue">
                                    <div class="color " style="background-color: #345aa0;"></div>
                                </a>
                                <a href="javascript:void(0);" onclick="change_colors('thread', 'white', this);"
                                   title="White">
                                    <div class="color active" style="background-color: #ffffff;"></div>
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Arm Zipper</label>
                            <select name="armZipper" class="form-control" onchange="change_hand_zipper(this.value);">
                                <option value="yes">Yes</option>
                                <option value="no" selected>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ankle Zipper</label>
                            <select name="ankleZipper" class="form-control" onchange="change_leg_zipper(this.value);">
                                <option value="yes">Yes</option>
                                <option value="no" selected>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="measurement">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Measurements</h4>
                </div>
                <div class="modal-body" id="measure-print">
                    <div class="row">
                        <div class="col-sm-6">
                            <img src="public/images/sakarioka/CMD.jpg" alt="" style="width: 100%">
                        </div>
                        <div class="col-sm-6">
                            <div style="height: 610px; overflow-y: scroll;">
                                <div class="form-group">
                                    <label>Total Body Height</label>
                                    <input type="number" name="measureTotalBodyHeight" step="0.1" id="measurement-1"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Head</label>
                                    <input type="number" name="measureHead" step="0.1" id="measurement-2"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Neck</label>
                                    <input type="number" name="measureNeck" step="0.1" id="measurement-3"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Bust / Chest</label>
                                    <input type="number" name="measureBustChest" step="0.1" id="measurement-4"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Waist</label>
                                    <input type="number" name="measureWaist" step="0.1" id="measurement-5"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Stomach</label>
                                    <input type="number" name="measureStomach" step="0.1" id="measurement-6"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Abdomen</label>
                                    <input type="number" name="measureAbdomen" step="0.1" id="measurement-7"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Hip</label>
                                    <input type="number" name="measureHip" step="0.1" id="measurement-8"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Shoulder</label>
                                    <input type="number" name="measureShoulder" step="0.1" id="measurement-9"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Shoulder to Elbow</label>
                                    <input type="number" name="measureShoulderToElbow" step="0.1" id="measurement-10"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Shoulder to Wrist</label>
                                    <input type="number" name="measureShoulderToWrist" step="0.1" id="measurement-11"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Arm Hole</label>
                                    <input type="number" name="measureArmHole" step="0.1" id="measurement-12"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Upper Arm</label>
                                    <input type="number" name="measureUpperArm" step="0.1" id="measurement-13"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Bicep</label>
                                    <input type="number" name="measureBicep" step="0.1" id="measurement-14"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Elbow</label>
                                    <input type="number" name="measureElbow" step="0.1" id="measurement-15"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Forarm</label>
                                    <input type="number" name="measureForarm" step="0.1" id="measurement-16"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Wrist</label>
                                    <input type="number" name="measureWrist" step="0.1" id="measurement-17"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Outside Leg Length</label>
                                    <input type="number" name="measureOutsideLegLength" step="0.1" id="measurement-18"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Inside Leg Length</label>
                                    <input type="number" name="measureInsideLegLength" step="0.1" id="measurement-19"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Upper Thigh</label>
                                    <input type="number" name="measureUpperThigh" step="0.1" id="measurement-20"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Thigh</label>
                                    <input type="number" name="measureThigh" step="0.1" id="measurement-21"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Above Knee</label>
                                    <input type="number" name="measureAboveKnee" step="0.1" id="measurement-22"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Knee</label>
                                    <input type="number" name="measureKnee" step="0.1" id="measurement-23"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Below Knee</label>
                                    <input type="number" name="measureBelowKnee" step="0.1" id="measurement-24"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Calf</label>
                                    <input type="number" name="measureCalf" step="0.1" id="measurement-27"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Below Calf</label>
                                    <input type="number" name="measureBelowCalf" step="0.1" id="measurement-28"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Above Ankle</label>
                                    <input type="number" name="measureAboveAnkle" step="0.1" id="measurement-29"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Shoulder to Bust</label>
                                    <input type="number" name="measureShoulderToBust" step="0.1" id="measurement-30"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Shoulder to Waist</label>
                                    <input type="number" name="measureShoulderToWaist" step="0.1" id="measurement-31"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Shoulder to Hip</label>
                                    <input type="number" name="measureShoulderToHip" step="0.1" id="measurement-32"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Hip to Knee Length</label>
                                    <input type="number" name="measureHipToKneeLength" step="0.1" id="measurement-33"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Knee to Ankle Length</label>
                                    <input type="number" name="measureKneeToAnkleLength" step="0.1" id="measurement-34"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Back Shoulder</label>
                                    <input type="number" name="measureBackShoulder" step="0.1" id="measurement-35"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Dorsum</label>
                                    <input type="number" name="measureDorsum" step="0.1" id="measurement-36"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                                <div class="form-group">
                                    <label>Crotch Point</label>
                                    <input type="number" name="measureCrotchPoint" step="0.1" id="measurement-37"
                                           class="form-control input-sm"
                                           style="width: 85%; display: inline-block; margin-right: 7px;" type="text">cm
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="savemeasurement" class="btn btn-default">Print</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="img-out" style="display: none"></div>
<div id="print-measurement" style="display: none">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <p><strong>CONFIGURATION</strong></p>
                <p>Gender: <span id="gender-print"></span></p>
                <p>1<sup>st</sup> Layer: <span id="color-print-1">23252b</span> (<span id="thickness-print-1">-</span>)
                </p>
                <p>2<sup>nd</sup> Layer: <span id="color-print-2">23252b</span> (<span id="thickness-print-2">-</span>)
                </p>
                <p>3<sup>rd</sup> Layer: <span id="color-print-3">23252b</span> (<span id="thickness-print-3">-</span>)
                </p>
                <p>Elbow Pad: <span id="color-print-4">-</span></p>
                <p>Knee Pad: <span id="color-print-5">-</span></p>
                <p>Thread Color: <span id="color-print-6">ffffff</span></p>
            </div>
            <div class="col-sm-8">
                <p><strong>MEASUREMENT</strong></p>
                <div class="row">
                    <div class="col-sm-6">
                        <p>Total Body Height: <span id="measure-print-1">-</span> cm</p>
                        <p>Head: <span id="measure-print-2">-</span> cm</p>
                        <p>Neck: <span id="measure-print-3">-</span> cm</p>
                        <p>Bust / Chest: <span id="measure-print-4">-</span> cm</p>
                        <p>Waist: <span id="measure-print-5">-</span> cm</p>
                        <p>Stomach: <span id="measure-print-6">-</span> cm</p>
                        <p>Abdomen: <span id="measure-print-7">-</span> cm</p>
                        <p>Hip: <span id="measure-print-8">-</span> cm</p>
                        <p>Shoulder: <span id="measure-print-9">-</span> cm</p>
                        <p>Shoulder to Elbow: <span id="measure-print-10">-</span> cm</p>
                        <p>Shoulder to Wrist: <span id="measure-print-11">-</span> cm</p>
                        <p>Arm Hole: <span id="measure-print-12">-</span> cm</p>
                        <p>Upper Arm: <span id="measure-print-13">-</span> cm</p>
                        <p>Bicep: <span id="measure-print-14">-</span> cm</p>
                        <p>Elbow: <span id="measure-print-15">-</span> cm</p>
                        <p>Forarm: <span id="measure-print-16">-</span> cm</p>
                        <p>Wrist: <span id="measure-print-17">-</span> cm</p>
                        <p>Outside Leg Length: <span id="measure-print-18">-</span> cm</p>
                        <p>Inside Leg Length: <span id="measure-print-19">-</span> cm</p>
                    </div>
                    <div class="col-sm-6">
                        <p>Upper Thigh: <span id="measure-print-20">-</span> cm</p>
                        <p>Thigh: <span id="measure-print-21">-</span> cm</p>
                        <p>Above Knee: <span id="measure-print-22">-</span> cm</p>
                        <p>Knee: <span id="measure-print-23">-</span> cm</p>
                        <p>Below Knee: <span id="measure-print-24">-</span> cm</p>
                        <p>Calf: <span id="measure-print-25">-</span> cm</p>
                        <p>Below Knee: <span id="measure-print-26">-</span> cm</p>
                        <p>Calf: <span id="measure-print-27">-</span> cm</p>
                        <p>Below Calf: <span id="measure-print-28">-</span> cm</p>
                        <p>Above Ankle: <span id="measure-print-29">-</span> cm</p>
                        <p>Shoulder to Bust: <span id="measure-print-30">-</span> cm</p>
                        <p>Shoulder to Waist: <span id="measure-print-31">-</span> cm</p>
                        <p>Shoulder to Hip: <span id="measure-print-32">-</span> cm</p>
                        <p>Hip to Knee Length: <span id="measure-print-33">-</span> cm</p>
                        <p>Knee to Ankle Length: <span id="measure-print-34">-</span> cm</p>
                        <p>Back Shoulder: <span id="measure-print-35">-</span> cm</p>
                        <p>Dorsum: <span id="measure-print-36">-</span> cm</p>
                        <p>Crotch Point: <span id="measure-print-37">-</span> cm</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    var canvas2Data, canvasData, canvas3Data;
    function hide_gender(gender) {
        $('#select-gender').hide(500, function () {
            $('select[name="gender"]').val(gender);
            $('.selectpicker').selectpicker('refresh')
            $('.custom-gender').hide();
            $('#custom-' + gender).show();
            $('#custom').show(500, function () {
                //save_image();
                $('main').css('margin-bottom', '');
            });
        });
        change_gender(gender);
    }

    function change_gender(gender) {
        $('.custom-gender').hide();
        $('#custom-' + gender).show(500);

        if (gender == 'male') {
            draw_male();
        } else if (gender == 'female') {
            draw_female();
        } else if (gender == 'unisex') {
            draw_unisex();
        }
    }

    /*function change_color(layer , color, base)
     {
     $('#custom-male').find('.var1-' + layer).attr('src', 'public/custom/wetsuit/female-new/var1/' + layer + '/'  + color + '.png');
     //$('#custom-male').find('.side-'  + layer).attr('src', 'public/custom/wetsuit/male/side/'  + layer + '/' + color + '.png');
     // $('#custom-male').find('.back-'  + layer).attr('src', 'public/custom/wetsuit/male/back/'  + layer + '/' + color + '.png');
     //$('#custom-male').find('.front-' + layer).attr('src', 'public/custom/wetsuit/male/front/' + layer + '/' + color + '.png');

     $('#custom-female').find('.side-'  + layer).attr('src', 'public/custom/wetsuit/female-new/'  + layer + '/' + color + '.png');
     $('#custom-female').find('.back-'  + layer).attr('src', 'public/custom/wetsuit/female/back/'  + layer + '/' + color + '.png');
     $('#custom-female').find('.front-' + layer).attr('src', 'public/custom/wetsuit/female/front/' + layer + '/' + color + '.png');

     $('#custom-unisex').find('.side-'  + layer).attr('src', 'public/custom/wetsuit/unisex/side/'  + layer + '/' + color + '.png');
     $('#custom-unisex').find('.back-'  + layer).attr('src', 'public/custom/wetsuit/unisex/back/'  + layer + '/' + color + '.png');
     $('#custom-unisex').find('.front-' + layer).attr('src', 'public/custom/wetsuit/unisex/front/' + layer + '/' + color + '.png');

     $('#color-print-' + layer).text(color);

     $(base).parent().find('a').each(function() {
     $(this).find('div').removeClass('active');
     });

     $(base).find('div').addClass('active');

     $('input[name="' + layer + '"]').val(color);

     //save_image();
     }*/

    function change_colors(layer, color, base) {

        $('#custom-male').find('.var0-' + layer).attr('src', 'public/custom/wetsuit/male-new/var0/' + layer + '/' + color + '.png');
        $('#custom-male').find('.var1-' + layer).attr('src', 'public/custom/wetsuit/male-new/var1/' + layer + '/' + color + '.png');
        $('#custom-male').find('.var2-' + layer).attr('src', 'public/custom/wetsuit/male-new/var2/' + layer + '/' + color + '.png');


        $('#custom-female').find('.var0-' + layer).attr('src', 'public/custom/wetsuit/female-new/var0/' + layer + '/' + color + '.png');
        $('#custom-female').find('.var1-' + layer).attr('src', 'public/custom/wetsuit/female-new/var1/' + layer + '/' + color + '.png');
        $('#custom-female').find('.var2-' + layer).attr('src', 'public/custom/wetsuit/female-new/var2/' + layer + '/' + color + '.png');


        $('#custom-unisex').find('.var0-' + layer).attr('src', 'public/custom/wetsuit/unisex_new/var0/' + layer + '/' + color + '.png');
        $('#custom-unisex').find('.var1-' + layer).attr('src', 'public/custom/wetsuit/unisex_new/var1/' + layer + '/' + color + '.png');
        $('#custom-unisex').find('.var2-' + layer).attr('src', 'public/custom/wetsuit/female-new/var2/' + layer + '/' + color + '.png');

        // $('#custom-unisex').find('.side-'  + layer).attr('src', 'public/custom/wetsuit/unisex/side/'  + layer + '/' + color + '.png');
        //$('#custom-unisex').find('.back-'  + layer).attr('src', 'public/custom/wetsuit/unisex/back/'  + layer + '/' + color + '.png');
        //$('#custom-unisex').find('.front-' + layer).attr('src', 'public/custom/wetsuit/unisex/front/' + layer + '/' + color + '.png');

        // $('#color-print-' + layer).text(color);

        $(base).parent().find('a').each(function () {
            $(this).find('div').removeClass('active');
        });
        $(base).find('div').addClass('active');
        // $('input[name="' + layer + '"]').val(color);

        //save_image();

        var selectValue = $('[name="gender"]').val();
        if (selectValue == 'male') {
            draw_male();
        } else if (selectValue == 'female') {
            draw_female();
        } else if (selectValue == 'unisex') {
            draw_unisex();
        }
    }

    function change_suit_type(suitType) {
        if (suitType == 'front') {
            $('#custom-male').find('.side-front_zip').hide();

            $('#custom-female').find('.side-front_zip').hide();

            $('#custom-unisex').find('.side-front_zip').hide();
        } else {

            $('#custom-male').find('.side-front_zip').show();

            $('#custom-female').find('.side-front_zip').show();

            $('#custom-unisex').find('.side-front_zip').show();
        }
    }

    function change_hand_zipper(handZipper) {
        if (handZipper == 'yes') {
            $('#custom-male').find('.back-hand_zip').show();
            $('#custom-male').find('.front-hand_zip').show();
            $('#custom-male').find('.side-hand_zip').show();

            $('#custom-female').find('.back-hand_zip').show();
            $('#custom-female').find('.front-hand_zip').show();
            $('#custom-female').find('.side-hand_zip').show();

            $('#custom-unisex').find('.back-hand_zip').show();
            $('#custom-unisex').find('.front-hand_zip').show();
            $('#custom-unisex').find('.side-hand_zip').show();
        } else {
            $('#custom-male').find('.back-hand_zip').hide();
            $('#custom-male').find('.front-hand_zip').hide();
            $('#custom-male').find('.side-hand_zip').hide();

            $('#custom-female').find('.back-hand_zip').hide();
            $('#custom-female').find('.front-hand_zip').hide();
            $('#custom-female').find('.side-hand_zip').hide();

            $('#custom-unisex').find('.back-hand_zip').hide();
            $('#custom-unisex').find('.front-hand_zip').hide();
            $('#custom-unisex').find('.side-hand_zip').hide();
        }
    }

    function change_leg_zipper(legZipper) {
        if (legZipper == 'yes') {
            $('#custom-male').find('.back-leg_zip').show();
            $('#custom-male').find('.front-leg_zip').show();
            $('#custom-male').find('.side-leg_zip').show();

            $('#custom-female').find('.back-leg_zip').show();
            $('#custom-female').find('.front-leg_zip').show();
            $('#custom-female').find('.side-leg_zip').show();

            $('#custom-unisex').find('.back-leg_zip').show();
            $('#custom-unisex').find('.front-leg_zip').show();
            $('#custom-unisex').find('.side-leg_zip').show();
        } else {
            $('#custom-male').find('.back-leg_zip').hide();
            $('#custom-male').find('.front-leg_zip').hide();
            $('#custom-male').find('.side-leg_zip').hide();

            $('#custom-female').find('.back-leg_zip').hide();
            $('#custom-female').find('.front-leg_zip').hide();
            $('#custom-female').find('.side-leg_zip').hide();

            $('#custom-unisex').find('.back-leg_zip').hide();
            $('#custom-unisex').find('.front-leg_zip').hide();
            $('#custom-unisex').find('.side-leg_zip').hide();
        }
    }

    function change_genital_zipper(genitalZipper) {
        if (genitalZipper == 'yes') {
            $('#custom-male').find('.back-genital_zip').show();
            $('#custom-male').find('.front-genital_zip').show();
            $('#custom-male').find('.side-genital_zip').show();

            $('#custom-female').find('.back-genital_zip').show();
            $('#custom-female').find('.front-genital_zip').show();
            $('#custom-female').find('.side-genital_zip').show();

            $('#custom-unisex').find('.back-genital_zip').show();
            $('#custom-unisex').find('.front-genital_zip').show();
            $('#custom-unisex').find('.side-genital_zip').show();
        } else {
            $('#custom-male').find('.back-genital_zip').hide();
            $('#custom-male').find('.front-genital_zip').hide();
            $('#custom-male').find('.side-genital_zip').hide();

            $('#custom-female').find('.back-genital_zip').hide();
            $('#custom-female').find('.front-genital_zip').hide();
            $('#custom-female').find('.side-genital_zip').hide();

            $('#custom-unisex').find('.back-genital_zip').hide();
            $('#custom-unisex').find('.front-genital_zip').hide();
            $('#custom-unisex').find('.side-genital_zip').hide();
        }
    }

    function save_image() {
        html2canvas($("#custom-change"), {
            onrendered: function (canvas) {
                var newimg = Canvas2Image.convertToPNG(canvas);
                $('#img-out').html('').append(newimg);
                var imgData = $('#img-out').find('img').attr('src');

                $('#img-save-button').attr('href', imgData);
            }
        });
    }

    function draw_male() {
        var location = window.location.href;

        var canvas = document.getElementById("canvas");
        var img1 = document.getElementById("var0").src;
        var img2 = document.getElementById("var1").src;
        var img3 = document.getElementById("var2").src;
        var img4 = document.getElementById("logo_of_male").src;
        var img5 = document.getElementById("thread").src;
        var img6 = document.getElementById("hand_pad").src;
        var img7 = document.getElementById("knee_pad").src;
        var img8 = document.getElementById("side-hand_zip").src;
        var img9 = document.getElementById("side-leg_zip").src;
        var img10 = document.getElementById("side-front_zip").src;

        canvas.width = 2000;
        canvas.height = 1000;

        image1 = new MarvinImage();
        image1.load(img1, imageLoaded);
        image2 = new MarvinImage();
        image2.load(img2, imageLoaded);
        image3 = new MarvinImage();
        image3.load(img3, imageLoaded);
        image4 = new MarvinImage();
        image4.load(img4, imageLoaded);
        image5 = new MarvinImage();
        image5.load(img5, imageLoaded);

        // check if Need
        if (img6 != location && img6 != '') {
            image6 = new MarvinImage();
            image6.load(img6, imageLoaded);
        }
        if (img7 != location && img7 != '') {
            image7 = new MarvinImage();
            image7.load(img7, imageLoaded);
        }
        image8 = new MarvinImage();
        image8.load(img8, imageLoaded);
        image9 = new MarvinImage();
        image9.load(img9, imageLoaded);
        image10 = new MarvinImage();
        image10.load(img10, imageLoaded);

        var loaded = 0;

        function imageLoaded() {
            if (++loaded > 1) {
                var image = new MarvinImage(image1.getWidth(), image1.getHeight());

                Marvin.combineByAlpha(image1, image2, image, 0, 0);
                Marvin.combineByAlpha(image, image3, image, 0, 0);
                Marvin.combineByAlpha(image, image4, image, 0, 0);
                Marvin.combineByAlpha(image, image5, image, 0, 0);

                // check if Need
                if (img6 != location && img6 != '') {
                    Marvin.combineByAlpha(image, image6, image, 0, 0);
                }
                if (img7 != location && img7 != '') {
                    Marvin.combineByAlpha(image, image7, image, 0, 0);
                }
                if ($('[name="armZipper"]').val() == "yes") { // sipper
                    Marvin.combineByAlpha(image, image8, image, 0, 0);
                }

                if ($('[name="ankleZipper"]').val() == "yes") { // sipper
                    Marvin.combineByAlpha(image, image9, image, 0, 0);
                }
                if ($('[name="wetsuitType"]').val() == "back") { // sipper
                    Marvin.combineByAlpha(image, image10, image, 0, 0);
                }

                image.draw(canvas);
                canvasData = canvas;
            }
        }
    }

    function draw_female() {
        var location = window.location.href;

        var canvas2 = document.getElementById("canvas2");
        var img1 = document.getElementById("var3").src;
        var img2 = document.getElementById("var4").src;
        var img3 = document.getElementById("var5").src;
        var img4 = document.getElementById("logo_of_female").src;
        var img5 = document.getElementById("thread-2").src;
        var img6 = document.getElementById("hand_pad-2").src;
        var img7 = document.getElementById("knee_pad-2").src;
        var img8 = document.getElementById("side-hand_zip-2").src;
        var img9 = document.getElementById("side-leg_zip-2").src;
        var img10 = document.getElementById("side-front_zip-2").src;

        canvas2.width = 2000;
        canvas2.height = 1000;

        image1 = new MarvinImage();
        image1.load(img1, imageLoaded);
        image2 = new MarvinImage();
        image2.load(img2, imageLoaded);
        image3 = new MarvinImage();
        image3.load(img3, imageLoaded);
        image4 = new MarvinImage();
        image4.load(img4, imageLoaded);
        image5 = new MarvinImage();
        image5.load(img5, imageLoaded);

        // check if Need
        if (img6 != location && img6 != '') {
            image6 = new MarvinImage();
            image6.load(img6, imageLoaded);
        }
        if (img7 != location && img7 != '') {
            image7 = new MarvinImage();
            image7.load(img7, imageLoaded);
        }

        image8 = new MarvinImage();
        image8.load(img8, imageLoaded);
        image9 = new MarvinImage();
        image9.load(img9, imageLoaded);
        image10 = new MarvinImage();
        image10.load(img10, imageLoaded);

        var loaded = 0;

        function imageLoaded() {
            if (++loaded > 1) {
                var image = new MarvinImage(image1.getWidth(), image1.getHeight());
                Marvin.combineByAlpha(image1, image2, image, 0, 0);
                Marvin.combineByAlpha(image, image3, image, 0, 0);
                Marvin.combineByAlpha(image, image4, image, 0, 0);
                Marvin.combineByAlpha(image, image5, image, 0, 0);

                // check if Need
                if (img6 != location && img6 != '') {
                    Marvin.combineByAlpha(image, image6, image, 0, 0);
                }
                if (img7 != location && img7 != '') {
                    console.log('true')
                    Marvin.combineByAlpha(image, image7, image, 0, 0);
                }
                if ($('[name="armZipper"]').val() === "yes") { // sipper
                    Marvin.combineByAlpha(image, image8, image, 0, 0);
                }

                if ($('[name="ankleZipper"]').val() === "yes") { // sipper
                    Marvin.combineByAlpha(image, image9, image, 0, 0);
                }
                if ($('[name="wetsuitType"]').val() === "back") { // sipper
                    Marvin.combineByAlpha(image, image10, image, 0, 0);
                }

                image.draw(canvas2);
                canvas2Data = canvas2;
            }
        }
    }

    function draw_unisex() {
        var location = window.location.href;

        var canvas3 = document.getElementById("canvas3");
        var img1 = document.getElementById("var6").src;
        var img2 = document.getElementById("var7").src;
        var img3 = document.getElementById("logo_of_unisex").src;
        var img4 = document.getElementById("thread-3").src;
        var img5 = document.getElementById("hand_pad-3").src;
        var img6 = document.getElementById("knee_pad-3").src;
        var img7 = document.getElementById("side-hand_zip-3").src;
        var img8 = document.getElementById("side-leg_zip-3").src;
        var img9 = document.getElementById("side-front_zip-3").src;

        canvas3.width = 1690;
        canvas3.height = 1000;

        image1 = new MarvinImage();
        image1.load(img1, imageLoaded);
        image2 = new MarvinImage();
        image2.load(img2, imageLoaded);
        image3 = new MarvinImage();
        image3.load(img3, imageLoaded);
        image4 = new MarvinImage();
        image4.load(img4, imageLoaded);
        ;

        // check if Need
        if (img5 != location && img5 != '') {
            image5 = new MarvinImage();
            image5.load(img5, imageLoaded)
        }
        if (img6 != location && img6 != '') {
            image6 = new MarvinImage();
            image6.load(img6, imageLoaded);
        }

        image7 = new MarvinImage();
        image7.load(img7, imageLoaded);
        image8 = new MarvinImage();
        image8.load(img8, imageLoaded);
        image9 = new MarvinImage();
        image9.load(img9, imageLoaded);

        var loaded = 0;

        function imageLoaded() {
            if (++loaded > 1) {
                var image = new MarvinImage(image1.getWidth(), image1.getHeight());
                Marvin.combineByAlpha(image1, image2, image, 0, 0);
                Marvin.combineByAlpha(image, image3, image, 0, 0);
                Marvin.combineByAlpha(image, image4, image, 0, 0);

                // check if Need
                if (img5 != location && img5 != '') {
                    Marvin.combineByAlpha(image, image5, image, 0, 0);
                }
                if (img6 != location && img6 != '') {
                    Marvin.combineByAlpha(image, image6, image, 0, 0);
                }
                if ($('[name="armZipper"]').val() == "yes") { // sipper
                    Marvin.combineByAlpha(image, image7, image, 0, 0);
                }

                if ($('[name="ankleZipper"]').val() == "yes") { // sipper
                    Marvin.combineByAlpha(image, image8, image, 0, 0);
                }
                if ($('[name="wetsuitType"]').val() == "back") { // sipper
                    Marvin.combineByAlpha(image, image9, image, 0, 0);
                }
                image.draw(canvas3);
                canvas3Data = canvas3;
            }
        }
    }

    function print_page() {
        $('#gender-print').text($('select[name="gender"]').val());

        for (var i = 1; i <= 3; i++) {
            var layerValue = $('#' + i + '-layer-thickness').val();

            if (layerValue.length > 0) {
                $('#thickness-print-' + i).text(layerValue);
            } else {
                $('#thickness-print-' + i).text('-');
            }
        }

        for (var i = 1; i <= 37; i++) {
            var measurementValue = $('#measurement-' + i).val();

            if (measurementValue.length > 0) {
                $('#measure-print-' + i).text(measurementValue);
            } else {
                $('#measure-print-' + i).text('-');
            }
        }

        window.print();
    }

</script>

<script>
    function open_fold(base) {
        $(base).find('.btn_accor > i').click();
    }

    function add_collection(base, id) {
        var button = $(base).find('strong').html();

        $(base).find('strong').html('<i class="fa fa-clear fa-spinner fa-pulse"></i> Adding..');

        $.ajax({
            url: '/collection/addCollection',
            type: 'POST',
            data: {id: id},
        })
            .done(function () {
                $('#collection-notification').show(1000).delay(3000).hide(1000);
                fetch_cart();
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                $(base).find('strong').html(button);
            });

    }

    function fetch_cart() {
        $.ajax({
            url: '/collection/fetchCart',
            type: 'POST',
            dataType: 'html'
        })
            .done(function (data) {
                $('#cart-header').html(data);
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
            });
    }

    var headerLogoHeight = $('#sns_header_logo').outerHeight();

    $(window).scroll(function () {
        scroll_top();
    });

    $(window).load(function () {
        scroll_top();
    });

    function scroll_top() {
        /*Hide From Arbi*/
        /*if ($(document).scrollTop() < ($('#sns_header_logo').offset().top + $('#sns_header_logo').outerHeight())) {
         $('#sns_menu').removeClass('follow-top');
         $('.image-follow').hide();
         $('#scroll-language').hide();
         } else if ($(document).scrollTop() >= $('#sns_menu').offset().top) {
         $('#sns_menu').addClass('follow-top');
         $('.image-follow').show();
         $('#scroll-language').show();
         }*/
    }

    $('#savemeasurement').click(function () {
        var divToPrint = document.getElementById('measurement');

        var newWin = window.open('', 'Print Measurement');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

        newWin.document.close();

        setTimeout(function () {
            newWin.close();
        }, 10);
    });

    $("#saveimage").click(function () {

        var selectValue = $('[name="gender"]').val();
        if (selectValue == 'male') {
            canvasData.toBlob(function (blob) {
                saveAs(blob, "Design.png");
            });
        } else if (selectValue == 'female') {
            canvas2Data.toBlob(function (blob) {
                saveAs(blob, "Design.png");
            });
        } else if (selectValue == 'unisex') {
            canvas3Data.toBlob(function (blob) {
                saveAs(blob, "Design.png");
            });
        }
    });

    $('#printimage').click(function () {
        var selectValue = $('[name="gender"]').val();
        dataUrl = '';
        if (selectValue == 'male') {
            var dataUrl = canvasData.toDataURL();
        } else if (selectValue == 'female') {
            var dataUrl = canvas2Data.toDataURL();
        } else if (selectValue == 'unisex') {
            var dataUrl = canvas3Data.toDataURL();
        }

        let windowContent = '<!DOCTYPE html>';
        windowContent += '<html>';
        windowContent += '<head><title>Print canvas</title></head>';
        windowContent += '<body>';
        windowContent += '<img src="' + dataUrl + '">';
        windowContent += '</body>';
        windowContent += '</html>';

        const printWin = window.open('', '', 'width=' + 2000 + ',height=' + 1000);
        printWin.document.open();
        printWin.document.write(windowContent);

        printWin.document.addEventListener('load', function () {
            printWin.focus();
            printWin.print();
            printWin.document.close();
            printWin.close();
        }, true);
    });

    function change_currency(base) {
        $(base).parents('form').submit();
        /*
         var currency = base.value;
         $('.currency-rate').hide();
         $('.currency-rate.currency-' + currency).show();
         */
    }

    function change_language(base) {
        $(base).parents('form').submit();
    }

    $('[data-toggle="tooltip"]').tooltip();
</script>
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script>
<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag("js", new Date());

    gtag("config", "UA-131936719-1");
</script>


</body>
</html>