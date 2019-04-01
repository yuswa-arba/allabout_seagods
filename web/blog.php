<?php
include("config/configuration.php");
include ("config/template_blog.php");
session_start();

// Set logged in
$loggedin = logged_in();

$perhalaman = 6;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
    $start = ($page - 1) * $perhalaman;
} else {
    $start = 0;
}

$query_blog = mysql_query("Select * FROM `blog` WHERE `level` = '0' Order by `id_blog` DESC LIMIT $start,$perhalaman;");

$sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `blog` WHERE `level` = '0' order by `id_blog`"));

$titlebar = "Blog";
$menu = "";

function show_text($text, $limit)
{
    $text = preg_replace('!\s+!', ' ', $text);

    return strlen($text) > $limit ?
        substr($text, 0, $limit) . "...." :
        $text;
}

$content = '
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
                                         
                                            <div class="section mcb-section" style="padding-top:0px; padding-bottom:20px">
                                                <div class="section_wrapper mcb-section-inner">
                                                    <div class="wrap mcb-wrap one valign-top clearfix">
                                                        <div class="mcb-wrap-inner">';

while ($row_blog = mysql_fetch_array($query_blog)) {

    $content .= '
                                                            <div class="column mcb-column one-third column_column">
                                                                <div class="column_attr clearfix" style=" padding:15px">
                                                                    <a href="read_blog.php?blog=' . $row_blog["id_blog"] . '&ttle=' . $row_blog["title"] . '" target="_blank"><h3>' . show_text($row_blog["title"], 35) . '</h3></a>
                                                                    <h7 style="font-size:10px;" align="center">' . date('F d, Y', strtotime($row_blog["date_add"])) . '</h7>
                                                                    <div><img src="../admin/images/blog/' . $row_blog["photo"] . '" style="height: 115px !important;"></div>
                                                                    <p> 
                                                                       ' . show_text(strip_tags($row_blog['body']), 70) . '
                                                                    </p>
                                                                    <a class="button button_size_2 button_js" href="read_blog.php?blog=' . $row_blog["id_blog"] . '&ttle=' . $row_blog["title"] . '"  target="_blank" style=" background-color:#f2f2f2 !important; color:#000;"><span class="button_label">Read more..</span></a>
                                                                </div>
                                                            </div>';

}

$content .= '
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>';

$content .= (halaman($sql_total_data, $perhalaman, 1, '?'));

$content .= '                            
                                        </div>
              
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';

$plugin = '
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());

  gtag("config", "UA-131936719-1");
</script>


';
$template = admin_template($content, $titlebar, "", "", $menu, $plugin, $loggedin);
echo $template;

?>