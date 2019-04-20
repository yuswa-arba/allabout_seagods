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

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $perhalaman = 100;
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        $start = ($page - 1) * $perhalaman;
    } else {
        $start = 0;
    }

    $titlebar = "List News";
    $titlepage = "List News";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    function show_text($text, $limit)
    {
        $text = preg_replace('!\s+!', ' ', $text);

        return strlen($text) > $limit ?
            substr($text, 0, $limit) . "...." :
            $text;
    }

    // Action delete
    if (isset($_POST['delete'])) {

        // Set value request
        $news_id = isset($_POST['news_id']) ? mysql_real_escape_string(trim($_POST['news_id'])) : '';

        // query update status delete
        $query_news = "UPDATE `news_subscriber` SET `level` = '1' WHERE `id` = '$news_id';";

        // If error
        if (!mysql_query($query_news)) {
            echo "<script>
                alert('Unable to delete news!!');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Success
        echo "<script>
            alert('News has been deleted successfully');
            window.history.back(-1);
        </script>";
        exit();
    }

    // Action send to subscriber
    if (isset($_POST['send'])) {

        // Set value
        $news_id = isset($_POST['send']) ? mysql_real_escape_string(trim($_POST['news_id'])) : '';

        // Check news
        $query_check_news = mysql_query("SELECT `id` FROM `news_subscriber` WHERE `id` = '$news_id' AND `level` = '0';");
        if (mysql_num_rows($query_check_news) == 0) {
            echo "<script>
                alert('News selected does not exists');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Set date timestamp
        $nowDate = new DateTime();
        $nowDate->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
        $dateTimestamp = $nowDate->getTimestamp();

        // query send news
        $query_send_news = "UPDATE `news_subscriber` SET `dateTimestamp` = '$dateTimestamp', `sent` = TRUE,
            `delivery_process` = TRUE, `date_upd` = NOW() WHERE `id` = '$news_id';";

        // If error
        if (!mysql_query($query_send_news)) {
            echo "<script>
                alert('Unable to send this news!!');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Success
        echo "<script>
            alert('News has been sent successfully. Will be processed after 10 minutes!');
            window.history.back(-1);
        </script>";
        exit();

    }

    $content = '
        <div class="page-container ">
            
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
                
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class=" container container-fixed-lg">
            
                        <!-- START card -->
                        <div class="card card-transparent">
                            <div class="card-header ">
                                <div class="card-title">List News</div>
                                <div class="pull-right">
                                    <div class="col-xs-12">
                                        <a href="form_news.php" id="show-modal" class="btn btn-primary btn-cons" style="color:white"><i class="fa fa-plus"></i> Write News
                                        </a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="card-block">
                                <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                    <thead>
                                        <tr>
                                            <th style="width:5%"></th>
                                            <th >Title</th>
                                            <th style="width:50%">Body</th>
                                            <th style="width:10%">Status</th>
                                            <th style="width:10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

    // Get news
    $query_news = mysql_query("SELECT * FROM `news_subscriber` WHERE `level` = '0' ORDER BY `id` DESC LIMIT $start,$perhalaman;");
    $sql_total_data = mysql_num_rows(mysql_query("SELECT * FROM `news_subscriber` WHERE `level` = '0' ORDER BY `id` DESC;"));

    $no = 1;
    while ($row_news = mysql_fetch_array($query_news)) {

        $content .= '
                                        <form action="" method="post" name="list_subscriber">
                                            <tr>
                                                <input type="hidden" name="news_id" value="' . $row_news['id'] . '">
                                                <td class="v-align-middle">' . $no . '</td>
                                                <td class="v-align-middle">
                                                    <p>' . show_text($row_news['title'], 60) . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>' . show_text(strip_tags($row_news['body']), 100) . '</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    ' . (($row_news['sent'] == false) ?
                '<button type="submit" class="btn btn-sm btn-danger" name="send"> SEND TO SUBSCRIBER</button>' :
                '<span class="label" style="background-color: #00A8FF; color: #ffffff;"> SENT </span>') . '
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="detail_news.php?id=' . $row_news['id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-success"><i class="fa fa-eye"></i>
                                                        <a href="form_news.php?id=' . $row_news['id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                                        <button type="submit" class="btn btn-danger" name="delete"><i class="fa fa-trash-o"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </form>';
        $no++;

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

    $plugin = '
    <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/datatables.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>