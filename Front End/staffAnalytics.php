<?php 
session_start();
require_once('resources/functions.php');

//if staff user not logged in 
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}

$message = "";//error messages

try {
  require_once('resources/functions.php');
  $dbConn = getConnection();
  
  if(isset($_POST["location"])){
    $staffID = $_SESSION["staffID"];
    $locationID = $_POST["locationID"];
    //check if any field is empty 
    if(empty($locationID)) {
      $message = 'All fields are required';
    } else {
      staffLocation($locationID, $staffID);
    }
  }
}//end try
catch (PDOException $error){
  $message = $error->getMessage()."</p>\n";
}//end catch

//Check if success
if(isset($_GET["success"])){
  $locationSuccess = "Success, location updated.";
  echo "<script type='text/javascript'>alert('$locationSuccess');</script>";
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
    <p>Update your working location.</p>
    <form id="registerForm" class="borderBlue" method="post">
      <select name="locationID">
        <option value="empty">Select location</option>
        <?php 
          getLocationOptions();
        ?>
      </select>
       <?php 
        if(isset($message)){
          echo '<p>'.$message.'</p>';
        }
        ?>
      <input type="submit" class="submit" name="location" value="Update Location"/>
    </form>
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
    <a class="requestLink" href="resources/logOut.php">
      <section class="splitCol dashBtnOrange">
        <img src="images/delete.png"/>
        <p>Log Out</p>
      </section>
    </a>
    <br>
    
  </main>
    
</body>
</html>