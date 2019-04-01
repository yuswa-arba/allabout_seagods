<?php

include ("config/configuration.php");
session_start();
ob_start();
if($loggedin = logged_in()){
  if(isset($_POST['nilai'])){
      $_SESSION['nilai_login'] = $_POST['nilai']+1;
  }else{
      $_SESSION['nilai_login'] = 0;
  }

  $user=  ''.$_SESSION['user'].'';
}
$select = mysql_query('select * from custom_cart where id_member="'.$loggedin["id_member"].'"');
$data = mysql_fetch_array($select);


?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/public/images/logo.png">

    <title>SeaGods Wetsuit</title>
    <link href="//fonts.googleapis.com/css?family=Poppins:300,700" rel="stylesheet" type="text/css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Style Sheet-->
    <link rel="stylesheet" type="text/css" href="/public/css/font-awesome-4.6.3/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" media="all">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" media="all">
    <link rel="stylesheet" href="/public/js/owl-carousel/owl.carousel.css" media="all">
    <link rel="stylesheet" href="/public/js/owl-carousel/owl.theme.css" media="all">
    <link rel="stylesheet" href="/public/css/style.css?v=20181006" media="all">
    <link rel="stylesheet" href="/public/css/mystyle.css?v=20181006" media="all">
    <link rel="stylesheet" href="/public/css/sakarioka.css?v=20181006" media="all">

    <!-- Scripts -->
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <script src="/public/js/less.min.js"></script>
    <script src="/public/js/owl-carousel/owl.carousel.min.js"></script>
    <script src="/public/js/sns-extend.js"></script>
    <script src="/public/js/custom.js?v=20181006"></script>
    <script src="/public/js/list-grid.js"></script>
    <style>
      .sns_header_top {
        height: 25px !important;
      }

      .sns_header_top img {
        height: 25px;
        padding: 5px 0;
      }
    </style>
  </head>
  <body id="bd" class="cms-index-index4 header-style4 prd-detail cms-simen-home-page-v2 default cmspage">
    <div id="sns_wrapper">  
      <!-- HEADER -->  
      
      <!-- Header Logo -->
      
      <!-- Menu -->

          </div>
    <!-- AND HEADER -->
    <!-- BREADCRUMBS -->

<!-- AND BREADCRUMBS -->

<!-- CONTENT -->
<div id="sns_content" class="wrap layout-m">
  <div class="container">
    <div class="row">
      <div class="shoppingcart">
        <div class="sptitle col-md-12 clearfix">
          <h3 class="pull-left" style="margin-top: 10px;">SHOPPING CART</h3>
                      <a href="/en/cart/checkout" class="btn hidden-xs btn-primary pull-right">
              <i class="fa fa-fw fa-clear fa-chevron-right"></i>
              Proceed To Checkout
            </a>
        </div>
      </div>
    </div>
          <div class="row hidden-xs cart-table">
        <div class="content col-md-12">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="hidden-xs" style="width: 50px;">No</th>
                <th style="width: 100px;">Image</th>
                <th>Name</th>
                <th style="width: 75px;">Quantity</th>
                <th style="width: 200px;">Price Per Item (USD)</th>
                <th style="width: 200px;">Total Price (USD)</th>
                <th style="width: 50px;">Action</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="hidden-xs text-center">1</td>
                    <td>
                      <img alt="custom-wet-suit-1" width="100" height="100" src="<?php echo $data["image"]; ?>">
                    </td>
                    <td>
                     Custom 1                
                    </td>
                    <td class="text-center">1</td>
                    <td class="text-right">
                      <div class="currency-rate ">
                        230                      
                      </div>
                    </td>
                    <td class="text-right">
                      <div class="currency-rate ">
                        230                      
                      </div>
                    </td>
                    <td>
                      <form action="/en/cart/deleteCustom" method="post">
                        <input type="hidden" name="id" value="1">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure to remove &quot;Custom 1&quot; cart item?');">
                          <i class="fa fa-fw fa-clear fa-trash"></i>
                        </button>
                      </form>
                    </td>
                </tr>                                          
              <tr>
                <td colspan="5">
                  <h5>
                    <strong>
                      TOTAL PRICE
                    </strong>
                  </h5>
                </td>
                <td colspan="2">
                  <h5 class="text-right">
                    <strong>
                      <div class="currency-rate ">
                        USD 230                      </div>                                                  
                      </strong>
                  </h5>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
          </div>
</div>
  </body>
</html>