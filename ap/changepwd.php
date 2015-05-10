<?php
include "../inc/header.inc.php";
include "forcelogin.php";

// set page properties
$ls_page_entity = "Change Password";
$ls_page_title = "$ls_page_entity";
$ls_page_parent = "home.php";
if (isset($lp_back2listpage)) {
	$ls_page_parent .= "?page=$lp_back2listpage";
}

// if the cancel button is pressed
if (isset( $lp_cancel ) == true ) {
	// go back to where came from
	header("Location: $ls_page_parent") ;
}

if(isset($lp_save)==true) {
	$result = $conn->prepare("select password from users where id_user = :id_user");
	$result->execute(array(":id_user"=>$_SESSION['ap_login_id']));
	
	$row = $result->fetch(PDO::FETCH_ASSOC);
	extract($row,EXTR_PREFIX_ALL,"ls");
	
	if ($ls_password <> $lp_txt_oldpass) {  
		$ls_errmsg.="Wrong existing password<br>"; 
	}
	if ( $lp_txt_confirm <> $lp_txt_newpass) {  
		$ls_errmsg.="New password and confirm password do not match<br>"; 
	}
	if ($ls_errmsg == "") {
		$ls_query = "update users set password = :password where id_user = :id_user";
		$result_update = $conn->prepare($ls_query);
		$result_update->execute(array(":password"=>$lp_txt_newpass,":id_user"=>$_SESSION['ap_login_id']));
		
		if ($result_update->errorCode() == 0) {
			header("Location: $ls_page_parent");
		}
		else {
			$la_errors = $result_update->errorInfo();
			$ls_errmsg.="Password could not be modified. Error No: " . $la_errors[0] . ": " . $la_errors[2];
		}
	}
}

require "ap_header.php";
require "components/form/div_page_head.php";
?>
<script language="javascript" src="../inc/qsslib.js"></script>
<script language="javascript">
function QssFormValidator(theForm) {
	return (true);
}
</script>
<?php
require "components/form/form_header.php";
?>
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter"> 
															<tbody> 
																<tr> 
																	<td style="width:160px;">Old Password:</td> 
																	<td><input name="txt_oldpass" type="password" id="txt_oldpass" size="50" maxlength="50" value="<?php if( isset($lp_txt_oldpass)) {echo $lp_txt_oldpass;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">New Password:</td> 
																	<td><input name="txt_newpass" type="password" id="txt_newpass" size="50" maxlength="50" value="<?php if( isset($lp_txt_newpass)) {echo $lp_txt_newpass;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Confirm New Password:</td> 
																	<td><input name="txt_confirm" type="password" id="txt_confirm" size="50" maxlength="50" value="<?php if( isset($lp_txt_confirm)) {echo $lp_txt_confirm;}?>"/></td>  
																</tr> 
															</tbody>
														</table>
													</div>
												</div>
<?php
require "components/form/standard_button_set.php";
require "components/form/form_footer.php";

require "ap_footer.php";
include "../inc/footer.inc.php";
?>