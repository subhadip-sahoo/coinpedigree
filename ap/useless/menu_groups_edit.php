<?php
include "../inc/header.inc.php";
include "forcelogin.php";

// set page properties
$ls_page_entity = "Menu Groups";
$ls_page_title = "$ls_page_entity Entry";
// get parent of parent
extract(mysql_fetch_assoc(mysql_query("select id_inst as parent_of_parent from sections where id_section = $lp_parent",$conn)),EXTR_PREFIX_ALL,"ll");
$ls_page_parent = "sections_edit.php?id=$lp_parent&parent=$ll_parent_of_parent";


// if the cancel button is pressed
if (isset( $lp_cancel ) == true ) {
	// go back to where came from
	header("Location: $ls_page_parent") ;
}

// if the delete button is pressed
if (isset( $lp_delete ) == true ) {
	// delete data from database
	mysql_query("delete from menu_groups where id_menu_group = $lp_id", $conn);
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
	if (trim($lp_menu_group_name) == "") {
		$ls_errmsg .= "Menu Group name cannot be blank<br/>";
	}
	if (trim($lp_sequence) == "") {
		$ls_errmsg .= "Sequence cannot be blank<br/>";
	}
	// check for duplicates
	if (check_for_duplicates("menu_groups","menu_group_name",$lp_menu_group_name,"id_menu_group",$lp_id,"id_section = $lp_parent")) {
		$ls_errmsg .= "Duplicate $ls_page_entity! Another $ls_page_entity with the same name exists<br/>";
	}
	
	// if entered data is ok 
	if ($ls_errmsg == "") {
		// check what user wants to do if its a new entry
		if ($lp_id == "new") {
			// insert data into database
			$ls_query = sprintf("insert into menu_groups(id_section, menu_group_name, sequence) 
									values (%d,'%s',%d)", 
									$lp_parent,
									mysql_real_escape_string($lp_menu_group_name),
									$lp_sequence);
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
			$ls_query = sprintf("update menu_groups set
								menu_group_name='%s',
								sequence=%d
						where id_menu_group = %d", 
							mysql_real_escape_string($lp_menu_group_name),
							$lp_sequence,
							$lp_id);
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
		$result = mysql_query("select * from menu_groups where id_menu_group = $lp_id", $conn);
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
	}
}
require "ap_header.php";
require "components/form/div_page_head.php";
?>
<script language="javascript" type="text/javascript" src="../inc/qsslib.js"></script>
<script language="javascript">
function QssFormValidator(theForm) {
	if (!validateText(theForm.menu_group_name, "Menu Group Name", 50, 1, 0)) return (false);
	if (!validateText(theForm.sequence, "Sequence", 50, 0, 1)) return (false);
	return (true);
}
</script>
<?php
require "components/form/form_header.php";

extract(mysql_fetch_assoc(mysql_query("select section_name as parent_name, inst_name from sections join institutes on sections.id_inst = institutes.id_inst where id_section = $lp_parent",$conn)),EXTR_PREFIX_ALL,"ls");
?>
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter"> 
															<tbody> 
																<tr> 
																	<td style="width:160px;">Institute:</td> 
																	<td><?php echo $ls_inst_name;?></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Section:</td> 
																	<td><?php echo $ls_parent_name;?></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Menu Group Name:</td> 
																	<td><input name="menu_group_name" type="text" id="menu_group_name" size="50" maxlength="50" value="<?php if( isset($lp_menu_group_name)) {echo $lp_menu_group_name;}?>"/></td>  
																</tr>
																<tr> 
																	<td style="width:160px;">Sequence:</td> 
																	<td><input name="sequence" type="text" id="sequence" size="50" maxlength="50" value="<?php if( isset($lp_sequence)) {echo $lp_sequence;}?>"/></td>  
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
	$ls_child_entity = "Menus";
	$ls_child_list_title = 'List of Menus';
	$ls_child_edit_program_file = 'menus_edit.php';
	$ls_child_edit_col_name_id = 'id_menu';
	$ls_child_edit_col_name_name = 'menu_text';

	require "components/form/child_listing/child_listing_header.php";
?>
															<thead> 
																<tr> 
																	<th>Menu Text</th> 
																	<th>Seqeunce</th> 
																	<th>Action</th> 
																</tr> 
															</thead> 
															<tbody> 
<?php
	// Query the database for the list of series
	$result_child_list = mysql_query("select * from menus where id_menu_group=$lp_id order by sequence asc", $conn);
	$no_of_rows_child_list = mysql_num_rows($result_child_list);
	while ($row_child_list = mysql_fetch_assoc($result_child_list)) {
?>
																<tr> 
																	<td><a href="<?php echo $ls_child_edit_program_file;?>?id=<?php echo $row_child_list[$ls_child_edit_col_name_id];?>&parent=<?php echo $lp_id;?>"><?php echo $row_child_list[$ls_child_edit_col_name_name];?></a></td> 
																	<td align="center"><?php echo $row_child_list['sequence'];?></td>
																	<td align="center"><a href="<?php echo $ls_child_edit_program_file;?>?id=<?php echo $row_child_list[$ls_child_edit_col_name_id];?>&parent=<?php echo $lp_id;?>"><img src="images/edit.png" width="16" height="16" alt="edit" title="edit" style="vertical-align:top" /> Edit</a></td> 
																</tr>
<?php
	}
?>
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="3" style="text-align:left">Count: <?php echo $no_of_rows_child_list;?></th>
																</tr>
															</tfoot> 
<?php
	require "components/form/child_listing/child_listing_footer.php";
}

require "ap_footer.php";
include "../inc/footer.inc.php";
?>