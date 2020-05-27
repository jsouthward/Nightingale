<?php 
session_start();
require_once('resources/functions.php');

//Check if staff user and admin user
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
}
if (!checkIfAdmin() == 1){
  header("location:staffLogin.php");
}

$message = "";//error messages

if(isset($_GET["add"])){
  try {
    $dbConn = getConnection();

    if(isset($_POST["add"])){
      $rName = $_POST["rName"];
      $rDesc = $_POST["rDesc"];
      $rImg = $_POST["rImg"];
      //check if any field is empty 
      if(empty($rName) || empty($rDesc)) {
        $message = 'All fields are required';
      } else {
        //Sanitize and add to db
        if(empty($rImg)){
          $rImg = 'images/request1.png';
        }
        addNewRequest($rName, $rDesc, $rImg);
        header("location:adminDashboard.php");
      }
    }
  }//end try
  catch (PDOException $error){
    $message = $error->getMessage()."</p>\n";
  }//end catch
}

// edit existing request
if(isset($_GET["edit"])){
  try {
    $dbConn = getConnection();

    if(isset($_POST["edit"])){
      $eName = $_POST["eName"];
      $eDesc = $_POST["eDesc"];
      $eImg = $_POST["eImg"];
      //check if any field is empty 
      if(empty($eName) || empty($eDesc) || empty($eImg)) {
        $message = 'All fields are required';
      } else {
        $requestID = $_GET["edit"];
        //Sanitize and modify request in db
        editRequest($eName, $eDesc, $eImg, $requestID);
        header("location:adminDashboard.php");
      }
    }
  }//end try
  catch (PDOException $error){
    $message = $error->getMessage()."</p>\n";
  }//end catch
}

//register functionality

$registerMessage = "";//error messages

try {
  $dbConn = getConnection();
  
  if(isset($_POST["register"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $fName = $_POST["fName"];
    $lName = $_POST["lName"];
    $location = $_POST["location"];
    $admin = $_POST["admin"];
    //check if any field is empty 
    if(empty($email) || empty($password) || empty($fName) || empty($lName) || empty($location) || empty($admin)) {
      $registerMessage = 'All fields are required';
    } else {
      addNewStaffUser($email, $password, $fName, $lName, $location, $admin);
    }
  }
}//end try
catch (PDOException $error){
  $registerMessage = $error->getMessage()."</p>\n";
}//end catch

//Check if success
if(isset($_GET["success"])){
  $registerSuccess = "Success, Account added.";
  echo "<script type='text/javascript'>alert('$registerSuccess');</script>";
}

$locationMessage = "";//error messages

try {
  $dbConn = getConnection();
  
  if(isset($_POST["addLocation"])){
    $locationName = $_POST["locationName"];
    //check if any field is empty 
    if(empty($locationName)) {
      $locationMessage = 'All fields are required';
    } else {
      addNewLocation($locationName);
    }
  }
}//end try
catch (PDOException $error){
  $locationMessage = $error->getMessage()."</p>\n";
}//end catch

//Check if success
if(isset($_GET["locationAdded"])){
  $locationSuccess = "Success, Location added.";
  echo "<script type='text/javascript'>alert('$locationSuccess');</script>";
}

?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Nightingale Dash</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95, user-scalable=no">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="css/stylish.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#accordion" ).accordion({
      heightStyle: "content"
    });
  } );
  </script>
  <script>
  //colour inputs red that are empty
  $(document).ready(function(){
    $('input').blur(function(){
      if(!$(this).val()){
        $(this).addClass("error");
      } else{
        $(this).removeClass("error");
      }
    });
  });
  </script>
</head>

<body>
  <header class="wrap">
    <a class="backBtn" href="staffAnalytics.php">
      <img src="images/arrowBack.png"/>
      <p>back </p>
    </a>
    <h2>Admin Dashboard</h2> 
  </header>
  <main class= "wrap">
    <div id="accordion">
      <h3>Add and edit requests.</h3>
      <div>
        <p>Add and edit requests.</p>
        <?php 
          include_once('resources/functions.php');
          getRequestsTable($_SESSION["locationID"]);

          // adding new request
          if(isset($_GET["add"])){
            echo '
              <br>
              <p>Fill in the form below to add a new request.</p>
              <form class="adminForm" method="post">
                <input name="rName" id="name" type="text" placeholder="Request name">
                <textarea name="rDesc" id="name" placeholder="Request Description"></textarea>
                <input name="rImg" id="name" type="text" placeholder="icon URL (optional)">';
            if(isset($message)){
              echo '<p>'.$message.'</p>';
            }
            echo '
                <input type="submit" class="submit" name="add" value="Add request">
              </form>
            ';
          }
          //Editing an existing request
          if(isset($_GET["edit"])){
            $requestID = $_GET["edit"];
            $dbConn = getConnection();

            $sql = "
              SELECT *
              FROM nightingale_request
              WHERE requestID = :requestID
            ";
            $queryResult = $dbConn->prepare($sql);
            $queryResult->execute(array(
              'requestID' => $requestID,
            ));

            while ($rowObj = $queryResult->fetch(PDO::FETCH_ASSOC)){
              echo "
              <br>
              <p>Edit '{$rowObj['requestName']}' request below.</p>
              <form class='adminForm' method='post'>
                <input value='{$rowObj['requestName']}' name='eName' id='name' type='text' placeholder='Request name'>
                <textarea name='eDesc' id='name' placeholder='Request Description'>{$rowObj['Description']}</textarea>
                <input value='{$rowObj['imgUrl']}' name='eImg' id='name' type='text' placeholder='icon URL'>";
            if(isset($message)){
              echo '<p>'.$message.'</p>';
            }
            echo '
                <input type="submit" class="submit" name="edit" value="Save">
              </form>
            ';
            }//End while
          }//End if
        ?>
      </div>
      <h3>Create an Account</h3>
      <div>
        <form id="registerForm" class="adminAddUser" method="post">
          <h2>Create an Account</h2>
          <span class="accentColor"><p>Please enter account information.</p></span>
          <input form="registerForm" required type="email" name="email" id="name" placeholder="Email" tabindex="1"/>
          <input form="registerForm" required type="password" name="password" id="name" placeholder="Password" tabindex="2"/>
          <input form="registerForm" required type="text" name="fName" id="name" placeholder="First Name" tabindex="3"/>
          <input form="registerForm" required type="text" name="lName" id="name" placeholder="Surname" tabindex="4"/>
          <select name="admin">
            <option value="empty">Select Account Type</option>
            <option value="2">Staff User</option>
            <option value="1">Admin</option>
          </select>
          <select name="location">
            <option value="empty">Select your location</option>
            <?php 
              include_once('functions.php');
              getLocationOptions();
            ?>
          </select>
          <?php 
          if(isset($registerMessage)){
            echo '<p>'.$registerMessage.'</p>';
          }
          ?>
          <!-- submit button -->
          <input type="submit" class="submit" name="register" value="Add Account"/>
        </form>  
      </div>
      <h3>Add a new location</h3>
      <div>
        <p>Add a new hospital or care home here.</p>
        <form id="locationForm" class="adminAddUser" method="post">
          <input form="locationForm" required type="text" name="locationName" id="name" placeholder="Location Name" tabindex="5"/>
          <?php 
          if(isset($locationMessage)){
            echo '<p>'.$locationMessage.'</p>';
          }
          ?>
          <!-- submit button -->
          <input type="submit" class="submit" name="addLocation" value="Add Location"/>
        </form>
      </div>
    </div>
    
    
    
    
    
  </main>
</body>
</html>