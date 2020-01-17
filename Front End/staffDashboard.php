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

<body>
  <header class="wrap">
      <nav>
        <a href="staffDashboard.html"><p>Activity</p></a>
        <img class="fallIn" src="https://i.imgur.com/jTlkSyj.png"/>
        <a href="staffAnalytics.html"><p>Analytics</p></a>
      </nav>
      <h2>Hello Jake,</h2> 
      <p>All current activity is shown here.
    </header>
    <main class= "wrap">
    <?php
      require_once('functions.php');
      
      // replace with sessions
      $acceptedBy = "Staff Test Name";
      getAcceptedTasks($acceptedBy);
      getTasks();
    ?>

    </main>
</body>
</html>