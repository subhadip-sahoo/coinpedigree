<?php
include "../inc/header.inc.php";

if(!isset($lp_type)){
	header("location:/");
	return;
}
switch($lp_type)
{
	case "homepage" :
		save_homepage($lp_key);
		break;
	
	case "about_us" :
		save_about_us($lp_key);
		break;
	
	case "how_we_works" :
		save_how_we_works($lp_key);
		break;
		
	case "terms_and_conditions_homeowners_content" :
		save_terms_and_conditions_homeowners_content($lp_key);
		break;
		
	case "terms_and_conditions_contractors_content" :
		save_terms_and_conditions_contractors_content($lp_key);
		break;
}

function save_homepage($key){
	global $conn;
	set_setting(HOMEPAGE,$key,$conn);
}

function save_about_us($key){
	global $conn;
	set_setting(ABOUT_US,$key,$conn);
}

function save_how_we_works($key){
	global $conn;
	set_setting(HOW_WE_WORKS,$key,$conn);
}

function save_terms_and_conditions_homeowners_content($key){
	global $conn;
	set_setting(TERMS_AND_CONDITIONS_HOMEOWNERS,$key,$conn);
}

function save_terms_and_conditions_contractors_content($key){
	global $conn;
	set_setting(TERMS_AND_CONDITIONS_CONTRACTORS,$key,$conn);
}
?>