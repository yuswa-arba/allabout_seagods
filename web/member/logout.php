<?php
session_start();
ob_start();

include ("config/configuration.php");


if ($loggedin = logged_in())
{ // Check if they are logged in




    $id = mysql_query("SELECT * FROM `users` WHERE `id_member` = '" . $loggedin['id_member'] ."'");
    // Check if the username and password are correct.
    
    $jam = date("H") ;
    $waktu = date("Y-m-d ") . ($jam - 1) . date(":i:s") ;
    
    if (mysql_num_rows($id) == 1)
    {
        $uid = mysql_fetch_array($id) ;
        $sql = mysql_query("UPDATE `users` SET `user_active` = 'no', `lastvisit` = '$waktu' WHERE `id_member` = '" .
            $uid['id_member'] . "' ") ;
    
    }
    $update = mysql_query("UPDATE `sessions` SET `logged` = '1', `waktu`= '$waktu' WHERE `id` = '" . $loggedin['session_id'] . "'") ; // Update the current session to log the person out.
    
    if ($update)
    {
        if ($sql)
        { // If it successfully logged the person out then show success message.
        echo '<script language="JavaScript">
        alert("Successfully Logged Out.");
        window.location.href ="home.php";
        </script>';
        header("location: ../login.php");
        } else
        { // If an error occured show error message.
        /*echo '<script language="JavaScript">
        alert("Unknown Error. Unable to logout (error sql).");
        window.location.href ="home.php";
        </script>';*/
        header("location: ../home.php");
        }
    } else
    { // If an error occured show error message.
    /*echo '<script language="JavaScript">
        alert("Unknown Error. Unable to logout (error update).");
        window.location.href ="home.php";
        </script>';*/
    header("location: ../home.php");
    }

}
else
{ 
    echo '<script language="JavaScript">
        alert("You need login to see this page");
        window.location.href ="../index.php";
        </script>';
}

?>