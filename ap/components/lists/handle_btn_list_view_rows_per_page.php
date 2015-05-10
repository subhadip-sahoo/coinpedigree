<?php
// code to process clicks on button for changing number of rows to show in a list view
if (isset($_POST['btn_list_view_rows_per_page'])) {
	// save selected option to session so that it can be used next time on all pages
	if ($_SESSION['list_view_rows_per_page'] <> $_POST['limit']) {
		$_SESSION['list_view_rows_per_page'] = $_POST['limit'];
		// since the item to display per page has been changed, must restart at page 1
		$lp_page = 1;
	}
}

?>
