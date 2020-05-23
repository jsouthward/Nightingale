<?php 
session_start();
require_once('resources/functions.php');

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
  <meta name="viewport" content="width=device-width, initial-scale=0.95">
  <link rel="stylesheet" href="css/stylish.css">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
</head>

<body >
  
  <header class="wrap">
    <nav>
      <a href="staffDashboard.php"><p>Activity</p></a>
      <img class="fallIn" src="https://i.imgur.com/x12dxqm.png"/>
      <a href="staffAnalytics.php"><p>Analytics</p></a>
    </nav>
    <h2>Analytics</h2> 
    <p>Various analytics to help understand usage.
  </header>
  <main class= "wrap">
    <?php 
      getAnalytics();
    ?>
    <br>
    <a onclick="return confirm('Are you sure you want to remove all completed requests?');" class="requestLink" href="resources/deleteCompleted.php">
      <section class="splitCol dashBtnRed">
        <img src="images/delete.png"/>
        <p>Remove completed requests</p>
      </section>
    </a>
    <br>
    <?php 
      // if admin display admin settings button
      if (checkIfAdmin() == 1){
        echo '  <a class="requestLink" href="adminDashboard.php">
                  <section class="splitCol dashBtnBlue">
                    <img src="images/admin.png"/>
                    <p>Admin settings</p>
                  </section>
                </a>
                <br>';
      }
    ?>
    
  </main>
    
</body>
</html>