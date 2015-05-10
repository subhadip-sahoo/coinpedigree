												<div class="breadcremb">
													<div class="bg">
<?php
// in new mode, dont need to show the delete button
if (isset($lp_id) and $lp_id != "new") {
?>
																	<input name="suspend" type="button" id="suspend" value="Suspend" onclick="return confirm('Suspend this user?');"  class="button"/>
																	<input name="activate" type="button" id="activate" value="Activate" onclick="return confirm('Activate this user?');"  class="button"/>
<?php
}
?>
<?php
if (isset($lp_id)) {
?>
																	<input name="id" type="hidden" id="id" value="<?php echo $lp_id; ?>">
																	<input name="status_hidden" type="hidden" id="status_hidden" value="<?php echo $lp_status; ?>">
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