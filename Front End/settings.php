<?php 
session_start();
require_once('resources/functions.php');

//if user not logged in 
if(!isset($_SESSION["userID"])){
  header("location:login.php");
}

$message = "";//error messages

try {
  require_once('resources/functions.php');
  $dbConn = getConnection();
  
  if(isset($_POST["location"])){
    $userID = $_SESSION["userID"];
    $locationID = $_POST["locationID"];
    $roomNo = $_POST["roomNo"];
    //check if any field is empty 
    if(empty($locationID) || empty($roomNo)) {
      $message = 'All fields are required';
    } else {
      userLocation($locationID, $userID, $roomNo);
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

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Settings</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95, user-scalable=no">
  <link rel="stylesheet" href="css/stylish.css">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
  <?php darkModeStyle(); textModeStyle(); ?>
</head>

<body>
  <header class="wrap">
    <a class="backBtn" href="dashboard.php">
      <img src="images/arrowBack.png"/>
      <p>back </p>
    </a>
    <h2>Settings</h2> 
    <p>Some settings for the applciation.</p>
  </header>
  <main class= "wrap">
    
  <br>
  <?php 
  if (darkMode() == 1){
    echo '
      <a class="requestLink" href="resources/userSettings.php?dark=0">
        <section class="splitCol dashBtnGreen">
          <img src="images/moon.png"/>
          <p>Dark Mode on</p>
        </section>
      </a>
      <br>';
  } else {
    echo '
    <a class="requestLink" href="resources/userSettings.php?dark=1">
      <section class="splitCol dashBtnOrange">
        <img src="images/sun.png"/>
        <p>Dark Mode off</p>
      </section>
    </a>
    <br>';
  }
  // Font size
  if (textMode() == 1){
    echo '
      <a class="requestLink" href="resources/userSettings.php?text=0">
        <section class="splitCol dashBtnGreen">
          <img src="images/text.png"/>
          <p>Large Text Mode on</p>
        </section>
      </a>
      <br>';
  } else {
    echo '
    <a class="requestLink" href="resources/userSettings.php?text=1">
      <section class="splitCol dashBtnOrange">
        <img src="images/text.png"/>
        <p>Large Text Mode off</p>
      </section>
    </a>
    <br>';
  }
  ?>
  <br>
  <p>Update location and room number.</p>
  <form id="registerForm" class="borderBlue" method="post">
    <select name="locationID">
      <option value="empty">Select location</option>
      <?php 
        include_once('functions.php');
        getLocationOptions();
      ?>
    </select>
    <input required type="number" value="<?php echo $_SESSION['roomNo']; ?>" name="roomNo" id="name" placeholder="Room number" tabindex="2"/>
     <?php 
      if(isset($message)){
        echo '<p>'.$message.'</p>';
      }
      ?>
    <input type="submit" class="submit" name="location" value="Update Location"/>
  </form>
  <br>
  <br>
  <br>
  <br>
  <br>
  
  </main>
  <section class="cancel saveSettings">
    <a href="resources/logOut.php">
      <h2>Log Out</h2>
    </a>
  </section>
</body>
</html>