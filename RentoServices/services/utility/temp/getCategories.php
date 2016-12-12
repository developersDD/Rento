<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "GET") {
        getAllCategory();
}
mysql_close($connection);

function getAllCategory(){
    $categoryArray = array();
    $query = "select * from categories";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result)){
        array_push($categoryArray,$row);
    }
    if(!empty($categoryArray)){
        echo json_encode($categoryArray);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        echo json_encode($response);
    }
}