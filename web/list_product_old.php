<?php
session_start();

include("config/configuration.php");
include("config/template.php");
include("config/currency_types.php");

// Check login
$loggedin = logged_in();

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Set page
$perhalaman = 12;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
    $start = ($page - 1) * $perhalaman;
} else {
    $start = 0;
}

$titlebar = "List Product";
$menu = "";

if (isset($_GET['id_cat'])) {
    $id_cat = isset($_GET['id_cat']) ? strip_tags(trim($_GET['id_cat'])) : "";
    $query = mysql_query("SELECT * FROM `item` where `item`.`id_cat` = '$id_cat' AND `item`.`level` = '0' order by `id_item`;");
    $rows = mysql_num_rows($query);
    $category_name = mysql_fetch_array(mysql_query("SELECT `category` FROM `category` WHERE `id_cat` = (SELECT category.id_parent FROM category WHERE `id_cat` = '$id_cat' LIMIT 0,1) AND `level` = '0';"));
}

if (isset($_GET['id_cats'])) {
    $id_cats = isset($_GET['id_cats']) ? strip_tags(trim($_GET['id_cats'])) : "";
    $query = mysql_query("SELECT * FROM `item` where `item`.`id_category` = '$id_cats' AND `item`.`level` = '0' order by `id_item`;");
    $rows = mysql_num_rows($query);
    $category_name = mysql_fetch_array(mysql_query("SELECT `category` FROM `category` WHERE `id_cat` = '$id_cats' AND `level` = '0';"));
}

// Set id cat
$id_cat_currency = null;
if (isset($id_cats)) {
    $id_cat_currency = $id_cats;
}

if (isset($id_cat)) {
    $id_cat_currency = $id_cat;
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

$content = '<div class="sections_group">
                    <div class="section">
                        <div class="section_wrapper clearfix">
                            <div class="items_group clearfix">
                                <div class="column one woocommerce-content">
                                    <div class="shop-filters">
                                        <p class="woocommerce-result-count">
                                            Showing all ' . $loggedin['id_member'] . ' results - <strong>' . (isset($_GET['id_cat']) || isset($_GET['id_cats']) ? $category_name['category'] : "") . '</strong>
                                        </p>
                                            <form class="woocommerce-ordering" method="get" style="width:80px;height:50">
                                            <select id="currency_code" class="currency" onchange="changeCurrency(' . $id_cat_currency . ');">';

foreach (get_all_currency() as $row_currency) {
    $content .= '<option value="' . $row_currency . '" ' . (($row_currency == $currency_code) ? "selected" : "") . '>' . $row_currency . '</option>';
}

$content .= '
                                            </select>
                                        </form>
                                    </div>
                                    <div class="products_wrapper isotope_wrapper">
                                    

                                        <ul class="products isotope grid">';

if (isset($_GET['id_cat'])) {
    $id_cat = isset($_GET['id_cat']) ? strip_tags(trim($_GET['id_cat'])) : "";
    $query = mysql_query("SELECT * FROM `item` where `item`.`id_cat` = '$id_cat' AND `item`.`level` = '0' order by `id_item` DESC LIMIT $start,$perhalaman;");

    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `item` WHERE `item`.`id_cat` = '$id_cat' AND `item`.`level` = '0' order by `item`.`id_item` DESC"));

    $key = 0;
    while ($row_item = mysql_fetch_array($query)) {
        $id_item = $row_item['id_item'];
        $row = mysql_query("SELECT * FROM  `photo` WHERE `id_item` = '$id_item' order by `id_item`;");
        $row_photo = mysql_fetch_array($row);
        $rows = mysql_num_rows($row);
        $photo = $row_photo["photo"];

        $content .= '
                                                    <li class="isotope-item product has-post-thumbnail" >
                                                        <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                            <div class="image_wrapper">
                                                                <a href="detail_product.php?id=' . $row_item["id_item"] . '">
                                                                    <div class="mask"></div>';
        if ($photo == "") {
            $content .= '<img src="../admin/images/no-images.jpg" alt="' . $row_item['title'] . '" class="scale-with-grid wp-post-image">';
        } else {
            $content .= '
                                                                    <img src="../admin/images/product/600/thumb_' . $row_photo['photo'] . '" alt="' . $row_item['title'] . '" class="scale-with-grid wp-post-image">';
        }

        $content .= '
                                                                </a>
                                                                <div class="image_links double">
                                                                    <a rel="nofollow" href="" data-quantity="1" data-product_id="76" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                </div>
                                                            </div><a href="detail_product.php?id=' . $row_item["id_item"] . '"><span class="product-loading-icon added-cart"></span></a>
                                                        </div>
                                                        <div class="desc">
                                                            <h4><a href="detail_product.php?id=' . $row_item["id_item"] . '">' . $row_item['title'] . '</a></h4>
                                                            
                                                            <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . (($currency_code == CURRENCY_USD_CODE) ? round($row_item['price'], 2) : number_format(($row_item['price'] * $USDtoIDR), 2, '.', ',')) . '</span>
                                                            </span>
                                                        </div>
                                                        <div class="desc">
                                                            <form method="post" action="">
                                                                <input type="text" id="quantity' . $key . '" value="1" size="2">
                                                                <button class="button" type="button" id="add_cart' . $key . '" onclick="add_to_cart(' . $row_item["id_item"] . ', ' . $key . ')" value="">add to cart</button>
                                                            </form>
                                                        </div>
                                                    </li>';
        $key++;
    }

} else if (isset($_GET['id_cats'])) {

    $id_cats = isset($_GET['id_cats']) ? strip_tags(trim($_GET['id_cats'])) : "";
    $query = mysql_query("SELECT * FROM `item` where `item`.`id_category` = '$id_cats' AND `item`.`level` = '0' order by `id_item` DESC LIMIT $start,$perhalaman;");

    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `item` WHERE `item`.`id_category` = '$id_cats' AND `item`.`level` = '0' order by `item`.`id_item` DESC"));

    $key = 0;
    while ($row_item = mysql_fetch_array($query)) {
        $id_item = $row_item['id_item'];
        $row = mysql_query("SELECT * FROM  `photo` WHERE `id_item` = '$id_item' order by `id_item`;");
        $row_photo = mysql_fetch_array($row);
        $rows = mysql_num_rows($row);
        $photo = $row_photo["photo"];

        $content .= '
                                                    <li class="isotope-item product has-post-thumbnail">
                                                        <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                            <div class="image_wrapper">
                                                                <a href="detail_product.php?id=' . $row_item["id_item"] . '">
                                                                    <div class="mask"></div>';
        if ($photo == "") {
            $content .= '<img src="../admin/images/no-images.jpg" alt="' . $row_item['title'] . '" class="scale-with-grid wp-post-image">';
        } else {
            $content .= '
                                                                    <img src="../admin/images/product/600/thumb_' . $row_photo['photo'] . '" alt="' . $row_item['title'] . '" class="scale-with-grid wp-post-image">';
        }

        $content .= '
                                                                </a>
                                                                <div class="image_links double">
                                                                    <a rel="nofollow" href="" data-quantity="1" data-product_id="76" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                </div>
                                                            </div><a href="detail_product.php?id=' . $row_item["id_item"] . '"><span class="product-loading-icon added-cart"></span></a>
                                                        </div>
                                                        <div class="desc">
                                                            <h4><a href="detail_product.php?id=' . $row_item["id_item"] . '">' . $row_item['title'] . '</a></h4>
                                                            
                                                            <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . (($currency_code == CURRENCY_USD_CODE) ? round($row_item['price'], 2) : number_format(($row_item['price'] * $USDtoIDR), 2, '.', ',')) . '</span>
                                                            </span>
                                                        </div>
                                                        <div class="desc">
                                                            <form method="post" action="">
                                                                <input type="text" id="quantity' . $key . '" value="1" size="2">
                                                                <button class="button" type="button" id="add_cart' . $key . '" onclick="add_to_cart(' . $row_item["id_item"] . ', ' . $key . ')" value="">add to cart</button>
                                                            </form>
                                                        </div>
                                                    </li>';
        $key++;
    }

} else {

    $sql_item = mysql_query("SELECT *  FROM `item` WHERE `item`.`level` = '0' 
						    Order by `item`.`id_item` DESC LIMIT $start,$perhalaman;");

    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `item` WHERE `item`.`level` = '0' order by `item`.`id_item` DESC"));

    $key = 0;
    while ($row_item = mysql_fetch_array($sql_item)) {
        $id_item = $row_item['id_item'];
        $row = mysql_query("SELECT * FROM  `photo` WHERE `id_item` = '$id_item' order by `id_item`;");
        $row_photo = mysql_fetch_array($row);
        $rows = mysql_num_rows($row);
        $photo = $row_photo["photo"];

        $content .= '
                                                    <li class="isotope-item product has-post-thumbnail">
                                                        <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                            <div class="image_wrapper">
                                                                <a href="detail_product.php?id=' . $row_item["id_item"] . '">
                                                                    <div class="mask"></div>';
        if ($photo == "") {
            $content .= '<img src="../admin/images/no-images.jpg" alt="' . $row_item['title'] . '" class="scale-with-grid wp-post-image">';
        } else {
            $content .= '
                                                                    <img src="../admin/images/product/600/thumb_' . $row_photo['photo'] . '" alt="' . $row_item['title'] . '" class="scale-with-grid wp-post-image">';
        }

        $content .= '
                                                                </a>
                                                                <div class="image_links double">
                                                                    <a rel="nofollow" href="" data-quantity="1" data-product_id="76" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                </div>
                                                            </div><a href="detail_product.php?id=' . $row_item["id_item"] . '"><span class="product-loading-icon added-cart"></span></a>
                                                        </div>
                                                        <div class="desc">
                                                            <h4><a href="detail_product.php?id=' . $row_item["id_item"] . '">' . $row_item['title'] . '</a></h4>
                                                            
                                                            <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . (($currency_code == CURRENCY_USD_CODE) ? round($row_item['price'], 2) : number_format(($row_item['price'] * $USDtoIDR), 2, '.', ',')) . '</span>
                                                            </span>
                                                        </div>
                                                        <div class="desc">
                                                            <form method="post" action="">
                                                                <input type="text" id="quantity' . $key . '" value="1" size="2">
                                                                <button class="button" type="button" id="add_cart' . $key . '" onclick="add_to_cart(' . $row_item["id_item"] . ', ' . $key . ')" value="">add to cart</button>
                                                            </form>
                                                        </div>
                                                    </li>';
        $key++;
    }
}

$content .= '
                                        </ul>
                                    </div>
                                    ';

// Set param
$param = '?';
$param .= (isset($_GET['id_cat']) ? ('id_cat=' . $_GET['id_cat'] . '&') : '');
$param .= (isset($_GET['id_cats']) ? ('id_cats=' . $_GET['id_cats'] . '&') : '');
$param .= (isset($_GET['v']) ? ('v=' . $_GET['v'] . '&') : '');

$content .= '' . (halaman($sql_total_data, $perhalaman, 1, $param)) . '';

$content .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
$plugin = '

<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script type="text/javascript" src="js/notification/notify.js"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag("js", new Date());

    gtag("config", "UA-131936719-1");
  
      function changeCurrency(id_cat) {
          
          var currency_code = jQuery("#currency_code").val();
          
          jQuery.ajax({
            type: "POST",
            url: "member/update_currency.php",
            data: {currency_code: currency_code},
            dataType: "json",
            success: function(data) {
                if (data.status == "error") {
                    alert(data.msg);
                } else {
                    document.location.reload();
                }
            }
          });
    }
    
    function add_to_cart(id_item, key) {
          
        var add_cart_button = jQuery("#add_cart" + key);
        var quantity = jQuery("#quantity" + key).val();
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
              console.log(data);
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
