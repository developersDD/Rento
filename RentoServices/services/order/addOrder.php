<?php

include_once('../configure.php');
include_once('../commonServices.php');

$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->orderDetails)){
        $orderData = $myPostData->orderDetails;
        $orderDate = convertDateFormat($orderData->order_date);
        $toDate = convertDateFormat($orderData->to_date);
        $fromDate = convertDateFormat($orderData->from_date);
        addOrder($orderData, $orderDate, $toDate, $fromDate);
    }
}

mysql_close($connection);

function addOrder($orderData, $orderDate, $toDate, $fromDate){
    $query = "INSERT INTO `rento_trial`.`order_details` (`id`, `product_id`, `owner_id`, `buyer_id`, `order_date`, `to_date`, `from_date`, `amount`) VALUES (NULL, '$orderData->product_id','$orderData->owner_id', '$orderData->buyer_id','$orderDate','$toDate','$fromDate','$orderData->amount');";
    $result = mysql_query($query);
    if($result){
        $response = array("status" => "1","msg" => "Success");
    }else{
        $response = array("status" => "0","msg" => "Failed");
    }
    echo json_encode($response);
}