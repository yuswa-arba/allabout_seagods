<?php 
session_start();
include ("config/configuration.php");

if($loggedin = logged_in()){
  $user=  ''.$_SESSION['user'].'';
  $id_member =  ''.$_SESSION['id_member'].'';

  if($finish = isset($_POST["finish"]) ? $_POST["finish"] : ""){
      if($finish == "Finish"){
        $sql_checkout = mysql_query("SELECT * FROM  `transaction` ORDER BY `id_transaction` DESC lIMIT 0,1");
        $harga_total  = isset($_POST["total"]) ? $_POST["total"] : "";
        $row_checkout = mysql_fetch_array($sql_checkout);
        $id_transaction = $row_checkout["id_transaction"]+1;
        $sql = "INSERT INTO `transaction` (`id_transaction` , `kode_transaction` , `id_member`, `status` ,`konfirm`,`total`,`date_add`,`date_upd`) VALUES ('','INV/".$loggedin["id_member"]."/".date("Ymd")."/".rand_numb($id_transaction,5)."',
          '".$loggedin["id_member"]."' , 'prosees' , 'not confirmated' , '$harga_total', NOW(), NOW());";

        $sql1 = "UPDATE `cart` set `id_transaction` ='$id_transaction', `date_upd` = NOW() , `level` = '1' 
                 WHERE `id_member` = '$id_member' AND `level` = '0';";

        mysql_query($sql) or die(mysql_error());
        mysql_query($sql1) or die(mysql_error());


        unset($_SESSION["cart_item"]);
        echo "<script language='JavaScript'>
                alert('Your Transaction will be procced');
                location.href = 'info_pembayaran.php';
              </script>"; 
      }
  }




} else {
  echo"<script language='JavaScript'>
        alert('You need to login first before transaction');
        location.href = 'list_product.php';
      </script>";
}
?>
<!DOCTYPE html>
<html>
  <head>
     <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
	    <title>Checkout - SeaGods Wetsuit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="member/pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="member/pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="member/pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="member/pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="member/favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="member/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="member/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="member/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="member/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="member/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="member/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="member/pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="member/pages/css/pages.css" rel="stylesheet" type="text/css" />
  </head>
  <body class="fixed-header horizontal-menu horizontal-app-menu ">
    <!-- START HEADER -->
    <div class="header">
      <div class="container">

        <div class="header-inner header-md-height">
          <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-menu" data-toggle="horizontal-menu">
          </a>
          <div class="">
            <a href="#" class="" data-toggle="search"><i class=""></i><span class="bold"></span></a>
          </div>
          <div class="d-flex align-items-center">
            <!-- START User Info-->
            <div class="pull-left p-r-10 fs-14 font-heading hidden-md-down">
              <span class="semi-bold">Member</span> <span class=""><?php echo $user;?></span>
            </div>
            <div class="dropdown pull-right sm-m-r-5">
              <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span >
                  <img src="member/assets/img/profiles/avatar.jpg" alt=""  width="32" height="32">
                  </span>
              </button>
              <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                <a href="#" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
                <a href="#" class="dropdown-item"><i class="pg-outdent"></i> Feedback</a>
                <a href="#" class="dropdown-item"><i class="pg-signals"></i> Help</a>
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
            <img src="member/assets/img/s-logo.png" alt="logo" data-src="member/assets/img/s-logo.png" data-src-retina="member/assets/img/s-logo.png"  height="20">
          </div>
          <h2 class="page-title align-self-end">
                SeaGods Wetsuit Cart
              </h2>
        </div>
        
		
		<div class="menu-bar header-sm-height" data-pages-init='horizontal-menu' data-hide-extra-li="4">
          <a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-menu" data-toggle="horizontal-menu">
          </a>
		  <ul>
            <li class=" active">
              <a href="member/index.php">Dashboard</a>
            </li>
            <li>
              <a href="member/profile.php"><span class="title">Profile</span></a>
            </li>
            
            
            <li class="">
                  <a href="member/list-transaction.php">List Transaction</a>
            </li>
            <li>
              <a href="member/wishlist.php"><span class="title">Wishlist</span></a>
            </li>
            <li>
              <a href="member/customemade.php"><span class="title">Custom Made</span></a>
            </li>
            
            
          </ul>
         
         
        </div>
        
        
      </div>
    </div>
    <div class="page-container ">
      <!-- START PAGE CONTENT WRAPPER -->
      <div class="page-content-wrapper ">
        <!-- START PAGE CONTENT -->
        <div class="content ">
          <!-- START CONTAINER FLUID -->
          <div class=" container    container-fixed-lg">
            <div id="rootwizard" class="m-t-50">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs nav-tabs-linetriangle nav-tabs-separator nav-stack-sm" role="tablist" data-init-reponsive-tabs="dropdownfx">
                <li class="nav-item">
                  <a class="active" data-toggle="tab" href="#tab1" role="tab"><i class="fa fa-shopping-cart tab-icon"></i> <span>Your cart</span></a>
                </li>
                <li class="nav-item">
                  <a class="" data-toggle="tab" href="#tab2" role="tab"><i class="fa fa-truck tab-icon"></i> <span>Shipping information</span></a>
                </li>
                <li class="nav-item">
                  <a class="" data-toggle="tab" href="#tab3" role="tab"><i class="fa fa-credit-card tab-icon"></i> <span>Payment details</span></a>
                </li>
                <li class="nav-item">
                  <a class="" data-toggle="tab" href="#tab4" role="tab"><i class="fa fa-check tab-icon"></i> <span>Summary</span></a>
                </li>
              </ul>
              <!-- Tab panes -->
              <?php 
                $sql_detail_bio = mysql_query("SELECT `member`.*, `kota`.`namaKota`, `kota`.`ongkos_kirim` ,`provinsi`.`namaProvinsi`
                                  FROM `member`, `kota`,`provinsi`
                                  WHERE `member`.`id_member` = '$id_member'
                                  AND   `member`.`idpropinsi` =  `provinsi`.`idProvinsi` 
                                  AND  `member`.`idkota` =  `kota`.`idKota`");
                $row_bio = mysql_fetch_array($sql_detail_bio);
                $kurs = round($row_bio["ongkos_kirim"] / 13887);

              ?>
			  <form role="form" method="post" action="checkout.php">
              <div class="tab-content">
			         <?php// echo $loggedin["id_member"];
               ;?>
                <div class="tab-pane padding-20 sm-no-padding active slide-left" id="tab1">
                  <div class="row row-same-height">
                    <div class="col-md-5 b-r b-dashed b-grey sm-b-b">
                      <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                        <i class="fa fa-shopping-cart fa-2x hint-text"></i>
                        <h2>Your Bags are ready to check out!</h2>
                        <p>Thanks. Here is a list of your shopping items.</p>
                        <p class="small hint-text">Take the next step to go to the shipping info and billing details.</p>
                      </div>
                    </div>
					
                    <div class="col-md-7">
                      <div class="padding-30 sm-padding-5">
                        <?php 
                          if(isset($_SESSION["cart_item"])){
                              $item_total = 0;
                          
                        ?>
                        <table class="table table-condensed">
                          <?php
                             foreach ($_SESSION["cart_item"] as $item){
                          ?>
                          <tr>
                                  <td class="col-lg-8 col-md-6 col-sm-7 ">
                                    <!--<a href="cart.php?remove=<?php echo $item["code"];?>"><i class="pg-close"></i></a>-->
                                    <span class="m-l-10 font-montserrat fs-11 all-caps"><?php echo $item["title"];?></span>
                                  </td>
                                  <td class=" col-lg-2 col-md-3 col-sm-3 text-right">
                                    <span>Qty <?php echo $item['quantity'];?></span>
                                  </td>
                                  <td class=" col-lg-2 col-md-3 col-sm-2 text-right">
                                    <h4 class="text-primary no-margin font-montserrat">$ <?php echo $item['price']?></h4>

                                  </td>
                                </tr>
                          <!--<tr>
                            <td class="col-lg-8 col-md-6 col-sm-7">
                              <a href="#" class="remove-item"><i class="pg-close"></i></a>
                              <span class="m-l-10 font-montserrat fs-11 all-caps">SeaGods Hat</span>
                            </td>
                            <td class="col-lg-2 col-md-3 col-sm-3 text-right">
                              <span>Qty 1</span>
                            </td>
                            <td class=" col-lg-2 col-md-3 col-sm-2 text-right">
                              <h4 class="text-primary no-margin font-montserrat">$17</h4>
                            </td>
                          </tr>-->
                          <?php
                              $item_total +=($item["price"] * $item["quantity"]);
                             }
                          ?>
                        </table>
                        
                        <div class="row">
                          <?php 

                            //$item = $_SESSION["cart_item"];
                            //print json_encode($item);
                          ?>
                        
                        </div>
                        <br>
                        <div class="row b-a b-grey no-margin">
                          <div class="col-md-3 p-l-10 sm-padding-15 align-items-center d-flex">
                            <div>
                              <h5 class="font-montserrat all-caps small no-margin hint-text bold">Discount</h5>
                              <p class="no-margin">$ 0</p>
                            </div>
                          </div>
                          <div class="col-md-7 col-middle sm-padding-15 align-items-center d-flex">
                            <div>
                              <h5 class="font-montserrat all-caps small no-margin hint-text bold">Shipping Cost</h5>
                              <p class="no-margin">$<?php echo $kurs ;?></p>
                            </div>
                          </div>
                          <div class="col-md-2 text-right bg-primary padding-10">
                            <h5 class="font-montserrat all-caps small no-margin hint-text text-white bold">Total</h5>
                            <h4 class="no-margin text-white">$ <?php echo $item_total+$kurs;?></h4>
                           
                          </div>
                        </div>
                        <?php 
                          }
                        ?>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab2">
                  <div class="row row-same-height">
                    <div class="col-md-5 b-r b-dashed b-grey ">
                      <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                        <h2>Your Information is safe with us!</h2>
                        <p>Please enter your data for shipping details of invoices and payments.</p>
                        <p class="small hint-text">Make sure your email is active.</p>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="padding-30 sm-padding-5">
                          <p>Name and Email Address</p>
                          <div class="form-group-attached">
                            <div class="row clearfix">
                              <div class="col-sm-6">
                                <div class="form-group form-group-default">
                                  <label>First Name</label>
                                  <span><?php echo $row_bio["firstname"];?></span>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group form-group-default">
                                  <label>Last name</label>
                                  <span><?php echo $row_bio["lastname"];?></span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group form-group-default ">
                              <label>Email</label>
                              <span><?php echo $row_bio["email"];?></span>
                            </div>
							<div class="form-group form-group-default ">
                              <label>Phone Number</label>
                              <span><?php echo $row_bio["notelp"];?></span>
                            </div>
                          </div>
                          <br>
                          <p>Billing Address</p>
                          <div class="form-group-attached">
                            <div class="form-group form-group-default ">
                              <label>Address</label>
                              <span><?php echo $row_bio["alamat"];?></span>  
                            </div>
                            <div class="row clearfix">
                              <div class="col-sm-6">
                                <div class="form-group form-group-default">
                                  <label>City</label>
                                 <span><?php echo $row_bio["namaKota"];?></span>
                                </div>
                              </div>
                            </div>
                            <div class="row clearfix">
                              <div class="col-sm-9">
                                <div class="form-group form-group-default">
                                  <label>Province</label>
                                  <span><?php echo $row_bio["namaProvinsi"];?></span>
                                </div>
                              </div>
                              <div class="col-sm-3">
                                <div class="form-group form-group-default">
                                  <label>Zip code</label>
                                  <span><?php echo $row_bio["zip_code"];?></span>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab3">
                  <div class="row row-same-height">
                    <div class="col-md-5 b-r b-dashed b-grey ">
                      <div class="padding-30 sm-padding-5 sm-m-t-15 m-t-50">
                        <h2>Your Cart</h2>
                        
                        
                        <?php 
                          if(isset($_SESSION["cart_item"])){
                              $item_total = 0;
                          
                        ?>
                        <table class="table table-condensed">
                          <?php
                             foreach ($_SESSION["cart_item"] as $item){
                              $item_total +=($item["price"] * $item["quantity"]);
                          ?>
                          <tr>
                                <td class=" col-md-9">
                                  <span class="m-l-10 font-montserrat fs-11 all-caps"><?php echo $item["title"];?></span>
                                </td>
                                <td class=" col-md-3 text-right">
                                  <span>Qty <?php echo $item['quantity']; }?></span>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" class=" col-md-3 text-right">
                                  <h4 class="text-primary no-margin font-montserrat">$ <?php echo $item_total+$kurs;?></h4>
                                   <input type="hidden" name="total" value="<?php echo $item_total+$kurs;?>">
                                </td>
                              </tr>
                            <?php
                              
                             
                          }
                          ?>
                        </table>
                        <p class="small">The billing details will be sent to your email.</p>
                        <p class="small">Due date 20 days after invoice was sent. <a href="#"><br>Terms &amp; Conditions</a></p>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="padding-30 sm-padding-5">
                        <ul class="list-unstyled list-inline m-l-30">
                          <li><a href="#" class="p-r-30 text-black  hint-text">Bank Transfer</a></li>
                        </ul>
                        
                          <div class="bg-master-light padding-30 b-rad-lg">
                            <h2 class="pull-left no-margin">Bank Account</h2>
                           
                            <div class="clearfix"></div>
                            <div class="form-group form-group-default  m-t-25">
                              <label>Card holder's name</label>
                              <span>Bank BCA SEAGODS</span>
                            </div>
                            <div class="form-group form-group-default ">
                              <label>Account Number</label>
                              <span>0878534456</span>
                            </div>
							<div class="form-group form-group-default ">
                              <label>Your Bill</label>
                              <span>$ <?php echo $item_total+$kurs;?></span>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <label>Due Date</label>
                                <br>
                                <select class="cs-select cs-skin-slide" data-init-plugin="cs-select">
                                  <option selected>20 hari</option>
                                </select>
                                <select class="cs-select cs-skin-slide" data-init-plugin="cs-select">
								<option selected>20 hari</option>
                                </select>
                              </div>
                              
                            </div>
                          </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane slide-left padding-20 sm-no-padding" id="tab4">
                  <h1>Thank you.</h1>
				  <p>The bill we sent to email: ...<br>
					Please check your email</p>
                </div>
                <div class="padding-20 sm-padding-5 sm-m-b-20 sm-m-t-20 bg-white clearfix">
                  <ul class="pager wizard no-style">
                    <li class="next">
                      <button class="btn btn-primary btn-cons btn-animated from-left fa fa-truck pull-right" type="button">
                        <span>Next</span>
                      </button>
                    </li>
                    <li class="next finish hidden">
                      <input class="btn btn-primary btn-cons btn-animated from-left fa fa-cog pull-right" type="submit" name="finish" value="Finish">
                        
                      
                    </li>
                    <li class="previous first hidden">
                      <button class="btn btn-default btn-cons btn-animated from-left fa fa-cog pull-right" type="button">
                        <span>First</span>
                      </button>
                    </li>
                    <li class="previous">
                      <button class="btn btn-default btn-cons pull-right" type="button">
                        <span>Previous</span>
                      </button>
                    </li>
                  </ul>
                </div></form>
                <div class="wizard-footer padding-20 bg-master-light">
                  <p class="small hint-text pull-left no-margin">
                    
SEAGODS WETSUIT

<br>By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia .

                  </p>
                  <div class="pull-right">
                    <img src="member/assets/img/s-logo.png" alt="logo" data-src="member/assets/img/s-logo.png" data-src-retina="member/assets/img/s-logo.png"  height="22">
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
        <!-- START CONTAINER FLUID -->
        
        
        <!-- END COPYRIGHT -->
      </div>
      <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    <!--START QUICKVIEW -->
    
    <!-- END QUICKVIEW-->
    <!-- START OVERLAY -->
    
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
   <script src="member/assets/plugins/feather-icons/feather.min.js" type="text/javascript"></script>
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
    <script src="member/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script type="text/javascript" src="member/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="member/assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="member/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="member/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="member/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="member/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/moment/moment.min.js"></script>
    <script src="member/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="member/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="member/pages/js/pages.min.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="member/assets/js/form_wizard.js" type="text/javascript"></script>
    <script src="member/assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->
  </body>
</html>