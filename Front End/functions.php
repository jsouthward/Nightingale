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
    
    $staffLocationID = '1';//change me!!
    
    //Query to retrieve events that have not been accepted
    $sqlRequests = "
      SELECT 
        nightingale_alert.taskID, 
        nightingale_alert.timeCreated, 
        nightingale_alert.timeAccepted, 
        nightingale_alert.timeCompleted,  
        nightingale_user.firstName, 
        nightingale_user.lastName,
        nightingale_user.roomNo,
        nightingale_request.requestName,
        nightingale_request.imgURL
      FROM nightingale_alert
        INNER JOIN nightingale_user ON nightingale_alert.userID=nightingale_user.userID
        LEFT JOIN nightingale_request ON nightingale_alert.requestID=nightingale_request.requestID
      WHERE timeCompleted IS NULL
      AND timeAccepted IS NULL
      AND nightingale_alert.locationID = '$staffLocationID';
    ";
    $queryRequestsResult = $dbConn->query($sqlRequests);

    while ($rowObj = $queryRequestsResult->fetchObject()){
      //Get Time elapsed 
      $minutes = getTimeElasped($rowObj->timeCreated);
      //Display Request info 
      echo "
      <a class='activityLink' href='staffRequest.php?taskID={$rowObj->taskID}'>
        <section class='staffRequest'>";
      //Task Icon based on request type 
      echo "
      <div class='requestIcon1'>
        <img src='{$rowObj->imgURL}'/>
      </div>";//Change colour class for each request type 
      echo "<div class='staffRequestInfo'>";
      echo "<p><b>Room: {$rowObj->roomNo}</b></p>";
      echo "<p>Patient: {$rowObj->firstName} {$rowObj->lastName}</p>";
      echo "<p>Request: {$rowObj->requestName}</p>";                
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
    
    $staffLocationID = '1';//change me! 
  
    $sqlAccRequests = "
      SELECT 
        nightingale_alert.taskID, 
        nightingale_alert.staffID, 
        nightingale_alert.timeCreated, 
        nightingale_alert.timeAccepted, 
        nightingale_alert.timeCompleted, 
        nightingale_user.roomNo, 
        nightingale_request.requestName, 
        nightingale_user.firstName, 
        nightingale_user.lastName
      FROM nightingale_alert
        INNER JOIN nightingale_user ON nightingale_alert.userID=nightingale_user.userID
        LEFT JOIN nightingale_request ON nightingale_alert.requestID=nightingale_request.requestID
      WHERE staffID = '$acceptedBy'
      AND nightingale_alert.locationID = '$staffLocationID'
      ORDER BY timeCreated;
    ";
    $queryAccRequestsResult = $dbConn->query($sqlAccRequests);
    while ($rowObj = $queryAccRequestsResult->fetchObject()){
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
      echo "<p><b>Room: {$rowObj->roomNo}</b></p>";
      echo "<p>Patient: {$rowObj->firstName} {$rowObj->lastName}</p>";
      echo "<p>Request: {$rowObj->requestName}</p>";                
      echo "</div><span class='acceptedTime'><p>{$minutes}</p></span>";
      echo "</section></a>";
    }//end while
    echo "<hr>";
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

function getRequestsTable($locationID) {
  try {
  $dbConn = getConnection();
    
    $staffLocationID = '1';//change me! 
  
    $sqlAccRequests = "
      SELECT 
      FROM nightingale_request
      WHERE 
    ";
    $queryAccRequestsResult = $dbConn->query($sqlAccRequests);

    while ($rowObj = $queryAccRequestsResult->fetchObject()){
      
    }//end while
    echo "<hr>";
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

function getGlucoseData($queryDate) {
  require_once('functions.php');
  $userID = $_SESSION["userID"]; // replace me !!!

  try{
    $dbConn = getConnection();
    //Query to retrieve requests for location
    $sql = "
      SELECT *
      FROM nightingale_glucose
      WHERE userID = '$userID'
      AND readingDate = '$queryDate';
    ";
    $queryResult = $dbConn->query($sql);
    while ($rowObj = $queryResult->fetchObject()){
      $hours = date('g', $rowObj->readingTime);
      $mins = date('i', $rowObj->readingTime);
      $reading = $rowObj->readingData;
      echo "[new Date(2015, 0, 1, ".$hours.", ".$mins."), ".$reading."],";
    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

function glucoseThreat($mgdL){
  if($mgdL > 215){
    echo "<td class='highGlucose'><b>$mgdL</b></td>";
  } else {
    echo "<td class='safeGlucose'><b>$mgdL</b></td>";
  }
}

function getGlucoseDataTable() {
  require_once('functions.php');
  $userID = $_SESSION["userID"];

  try{
    $dbConn = getConnection();
    //Query to retrieve requests for location
    $sql = "
      SELECT *
      FROM nightingale_glucose
      WHERE userID = '$userID';
    ";
    $queryResult = $dbConn->query($sql);
    while ($rowObj = $queryResult->fetchObject()){
      
      $dateTimes = date('d/m/y g:i', $rowObj->readingTime);
      $reading = $rowObj->readingData;

      echo "
      <tr><td>$dateTimes</td>";
      glucoseThreat($reading);
      echo "</tr>";
    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

//get requests for user location - for user dashboard page 
function getRequests($locationID){
  try{
    $dbConn = getConnection();
    //Query to retrieve requests for location
    $sqlRequests = "
      SELECT 
        requestID, requestName, description, imgURL, locationID 
      FROM nightingale_request
      WHERE locationID = $locationID
      ORDER BY requestID
    ";//OR locationID IS NULL 
    $queryRequestsResult = $dbConn->query($sqlRequests);
    echo '<aside class="wrapScroll"> 
    <p>Non Emeregency Requests</p>
    <section class="requestSlider">
    <div class="slides">';
    while ($rowObj = $queryRequestsResult->fetchObject()){
      echo "<div class='request customRequest'>
              <a class='requestLink' href='createRequest.php?request={$rowObj->requestID}'>
                <img src='{$rowObj->imgURL}'/>
                <h2>{$rowObj->requestName}</h2>
                <p>{$rowObj->description}</p> 
              </a>
            </div>";
    }//end while
    echo '</div></section></aside>';
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

?>