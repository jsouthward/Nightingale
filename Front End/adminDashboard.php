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
      if(empty($rName) || empty($rDesc) || empty($rImg)) {
        $message = 'All fields are required';
      } else {
        //Sanitize and add to db
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

?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Nightingale Dash</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95">
  <link rel="stylesheet" href="css/stylish.css">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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
            <input name="rImg" id="name" type="text" placeholder="icon URL">';
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
          WHERE requestID = '$requestID'
        ";
        $queryResult = $dbConn->query($sql);
        
        while ($rowObj = $queryResult->fetchObject()){
          echo "
          <br>
          <p>Edit '{$rowObj->requestName}' request below.</p>
          <form class='adminForm' method='post'>
            <input value='{$rowObj->requestName}' name='eName' id='name' type='text' placeholder='Request name'>
            <textarea name='eDesc' id='name' placeholder='Request Description'>{$rowObj->Description}</textarea>
            <input value='{$rowObj->imgUrl}' name='eImg' id='name' type='text' placeholder='icon URL'>";
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
  </main>
</body>
</html>