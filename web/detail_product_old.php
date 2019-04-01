<?php 

session_start();

include ("config/configuration.php");
include ("config/template_detail.php");


if(isset($_GET['id'])){
  
    $id_item       = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
    $query        = "SELECT `item`.* FROM `item` WHERE `item`.`level` = '0' AND `item`.`id_item` = '$id_item' ;";
    $id           = mysql_query($query);
    $data_item    = mysql_fetch_array($id);
    $rows         = mysql_fetch_array(mysql_query("SELECT `photo`.* from `photo` where `photo`.`id_item` = '$id_item' AND `photo`.`level` = '0'; "));
    $photo        = isset($_GET['id']) ? strip_tags(trim($rows["photo"])) : "";
}

$titlebar = "Detail Product ".$title;
$menu 	  = "";

$content ='<div id="Subheader" style="padding:50px 0px 30px;">
                <div class="container">
                    <div class="column one">
                        <h2 class="title">'.(isset($_GET['id']) ? strip_tags(trim($data_item["title"])) : "").'</h2>
                    </div>
                </div>
            </div>
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
                                                <span class="onsale"><i class="icon-star"></i></span>
                                                <div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 0; transition: opacity .25s ease-in-out;">
                                                    <figure class="woocommerce-product-gallery__wrapper">
                                                        <div class="woocommerce-product-gallery__image">';
                                                        if($photo == ""){
                                                            $content .='<a href="../admin/images/no-images.jpg">
                                                            	<img src="../admin/images/no-images.jpg" class="attachment-shop_single size-shop_single wp-post-image">
                                                            </a>';
                                                        } else {
                                                            $content .='<a href="../admin/images/product/'.(isset($_GET['id']) ? strip_tags(trim($rows["photo"])) : "").'">
                                                            	<img src="../admin/images/product/'.(isset($_GET['id']) ? strip_tags(trim($rows["photo"])) : "").'" class="attachment-shop_single size-shop_single wp-post-image">
                                                            </a>';
                                                        }
                                                            $content .='
                                                        </div>
                                                    </figure>
                                                </div>
                                            </div>
                                            <div class="summary entry-summary column one-second">
                                                
                                                
                                                
                                                <p class="price">
                                                    <del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"></span></span></del><ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#36;</span>'.(isset($_GET['id']) ? strip_tags(trim($data_item["price"])) : "").'</span></ins>
                                                </p><br><br>
                                                
                                               <form method="post" action="cart.php?code='.$data_item['code'].'">
                                                    <div class="quantity">
                                                        <label class="screen-reader-text" for="quantity_5a12b05791970">Quantity</label>
                                                        <input type="number" id="quantity_5a12b05791970" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4"  pattern="[0-9]*" inputmode="numeric" />
                                                        <input type="hidden" name="title" value="'.(isset($_GET['id']) ? strip_tags(trim($data_item["title"])) : "").'">
                                                        <input type="hidden" name="code" value="'.(isset($_GET['id']) ? strip_tags(trim($data_item["code"])) : "").'">
                                                        <input type="hidden" name="price" value="'.(isset($_GET['id']) ? strip_tags(trim($data_item["price"])) : "").'">
                                                    </div>
                                                    <button type="submit" name="add-to-cart" value="70" class="single_add_to_cart_button button alt">
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
                                                    </p>
                                                    <h3>DESCRIPTION
														</h3>
                                                    <p>
                                                        '.(isset($_GET['id']) ? strip_tags(trim($data_item["description"])) : "").'
                                                </div>
                                                </p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';

$plugin ='';
$template = admin_template($content,$titlebar,$menu,$plugin);
echo $template;           

?>