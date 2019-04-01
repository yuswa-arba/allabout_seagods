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

if ($loggedin = logged_inadmin()) { //  Check if they are logged in

    $titlebar = "Add News";
    $titlepage = "Add News";

    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';


    // Action save
    if (isset($_POST['save'])) {

        // Set value request parameter
        $title = isset($_POST['title']) ? mysql_real_escape_string(trim($_POST['title'])) : '';
        $body = isset($_POST['body']) ? mysql_real_escape_string(trim($_POST['body'])) : '';

        if (!empty($title) && !empty($body)) {

            // Save news
            $query_insert_news = "INSERT INTO `news_subscriber` (`title`, `body`, `date_add`, `date_upd`)
                VALUES('$title', '$body', NOW(), NOW());";

            // If error
            if (!mysql_query($query_insert_news)) {
                echo "<script language='JavaScript'>
                    alert('Unable to create news');
                    window.history.go(-1);
                </script>";
                exit();
            }

            // Success
            echo "<script language='JavaScript'>
			    alert('News has been created successfully');
			    window.location.href = 'list_news.php';
			</script>";
            exit();

        } else {
            echo "<script language='JavaScript'>
			    alert('Data title and article can\'t empty');
			    window.history.go(-1);
			</script>";
            exit();
        }
    }


    // Action update
    if (isset($_POST['update'])) {

        // Set value request parameter
        $news_id = isset($_POST['news_id']) ? mysql_real_escape_string(trim($_POST['news_id'])) : '';
        $title = isset($_POST['title']) ? mysql_real_escape_string(trim($_POST['title'])) : '';
        $body = isset($_POST['body']) ? mysql_real_escape_string(trim($_POST['body'])) : '';

        if (!empty($title) && !empty($body)) {

            // Update Title
            $query_update_news = "UPDATE `news_subscriber` SET `title` = '$title', `body` = '$body', `date_upd` = NOW() WHERE `id` = '$news_id';";

            // If error
            if (!mysql_query($query_update_news)) {
                echo "<script language='JavaScript'>
                    alert('Unable to update news');
                    window.history.go(-1);
                </script>";
                exit();
            }

            // Success
            echo "<script language='JavaScript'>
                alert('News has been updated successfully');
                window.location.href = 'list_news.php" . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . "';
            </script>";
            exit();

        } else {
            echo "<script language='JavaScript'>
			    alert('Data title and article can\'t empty');
			    window.history.go(-1);
			</script>";
            exit();
        }

    }


    // If parameter ID is ecists
    if (isset($_GET['id'])) {

        // Set news
        $query_news = mysql_query("SELECT * FROM `news_subscriber` WHERE `id` = '" . $_GET['id'] . "' LIMIT 0,1;");

        // If news not found
        if (mysql_num_rows($query_news) == 0) {
            echo "<script>
                alert('News not found!!');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Result news
        $row_news = mysql_fetch_array($query_news);
    }

    $content = '
        <div class="content ">
            <div class=" container container-fixed-lg">
                
                <!-- START card -->
                <div class="card card-default">
                
                    <div class="card-header">
                        <div class="card-title">News For Subscriber</div>
                    </div>
                    
                    <div class="card-block no-scroll card-toolbar">
                        <form method="post" action="" enctype="multipart/form-data" role="form">
                            <input type="hidden" name="news_id" value="' . (isset($_GET['id']) ? $row_news['id'] : '') . '">
                            <div class="form-group form-group-default required ">
                                <label>Title News</label>
                                <input class="form-control" name="title" value="' . (isset($_GET['id']) ? $row_news['title'] : '') . '">
                            </div>
                            
                            <div class="form-group form-group-default required ">
                                <label>Form News</label>
                                <div class="summernote-wrapper">
                                    <textarea id="summernote" name="body">' . (isset($_GET['id']) ? $row_news['body'] : '') . '</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="' . (isset($_GET['id']) ? 'update' : 'save') . '">' . (isset($_GET['id']) ? 'UPDATE' : 'SAVE') . '</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
                <!-- END card -->
                
            </div>
        </div>';

    $plugin = '
    <link href="assets/plugins/summernote/css/summernote.css" rel="stylesheet" type="text/css" media="screen">
	
    <script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script src="assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
    <script src="assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/form_elements.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>