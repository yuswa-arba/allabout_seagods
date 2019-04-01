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

    if (isset($_POST['save'])) {

        // Set value request parameter
        $title = isset($_POST['title']) ? mysql_real_escape_string(trim($_POST['title'])) : '';
        $body = isset($_POST['body']) ? mysql_real_escape_string(trim($_POST['body'])) : '';
        $photoNameUpload = '';

        if (!empty($title) && !empty($body)) {

            if ($_FILES['photo']['tmp_name'] != "") {

                $target_path = "images/blog/"; // Declaring Path for uploaded images.

                // Set memory limit in php.ini
                ini_set('memory_limit', '120M');

                // Loop to get individual element from the array
                $validextensions = array("jpeg", "jpg", "png", "PNG"); // Extensions which are allowed.
                $RandomNumber = rand(0, 9999999999); // for create name file upload
                $imageName = $RandomNumber . "-" . ($_FILES['photo']['name']);

                $ext = explode('.', $_FILES['photo']['name']); // Explode file name from dot(.)
                $file_extension = end($ext); // Store extensions in the variable.

                if (($_FILES["photo"]["size"] < 5000000) // Approx. 5Mb files can be uploaded.
                    && in_array($file_extension, $validextensions)
                ) {
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path . $imageName)) {
                        $photoNameUpload = $imageName;
                    } else { // If File Was Not Moved.
                        echo "<script language='JavaScript'>
                            alert('File Was Not Moved.');
                            window.history.go(-1);
						</script>";
                        exit();
                    }
                }

                // Query save article
                $query_insert_blog = "INSERT INTO `blog` (`id_blog`, `title`, `photo`, `body`, `date`, `date_add`, `date_upd`)
                    VALUES(NULL, '$title', '$photoNameUpload', '$body', '" . date("d/m/Y") . "', NOW(), NOW());";
                $save_article = mysql_query($query_insert_blog) or die("<script language='JavaScript'>
                        alert('Unable to save article!');
				    window.history.go(-1);
                    </script>");

                echo "<script language='JavaScript'>
				    alert('Article has been saved successfully');
				    window.location.href = 'list_blog.php';
				</script>";

            } else {
                echo "<script language='JavaScript'>
				    alert('Your photo is empty!!');
				    window.history.go(-1);
				</script>";
            }

        } else {
            echo "<script language='JavaScript'>
				    alert('Data title and article can\'t empty');
				    window.history.go(-1);
				</script>";
        }
    }

    if (isset($_POST['update'])) {

        // Set value request parameter
        $id_blog = isset($_POST['id_blog']) ? mysql_real_escape_string(trim($_POST['id_blog'])) : '';
        $title = isset($_POST['title']) ? mysql_real_escape_string(trim($_POST['title'])) : '';
        $body = isset($_POST['body']) ? mysql_real_escape_string(trim($_POST['body'])) : '';
        $photoNameUpload = isset($_POST['photo_old']) ? mysql_real_escape_string(trim($_POST['photo_old'])) : '';

        if (!empty($title) && !empty($body)) {

            if ($_FILES['photo']['tmp_name'] != "") {

                $target_path = "images/blog/"; // Declaring Path for uploaded images.

                // Query get photo old
                $query_photo_old = mysql_query("SELECT `photo` FROM `blog` WHERE `id_blog` = '$id_blog' LIMIT 0,1;");
                $row_photo = mysql_fetch_array($query_photo_old);

                if (file_exists($target_path . $row_photo['photo'])) {
                    unlink($target_path . $row_photo['photo']);
                }

                // Set memory limit in php.ini
                ini_set('memory_limit', '120M');

                // Loop to get individual element from the array
                $validextensions = array("jpeg", "jpg", "png", "PNG"); // Extensions which are allowed.
                $RandomNumber = rand(0, 9999999999); // for create name file upload
                $imageName = $RandomNumber . "-" . ($_FILES['photo']['name']);

                $ext = explode('.', $_FILES['photo']['name']); // Explode file name from dot(.)
                $file_extension = end($ext); // Store extensions in the variable.

                if (($_FILES["photo"]["size"] < 5000000) // Approx. 5Mb files can be uploaded.
                    && in_array($file_extension, $validextensions)
                ) {
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path . $imageName)) {
                        $photoNameUpload = $imageName;
                    } else { // If File Was Not Moved.
                        echo "<script language='JavaScript'>
                            alert('File Was Not Moved.');
                            window.history.go(-1);
						</script>";
                        exit();
                    }
                }

            }

            // Query save article
            $query_update_blog = "UPDATE `blog` SET `title` = '$title', `photo` = '$photoNameUpload', `body` = '$body' WHERE `id_blog` = '$id_blog';";
            mysql_query($query_update_blog) or die("<script language='JavaScript'>
                        alert('Unable to update article!');
				    window.history.go(-1);
                    </script>");

            echo "<script language='JavaScript'>
				    alert('Article has been updated successfully');
				    window.location.href = 'list_blog.php';
				</script>";

        } else {
            echo "<script language='JavaScript'>
				    alert('Data title and article can\'t empty');
				    window.history.go(-1);
				</script>";
        }

    }

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
    }

    $titlebar = isset($_GET['blog']) ? "Update Article" : "Add Article";
    $titlepage = isset($_GET['blog']) ? "Update Article" : "Add Article";

    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $content = '
        <div class="content ">
            <div class=" container container-fixed-lg">
                
                <!-- START card -->
                <div class="card card-default">
                
                    <div class="card-header">
                        <div class="card-title">Content Editor</div>
                    </div>
                    
                    <div class="card-block no-scroll card-toolbar">
                        <form method="post" action="" enctype="multipart/form-data" role="form">
                            <input type="hidden" name="id_blog" value="' . (isset($_GET['blog']) ? $row_blog['id_blog'] : '') . '">
                            <div class="form-group form-group-default required ">
                                <label>Title Blog</label>
                                <input class="form-control" name="title" value="' . (isset($_GET['blog']) ? $row_blog['title'] : '') . '" placeholder="Title Your Blog...">
                            </div>
                            
                            <div class="form-group form-group-default required ">
                                <label>Form Article Blog</label>
                                <div class="summernote-wrapper">
                                    <textarea id="summernote" name="body">' . (isset($_GET['blog']) ? $row_blog['body'] : '') . '</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group form-group-default">
                                 <label>Upload Headline Picture</label><br/>
                                 <input type="hidden" name="photo_old" value="' . (isset($_GET['blog']) ? $row_blog['photo'] : '') . '">
                                            <input type="file" name="photo">
                            </div>
                                
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="' . (isset($_GET['blog']) ? 'update' : 'save') . '">' . (isset($_GET['blog']) ? 'UPDATE' : 'SAVE') . '</button>
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