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
<div class="column one">
                            <div class="top_bar_left clearfix">
                                <div class="logo">
                                    <a id="logo" href="index.php" title="Sea Gods" data-height="60" data-padding="0"><img class="logo-main scale-with-grid" src="images/logo.png" data-retina="images/logo.png" data-height="21" alt="sea gods"><img class="logo-sticky scale-with-grid" src="images/logo.png" data-retina="images/logo.png" data-height="21" alt="sea gods"><img class="logo-mobile scale-with-grid" src="images/logo.png" data-retina="images/logo.png" data-height="21" alt="sea gods"><img class="logo-mobile-sticky scale-with-grid" src="images/logo.png" data-retina="images/logo.png" data-height="21" alt="sea gods">
                                    </a>
                                </div>
                                <div class="menu_wrapper">
                                    <nav id="menu">
                                        <ul id="menu-main-menu" class="menu menu-main">
                                            <li class="current-menu-item"><a href="index.php"><span>Home</span></a></li>
                                            <li><a href="about_us.php"><span>About Us</span></a></li>
                                            <li><a href="buyers_guide.php"><span>Guide</span></a></li>
                                            <li><a href="list_product.php?id_cats=9"><span>Collection</span></a></li>
                                            <li><a href="custom/"><span>Custom Wetsuit</span></a></li>
                                            <li><a href="blog.php"><span>Blog</span></a></li>
                                            <li><a href="member/index.php" target="_blank"><span>Login</span></a></li>
                                            
                                        </ul>
                                    </nav>
                                    <a class="responsive-menu-toggle" href="#"><i class="icon-menu-fine"></i></a>
                                </div>
                            </div>
                            <div class="top_bar_right">
                                <div class="top_bar_right_wrapper"><a id="header_cart" href="cart.php"><i class="icon-bag-fine"></i><span id="notify_value">0</span></a>
                                </div>
                            </div>
                        </div>
';
  
  
$template = '
<!DOCTYPE html>
<html class="no-js">
<head>

    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>'.$titlebar.' Sea Gods Wetsuit</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <!-- Favicons -->
    <link rel="shortcut icon" href="images/favicon.ico">

    <!-- FONTS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Patua+One:100,300,400,400italic,700">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic,900">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Montserrat:100,300,400,400italic,500,700,700italic">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Asap+Condensed:100,300,400,400italic,500,700,700italic">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:100,300,400,400italic,500,700,700italic">

    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/structure.css">
    <link rel="stylesheet" href="css/denim.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="css/paging.css">

    <!-- Revolution Slider -->
    <link rel="stylesheet" href="plugins/rs-plugin/css/settings.css">

	'.$plugin.'
  </head>
  <body class="template-slider color-custom style-simple button-flat layout-full-width if-zoom if-border-hide no-shadows header-classic minimalist-header sticky-header sticky-tb-color ab-hide subheader-both-center menu-link-color menuo-no-borders menuo-right mobile-tb-hide mobile-side-slide mobile-mini-mr-ll tablet-sticky mobile-sticky be-reg-2062">
    <!-- START HEADER -->
     <div id="Wrapper">
         <div id="Header_wrapper">
            <header id="Header">
                <div class="header_placeholder"></div>
                <div id="Top_bar">
                    <div class="container">
                      '.$menu.'
                    </div>
                </div>
            </header>
        </div>
        <section class="slide1">
        <div class="wrap-slick1">
            <div class="slick1">
                <div class="item-slick1 item1-slick1" style="background-image: url(images/b1.jpg);">
                    <div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
                        <!--<span class="caption1-slide1 m-text1 t-center animated visible-false m-b-15" data-appear="fadeInDown">
                            Women Collection 2018
                        </span>

                        <h2 class="caption2-slide1 xl-text1 t-center animated visible-false m-b-37" data-appear="fadeInUp">
                            New arrivals
                        </h2>

                        <div class="wrap-btn-slide1 w-size1 animated visible-false" data-appear="zoomIn">
                            <!-- Button
                            <a href="product.html" class="flex-c-m size2 bo-rad-23 s-text2 bgwhite hov1 trans-0-4">
                                Shop Now
                            </a>
                        </div>-->
                    </div>
                </div>

                <div class="item-slick1 item2-slick1" style="background-image: url(images/b2.jpg);">
                    <div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
                        
                    </div>
                </div>

                <div class="item-slick1 item3-slick1" style="background-image: url(images/b3.jpg);">
                    <div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
                        
                    </div>
                </div>

            </div>
        </div>
    </section>
    '.$content.' 
    
        <footer id="Footer" class="clearfix">
            <div class="widgets_wrapper" style="padding:70px 0">
                <div class="container">
                    <div class="column one-fourth">
                        <aside class="widget_text widget widget_custom_html">
                            <div class="textwidget custom-html-widget">
                                <br> <a class="button button_size_2 button_js" href="https://bit.ly/SeaGodsWetsuit" target="_blank" style=" background-color:#45b5e9 !important; color:#000;"><span class="button_label">Whatsapp</span></a>
                            </div>
                        </aside>
                    </div>
                    <div class="column one-fourth">
                        <aside class="widget_text widget widget_custom_html">
                            <h4>SeaGods Wetsuit</h4>
                            <div class="textwidget custom-html-widget">
                                <ul class="list-footer">
                                    <li><i class="icon-dot themecolor"></i><a href="about_us.php">Company Profile</a>
                                    </li>
                                    <li><i class="icon-dot themecolor"></i><a href="production.php">Production</a>
                                    </li>
                                    <li><i class="icon-dot themecolor"></i><a href="buyers_guide.php">Buyers Guide</a>
                                    </li>
                                    <li><i class="icon-dot themecolor"></i><a href="payment_guide.php">Payment Guide</a>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                    <div class="column one-fourth">
                        <aside class="widget_text widget widget_custom_html">
                            <h4>Office & Store</h4>
                            <div class="textwidget custom-html-widget">
                                
                                <div class="clearfix" style="margin-bottom: 6px;">
                    <div class="pull-left" style="width: 7%;">
                      <i class="fa fa-fw fa-home" style="top: 0; color: #FFF;"></i>
                    </div>
                    <div class="pull-left" style="width: 93%;">
                      By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, <br>Bali - Indonesia,  The #BigBlue                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 6px;">
                    <div class="pull-left" style="width: 7%;">
                      <i class="fa fa-fw fa-phone" style="top: 0; color: #FFF;"></i>
                    </div>
                    <div class="pull-left" style="width: 93%;">
                      Phone +62 361 27 11 99 <br>Whatsapp +62 8 11 534 9005                    </div>
                  </div>
                  <div class="clearfix" style="margin-bottom: 6px;">
                    <div class="pull-left" style="width: 7%;">
                      <i class="fa fa-fw fa-envelope" style="top: 0; color: #FFF;"></i>
                    </div>
                    <div class="pull-left" style="width: 93%;">
                      <a href="mailto:info@seagodswetsuit.com">info@seagodswetsuit.com </a>                    </div>
                  </div>
                                
                                
                                
                            </div>
                        </aside>
                    </div>
                    <div class="column one-fourth">
                        <aside id="media_image-2" class="widget widget_media_image"><br><br>
                             <!--<h4>About Us</h4>-->
                            <img src="images/blog1.jpg" class="image wp-image-126  attachment-full size-full" style="max-width: 100%; height: auto;">
                        </aside>
                    </div>
                </div>
            </div>
            <div class="footer_copy">
                <div class="container">
                    <div class="column one">
                         <a id="back_to_top" class="button button_js" href="#"><i class="icon-up-open-big"></i></a>
                        <div class="copyright"> &copy; 2019. SeaGods Wetsuit </div>

                    </div>
                </div>
            </div>
        </footer>
    </div>
                 
            
    <div id="Side_slide" class="right dark" data-width="250">
        <div class="close-wrapper"><a href="#" class="close"><i class="icon-cancel-fine"></i></a>
        </div>
        <div class="extras">
            <div class="extras-wrapper">
                <a class="icon cart" id="slide-cart" href="cart.php"><i class="icon-bag-fine"></i><span>0</span></a>
            </div>
        </div>
        <div class="menu_wrapper"></div>
    </div>
    <div id="body_overlay"></div>


    <!-- JS -->
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/mfn.menu.js"></script>
    <script src="js/jquery.plugins.js"></script>
    <script src="js/jquery.jplayer.min.js"></script>
    <script src="js/animations/animations.js"></script>
    <script src="js/translate3d.js"></script>
    <script src="js/scripts.js"></script>
    <script src="plugins/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
    <script src="plugins/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.video.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.migration.min.js"></script>
    <script src="plugins/rs-plugin/js/extensions/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript" src="vendor/slick/slick.min.js"></script>
    <script type="text/javascript" src="js/slick-custom.js"></script>

    <script src="js/email.js"></script>
    <script>
        var revapi1, tpj = jQuery;
        tpj(document).ready(function() {
            if (tpj("#rev_slider_1_1").revolution == undefined) {
                revslider_showDoubleJqueryError("#rev_slider_1_1");
            } else {
                revapi1 = tpj("#rev_slider_1_1").show().revolution({
                    sliderType: "standard",
                    sliderLayout: "fullscreen",
                    dottedOverlay: "none",
                    delay: 9000,
                    navigation: {
                        onHoverStop: "off",
                    },
                    visibilityLevels: [1240, 1024, 778, 480],
                    gridwidth: 1240,
                    gridheight: 868,
                    lazyType: "none",
                    shadow: 0,
                    spinner: "spinner2",
                    stopLoop: "off",
                    stopAfterLoops: -1,
                    stopAtSlide: -1,
                    shuffle: "off",
                    autoHeight: "off",
                    fullScreenAutoWidth: "off",
                    fullScreenAlignForce: "off",
                    fullScreenOffsetContainer: "",
                    fullScreenOffset: "",
                    disableProgressBar: "on",
                    hideThumbsOnMobile: "off",
                    hideSliderAtLimit: 0,
                    hideCaptionAtLimit: 0,
                    hideAllCaptionAtLilmit: 0,
                    debugMode: false,
                    fallbacks: {
                        simplifyAll: "off",
                        nextSlideOnWindowFocus: "off",
                        disableFocusListener: false,
                    }
                });
            }
        });
        
        function notification() {
            console.log("testing");
            jQuery.ajax({
                type: "POST",
                url: "notification.php",
                data: {action: "notification"},
                dataType: "json",
                success: function(data) {
                    console.log(data);
                }
            });
        }
        
    </script>
	
  </body>
</html>


';

return $template;
}

?>