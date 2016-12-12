<?php

include_once('../configure.php');
include_once('../commonServices.php');

$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->orderDetails->id)){
        $orderData = $myPostData->orderDetails;
        $orderDate = convertDateFormat($orderData->order_date);
        $toDate = convertDateFormat($orderData->to_date);
        $fromDate = convertDateFormat($orderData->from_date);
        updateOrder($orderData, $orderDate, $toDate, $fromDate);
    }
}
mysql_close($connection);

function updateOrder($orderData, $orderDate, $toDate, $fromDate){
    $query = "select * from `rento_trial`.`order_details` where id='$orderData->id'";
    $result = mysql_query($query);
    $queryData = mysql_fetch_object($result);
    if($queryData){
        $query = "update `rento_trial`.`order_details` set product_id='$orderData->product_id',owner_id='$orderData->owner_id',buyer_id ='$orderData->buyer_id',order_date='$orderDate',to_date = '$toDate',from_date = '$fromDate',amount = '$orderData->amount' where id='$orderData->id'";
        $result = mysql_query($query);
        if($result){
            $response = array("status" => "1","msg" => "Success");
        }else{
            $response = array("status" => "0","msg" => "Failed");
        }
        echo json_encode($response);
    }
}