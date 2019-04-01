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

    $loggedin = logged_inadmin();
    $titlebar = "Add Category";
    $titlepage = "Add Category";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $simpan = isset($_POST["simpan"]) ? $_POST["simpan"] : '';
    $update = isset($_POST["update"]) ? $_POST["update"] : '';

    if ($simpan == "Simpan") {
        $category = isset($_POST['category']) ? strip_tags(trim($_POST["category"])) : "";
        $no_order = isset($_POST['no_order']) ? strip_tags(trim($_POST["no_order"])) : "";

        // Cek nomor urut sudah digunakan atau belum
        $no_serial_category = mysql_query("SELECT * FROM `category`
            WHERE `id_parent` = '0' AND `no_order` = '$no_order' AND `level` = '0';");

        // Jika nomor urut sudah digunakan maka akan menjalankan perintah dibawah
        if (mysql_num_rows($no_serial_category) > 0) {

            // Mengambil data dari query
            $row_category = mysql_fetch_array($no_serial_category);
            $id_cat = $row_category['id_cat'];

            // Jumlah semua nomor urut yang ada
            $count_all_no_order = mysql_num_rows(mysql_query("SELECT * FROM `category` WHERE `id_parent` = '0' AND `level` = '0';"));

            // Update nomor urut lama menjadi yang terakhir
            $query_update_no_serial = "UPDATE `category` SET `no_order` = '" . ($count_all_no_order + 1) . "' WHERE `id_cat` = '$id_cat';";
            mysql_query($query_update_no_serial) or die("<script>
                    alert('Gagal update nomor urut lama');
                    window.history(-1);
                </script>");
        }

        $querycat = "INSERT INTO `category` (`id_cat`, `id_parent`, `no_order`, `category`, `level`) 
		    VALUES (NULL, '0', '$no_order', '$category', '0');";

        //echo $query ;
        mysql_query($querycat) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			alert('Data telah disimpan!');
			location.href = 'list_categories.php';
            </script>";
    }

    if ($update == "Update") {
        $idCategory = isset($_POST['idCategory']) ? strip_tags(trim($_POST["idCategory"])) : "";
        $category = isset($_POST['category']) ? strip_tags(trim($_POST["category"])) : "";
        $no_order = isset($_POST['no_order']) ? strip_tags(trim($_POST["no_order"])) : "";

        // Cek nomor urut sudah digunakan atau belum
        $no_serial_category = mysql_query("SELECT * FROM `category`
            WHERE `id_parent` = '0' AND `no_order` = '$no_order' 
            AND `id_cat` != '$idCategory' AND `level` = '0';");

        // Jika nomor urut sudah digunakan maka akan menjalankan perintah dibawah
        if (mysql_num_rows($no_serial_category) > 0) {

            // Mengambil data dari query
            $row_category = mysql_fetch_array($no_serial_category);
            $id_cat = $row_category['id_cat'];

            // Select data category at updated
            $select_category_at_updated = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '$idCategory';");
            $row_category_at_updated = mysql_fetch_array($select_category_at_updated);

            // Update nomor urut lama menjadi yang terakhir
            $query_update_no_serial = "UPDATE `category` SET `no_order` = '" . $row_category_at_updated["no_order"] . "' WHERE `id_cat` = '$id_cat';";
            mysql_query($query_update_no_serial) or die("<script>
                    alert('Gagal update nomor urut lama');
                    window.history(-1);
                </script>");
        }

        $sqlCategoryUpdate = "UPDATE `category` SET `category` = '$category', `no_order` = '$no_order' WHERE `id_cat` = '$idCategory'";
        mysql_query($sqlCategoryUpdate, $conn) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			alert('Data telah diupdate!');
			location.href = 'list_categories.php';
            </script>";
    }

    if (isset($_GET['id'])) {
        $idCategory = $_GET['id'];
        $queryCategory = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '$idCategory' LIMIT 0,1;", $conn);
        $rowCategory = mysql_fetch_array($queryCategory);
    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">
                                ADD NEW CATEGORY
                            </div>
                        </div>
                        <div class="card-block">
                            <form class="" action="form_category.php" method="post" role="form">
                                <input type="hidden" name="idCategory" value="' . (isset($_GET['id']) ? $rowCategory["id_cat"] : "") . '">
                                <div class="form-group form-group-default required ">
                                    <label>Category Name</label>
                                    <input type="text" class="form-control" required name="category" value="' . (isset($_GET['id']) ? $rowCategory["category"] : "") . '">
                                </div>
                                <div class="form-group form-group-default required ">
                                    <label class="">Order Number</label>
                                    <select class="form-control" data-placeholder="Select Country" name="no_order">';

    $sql_category = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '0' AND `level` = '0';");
    $total_serial = (isset($_GET['id']) ? mysql_num_rows($sql_category) : (mysql_num_rows($sql_category) + 1));

    for ($a = 1; $a <= $total_serial; $a++) {
        $content .= '<option value="' . $a . '" ' . (isset($_GET['id']) ? (($a == $rowCategory["no_order"]) ? "selected" : "") : ($a == $total_serial) ? "selected" : "") . '>ke - ' . $a . '</option>';
    }

    $content .= '
                                    </select>
                                </div>
                                <button class="btn btn-primary" name="' . (isset($_GET['id']) ? "update" : "simpan") . '" value="' . (isset($_GET['id']) ? "Update" : "Simpan") . '" type="submit">' . (isset($_GET['id']) ? "Update Category" : "Create a New Category") . '</button>
					        </form>
                        </div>
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