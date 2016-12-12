<?php

include_once('../configure.php');

$auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
$auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";

if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(isset($_GET['productId'])){
        deleteProduct($_GET['productId']);
    }
}
mysql_close($connection);

function deleteProduct($id){
    $query = "select * from `rento_trial`.`product` where id='$id'";
    $result = mysql_query($query);
    if (mysql_fetch_object($result)) {
        $query = "DELETE FROM `rento_trial`.`product` WHERE id='$id'";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        if($result){
            $response = array("status" => "1","msg" => "Success");
        }else{
            $response = array("status" => "0","msg" => "Failed");
        }
        echo json_encode($response);
    } else {
        echo  json_encode("ERROR! No Such Product Exists");
    }
}
