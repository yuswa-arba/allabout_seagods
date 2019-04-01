<?php
//Include database configuration file
include('config/dbConfig.php');

if(isset($_POST["idProvinsi"]) && !empty($_POST["idProvinsi"])){
    //Get all state data
    $query = $db->query("SELECT * FROM kota WHERE idProvinsi = ".$_POST['idProvinsi']."  ORDER BY namaKota ASC");
    
    //Count total number of rows
    $rowCount = $query->num_rows;
    
    //Display states list
    if($rowCount > 0){
        echo '<option value="">--Pilih Kota--</option>';
        while($row = $query->fetch_assoc()){ 
            echo '<option value="'.$row['idKota'].'">'.$row['namaKota'].'</option>';
        }
    }else{
        echo '<option value="">--Pilih Kota--</option>';
    }
}

/*if(isset($_POST["state_id"]) && !empty($_POST["state_id"])){
    //Get all city data
    $query = $db->query("SELECT * FROM cities WHERE state_id = ".$_POST['state_id']." AND status = 1 ORDER BY city_name ASC");
    
    //Count total number of rows
    $rowCount = $query->num_rows;
    
    //Display cities list
    if($rowCount > 0){
        echo '<option value="">Select city</option>';
        while($row = $query->fetch_assoc()){ 
            echo '<option value="'.$row['city_id'].'">'.$row['city_name'].'</option>';
        }
    }else{
        echo '<option value="">City not available</option>';
    }
}*/
?>