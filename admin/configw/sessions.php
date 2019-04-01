<?php

/**
 * @author global
 * @copyright 2010
 */

//session_start(); 

function login_validate() 
{ 
  $timeout = 1800;
  $_SESSION["expires_by"] = time() + $timeout;
} 
function login_check() 
{ 
  $exp_time = $_SESSION["expires_by"]; 
  if (time() < $exp_time)  
  { 
    login_validate(); 
    return true; 
  }  
  else  
  { 
    unset($_SESSION["expires_by"]); 
    return false; 
  } 
} 
?>