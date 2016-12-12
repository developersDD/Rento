<?php
include_once('../commonServices.php');
require_once("../SimpleRest.php");
require_once("Category.php");
require_once("Address.php");

class UtilityRestHandler extends SimpleRest {

	/*
	 * Encoding into json response.
	 */
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;
	}
	/*
	 * Fetching all the categories with their respective subcategories.
	 */
	function getAllCategoryAndSubCategory() {

		$category = new Category();
		$rawData = $category->getAllCategoryAndSubCategories();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');		
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['CONTENT_TYPE'];
		$this->setHttpHeaders($requestContentType, $statusCode);

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		}
	}

	/*
	 * Fetching all the categories with their respective subcategories.
	 */
	public function getAllCategory() {

		$category = new Category();
		$rawData = $category->getAllCategories();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');		
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
	 * Fetching all the categories with their respective subcategories.
	 */
	public function getSubCategoryByCategoryId($categoryId) {

		$category = new Category();
		$rawData = $category->getSubCategoriesByCategoryId($categoryId);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');
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
	 * Fetching all the categories and subcategories by their name or dscription.
	 */
	public function getSubCategoryByName($categoryId) {

		$category = new Category();
		$rawData = $category->getSubCategoriesByName($categoryId);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');
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
	 * Fetching all the categories with their respective subcategories.
	 */
	public function getAllCountry() {

		$category = new Address();
		$rawData = $category->getAllCountries();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');
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
	 * Fetching all the cities by stateId.
	 */
	public function getCityByStateId($stateId) {

		$category = new Address();
		$rawData = $category->getCitiesByStateId($stateId);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');
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
	 * Fetching cities by Name.
	 */
	public function getCityByName($stateId) {

		$category = new Address();
		$rawData = $category->getCitiesByName($stateId);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');
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
	 * Fetching all the states by countryId.
	 */
	public function getStateByCountryId($countryId) {

		$address = new Address();
		$rawData = $address->getStatesByCountryId($countryId);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');
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
}
?>