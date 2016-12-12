<?php

include_once('commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->register)){
        $otp = generateOtp();


        $success = addUsers($myPostData->register,$otp);
        if($success){
            echo json_encode($success);
        }
        sendOtp($myPostData->register->contact, $otp);
    }
}
mysql_close($connection);

function addUsers($user, $otp){
    $hashpwd = passwordHashing($user->password);
    $query = "INSERT INTO `user`  (`id`, `name`, `email`, `contact`, `password`, `otp`) VALUES (NULL, '$user->name', '$user->email' ,'$user->contact', '$hashpwd','$otp');";
    $result = mysql_query($query);
    if(mysql_affected_rows() > 0){
        $response = array("status" => "1","userId" => mysql_insert_id(),"msg" => "Please verify your account. OTP is sent to your registered mobile number");
    }else{
        $response = array("status" => "0","msg" => "Failed");
    }
    return $response;
}