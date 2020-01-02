<?php
// Database connection
function getConnection() {
    try {
        $connection = new PDO('mysql:host=localhost;dbname=unn_w15024065',
            'unn_w15024065', 'Techfuture12');
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (Exception $e) {
        throw new Exception('Connection error '. $e->getMessage(), 0, $e);
    }
}

// Function Time elapsed
function getTimeElasped($start_time) {
    $since_start = time() - $start_time;
    $minutes = round( $since_start / 60); 
    if ($minutes < 2 ){
        $minutes = "1 min";
    } 
    elseif ($minutes > 60) {
        $minutes = round($minutes / 60);
        $minutes = "over ". $minutes ."h";
    }
    else {
        $minutes = $minutes . " mins";
    }
    return $minutes;
}

?>