<?php
include '../product/Product.php';
Class User{
    /*
     * User login to the application.
     */
    public function loginUser($loginData){
        if(isset($loginData->password) && isset($loginData->username)){
            $query = "select * from user where (contact = '$loginData->username' OR email ='$loginData->username')";
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
        return $response;
    }
    /*
     * Fetching email address to know it is already registered.
     */
    public function getEmail($email){
        $emailArray = array();App_dump($email);
        $query = "select * from user where email = '$email'";
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)){
            array_push($emailArray,$row);
        }App_dump($emailArray);
        if(!empty($emailArray)){
            $response = array("status" => "0", "msg" => "Email already registered");
        }else{
            $response = array("status" => "0", "msg" => "No Match Found");
        }
        return $response;
    }
    /*
     * Fetching mobile number to know it is already registered.
     */
    public function getMobile($mobile){
        $mobileArray = array();
        $query = "select * from user where contact = '$mobile'";
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)){
            array_push($mobileArray,$row);
        }
        if(!empty($mobileArray)){
            $response = array("status" => "0", "msg" => "Mobile number already registered");
        }else{
            $response = array("status" => "0", "msg" => "No Match Found");
        }
        return $response;
    }
    /*
	 * New user registration.
	 */
    public function registerUsers($user){
        $otp = generateOtp();
        $hashpwd = passwordHashing($user->password);
        $query = "INSERT INTO `user`  (`id`, `name`, `email`, `contact`, `password`, `otp`) VALUES (NULL, '$user->name', '$user->email' ,'$user->contact', '$hashpwd','$otp');";
        $result = mysql_query($query);
        if(mysql_affected_rows() > 0){
            $response = array("status" => "1","userId" => mysql_insert_id(),"msg" => "Please verify your account. OTP is sent to your registered mobile number");
        }else{
            $response = array("status" => "0","msg" => "Failed");
        }
        sendOtp($user->contact, $otp);
        return $response;
    }
    /*
     * Sending otp to reset password.
     */
    public function sendOtpVerification($data){
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
        return $response;
    }
    /*
     * Changing password without login when user forgot the password.
     */
    public function setNewPassword($data){
        if(isset($data->password) && isset($data->userId)){
            $query = "select id from user where (id = '$data->userId')";
            $result = mysql_query($query);
            if(mysql_num_rows($result)){
                $hashpwd = passwordHashing($data->password);
                $queryPassword = "update `user` set password='$hashpwd' where (id = '$data->userId')";
                $resultPassword = mysql_query($queryPassword);
                if($resultPassword){
                    $userId = $data->userId;
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
        return $response;
    }
    /*
     * Sending otp to users registered mobile number.
     */
    public function sendOtpToUser($data){
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
        return $response;
    }
    /*
     * Otp verification of user.
     */
    public function verifyOtp($data){
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
        return $response;
    }
    /*
     * Delete the user.
     */
    public function deleteUsers($id){
        $query = "select * from `user` where id='$id'";
        $result = mysql_query($query);
        if(mysql_fetch_object($result)){
            $query = "DELETE FROM `user` WHERE id='$id'";
            $result = mysql_query($query)or die("Query failed : " . mysql_error());
            if($result){
                $response = array("status" => "1","msg" => "User deleted successfully");
            }else{
                $response = array("status" => "0","msg" => "Failed");
            }
        }else{
            $response = array("status" => "0","msg" => "No Such User Exists");
        }
        return $response;
    }
    /*
     * Reset the password when user is logged in.
     */
    public function resetPassword($data){
        if(isset($data->password) && isset($data->userId) && isset($data->confirmPassword)){
            if($data->password == $data->confirmPassword){
                $hashpwd = passwordHashing($data->password);
                $query = "update `user` set password='$hashpwd' where (id = '$data->userId')";
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
        return $response;
    }
    /*
   * Get product by user id.
   */
    public function getProductByUserId($userId){
        $productArray = array();
        $query = "select * from product where owner_id = '$userId'";
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)){
            $productId = $row['id'];
            $addressId = $row['product_address_id'];
            $imageArray = array();
            $imageQuery = "select id,image_name from product_images where product_id = '$productId'";
            $imageResult = mysql_query($imageQuery);
            while($imageResultRow = mysql_fetch_assoc($imageResult)){
                array_push($imageArray,$imageResultRow);
            }
            $addressArray = array();
            $addressQuery = "select * from address where id = '$addressId'";
            $addressResult = mysql_query($addressQuery);
            while($addressResultRow = mysql_fetch_assoc($addressResult)){
                array_push($addressArray,$addressResultRow);
            }
            $row['image'] = $imageArray;
            $row['address'] = $addressArray;
            array_push($productArray,$row);
        }
        if(!empty($productArray)){
            $response = $productArray;
        }else{
            $response = array("status" => "0", "msg" => "Product not available");
        }
        return $response;
    }

    public function getUserByUserId($userId){
        $userArray = array();
        $query = "SELECT name,email,contact,address_id from user WHERE id = '$userId'";
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)){
            $addressId = $row['address_id'];
                $addressArray = array();
                $queryAddress = "SELECT * from address WHERE id = '$addressId'";
                $resultAddress = mysql_query($queryAddress);
                while($rowAddress = mysql_fetch_assoc($resultAddress)){
                    array_push($addressArray,$rowAddress);
                }
                $row['address'] = $addressArray;
            array_push($userArray,$row);
        }
        if(!empty($userArray)){
            $response = $userArray[0];
        }else{
            $response = array("status" => "0", "msg" => "User does not exist");
        }
        return $response;
    }

    public function updateExistingUser($user){
        if($user->address){
            $address = new Product();
             if($user->address->id == ''|| $user->address->id == 0){
//App_dump($user);
                 $addressId = $address->addAddress($user->address);
             }else{
//                 App_dump($user);
                 $addressId = $address->updateAddress($user->address);
             }
        }else{
            $addressId = 0;
        }
        $query = "select * from `user` where id='$user->id'";
        $result = mysql_query($query);
        $queryData = mysql_fetch_object($result);
        if($queryData){
            $query = "update `user` set name='$user->name',address_id ='$addressId',email='$user->email',contact='$user->contact' where id='$user->id'";
            $result = mysql_query($query);
            if($result){
                $response = array("status" => "1","msg" => "User updated Successfully");
            }else{
                $response = array("status" => "0","msg" => "Failed to update user");
            }
        }else{
            $response =  array("status" => "0","msg" => "No Such User Exists");
        }
        return $response;
    }
}
?>