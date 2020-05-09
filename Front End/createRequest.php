<?php
//link to functions script
require_once('functions.php');

// session data 
session_start();
$userID = $_SESSION["userID"];
$userLocation = $_SESSION["locationID"];
$currentTime = time();

//get request type
$requestID = isset($_REQUEST['request']) ? $_REQUEST['request'] : null;

try{
  //add to database
  // WARNING CHANGE THIS SQL INJECTION RISK!!!
  // https://stackoverflow.com/questions/4364686/how-do-i-sanitize-input-with-pdo
  $dbConn = getConnection();
  $newTask = "
    INSERT INTO nightingale_alert (timeCreated, locationID, userID, requestID) 
    VALUES ('$currentTime','$userLocation','$userID','$requestID');
  ";
  $newTaskQuery = $dbConn->exec($newTask);
  // WARNING CHANGE THIS SQL INJECTION RISK!!!
  
  // get taskID to send to user request.php
  // SELECT taskID WHERE user = $userName
  
  header('Location: userRequest.php');//add ?taskID={$rowObj->taskID}
  exit();
}//end try
catch (Exception $e){
  header('Location: dashboard.php');
  exit();
}//end catch
?>