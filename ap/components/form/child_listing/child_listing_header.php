										<div class="data-table-bot">
											<p><?php echo $ls_child_list_title;?>
											<?php
											if (!isset($lb_child_edit_add_option) or $lb_child_edit_add_option==true) {
											?>
											<button type="button" value="" class="add" onclick="location.href='<?php echo $ls_child_edit_program_file;?>?id=new&parent=<?php echo $lp_id;?><?php echo isset($ls_addtl_parms)?"&".$ls_addtl_parms:"";?>'"><span>Add New <?php echo $ls_child_entity;?></span></button>
											<?php
											}
											?>
											</p>
											<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter" id="sort_list"> 
