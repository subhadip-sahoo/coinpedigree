<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page properties
$ls_page_entity = "User";
$ls_page_title = "$ls_page_entity Entry";
$ls_page_parent = "users_list.php";
if (isset($lp_back2listpage)) {
	$ls_page_parent .= "?page=$lp_back2listpage";
}

// if the cancel button is pressed
if (isset( $lp_cancel ) == true ) {
	// go back to where came from
	header("Location: $ls_page_parent") ;
}

// if the delete button is pressed
if (isset( $lp_delete ) == true ) {
	// delete data from database
	$result_delete = $conn->query("delete from users where id_user = $lp_id");
	// if no error
	if ($result_delete->errorCode() == "0000") {
		// go back to where came from
		header("Location: $ls_page_parent");
		return;
	} else {
		// display error
		$la_errors = $result_delete->errorInfo();
		$ls_errmsg .= $la_errors[0] . ": " . $la_errors[2];
	}
}

// if the save button is pressed
if (isset( $lp_save ) == true ) {
	// validate entered data
	if (trim($lp_user_name) == "") {
		$ls_errmsg .= "User name cannot be blank<br/>";
	}
	if (trim($lp_password) == "") {
		$ls_errmsg .= "Password cannot be blank<br/>";
	}
	if (trim($lp_email) == "") {
		$ls_errmsg .= "Email cannot be blank<br/>";
	}
	if (trim($lp_user_fullname) == "") {
		$ls_errmsg .= "User fullname cannot be blank<br/>";
	}
	// check for duplicates
	if (check_for_duplicates("users","user_name",$lp_user_name,"id_user",$lp_id)) {
		$ls_errmsg .= "Duplicate $ls_page_entity! Another $ls_page_entity with the same name exists<br/>";
	}
	
	// if entered data is ok 
	if ($ls_errmsg == "") {
		// check what user wants to do if its a new entry
		if ($lp_id == "new") {
			// insert data into database
			$ls_query = "insert into users(user_name, password, email, user_fullname, user_status, user_type) 
									values (:user_name, :password, :email, :user_fullname, :user_status, :user_type)";

			$result_insert = $conn->prepare($ls_query);
			$result_insert->execute(array(
					":user_name"=>$lp_user_name,
					":password"=>$lp_password,
					":email"=>$lp_email,
					":user_fullname"=>$lp_user_fullname,
					":user_status"=>$lp_user_status,
					":user_type"=>$lp_user_type
					));
			
			// if no error
			if ($result_insert->errorCode() == "0000") {
				$lp_id = $conn->lastInsertId();
			} else {
				$la_errors = $result_insert->errorInfo();
				$ls_errmsg .= $la_errors[0]." : ".$la_errors[2]; 
			}
		} else {
			// update data to database
			$ls_query = "update users set 
								user_name = :user_name,
								password = :password,
								email = :email,
								user_fullname = :user_fullname,
								user_status = :user_status,
								user_type = :user_type
						where id_user = :id_user";

			$result_update = $conn->prepare($ls_query);
			$result_update->execute(array(
					":user_name"=>$lp_user_name,
					":password"=>$lp_password,
					":email"=>$lp_email,
					":user_fullname"=>$lp_user_fullname,
					":user_status"=>$lp_user_status,
					":user_type"=>$lp_user_type,
					":id_user"=>$lp_id
					));
			
			// if no error
			if ($result_update->errorCode() == "0000") {
				
			} else {
				$la_errors = $result_update->errorInfo();
				$ls_errmsg .= $la_errors[0]." : ".$la_errors[2];
			}
		}
		// if no error
		if ($ls_errmsg == "") {
			// do not go back
			// go back to where came from
			header("Location: $ls_page_parent" );
		} else {
			// display error
			$ls_errmsg = $ls_errmsg;
		}
	}
} else {
	if ($lp_id != "new") {
		// Query the database for the list of series
		$result = $conn->query("select * from users where id_user = $lp_id");
		
		// get number of rows returned
		$no_of_rows = $result->rowCount();
		// there has to be only one row returned. if not
		if ($no_of_rows != 1 ) {
			// go back to where came from
			header("Location: $ls_page_parent"); 
		}
		// fetch row to edit
		$row = $result->fetch();
		extract($row,EXTR_PREFIX_ALL,"lp");	
	} else {
		// set default values for ddlb, check box, radio etc.
		$lp_user_status="A";
		$lp_user_type="O";
	}
}
require "ap_header.php";
require "components/form/div_page_head.php";
?>
<script language="javascript" type="text/javascript" src="../inc/qsslib.js"></script>
<script language="javascript">
function QssFormValidator(theForm) {
	if (!validateText(theForm.user_name, "User Name", 20, 1, 0)) return (false);
	if (!validateText(theForm.password, "Password", 20, 1, 0)) return (false);
	if (!validateText(theForm.email, "Email", 50, 0, 0, 0, 1)) return (false);
	if (!validateText(theForm.user_name, "User Full Name", 50, 1, 0)) return (false);
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
																	<td style="width:160px;">User Name:</td> 
																	<td><input name="user_name" type="text" id="user_name" size="50" maxlength="20" value="<?php if( isset($lp_user_name)) {echo $lp_user_name;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Password:</td> 
																	<td><input name="password" type="text" id="password" size="50" maxlength="20" value="<?php if( isset($lp_password)) {echo $lp_password;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Email:</td> 
																	<td><input name="email" type="text" id="email" size="50" maxlength="50" value="<?php if( isset($lp_email)) {echo $lp_email;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Full Name:</td> 
																	<td><input name="user_fullname" type="text" id="user_fullname" size="50" maxlength="50" value="<?php if( isset($lp_user_fullname)) {echo $lp_user_fullname;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Status:</td> 
																	<td><input type="radio" name="user_status" value="A" <?php echo $lp_user_status == "A" ? " checked " : "";?>>Active&nbsp;&nbsp;<input type="radio" name="user_status" value="S" <?php echo $lp_user_status == "S" ? " checked " : "";?>>Suspended</td>
																</tr> 
																<tr> 
																	<td style="width:160px;">Type:</td> 
																	<td><input type="radio" name="user_type" value="A" <?php echo $lp_user_type == "A" ? " checked " : "";?>>Administrator&nbsp;&nbsp;<input type="radio" name="user_type" value="O" <?php echo $lp_user_type == "O" ? " checked " : "";?>>Operator</td>
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