<?php
require_once("UserRestHandler.php");

$view = "";
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$myPostData = json_decode($HTTP_RAW_POST_DATA);
	if(isset($myPostData->register)){
		// to handle REST Url register/
		$view = $myPostData->register;
		$register = new UserRestHandler();
		$register->registerUser($myPostData->register);
	}elseif(isset($myPostData->forgotPassword)){
		// to handle REST Url user/forgotpassword/
		$password = new UserRestHandler();
		$password->forgotPassword($myPostData->forgotPassword);
	}elseif(isset($myPostData->setPassword)){
		// to handle REST Url user/setpassword/
		$password = new UserRestHandler();
		$password->setPassword($myPostData->setPassword);
	}elseif(isset($myPostData->sendOtp)){
		// to handle REST Url user/sendotp/
		$otp = new UserRestHandler();
		$otp->sendOtp($myPostData->sendOtp);
	}elseif(isset($myPostData->verifyOtp)){
		// to handle REST Url user/verifyotp/
		$otp = new UserRestHandler();
		$otp->OtpVerification($myPostData->verifyOtp);
	}elseif(isset($myPostData->login)){
		// to handle REST Url user/email/
		$login = new UserRestHandler();
		$login->login($myPostData->login);
	}elseif(isset($myPostData->resetPassword)){
		// to handle REST Url user/resetpassword/
		$password = new UserRestHandler();
		$password->resetPassword($myPostData->resetPassword);
	}elseif(isset($myPostData->userDetails->id)){
		$user = new UserRestHandler();
		$user->updateUser($myPostData->userDetails);
	}
}elseif($_SERVER['REQUEST_METHOD'] == "GET"){
	if(isset($_GET["user"])){
		$view = $_GET["user"];
	}elseif(isset($_GET["address"])){
		$view = $_GET["address"];
	}elseif(isset($_GET["product"])){
		$view = $_GET["product"];
	}
	/*
    controls the RESTful services
    URL mapping
    */
	switch($view){

		case "getEmail":
			// to handle REST Url user/email/<username>
			$email = new UserRestHandler();
			$email->getEmailId($_GET['email']);
			break;
		case "getUserById":
			// to handle REST Url user/<userid>
			$user = new UserRestHandler();
			$user->getUserById($_GET['userId']);
			break;
		case "getMobileNumber":
			// to handle REST Url user/mobile/<username>
			$mobile = new UserRestHandler();
			$mobile->getMobileNumber($_GET['mobile']);
			break;
		case "deleteUser" :
			// to handle REST Url user/remove/<id>
			$user = new UserRestHandler();
			$user->deleteUserById($_GET['userId']);
			break;
		case "getProductByUserId" :
			// to handle REST Url user/product/<id>
			$user = new UserRestHandler();
			$user->getProductsByUserId($_GET['userId']);
			break;
	}
}


?>
