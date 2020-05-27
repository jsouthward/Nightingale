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
    VALUES (:currentTime,:userLocation,:userID,:requestID);
  ";
  $newTaskQuery = $dbConn->prepare($newTask);
  $newTaskQuery->execute(array(
    'currentTime' => $currentTime,
    'userLocation' => $userLocation,
    'userID' => $userID,
    'requestID' => $requestID,
  ));
  
  
  header('Location: ../userRequest.php');
  exit();
}//end try
catch (Exception $e){
  header('Location: ../dashboard.php');
  exit();
}//end catch
?>