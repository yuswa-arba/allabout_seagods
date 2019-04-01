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
    $titlebar = "Add Sub Category";
    $titlepage = "Add Sub Category";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $simpan = isset($_POST["save"]) ? $_POST["save"] : '';
    $update = isset($_POST["update"]) ? $_POST["update"] : '';

    if ($simpan == "Save") {
        $category = isset($_POST['category']) ? strip_tags(trim($_POST["category"])) : "";
        $id_parent = isset($_POST['id_parent']) ? strip_tags(trim($_POST["id_parent"])) : "";
        $no_order = isset($_POST['no_order']) ? strip_tags(trim($_POST["no_order"])) : "";

        // Cek nomor urut sudah digunakan atau belum
        $no_serial_category = mysql_query("SELECT * FROM `category`
            WHERE `id_parent` = '$id_parent' AND `no_order` = '$no_order' AND `level` = '0';");

        // Jika nomor urut sudah digunakan maka akan menjalankan perintah dibawah
        if (mysql_num_rows($no_serial_category) > 0) {

            // Mengambil data dari query
            $row_category = mysql_fetch_array($no_serial_category);
            $id_cat = $row_category['id_cat'];

            // Jumlah semua nomor urut yang ada
            $get_last_order_number = mysql_fetch_array(mysql_query("SELECT * FROM `category` 
                WHERE `id_parent` = '$id_parent' AND `level` = '0' ORDER BY `no_order` DESC LIMIT 0,1;"));

            // Update nomor urut lama menjadi yang terakhir
            $query_update_no_serial = "UPDATE `category` SET `no_order` = '" . ($get_last_order_number["no_order"] + 1) . "' WHERE `id_cat` = '$id_cat';";
            mysql_query($query_update_no_serial) or die("<script>
                    alert('Gagal update nomor urut lama');
                    window.history(-1);
                </script>");
        }

        $querycategory = "INSERT INTO `category` (`id_cat`, `id_parent`, `no_order`, `category`, `level`) 
										VALUES (NULL, '$id_parent', '$no_order', '$category', '0');";


        //echo $query ;
        mysql_query($querycategory, $conn) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			alert('Data telah disimpan!');
			location.href = 'list_sub_categories.php';
            </script>";
    }

    if ($update == "Update") {
        $category = isset($_POST['category']) ? strip_tags(trim($_POST["category"])) : "";
        $id_parent = isset($_POST['id_parent']) ? strip_tags(trim($_POST["id_parent"])) : "";
        $id_catte = isset($_POST['id_cate']) ? strip_tags(trim($_POST["id_cate"])) : "";
        $no_order = isset($_POST['no_order']) ? strip_tags(trim($_POST["no_order"])) : "";

        // Cek nomor urut sudah digunakan atau belum
        $no_serial_category = mysql_query("SELECT * FROM `category`
            WHERE `id_parent` = '$id_parent' AND `no_order` = '$no_order' 
            AND `id_cat` != '$id_catte' AND `level` = '0';");

        // Jika nomor urut sudah digunakan maka akan menjalankan perintah dibawah
        if (mysql_num_rows($no_serial_category) > 0) {

            // Mengambil data dari query
            $row_category = mysql_fetch_array($no_serial_category);
            $id_cat_old = $row_category['id_cat'];

            // Get which is now category
            $get_now_category = mysql_fetch_array(mysql_query("SELECT `id_parent` FROM `category` WHERE `id_cat` = '$id_catte' LIMIT 0,1;"));
            $update_to_new_parent = (($get_now_category['id_parent'] == $id_parent) ? false : true);

            if ($update_to_new_parent) {
                $select_category_new_parent = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '$id_parent' 
                    AND `level` = '0' ORDER BY `no_order` DESC LIMIT 0,1;");
                $row_category_new_parent = mysql_fetch_array($select_category_new_parent);

                $order_number_for_old_category = $row_category_new_parent['no_order'] + 1;
            } else {
                $select_category_old_parent = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '$id_catte' 
                    AND `level` = '0' ORDER BY `no_order` DESC LIMIT 0,1;");
                $row_category_old_parent = mysql_fetch_array($select_category_old_parent);

                $order_number_for_old_category = $row_category_old_parent['no_order'];
            }

            // Update nomor urut lama menjadi yang terakhir
            $query_update_no_serial = "UPDATE `category` SET `no_order` = '" . $order_number_for_old_category . "' WHERE `id_cat` = '$id_cat_old';";
            mysql_query($query_update_no_serial) or die("<script>
                    alert('Gagal update nomor urut lama');
                    window.history(-1);
                </script>");
        }

        $querycategory = "UPDATE `category` SET `id_parent` = '$id_parent', `no_order` = '$no_order', `category` = '$category' WHERE `id_cat` = '$id_catte';";

        //echo $querycategory ;
        mysql_query($querycategory, $conn) or die("<script language='JavaScript'>
                alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			alert('Data telah diupdate!');
			location.href = 'list_sub_categories.php';
        </script>";
    }

    if (isset($_GET["id"])) {

        $id_cat = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
        $query = "SELECT * FROM `category` WHERE `category`.`id_cat` = '$id_cat' AND `level` = '0' ORDER BY `category`.`id_cat` DESC  ;";
        $id = mysql_query($query, $conn);
        $data_cat = mysql_fetch_array($id);

    }
    $content = '
        <div class=" container    container-fixed-lg">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">
                                ADD NEW SUB CATEGORY
                            </div>
                        </div>
                        <div class="card-block">
                            <form class="" method="post" role="form">
                                <div class="form-group form-group-default required ">
                                    <label>Category Name</label>
                                    <input type="text" name="category" class="form-control" value="' . (isset($_GET['id']) ? strip_tags(trim($data_cat["category"])) : "") . '" required>
                                    <input type="hidden" name="id_cate" id="id_cate" class="form-control" value="' . (isset($_GET['id']) ? $_GET['id'] : "") . '" >
                                </div>
                                
					            <div class="form-group form-group-default form-group-default-select2 required">
                                    <label class="">Select Category</label>
                                    <select class="full-width" data-placeholder="Select Country" name="id_parent" id="id_parent" data-init-plugin="select2">
						                <optgroup label="Select One">';

    $sql_caregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '0' AND `level` = '0';");

    while ($row_category = mysql_fetch_array($sql_caregory)) {
        $content .= '<option value="' . $row_category["id_cat"] . '" ' . (isset($_GET['id']) && $data_cat["id_parent"] == $row_category["id_cat"] ? "selected" : "") . '>' . $row_category["category"] . '</option>';
    }

    $content .= '
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group form-group-default required ">
                                    <label class="">Order Number</label>
                                    <select class="form-control" data-placeholder="Select Country" name="no_order" id="no_order">
                                    </select>
                                </div>
					            <button class="btn btn-primary" name="' . (isset($_GET['id']) ? "update" : "save") . '" value="' . (isset($_GET['id']) ? "Update" : "Save") . '" type="submit">' . (isset($_GET['id']) ? "Update Sub Category" : "Create New Sub Category") . '</button>
					        </form>
                        </div>
                    </div>
				</div>
			</div>
		</div>';

    $plugin = '
    <script>
        
        var parent = $("#id_parent");
        var parent_this = parent.val();
        
        set_no_order();
        
        parent.on("change", function () {
            set_no_order();
        });
        
        function set_no_order() {
            var with_new_order_number = ((parent_this == parent.val()) ? 0 : 1);
            
            $.ajax({
                type: "POST",
                url: "config/handle_request_ajax.php",
                data: {
                    "action": "set_no_order", 
                    "id_parent": parent.val(),
                    "id_category": $("#id_cate").val(),
                    "with_new_order_number": with_new_order_number
                },
                dataType: "json",
                success: function(data) {
                    
                    var no_order = $("#no_order");
                    
                    no_order.html("");
                    for (var i = 0; i < data.length; i++) {
                        no_order.append(
                            \'<option value="\'+data[i].number+\'" \'+(data[i].selected ? "selected" : "")+\'>\'+(i + 1)+\'</option>\'
                        );
                    }
                }
            });
        }
    </script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('Location: logout.php');
}

?>