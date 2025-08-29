<?php
// Include database setup files
require_once "config/config.php";
require_once "config/database.php";

// Redirect to login page after setup
header("location: login.php");
exit;
?>