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
</head>

<body>
  <header class="wrap">
      <nav>
        <a href="staffDashboard.html"><p>Activity</p></a>
        <img class="fallIn" src="https://i.imgur.com/0DVcOQv.png"/>
        <a href="staffAnalytics.html"><p>Analytics</p></a>
      </nav>
      <h2>Hello Jake,</h2> 
      <p>All current activity is shown here.
    </header>
    <main class= "wrap">
    <?php
      require_once('functions.php');
      try{
        $dbConn = getConnection();
        //Query to retrieve events
        $sqlRequests = "SELECT taskID, acceptedBy, timeCreated, timeAccepted, location, user, requestType
          FROM nightingale_alert
          ORDER BY timeCreated";
        $queryRequestsResult = $dbConn->query($sqlRequests);

        while ($rowObj = $queryRequestsResult->fetchObject()){
          //Get Time elapsed 
          $minutes = getTimeElasped($rowObj->timeCreated);
          //Display Request info
          echo "
          <a class='activityLink' href='staffRequest.php?taskID={$rowObj->taskID}'>
            <section class='staffRequest'>
              <div class='requestIcon'>
                <img src='https://i.imgur.com/h1Rt1Fb.png'/>
              </div>
            <div class='staffRequestInfo'>
          ";
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
    ?>

    </main>
</body>
</html>