<?php
session_start();
//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
//link to functions script
require_once('functions.php');
// session data 
session_start();
$StaffID = $_SESSION["staffID"];
//get task ID
$taskID = isset($_REQUEST['taskID']) ? $_REQUEST['taskID'] : null;

try{
  $dbConn = getConnection();
  $currentTime = time();
  $editTask = "
    UPDATE nightingale_alert 
    SET timeAccepted = '$currentTime', staffID = '$StaffID'
    WHERE taskID = '$taskID';
  ";
  $newTaskQuery = $dbConn->exec($editTask);
  // WARNING CHANGE THIS SQL INJECTION RISK!!!

  header('Location: ../staffDashboard.php');
  exit();
}//end try
catch (Exception $e){
  header('Location: ../staffDashboard.php');
  exit();
}//end catch
?>
