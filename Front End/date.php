<?php

$nowTime = time();
$fDate = date('d/m/y g:i', $nowTime);   
$fTime = date('g:i', $nowTime);  
//forrmatting for chart
$fTimeH = date('g', $nowTime);  
$fTimeM = date('i', $nowTime);  


echo '<p>Timestamp: '.time().'</p>';
echo '<p>Time: '.$fTime.'</p>';
echo '<p>Date: '.$fDate.'</p>';
echo '<br>';
echo '<p>Hours: '.$fTimeH.'</p>';
echo '<p>Mins: '.$fTimeM.'</p>';

echo "<hr>";

require_once('functions.php');
$userID = '1'; // replace me !!!
$todayDate = date('d/m/y', time());

try{
  $dbConn = getConnection();
  //Query to retrieve requests for location
  $sql = "
    SELECT *
    FROM nightingale_glucose
    WHERE userID = '$userID'
    AND readingDate = '$todayDate';
  ";
  $queryResult = $dbConn->query($sql);
  while ($rowObj = $queryResult->fetchObject()){
    $fTimeH = date('g', $rowObj->readingTime);
    $fTimeM = date('i', $rowObj->readingTime);
    
    echo "<p>{$rowObj->readingDate}</p>";
    echo "<p>$fTimeH : $fTimeM</p>";
    echo "<p>{$rowObj->readingData}</p>";
  }//end while
}//end try
catch (Exception $e){
  echo "<p>Query failed: ".$e->getMessage()."</p>\n";
}//end catch

?>