<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page properties
$ls_page_entity = "Coin";
$ls_page_title = "$ls_page_entity Entry";
$ls_page_parent = "item_listing.php";
if (isset($lp_back2listpage)) {
	$ls_page_parent .= "?page=$lp_back2listpage";
}

// if the cancel button is pressed
if (isset( $lp_cancel ) == true ) {
	// go back to where came from
	header("Location: $ls_page_parent") ;
}

// if the delete button is pressed
if (isset( $lp_delete ) == true ) {
	// delete data from database
	$result_delete = $conn->prepare("delete from items where id_item= :id_item");
	$result_delete->execute(array(":id_item"=>$lp_id));
	
	// if no error
	if ($result_delete->errorCode() == 0) {
		// go back to where came from
		header("Location: $ls_page_parent");
		return;
	} else {
		// display error
		$la_errors = $result_delete->errorInfo();
		$ls_errmsg .= $la_errors[0] . ": " . $la_errors[2];
	}
}

// if the save button is pressed
if (isset( $lp_save ) == true ) {
	// validate entered data
	if (trim($lp_status) == "S" && trim($lp_suspend_reason) == "") {
		$ls_errmsg .= "Suspend reason cannot be blank<br/>";
	}
	// check for duplicates
	if (trim($lp_status) == "S") {
            $lp_suspended_time = "NOW()";
	}
        else {
            $lp_suspended_time = 'NULL';
            $lp_suspend_reason = 'NULL';
        }
	// if entered data is ok 
	if ($ls_errmsg == "") {
			// update data to database
			$ls_query = 
					"
						update 
							items 
						set 
							status = :status,
							suspend_reason = :suspend_reason,
							suspend_at = $lp_suspended_time
						where 
							id_item = :id_item
					";

			$result_update = $conn->prepare($ls_query);
			$result_update->execute(array(
					":status"=>$lp_status,
					":suspend_reason"=>$lp_suspend_reason,
					":id_item"=>$lp_id
					));
			
			// if no error
			if ($result_update->errorCode() == "0000") {
				// do not go back
				// go back to where came from
				header("Location: $ls_page_parent" );
			} else {
				// display error
				$la_errors = $result_update->errorInfo();
				$ls_errmsg = $la_errors[0] . ": " . $la_errors[2];
			}
	}
} else {
	if ($lp_id != "new") {
		// Query the database for the list of series
		$result = $conn->prepare("select * from items where id_item = :id_item");
		$result->execute(array(":id_item"=>$lp_id));
		
		// get number of rows returned
		$no_of_rows = $result->rowCount();
		// there has to be only one row returned. if not
		if ($no_of_rows != 1 ) {
			// go back to where came from
			header("Location: $ls_page_parent"); 
		}
		// fetch row to edit
		$row = $result->fetch(PDO::FETCH_ASSOC);
		extract($row,EXTR_PREFIX_ALL,"lp");	
	} else {
		// set default values for ddlb, check box, radio etc.
            $lp_status = 'A';
	}
}
require "ap_header.php";
require "components/form/div_page_head.php";
?>
<script language="javascript" type="text/javascript" src="../inc/qsslib.js"></script>
<script language="javascript">
function QssFormValidator(theForm) {
	if (!validateText(theForm.suspend_reason, "Suspend reason", 500, 1, 0)) return (false);
	return (true);
}
$(document).ready(function(){
    if($(':radio:checked').val() == 'S'){
        $('#showHideReason').show();
    }  
    $('#delete').hide();
    $(':radio').click(function(){
        if($(this).val() == 'S'){
            $('#showHideReason').show();
        }
        else{
            $('#showHideReason').hide();
        }
    });
});
</script>
<?php
require "components/form/form_header.php";
?>
												<div class="panel">
													<div class="content">
														<table cellspacing="1" class="tablesorter">
															<tbody>
                                                                                                                                <td style="width:160px;">Status:</td>
                                                                                                                                <td>
                                                                                                                                    <input type="radio" name="status" value="A" <?php echo $lp_status == "A" ? " checked " : "";?>>Active&nbsp;&nbsp;
                                                                                                                                    <input type="radio" name="status" value="S" <?php echo $lp_status == "S" ? " checked " : "";?>>Suspended
                                                                                                                                </td>
                                                                                                                                <tr id="showHideReason" style="display: none;"> 
                                                                                                                                    <td style="width:160px;">Suspend Reason:</td> 
                                                                                                                                    <td><textarea name="suspend_reason" id="suspend_reason" cols="60" rows="5"><?php if( isset($lp_suspend_reason)) {echo $lp_suspend_reason;}?></textarea></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
<?php
require "components/form/standard_button_set.php";
require "components/form/form_footer.php";

require "ap_footer.php";
include "../inc/footer.inc.php";
?>