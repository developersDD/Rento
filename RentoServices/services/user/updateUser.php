<?php

include_once('../configure.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->userDetails->id)){
        updateUsers($myPostData->userDetails);
    }
}
mysql_close($connection);

function updateUsers($user){
    $query = "select * from `rento_trial`.`user` where id='$user->id'";
    $result = mysql_query($query);
    $queryData = mysql_fetch_object($result);
    if($queryData){
        $query = "update `rento_trial`.`user` set name='$user->name',address_id ='$user->address_id',email='$user->email',contact='$user->contact',password='$user->password' where id='$user->id'";
        $result = mysql_query($query);
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