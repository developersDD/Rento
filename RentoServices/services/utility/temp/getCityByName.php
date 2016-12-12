<?php

include_once('../configure.php');
include_once('../commonServices.php');
$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['cityName'])) {
        getCityByName($_GET['cityName']);
    }
}
mysql_close($connection);

function getCityByName($city){
    $query = "select c.cityname, s.statename from cities c left join states s on c.stateid = s.id where (c.cityname LIKE '$city%');";
    $result = mysql_query($query);
    $cityArray = array();App_dump($query);
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