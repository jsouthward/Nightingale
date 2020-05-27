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
  <?php darkModeStyle(); textModeStyle(); ?>
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
</head>
<body class="fallIn">
  
  <header class="wrap">
  <div class="fadein">
    <img id="f1" src="images/bird1.png">
    <img id="f2" src="images/bird2.png">
    <img id="f3" src="images/bird3.png">
    <img id="f4" src="images/bird4.png">
  </div>
  <h2>A nurse will be with you shortly.</h2> 
    <p>You can select more information to help us understand your problem.</p>
    <div id="player"></div>
    <form class="infoForm" method="post">
      <input hidden type="textbox" value="A member of staff will recieve your request. We will be with you as soon as possible." id="txt" name="txt"/>
      <input class="infoBtn" type="button" name="txt" onclick="getAudio()"/>
      Audio Help
    </form>
</header>
<main class= "userDash wrap waiting">
  <div class="informationWrap">
  <a class="requestLink" href="resources/createRequest.php?request=1">
    <section class="splitCol dashBtnGreen">
      <img src="images/request5.png"/>
      <p>Dizzyness or Nausea</p>
    </section>
  </a>
  <br>
  <a class="requestLink" href="resources/createRequest.php?request=1">
    <section class="splitCol dashBtnGreen">
      <img src="images/request3.png"/>
      <p>Severe pain levels</p>
    </section>
  </a>
  <br>
  </div>
</main>
<section class="cancel">
  <a href="resources/userDeleteRequest.php" onclick="return confirm('Are you sure you want to remove your request?');">
    <h2>Cancel Request</h2>
  </a>
</section>
    
</body>
</html>