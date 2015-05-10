<?php
// if not logged in then go to login page
if (isset($_SESSION['ap_login_id']) == false) {
	$_SESSION['goback'] = $HTTP_SERVER_VARS['PHP_SELF'];
	header("Location: index.php"); 
}
?>