												<div class="breadcremb">
													<div class="bg">
																	<input name="save" type="submit" id="save" value="Save" class="button" />
																	<input name="cancel" type="button" id="cancel" value="Cancel" onclick="document.location.href='<?php echo $ls_page_parent; ?>'" class="button"/>
<?php
// in new mode, dont need to show the delete button
if (isset($lp_id) and $lp_id != "new") {
?>
																	<input name="delete" type="submit" id="delete" value="Delete" onclick="return confirm('Delete this record?');"  class="button"/>
<?php
}
?>
<?php
if (isset($lp_id)) {
?>
																	<input name="id" type="hidden" id="id" value="<?php echo $lp_id; ?>">
<?php
}
?>
<?php
// if a child edit page then a parent parameter will be passed to identify the parent which needs to be maintained across page sessions
if (isset($lp_parent)) {
?>
																	<input name="parent" type="hidden" id="parent" value="<?php echo $lp_parent ?>">
<?php
}
?>
													</div>
												</div>
