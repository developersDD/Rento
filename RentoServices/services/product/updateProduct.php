<?php

include_once('../configure.php');

$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->productDetails->id)){
        updateProduct($myPostData->productDetails);
    }
}
mysql_close($connection);

function updateProduct($productData){
    $query = "select * from `rento_trial`.`product` where id='$productData->id'";
    $result = mysql_query($query);
    $queryData = mysql_fetch_object($result);
    if($queryData){
        $query = "update `rento_trial`.`product` set name='$productData->name',description='$productData->description',category='$productData->category',sub_category='$productData->sub_category',rate_per_day='$productData->rate_per_day',owner_id='$productData->owner_id' where id='$productData->id'";
        $result = mysql_query($query);
        if(mysql_affected_rows() > 0){
            $response = array("status" => "1","msg" => "Success");
        }else{
            $response = array("status" => "0","msg" => "Failed");
        }
        echo json_encode($response);
    }else{
        echo  json_encode("ERROR! No Such Product Exists");
    }
}