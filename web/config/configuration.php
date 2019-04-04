<?php


/** DATABASE CONNECTION INFORMATION **/
	// The information below is here to provide for the database connection at the bottom of this configuration file.
	// We are using defines. I will be covering a tutorial on at www.pk-tuts.co.uk soon.
define("DATABASE_LOCATION", "localhost");
define("DATABASE_USERNAME", "root"); // u9814486_seagx2018
define("DATABASE_PASSWORD", ""); // backupweb123
define("DATABASE_NAME", "u9814486_seagx2018");
define("URLADMIN", "");
define("URL", "");

/** CONNECT TO DATABASE **/
	// If we cant connect to the database server with the username and password provided. Stop and show error.
	// Once connected If we can not select the database name provided then stop and show error.
$conn = mysql_connect(DATABASE_LOCATION,DATABASE_USERNAME,DATABASE_PASSWORD);
if (!$conn) die ("Could not connect MySQL Server With Username And Password");
mysql_select_db(DATABASE_NAME,$conn) or die ("Could Not Open Database");

function begin_transaction()
{
    global $conn;

    mysql_query("START TRANSACTION", $conn);
}

function roll_back()
{
    global $conn;

    mysql_query("ROLLBACK", $conn);
}

function commit()
{
    global $conn;

    mysql_query("COMMIT", $conn);
}

/** INCLUDE FUNCTIONS **/
	// The functions page included a lot of important functions which are required to use this usersystem.
	// So to save having to type it out on every page we will just include it in the configuration file which is also included on every page.
include("functions.php");
include("sessions.php");
//include("template.php");
//include("template_detail.php");
//include("template_home.php");
//include("template_web.php");

// Success response
function success_response($msg, $results = null)
{
    return [
        'status' => 'success',
        'msg' => $msg,
        'results' => $results
    ];
}

// Error response
function error_response($msg)
{
    return [
        'status' => 'error',
        'msg' => $msg
    ];
}

?>