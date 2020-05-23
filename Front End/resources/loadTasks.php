<?php 
  // get staff id from sessions 
  require_once('functions.php');
  session_start();
  $staffID = $_SESSION["staffID"];
  getAcceptedTasks($staffID);
  getTasks();
  // panic button overlay
  getPanicTasks();
?>
