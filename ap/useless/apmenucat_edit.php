<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page properties
$ls_page_entity = "Menu Category";
$ls_page_title = "$ls_page_entity Entry";
$ls_page_parent = "apmenucat_list.php";
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
	mysql_query("delete from ap_menucat where id_apmenucat= $lp_id", $conn);
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
		$ls_errmsg .= "Name cannot be blank<br/>";
	}
	if (trim($lp_sequence) == "") {
		$ls_errmsg .= "Sequence cannot be blank<br/>";
	}
	// check for duplicates
	if (check_for_duplicates("ap_menucat","name",$lp_name,"id_apmenucat",$lp_id)) {
		$ls_errmsg .= "Duplicate $ls_page_entity! Another $ls_page_entity with the same name exists<br/>";
	}
	
	// if entered data is ok 
	if ($ls_errmsg == "") {
		// set value for unchecked check box fields as unchecked check boxes do not generate key/value pairs and hence do not have any "lp" variable set
		if (!isset($lp_admin_only)) $lp_admin_only = "N";
		
		// check what user wants to do if its a new entry
		if ($lp_id == "new") {
			// insert data into database
			$ls_query = sprintf("insert into ap_menucat(name,sequence,admin_only) values ('%s',%d,'%s')", mysql_real_escape_string($lp_name),$lp_sequence,$lp_admin_only);
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
			$ls_query = sprintf("update ap_menucat set 
								name='%s',
								sequence=%d,
								admin_only='%s'
						where id_apmenucat = %d", mysql_real_escape_string($lp_name),$lp_sequence,$lp_admin_only,$lp_id);
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
		$result = mysql_query("select * from ap_menucat where id_apmenucat = $lp_id", $conn);
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
		// default values for check boxes, radios, list boxes
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
																	<td style="width:160px;">Name:</td> 
																	<td><input name="name" type="text" id="seq" size="50" maxlength="50" value="<?php if( isset($row) ) { echo $row['name']; } elseif( isset($lp_name)) {echo $lp_name;}?>"/></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Sequence:</td> 
																	<td><input name="sequence" type="text" id="seq" size="50" maxlength="50" value="<?php if( isset($row) ) { echo $row['sequence']; } elseif( isset($lp_sequence)) {echo $lp_sequence;}?>"/></td>  
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

if ($lp_id <> "new") {
	// set child listing values
	$ls_child_entity = "Menu Option";
	$ls_child_list_title = 'List of Menu Options';
	$ls_child_edit_program_file = 'apmenuoptions_edit.php';
	$ls_child_edit_col_name_id = 'id_menuoption';
	$ls_child_edit_col_name_name = 'name';

	require "components/form/child_listing/child_listing_header.php";
?>
															<thead> 
																<tr> 
																	<th style="width:10%;">Sequence</th> 
																	<th style="width:30%;">Name</th> 
																	<th style="width:30%;">URL</th> 
																	<th style="width:10%;">Enabled</th> 
																	<th style="width:10%;">Admin Only</th> 
																	<th>Action</th> 
																</tr> 
															</thead> 
															<tbody> 
<?php
	// Query the database for the list of series
	$result_child_list = mysql_query("select * from ap_menuoptions where id_apmenucat=$lp_id order by sequence, name asc", $conn);
	$no_of_rows_child_list = mysql_num_rows($result_child_list);
	while ($row_child_list = mysql_fetch_assoc($result_child_list)) {
?>
																<tr> 
																	<td align="center"><?php echo $row_child_list['sequence'];?></td>
																	<td><a href="<?php echo $ls_child_edit_program_file;?>?id=<?php echo $row_child_list[$ls_child_edit_col_name_id];?>&parent=<?php echo $lp_id;?>"><?php echo $row_child_list[$ls_child_edit_col_name_name];?></a></td> 
																	<td><?php echo $row_child_list['url'];?></td>
																	<td align="center"><?php if($row_child_list['enabled'] == 'Y') { echo "Yes";} else { echo "No";}?></td>
																	<td align="center"><?php if($row_child_list['admin_only'] == 'Y') { echo "Yes";} else { echo "No";}?></td>
																	<td align="center"><a href="<?php echo $ls_child_edit_program_file;?>?id=<?php echo $row_child_list[$ls_child_edit_col_name_id];?>&parent=<?php echo $lp_id;?>"><img src="images/edit.png" alt="edit" title="edit" style="vertical-align:top" /> Edit</a></td> 
																</tr>
<?php
	}
?>
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="6" style="text-align:left">Count: <?php echo $no_of_rows_child_list;?></th>
																</tr>
															</tfoot> 
<?php
	require "components/form/child_listing/child_listing_footer.php";
}

require "ap_footer.php";
include "../inc/footer.inc.php";
?>
