<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page title
$ls_page_entity = "Menu Category";
$ls_page_title = 'List of Menu Categories';
$ls_edit_program_file = 'apmenucat_edit.php';
$ls_edit_col_name_id = 'id_apmenucat';
$ls_edit_col_name_name = 'name';

require "components/lists/handle_btn_list_view_rows_per_page.php";
require "ap_header.php";
require "components/lists/div_page_head.php";
?>
<form name="form_page_navigation" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<?php
// Query the database for the list of series
$result = mysql_query("select * from ap_menucat order by sequence, name asc", $conn);
$no_of_rows = mysql_num_rows($result);
require "components/lists/grid_top_navigation.php";
require "components/lists/grid_header.php";
?>
														<tr>
															<td>
																<table cellspacing="1" class="tablesorter"> 
																	<thead> 
																		<tr> 
																            <th>Sequence</th>
																            <th>Menu Category</th>
																            <th>Admin Only</th>
																			<th style="width:30%">Action</th>
																		</tr> 
																	</thead> 
																	<tbody>
<?php
$ll_start_row_no = ($lp_page - 1) * $_SESSION['list_view_rows_per_page'];
$ll_end_row_no = ($lp_page * $_SESSION['list_view_rows_per_page']);
if ($no_of_rows < $ll_end_row_no) $ll_end_row_no = $no_of_rows;
$ll_end_row_no--;
if ($no_of_rows > 0 ) {
	if (mysql_data_seek($result,$ll_start_row_no)) { 
		for ($ll_current_row = $ll_start_row_no;$ll_current_row<=$ll_end_row_no;$ll_current_row++) {
			$row = mysql_fetch_assoc($result);
?>
<tr>
																            <td align="center"><?php echo $row['sequence']?></td>
																            <td align="center"><a href="<?php echo $ls_edit_program_file; ?>?id=<?php echo $row[$ls_edit_col_name_id] . $ls_back2listpage; ?>"><?php echo $row[$ls_edit_col_name_name]?></a></td> 
																            <td align="center"><?php if($row['admin_only'] == 'Y') { echo "Yes";} else { echo "No";}?></td>
																            <td align="center"><a href="<?php echo $ls_edit_program_file; ?>?id=<?php echo $row[$ls_edit_col_name_id] . $ls_back2listpage; ?>"><img src="images/edit.png" width="16" height="16" alt="edit" title="edit" style="vertical-align:top" /> Edit</a>&nbsp;</td>
																		</tr>
<?php
		}
	}
} else {
		// show blank row for no data
?>
																		<tr>
																			<td colspan="4">&nbsp;</td>
																		</tr>
<?php
}
?>
																	<tfoot>
																		<tr>
																			<th colspan="4" style="text-align:left;">Count: <?php echo $ll_end_row_no - $ll_start_row_no + 1; ?></th>
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