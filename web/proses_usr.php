<?php
include ("config/configuration.php");

$username = mysql_real_escape_string($_POST['username']);
$sql = "select * from `users` where `username` = '$username'";
$process = mysql_query($sql);
$num = mysql_num_rows($process);
if($num == 0){
	echo " &#10004; Username masih tersedia";
}else{
	echo " &#10060; Username tidak tersedia";
}
?>