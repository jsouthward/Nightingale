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

// check if user is admin return 1 if admin 0 if not an admin
function checkIfAdmin() {
  //if staff user not logged in 
  if(isset($_SESSION["staffID"])){
    $dbConn = getConnection();
    $staffID = $_SESSION["staffID"];
    
    $sql = "  SELECT admin 
              FROM nightingale_staffUser
              WHERE staffID = '$staffID';";
    
    $queryResult = $dbConn->query($sql);

    while ($rowObj = $queryResult->fetchObject()){
      $admin = (int) $rowObj->admin;
      if ($admin == 1){
        return 1;
      } else {
        return 0;
      }
    }//end while
  } else {
    header("location:staffLogin.php");
  }
}

//count rows of query
function rowCount($query) {
  $dbConn = getConnection();
  $stmt = $dbConn->prepare($query);
  $stmt->execute();
  return $stmt->rowCount();
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

//get user and user ID for all users in a location, to autocomplete user search
function autocompleteUser($locationID) {
  require_once('functions.php');

  try{
    $dbConn = getConnection();
    //Query to retrieve users by name
    $sql = "
      SELECT firstName, lastName, userID
      FROM nightingale_user
      WHERE locationID = '$locationID';
    ";
    $queryResult = $dbConn->query($sql);
    while ($rowObj = $queryResult->fetchObject()){
      
      $fn = $rowObj->firstName;
      $ln = $rowObj->lastName;
      $id = $rowObj->userID;
      
      echo '{value: "'.$id.'",label: "'.$fn.' '.$ln.'"},';

    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

//get list of tasks for staff dashboard
function getTasks(){
  try{
    $dbConn = getConnection();
    
    $staffLocationID = $_SESSION["locationID"];
    
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
      <div class='requestIcon'>
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

//get list of Accepted tasks for staff dashboard
function getAcceptedTasks($acceptedBy) {
  try {
  $dbConn = getConnection();
    $notEmpty = false;
    
    $staffLocationID = $_SESSION["locationID"];
  
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
      AND timeCompleted IS NULL
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
      $notEmpty = true;
    }//end while
    if ($notEmpty == true){
      echo "<hr>";
    } else {
      echo "<br>";
    }
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

//get list of staff panic button tasks for staff dashboard
function getPanicTasks() {
  try {
  $dbConn = getConnection();
    $notEmpty = false;
    
    $staffLocationID = $_SESSION["locationID"];
  
    $sql = "
      SELECT 
        nightingale_alert.taskID, 
        nightingale_alert.requestID, 
        nightingale_alert.staffID, 
        nightingale_alert.timeCreated,
        nightingale_staffUser.firstNameStaff, 
        nightingale_staffUser.lastNameStaff
      FROM nightingale_alert
      INNER JOIN nightingale_staffUser ON nightingale_alert.staffID=nightingale_staffUser.staffID
      WHERE nightingale_alert.locationID = '$staffLocationID'
      AND timeCompleted IS NULL
      AND requestID = 0
      ORDER BY timeCreated;
    ";
    $queryResult = $dbConn->query($sql);
    while ($rowObj = $queryResult->fetchObject()){
      //Get Time elapsed 
      $minutes = getTimeElasped($rowObj->timeCreated);
      //Display Request info
      echo "
        <a class='requestLink scaleIn'>
        <section class='splitCol dashOverlay'>";
      echo '<a href="resources/deleteRequest.php?taskID='.$rowObj->taskID.'" onclick="return confirm('."'Are you sure you want to delete request?'".')">';
      echo " <img src='images/delete.png'/>
          </a>
          <p>Urgent Request<br> Needs help!</p>
          <p>Staff Member:<br> {$rowObj->firstNameStaff} {$rowObj->lastNameStaff}</p>
        </section>
      </a>
      <br>
      ";
           
    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

//Admin dashboard, get table of requets for admin user location
function getRequestsTable($locationID) {
  try {
    $dbConn = getConnection();
    $staffLocationID = $_SESSION["locationID"];
    $sql = "
      SELECT *
      FROM nightingale_request
      WHERE locationID = '$locationID'
    ";
    $queryResult = $dbConn->query($sql);
    
    echo '
      <table class="adminTable">
      <tr><th>Name</th><th>edit</th><th>Delete</th></tr>';
    
    while ($rowObj = $queryResult->fetchObject()){
      echo "
        <tr>
        <td>{$rowObj->requestName}</td>
        <td><a href='adminDashboard.php?edit={$rowObj->requestID}'>edit</a></td>";
      echo '
        <td><a onclick="return confirm('."'Are you sure you want to remove Request?'".')" href="resources/adminDeleteRequest.php?requestID='."{$rowObj->requestID}".'">delete</a></td>
        </tr>
      ';
    }//end while
    echo '<tr><td colspan="3"><a href="adminDashboard.php?add=true">+ Add New Request</a></td></tr></table>';
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

//add new request to the db for admin location
function addNewRequest($rName, $rDesc, $rImg) {
  $dbConn = getConnection();
  
  $locationID = $_SESSION["locationID"];
  $rName = htmlspecialchars($rName);
  $rDesc = htmlspecialchars($rDesc);
  $rImg = htmlspecialchars($rImg);
  
  $query = "  INSERT INTO nightingale_request (requestName, Description, imgUrl, locationID) 
              VALUES ('$rName', '$rDesc', '$rImg', '$locationID')";
  $newRequestQuery = $dbConn->exec($query);
  //redirect
  header('Location: adminDashboard.php');
  exit();
}

function editRequest($eName, $eDesc, $eImg, $requestID) {
  $dbConn = getConnection();
  
  $locationID = $_SESSION["locationID"];
  $eName = htmlspecialchars($eName);
  $eDesc = htmlspecialchars($eDesc);
  $eImg = htmlspecialchars($eImg);
  $requestID = htmlspecialchars($requestID);
  
  $query = "  UPDATE nightingale_request 
              SET requestName='$eName', Description='$eDesc', imgUrl='$eImg', locationID='$locationID' 
              WHERE locationID = '$locationID'
              AND requestID = '$requestID'";
  $editRequestQuery = $dbConn->exec($query);
  //redirect
  header('Location: adminDashboard.php');
  exit();
}

//get glucose data of user in correct format for google chart 
function getGlucoseData($queryDate, $userID) {
  require_once('functions.php');

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
      $hours = date('G', $rowObj->readingTime);
      $mins = date('i', $rowObj->readingTime);
      $reading = $rowObj->readingData;
      echo "[new Date(2015, 0, 1, ".$hours.", ".$mins."), ".$reading."],";
    }//end while
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}

//echo threat level for glucose reading
function glucoseThreat($mgdL){
  if($mgdL >= 215){
    echo "<td class='dangerGlucose'><b>$mgdL</b></td>";
  } else if($mgdL >= 150 && $mgdL <= 214 ) {
    echo "<td class='highGlucose'><b>$mgdL</b></td>";
  } else {
    echo "<td class='safeGlucose'><b>$mgdL</b></td>";
  }
}

//generate table of glucose readings for user 
function getGlucoseDataTable($queryDate, $userID) {
  require_once('functions.php');

  try{
    $dbConn = getConnection();
    //Query to retrieve requests for location
    $sql = "
      SELECT *
      FROM nightingale_glucose
      WHERE userID = '$userID'
      AND readingDate = '$queryDate'
      ORDER BY readingTime DESC;
    ";
    $queryResult = $dbConn->query($sql);
    
    // if there is data 
    if (rowCount($sql) > 0){
      echo "
      <p>All glucose records.</p>
      <div class='splitCol glucoseKey'>
        <p class='safeGlucose'>Excellent</p>
        <p class='highGlucose'>Good </p>
        <p class='dangerGlucose'>Action Required</p>
      </div>
      <table class='adminTable'>
      <tr>
        <th>Date/time</th>
        <th>mg/dL</th> 
      </tr>
      ";
      while ($rowObj = $queryResult->fetchObject()){
        $dateTimes = date('d/m/y G:i', $rowObj->readingTime);
        $reading = $rowObj->readingData;
        echo "
        <tr><td>$dateTimes</td>";
        glucoseThreat($reading);
        echo "</tr>";
      }//end while
      echo "</table>";
    } // end if data
    else {
      echo "<p>No data, Log blood glucose levels above.</p>";
    }
    
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
              <a class='requestLink' href='resources/createRequest.php?request={$rowObj->requestID}'>
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

//get analytics for staff dashhboard
function getAnalytics(){
  try{
    $dbConn = getConnection();
    
    $staffLocationID = $_SESSION["locationID"];
    
    //Query to get avarage time taken to complete requests
    $sqlAvg = "
      SELECT locationID, AVG(timeCompleted - timeCreated) AS completedAvg
      FROM nightingale_alert
      WHERE timeCompleted IS NOT NULL
      AND locationID = '$staffLocationID';
    ";
    $queryResult = $dbConn->query($sqlAvg);
    while ($rowObj = $queryResult->fetchObject()){
      //convert timestamp to mins
      $minutes = round( $rowObj->completedAvg / 60);
    };//end while
    echo '
    <section class="staffAnalytics">
      <div class="staffAnalyticInfo">
        <p><b>Current open requests</b></p>
      </div>
      <span class="analyticValue"><p>'.rowCount("SELECT * FROM nightingale_alert WHERE timeCompleted IS NULL AND locationID = $staffLocationID;").'</p></span>
    </section>
    <section class="staffAnalytics">
      <div class="staffAnalyticInfo">
        <p><b>Avarage waiting time</b></p>
      </div>
      <span class="analyticValue2"><p>'.$minutes.' mins</p></span>
    </section>
    <section class="staffAnalytics">
      <div class="staffAnalyticInfo">
        <p><b>Completed requests</b></p>
      </div>
      <span class="analyticValue3"><p>'.rowCount("SELECT * FROM nightingale_alert WHERE timeCompleted IS NOT NULL AND locationID = $staffLocationID;").'</p></span>
    </section>
    ';
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}


function getLocationOptions(){
  try{
    $dbConn = getConnection();
    //Query to retrieve location names
    $sql = "SELECT * FROM nightingale_location;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row) {
      echo '<option value="'.$row['locationID'].'">'.$row['location'].'</option>'; 
    }
   
  }//end try
  catch (Exception $e){
    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
  }//end catch
}


function addNewUser($email, $password, $fName, $lName, $location, $roomNo) {
  $dbConn = getConnection();
  //hash password
  $passwordHash = md5($password); 
  $query = "INSERT INTO nightingale_user (email, password, firstName, lastName, locationID, roomNo) VALUES ('$email', '$passwordHash', '$fName', '$lName', '$location', '$roomNo')";
  $newUserQuery = $dbConn->exec($query);
  //redirect
  header('Location: dashboard.php');
  exit();
}

//delete all completed tasks for location
function deleteCompleted() {
  try{
    $dbConn = getConnection();

    $staffLocationID = $_SESSION["locationID"];
    
    $deleteCompleted = "
    DELETE FROM nightingale_alert
      WHERE timeCompleted IS NOT NULL
      AND locationID = $staffLocationID;
    ";
    $deleteQuery = $dbConn->query($deleteCompleted);
    if ($deleteQuery === false) {
      header('Location: ../staffAnalytics.php');
      exit();
    } else {
      //if deleted
      header('Location: ../staffAnalytics.php');
      exit();
    }
  }//end try
  catch (Exception $e){
    header('Location: ../staffAnalytics.php');
    exit();
  }//end catch
}

function panic($staffLocation, $staffID) {
  try{
    $dbConn = getConnection();
    $currentTime = time();
    $newTask = "
      INSERT INTO nightingale_alert (timeCreated, locationID, staffID, requestID) 
      VALUES ('$currentTime','$staffLocation','$staffID', '0');
    ";
    $newTaskQuery = $dbConn->exec($newTask);
    
    header('Location: ../staffDashboard.php');
    exit();
  }//end try
  catch (Exception $e){
    header('Location: ../staffDashboard.php');
    exit();
  }//end catch
}

?>