<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Nightingale Dash</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95">
  <link rel="stylesheet" href="stylish.css">
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
  <header class="fullHeader">
    <img src="https://i.imgur.com/h1Rt1Fb.png"/>
    <h2>EMERGENCY</h2>
  </header>
  
  <?php
  require_once('functions.php');
  try{
    $dbConn = getConnection();
    // WARNING CHANGE THIS SQL INJECTION RISK!!!
    $taskID = isset($_REQUEST['taskID']) ? $_REQUEST['taskID'] : null;
    
    //Query to retrieve events
    $sqlRequests = "SELECT taskID, acceptedBy, timeCreated, timeAccepted, location, user, requestType
      FROM nightingale_alert
      WHERE taskID = '$taskID'
      ORDER BY timeCreated";
    $queryRequestsResult = $dbConn->query($sqlRequests);

    while ($rowObj = $queryRequestsResult->fetchObject()){
      //Get Time elapsed 
      $minutes = getTimeElasped($rowObj->timeCreated);
      //Display Request info if task is available 
      echo "
      <main class='fullRequest scaleIn'>
        <div class='fullWrap'>
          <span class='time'><p><b>{$minutes}</b></p></span>
        </div>
        <p>Room Number: <b>{$rowObj->location}</b></p>
        <p>Patient Name: <b>{$rowObj->user}</b></p>
        <p>Request Type: <b>{$rowObj->requestType}</b></p>
        ";
        $accepted = $rowObj->acceptedBy;
        //request has NOT been accepted
        if (!empty($accepted)){
          echo "
          <p>Accepted by: <b>{$rowObj->acceptedBy}</b></p>
          <div class='fullWrap'>
          <a href='completeRequest.php?taskID={$rowObj->taskID}'>
            <input class='completeRequest' type='submit' value='Request Completed'>
          </a>
          <a onclick='return confirm('Are you sure you want to remove request?');' href='deleteRequest.php?taskID={$rowObj->taskID}'>
            <input class='delete' type='submit' value='Delete'>
          </a></div></main>";  
        }//end if
      //request has allready been accepted 
      else {
        echo "
        <div class='fullWrap'>
        <a href='acceptRequest.php?taskID={$rowObj->taskID}'>
          <input class='acceptRequest' type='submit' value='Accept Request'>
        </a>
        <a onclick='return confirm('Are you sure you want to remove request?');' href='deleteRequest.php?taskID={$rowObj->taskID}'>
          <input class='delete' type='submit' value='Delete'>
        </a></div></main>"; 
      }//end else
    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
?>
    
    
  <a href="staffDashboard.php"><p> Back to Dashboard</p></a>
</main>
    
</body>
</html>