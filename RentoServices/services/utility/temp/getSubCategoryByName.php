<?php

include_once('../configure.php');
include_once('../commonServices.php');
$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['categoryName'])) {
        getSubCategoryByName($_GET['categoryName']);
    }
}
mysql_close($connection);

function getSubCategoryByName($name){
    $dataArray = array();
    $query = "select s.name from sub_categories s where (s.name LIKE '%$name%');";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result)){
        array_push($dataArray,$row);
    }
    $query1 = "select c.name from categories c where (c.name LIKE '%$name%');";
    $result1 = mysql_query($query1);
    while($row = mysql_fetch_assoc($result1)){
        array_push($dataArray,$row);
    }
    $query2 = "select p.name from product p where (p.name LIKE '%$name%') OR (p.description LIKE '%$name%');";
    $result2 = mysql_query($query2);
    while($row = mysql_fetch_assoc($result2)){
        array_push($dataArray,$row);
    }
    if(!empty($dataArray)){
        echo json_encode($dataArray);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        echo json_encode($response);
    }
}