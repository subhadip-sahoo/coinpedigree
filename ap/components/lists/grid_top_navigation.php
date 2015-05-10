<?php
	// if page's querystring is not defined (as in pages_list.php) then define it
	if (!isset($ls_page_querystring)) {
		$ls_page_querystring = "";
	}
	// if no page parameter passed then show first page
	if (!isset($lp_page)) {
		$lp_page = 1;
	}
	// generate back2listpage string using in passing the list page to the edit page so that the user is returned to the list page he was viewing
	$ls_back2listpage = "&back2listpage=" . $lp_page;
	// calculate total no of pages
	$ll_tot_pages = ceil($no_of_rows / $_SESSION['list_view_rows_per_page']);
	// check for invalid page no passed and go to last page in case an invalid page no is passed
	if ($_SESSION['list_view_rows_per_page'] * ($lp_page - 1) > $no_of_rows) $lp_page = $ll_tot_pages;
	
	// page navigation images
	$ls_img_arrow_left_on = "images/pager_arrow_left_on.gif";
	$ls_img_arrow_left_off = "images/pager_arrow_left_off.gif";
	$ls_img_arrow_right_on = "images/pager_arrow_right_on.gif";
	$ls_img_arrow_right_off = "images/pager_arrow_right_off.gif";
	// decide which navigation to show
	// if first page
	if ($lp_page == 1) {
		// disable left arrow
		$ls_img_arrow_left = "<img src='$ls_img_arrow_left_off' alt='' />";
	} else {
		// show left arrow with link to previous page
		$ls_img_arrow_left = "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($lp_page - 1) ."$ls_page_querystring'><img src='$ls_img_arrow_left_on' alt='' /></a>";
	}
	// if last page
	if ($lp_page == $ll_tot_pages or $no_of_rows == 0) {
		// disable right arrow
		$ls_img_arrow_right = "<img src='images/pager_arrow_right_off.gif' alt='' />";
	} else {
		// show right arrow with link to next page
		$ls_img_arrow_right = "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($lp_page + 1) ."$ls_page_querystring'><img src='images/pager_arrow_right_on.gif' alt='' /></a>";
	}
?>
										<div class="result">
											<div class="result-row">
												&nbsp;Page <?php echo $ls_img_arrow_left;?> <input type="text" name="page" value="<?php echo $lp_page;?>" class="field" style="width:27px;" align="top" /> <?php echo $ls_img_arrow_right;?> of <?php echo $ll_tot_pages;?> pages <span class="separator">|</span> View 
												<select name="limit" class="field" style="width:50px; padding:1px;"> 
													<option value="10" <?php if ($_SESSION['list_view_rows_per_page'] == 10) echo " selected ";?>>10</option>
													<option value="20" <?php if ($_SESSION['list_view_rows_per_page'] == 20) echo " selected ";?>>20</option>
													<option value="50" <?php if ($_SESSION['list_view_rows_per_page'] == 50) echo " selected ";?>>50</option>
													<option value="100" <?php if ($_SESSION['list_view_rows_per_page'] == 100) echo " selected ";?>>100</option>
												</select>
												rows per page &nbsp;<button name="btn_list_view_rows_per_page" type="submit" class="scalable go"><span>Go</span></button>
											</div>
										</div>
										