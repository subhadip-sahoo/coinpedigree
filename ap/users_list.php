<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page title
$ls_page_entity = "User";
$ls_page_title = 'List of Users';
$ls_edit_program_file = 'users_edit.php';
$ls_edit_col_name_id = 'id_user';
$ls_edit_col_name_name = 'user_name';

require "components/lists/handle_btn_list_view_rows_per_page.php";
require "ap_header.php";
require "components/lists/div_page_head.php";
?>
<form name="form_page_navigation" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<?php
// if criteria set, then generate where clause
$ls_where = "";
$ls_page_querystring = "";
// Query the database for the list of series
$ls_query_list = "
		select 
			*
		from 
			users
		where 
			1=1 $ls_where 				
		order by 
			user_name
";
$result = $conn->prepare($ls_query_list);
$result->execute();
$no_of_rows = $result->rowCount();

require "components/lists/grid_top_navigation.php";
require "components/lists/grid_header.php";
?>
														<tr>
															<td>
																<table cellspacing="1" class="tablesorter"> 
																	<thead> 
																		<tr> 
																            <th>User</th>
																            <th>Name</th>
																            <th>Email</th>
																            <th>Status</th>
																            <th>Type</th>
																			<th style="width:120px">Action</th>
																		</tr>
																	</thead> 
																	<tbody>
<?php
$ll_start_row_no = ($lp_page - 1) * $_SESSION['list_view_rows_per_page'];
$ll_end_row_no = ($lp_page * $_SESSION['list_view_rows_per_page']);
if ($no_of_rows < $ll_end_row_no) $ll_end_row_no = $no_of_rows;
$ll_end_row_no--;
if ($no_of_rows > 0 ) {
		$rowall = $result->fetchAll(); 
		for ($ll_current_row = $ll_start_row_no;$ll_current_row<=$ll_end_row_no;$ll_current_row++) {
			$row = $rowall[$ll_current_row];
?>
																		<tr>
																            <td align="center"><a href="<?php echo $ls_edit_program_file; ?>?id=<?php echo $row[$ls_edit_col_name_id] . $ls_back2listpage; ?>"><?php echo $row[$ls_edit_col_name_name]?></a></td> 
																            <td align="center"><?php echo $row['user_fullname'];?></td>
																            <td align="center"><?php echo $row['email'];?></td>
																            <td align="center"><?php echo $row['user_status'] == "A" ? "Active" : "Suspended";?></td>
																            <td align="center"><?php echo $row['user_type'] == "A" ? "Administrator" : "Operator";?></td>
																            <td align="center"><a href="<?php echo $ls_edit_program_file; ?>?id=<?php echo $row[$ls_edit_col_name_id] . $ls_back2listpage; ?>"><img src="images/edit.png" alt="edit" title="edit" style="vertical-align:top" /> Edit</a> | <a href="users_history_list.php?crit_id_user=<?php echo $row["id_user"]; ?>">Login History</a></td>
																		</tr>
<?php
		}
		$result = null;
} else {
		// show blank row for no data
?>
																		<tr>
																			<td colspan="6">&nbsp;</td>
																		</tr>
<?php
}
?>
																	<tfoot>
																		<tr>
																			<th colspan="6" style="text-align:left;">Count: <?php echo $ll_end_row_no - $ll_start_row_no + 1; ?></th>
																		</tr>
																	</tfoot> 
																</table>
															</td>
														</tr>
<?php
require "components/lists/grid_footer.php";
?>
</form>
<?php
require "ap_footer.php";
include "../inc/footer.inc.php";
?>