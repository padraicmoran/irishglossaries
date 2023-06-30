<?php
// rename this file as connection.php
// supply MySQL authentication details as appropriate

// Apache settings
header("Content-type: text/html; charset=utf-8");
ini_set('mbstring.internal_encoding', 'utf-8');

if ($_SERVER['SERVER_NAME'] == 'irishglossaries') {
   // development server
   $devServer = true;
   $link = mysqli_connect("HOSTNAME", "USER", "PWD");
   mysqli_select_db($link, "DB_NAME");

	$link->query("SET GLOBAL regexp_time_limit=1024"); 
	$link->query("SET NAMES 'utf8'"); 
  
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
   // live server
   $devServer = false;
   $link = mysqli_connect("HOSTNAME", "USER", "PWD");
   mysqli_select_db($link, "DB_NAME");

	$link->query("SET NAMES 'utf8'"); 
	$link->query("SET CHARACTER SET utf8");  
	$link->query("SET SESSION collation_connection = 'utf8_unicode_ci'");

   error_reporting(0);
}

?>
