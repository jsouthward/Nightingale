<?php 
session_start();

$message = "";//error messages

try {
  require_once('resources/functions.php');
  $dbConn = getConnection();
  
  if(isset($_POST["register"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $fName = $_POST["fName"];
    $lName = $_POST["lName"];
    $location = $_POST["location"];
    $roomNo = $_POST["roomNo"];
    //check if any field is empty 
    if(empty($email) || empty($password) || empty($fName) || empty($lName) || empty($location) || empty($roomNo)) {
      $message = 'All fields are required';
    } else {
      addNewUser($email, $password, $fName, $lName, $location, $roomNo);
    }
  }
}//end try
catch (PDOException $error){
  $message = $error->getMessage()."</p>\n";
}//end catch

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
  <style>
    body {
      background-color: #0062ff;
    }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $(function() {
    $("#tabs").tabs();
    $(".nexttab").click(function() {
      var active = $( "#tabs" ).tabs( "option", "active" );
      $( "#tabs" ).tabs( "option", "active", active + 1 );
    });
  });
  //preload images
  function preload(arrayOfImages) {
    $(arrayOfImages).each(function(){
      $('<images/>')[0].src = this;
    });
  }
  preload([
    'images/register1.png',
    'images/register1a.png',
    'images/register2.png',
    'images/register2a.png',
    'images/register3.png',
    'images/register3a.png'
  ]);  
  
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
  
  <main class="wrap">
    
    <hr class="dottedLine">
    <div id="tabs">
      <ul>
        <li><a href="#tabs-1"><p>Account</p></a></li>
        <li><a href="#tabs-2"><p>Personal</p></a></li>
        <li><a href="#tabs-3"><p>location</p></a></li>
      </ul>
      <div id="tabs-1" class="ui-tabs-panel">
        <div class="tabsImg">
          <span class="scaleIn"><img src="images/register1a.png"></span>
          <span><img src="images/register2.png"></span>
          <span><img src="images/register3.png"></span>
        </div>
        <!-- Login Form -->
        <div class="formWrap scaleIn">
          <form>
            <h2>Create Account</h2>
            <span class="accentColor"><p>Please enter your email and a password for your account.</p></span>
            <div class="formWrap">
              <input form="registerForm" required type="email" name="email" id="name" placeholder="Email" tabindex="1"/>
              <input form="registerForm" required type="password" name="password" id="name" placeholder="Password" tabindex="2"/>
            </div>
            <!-- next button -->
            <a class="nexttab" href="#"><input class="submit" value="Next"/></a>
          </form>  
        </div>
        <!-- Login Form END -->
      </div>
      <div id="tabs-2" class="ui-tabs-panel">
        <div class="tabsImg">
          <span><img src="images/register1.png"></span>
          <span class="scaleIn"><img src="images/register2a.png"></span>
          <span><img src="images/register3.png"></span>
        </div>
        <!-- Login Form -->
        <div class="formWrap scaleIn">
          <form>
            <h2>Personal Info</h2>
            <span class="accentColor"><p>We do not share this information with third parties.</p></span>
            <div class="formWrap">
              <input form="registerForm" required type="text" name="fName" id="name" placeholder="First Name" tabindex="1"/>
              <input form="registerForm" required type="text" name="lName" id="name" placeholder="Surname" tabindex="1"/>
            </div>
            <!-- next button -->
            <a class="nexttab" href="#"><input class="submit" value="Next"/></a>
          </form>  
        </div>
        <!-- Login Form END -->
      </div>
      <div id="tabs-3" class="ui-tabs-panel">
        <div class="tabsImg">
          <span><img src="images/register1.png"></span>
          <span><img src="images/register2.png"></span>
          <span class="scaleIn"><img src="images/register3a.png"></span>
        </div>
        <!-- Login Form -->
        <div class="formWrap scaleIn">
          <form id="registerForm" method="post">
            <h2><img src="images/map.png">Location</h2>
            <span class="accentColor"><p>Let us know where you are so that you can get help if you need it. </p></span>
            <div class="formWrap">
              <select name="location">
                <option value="empty">Select your location</option>
                <?php 
                  include_once('functions.php');
                  getLocationOptions();
                ?>
              </select>
              <input required type="number" name="roomNo" id="name" placeholder="Room number" tabindex="2"/>
            </div>
            <?php 
            if(isset($message)){
              echo '<p>'.$message.'</p>';
            }
            ?>
            <!-- submit button -->
            <input type="submit" class="submit" name="register" value="Go to Dashbaord"/>
          </form>  
        </div>
        <!-- Login Form END -->
      </div>
    </div>
    
    <div class="splitCol loginLinks">
      <a href="login.php"><p>Allready have an account</p></a>
    </div>
  </main>
    
</body>
</html>