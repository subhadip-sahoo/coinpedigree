<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page properties
$ls_page_entity = "Settings";
$ls_page_title = "$ls_page_entity Management";
$ls_page_parent = "home.php";

// if the cancel button is pressed
if (isset( $lp_cancel ) == true ) {
	// go back to where came from
	header("Location: $ls_page_parent") ;
}

// if the save button is pressed
if (isset( $lp_save ) == true ) {
	
	// if entered data is ok 
	if ($ls_errmsg == "") {
		set_setting(EMAIL_CONTACT,$lp_email_contact,$conn);
                set_setting(COMMUNICATIONS_FROM_EMAIL_ADDRESS, $lp_communications_from_email_address,$conn);
		set_setting(COMMUNICATIONS_FROM_NAME, $lp_communications_from_name,$conn);
		set_setting(BASE_URL, folder_remove_trailing_slash($lp_base_url),$conn);
		set_setting(BASE_DIRECTORY,folder_remove_trailing_slash($lp_base_directory),$conn);
		header("Location: $ls_page_parent") ;
	}
} else {
	$lp_email_contact = get_setting(EMAIL_CONTACT,$conn);
	$lp_communications_from_email_address = get_setting(COMMUNICATIONS_FROM_EMAIL_ADDRESS,$conn);
	$lp_communications_from_name = get_setting(COMMUNICATIONS_FROM_NAME,$conn);
	$lp_base_url = get_setting(BASE_URL,$conn);
	$lp_base_directory = get_setting(BASE_DIRECTORY,$conn);
}

require "ap_header.php";
require "components/form/div_page_head.php";
?>
<script language="javascript" type="text/javascript" src="../inc/qsslib.js"></script>
<script language="javascript">
function QssFormValidator(theForm) {
	if (!validateText(theForm.email_contact, "Contact Email ID", 250, 0, 0, 0 , 1)) return (false);
	if (!validateText(theForm.base_url, "Base URL", 250, 1, 0)) return (false);
	if (!validateText(theForm.base_directory, "Base Directory", 250, 1, 0)) return (false);
	return (true);
}
</script>
<link rel="stylesheet" href="../js/validationEngine.jquery.css" type="text/css"/>
<script src="../js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){	
	// binds form submission and fields to the validation engine
	$("#form_page").validationEngine();	
	});
</script>
										<form id="form_page" name="form_page" ENCTYPE="multipart/form-data" method="post" onSubmit="return (QssFormValidator(this));" action="<?php $_SERVER['PHP_SELF'];?>">
										<div class="result">
<?php
if ($ls_errmsg != "") {
	require "components/form/div_error_box.php";
}
?> 
											<div class="heading"><span>Coin Image Upload</span></div>
											<div class="data-table">
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter"> 
															<tbody> 
																<tr> 
																	<td style="width:160px;">File Upload URL:</td> 
																	<td><input name="base_url" type="text" id="base_url" size="50" maxlength="250" value="<?php echo $lp_base_url;?>"/></td>
																</tr> 
                                                                                                                                <tr> 
																	<td style="width:160px;">File Upload Path:</td> 
																	<td><input name="base_directory" type="text" id="base_directory" size="50" maxlength="250" value="<?php echo $lp_base_directory;?>"/></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="heading"><span>Contact Email Addresses Configuration</span></div>
											<div class="data-table">
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter"> 
															<tbody> 
																<tr> 
																	<td style="width:160px;">Contact Email ID:</td> 
																	<td><input name="email_contact" type="text" id="email_contact" size="50" maxlength="250" value="<?php echo $lp_email_contact;?>"/></td>
																</tr> 
															</tbody>
														</table>
													</div>
												</div>
											</div>
                                                                                        <div class="heading"><span>Communications</span></div>
											<div class="data-table">
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter"> 
															<tbody> 
																<tr> 
																	<td style="width:160px;">From Email Address:</td> 
																	<td><input name="communications_from_email_address" type="text" id="communications_from_email_address" size="50" maxlength="250" value="<?php echo $lp_communications_from_email_address;?>"/></td>
																</tr> 
																<tr> 
																	<td style="width:160px;">From Name:</td> 
																	<td><input name="communications_from_name" type="text" id="communications_from_name" size="50" maxlength="250" value="<?php echo $lp_communications_from_name;?>"/></td>
																</tr> 
															</tbody>
														</table>
													</div>
												</div>
											</div>
<?php
require "components/form/standard_button_set.php";
?>
										</div>
										</form>

<?php
require "ap_footer.php";
include "../inc/footer.inc.php";
?>