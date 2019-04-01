<?php
include("config/configuration.php");
include("config/template_cart.php");

$titlebar = "Cart";
$menu = "";

$content = '
    <div class="sidebar four columns">
         <div class="widget-area">
             <div class="widget woocommerce widget_product_categories">
                 <h3>CATEGORIES</h3>
                 <ul class="product-categories">';

$sql_caregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '0' AND `level` = '0' ORDER BY `no_order` ASC  ;");

while ($row_category = mysql_fetch_array($sql_caregory)) {

    $content .= '<li><a href="list_product.php?id_cats=' . $row_category['id_cat'] . '">' . $row_category["category"] . '';

    $sql_subcaregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '$row_category[id_cat]' AND `level` = '0' ORDER BY `no_order` ASC  ;");
    while ($row_sucategory = mysql_fetch_array($sql_subcaregory)) {

        $content .= '<ul><li><a href="list_product.php?id_cat=' . $row_sucategory['id_cat'] . '">' . $row_sucategory["category"] . '</a></li></ul>';

    }

    $content .= '</li>';
}

$content .= '
                </ul>
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
                                    <div class="post-content">
                                        <div class="section mcb-section" style="padding-top:0px; padding-bottom:20px">
                                            <div class="section_wrapper mcb-section-inner">
                                                <div class="wrap mcb-wrap one valign-top clearfix">
                                                    <div class="mcb-wrap-inner">
                                                        <table class="table table-condensed">
                                                            <tr>
                                                                <td class="col-lg-8 col-md-6 col-sm-7 ">
                                                                    <span class="m-l-10 font-montserrat fs-11 all-caps">sadfsadf</span>
                                                                </td>
                                                                <td class=" col-lg-2 col-md-3 col-sm-3 text-right">
                                                                    <span>Qty asdf</span>
                                                                </td>
                                                                <td class=" col-lg-2 col-md-3 col-sm-2 text-right">
                                                                    <h4 class="text-primary no-margin font-montserrat">$ asdfsa</h4>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="col-lg-8 col-md-6 col-sm-7 ">
                                                                    <span class="m-l-10 font-montserrat fs-11 all-caps">sadfsadf</span>
                                                                </td>
                                                                <td class=" col-lg-2 col-md-3 col-sm-3 text-right">
                                                                    <span>Qty asdf</span>
                                                                </td>
                                                                <td class=" col-lg-2 col-md-3 col-sm-2 text-right">
                                                                    <h4 class="text-primary no-margin font-montserrat">$ asdfsa</h4>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

$plugin = '';

$template = admin_template($content, $titlebar, $titlepage = "", $user = "", $menu, $plugin);

echo $template;

?>