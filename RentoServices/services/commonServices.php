<?php
    include_once('configure.php');
//    $auth_user = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:" ";
//    $auth_pass = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:" ";
//    if (! pc_validate($auth_user, $auth_pass)){
//        header('WWW-Authenticate: Basic realm="My Website"');
//        header('HTTP/1.0 401 Unauthorized');
//        echo "You need to enter a valid username and password.";
//        exit;
//    }

//    require('php-uk/textlocal.class.php');

    function convertDateFormat($date){
        $str = strtotime($date);
        return date('Y-m-d',$str);
    }

    function App_dump($string){
        print_r($string);die;
    }

    function randomTokenGenerater(){
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }

    function SMS(){

        // Authorisation details.
//        $username = "rohankawade222@gmail.com";
//        $hash = "c9963114db8e1105ab875cdb14e55dbfd627f8a7";
//        $test = "0";
//        $data = array('username' => $username, 'hash' => $hash);
//        $textlocal = new Textlocal($username, $hash);
//
//        $response = $textlocal->getSenderNames();
//        print_r($response);
        // Send the POST request with cURL
//        $ch = curl_init('http://api.txtlocal.com/get_sender_names/');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        $sender = urlencode('rohankawade');
//        $numbers = "9561313954"; // A single number or a comma-seperated list of numbers
//        $message = "This is a test message from Rohan Kawade, Checking SMS API";
//        $message = urlencode($message);
//        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
//        $ch = curl_init('http://api.textlocal.in/send/?');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $result = curl_exec($ch); // This is the result from the API
//        curl_close($ch);
//        echo $response;
    }

    function generateOtp(){
        return mt_rand(100000,999999);
    }

    function sendOtp($contact, $otp){
        SMS();
    }

    function passwordHashing($password){
        $options = array('cost' => 11,);


        $hashpwd = password_hash($password, PASSWORD_BCRYPT, $options);
        return $hashpwd;
    }