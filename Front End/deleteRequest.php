<?php
//link to functions script
require_once('functions.php');

//get task id
// WARNING CHANGE THIS SQL INJECTION RISK!!!
$taskID = isset($_REQUEST['taskID']) ? $_REQUEST['taskID'] : null;
try{
  $dbConn = getConnection();

  $deleteTask = "
  DELETE FROM nightingale_alert
    WHERE taskID = '$taskID'
  ";
  $deleteQuery = $dbConn->query($deleteTask);
  if ($deleteQuery === false) {
    header('Location: staffRequest.php');
    exit();
  } else {
    //if deleted
    header('Location: staffRequest.php');
    exit();
  }
}//end try
catch (Exception $e){
  header('Location: staffRequest.php');
  exit();
}//end catch
?>