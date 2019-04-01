<?php 
session_start();

include ("config/configuration.php");
include ("config/template_guide.php");


$query = mysql_query("Select custom from pages");
$data = mysql_fetch_assoc($query);
$about = $data['custom'];

$titlebar = "Standard Size Guide";
$menu 	  = "";

$content ='<!--<div id="Subheader" style="padding:50px 0px 30px;">
                <div class="container">
                    <div class="column one">
                        <h2 class="title">   Standard Size Guide </h2>
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
                                           <!--'.$about.'-->
                                           
 
<div align="center">A full Standard Size Guide will be updated soon. Stay tuned!</div><br><br>
                     <div align="center">
                        <h3>MALE</h3>
                      </div>
                      <div class="row" style="min-height: 450px;">
                  <div class="col-sm-10 col-sm-offset-1">
                    <img src="images/size/man.jpg" alt="" class="width-100" id="sizecart-male">
                    
                  </div>
                  <br><br>
                  <div align="center">
                        <h3>FEMALE</h3>
                      </div>
                  <div class="col-sm-10 col-sm-offset-1">
                    
                    <img src="images/size/woman.jpg" alt="" class="width-100" id="sizecart-female" >
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

$plugin ='

<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());

  gtag("config", "UA-131936719-1");
</script>


';
$template = admin_template($content,$titlebar,$menu,$plugin);
echo $template;           

?>