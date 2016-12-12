<?php

include_once('../configure.php');

if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(isset($_GET['userId'])){
        deleteUsers($_GET['userId']);
    }
}
mysql_close($connection);

function deleteUsers($id){
    $query = "select * from `rento_trial`.`user` where id='$id'";
    $result = mysql_query($query);
    if(mysql_fetch_object($result)){
        $query = "DELETE FROM `rento_trial`.`user` WHERE id='$id'";
        $result = mysql_query($query)or die("Query failed : " . mysql_error());
        if($result){
            $response = array("status" => "1","msg" => "Success");
        }else{
            $response = array("status" => "0","msg" => "Failed");
        }
        echo json_encode($response);
    }else{
        echo  json_encode("ERROR! No Such User Exists");
    }
}