<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */

function admin_template($content="",$titlebar="",$titlepage="",$user="",$menu="",$plugin=""){
    
    $titlebar      = ($titlebar!="") ? $titlebar : "";
	$titlepage     = ($titlepage!="") ? $titlepage : "";
    $plugin	    = ($plugin!="") ? $plugin : "";
    $menu	    = ($menu!="") ? $menu : "";
    $content	= ($content!="") ? $content : "";

  
$menu = '
<div class="menu-bar header-sm-height" data-pages-init=\'horizontal-menu\' data-hide-extra-li="0">
          <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-close" data-toggle="horizontal-menu">
          </a>
          <ul>
            <li class=" active">
              <a href="home.php">Dashboard</a>
            </li>
            <li>
              <a href="list_member.php"><span class="title">Members</span></a>
            </li>
            <li>
              <a href="javascript:;"><span class="title">Products</span>
            <span class=" arrow"></span></a>
              <ul class="">
                <li class="">
                  <a href="list_products.php">List Products</a>
                </li>
                <li class="">
                  <a href="list_categories.php">List Categories</a>
                </li>
				<li class="">
                  <a href="list_sub_categories.php">Sub Categories</a>
                </li>
              
              </ul>
            </li>
            
                <li class="">
                  <a href="list_transaction.php">List Transaction</a>
                </li>
                
                <li class="">
                  <a href="request_product.php">Request Product</a>
                </li>
                
            </li>
			<li>
              <a href="javascript:;"><span class="title">Pages</span>
            <span class=" arrow"></span></a>
              <ul class="">
                <li class="">
                  <a href="about_us.php">About Us</a>
                </li>
                <li class="">
                  <a href="production.php">Production</a>
                </li>
				<li class="">
                  <a href="buyers_guide.php">Buyers Guide</a>
                </li>
				<li class="">
                  <a href="custom_guide.php">Custom Guide</a>
                </li>
				<li class="">
                  <a href="payment_guide.php">Payment Guide</a>
                </li>
              </ul>
            </li>
			<li>
              <a href="list_contact_us.php">Incoming Contact Us</a>
            </li>
            
          </ul>
          <a href="#" class="search-link d-flex justify-content-between align-items-center hidden-lg-up" data-toggle="search">Tap here to search <i class="pg-search float-right"></i></a>
        </div>
';
  
  
$template = '
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>'.$titlebar.' Seagods Wetsuit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="pages/css/pages.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="assets/css/paging.css">
    <link class="main-stylesheet" href="assets/css/style.css" rel="stylesheet" type="text/css" />
	<style>
	    .show-details > label {
                font-size: 11px !important;
                text-transform: uppercase !important;
                margin: 0 !important;
        }

        .show-details > h5 {
            font-size: 16px !important;
            margin-top: 0 !important;
            font-weight: 400 !important;
        }
	</style>

  </head>
  <body class="fixed-header horizontal-menu horizontal-app-menu dashboard">
    <!-- START HEADER -->
    <div class="header">
      <div class="container">
        <div class="header-inner header-md-height">
          <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-menu" data-toggle="horizontal-menu">
          </a>
          <div class="">
            <a href="#" class="search-link hidden-md-down" data-toggle="search"></a>
          </div>
          <div class="d-flex align-items-center">
            <!-- START User Info-->
            <div class="pull-left p-r-10 fs-14 font-heading hidden-md-down">
              <span class="semi-bold">'.$user.'</span>
            </div>
            <div class="dropdown pull-right sm-m-r-5">
              <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span >
                  <img src="assets/img/s-logo.png" alt=""  width="32px" >
                  </span>
              </button>
              <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                <!--<a href="#" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
                <a href="#" class="dropdown-item"><i class="pg-outdent"></i> Feedback</a>
                <a href="#" class="dropdown-item"><i class="pg-signals"></i> Help</a>-->
                <a href="logout.php" class="clearfix bg-master-lighter dropdown-item">
                  <span class="pull-left">Logout</span>
                  <span class="pull-right"><i class="pg-power"></i></span>
                </a>
              </div>
            </div>
            <!-- END User Info
            <a href="#" class="header-icon pg pg-alt_menu btn-link m-l-10 sm-no-margin d-inline-block" data-toggle="quickview" data-toggle-element="#quickview"></a>
          --></div>
        </div>
        <div class="header-inner justify-content-start header-lg-height title-bar">
          <div class="brand inline align-self-end">
            <img src="assets/img/s-logo.png" style="width:35px;" >
          </div>
          <h2 class="page-title align-self-end">
                '.$titlepage.'
              </h2>
        </div>
        '.$menu.'
      </div>
    </div>
	
	<div class="page-container ">
      <!-- START PAGE CONTENT WRAPPER -->
      <div class="page-content-wrapper ">
        <!-- START PAGE CONTENT -->
        <div class="content sm-gutter">
          <!-- START JUMBOTRON -->
          <div data-pages="parallax">
            <div class=" container container-fixed-lg">
              <div class="inner">
                <!-- START BREADCRUMB -->
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                  <li class="breadcrumb-item active">'.$titlepage.'</li>
                </ol>
              </div>
            </div>
          </div>
	
            '.$content.'
    
        <!-- END COPYRIGHT -->
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
        
        <div class=" container container-fixed-lg footer">
          <div class="copyright sm-text-center">
            <p class="small no-margin pull-left sm-pull-reset">
              <span class="hint-text">SeaGods Administrator Page &copy; 2018 </span>
              <span class="font-montserrat"></span>.
              <span class="hint-text">All rights reserved. </span>
              <span class="sm-block"><a href="#" class="m-l-10 m-r-10">Terms of use</a> <span class="muted">|</span> <a href="#" class="m-l-10">Privacy Policy</a></span>
            </p>
            <p class="small no-margin pull-right sm-pull-reset">
              Supported by <span class="hint-text"> XtremeWeb Solution</span>
            </p>
            <div class="clearfix"></div>
          </div>
        </div>
        
      </div>
      <!-- END PAGE CONTAINER -->
                 
            
    
    <!-- BEGIN VENDOR JS -->
    <script src="assets/plugins/feather-icons/feather.min.js" type="text/javascript"></script>
    <script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="assets/plugins/tether/js/tether.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="assets/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="assets/plugins/classie/classie.js"></script>
    <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    '.$plugin.'
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="pages/js/pages.min.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <!--script src="assets/js/scripts.js" type="text/javascript"></script-->
	

	
	
	   
  </body>
</html>


';

return $template;
}

function member_template($content="",$titlebar="",$titlepage="",$user="",$menu="",$plugin=""){
    
    $titlebar      = ($titlebar!="") ? $titlebar : "";
	$titlepage     = ($titlepage!="") ? $titlepage : "";
    $plugin	    = ($plugin!="") ? $plugin : "";
    $menu	    = ($menu!="") ? $menu : "";
    $content	= ($content!="") ? $content : "";

  
$menu = '
<div class="menu-bar header-sm-height" data-pages-init=\'horizontal-menu\' data-hide-extra-li="0">
          <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-close" data-toggle="horizontal-menu">
          </a>
          <ul>
            <li class=" active">
              <a href="profile.php">Profile</a>
            </li>
			 <li>
              <a href="carts.php"><span class="title">Carts</span></a>
            </li>
			<li>
              <a href="transaction_history.php"><span class="title">History</span></a>
            </li>
           
           
            
          </ul>
          <a href="#" class="search-link d-flex justify-content-between align-items-center hidden-lg-up" data-toggle="search">Tap here to search <i class="pg-search float-right"></i></a>
        </div>
';
  
  
$template = '
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>'.$titlebar.' Seagods Wetsuit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="pages/css/pages.css" rel="stylesheet" type="text/css" />
	
	

	

	'.$plugin.'
  </head>
  <body class="fixed-header horizontal-menu horizontal-app-menu dashboard">
    <!-- START HEADER -->
    <div class="header">
      <div class="container">
        <div class="header-inner header-md-height">
          <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-menu" data-toggle="horizontal-menu">
          </a>
          <div class="">
            <a href="#" class="search-link hidden-md-down" data-toggle="search"></a>
          </div>
          <div class="d-flex align-items-center">
            <!-- START User Info-->
            <div class="pull-left p-r-10 fs-14 font-heading hidden-md-down">
              <span class="semi-bold">'.$user.'</span>
            </div>
            <div class="dropdown pull-right sm-m-r-5">
              <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span >
                  <img src="assets/img/s-logo.png" alt=""  width="32px" >
                  </span>
              </button>
              <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                <!--<a href="#" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
                <a href="#" class="dropdown-item"><i class="pg-outdent"></i> Feedback</a>
                <a href="#" class="dropdown-item"><i class="pg-signals"></i> Help</a>-->
                <a href="logout.php" class="clearfix bg-master-lighter dropdown-item">
                  <span class="pull-left">Logout</span>
                  <span class="pull-right"><i class="pg-power"></i></span>
                </a>
              </div>
            </div>
            <!-- END User Info
            <a href="#" class="header-icon pg pg-alt_menu btn-link m-l-10 sm-no-margin d-inline-block" data-toggle="quickview" data-toggle-element="#quickview"></a>
          --></div>
        </div>
        <div class="header-inner justify-content-start header-lg-height title-bar">
          <div class="brand inline align-self-end">
            <img src="assets/img/s-logo.png" style="width:35px;" >
          </div>
          <h2 class="page-title align-self-end">
                '.$titlepage.'
              </h2>
        </div>
        '.$menu.'
      </div>
    </div>
	
	<div class="page-container ">
      <!-- START PAGE CONTENT WRAPPER -->
      <div class="page-content-wrapper ">
        <!-- START PAGE CONTENT -->
        <div class="content sm-gutter">
          <!-- START JUMBOTRON -->
          <div data-pages="parallax">
            <div class=" container no-padding    container-fixed-lg">
              <div class="inner">
                <!-- START BREADCRUMB -->
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                  <li class="breadcrumb-item active">'.$titlepage.'</li>
                </ol>
              </div>
            </div>
          </div>
	
    '.$content.'
	
	<br><br>
	 <div class=" container   container-fixed-lg footer">
          <div class="copyright sm-text-center">
            <p class="small no-margin pull-left sm-pull-reset">
              <span class="hint-text">SeaGods Administrator Page &copy; 2018 </span>
              <span class="font-montserrat"></span>.
              <span class="hint-text">All rights reserved. </span>
              <span class="sm-block"><a href="#" class="m-l-10 m-r-10">Terms of use</a> <span class="muted">|</span> <a href="#" class="m-l-10">Privacy Policy</a></span>
            </p>
            <p class="small no-margin pull-right sm-pull-reset">
              Supported by <span class="hint-text"> XtremeWeb Solution</span>
            </p>
            <div class="clearfix"></div>
          </div>
        </div>
        <!-- END COPYRIGHT -->
      </div>
      <!-- END PAGE CONTENT WRAPPER -->
    </div>
	
    <!-- END PAGE CONTAINER -->
    <!--START QUICKVIEW -->
    <div id="quickview" class="quickview-wrapper" data-pages="quickview">
      
                  </div>
                 
            
    <!-- END QUICKVIEW-->
    <!-- START OVERLAY -->
    
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
 <script src="assets/plugins/feather-icons/feather.min.js" type="text/javascript"></script>
    <script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="assets/plugins/tether/js/tether.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="assets/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="assets/plugins/classie/classie.js"></script>
    <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="pages/js/pages.min.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/scripts.js" type="text/javascript"></script>
	

	
	
	   
  </body>
</html>


';

return $template;
}




?>