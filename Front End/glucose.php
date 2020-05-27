<?php 
require_once('resources/functions.php');
session_start();
if(!isset($_SESSION["userID"])){
  header("location:login.php");
  die();
}
$userID = $_SESSION["userID"];
// check date to be displayed on chart
if(isset($_GET["t"])){
  $queryDate = date('d/m/y', $_GET["t"]);
  $time = $_GET["t"];
} else {
  $queryDate = date('d/m/y', time());
  $time = time();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>log glucose</title>
  <meta name="description" content="Dashboard">
  <meta name="author" content="W15024065">
  <meta name="viewport" content="width=device-width, initial-scale=0.95, user-scalable=no">
  <link rel="stylesheet" href="css/stylish.css">
  <link href="https://fonts.googleapis.com/css?family=Calistoga|Montserrat:400,700&display=swap" rel="stylesheet">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script>
    function getAudio(){
      var txt=jQuery('#txt').val()
      jQuery.ajax({
        url:'resources/getAudio.php',
        type:'post',
        data:'txt='+txt,
        success:function(result){
          jQuery('#player').html(result);
        }
      });
    }
  </script>
  <script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    var chartwidth = $('#chartWrap').width();
    function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('datetime', 'Time of Day');
      data.addColumn('number', 'Glucose Level');
      data.addRows([
        <?php 
          // Add data to Google chart
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
  </script>
  <?php darkModeStyle(); textModeStyle(); ?>
</head>

<body>
  <header class="wrap">
    <a class="backBtn" href="dashboard.php">
      <img src="images/arrowBack.png"/>
      <p>back </p>
    </a>
    <h2>Glucose Log Book</h2> 
    <p>View and log your glucose levels.</p>
    <div id="player"></div>
    <form class="infoForm" method="post">
      <input hidden type="textbox" value="if you are Diabetic you can Log your Glucose levels below" id="txt" name="txt"/>
      <input class="infoBtn" type="button" name="txt" onclick="getAudio()"/>
      Audio Help
    </form>
    <!-- chart controls -->
    <div class="splitCol chartControls scaleIn">
      <a href="glucose.php?t=<?php echo $time - 86400; ?>"><p>&#60;</p></a>
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
      <a href="glucose.php?t=<?php echo $time + 86400; ?>"><p>&#62;</p></a>
    </div>
    <!-- Glucose level chart -->
    <div id="chartWrap">
      <div class="scaleIn" id="chart_div"></div>
    </div>
    <br/>
    <p>Log your glucose.<p>
    <form class="splitCol glucoseForm" action="resources/logGlucose.php" method="post">
      <input placeholder="mg/dL" name="data" id="glucose" type="number" min="50" max="400">
      <input class="submit" type="submit">
    </form>
    <br/>
    <?php 
      //get glucose table data for date viewing
      getGlucoseDataTable($queryDate, $userID);
    ?>
  </header>
  <main class= "wrap">


  </main>
</body>
</html>