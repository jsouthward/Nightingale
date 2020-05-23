<?php
//link to functions script
require_once('functions.php');

// session data 
session_start();
$staffID = $_SESSION["staffID"];
$staffLocation = $_SESSION["locationID"];

//alert all members of staff
panic($staffLocation,$staffID);

?>