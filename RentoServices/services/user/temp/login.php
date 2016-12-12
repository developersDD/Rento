<?php

include_once('commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if($myPostData->login){
        loginUser($myPostData->login);
    }
}
mysql_close($connection);

function loginUser($loginData){
    if(isset($loginData->password) && isset($loginData->username)){
        $query = "select * from rento_trial.user where (contact = '$loginData->username' OR email ='$loginData->username')";
        $result = mysql_query($query);
        $data = mysql_fetch_assoc($result);
        if($data){
            if($data['active'] != 0){
                $userId = $data['id'];
                $name = $data['name'];
                if (password_verify($loginData->password, $data['password'])) {
                    $response = array("status" => '1',"msg" => "Success","userId" =>$userId,"name" =>$name );

                }else{
                    $response = array("status" => '0',"msg" => "Invalid username or password");
                }
            }else{
                $response = array("status" => '0',"msg" => "You are not an active user");
            }
        }else{
            $response = array("status" => '0',"msg" => "Invalid user");
        }
    }else{
        $response = array("status" => "0","msg" => "Invalid data");
    }


    echo json_encode($response);
}