<?php

include_once('../configure.php');

if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(isset($_GET['orderId'])){
        deleteOrder($_GET['orderId']);
    }
}
mysql_close($connection);

function deleteOrder($id){
    $query = "DELETE FROM `rento_trial`.`order_details` WHERE id='$id'";
    $result = mysql_query($query);
    if(mysql_fetch_object($result)){
        if($result){
            $response = array("status" => "1","msg" => "Success");
        }else{
            $response = array("status" => "0","msg" => "Failed");
        }
        echo json_encode($response);
    }else{
        echo  json_encode("ERROR! No Such Order Exists");
    }
}