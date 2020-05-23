<?php
//link to functions script
require_once('functions.php');

//replace with session user
session_start();
$userID = $_SESSION["userID"];
  
try{
  $dbConn = getConnection();

  $deleteTask = "
  DELETE FROM nightingale_alert
    WHERE userID = '$userID'
    AND timeCompleted IS NULL
    AND timeAccepted IS NULL
  ";
  $deleteQuery = $dbConn->query($deleteTask);
  if ($deleteQuery === false) {
    header('Location: ../dashboard.php');
    exit();
  } else {
    //if deleted
    header('Location: ../dashboard.php');
    exit();
  }
}//end try
catch (Exception $e){
  header('Location: ../dashboard.php');
  exit();
}//end catch
?>