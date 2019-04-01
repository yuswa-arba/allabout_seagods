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

    $titlebar = "Detail News";
    $titlepage = "Detail News";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    // If request id is exists
    if (isset($_GET['id'])) {

        // set value parameter
        $news_id = isset($_GET['id']) ? mysql_real_escape_string(trim($_GET['id'])) : '';

        // Query select news
        $query_news = mysql_query("SELECT * FROM `news_subscriber` WHERE `id` = '$news_id' AND `level` = '0' LIMIT 0,1;");

        // if null
        if (mysql_num_rows($query_news) == 0) {
            echo "<script>
                alert('News not found');
                window.history.back(-1);
            </script>";
            exit();
        }

        // result news
        $row_news = mysql_fetch_array($query_news);
    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">
                                <h4><b>' . $row_news["title"] . '</b></h4>
                            </div>
                                <a href="list_news.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '" class="btn btn-outline-primary pull-right" name="">Back to List</a>
                        </div>
                    </div>
                    
                    <div class="card card-default">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-12">
                                    ' . $row_news["body"] . ' 
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