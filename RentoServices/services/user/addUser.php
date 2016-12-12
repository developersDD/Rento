<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->userDetails)){
        $otp = generateOtp();
        $success = addUsers($myPostData->userDetails,$otp);
        if($success){
            App_dump($success['success']);
        }
        sendOtp($myPostData->userDetails->contact, $otp);
    }
}
mysql_close($connection);

function addUsers($user, $otp){
    $query = "INSERT INTO `rento_trial`.`user`  (`id`, `name`, `email`, `contact`, `address_id`, `password`, `otp`) VALUES (NULL, '$user->name', '$user->email' ,'$user->contact', '$user->address_id','$user->password','$otp');";
    $result = mysql_query($query);
    if(mysql_affected_rows() > 0){
        $response = array("status" => "1","msg" => "Success");
    }else{
        $response = array("status" => "0","msg" => "Failed");
    }
    return json_encode($response);
}