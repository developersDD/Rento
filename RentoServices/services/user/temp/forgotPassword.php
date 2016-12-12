<?php

include_once('commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if($myPostData->forgotPassword){
        setOtp($myPostData->forgotPassword);
    }
}
mysql_close($connection);

function setOtp($data){
    if(isset($data->username)){
        $query = "select * from user where (contact = '$data->username' OR email ='$data->username')";
        $result = mysql_query($query);
        $resultusername = mysql_fetch_assoc($result);
        if($resultusername){
            $userId = $resultusername['id'];
            $otp = generateOtp();
            $queryOtp = "update user set otp ='$otp' where (contact = '$data->username' OR email ='$data->username')";
            $resultOtp = mysql_query($queryOtp);
            if($resultOtp){
                $response = array("status" => "1","userId" => $userId,"msg" => "OTP sent successfully");
            }else{
                $response = array("status" => "0","msg" => "Invalid username");
            }
        }else{
            $response = array("status" => '0',"msg" => "Username does not exist");
        }

    }else{
        $response = array("status" => "0","msg" => "Invalid data");
    }
    echo json_encode($response);
}