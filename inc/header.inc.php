<?php
set_time_limit(0);
header ("Pragma: no-cache");
session_start();
date_default_timezone_set('Asia/Calcutta');
$ls_errmsg = "";
$ls_warningmsg = "";
$ls_information_msg = "";
$ls_noreply_email_from = "donotreply@domain.in";

// Import passed parameters
import_request_variables("gP", "lp_");

// include file for database access codes and settings
require "db_details.php";

//PDO
try{
	$conn = new PDO("mysql:host=$ls_dbserver;dbname=$ls_dbname", $ls_userid, $ls_userpass);
}
catch(PDOException $pe){
	die('Connection error, because: ' .$pe->getMessage());
}

require "defines.php";

// QSS PHP Lib
require "qssphplib.inc.php";
include "convert_video.php";
require "custom_lib.php";

// Report all PHP errors
error_reporting(E_ALL);
?>
