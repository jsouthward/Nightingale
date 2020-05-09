<?php 
session_start();
if(!isset($_SESSION["userID"])){
  header("location:login.php");
  die();
}
?>

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

<body class="fallIn">
  <header class="wrap">
    <img src="https://i.imgur.com/jTlkSyj.png"/>
    <?php echo '<h2>Hello '.$_SESSION["firstName"].'</h2>'; ?>
    <p>Please use the buttons to let a member of staff know you need something.
  </header>
  
  
  
  <main class= "userDash wrap">
    <a class="requestLink" href="createRequest.php?request=1">
      <section class="request emergency">
        <img src="https://i.imgur.com/h1Rt1Fb.png"/>
        <h2>EMERGENCY</h2>
        <p>Please only press this if you are in need of urgent staff attention.</p>
      </section>
    </a>
  </main>
  
  <?php 
  $locationID = $_SESSION["locationID"]; //change to session data
  
  require_once('functions.php');
  //get request options
  echo getRequests($locationID);
  ?>
    
</body>
</html>