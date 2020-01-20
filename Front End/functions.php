<?php
// Database connection
function getConnection() {
  try {
    $connection = new PDO('mysql:host=localhost;dbname=unn_w15024065', 'unn_w15024065', 'Techfuture12');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $connection;
  } catch (Exception $e) {
    throw new Exception('Connection error '. $e->getMessage(), 0, $e);
  }
}

// Function Time elapsed, display time in mins between two timestams
function getTimeElasped($start_time) {
  $since_start = time() - $start_time;
  $minutes = round( $since_start / 60); 
  if ($minutes < 2 ){
    $minutes = "1 min";
  } 
  elseif ($minutes > 60) {
    $minutes = round($minutes / 60);
    $minutes = "over ". $minutes ."h";
  }
  else {
    $minutes = $minutes . " mins";
  }
  return $minutes;
}

//get list of tasks 
function getTasks(){
  try{
    $dbConn = getConnection();
    //Query to retrieve events that have not been accepted
    $sqlRequests = "SELECT taskID, acceptedBy, timeCreated, timeAccepted, timeCompleted, location, user, requestType
      FROM nightingale_alert
      WHERE timeCompleted IS NULL 
      AND timeAccepted IS NULL
      ORDER BY timeCreated";
    $queryRequestsResult = $dbConn->query($sqlRequests);

    while ($rowObj = $queryRequestsResult->fetchObject()){
      //Get Time elapsed 
      $minutes = getTimeElasped($rowObj->timeCreated);
      //Display Request info
      echo "
      <a class='activityLink' href='staffRequest.php?taskID={$rowObj->taskID}'>
        <section class='staffRequest'>";
      //Task Icon based on request type 
      if ($rowObj->requestType == 'Emergency') {
        echo "
        <div class='requestIcon1'>
          <img src='images/request1.png'/>
        </div>";
      } elseif ($rowObj->requestType == 'Query'){
        echo "
        <div class='requestIcon2'>
          <img src='images/request2.png'/>
        </div>";
      } elseif ($rowObj->requestType == 'Sanitery'){
        echo "
        <div class='requestIcon4'>
          <img src='images/request4.png'/>
        </div>";
      } elseif ($rowObj->requestType == 'Pain'){
        echo "
        <div class='requestIcon3'>
          <img src='images/request3.png'/>
        </div>";
      };
      echo "<div class='staffRequestInfo'>";
      echo "<p><b>Room: {$rowObj->location}</b></p>";
      echo "<p>Patient: {$rowObj->user}</p>";
      echo "<p>Request: {$rowObj->requestType}</p>";                
      echo "</div><span class='time'><p>{$minutes}</p></span>";
      echo "</section></a>";
    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

function getAcceptedTasks($acceptedBy) {
  try {
  $dbConn = getConnection($acceptedBy);
    //Query to retrieve events 
    $sqlRequests = "SELECT taskID, acceptedBy, timeCreated, timeAccepted, timeCompleted, location, user, requestType
      FROM nightingale_alert
      WHERE acceptedBy = '$acceptedBy' 
      AND timeCompleted IS NULL
      ORDER BY timeCreated";
    $queryRequestsResult = $dbConn->query($sqlRequests);

    while ($rowObj = $queryRequestsResult->fetchObject()){
      //Get Time elapsed 
      $minutes = getTimeElasped($rowObj->timeCreated);
      //Display Request info
      echo "
      <a class='activityLink' href='staffRequest.php?taskID={$rowObj->taskID}'>
        <section class='staffRequest'>
          <div class='acceptedRequestIcon'>
            <img src='https://i.imgur.com/IqWX5sf.png'/>
          </div>
        <div class='staffRequestInfo'>
      ";
      echo "<p><b>Room: {$rowObj->location}</b></p>";
      echo "<p>Patient: {$rowObj->user}</p>";
      echo "<p>Request: {$rowObj->requestType}</p>";                
      echo "</div><span class='acceptedTime'><p>{$minutes}</p></span>";
      echo "</section></a>";
    }//end while
    echo "<hr>";
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

?>