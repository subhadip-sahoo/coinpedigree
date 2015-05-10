										<div class="page-head">
											<div style="float:left"><?php echo $ls_page_title;?></div>
											<div style="float:right;">
<?php
$ls_url_edit_program_file = isset($ls_edit_program_file)? $ls_edit_program_file . "?id=new" : "";
if (isset($lp_page)) {
	$ls_url_edit_program_file .= "&back2listpage=" . $lp_page;
}
if (!isset($lb_master_edit_add_option) or $lb_master_edit_add_option == true) {
?>
												<button onclick="location.href='<?php echo $ls_url_edit_program_file; ?>'" type="button" class="scalable add"><span>Add New</span></button>
<?php
}
?>
												&nbsp;<button type="button" onclick="location.href='home.php'" class="scalable back"><span>Back</span></button>
											</div>
										</div>
