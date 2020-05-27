<?php
//link to functions script
require_once('functions.php');

// session data will be here 
$currentTime = time();

//get task ID
$taskID = isset($_REQUEST['Info']) ? $_REQUEST['Info'] : null;

try{
  $dbConn = getConnection();
  $editTask = "
    UPDATE nightingale_alert 
    SET timeCompleted = '$currentTime'
    WHERE taskID = :taskID;
  ";
  $newTaskQuery = $dbConn->prepare($editTask);
  $newTaskQuery->execute(array(
    'taskID' => $taskID,
  ));
  
  header('Location: staffDashboard.php');
  exit();
}//end try
catch (Exception $e){
  header('Location: staffDashboard.php');
  exit();
}//end catch
?>
