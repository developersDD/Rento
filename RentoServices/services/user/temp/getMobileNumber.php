<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['mobile'])) {
        getMobile($_GET['mobile']);
    }
}
mysql_close($connection);

function getMobile($mobile){
    $mobileArray = array();
    $query = "select * from user where contact = '$mobile'";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result)){
        array_push($mobileArray,$row);
    }
    if(!empty($mobileArray)){
        $response = array("status" => "0", "msg" => "Mobile number already registered");
        header('HTTP/1.1 200 Authorized',true,200);
        echo json_encode($response);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        header('HTTP/1.1 401 Unauthorized',true,401);
        echo json_encode($response);
    }
}