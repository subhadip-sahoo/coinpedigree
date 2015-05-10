<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page properties
$ls_page_entity = "Menu Option";
$ls_page_title = "$ls_page_entity Entry";
$ls_page_parent = "apmenucat_edit.php?id=$lp_parent";

// if the cancel button is pressed
if (isset( $lp_cancel ) == true ) {
	// go back to where came from
	header("Location: $ls_page_parent") ;
}

// if the delete button is pressed
if (isset( $lp_delete ) == true ) {
	// delete data from database
	mysql_query("delete from ap_menuoptions where id_menuoption= $lp_id", $conn);
	// if no error
	if (mysql_errno() == 0) {
		// go back to where came from
		header("Location: $ls_page_parent");
		return;
	} else {
		// display error
		$ls_errmsg .= mysql_errno() . ": " . mysql_error();
	}
}

// if the save button is pressed
if (isset( $lp_save ) == true ) {
	// validate entered data
	if (trim($lp_name) == "") {
		$ls_errmsg .= "Name cannot be blank<br>";
	}
	if (trim($lp_sequence) == "") {
		$ls_errmsg .= "Sequence cannot be blank<br>";
	}
	if (trim($lp_url) == "") {
		$ls_errmsg .= "URL cannot be blank<br>";
	}
	// check for duplicates
	if (check_for_duplicates("ap_menuoptions","name",$lp_name,"id_menuoption",$lp_id)) {
		$ls_errmsg .= "Duplicate $ls_page_entity! Another $ls_page_entity with the same name exists<br/>";
	}
	
	// if entered data is ok 
	if ($ls_errmsg == "") {
		// set value for unchecked check box fields as unchecked check boxes do not generate key/value pairs and hence do not have any "lp" variable set
		if (!isset($lp_enabled)) $lp_enabled = "N";
		if (!isset($lp_admin_only)) $lp_admin_only = "N";
		// check what user wants to do if its a new entry
		if ($lp_id == "new") {
			// insert data into database
			$ls_query = sprintf("insert into ap_menuoptions(id_apmenucat,name, sequence, url, enabled,admin_only) values (%d,'%s',%d, '%s','%s','%s')", $lp_parent, mysql_real_escape_string($lp_name),$lp_sequence, mysql_real_escape_string($lp_url), $lp_enabled,$lp_admin_only);
			mysql_query($ls_query, $conn);
			// if no error
			if (mysql_errno() == 0) {
				// go back to where came from
				header("Location: $ls_page_parent" );
			} else {
				// display error
				$ls_errmsg = mysql_errno() . ": " . mysql_error();
			}
		} else {
			// update data to database
			$ls_query = sprintf("update ap_menuoptions set 
								name='%s',
								sequence=%d,
								url = '%s',
								enabled = '%s',
								admin_only = '%s'
						where id_menuoption = %d", mysql_real_escape_string($lp_name),$lp_sequence, mysql_real_escape_string($lp_url), $lp_enabled,$lp_admin_only,$lp_id);
			mysql_query($ls_query, $conn);
			// if no error
			if (mysql_errno() == 0) {
				// go back to where came from
				header("Location: $ls_page_parent" ); 
			} else {
				// display error
				$ls_errmsg = mysql_errno() . ": " . mysql_error();
			}
		}
	}
} else {
	if ($lp_id != "new") {
		// Query the database for the list of series
		$result = mysql_query("select * from ap_menuoptions where id_menuoption = $lp_id", $conn);
		// get number of rows returned
		$no_of_rows = mysql_num_rows($result);
		// there has to be only one row returned. if not
		if ($no_of_rows != 1 ) {
			// go back to where came from
			header("Location: $ls_page_parent"); 
		}
		// fetch row to edit
		$row = mysql_fetch_assoc($result);
		extract($row,EXTR_PREFIX_ALL,"lp");	
	} else {
		// set default values for ddlb, check box, radio etc.
		$lp_enabled = "N";
		$lp_admin_only = "N";
	}
}
require "ap_header.php";
require "components/form/div_page_head.php";
?>
<script language="javascript" type="text/javascript" src="../inc/qsslib.js"></script>
<script language="javascript">
function QssFormValidator(theForm) {
	if (!validateText(theForm.name, "Name", 50, 1, 0)) return (false);
	if (!validateText(theForm.sequence, "Sequence", 50, 0, 1)) return (false);
	if (!validateText(theForm.url, "URL", 50, 1, 0)) return (false);
	return (true);
}
</script>
<?php
require "components/form/form_header.php";

extract(mysql_fetch_assoc(mysql_query("select name as menu_category from ap_menucat where id_apmenucat = $lp_parent",$conn)),EXTR_PREFIX_ALL,"ls");
?>
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter"> 
															<tbody> 
																<tr> 
																	<td style="width:160px;">Menu Category:</td> 
																	<td><?php echo $ls_menu_category;?></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Name:</td> 
																	<td><input name="name" type="text" id="name" size="50" maxlength="50" value="<?php if( isset($lp_name)) {echo $lp_name;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Sequence:</td> 
																	<td><input name="sequence" type="text" id="sequence" size="50" maxlength="50" value="<?php if( isset($lp_sequence)) {echo $lp_sequence;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">URL:</td> 
																	<td><input name="url" type="text" id="seq" size="50" maxlength="50" value="<?php if( isset($lp_url)) {echo $lp_url;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Enabled:</td> 
																	<td><input type="checkbox" name="enabled" value="Y" <?php if ($lp_enabled=='Y') echo " checked ";?>></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Admin Only Access:</td> 
																	<td><input type="checkbox" name="admin_only" value="Y" <?php echo $lp_admin_only == "Y" ? "checked" : "";?>></td>
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
