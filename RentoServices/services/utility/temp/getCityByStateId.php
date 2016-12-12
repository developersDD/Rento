<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['stateId'])) {
        getCityByStateId($_GET['stateId']);
    }
}
mysql_close($connection);

function getCityByStateId($stateId){
    $query = "select id,cityname from cities where state_id = '$stateId'";
    $result = mysql_query($query);
    $cityArray = array();
    while($row = mysql_fetch_assoc($result)){
        array_push($cityArray,$row);
    }
    if(!empty($cityArray)){
        echo json_encode($cityArray);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        echo json_encode($response);
    }
}