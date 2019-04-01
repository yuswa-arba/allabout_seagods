<?php
include ("config/configuration.php");

$email = mysql_real_escape_string($_POST['email']);
$sql = "select * from `users` where `email` = '$email'";
$process = mysql_query($sql);
$num = mysql_num_rows($process);
if($num == 0){
	echo " &#10004; Email belum Terdaftar ";
}else{
	echo " &#10060; Alamat email sudah digunakan ";
}
?>