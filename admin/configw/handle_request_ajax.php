<?php

include "configuration.php";

if (isset($_POST['action']) && $_POST['action'] == 'set_no_order') {
    $id_parent = isset($_POST['id_parent']) ? strip_tags(trim($_POST['id_parent'])) : '';
    $id_cat = isset($_POST['id_category']) ? strip_tags(trim($_POST['id_category'])) : '';

    $select_sub_category = mysql_query("SELECT * FROM `category` 
        WHERE `id_parent` = '$id_parent' AND `level` = '0' ORDER BY `no_order` ASC;");
    $count_all_order_in_sub_category = mysql_num_rows($select_sub_category);

    $result_sub_category = array();
    while ($row_sub_category = mysql_fetch_array($select_sub_category)) {
        $result_sub_category[] = [
            'number' => (int)$row_sub_category['no_order'],
            'selected' => ($id_cat == '' || empty($id_cat)) ? false : ($id_cat == $row_sub_category['id_cat']) ? true : false
        ];
    }

    $get_last_order_number = mysql_fetch_array(mysql_query("SELECT * FROM `category` 
        WHERE `id_parent` = '$id_parent' AND `level` = '0' ORDER BY `no_order` DESC LIMIT 0,1;"));
    $last_order_number = (($id_cat == '' || empty($id_cat) || $_POST['with_new_order_number']) ? [$count_all_order_in_sub_category =>
        ['number' => $get_last_order_number['no_order'] + 1, 'selected' => true]
    ] : []);

    exit(json_encode($result_sub_category + $last_order_number));
}