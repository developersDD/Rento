<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['email'])) {
        getEmail($_GET['email']);
    }
}
mysql_close($connection);

function getEmail($email){
    $emailArray = array();
    $query = "select * from user where email = '$email'";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result)){
        array_push($emailArray,$row);
    }
    if(!empty($emailArray)){
        $response = array("status" => "0", "msg" => "Email already registered");
        header('HTTP/1.1 200 Authorized',true,200);
        echo json_encode($response);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        header('HTTP/1.1 401 Unauthorized',true,401);
        echo json_encode($response);
    }
}