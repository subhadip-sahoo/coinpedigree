<?php
include "../inc/header.inc.php";
include "forcelogin.php";
// set page title
$ls_page_entity = "Login History";
$ls_page_title = 'User login history';
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
$ls_execute_parameters = "";
if (isset($lp_crit_id_user)) {
	if ($lp_crit_id_user <> "") {
		$ls_where .= " and users.id_user = :crit_id_user";
		$ls_execute_parameters = array(":crit_id_user"=>$lp_crit_id_user);
		$ls_page_querystring .= "&crit_id_user=$lp_crit_id_user";
	}
}
if (!isset($lp_orderby)) {
	// set default order by
	$lp_orderby = "login_time" ;
}

if (!isset($lp_orderdir)) {
	// set default order direction
	$lp_orderdir = "desc";
}

// Query the database for the list of series
$ls_query_user_list = "
		select
			id_login_history, 
			login_time, 
			login_ip, 
			user_name 
		from 
			users 
			join login_history on users.id_user = login_history.id_user
		where 
			1=1 $ls_where 
		order by 
			$lp_orderby $lp_orderdir
						";

$result = $conn->prepare($ls_query_user_list);
$result->execute($ls_execute_parameters);

$no_of_rows = $result->rowCount();
require "components/lists/grid_top_navigation.php";
require "components/lists/grid_header.php";
?>
														<tr>
															<td>
																<table width="100%" border="0" cellpadding="0" cellspacing="0">
																	<tr class="table-result">
																		<td>
																			<table width="100%" border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td align="right">User:</td>
																					<td>
																						<select name="crit_id_user">
<?php
$ls_where = "";
if ($_SESSION['user_type'] == "A") {
?>
																							<option value="">-- all --</option>
<?php
	fillddlb($conn,"select id_user, user_name from users where 1=1 $ls_where order by user_name","user_name","id_user",isset($lp_crit_id_user)?$lp_crit_id_user:"");
} else {
	$ls_where = "and users.id_user = " . $_SESSION['ap_login_id'];
	fillddlb($conn,"select id_user, user_name from users where 1=1 $ls_where order by user_name","user_name","id_user",isset($lp_crit_id_user)?$lp_crit_id_user:"");
}
?>
																						</select>
																					</td>
																					<td align="right">Sort by:</td>
																					<td>
																						<select name="orderby">
																							<option value="login_time" <?php echo $lp_orderby == "login_time" ? "selected" : "";?>>Login Time</option>
																							<option value="user_name" <?php echo $lp_orderby == "user_name" ? "selected" : "";?>>User</option>
																						</select>
																					</td>
																					<td>
																						<select name="orderdir">
																							<option value="asc" <?php echo $lp_orderdir == "asc" ? "selected" : "";?>>Ascending</option>
																							<option value="desc" <?php echo $lp_orderdir == "desc" ? "selected" : "";?>>Descending</option>
																						</select>
																					</td>
																				</tr>
																			</table>
																		</td>
																		<td>
																			<input type="submit" name="btn_submit_list_criteria" value="Search" class="button" />
																			<input type="reset" name="btn_reset_list_criteria" value="Reset Filter" class="button" />
																		</td>
																	</tr>
																</table>
															</td>
														</tr>


														<tr>
															<td>
																<table cellspacing="1" class="tablesorter"> 
																	<thead> 
																		<tr> 
																            <th>User</th>
																            <th>Login Time</th>
																            <th>Location</th>
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
																            <td><?php echo $row['user_name'];?></td> 
																            <td><?php echo date(DISPLAY_FORMAT_DATETIME_SHORT,strtotime($row['login_time']));?></td> 
																            <td><?php echo $row['login_ip'];?></td> 
																		</tr>
<?php
		}
		$result = null;
} else {
		// show blank row for no data
?>
																		<tr>
																			<td colspan="3">&nbsp;</td>
																		</tr>
<?php
}
?>
																	<tfoot>
																		<tr>
																			<th colspan="3" style="text-align:left;">Count: <?php echo $ll_end_row_no - $ll_start_row_no + 1; ?></th>
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