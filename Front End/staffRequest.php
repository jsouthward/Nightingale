<?php 
session_start();
require_once('resources/functions.php');

//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
$staffLocationID = $_SESSION["locationID"];

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Nightingale Dash</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95">
  <link rel="stylesheet" href="css/stylish.css">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
  <style>
    body {
      background-image: linear-gradient(to bottom left, #fbb040, #ef4136);
      height: 100vh;
    }
  </style>
</head>

<body >
  <main class="wrap">
    <a class="backBtnWhite" href="staffDashboard.php">
      <img src="images/arrowBackWhite.png"/>
      <p>back </p>
    </a>
  
  <?php
    
  if(!isset($_GET['taskID'])){
    echo "
      <header class='fullHeader'>
        <img src='images/delete.png'/>
        <h2>Request Deleted</h2>
      </header>
    ";
  }  

  try{
    $dbConn = getConnection();
    $taskID = isset($_REQUEST['taskID']) ? $_REQUEST['taskID'] : null;
    
    $sqlRequests = "
      SELECT 
        nightingale_alert.taskID, 
        nightingale_alert.staffID,
        nightingale_alert.userID,
        nightingale_alert.timeCreated, 
        nightingale_alert.timeAccepted, 
        nightingale_alert.timeCompleted, 
        nightingale_user.roomNo, 
        nightingale_user.firstName, 
        nightingale_user.lastName,
        nightingale_request.requestName,
        nightingale_request.imgUrl
        
      FROM nightingale_alert
        INNER JOIN nightingale_user ON nightingale_alert.userID=nightingale_user.userID
        INNER JOIN nightingale_location ON nightingale_alert.locationID=nightingale_location.locationID
        LEFT JOIN nightingale_request ON nightingale_alert.requestID=nightingale_request.requestID
      WHERE taskID = '$taskID'
      AND nightingale_alert.locationID = '$staffLocationID'
      ORDER BY timeCreated
    ";
    $queryRequestsResult = $dbConn->query($sqlRequests);

    while ($rowObj = $queryRequestsResult->fetchObject()){
      //Get Time elapsed 
      $minutes = getTimeElasped($rowObj->timeCreated);
      
      //Display Request info if task is available 
      echo "
      <header class='fullHeader'>
        <img src='{$rowObj->imgUrl}'/>
        <h2>{$rowObj->requestName}</h2>
      </header>
      <main class='fullRequest scaleIn'>
        <div class='fullWrap'>
          <span class='time'><p><b>{$minutes}</b></p></span>
        </div>
        <p>Room Number: <b>{$rowObj->roomNo}</b></p>
        <p>Patient Name: <b>{$rowObj->firstName} {$rowObj->lastName}</b></p>
        <p>Request: <b>{$rowObj->requestName}</b></p>
        
        <br>
        <a class='requestLink' href='staffGlucose.php?user={$rowObj->firstName}+{$rowObj->lastName}&userID={$rowObj->userID}'>
          <section class='splitCol dashBtnBlue'>
            <img src='images/chart.png'/>
            <p>View Glucose data</p>
          </section>
        </a>
        
      </main>
      
      
        ";
        $accepted = $rowObj->timeAccepted;
        //request has NOT been accepted
        if (!empty($accepted)){
          echo "
          <div class='fullWrap'>
          <a class='acceptDelete' href='resources/completeRequest.php?taskID={$rowObj->taskID}'>
            <input class='completeRequest' type='submit' value='Request Completed'>
          </a>";
          echo '<a onclick="return confirm('."'Are you sure you want to remove request?'".')"href="resources/deleteRequest.php?taskID='.$rowObj->taskID.'">';
          echo "<input class='delete' type='submit' value='Delete'>
          </a></div>";  
        }//end if
      //request has allready been accepted 
      else {
        echo "
        <div class='fullWrap'>
        <a class='acceptDelete' href='resources/acceptRequest.php?taskID={$rowObj->taskID}'>
          <input class='acceptRequest' type='submit' value='Accept Request'>
        </a>";
        echo '<a onclick="return confirm('."'Are you sure you want to remove request?'".')"href="resources/deleteRequest.php?taskID='.$rowObj->taskID.'">';
        echo "<input class='delete' type='submit' value='Delete'>
        </a></div>";  
      }//end else
    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
?>
  

</main>
    
</body>
</html>