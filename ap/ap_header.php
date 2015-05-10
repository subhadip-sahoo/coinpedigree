<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title><?php echo isset($ls_page_title) ? $ls_page_title : TEXT_WEBSITE_ADMIN_PANEL_HEADING ;?></title>
<link rel="stylesheet" href="scripts/qss.css" type="text/css" />
<script type="text/javascript" language="javascript" src="scripts/custom.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<link href="../themes/smoothness/jquery.ui.all.css" rel="stylesheet"/>
<script src="../js/jquery-ui-1.10.0.custom.js"></script>
<link rel="stylesheet" href="../js/validationEngine.jquery.css" type="text/css"/>
<script src="../js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
</head>

<body onload="StartDate();">
<div id="container">
	<div class="logo_bg">
		<div class="banner">
			<div class="time">
				<form name="CForm"> 
				<input type="text" name="CDate" size="32" /> 
				<input type="text" name="Clock12" size="11" /> 
				</form>
			</div>
			<div class="co-name"><?php echo TEXT_WEBSITE_ADMIN_PANEL_HEADING;?></div>
			<div class="wl-cm">Welcome <?php echo $_SESSION['user_fullname'];?> <span class="separator">|</span> Login at <?php echo get_last_login($_SESSION['ap_login_id']);?> <a href="users_history_list.php?crit_id_user=<?php echo $_SESSION['ap_login_id'];?>">History</a> <span class="separator">|</span> <a href="logout.php">Log Off</a></div>
		</div>
	</div>
	<div class="menu">
		<div style="float:left; width:2%;">&nbsp;</div>
		<ul class="sf-menu">
			<li class="sub">Administrative Options
				<ul>
					<li><a href="users_list.php">Manage Users</a></li>
					<li><a href="changepwd.php">Change password</a></li>
					<li><a href="settings.php">Settings</a></li>
				</ul>
			</li>
			<li class="sub">Listings
				<ul>
					<li><a href="item_listing.php">Items Listing</a></li>
					<li><a href="owners_listing.php">Owners Listing</a></li>
					<li><a href="pages.php">Pages</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<div id="wrapper">
		<div class="box">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="lt-side" align="left" valign="top">
						&nbsp;
					</td>
					<td style="width:100%" align="left" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="top"></td>
								<td><img src="images/rt-cr.png" alt="" /></td>
							</tr>
							<tr>
								<td style="width:100%;padding-left:12px;background-color:#fff">
									<div class="rt-side">