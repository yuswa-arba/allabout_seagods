<?php 
session_start();

include ("config/configuration.php");
include ("config/template_blog.php");


$query = mysql_query("Select about from pages");
$data = mysql_fetch_assoc($query);
$about = $data['about'];

$titlebar = "Tips To Get Into Your Wetsuit Easily - JUDUL ARTIKEL ".$title; 
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
                                        <h3 class="title">   Tips To Get Into Your Wetsuit Easily </h3>
                                          <!--'.$about.'-->
                                          
                                           <div><img src="images/blog/GIW.jpg" height="200px"></div>
                                          We’ve all been there. Struggling to get into a wetsuit, sweating buckets, pulling at thick material,
gasping for breath as we struggle to get into the dreaded wetsuit. <br>Well, there’s a quick and easy solution
– make a custom suit at SeaGods wetsuit and all of your wetsuit worries will be over. <br>Seriously! A
custom suit is designed to slip on easily!<br><br>
But not everyone has a custom suit, and sometimes our body shape changes with the years. So it’s
always a good idea to have some tricks up your sleeve to glide effortlessly into your wetsuit.<br>
Wetsuits are designed to be snug, this is what keeps you warm underwater, but this is also what makes
putting them on so very treacherous! Here are some of my favorite tips for getting into (and out of) your
suit with ease.<br><br>
1. Dunk your suit in water, or better yet, dunk yourself. A dry suit can be hard to put on! If the
water temperature and conditions allow, jump in and put the suit on in the water. Neoprene is
buoyant, so the suit will float as you wiggle your way into it while floating on the water’s
surface.<br>
2. Use a plastic bag or two. Cover your feet and arms in a plastic bag to help slide the suit over
ankles and wrists. Once on, remove the plastic bags and store safely in your dive bag for your
next dive (don’t let them blow away – don’t add to pollution!).<br>
3. One of my favorite tips is to wear a rash guard or dive skin. Wearing a lycra top, pants or full
body suit under your wetsuit can really help the wetsuit slide on effortlessly. It is also a great
way to protect sensitive skin from neoprene. In the past, many divers relied on pantyhose – yes,
pantyhose – for the same result, thankfully rash guards and dive skins give you the same results
with none of the discomfort or embarrassment of pantyhose (I’m looking at you, guys!).<br>
4. Use a water-based lubricant. Never use 100 percent oil lubricant to slide into your wetsuit
because any oil that touches your wetsuit can easily fade the color of the wetsuit. Water-based
lubricants are also better for your skin and the ocean.<br>
5. Installing ankle and wrist zippers are also a wise step for getting into a wetsuit. Zippers also
protect your wetsuits from over-stretching.<br><br>
It can be hard to look graceful getting into and out of a wetsuit. But with these simple tips (and a custom
suit from SeaGods), you will find it to be much easier!<br><br>
                                          
                                          
                                          
                                          
                                          
                                          
                                          
                                          
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