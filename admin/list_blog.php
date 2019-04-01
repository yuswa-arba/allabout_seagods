<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
session_start();
ob_start();

if (isset($_POST['nilai'])) {
    $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
} else {
    $_SESSION['nilai_login'] = 0;
}

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    $perhalaman = 10;

    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    $titlebar = "List Articles Blog";
    $titlepage = "List Articles Blog";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_POST['delete'])) {
        $id_blog = isset($_POST['id_blog']) ? mysql_real_escape_string(trim($_POST['id_blog'])) : '';
        $page = isset($_POST['page']) ? mysql_real_escape_string(trim($_POST['page'])) : '';

        $query_delete_blog = "UPDATE `blog` SET `level` = '1', `date_upd` = NOW() WHERE `id_blog` = '$id_blog';";
        mysql_query($query_delete_blog) or die("<script language='JavaScript'>
			    alert('Unable to delete article');
			    window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			    alert('Article has been deleted successfully');
			    window.location.href = 'list_blog.php" . (($page != '') ? '?page=' . $page : '') . "';
            </script>";
    }

    function show_text($text, $limit)
    {
        $text = preg_replace('!\s+!', ' ', $text);

        return strlen($text) > $limit ?
            substr($text, 0, $limit) . "...." :
            $text;
    }

    $content = '
        <div class="page-container ">
            
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
                
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class="container container-fixed-lg">
                        
                        <!-- START card -->
                        <div class="card card-transparent">
                            <div class="card-header ">
                                <div class="card-title">List Articles - Blog</div>
                                <div class="pull-right">
                                    <div class="col-xs-12">
                                        <a href="article.php" class="btn btn-primary btn-cons" style="color:white"><i class="fa fa-plus"></i> Add Article
                                        </a>
                                    </div>
                                </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="card-block">
                            <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                <thead>
                                    <tr>
                                        <th style="width:10%">DATE</th>
                                        <th style="width:20%">TITLE</th> 
                                        <th style="width:40%">DESCRIPTION</th>
                                        <th style="width:10%">HEAD PIC</th>
                                        <th style="width:10%"></th>
                                        <th style="width:10%"></th> 
                                    </tr>
                                </thead>
                                <tbody>';

    // Query Select blog
    $query_blog = mysql_query("SELECT * FROM `blog` WHERE `level` = '0' ORDER BY `date_add` DESC LIMIT $start,$perhalaman");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT `id_blog` FROM `blog` WHERE `level` = '0' ORDER BY `date_add`"));

    while ($row_blog = mysql_fetch_array($query_blog)) {

        $content .= '
                                    <form action="" method="post">
                                        <tr>
                                            <td class="v-align-middle">
                                                ' . $row_blog['date'] . '
                                                <input type="hidden" name="id_blog" value="' . $row_blog["id_blog"] . '">
                                                <input type="hidden" name="page" value="' . (isset($_GET['page']) ? trim($_GET['page']) : '') . '">
                                            </td>
                                            <td class="v-align-middle">
                                                <p>' . show_text($row_blog['title'], 60) . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>' . show_text(strip_tags($row_blog['body']), 100) . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <img src="images/blog/' . $row_blog['photo'] . '" height="70px">
                                            </td>
                                            <td class="v-align-middle">
                                                <a class="btn btn-success" href="detail_article.php?blog=' . $row_blog['id_blog'] . '">view</a>
                                            </td>
                                            
                                            <td class="v-align-middle">
                                                <div class="btn-group">
                                                    <a href="article.php?blog=' . $row_blog["id_blog"] . '" class="btn btn-success"><i class="fa fa-pencil"></i>
                                                    </a>
                                                    <button type="submit" class="btn btn-danger" name="delete" value="Delete"><i class="fa fa-trash-o"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>';
    }

    $content .= '
                                </tbody>
                            </table>
                            ' . (halaman($sql_total_data, $perhalaman, 1, '?')) . '
                        </div>
                    </div>
                    <!-- END card -->
                </div>
            </div>
        </div>
    </div>';

    $plugin = '';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>