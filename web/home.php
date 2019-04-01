<?php 
include("config/configuration.php");
include("config/template_home1.php");

$titlebar = "Dashboard";
$menu = "";

$sql_item 	= mysql_query("SELECT *  FROM `item` WHERE `item`.`level` = '0' 
						    Order by `item`.`id_item` DESC limit 0, 4;");

$content ='<div class="mfn-main-slider" id="mfn-rev-slider" >
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                          <li data-target="#myCarousel" data-slide-to="1"></li>
                          <li data-target="#myCarousel" data-slide-to="2"></li>
                          <li data-target="#myCarousel" data-slide-to="3"></li>
                        </ol>
                    
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">                          
                          <div class="item active">
                            <a href="list_product.php?id_cats=9" target="_blank"><img src="images/slider/1.jpg" alt="" style="width:100%;"></a>
                          </div>
                    
                          <div class="item">
                            <a href="list_product.php?id_cats=8" target="_blank"><img src="images/slider/2.jpg" alt="" style="width:100%;"></a>
                          </div>
                        
                          <div class="item">
                            <a href="list_product.php?id_cats=7" target="_blank"><img src="images/slider/3.jpg" alt="" style="width:100%;"></a>
                          </div>
                          
                          <div class="item">
                            <a href="custom" target="_blank"><img src="images/slider/4.jpg" alt="" style="width:100%;"></a>
                          </div>
                          
                        </div>
                    
                        <!-- Left and right controls -->
                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                          <span class="glyphicon glyphicon-chevron-left"></span>
                          <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                          <span class="glyphicon glyphicon-chevron-right"></span>
                          <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
<div id="Content">
            <!--<div class="content_wrapper clearfix">
                <div class="sections_group">
                    <div class="entry-content">
                        <div class="section mcb-section dark bg-cover" style="padding-top:50px; padding-bottom:150px; background-color:#0f0f22; background-image:url(images/dive.jpg); background-repeat:no-repeat; background-position:center top;">
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
                                        
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix align_center" style=" margin-top:-20px;">FOR HIM - FOR HER</div>
                                        </div>
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
                        </div>-->
                        
                        <br>
                        <div class="section mcb-section" style="padding-top:0px; padding-bottom:10px;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one valign-top clearfix" style="margin-top:0px">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column  column-margin-0px">
                                            <div class="column_attr clearfix align_center">
                                                <!--<h3 style="color:#000">HOT ITEMS</h3>-->
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="column mcb-column one column_column  column-margin-0px">
                                            <div class="column_attr clearfix">
                                                <div class="woocommerce columns-4">
                                                    <div class="products_wrapper isotope_wrapper">
                                                        <ul class="products ">

                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="list_product.php?id_cats=9">
                                                                            <div class="mask"></div>
                                                                            <img src="images/sub/1.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                
                                                                
                                                            </li>
                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="list_product.php?id_cats=8">
                                                                            <div class="mask"></div>
                                                                            <img src="images/sub/2.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        </div>
                                                                    </div>
                                                                  
                                                                
                                                                
                                                            </li>
                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="list_product.php?id_cat=15">
                                                                            <div class="mask"></div>
                                                                            <img src="images/sub/3.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        </div>
                                                                    </div>
                                                                    

                                                            </li>
                                                            <li class="isotope-item product has-post-thumbnail">
                                                                <div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle("hover");">
                                                                    <div class="image_wrapper">
                                                                        <a href="list_product.php?id_cats=7">
                                                                            <div class="mask"></div>
                                                                            <img src="images/sub/4.jpg" class="scale-with-grid wp-post-image">
                                                                        </a>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                
                                                            </li>
                                                            
                                                           
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                       <!-- <div class="column mcb-column one column_column  column-margin-0px">
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
                                        </div>-->
                                        
                                    <!--<div class="column mcb-column one column_column  column-margin-0px">
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
                                        </div>-->
                                    </div>
                                </div>
                            </div>
 
                        </div>
                        <div><a href="list_product.php?id_cats=9"><img src="images/banner/1.jpg"  height="50%"></a>
                        </div>
                        <div><a href="list_product.php?id_cats=8"><img src="images/banner/2.jpg"  height="50%"></a>
                        
                        <br><br><div align="center" style="margin-bottom:-15px"><h6 style="color:#9DB8C6;"><strong>Check us on Instagram</strong></h6></div><br>
                        </div>
                        
                          
                        <!-- LightWidget WIDGET NEW--> <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/9668acebf2ad5d08a2e45e14bcd73330.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width:100%;border:0;overflow:hidden;"></iframe>
   
                        <br>
                        
                        <a href="blog.php"><img src="images/slider/1000/Blogpost.jpg"  height="50%"></a>
                        
                        
                        <!-- LightWidget WIDGET lama <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/44d586157e885018b3f31d7805e8454e.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width:100%;border:0;overflow:hidden;"></iframe>-->
                           
                           
                         
                        <!-- LightWidget WIDGET NEW <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/9668acebf2ad5d08a2e45e14bcd73330.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width:100%;border:0;overflow:hidden;"></iframe>-->
   
                           
                                        
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

$plugin = '
    <link rel="stylesheet" href="plugins/bootstrap-slider/css/bootstrap.min.css">
    <script src="plugins/bootstrap-slider/js/jquery.min.js"></script>
    <script src="plugins/bootstrap-slider/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="plugins/bootstrap-slider/css/customer.css">
    
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="css/paging.css">
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());

  gtag("config", "UA-131936719-1");
</script>
';

$template = admin_template($content,$titlebar,$titlepage="",$user="",$menu,$plugin);
echo $template;


?>