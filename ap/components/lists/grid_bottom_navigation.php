																<table width="100%" border="0" cellpadding="0" cellspacing="0" class="pagination pagination-left">
																	<tr>
																		<td class="results">
																			<span>
<?php
if ($no_of_rows>0) {
?>
																				Showing results <?php echo $ll_start_row_no+1;?>-<?php echo $ll_end_row_no+1;?> of <?php echo $no_of_rows;?>
<?php
} else {
?>
																				No data found matching your request
<?php
}
?>
																			</span>
																		</td>
																		<td align="right">
																			<table border="0" cellpadding="0" cellspacing="0">
																				<tr class="pager">
<?php
	// calculate first page no
	$ll_start_page = $lp_page - 5;
	if ($ll_start_page < 1) {
		$ll_start_page = 1;
	}
	$ll_end_page = $ll_start_page + 9;
	if ($ll_end_page > $ll_tot_pages) {
		$ll_end_page = $ll_tot_pages;
	}
	if ($ll_end_page - $ll_start_page < 9) {
		$ll_start_page = $ll_end_page - 9;
		if ($ll_start_page < 1) {
			$ll_start_page = 1;
		}
	}

	if ($lp_page == 1) {
		$ls_prev_page_class = "class='disabled'";
		$ls_prev_page_a_tag_start = "";
		$ls_prev_page_a_tag_end = "";
	} else {
		$ls_prev_page_class = "";
		$ls_prev_page_a_tag_start = "<a href='". $_SERVER["PHP_SELF"] . "?page=" . ($lp_page - 1) ."$ls_page_querystring'>";
		$ls_prev_page_a_tag_end = "</a>";
	}
	if ($lp_page == $ll_end_page or $no_of_rows == 0) {
		$ls_next_page_class = "class='disabled'";
		$ls_next_page_a_tag_start = "";
		$ls_next_page_a_tag_end = "";
	} else {
		$ls_next_page_class = "";
		$ls_next_page_a_tag_start = "<a href='". $_SERVER["PHP_SELF"] . "?page=" . ($lp_page + 1) ."$ls_page_querystring'>";
		$ls_next_page_a_tag_end = "</a>";
	}
?>
																					<td <?php echo $ls_prev_page_class;?>><?php echo $ls_prev_page_a_tag_start;?>&laquo; prev<?php $ls_prev_page_a_tag_end;?></td>
																					<td class="separator"></td>
<?php
	for ($ll_current_page = $ll_start_page; $ll_current_page <= $ll_end_page;$ll_current_page++) {
		if ($lp_page == $ll_current_page) {
			$ls_current_page_class = "class='current'";
			$ls_output = $ll_current_page;
		} else {
			$ls_current_page_class = "";
			$ls_output = "<a href='". $_SERVER["PHP_SELF"] ."?page=$ll_current_page$ls_page_querystring'>$ll_current_page</a>";
		}
?>
																					<td <?php echo $ls_current_page_class;?>><?php echo $ls_output;?></td>
																					<td class="separator"></td>
<?php
	}
?>
																					<td <?php echo $ls_next_page_class;?>><?php echo $ls_next_page_a_tag_start;?>next &raquo;<?php $ls_next_page_a_tag_end;?></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>