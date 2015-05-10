										<form id="form_page" name="form_page" ENCTYPE="multipart/form-data" method="post" onSubmit="return (QssFormValidator(this));" action="<?php echo $_SERVER['PHP_SELF'];?>">
										<div class="result">
<?php
if ($ls_errmsg != "") {
	require "div_error_box.php";
}
if ($ls_warningmsg != "") {
	require "div_warning_box.php";
}
if ($ls_information_msg != "") {
	require "div_information_box.php";
}
?> 
											<div class="heading"><span><?php echo $ls_page_entity;?> Information</span></div>
											<div class="data-table">
