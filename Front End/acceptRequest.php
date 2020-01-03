<?php
//link to functions script
require_once('functions.php');

// session data will be here 
$StaffName = "Staff Test Name";
$currentTime = time();

//get task ID
$taskID = isset($_REQUEST['taskID']) ? $_REQUEST['taskID'] : null;

try{
  //add to database
  // WARNING CHANGE THIS SQL INJECTION RISK!!!
  // https://stackoverflow.com/questions/4364686/how-do-i-sanitize-input-with-pdo
  $dbConn = getConnection();
  $editTask = "
    UPDATE nightingale_alert 
    SET timeAccepted = '$currentTime', acceptedBy = '$StaffName'
    WHERE taskID = '$taskID';
  ";
  $newTaskQuery = $dbConn->exec($editTask);
  // WARNING CHANGE THIS SQL INJECTION RISK!!!

  header('Location: staffDashboard.php');
  exit();
}//end try
catch (Exception $e){
  header('Location: staffDashboard.php');
  exit();
}//end catch
?>
