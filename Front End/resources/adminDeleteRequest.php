<?php
session_start();
//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
//link to functions script
require_once('functions.php');
$requestID = isset($_GET['requestID']) ? $_GET['requestID'] : null;

try{
  $dbConn = getConnection();

  $sql = "
  DELETE FROM nightingale_request
    WHERE requestID = '$requestID'
  ";
  $deleteQuery = $dbConn->query($sql);
  if ($deleteQuery === false) {
    header('Location: ../adminDashboard.php');
    exit();
  } else {
    //if deleted
    header('Location: ../adminDashboard.php');
    exit();
  }
}//end try
catch (Exception $e){
  header('Location: ../adminDashboard.php');
  exit();
}//end catch
?>