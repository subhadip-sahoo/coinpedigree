<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page title
$ls_page_entity = "Coins";
$ls_page_title = "List of $ls_page_entity";
$ls_edit_program_file = 'item_edit.php';
$ls_edit_col_name_id = 'id_item';
$ls_edit_col_name_name = 'pcgs_ver_id';
$lb_master_edit_add_option = false;

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
$result = $conn->prepare("select * from items where 1=1 $ls_where order by id_item");
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
                                                                                                                                                    <th>Cert Verification #</th>
                                                                                                                                                    <th>PCGS Coin #</th>
                                                                                                                                                    <th>Date, mintmark</th>
                                                                                                                                                    <th>Denomination</th>
                                                                                                                                                    <th>Country</th>
                                                                                                                                                    <th>Grade</th>
                                                                                                                                                    <th>Mintage</th>
                                                                                                                                                    <th>PCGS Price Guide Value</th>
                                                                                                                                                    <th>Holder Type</th>
                                                                                                                                                    <th>Population</th>
                                                                                                                                                    <th>Source</th>
                                                                                                                                                    <th>Status</th>
                                                                                                                                                    <th>Suspend At</th>
                                                                                                                                                    <th>Suspend Reason</th>
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
                                                                                                                                                    <td align="center"><a href="<?php echo $ls_edit_program_file; ?>?id=<?php echo $row[$ls_edit_col_name_id] . $ls_back2listpage; ?>"><?php echo trim($row[$ls_edit_col_name_name]); ?></a></td> 
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_coin_no']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_date_mintmark']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_denomination']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_country']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_grade']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_mintage']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_price_guide_value']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_holder_type']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['pcgs_population']); ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['source']); ?></td>
                                                                                                                                                    <td align="center"><?php echo (trim($row['status']) == 'A')?'Active':'Suspended'; ?></td>
                                                                                                                                                    <td align="center"><?php echo (trim($row['suspend_at']) == '0000-00-00 00:00:00' || trim($row['suspend_at']) == 'NULL')?'':$row['suspend_at']; ?></td>
                                                                                                                                                    <td align="center"><?php echo trim($row['suspend_reason']); ?></td>
                                                                                                                                                    <td align="center"><a href="<?php echo $ls_edit_program_file; ?>?id=<?php echo $row[$ls_edit_col_name_id] . $ls_back2listpage; ?>"><img src="images/edit.png" alt="edit" title="edit" style="vertical-align:top" /> Edit</a></td>
																		</tr>
<?php
	}
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
																			<th colspan="15" style="text-align:left;">Count: <?php echo $ll_end_row_no - $ll_start_row_no + 1; ?></th>
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