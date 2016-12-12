<?php

include_once('commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if($myPostData->setPassword){
        setNewPassword($myPostData->setPassword);
    }
}
mysql_close($connection);

function setNewPassword($data){
    if(isset($data->password) && isset($data->userId)){
        $query = "select id from user where (id = '$data->userId')";
        $result = mysql_query($query);
        if(mysql_num_rows($result)){
            $hashpwd = passwordHashing($data->password);
            $queryPassword = "update `rento_trial`.`user` set password='$hashpwd' where (id = '$data->userId')";
            $resultPassword = mysql_query($queryPassword);
            if($resultPassword){
                $userId = $resultPassword['id'];
                $response = array("status" => "1","userId" => $userId,"msg" => "Password updated successfully");
            }else{
                $response = array("status" => "0","msg" => "Password could not be updated");
            }
        }else{
            $response = array("status" => "0","msg" => "Invalid username");
        }

    }else{
        $response = array("status" => "0","msg" => "Invalid data");
    }
    echo json_encode($response);
}