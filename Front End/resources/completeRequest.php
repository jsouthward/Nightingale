<?php
session_start();
//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
//link to functions script
require_once('functions.php');
//get task ID
$taskID = isset($_REQUEST['taskID']) ? $_REQUEST['taskID'] : null;

try{
  $dbConn = getConnection();
  $currentTime = time();
  $editTask = "
    UPDATE nightingale_alert 
    SET timeCompleted = '$currentTime'
    WHERE taskID = '$taskID';
  ";
  $newTaskQuery = $dbConn->exec($editTask);

  header('Location: ../staffDashboard.php');
  exit();
}//end try
catch (Exception $e){
  header('Location: ../staffDashboard.php');
  exit();
}//end catch
?>
