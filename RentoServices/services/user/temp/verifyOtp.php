<?php

include_once('commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if($myPostData->verifyOtp){
        verifyOtp($myPostData->verifyOtp);
    }
}
mysql_close($connection);

function verifyOtp($data){
    if(isset($data->userId) && isset($data->otp)){
        $query = "select id,otp from user where (id = '$data->userId')";
        $result = mysql_query($query);
            if(mysql_num_rows($result)){
                while($userData = mysql_fetch_assoc($result)){
                    $userId = $userData['id'];
                    if($userData['otp'] == $data->otp){
                        $queryOtp = "update user set active = 1 where (id = '$userId')";
                        $resultOtp = mysql_query($queryOtp);
                        if($resultOtp){
                            $response = array("status" => "1","userId" =>$userId,"msg" => "OTP verified successfully");
                        }
                    }else{
                        $response = array("status" => "0","msg" => "Invalid OTP");
                    }
                }
            }else {
                $response = array("status" => "0","msg" => "Invalid user");
            }
        }else{
            $response = array("status" => "0","msg" => "OTP cannot be empty");
        }
    echo json_encode($response);
}