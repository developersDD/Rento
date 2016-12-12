<?php

include_once('commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if($myPostData->sendOtp){
        sendOtpToUser($myPostData->sendOtp);
    }
}
mysql_close($connection);

function sendOtpToUser($data){
    if(isset($data->username)){
        if($data->username){
            $query = "select * from user where (contact = '$data->username' OR email ='$data->username')";
            $result = mysql_query($query);
            $userData = mysql_fetch_assoc($result);
            if(mysql_affected_rows() > 0){
                $otp = generateOtp();
                sendOtp($userData['contact'], $otp);
                $response = array("status" => "1","msg" => "OTP sent successfully");
            }else{
                $response = array("status" => '0',"msg" => "Invalid username");
            }
        }else {
            $response = array("status" => "1","msg" => "OTP cannot be empty");
        }
    }else{
        $response = array("status" => "0","msg" => "Invalid input");
    }
    echo json_encode($response);
}