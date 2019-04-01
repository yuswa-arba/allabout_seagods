<?php
session_start();

include("config/configuration.php");
include("config/template_blog.php");

if (isset($_GET['blog'])) {

    $id_blog = isset($_GET['blog']) ? mysql_real_escape_string(trim($_GET['blog'])) : '';

    $query_blog = mysql_query("SELECT * FROM `blog` WHERE `id_blog` = '" . $id_blog . "';");
    $row_blog = mysql_fetch_assoc($query_blog);

    if (mysql_num_rows($query_blog) == 0) {
        echo "<script>
            alert('Blog not found');
            close();
        </script>";
    }

    $query_update_reader = "UPDATE `blog` SET `reader` = '" . ($row_blog["reader"] + 1) . "' WHERE `id_blog` = '" . $id_blog . "';";
    mysql_query($query_update_reader) or die("<script language='JavaScript'>
                alert('Sorry, an error has occurred');
                close();
            </script>");

} else {
    echo "<script>
        alert('Can\'t open this page.!');
        close();
    </script>";
}

$titlebar = $row_blog['title'];
$menu = "";

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
                                            <h3 class="title">' . $row_blog["title"] . '</h3>
                                            <h7 style="font-size:10px;" align="center">' . date('F d, Y', strtotime($row_blog["date_add"])) . '</h7>
                                            <div><img src="../admin/images/blog/' . $row_blog["photo"] . '" height="200px"></div>
                                            ' . $row_blog["body"] . '
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
$template = admin_template($content, $titlebar, $menu, $plugin);
echo $template;

?>