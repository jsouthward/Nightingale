<?php
//link to functions script
require_once('functions.php');

// session data 
session_start();
$userID = htmlspecialchars($_SESSION["userID"]);//escapes special chars
$currentTime = time();
$currentDate = date("d/m/y", time());
$readingData = htmlspecialchars($_POST["data"]);//escapes special chars


try{
  //redirect if field is empty 
  if ($readingData < 1) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
  }
  
  $dbConn = getConnection();
  $newReading = "
    INSERT INTO nightingale_glucose (userID, readingTime, readingDate, readingData) 
    VALUES (:userID, '$currentTime','$currentDate',:readingData);
  ";
  $newReadingQuery = $dbConn->prepare($newReading);
  $newReadingQuery->execute(array(
    'userID' => $userID,
    'readingData' => $readingData,
  ));
  
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
}//end try
catch (Exception $e){
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
}//end catch

?>