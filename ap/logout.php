<?php
include "../inc/header.inc.php";
include "forcelogin.php";
include "../inc/footer.inc.php";
//first we will unset any variable we were using
session_unset();
//finally we will destroy all the data
session_destroy();
header("Location: index.php"); 
?>
