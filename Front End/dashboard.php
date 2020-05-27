<?php 
session_start();
require_once('resources/functions.php');

//if user not logged in 
if(!isset($_SESSION["userID"])){
  header("location:login.php");
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
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script>
    function getAudio(){
      var txt=jQuery('#txt').val()
      jQuery.ajax({
        url:'resources/getAudio.php',
        type:'post',
        data:'txt='+txt,
        success:function(result){
          jQuery('#player').html(result);
        }
      });
    }
  </script>
  <?php darkModeStyle(); textModeStyle(); ?>
</head>

<body class="fallIn">
  <header class="wrap">
    <div class="splitCol spaceBetween">
      <div><img src="images/birdicon.png"/></div>
      <div><a href="settings.php"><img src="images/menu.png"/></a></div>
    </div>
    <?php echo '<h2>Hello '.$_SESSION["firstName"].'</h2>'; ?>
    <p>Please use the buttons to let a member of staff know you need something.</p>
    <div id="player"></div>
    <form class="infoForm" method="post">
      <input hidden type="textbox" value="you can use the buttons below to request help, or record Glucose levels. please only use the emergency button if you need urgent staff attention." id="txt" name="txt"/>
      <input class="infoBtn" type="button" name="txt" onclick="getAudio()"/>
      Audio Help
    </form>
  </header>
  
  <main class= "userDash wrap">
    <a class="requestLink" href="resources/createRequest.php?request=1">
      <section class="request emergency">
        <img src="images/request1.png"/>
        <h2>EMERGENCY</h2>
        <p>Please only press this if you are in need of urgent staff attention.</p>
      </section>
    </a>
  </main>
  
  <?php 
  //get custom requests for location
  $locationID = $_SESSION["locationID"];
  echo getRequests($locationID);
  ?>
  
  <main class= "userDash wrap">
    <a class="requestLink" href="glucose.php">
      <section class="splitCol dashBtnBlue">
        <img src="images/chart.png"/>
        <p>View and Log your blood glucose levels.</p>
      </section>
    </a>
    <br/>
  </main>
    
</body>
</html>