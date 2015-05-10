<?php
include "../inc/header.inc.php";
include "forcelogin.php";

// set page properties
$ls_page_entity = "Menu";
$ls_page_title = "$ls_page_entity Entry";
// get parent of parent
extract(mysql_fetch_assoc(mysql_query("select id_section as parent_of_parent from menu_groups where id_menu_group = $lp_parent",$conn)),EXTR_PREFIX_ALL,"ll");
$ls_page_parent = "menu_groups_edit.php?id=$lp_parent&parent=$ll_parent_of_parent";


// if the cancel button is pressed
if (isset( $lp_cancel ) == true ) {
	// go back to where came from
	header("Location: $ls_page_parent") ;
}

// if the delete button is pressed
if (isset( $lp_delete ) == true ) {
	// delete child menus
	mysql_query("delete from menus where id_menu_parent = $lp_id", $conn);
	// delete data from database
	mysql_query("delete from menus where id_menu = $lp_id", $conn);
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
	if (trim($lp_menu_text) == "") {
		$ls_errmsg .= "Menu text cannot be blank<br/>";
	}
	if (trim($lp_sequence) == "") {
		$ls_errmsg .= "Sequence cannot be blank<br/>";
	}
	switch ($lp_link_type) {
		case "N":
			break;
		case "E":
			if (trim($lp_external_link_page) == "") {
				$ls_errmsg .= "External link page cannot be blank<br/>";
			}
			break;
		case "I":
			if (trim($lp_page_code) == "") {
				$ls_errmsg .= "Content page code cannot be blank<br/>";
			}
			break;
		case "P":
			if (trim($lp_page_name) == "") {
				$ls_errmsg .= "Program page name cannot be blank<br/>";
			}
			break;
	}
	// check for duplicates
	if (check_for_duplicates("menus","menu_text",$lp_menu_text,"id_menu",$lp_id,"id_menu_group = $lp_parent")) {
		$ls_errmsg .= "Duplicate $ls_page_entity! Another $ls_page_entity with the same name exists<br/>";
	}
	
	// if entered data is ok find out IDs of the pages referred to
	if ($ls_errmsg == "") {
		switch ($lp_link_type) {
			case "N":
				$lp_external_link_page = "";
				$lp_id_page = "NULL";
				$lp_id_program_page = "NULL";
				break;
			case "E":
				$lp_id_page = "NULL";
				$lp_id_program_page = "NULL";
				break;
			case "I":
				$lp_external_link_page = "";
				$lp_id_program_page = "NULL";
				$lp_id_page = executeScaler("select id_page from pages where page_code='$lp_page_code'",$conn);
				if ($lp_id_page == "") {
					$ls_errmsg .= "Invalid page code!<br/>";
				}
				break;
			case "P":
				$lp_external_link_page = "";
				$lp_id_page = "NULL";
				$lp_id_program_page = executeScaler("select id_program_page from program_pages where page_name='$lp_page_name'",$conn);
				if ($lp_id_program_page == "") {
					$ls_errmsg .= "Invalid program page name!<br/>";
				}
				break;
		}
	}
	// if entered data is ok 
	if ($ls_errmsg == "") {
		
		if (!isset($lp_visible)) {
			$lp_visible = "N";
		}
		if (!isset($lp_page_menu)) {
			$lp_page_menu = "N";
		}
		// check what user wants to do if its a new entry
		if ($lp_id == "new") {
			// insert data into database
			$ls_query = sprintf("insert into menus(id_menu_group, menu_text, link_type, external_link_page, id_page, id_program_page, sequence, visible, page_menu)
									values (%d,'%s','%s','%s',$lp_id_page,$lp_id_program_page,%d,'%s','%s')", 
									$lp_parent,
									mysql_real_escape_string($lp_menu_text),
									$lp_link_type,
									mysql_real_escape_string($lp_external_link_page),
									$lp_sequence,
									$lp_visible,
									$lp_page_menu);
			mysql_query($ls_query, $conn);
			// get the ID of the new page to be used later in query
			$lp_id = mysql_insert_id($conn);
		} else {
			// update data to database
			$ls_query = sprintf("update menus set
								menu_text='%s',
								link_type='%s',
								external_link_page='%s',
								id_page=$lp_id_page,
								id_program_page=$lp_id_program_page,
								sequence=%d,
								visible='%s',
								page_menu='%s'
						where id_menu = %d", 
							mysql_real_escape_string($lp_menu_text),
							$lp_link_type,
							mysql_real_escape_string($lp_external_link_page),
							$lp_sequence,
							$lp_visible,
							$lp_page_menu,
							$lp_id);
			mysql_query($ls_query, $conn);
		}
		// if no error
		if (mysql_errno() == 0) {
			// if user requested the menu to be a page menu then all other menus need to be cancelled as page menus
			if ($lp_page_menu == 'Y') {
				// get institute ID to lock the update into
				$ll_id_inst = executeScaler("select id_inst from sections join menu_groups on sections.id_section = menu_groups.id_section where menu_groups.id_menu_group = $lp_parent",$conn);
				if ($lp_link_type == "I") {
					mysql_query("update menus join menu_groups on menus.id_menu_group = menu_groups.id_menu_group join sections on menu_groups.id_section = sections.id_section set page_menu = 'N' where menus.id_menu <> $lp_id and menus.id_page = $lp_id_page and sections.id_inst = $ll_id_inst",$conn);
				}
				if ($lp_link_type == "P") {
					mysql_query("update menus join menu_groups on menus.id_menu_group = menu_groups.id_menu_group join sections on menu_groups.id_section = sections.id_section set page_menu = 'N' where menus.id_menu <> $lp_id and menus.id_program_page = $lp_id_program_page and sections.id_inst = $ll_id_inst",$conn);
				}
				// if no error
				if (mysql_errno() == 0) {
					// go back to where came from
					header("Location: $ls_page_parent" );
				} else {
					// display error
					$ls_errmsg = mysql_errno() . ": " . mysql_error();
				}
			}
		} else {
			// display error
			$ls_errmsg = mysql_errno() . ": " . mysql_error();
		}
	}
} else {
	if ($lp_id != "new") {
		// Query the database for the list of series
		$result = mysql_query("select * from menus where id_menu = $lp_id", $conn);
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
		$lp_page_code = "";
		$lp_page_name = "";
		if ($lp_link_type == "I") {
			$lp_page_code = executeScaler("select page_code from pages where id_page = $lp_id_page",$conn);
		}
		if ($lp_link_type == "P") {
			$lp_page_name = executeScaler("select page_name from program_pages where id_program_page = $lp_id_program_page",$conn);
		}		
	} else {
		// set default values for ddlb, check box, radio etc.
		$lp_link_type = "N";
		$lp_visible = "Y";
		$lp_page_menu = "Y";
	}
}
require "ap_header.php";
require "components/form/div_page_head.php";
?>
<script for="window" event="onload" language="javascript">
  show_link_type_controls(getRadioValue(form_page.link_type));
</script>
<script language="javascript" type="text/javascript" src="../inc/qsslib.js"></script>
<script language="javascript">
function QssFormValidator(theForm) {
	if (!validateText(theForm.menu_text, "Menu text", 50, 1, 0)) return (false);
	if (!validateText(theForm.sequence, "Sequence", 50, 0, 1)) return (false);
	if (!validateRadio(theForm.link_type, "Link type")) return (false);
	switch (getRadioValue(theForm.link_type)) {
		case "N":
			break;
		case "E":
			if (!validateText(theForm.external_link_page, "External link page", 250, 1, 0)) return (false);
			break;
		case "I":
			if (!validateText(theForm.page_code, "Content page code", 50, 1, 0)) return (false);
			break;
		case "P":
			if (!validateText(theForm.page_name, "Program page name", 250, 1, 0)) return (false);
			break;
	}
	return (true);
}

function show_link_type_controls(as_val) {
  switch (as_val) {
	case "N":
		HideDiv("link_type_E_label");
		HideDiv("link_type_E_control");
		HideDiv("link_type_I_label");
		HideDiv("link_type_I_control");
		HideDiv("link_type_P_label");
		HideDiv("link_type_P_control");
		break;
	case "E":
		HideDiv("link_type_I_label");
		HideDiv("link_type_I_control");
		HideDiv("link_type_P_label");
		HideDiv("link_type_P_control");
		ShowDiv("link_type_E_label");
		ShowDiv("link_type_E_control");
		break;
	case "I":
		HideDiv("link_type_E_label");
		HideDiv("link_type_E_control");
		HideDiv("link_type_P_label");
		HideDiv("link_type_P_control");
		ShowDiv("link_type_I_label");
		ShowDiv("link_type_I_control");
		break;
	case "P":
		HideDiv("link_type_E_label");
		HideDiv("link_type_E_control");
		HideDiv("link_type_I_label");
		HideDiv("link_type_I_control");
		ShowDiv("link_type_P_label");
		ShowDiv("link_type_P_control");
		break;
	}
}

function link_type_onclick(as_val) {
  show_link_type_controls(as_val);
}
</script>
<?php
require "components/form/form_header.php";

extract(mysql_fetch_assoc(mysql_query("select menu_group_name as parent_name, section_name, inst_name from menu_groups join sections on menu_groups.id_section = sections.id_section join institutes on sections.id_inst = institutes.id_inst where menu_groups.id_menu_group = $lp_parent",$conn)),EXTR_PREFIX_ALL,"ls");
?>
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter"> 
															<tbody> 
																<tr id="tr_1"> 
																	<td style="width:160px;">Institute:</td> 
																	<td><?php echo $ls_inst_name;?></td>
																</tr> 
																<tr> 
																	<td style="width:160px;">Section:</td> 
																	<td><?php echo $ls_section_name;?></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Menu Group:</td> 
																	<td><?php echo $ls_parent_name;?></td>  
																</tr> 
																<tr> 
																	<td style="width:160px;">Menu text:</td> 
																	<td><input name="menu_text" type="text" id="menu_text" size="50" maxlength="50" value="<?php if( isset($lp_menu_text)) {echo $lp_menu_text;}?>"/></td>  
																</tr>
																<tr> 
																	<td style="width:160px;">Sequence:</td> 
																	<td><input name="sequence" type="text" id="sequence" size="50" maxlength="50" value="<?php if( isset($lp_sequence)) {echo $lp_sequence;}?>"/></td>  
																</tr>
																<tr> 
																	<td style="width:160px;">Link type:</td> 
																	<td>
																		<input type="radio" name="link_type" value="N" onclick="link_type_onclick('N')" <?php if( isset($lp_link_type) and $lp_link_type == "N") {echo " checked ";}?>>None
																		<input type="radio" name="link_type" value="E" onclick="link_type_onclick('E')" <?php if( isset($lp_link_type) and $lp_link_type == "E") {echo " checked ";}?>>External
																		<input type="radio" name="link_type" value="I" onclick="link_type_onclick('I')" <?php if( isset($lp_link_type) and $lp_link_type == "I") {echo " checked ";}?>>Internal
																		<input type="radio" name="link_type" value="P" onclick="link_type_onclick('P')" <?php if( isset($lp_link_type) and $lp_link_type == "P") {echo " checked ";}?>>Programmed
																	</td>
																</tr>
																<tr>
																	<td style="width:160px;">
																		<div id="link_type_E_label">External Link:</div>
																		<div id="link_type_I_label">Content Page:</div>
																		<div id="link_type_P_label">Programmed Page:</div>
																	</td> 
																	<td>
																		<div id="link_type_E_control"><input name="external_link_page" type="text" id="external_link_page" size="50" maxlength="250" value="<?php if( isset($lp_external_link_page)) {echo $lp_external_link_page;}?>"/></div>
																		<div id="link_type_I_control"><input name="page_code" type="text" id="page_code" size="50" maxlength="50" value="<?php if( isset($lp_page_code)) {echo $lp_page_code;}?>"/>&nbsp;<a target="_blank" href="pages_edit.php?id=new">Add new content page</a></div>
																		<div id="link_type_P_control"><input name="page_name" type="text" id="page_name" size="50" maxlength="250" value="<?php if( isset($lp_page_name)) {echo $lp_page_name;}?>"/>&nbsp;<a target="_blank" href="program_pages_edit.php?id=new">Add new program page</a></div>
																	</td>
																</tr>
																<tr> 
																	<td style="width:160px;">Visible:</td> 
																	<td><input type="checkbox" name="visible" value="Y" <?php if (isset($lp_visible) and $lp_visible=="Y") { echo " checked ";}?> ></td>  
																</tr>
																<tr> 
																	<td style="width:160px;">Page Menu:</td> 
																	<td><input type="checkbox" name="page_menu" value="Y" <?php if (isset($lp_page_menu) and $lp_page_menu=="Y") { echo " checked ";}?> >Set this menu as the menu for the selected page. A page can be linked from multiple menus but the page will then be shown with this menu only.</td>
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