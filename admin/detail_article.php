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

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    global $conn;

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = "Detail Article";
    $titlepage = "Detail Article";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET['blog'])) {
        $id_blog = isset($_GET['blog']) ? mysql_real_escape_string(trim($_GET['blog'])) : '';

        // Query select article blog
        $query_blog = mysql_query("SELECT * FROM `blog` WHERE `id_blog` = '$id_blog' LIMIT 0,1;");
        $row_blog = mysql_fetch_array($query_blog);

        // If article not found
        if (mysql_num_rows($query_blog) == 0) {
            echo "<script language='JavaScript'>
				    alert('Article not found..!!');
				    window.history.go(-1);
				</script>";
        }
    } else {
        echo "<script language='JavaScript'>
			    alert('Choose one article');
			    window.history.go(-1);
			</script>";
    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">
                                <h4><b>' . $row_blog['title'] . '</b></h4>
                            </div>
                                <a href="list_blog.php" class="btn btn-outline-primary pull-right" name="">Back to List</a>
                        </div>
                    </div>
                    
                    <div class="card card-default">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-12">
                                    <img src="images/blog/' . $row_blog['photo'] . '" height="200px">
                                </div>
                                <div class="col-12 body-article">
                                    ' . $row_blog['body'] . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

    $plugin = '
    <style>
        .body-article > img {
            width: 100% !important;
        }
    </style>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('Location: logout.php');
}

?>