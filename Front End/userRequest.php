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
  <div class="fadein">
    <img id="f1" src="https://i.imgur.com/NYvomFL.png">
    <img id="f2" src="https://i.imgur.com/vwul8S6.png">
    <img id="f3" src="https://i.imgur.com/vNE0XKw.png">
    <img id="f4" src="https://i.imgur.com/9bERgX4.png">
  </div>
  <h2>A nurse will be with you shortly.</h2> 
  <p>You can select more information to help us understand your problem.
</header>
<main class= "userDash wrap">
  
  <div class="informationWrap">
    <section class="request information">
      <img src="https://i.imgur.com/KaAmcUG.png"/>
      <h2>Staff Query</h2>
    </section>
    <section class="request information">
      <img src="https://i.imgur.com/4O09uAm.png"/>
      <h2>Pain</h2>
    </section>
  </div>
  
  <div class="informationWrap">
    <section class="request information">
      <img src="https://i.imgur.com/vE0qCeF.png"/>
      <h2>Dizziness</h2>
    </section>
    <section class="request information">
      <img src="https://i.imgur.com/KvmEEn8.png"/>
      <h2>Heart</h2>
    </section>
  </div>
  
  <section class="request cancel">
    <a href="deleteRequest.php" onclick="return confirm('Are you sure you want to remove your request?');">
      <h2>Cancel Request</h2>
    </a>
  </section>
  
</main>
    
</body>
</html>