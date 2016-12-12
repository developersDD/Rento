<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "GET") {
        getCountries();
}
mysql_close($connection);

function getCountries(){
    $countryArray = array();
    $query = "select id,countryname from countries";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result)){
        array_push($countryArray,$row);
    }
    if(!empty($countryArray)){
        echo json_encode($countryArray);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        echo json_encode($response);
    }
}