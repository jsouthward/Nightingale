<?php
//link to functions script
require_once('functions.php');

// session data 
session_start();
$userID = $_SESSION["userID"];
$userLocation = $_SESSION["locationID"];

//get request type
$requestID = isset($_REQUEST['request']) ? $_REQUEST['request'] : null;

try{
  
  $dbConn = getConnection();
  $currentTime = time();
  $newTask = "
    INSERT INTO nightingale_alert (timeCreated, locationID, userID, requestID) 
    VALUES ('$currentTime','$userLocation','$userID','$requestID');
  ";
  $newTaskQuery = $dbConn->exec($newTask);
  
  
  header('Location: ../userRequest.php');
  exit();
}//end try
catch (Exception $e){
  header('Location: ../dashboard.php');
  exit();
}//end catch
?>