<?php 
session_start();

$message = "";//error messages

try {
  require_once('resources/functions.php');
  $dbConn = getConnection();
  
  if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordHash = md5($password); 
    //if fields are empty
    if(empty($email) || empty($password)) {
      $message = '<label>All fields are required</label>';
    } else {
      $query = "SELECT * FROM nightingale_staffUser WHERE email = :email AND password = :password";
      $statement = $dbConn->prepare($query);
      $statement->execute(
        array(
          'email' => $_POST["email"],
          'password' => $passwordHash
        )
      );
      $count = $statement->rowCount();
      //if db result - password email match
      if ($count > 0){
        
        //get session data from db
        $sessionQuery = "SELECT * FROM nightingale_staffUser WHERE email = '$email' AND password = '$passwordHash'";
        $sessionData = $dbConn->query($sessionQuery);
        while($rowObj = $sessionData->fetchObject()){  
          
          $_SESSION['staffID'] = $rowObj->staffID;
          $_SESSION['firstNameStaff'] = $rowObj->firstNameStaff;
          $_SESSION['locationID'] = $rowObj->locationID;
          $_SESSION['admin'] = $rowObj->admin;
        }
        
        header("location:staffDashboard.php");
      } else {
        $message = '<label>Password or username incorrect</label>';
      }
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
  <title>Staff Login</title>
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
</head>

<body>
  
  <main class="wrap">
    
    <h1 class="fallIn"><img class="birdLogo" src="images/whitebird.png"/>Nightingale Staff</h1>
    
    <div class="formWrap scaleIn">
      <!-- Login Form -->
      <form id="LoginForm" method="post">
        <h2>Sign in</h2>
        <span class="accentColor"><p>Please enter your email and password</p></span>
        <div class="formWrap">
          <input type="email" name="email" id="name" placeholder="Email" tabindex="1"/>
          <input type="password" name="password" id="name" placeholder="Password" tabindex="2"/>  
        </div>
        <?php 
        if(isset($message)){
          echo '<p>'.$message.'</p>';
        }
        ?>
        <input class="submit" type="submit" name="login" value="Next"/>      
      </form>
      
    </div>
    <div class="splitCol loginLinks">
      <a href="login.php"><p>User login</p></a>
    </div>
  </main>
    
</body>
</html>