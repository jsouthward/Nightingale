<?php
//link to functions script
require_once('functions.php');

//replace with session user
$user = "Jake Test";
  
try{
  $dbConn = getConnection();

  $deleteTask = "
  DELETE FROM nightingale_alert
    WHERE user = '$user'
    AND timeCompleted IS NULL
  ";
  $deleteQuery = $dbConn->query($deleteTask);
  if ($deleteQuery === false) {
    header('Location: dashboard.php');
    exit();
  } else {
    //if deleted
    header('Location: dashboard.php');
    exit();
  }
}//end try
catch (Exception $e){
  header('Location: dashboard.php');
  exit();
}//end catch
?>