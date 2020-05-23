<?php
session_start();
//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
//link to functions script
require_once('functions.php');
$taskID = isset($_REQUEST['taskID']) ? $_REQUEST['taskID'] : null;

try{
  $dbConn = getConnection();

  $deleteTask = "
  DELETE FROM nightingale_alert
    WHERE taskID = '$taskID'
  ";
  $deleteQuery = $dbConn->query($deleteTask);
  if ($deleteQuery === false) {
    header('Location: ../staffRequest.php');
    exit();
  } else {
    //if deleted
    header('Location: ../staffRequest.php');
    exit();
  }
}//end try
catch (Exception $e){
  header('Location: ../staffRequest.php');
  exit();
}//end catch
?>