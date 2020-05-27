<?php 
session_start();
require_once('functions.php');
$dbConn = getConnection();

if(isset($_SESSION["userID"])){
  $userID = htmlspecialchars($_SESSION["userID"]);
  
  //dark mode
  if(isset($_GET["dark"])){
    $darkMode = htmlspecialchars($_GET["dark"]);
    $sql = "UPDATE nightingale_user SET darkMode = :darkMode WHERE userID = :userID;";
    $queryResult = $dbConn->prepare($sql);
    $queryResult->execute(array(
       'userID' => $userID,
       'darkMode' => $darkMode,
    ));
    header("location:../settings.php");
  }
  
  //dark mode
  if(isset($_GET["text"])){
    $darkMode = htmlspecialchars($_GET["text"]);
    $sql = "UPDATE nightingale_user SET textMode = :darkMode WHERE userID = :userID;";
    $queryResult = $dbConn->prepare($sql);
    $queryResult->execute(array(
       'userID' => $userID,
       'darkMode' => $darkMode,
    ));
    header("location:../settings.php");
  }
  
} else {
  header("location:../login.php");
}


?>