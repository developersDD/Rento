<?php
include_once('../commonServices.php');
require_once("../SimpleRest.php");
require_once("Product.php");
class ProductRestHandler extends SimpleRest {

	/*
	 * Encoding into json response.
	 */
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;
	}
	/*
	 * Adding a new product.
	 */
	function addNewProduct($productData) {

		$address = new Product();
		$addressId = $address->addAddress($productData->address);
		if(!$addressId) {
			$statusCode = 404;
			$rawData = array('error' => 'Invalid address!');
		} else {
			$rawData = $address->addProduct($productData,$addressId);
			if(empty($rawData)) {
				$statusCode = 404;
				$rawData = array('error' => 'No mobiles found!');
			} else {
				$statusCode = 200;
			}
		}

		$requestContentType = $_SERVER['CONTENT_TYPE'];
		$this->setHttpHeaders($requestContentType, $statusCode);

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		}
	}

	/*
	* Updating existing product.
	*/
	function updateExistingProduct($productData) {

		if(isset($productData->address->id)){
			$address = new Product();
			$addressId = $address->updateAddress($productData->address);
			if(!$addressId) {
				$statusCode = 404;
				$rawData = array('error' => 'Invalid address!');
			} else {
				$rawData = $address->updateProduct($productData,$addressId);
				if(empty($rawData)) {
					$statusCode = 404;
					$rawData = array('error' => 'No mobiles found!');
				} else {
					$statusCode = 200;
				}
			}
		}


		$requestContentType = $_SERVER['CONTENT_TYPE'];
		$this->setHttpHeaders($requestContentType, $statusCode);

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		}
	}

	/*
	 * Delete the product.
	 */
	public function deleteProductById($ownerId,$productId) {
		$product = new Product();
		$rawData = $product->deleteProduct($ownerId,$productId);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No Product found!');
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['CONTENT_TYPE'];
		$this ->setHttpHeaders($requestContentType, $statusCode);

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		}
	}

	/*
	 * Fetching mobile number to know it is already registered.
	 */
//	public function getMobileNumber($mobileNo) {
//
//		$mobile = new User();
//		$rawData = $mobile->getMobile($mobileNo);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * New user registration.
	 */
//	public function registerUser($userData) {
//
//		$register = new User();
//		$rawData = $register->registerUsers($userData);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * Sending otp to reset password.
	 */
//	public function forgotPassword($username) {
//
//		$password = new User();
//		$rawData = $password->sendOtpVerification($username);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * Changing password without login when user forgot the password.
	 */
//	public function setPassword($newpassword) {
//
//		$password = new User();
//		$rawData = $password->setNewPassword($newpassword);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * Sending otp to users registered mobile number.
	 */
//	public function sendOtp($username) {
//
//		$otp = new User();
//		$rawData = $otp->sendOtpToUser($username);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * Otp verification of user.
	 */
//	public function OtpVerification($otp) {
//
//		$verifyOtp = new User();
//		$rawData = $verifyOtp->verifyOtp($otp);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}


	/*
	 * User login to the application.
	 */
//	public function login($loginData) {
//
//		$login = new User();
//		$rawData = $login->loginUser($loginData);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * Delete the user.
	 */
//	public function deleteUserById($userId) {
//
//		$user = new User();
//		$rawData = $user->deleteUsers($userId);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * Reset the password when user is logged in.
	 */
//	public function resetPassword($newpassword) {
//
//		$password = new User();
//		$rawData = $password->resetPassword($newpassword);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}

	/*
	 * Get the product by User id.
	 */
//	public function getProductsByUserId($userId) {
//
//		$product = new User();
//		$rawData = $product->getProductByUserId($userId);
//
//		if(empty($rawData)) {
//			$statusCode = 404;
//			$rawData = array('error' => 'No mobiles found!');
//		} else {
//			$statusCode = 200;
//		}
//
//		$requestContentType = $_SERVER['CONTENT_TYPE'];
//		$this ->setHttpHeaders($requestContentType, $statusCode);
//
//		if(strpos($requestContentType,'application/json') !== false){
//			$response = $this->encodeJson($rawData);
//			echo $response;
//		}
//	}
}
?>