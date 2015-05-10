<?php
include "../inc/header.inc.php";
// if already logged in then go to home page
if (isset($_SESSION['ap_login_id']) == true) {
	header("Location: home.php"); 
}

if (isset( $lp_btn_login ) == true ) {
	// check for valid inputs
	if(trim($lp_login_id) == "") {
		$ls_warningmsg = "Please enter the user name";
	}
	// if no warnings
	if ($ls_warningmsg == "") {
		$ls_execute_parameters = array(":user_name"=>$lp_login_id,":password"=>$lp_pwd);
		
		// Query the database for the list of series
		$sql = "select * from users where user_status = 'A' and user_name = :user_name and password = :password";
		$result = $conn->prepare($sql);
		$result->execute($ls_execute_parameters);
		
		$no_of_rows = $result->rowCount();
		//echo "<h1>".$no_of_rows."</h1>";
		
		if ($no_of_rows > 0 ) {
			$result->setFetchMode(PDO::FETCH_ASSOC);
			$row = $result->fetch();
			
			// set details to session
			$_SESSION['ap_login_id'] = $row['id_user'];
			$_SESSION['user_fullname'] = $row['user_fullname'];
			$_SESSION['user_type'] = $row['user_type'];
			$_SESSION['list_view_rows_per_page'] = 20; // by default show 20 rows per page
			$ls_current_datetime = date("Y-m-d H:i:s");
			
			// update last login info
			$ls_insert_query = "insert into login_history (id_user, login_time, login_ip) values(:id_user,:login_time,:login_ip)";
			$result_insert_query = $conn->prepare($ls_insert_query);
			
			$la_insert_execute_parameters = array(":id_user"=>$row['id_user'],":login_time"=>$ls_current_datetime,":login_ip"=>$_SERVER["REMOTE_ADDR"]);
			$result_insert_query->execute($la_insert_execute_parameters);
			
			// provide access based on DB definition
			header("Location: home.php"); 
		}
		else {
			$ls_errmsg = "Invalid user id / password. Please try again.";
		}
	}
}

// set page title
$ls_page_title = 'Admin Panel Home';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title><?php echo TEXT_WEBSITE_ADMIN_PANEL_HEADING;?></title>
<link rel="stylesheet" href="scripts/qss.css" type="text/css" />
<script type="text/javascript" language="javascript" src="scripts/custom.js"></script>
</head>
<body>
<div id="container">
	<div class="logo_bg">
		<div class="banner">
			<div class="co-name"><?php echo TEXT_WEBSITE_ADMIN_PANEL_HEADING;?></div>
		</div>
	</div>
	<div style="border-bottom:solid 2px #ddd;"></div>

	<div id="wrap">
<?php
if ($ls_warningmsg <> "") {
?>
	<div class="msg-warning"> 
	<img src="images/messagebox_warning.png" alt=""/> 
			<p><?php echo $ls_warningmsg;?></p> 
</div>
<?php
}
?>
<?php
if ($ls_errmsg <> "") {
?>
	<div class="msg-error"> 
	<img src="images/remove.png" alt=""/> 
			<p><?php echo $ls_errmsg;?></p> 
</div> 
<?php
}
?>
		<div class="block"> 
		<form method="post"> 
			<div class="left"></div> 
			<div class="right" style="left: 146px; top: 20px" id="log"> 
				<div class="div-row"> 
					<input type="text" id="" name="login_id" onblur="if(this.value=='')this.value='User Name'" onfocus="if(this.value=='User Name')this.value=''" value="User Name" /> 
				</div> 
				<div class="div-row"> 
					<input type="password" id="" name="pwd" onblur="if(this.value=='')this.value='Password'" onfocus="if(this.value=='Password')this.value=''" value="Password" /> 
				</div>
				<div class="fp-row">
					<a href="#" onclick="showDiv('fg-pwd') ;HideDiv('log')">Forget your password?</a>
				</div> 
				<div class="rm-row"> 
					<input type="checkbox" value="" name="rm" id="remember"/> <label for="remember">Remember me?</label>
				</div> 
				<div class="send-row"> 
					<button id="login" value="" type="submit" name="btn_login"></button> 
				</div> 
			</div>
			<div class="right" style="left: 146px; top: 20px;visibility:hidden;" id="fg-pwd"> 
				<div class="div-row"> 
					<input type="text" id="" name="" onblur="if(this.value=='')this.value='enter your e-mail'" onfocus="if(this.value=='enter your e-mail')this.value=''" value="enter your e-mail" /> 
				</div> 
				<div class="rm-row"><p><a href="login.html">Click here to login</a></p></div> 
				<div class="send-row"> 
					<button id="pwd" value="" type="submit" name="login"></button> 
				</div>
				<div id="pwd-msg">
					<p>New password will now be delivered to the email that you have entered above. Please check your inbox.</p>
				</div> 
			</div> 
		</form> 
		<div id="footer">
			Powered by: <a href="http://qss.in" target="_blank">Quintessential Software Solutions Pvt Ltd</a>
		</div>
		</div> 
	</div>
</div>
</body>

</html>
<?php
include "../inc/footer.inc.php";
?>
