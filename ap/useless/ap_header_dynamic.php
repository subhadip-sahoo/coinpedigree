<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title><?php echo isset($ls_page_title) ? $ls_page_title : TEXT_WEBSITE_ADMIN_PANEL_HEADING ;?></title>
<link rel="stylesheet" href="scripts/qss.css" type="text/css" />
<script type="text/javascript" language="javascript" src="scripts/custom.js"></script>
</head>

<body onload="StartDate();">
<div id="container">
	<div class="logo_bg">
		<div class="banner">
			<div class="time">
				<form name="CForm"> 
				<input type="text" name="CDate" size="28" /> 
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
<?php
$ls_where = "";
if ($_SESSION["user_type"] <> "A") {
	$ls_where = "and admin_only <> 'Y'";
}
$sql = "select * from ap_menucat where 1=1 $ls_where order by sequence";
$res_menubar = mysql_query($sql,$conn);
while ($row_menubar = mysql_fetch_assoc($res_menubar)) {
?>
			<li class="sub"><?php echo $row_menubar['name'];?>
				<ul>
<?php
	$res_menus = mysql_query("select * from ap_menuoptions where enabled='Y' $ls_where and id_apmenucat=" . $row_menubar['id_apmenucat'] . " order by sequence",$conn);
	while ($row_menu_options = mysql_fetch_assoc($res_menus)) {
?>
					<li><a href="<?php echo $row_menu_options['url'];?>"><?php echo $row_menu_options['name'];?></a></li>
<?php
	}
?>
				</ul>
			</li>
<?php
}
?>
<!--			<li><a href="edit_page.html">Edit Page Type</a>
				<ul>
					<li><a href="edit_content_area.html">Edit content area</a></li>
					<li class="arrow"><a href="#">Sample Pages...</a>
						<ul>
							<li><a href="#">Files</a></li>
							<li><a href="#">Products</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><a href="form.html">Page List</a></li>	
			<li class="current">User</li>	
-->
		</ul>
	</div>
	<div id="wrapper">
		<div class="box">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="lt-side" align="left" valign="top">
						<div class="pnl">
							<div class="pnl-head-top">Most used options<img src="images/menu-icon.gif" alt="" style="vertical-align:middle;padding-left:50px;"/></div>
							<div class="pnl-cont">
								<ul>
									<li><a href="#">Dummu Option #1</a></li>
									<li><a href="#">Dummu Option #2</a></li>
									<li><a href="#">Dummu Option #3</a></li>
									<li><a href="#">Dummu Option #4</a></li>
								</ul>
							</div>
							<div class="pnl-bot"></div>
						</div>
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