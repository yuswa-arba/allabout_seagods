<?php 

include("config/configuration.php");
include("config/template_home1.php");

$titlebar = "Dashboard";
$menu = "";

$sql_item 	= mysql_query("SELECT *  FROM `item` WHERE `item`.`level` = '0' 
						    Order by `item`.`id_item` DESC limit 0, 4;");

$content ='<div class="mfn-main-slider" id="mfn-rev-slider">
                    <div id="rev_slider_1_1_wrapper" class="rev_slider_wrapper fullscreen-container" data-source="gallery" style="background:transparent;padding:0px;">
                        <div id="rev_slider_1_1" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.4.6">
                           <ul><li data-index="rs-1" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="100" data-rotate="0" data-saveperformance="off" data-title="" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description=""> <img src="images/slider//1000/Accessories.jpg" title="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
                                </li>
                                <li data-index="rs-2" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="100" data-rotate="0" data-saveperformance="off" data-title="" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description=""> <img src="images/slider/1000/Customize.jpg" title="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
                                </li>
                                <li data-index="rs-3" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="100" data-rotate="0" data-saveperformance="off" data-title="" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description=""> <img src="images/slider/1000/rashguard.jpg" title="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
                                </li>
                                <li data-index="rs-4" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="100" data-rotate="0" data-saveperformance="off" data-title="" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description=""> <img src="images/slider/1000/Wetsuit.jpg" title="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
                                </li>
                            </ul>
                            <div class="tp-static-layers">
                                </div>
                        </div>
                    </div>
                </div>
<div id="Content">


<div class="section mcb-section" style="padding-top:0px; padding-bottom:50px;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one valign-top clearfix" style="margin-top:0px">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column  column-margin-0px">
                                            <div class="column_attr clearfix align_center">
                                                <h3 style="color:#00000">HOT ITEMS</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        <!--<div class="column mcb-column one column_column  column-margin-0px">
                                            <div class="column_attr clearfix">
                                                <div class="woocommerce columns-4">
                                                    <div class="products_wrapper isotope_wrapper">
                                                        <ul class="products ">

                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="product_page.html">
                                                                            <div class="mask"></div>
                                                                            <img src="images/item1.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        <div class="image_links double"><a rel="nofollow" href="#" data-quantity="1" data-product_id="99" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <a href="product_page.html"><span class="product-loading-icon added-cart"></span></a>
                                                                </div>
                                                                <div class="desc">
                                                                    <h4><a href="product_page.html">Wetsuit</a></h4>
                                                                    <div class="star-rating"><span style="width:90%">Rated <strong class="rating">4.50</strong> out of 5</span>
                                                                    </div>
                                                                    <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#36;</span>3.00</span>
                                                                    </span>
                                                                </div>
                                                            </li>
                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="product_page.html">
                                                                            <div class="mask"></div>
                                                                            <img src="images/item2.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        <div class="image_links double"><a rel="nofollow" href="#" data-quantity="1" data-product_id="96" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <a href="product_page.html"><span class="product-loading-icon added-cart"></span></a>
                                                                </div>
                                                                <div class="desc">
                                                                    <h4><a href="product_page.html">Wetsuit</a></h4>
                                                                    <div class="star-rating"><span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5</span>
                                                                    </div>
                                                                    <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#36;</span>9.00</span>
                                                                    </span>
                                                                </div>
                                                            </li>
                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="product_page.html">
                                                                            <div class="mask"></div>
                                                                            <img src="images/item3.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        <div class="image_links double"><a rel="nofollow" href="#" data-quantity="1" data-product_id="93" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <a href="product_page.html"><span class="product-loading-icon added-cart"></span></a>
                                                                </div>
                                                                <div class="desc">
                                                                    <h4><a href="product_page.html">Wetsuit</a></h4>
                                                                    <div class="star-rating"><span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5</span>
                                                                    </div>
                                                                    <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#36;</span>3.00</span>
                                                                    </span>
                                                                </div>
                                                            </li>
                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="product_page.html">
                                                                            <div class="mask"></div>
                                                                            <img src="images/item4.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        <div class="image_links double"><a rel="nofollow" href="#" data-quantity="1" data-product_id="90" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <a href="product_page.html"><span class="product-loading-icon added-cart"></span></a>
                                                                </div>
                                                                <div class="desc">
                                                                    <h4><a href="product_page.html">Wetsuit</a></h4>
                                                                    <div class="star-rating"><span style="width:60%">Rated <strong class="rating">3.00</strong> out of 5</span>
                                                                    </div>
                                                                    <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#36;</span>9.00</span>
                                                                    </span>
                                                                </div>
                                                            </li>
                                                            
                                                           
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                        <div class="column mcb-column one column_column  column-margin-0px">
                                            <div class="column_attr clearfix">
                                                <div class="woocommerce columns-4">
                                                    <div class="products_wrapper isotope_wrapper">
                                                        <ul class="products ">';
                                                            while ($row_item = mysql_fetch_array($sql_item)){
                                                                $id_item    = $row_item['id_item'];
                                                                $row        = mysql_query("SELECT * FROM  `photo` WHERE `id_item` = '$id_item' order by `id_item` DESC;");
                                                                $row_photo  = mysql_fetch_array($row);
                                                                $rows       = mysql_num_rows($row); 
                                                                $photo      = $row_photo["photo"];
                                                                
                                                                $content .='
                                                                    <li class="isotope-item product has-post-thumbnail">
                                                                        <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                            <div class="image_wrapper">
                                                                                <a href="product_page.html">
                                                                                    <div class="mask"></div>';
                                                                                    
                                                                                    if($photo == "" ){
                                                                                        $content .= '<img src="../admin/images/no-images.jpg" class="scale-with-grid wp-post-image">';
                                                                                    } else {
                                                                                        $content .= '<img src="../admin/images/product/150/thumb_'.$row_photo['photo'].'" class="scale-with-grid wp-post-image">';
                                                                                    }
                                                                                    
                                                                                    $content .='
                                                                                </a>
                                                                                <div class="image_links double"><a rel="nofollow" href="#" data-quantity="1" data-product_id="99" class="add_to_cart_button ajax_add_to_cart product_type_simple"><i class="icon-basket"></i></a><a class="link" href="product_page.html"><i class="icon-link"></i></a>
                                                                                </div>
                                                                            </div>
                                                                            <a href="product_page.html"><span class="product-loading-icon added-cart"></span></a>
                                                                        </div>
                                                                        <div class="desc">
                                                                            <h4><a href="detail_product.php?id='.$row_item["id_item"].'">'.$row_item['title'].'</a></h4>
                                                                            <div class="star-rating"><span style="width:90%">Rated <strong class="rating">4.50</strong> out of 5</span>
                                                                            </div>
                                                                            <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#36;</span>'.$row_item['price'].'</span>
                                                                            </span>
                                                                        </div>
                                                                    </li>';
                                                            }
                                                            $content .='
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one column_button">
                                            <div class="button_align align_center"><a class="button  button_size_4 button_js" href="list_product.php" style="background-color:#45b5e9 !important; color:#0f0f22;"><span class="button_label">View more</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




            <div class="content_wrapper clearfix">
                <div class="sections_group">
                    <div class="entry-content">
                        <div class="section mcb-section dark bg-cover" style="padding-top:0px; padding-bottom:150px; background-color:#0f0f22; background-image:url(images/dive.jpg); background-repeat:no-repeat; background-position:center top;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one valign-top clearfix" style="margin-top:-150px">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_image">
                                            <div class="image_frame image_item no_link scale-with-grid aligncenter no_border">
                                                <div class="image_wrapper">
                                                    <img class="scale-with-grid" src="images/denim-home-lineup.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap mcb-wrap one valign-top clearfix" style="margin-top:-20px">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column  column-margin-0px">
                                            <div class="column_attr clearfix align_center">
                                                <a href="list_product.php" target="_blank" ><h2>VIEW OUR COLLECTION</a></h2>
                                            </div>
                                        </div>
                                        
                                        <!--<div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix align_center" style=" margin-top:-20px;">FOR HIM - FOR HER</div>
                                        </div>-->
                                        <div class="column mcb-column one column_image">
                                            <div class="image_frame image_item no_link scale-with-grid aligncenter no_border">
                                                <div class="image_wrapper">
                                                    <img class="scale-with-grid" src="images/denim-home-linedown.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap mcb-wrap one-fifth valign-top clearfix">
                                    <div class="column mcb-column one column_image">
                                            <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" style="margin-bottom:-15px">
                                                <div class="image_wrapper">
                                                    <img class="scale-with-grid" src="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix align_center" style="color:red">
                                                <h6 style="color:#45b5e9;"></h6>
                                            </div></div>
                                </div>
                                <div class="wrap mcb-wrap one-fifth valign-top clearfix">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_image">
                                            <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" style="margin-bottom:-15px">
                                                <div class="image_wrapper">
                                                    <img class="scale-with-grid" src="images/wetsuit-icon.png">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix align_center" style="color:red">
                                                <h6 style="color:#45b5e9;">WETSUITS</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap mcb-wrap one-fifth valign-top clearfix">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_image">
                                            <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" style="margin-bottom:-15px">
                                                <div class="image_wrapper">
                                                    <img class="scale-with-grid" src="images/swin-icon.png">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix align_center" style="color:red">
                                                <h6 style="color:#45b5e9;">SWIMMING</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap mcb-wrap one-fifth valign-top clearfix">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_image">
                                            <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" style="margin-bottom:-15px">
                                                <div class="image_wrapper">
                                                    <img class="scale-with-grid" src="images/acces-icon.png">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix align_center" style="color:red">
                                                <h6 style="color:#45b5e9;">ACCESORIES</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap mcb-wrap one-fifth valign-top clearfix">
                                    <div class="mcb-wrap-inner">
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        <!-- LightWidget WIDGET --><script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/44d586157e885018b3f31d7805e8454e.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width:100%;border:0;overflow:hidden;"></iframe>
                                        
                        <!--<div class="section mcb-section bg-cover" style="padding-top:150px; padding-bottom:150px; background-color:#ececec; background-image:url(images/newsletter_bg.jpg); background-repeat:no-repeat; background-position:center top;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap two-fifth valign-middle bg-contain clearfix">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix align_center" style=" background-image:url("images/home_denim_newsletter_bg2_repeat.jpg"); background-repeat:repeat; background-position:center top; padding:60px 30px 20px 30px;">
                                                <h2>SIGN UP TO<br>AND GET INFO ABOUT<br>NEW PRODUCTS<br>AND<br> DISCOUNTS</h2>
                                                <br>
                                                <div id="contactWrapper">
                                                    <form id="contactform">
                                                        <!-- One Second (1/2) Column 
                                                        <div class="column one-second">
                                                            <input placeholder="Your name" type="text" name="name" id="name" size="40" aria-required="true" aria-invalid="false" />
                                                        </div>
                                                        <!-- One Second (1/2) Column 
                                                        <div class="column one-second">
                                                            <input placeholder="Your e-mail" type="email" name="email" id="email" size="40" aria-required="true" aria-invalid="false" />
                                                        </div>
                                                        <div class="column one">
                                                            <input placeholder="Subject" type="text" name="subject" id="subject" size="40" aria-invalid="false" />
                                                        </div>
                                                        <div class="column one">
                                                            <textarea placeholder="Message" name="body" id="body" style="width:100%;" rows="10" aria-invalid="false"></textarea>
                                                        </div>
                                                        <div class="column one">
                                                            <input type="button" value="Send A Message" id="submit" onClick="return check_values();">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->

                    </div>
                </div>
            </div>
';

$plugin ='
';
$template = admin_template($content,$titlebar,$menu,$plugin);
echo $template;


?>