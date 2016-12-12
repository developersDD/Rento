<?php

include_once('../configure.php');
include_once('../commonServices.php');
$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->category)){
        addCategory($myPostData->category);
    }
}
mysql_close($connection);

function addCategory($category){
    $query = "INSERT INTO `categories`  (`id`, `name`) VALUES (NULL, '$category->name');";
    $result = mysql_query($query);
    if(mysql_affected_rows()> 0){
        $response = array("status" => "1","msg" => "Success");
    }else{
        $response = array("status" => "0","msg" => "Failed");
    }
    echo json_encode($response);
}