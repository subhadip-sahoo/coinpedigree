<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "require_admin_access.php";

// set page properties
$ls_page_entity = "Pages";
$ls_page_title = "$ls_page_entity Management";
$ls_page_parent = "home.php";

//$ls_homepage_content = get_setting(HOMEPAGE,$conn);
$ls_how_we_works_content = get_setting(HOW_WE_WORKS,$conn);
$ls_about_us_content = get_setting(ABOUT_US,$conn);
//$ls_terms_and_conditions_homeowners_content = get_setting(TERMS_AND_CONDITIONS_HOMEOWNERS,$conn);
//$ls_terms_and_conditions_contractors_content = get_setting(TERMS_AND_CONDITIONS_CONTRACTORS,$conn);

require "ap_header.php";
require "components/form/div_page_head.php";
?>
												<script src="../js/nicEdit.js" type="text/javascript"></script>
												<script src="../js/pagesnicedit.js" type="text/javascript"></script>
												
												<link rel="stylesheet" href="../themes/base/jquery.ui.all.css">
												<script src="../js/jquery.min.js"></script>
												<script src="../ui/jquery.ui.core.js"></script>
												<script src="../ui/jquery.ui.widget.js"></script>
												<script src="../ui/jquery.ui.tabs.js"></script>
												
												<script>
												$(function() {
													$( "#tabs" ).tabs();
												});
												</script>
												
												<div class="result">
													<div id="tabs">
													
														<ul>
															<!--<li><a href="#tabs-1">HOMEPAGE</a></li>-->
															<li><a href="#tabs-2">ABOUT US</a></li>
															<li><a href="#tabs-3">HOW IT WORKS</a></li>
<!--															<li><a href="#tabs-4">TERMS_AND_CONDITIONS_EMPLOYERS</a></li>
															<li><a href="#tabs-5">TERMS_AND_CONDITIONS_JOBSEEKERS</a></li>-->
														</ul>
														
														<!--<div id="tabs-1">
															<textarea cols="100" rows="5" id="homepage_content" >
																<?php //echo $ls_homepage_content; ?>
															</textarea><br>
															<input type="button" id="homepage_save" value="Save" class="button" />
															<input type="button" name="cancel" value="Cancel" onclick="document.location.href='<?php //echo $ls_page_parent; ?>'" class="button" />
														</div>-->
														
														<div id="tabs-2">
															<textarea cols="100" rows="5" id="about_us_content" >
																<?php echo $ls_about_us_content; ?>
															</textarea><br>
															<input type="button" id="about_us_save" value="Save" class="button" />
															<input type="button" name="cancel" value="Cancel" onclick="document.location.href='<?php echo $ls_page_parent; ?>'" class="button" />
														</div>
														
														<div id="tabs-3">
															<textarea cols="100" rows="5" id="how_we_works_content">
																<?php echo $ls_how_we_works_content; ?>
															</textarea><br>
															<input type="button" id="how_we_works_save" value="Save" class="button" />
															<input type="button" name="cancel" value="Cancel" onclick="document.location.href='<?php echo $ls_page_parent; ?>'" class="button" />
														</div>
														
<!--														<div id="tabs-4">
															<textarea cols="100" rows="5" id="terms_and_conditions_homeowners_content">
																<?php //echo $ls_terms_and_conditions_homeowners_content; ?>
															</textarea><br>
															<input type="button" id="terms_and_conditions_homeowners_content_save" value="Save" class="button" />
															<input type="button" name="cancel" value="Cancel" onclick="document.location.href='<?php //echo $ls_page_parent; ?>'" class="button" />
														</div>
														
														<div id="tabs-5">
															<textarea cols="100" rows="5" id="terms_and_conditions_contractors_content">
																<?php //echo $ls_terms_and_conditions_contractors_content; ?>
															</textarea><br>
															<input type="button" id="terms_and_conditions_contractors_content_save" value="Save" class="button" />
															<input type="button" name="cancel" value="Cancel" onclick="document.location.href='<?php //echo $ls_page_parent; ?>'" class="button" />
														</div>-->
														
													</div>
												</div>
<?php
require "ap_footer.php";
include "../inc/footer.inc.php";
?>