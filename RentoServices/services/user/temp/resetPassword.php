<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if($myPostData->resetPassword){
        resetPassword($myPostData->resetPassword);
    }
}
mysql_close($connection);

function resetPassword($data){
    if(isset($data->password) && isset($data->userId) && isset($data->confirmPassword)){
        if($data->password == $data->confirmPassword){
            $hashpwd = passwordHashing($data->password);
            $query = "update `rento_trial`.`user` set password='$hashpwd' where (id = '$data->userId')";
            $result = mysql_query($query);
            if($result){
                $response = array("status" => "1","msg" => "Password updated successfully");
            }else{
                $response = array("status" => "0","msg" => "Failed");
            }
        }else{
            $response = array("status" => "0","msg" => "Password and confirm password fields must be same");
        }
    }else{
        $response = array("status" => "0","msg" => "Password cannot not be empty");
    }
    echo json_encode($response);
}