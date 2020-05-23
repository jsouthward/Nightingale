<?php 
session_start();
//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
//link to functions script
require_once('functions.php');
//delete completed tasks
deleteCompleted();
?>