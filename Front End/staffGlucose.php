<?php 
require_once('resources/functions.php');
session_start();
if(!isset($_SESSION["staffID"])){
  header("location:staffLogin.php");
  die();
}
$userID = 0;
if(isset($_GET["userID"])){
  $userID = $_GET["userID"];
}

// check date to be displayed on chart
if(isset($_GET["t"])){
  $queryDate = date('d/m/y', $_GET["t"]);
  $time = $_GET["t"];
} else {
  $queryDate = date('d/m/y', time());
  $time = time();
}
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Glucose</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95">
  <link rel="stylesheet" href="css/stylish.css">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    var chartwidth = $('#chartWrap').width();
    function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('datetime', 'Time of Day');
      data.addColumn('number', 'Motivation Level');
      data.addRows([
        <?php 
          // Add data to Google charts
          getGlucoseData($queryDate, $userID);
        ?>
      ]);
      
      var options = {
        width: chartwidth,
        backgroundColor: { fill:'transparent' },
        legend: {position: 'none'},
        enableInteractivity: false,
        legend: 'none',
        colors:['white'],
        pointSize: 5,
        chartArea: {
          width: chartwidth,
          left: 40,
          top:20,
          height:150
        },
        hAxis: {
          textStyle:{color: '#FFF'},
          gridlines: {
            count: -1,
            units: {
              days: {format: ['MMM dd']},
              hours: {format: ['HH:mm', 'ha']},
            }
          },
          minorGridlines: {
            units: {
              hours: {format: ['hh:mm:ss a', 'ha']},
              minutes: {format: ['HH:mm a Z', ':mm']}
            }
          }
        }
      };

      var chart = new google.visualization.LineChart(
      document.getElementById('chart_div'));

      chart.draw(data, options);
    }
    
    
    // search for a users glucose data  
    $(document).ready(function(){
        var users = [
          <?php 
            $locationID = $_SESSION["locationID"];
            echo autocompleteUser($locationID);
          ?>
        ];

        $( "#user" ).autocomplete({
          minLength: 0,
          source: users,
          focus: function( event, ui ) {
            $( "#user" ).val( ui.item.label );
            return false;
          },
          select: function( event, ui ) {
            $( "#users" ).val( ui.item.label );
            $( "#userID" ).val( ui.item.value );
            return false;
          }
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<div>" + item.label + "</div>" )
            .appendTo( ul );
        };
    });
    
  </script>
</head>

<body>
  <header class="wrap">
    <a class="backBtn" href="staffDashboard.php">
      <img src="images/arrowBack.png"/>
      <p>back </p>
    </a>
    <h2>Patient Glucose Log</h2> 
    <p>Search for a user by name. </p>
    
    
    <div class="ui-widget">
      <form class="glucoseForm" method="get">
        <input value="<?php if(isset($_GET["user"])){echo $_GET["user"];} ?>" class="search" autocomplete="off" placeholder="Patient name" type="text" id="user" name="user">
        <input hidden placeholder="UserID" type="text" id="userID" name="userID">
        <input class="submit" value="Search" type="submit">
      </form>
    </div>
    
    <!-- chart controls -->
    <div class="splitCol chartControls scaleIn">
      <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&t=<?php echo $time - 86400; ?>"><p>&#60;</p></a>
      <p>
      <?php 
        //current date viewing
        if($queryDate == date('d/m/y', time())){
          echo '<p>Today </p>';  
        } else {
           echo '<p>'.$queryDate.'</p>';  
        }
      ?>
      </p>
      <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&t=<?php echo $time + 86400; ?>"><p>&#62;</p></a>
    </div>
    <!-- Glucose level chart -->
    <div id="chartWrap">
      <div class="scaleIn" id="chart_div"></div>
    </div>
    <br/>
   
    
    <?php 
      if(isset($_GET["userID"])){
        echo '
          <p>Log patient glucose.<p>
          <form class="splitCol glucoseForm" action="resources/logGlucose.php" method="post">
            <input placeholder="mg/dL" name="data" id="glucose" type="number" min="50" max="400">
            <input class="submit" type="submit">
          </form>
          <br/>
        ';
        //get glucose table data for date viewing
        getGlucoseDataTable($queryDate, $userID);
      }
    ?>
  </header>
  <main class= "wrap">


  </main>
</body>
</html>