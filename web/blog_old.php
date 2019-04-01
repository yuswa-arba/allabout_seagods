<?php 
session_start();

include ("config/configuration.php");
include ("config/template_blog.php");


$query = mysql_query("Select about from pages");
$data = mysql_fetch_assoc($query);
$about = $data['about'];

$titlebar = "Blog ".$title;
$menu 	  = "";

$content ='<!--<div id="Subheader" style="padding:50px 0px 30px;">
                <div class="container">
                    <div class="column one">
                        <h2 class="title">   Company Profile </h2>
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
                                    
                                    
                                       <div class="post-content">
                                        <h3 class="title">   </h3>
                                          <!--'.$about.'-->
                                          
                                          
                        <div class="section mcb-section" style="padding-top:0px; padding-bottom:20px">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one valign-top clearfix">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one-third column_column">
                                            <div class="column_attr clearfix" style=" padding:15px">
                                                <a href="b.php" target="_blank"><h3>Judul maks 22 karakter ...</h3></a>
                                                <h7 style="font-size:10px;" align="center">October 25th, 2018</h7>
                                                <div><img src="images/blog/b2.jpg" height="100px"></div>
                                                <p> 
                                                   Tampilan isi artikel dibatasi 100 karakter pertama lalu dikasih ...
                                                </p>
                                                <a class="button button_size_2 button_js" href="b.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one-third column_column">
                                            <div class="column_attr clearfix" style=" padding:15px">
                                                <a href="b.php" target="_blank"><h3>Judul maks 22 karakter ...</h3></a>
                                                <h7 style="font-size:10px;" align="center">October 25th, 2018</h7>
                                                <div><img src="images/blog/b2.jpg" height="100px"></div>
                                                <p> 
                                                   Tampilan isi artikel dibatasi 100 karakter pertama lalu dikasih ...
                                                </p>
                                                <a class="button button_size_2 button_js" href="b.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one-third column_column">
                                            <div class="column_attr clearfix" style=" padding:15px">
                                                <a href="b.php" target="_blank"><h3>Judul maks 22 karakter ...</h3></a>
                                                <h7 style="font-size:10px;" align="center">October 25th, 2018</h7>
                                                <div><img src="images/blog/b2.jpg" height="100px"></div>
                                                <p> 
                                                   Tampilan isi artikel dibatasi 100 karakter pertama lalu dikasih ...
                                                </p>
                                                <a class="button button_size_2 button_js" href="b.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one-third column_column">
                                            <div class="column_attr clearfix" style=" padding:15px">
                                                <a href="b.php" target="_blank"><h3>Judul maks 22 karakter ...</h3></a>
                                                <h7 style="font-size:10px;" align="center">October 25th, 2018</h7>
                                                <div><img src="images/blog/b2.jpg" height="100px"></div>
                                                <p> 
                                                   Tampilan isi artikel dibatasi 100 karakter pertama lalu dikasih ...
                                                </p>
                                                <a class="button button_size_2 button_js" href="b.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one-third column_column">
                                            <div class="column_attr clearfix" style=" padding:15px">
                                                <a href="b.php" target="_blank"><h3>Judul maks 22 karakter ...</h3></a>
                                                <h7 style="font-size:10px;" align="center">October 25th, 2018</h7>
                                                <div><img src="images/blog/b2.jpg" height="100px"></div>
                                                <p> 
                                                   Tampilan isi artikel dibatasi 100 karakter pertama lalu dikasih ...
                                                </p>
                                                <a class="button button_size_2 button_js" href="b.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                        <div class="column mcb-column one-third column_column">
                                            <div class="column_attr clearfix" style=" padding:15px">
                                                <a href="b.php" target="_blank"><h3>Judul maks 22 karakter ...</h3></a>
                                                <h7 style="font-size:10px;" align="center">October 25th, 2018</h7>
                                                <div><img src="images/blog/b2.jpg" height="100px"></div>
                                                <p> 
                                                   Tampilan isi artikel dibatasi 100 karakter pertama lalu dikasih ...
                                                </p>
                                                <a class="button button_size_2 button_js" href="b.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                    <center><h3>sistem paging copy dari list produk</h3></center>
                                </div>
                            </div>
                        </div>
                                          
                                          
                                          
                                          
                                          
                                          
                       <!-- <div class="section mcb-section equal-height-wrap" style="padding-top:0px; padding-bottom:0px">
                            <div class="section">
                                <div class="wrap">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix" style=" padding:0 20px">
                                                <h2>Lorem ipsum dolor mit samet<br> et omni quantum</h2>
                                                <hr class=" hr_color" style="margin: 0 auto 30px">
                                                <p>Praesent nec magna ac pede. Mauris suscipit mauris. Nam quis erat id tortor. Phasellus at nibh nulla nulla, egestas quam eu tortor orci, id eros. Mauris neque. Pellentesque dolor. Mauris in est. Vivamus lacus sed justo. Aenean ac dignissim nibh.</p>
                                                <a class="button button_size_2 button_js" href="about_us.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section mcb-section equal-height-wrap" style="padding-top:0px; padding-bottom:0px">
                            <div class="section">
                                <div class="wrap">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix" style=" padding:0 20px">
                                                <h2>Lorem ipsum dolor mit samet<br> et omni quantum</h2>
                                                <hr class=" hr_color" style="margin: 0 auto 30px">
                                                <p>Praesent nec magna ac pede. Mauris suscipit mauris. Nam quis erat id tortor. Phasellus at nibh nulla nulla, egestas quam eu tortor orci, id eros. Mauris neque. Pellentesque dolor. Mauris in est. Vivamus lacus sed justo. Aenean ac dignissim nibh.</p>
                                                <a class="button button_size_2 button_js" href="about_us.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section mcb-section equal-height-wrap" style="padding-top:0px; padding-bottom:0px">
                            <div class="section">
                                <div class="wrap">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix" style=" padding:0 20px">
                                                <h2>Lorem ipsum dolor mit samet<br> et omni quantum</h2>
                                                <hr class=" hr_color" style="margin: 0 auto 30px">
                                                <p>Praesent nec magna ac pede. Mauris suscipit mauris. Nam quis erat id tortor. Phasellus at nibh nulla nulla, egestas quam eu tortor orci, id eros. Mauris neque. Pellentesque dolor. Mauris in est. Vivamus lacus sed justo. Aenean ac dignissim nibh.</p>
                                                <a class="button button_size_2 button_js" href="about_us.php" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                                          
                                          
                                          
                                          
                                          
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