<?php
include("config/configuration.php");
include("config/template_detail.php");
include("config/currency_types.php");
session_start();

// Set logged in
$loggedin = logged_in();

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Default currency
$currency_code = CURRENCY_IDR_CODE;

// Set currency from session
if (isset($_SESSION['currency_code'])) {
    $currency_code = $_SESSION['currency_code'];
}

// Set currency from database
if ($loggedin) {
    $currency_code = $loggedin['currency_code'];
}

// Set currency
$currency = get_currency($currency_code);

// Set nominal curs from USD to IDR
$USDtoIDR = get_price('currency-value-usd-to-idr');

if ($wishlist = isset($_POST["wishlist"]) ? $_POST["wishlist"] : "") {
    if ($loggedin) {
        if ($wishlist == "add-to-wishlist") {
            $code = isset($_POST["code"]) ? strip_tags(trim($_POST["code"])) : "";
            $quantity = isset($_POST["quantity"]) ? strip_tags(trim($_POST["quantity"])) : "";
            $price = isset($_POST["price"]) ? strip_tags(trim($_POST["price"])) : "";
            $amount = $quantity * $price;

            $sql = "INSERT INTO `wishlist` (`id_wishlist`, `id_member`, `code`, `qty`, `price`, `amount`, `date_add`, `date_upd`, `level`) 
                VALUES ('', '" . $loggedin["id_member"] . "', '$code', '$quantity', '$price', '$amount', NOW(), NOW(), '0');";

            mysql_query($sql) or die (mysql_error());
            echo "<script language='JavaScript'>
                alert('Succesfully adding to your wishlist');
                window.history.back(-1);
            </script>";
        }
    } else {
        echo "<script language='JavaScript'>
        alert('You need to login first before transaction');
        location.href = 'login.php';
      </script>";
    }
}

$title = '';
if (isset($_GET['id'])) {

    $id_item = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
    $query = "SELECT `item`.* FROM `item` WHERE `item`.`level` = '0' AND `item`.`id_item` = '$id_item' ;";
    $id = mysql_query($query);
    $data_item = mysql_fetch_array($id);
    $title = $data_item["title"];

//    $rows = mysql_fetch_array(mysql_query("SELECT `photo`.* from `photo` where `photo`.`id_item` = '$id_item' AND `photo`.`level` = '0'; "));
//    $photo = isset($_GET['id']) ? strip_tags(trim($rows["photo"])) : "";
}

$titlebar = "Detail Product " . $title;
$menu = "";

$content = '<!--<div id="Subheader" style="padding:50px 0px 30px;">
                <div class="container">
                    <div class="column one">
                        <h2 class="title">' . (isset($_GET['id']) ? strip_tags(trim($data_item["title"])) : "") . '</h2>
                    </div>
                </div>
            </div>-->
        </div>
        <div id="Content">
            <div class="content_wrapper clearfix">
                <div class="sections_group">
                    <div class="section">
                        <div class="section_wrapper clearfix">
                            <div class="items_group clearfix">
                                <div class="column one woocommerce-content">
                                    <div class="product has-post-thumbnail">
                                        <div class="column one post-nav minimal">
                                            <!--<a class="prev" href="product_page.html"><i class="icon icon-left-open-big"></i></a>
                                            </li><a class="next" href="product_page.html"><i class="icon icon-right-open-big"></i></a>
                                            </li>-->	
                                        </div>
                                        <div class="product_wrapper clearfix">
                                            <div class="column one-second product_image_wrapper">
                                                <!--<span class="onsale"><i class="icon-star"></i></span>-->
                                                
                                                <div class="rslides_container">
                                                 
                                                  <ul class="rslides" id="slider1">';

if (isset($_GET['id'])) {
    $rows = mysql_query("SELECT `photo`.* from `photo` where `photo`.`id_item` = '$id_item' AND `photo`.`level` = '0'; ");
    $photo = isset($_GET['id']) ? strip_tags(trim($rows["photo"])) : "";
    while ($row_photo = mysql_fetch_array($rows)){
        if ($row_photo["photo"] == "") {
            $content .= '<li data-thumb="admin/images/no-images.jpg">
                            <img src="../admin/images/no-images.jpg" alt="">
                        </li>';
        } else {
            $urlImg = (isset($_GET['id']) ? strip_tags(trim($row_photo["photo"])) : "");
            $content .= '<li>
                            <img src="../admin/images/product/' . $urlImg . '" alt="admin/images/product/' . $urlImg . '" class="for-scroll-zoom"/>
                         </li>';
        }
    }
}

$content .= '
                                                     
                                                </ul>
                                               </div>
                                            </div>
                                            <div class="summary entry-summary column one-second">
                                                
                                                
                                                <h4 class="title">' . (isset($_GET['id']) ? strip_tags(trim($data_item["title"])) : "") . '</h4>
                                                <p class="price">
                                                    <del>
                                                    
                                                    <span class="woocommerce-Price-amount amount">
                                                    <span class="woocommerce-Price-currencySymbol"></span></span></del><ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$currency.'</span> ' . (isset($_GET['id']) ? ($currency_code == CURRENCY_USD_CODE) ? round($data_item['price'], 2) : number_format(($data_item['price'] * $USDtoIDR), 2, '.', ',') : 0) . '</span></ins>
                                                </p><br><br>
                                                
                                               <form method="post" action="?id=' . (isset($_GET['id']) ? $_GET['id'] : "") . '&code=' . $data_item['code'] . '">
                                                    <div class="quantity">
                                                        <label class="screen-reader-text" for="quantity">Quantity</label>
                                                        <input type="number" id="quantity" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4"  pattern="[0-9]*" inputmode="numeric" />
                                                        <input type="hidden" name="title" value="' . (isset($_GET['id']) ? strip_tags(trim($data_item["title"])) : "") . '">
                                                        <input type="hidden" name="code" value="' . (isset($_GET['id']) ? strip_tags(trim($data_item["code"])) : "") . '">
                                                        <input type="hidden" name="price" value="' . (isset($_GET['id']) ? strip_tags(trim($data_item["price"])) : "") . '">
                                                    </div>
                                                    <button type="button" id="add_cart" onclick="add_to_cart('.$data_item['id_item'].')" value="70" class="single_add_to_cart_button button alt">
                                                        Add to cart
                                                    </button>
                                                    <input type="submit" name="wishlist" value="add-to-wishlist" class="single_add_to_cart_button button alt">
                                                </form>
                                                <!--<div class="product_meta">
                                                    <span class="posted_in">Categories: <a href="for-her.html" rel="tag">For her</a>, <a href="category_page.html" rel="tag">Shoes</a></span>
                                                </div>-->
                                            </div>
                                        </div>
                                        <div class="jq-tabs tabs_wrapper">
                                            
                                            <div id="tab-description">
                                                
                                                <hr class="" style="margin: 0 auto 30px">
                                                
                                                <div class="column one-second">
                                                    <h3>DESCRIPTION</h3>
                                                    <p style="white-space: pre-line">
                                                        ' . (isset($_GET['id']) ? strip_tags(trim($data_item["description"])) : "") . '
                                                    </p>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';

$plugin = '
    <!-- Img Products -->
    <link rel="stylesheet" href="plugins/img-product/css/responsiveslides.css">
    <link rel="stylesheet" href="plugins/img-product/css/themes.css">
    <script src="plugins/img-product/js/wheelzoom.js"></script>
    <script type="text/javascript" src="js/notification/notify.js"></script>
    
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag("js", new Date());
    
    gtag("config", "UA-131936719-1");
  
    function add_to_cart(id_item) {
          
        var add_cart_button = jQuery("#add_cart");
        var quantity = jQuery("#quantity").val();
        console.log(add_cart_button);
        
        jQuery.ajax({
          type: "POST",
          url: "member/action_cart.php",
          data: {
              action: "add_cart",
              id_item: id_item, 
              quantity: quantity
         },
          dataType: "json",
          success: function(data) {
              if (data.status == "error") {
                  alert(data.msg);
              } else {
                  add_cart_button.text("Added to cart").attr("disabled", true);
                  notification();
              }
          }
        });
    }
  
</script>
    
    
    
';
$template = admin_template($content, $titlebar, "", "", $menu, $plugin, $loggedin);
echo $template;

?>