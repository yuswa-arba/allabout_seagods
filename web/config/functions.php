<?php

function protection($field, $encrypt = false)
{ // Start Of Function.
    if (empty($field)) // Checks if $field is empty.

    {
        $return['error'] = "Value Empty"; // If $field is found to be empty it will return an error message.
    } else {
        if (is_array($field)) // Checks if $field is an array or not.

        { // If it is an array then carry on.
            foreach ($field as $key => $value) { // Carry out the foreach on the $field assigning the key and value of the array to $key and $value.
                $key = strip_tags($key); // Remove any tags from the field
                $value = strip_tags($value); // Remove any tags from the field
                $return[$key] = htmlentities($value, ENT_QUOTES); // Convert all applicable characters to HTML entities
            }
        } else // If $field isnt an array carry out the following.

        {
            $field = strip_tags($field); // Remove any tags from the field.
            $return = htmlentities($field, ENT_QUOTES); // Convert all applicable characters to HTML entities.
        }
    }
    return $return; // Return $return
}

function rand_numb($number,$max_length)
{
	$number_length = strlen($number);
	$zero_length = $max_length - $number_length;
	$zero = "";
	for($i=1;$i<=$zero_length;$i++)
	{
		$zero .= '0';
	}
	return $zero.$number;
}

function logged_in_lama()
{
    $sess_id = (isset($_COOKIE["PHPSESSID"])!="") ? ($_COOKIE["PHPSESSID"]) : ""; // Remove any injection and bugout stuff from the session
    // Retrieve the sessions tables wheres the session id above matches the session id in the sessions table
   
    $sess_check = mysql_query("SELECT * FROM `sessions` WHERE `sess_id` = '" . $sess_id ."' && `logged` = '0'");
	
    // If there is no session in the table where they are not logged in, show them as not logged in
    if (mysql_num_rows($sess_check)) { // Check if there is a row in the table.
        $s = mysql_fetch_array($sess_check); // Retrieve the data from the tables.
        $uinfo = mysql_query("SELECT * FROM `users` WHERE `group` = 'member' AND `id_user` = '" . $s['uid'] . "'"); // Retrieve the users table where the uid matches the uid in the sessions table
        
        $u = mysql_fetch_array($uinfo); // Retrieve the data from the tables.
        // Put the data into an array to be returned.
        $return = array("session_id" => $s['id'],
                        "session_sessid" => $s['sess_id'],
                        "id_user" => $u['id_user'], 
                        "lastvisit" => $u['lastvisit'],
			"username" => $u['username'], 
                        "group" => $u['group']);
	
        // Return the array
        return $return;
    } else {
        // Return nothing
        return false;
    }
}
function logged_inadmin_lama()
{
    
    $sess_id = (isset($_COOKIE["PHPSESSID"])!="") ? ($_COOKIE["PHPSESSID"]) : ""; // Remove any injection and bugout stuff from the session
    // Retrieve the sessions tables wheres the session id above matches the session id in the sessions table
   
    $sess_check = mysql_query("SELECT * FROM `sessions` WHERE `sess_id` = '" . $sess_id ."' && `logged` = '0'");
	
    // If there is no session in the table where they are not logged in, show them as not logged in
    if (mysql_num_rows($sess_check)) { // Check if there is a row in the table.
        $s = mysql_fetch_array($sess_check); // Retrieve the data from the tables.
        $uinfo = mysql_query("SELECT * FROM `users` WHERE `group`='admin' AND `id_user` = '" . $s['uid'] . "'"); // Retrieve the users table where the uid matches the uid in the sessions table
        
        $u = mysql_fetch_array($uinfo); // Retrieve the data from the tables.
        // Put the data into an array to be returned.
        $return = array("session_id" => $s['id'],
                        "session_sessid" => $s['sess_id'],
                        "id_user" => $u['id_user'], 
                        "lastvisit" => $u['lastvisit'],
			"username" => $u['username'], 
                        "group" => $u['group']);
	
        // Return the array
        return $return;
    } else {
        // Return nothing
        return false;
    }
}

function logged_in()
{
    $sess_id = (isset($_COOKIE["PHPSESSID"])!="") ? ($_COOKIE["PHPSESSID"]) : ""; // Remove any injection and bugout stuff from the session
    // Retrieve the sessions tables wheres the session id above matches the session id in the sessions table

    $sess_check = mysql_query("SELECT * FROM `sessions`, `users`
			      WHERE `sessions`.`uid` = `users`.`id_user`
			      AND `users`.`group` != 'admin' AND `sessions`.`sess_id` = '" . $sess_id ."'
			      AND `sessions`.`logged` = '0'");
	
    // If there is no session in the table where they are not logged in, show them as not logged in
    if (mysql_num_rows($sess_check)) { // Check if there is a row in the table.
        $s = mysql_fetch_array($sess_check); // Retrieve the data from the tables.
        $uinfo = mysql_query("SELECT `users`.*, `member`.`currency_code`, `member`.`subscribe` FROM `users`, `member` 
            WHERE `users`.`id_member` = `member`.`id_member` AND `users`.`group` != 'admin' AND `users`.`id_user` = '" . $s['uid'] . "'"); // Retrieve the users table where the uid matches the uid in the sessions table
        //echo "SELECT * FROM `users` WHERE `group` != 'admin' AND `id_user` = '" . $s['uid'] . "';";
        $u = mysql_fetch_array($uinfo); // Retrieve the data from the tables.
        // Put the data into an array to be returned.
        $return = array("session_id" => $s['id'],
                        "session_sessid" => $s['sess_id'],
                        "id_user" => $u['id_user'],
                        "id_member" => $u['id_member'],
                        "username" => $u['username'],
                        "lastvisit" => $u['lastvisit'],
                        "group" => $u['group'],
                        "currency_code" => $u['currency_code'],
                        "subscribe" => $u["subscribe"]);
	
        // Return the array
        return $return;
    } else {
        // Return nothing
        return false;
    }
}
function logged_inadmin()
{
    $sess_id = (isset($_COOKIE["PHPSESSID"])!="") ? ($_COOKIE["PHPSESSID"]) : ""; // Remove any injection and bugout stuff from the session
    // Retrieve the sessions tables wheres the session id above matches the session id in the sessions table

    $sess_check = mysql_query("SELECT * FROM `sessions`, `users`
			      WHERE `sessions`.`uid` = `users`.`id_user`
			      AND `users`.`group` = 'admin' AND `sessions`.`sess_id` = '" . $sess_id ."'
			      AND `sessions`.`logged` = '0'");
	
    // If there is no session in the table where they are not logged in, show them as not logged in
    if (mysql_num_rows($sess_check)) { // Check if there is a row in the table.
        $s = mysql_fetch_array($sess_check); // Retrieve the data from the tables.
        $uinfo = mysql_query("SELECT `users`.*,`member`.`firstname`,`member`.`lastname` FROM `users` , `member` WHERE `users`.`id_member`=`member`.`id_member` AND  `group` = 'admin' AND `id_user` = '" . $s['uid'] . "'"); // Retrieve the users table where the uid matches the uid in the sessions table
        
        $u = mysql_fetch_array($uinfo); // Retrieve the data from the tables.
        // Put the data into an array to be returned.
        $return = array("session_id" => $s['id'],
                        "session_sessid" => $s['sess_id'],
                        "id_user" => $u['id_user'],
			"username" => $u['username'],
			"firstname" => $u['firstname'],
			"lastname" => $u['lastname'],
                        "lastvisit" => $u['lastvisit'], 
                        "group" => $u['group']);
	
        // Return the array
        return $return;
    } else {
        // Return nothing
        return false;
    }
}
function check_phpsessid()
{
    //session_start();
    if (empty($_COOKIE['PHPSESSID'])) {
        die("Your cookies are disabled. Please enable them before using this usersystem.");
    }
}

//fungsi paging
function halaman($tot,$perhal,$adj,$file=""){
$adjacents = $adj;
    $total_pages =$tot;
    $targetpage = "$file";
    $limit = $perhal;  
    $page = isset($_GET['page']) ? (int)$_GET['page'] : "" ;
    if($page) 
        $start = ($page - 1) * $limit;
    else
        $start = 0;     

    if ($page == 0) $page = 1;                    //if no page var is given, default to 1.
    $prev = $page - 1;                            //previous page is page - 1
    $next = $page + 1;                            //next page is page + 1
    $lastpage = ceil($total_pages/$limit);        //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1;                        //last page minus 1

    $pagination = "";
    if($lastpage > 1)
    {    
        $pagination .= "<div class=\"pagination\">";
        //previous button
        if ($page > 1) 
            $pagination.= "<a href=\"$targetpage"."page=$prev\"> &#171; previous</a>";
        else
            $pagination.= "<span class=\"disabled\"> &#171; previous</span>";    
        
        //pages    
        if ($lastpage < 7 + ($adjacents * 2))    //not enough pages to bother breaking it up
        {    
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"$targetpage"."page=$counter\">$counter</a>";                    
            }
        }
        elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
        {
            //close to beginning; only hide later pages
            if($page < 1 + ($adjacents * 2))        
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage"."page=$counter\">$counter</a>";                    
                }
                $pagination.= "...";
                $pagination.= "<a href=\"$targetpage"."page=$lpm1\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage"."page=$lastpage\">$lastpage</a>";        
            }
            //in middle; hide some front and some back
            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $pagination.= "<a href=\"$targetpage"."page=1\">1</a>";
                $pagination.= "<a href=\"$targetpage"."page=2\">2</a>";
                $pagination.= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage"."page=$counter\">$counter</a>";                    
                }
                $pagination.= "...";
                $pagination.= "<a href=\"$targetpage"."page=$lpm1\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage"."page=$lastpage\">$lastpage</a>";        
            }
            //close to end; only hide early pages
            else
            {
                $pagination.= "<a href=\"$targetpage"."page=1\">1</a>";
                $pagination.= "<a href=\"$targetpage"."page=2\">2</a>";
                $pagination.= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage"."page=$counter\">$counter</a>";                    
                }
            }
        }
        
        //next button
        if ($page < $counter - 1) 
            $pagination.= "<a href=\"$targetpage"."page=$next\">next &#187; </a>";
        else
            $pagination.= "<span class=\"disabled\">next &#187; </span>";
        $pagination.= "</div>\n";        
    }
        return $pagination;
}

function Rupiah($angka){
    $ang  = "";
    while (strlen($angka) > 3)
    {
        $ang    = "." . substr($angka, -3) . $ang;
        $lbr    = strlen($angka);
        $angka  = substr($angka,0,$lbr-3);
    }
    $ang = 'Rp. '.$angka.$ang;
    
    
    return $ang;
}
function Angka($angka){
    $ang  = "";
    while (strlen($angka) > 3)
    {
        $ang    = "." . substr($angka, -3) . $ang;
        $lbr    = strlen($angka);
        $angka  = substr($angka,0,$lbr-3);
    }
    $ang = $angka.$ang;
    return $ang;
}
//fungsi word limiter
function word_limiter( $text, $limit = 25, $chars = '0123456789' ) {
    if( strlen( $text ) > $limit ) {
        $words = str_word_count( $text, 2, $chars );
        $words = array_reverse( $words, TRUE );
        foreach( $words as $length => $word ) {
            if( $length + strlen( $word ) >= $limit ) {
                array_shift( $words );
            } else {
                break;
            }
        }
        $words = array_reverse( $words );
        $text = implode( " ", $words ) . '&hellip;';
    }
    return $text;
}
function getOSnew() {
    if (isset($_SERVER["HTTP_USER_AGENT"]) OR ($_SERVER["HTTP_USER_AGENT"] != "")) {
        $visitor_user_agent = $_SERVER["HTTP_USER_AGENT"];
    } else {
        $visitor_user_agent = "Unknown";
    }
    // Create list of operating systems with operating system name as array key 
    $oses = array(
        'Mac OS X(Apple)' => '(iPhone)|(iPad)|(iPod)|(MAC OS X)|(OS X)',
        'Apple\'s mobile/tablet' => 'iOS',
        'BlackBerry' => 'BlackBerry',
        'Android' => 'Android',
        'Java Mobile Phones (J2ME)' => '(J2ME\/MIDP)|(J2ME)',
        'Java Mobile Phones (JME)' => 'JavaME',
        'JavaFX Mobile Phones' => 'JavaFX',
        'Windows Mobile Phones' => '(WinCE)|(Windows CE)',
        'Windows 3.11' => 'Win16',
        'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', 
        'Windows 98' => '(Windows 98)|(Win98)',
        'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
        'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
        'Windows 2003' => '(Windows NT 5.2)',
        'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
        'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
        'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'Windows ME' => 'Windows ME',
        'Open BSD' => 'OpenBSD',
        'Sun OS' => 'SunOS',
        'Linux' => '(Linux)|(X11)',
        'Macintosh' => '(Mac_PowerPC)|(Macintosh)',
        'QNX' => 'QNX',
        'BeOS' => 'BeOS',
        'OS/2' => 'OS/2',
        'ROBOT' => '(Spider)|(Bot)|(Ezooms)|(YandexBot)|(AhrefsBot)|(nuhk)|
                    (Googlebot)|(bingbot)|(Yahoo)|(Lycos)|(Scooter)|
                    (AltaVista)|(Gigabot)|(Googlebot-Mobile)|(Yammybot)|
                    (Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)|
                    (Ask Jeeves/Teoma)|(Java/1.6.0_04)'
    );
    foreach ($oses as $os => $pattern) {
        if (preg_match("/$pattern/", $visitor_user_agent)) {
            return $os;
        }
    }
    return 'Unknown';
}

function GetIP()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}

function getBrowserNew() {
    if (isset($_SERVER["HTTP_USER_AGENT"]) OR ($_SERVER["HTTP_USER_AGENT"] != "")) {
        $visitor_user_agent = $_SERVER["HTTP_USER_AGENT"];
    } else {
        $visitor_user_agent = "Unknown";
    }
    $bname = 'Unknown';
    $version = "0.0.0";
 
    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/', $visitor_user_agent) && !preg_match('/Opera/', $visitor_user_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/', $visitor_user_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/', $visitor_user_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/', $visitor_user_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/', $visitor_user_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/', $visitor_user_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } elseif (preg_match('/Seamonkey/', $visitor_user_agent)) {
        $bname = 'Seamonkey';
        $ub = "Seamonkey";
    } elseif (preg_match('/Konqueror/', $visitor_user_agent)) {
        $bname = 'Konqueror';
        $ub = "Konqueror";
    } elseif (preg_match('/Navigator/', $visitor_user_agent)) {
        $bname = 'Navigator';
        $ub = "Navigator";
    } elseif (preg_match('/Mosaic/', $visitor_user_agent)) {
        $bname = 'Mosaic';
        $ub = "Mosaic";
    } elseif (preg_match('/Lynx/', $visitor_user_agent)) {
        $bname = 'Lynx';
        $ub = "Lynx";
    } elseif (preg_match('/Amaya/', $visitor_user_agent)) {
        $bname = 'Amaya';
        $ub = "Amaya";
    } elseif (preg_match('/Omniweb/', $visitor_user_agent)) {
        $bname = 'Omniweb';
        $ub = "Omniweb";
    } elseif (preg_match('/Avant/', $visitor_user_agent)) {
        $bname = 'Avant';
        $ub = "Avant";
    } elseif (preg_match('/Camino/', $visitor_user_agent)) {
        $bname = 'Camino';
        $ub = "Camino";
    } elseif (preg_match('/Flock/', $visitor_user_agent)) {
        $bname = 'Flock';
        $ub = "Flock";
    } elseif (preg_match('/AOL/', $visitor_user_agent)) {
        $bname = 'AOL';
        $ub = "AOL";
    } elseif (preg_match('/AIR/', $visitor_user_agent)) {
        $bname = 'AIR';
        $ub = "AIR";
    } elseif (preg_match('/Fluid/', $visitor_user_agent)) {
        $bname = 'Fluid';
        $ub = "Fluid";
    } else {
        $bname = 'Unknown';
        $ub = "Unknown";
    }
 
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $visitor_user_agent, $matches)) {
        // we have no matching number just continue
    }
 
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($visitor_user_agent, "Version") < strripos($visitor_user_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
 
    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }
 
    return array(
        'userAgent' => $visitor_user_agent,
        'name' => $bname,
        'version' => $version,
        'pattern' => $pattern
    );
}

function generate_transaction_number()
{
    date_default_timezone_set('Asia/Jakarta');

    // Set first number
    $transaction_number = 'TRS';

    // Set number with date time
    $transaction_number .= date('ymdHis');

    // Set random number
    $transaction_number .= rand(10,99);

    // Set number with last custom collection
    $total_transaction = mysql_fetch_array(mysql_query("SELECT count(id_transaction) AS total FROM transaction;"));
    $transaction_number .= $total_transaction['total'] + 1;

    return $transaction_number;
}

?>