<?php

include_once('../configure.php');
include_once('../commonServices.php');
$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "GET") {
if (isset($_GET['categoryId'])) {
        getSubCategoryByCategoryId($_GET['categoryId']);
    }
}
mysql_close($connection);

function getSubCategoryByCategoryId($categoryId){
    $query = "select * from sub_categories where category_id = '$categoryId'";
    $result = mysql_query($query);
    $categoryArray = array();
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