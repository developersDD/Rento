<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['countryId'])) {
        getStateByCountryId($_GET['countryId']);
    }
}
mysql_close($connection);

function getStateByCountryId($countryId){
    $query = "select id,statename from states where country_id = '$countryId'";
    $result = mysql_query($query);
    $stateArray = array();
    while($row = mysql_fetch_assoc($result)){
        array_push($stateArray,$row);
    }
    if(!empty($stateArray)){
        echo json_encode($stateArray);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        echo json_encode($response);
    }
}