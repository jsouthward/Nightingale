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
<body class="fallIn">
  
  <header class="wrap">
  <div class="fadein">
    <img id="f1" src="images/bird1.png">
    <img id="f2" src="images/bird2.png">
    <img id="f3" src="images/bird3.png">
    <img id="f4" src="images/bird4.png">
  </div>
  <h2>A nurse will be with you shortly.</h2> 
  <p>You can select more information to help us understand your problem.
</header>
<main class= "userDash wrap waiting">
  <div class="informationWrap">
  <a class="requestLink" href="glucose.php">
    <section class="splitCol dashBtnGreen">
      <img src="images/request5.png"/>
      <p>Dizzyness or Nausea</p>
    </section>
  </a>
  <br>
  <a class="requestLink" href="glucose.php">
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