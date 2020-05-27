<?php 
session_start();
require_once("resources/functions.php");

//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Nightingale Dash</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95, user-scalable=no">
  <link rel="stylesheet" href="css/stylish.css">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      //get tasks
      $('#loadTasks').load('resources/loadTasks.php');
      //preload icons to save on resource loading time
      function preload(arrayOfImages) {
          $(arrayOfImages).each(function(){
              $('<images/>')[0].src = this;
          });
      }
      preload([
          'images/request1.png',
          'images/request2.png',
          'images/request3.png',
          'images/request4.png',
          'images/request5.png'
      ]);  
    });
    //refresh tasks automaticly every 5s 
    var autoload = setInterval(
    function()
      {
        $('#loadTasks').load('resources/loadTasks.php').fadeIn;
      }, 5000);
  </script>
</head>

<body>
  <header class="wrap">
      <nav>
        <a href="staffDashboard.php"><p>Activity</p></a>
        <img class="fallIn" src="https://i.imgur.com/jTlkSyj.png"/>
        <a href="staffAnalytics.php"><p>Analytics</p></a>
      </nav>
      <?php echo '<h2>Hello '.$_SESSION["firstNameStaff"].'</h2>'; ?>
      <p>All current activity is shown here.
    </header>
    <main class= "wrap">
      <div class="splitCol">
        <div class="buttonSplit">
          <a onclick="return confirm('Are you sure you want to alert all members of staff to an emergency?');"  class="requestLink" href="resources/panic.php">
            <section class="splitCol dashBtnRed">
              <img src="images/request1.png"/>
              <p>PANIC</p>
            </section>
          </a>
        </div>
        <div class="buttonSplit">
          <a class="requestLink" href="staffGlucose.php">
            <section class="splitCol dashBtnBlue">
              <img src="images/chart.png"/>
              <p>Glucose Log</p>
            </section>
          </a>
        </div>
      </div>
      <br>
      <div id="loadTasks"></div>
    </main>
</body>
</html>