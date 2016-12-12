<?php
require_once("UtilityRestHandler.php");

$view = "";
if(isset($_GET["category"])){
	$view = $_GET["category"];
}elseif(isset($_GET["address"])){
	$view = $_GET["address"];
}
/*
controls the RESTful services
URL mapping
*/
switch($view){

	case "allCategory":
		// to handle REST Url /category/list/
		$categories = new UtilityRestHandler();
		$categories->getAllCategory();
		break;
	case "allCategoryWithSubCategory":
		// to handle REST Url /category/subcategory/
		$categories = new UtilityRestHandler();
		$categories->getAllCategoryAndSubCategory();
		break;
	case "subCategoryByCategoryId":
		// to handle REST Url /category/subcategory/<id>/
		$categories = new UtilityRestHandler();
		$categories->getSubCategoryByCategoryId($_GET["categoryId"]);
		break;
	case "subCategoryByName":
		// to handle REST Url /category/subcategoryname/<name>/
		$categories = new UtilityRestHandler();
		$categories->getSubCategoryByName($_GET["subCategoryName"]);
		break;
	case "allCountry":
		// to handle REST Url /category/subcategoryname/<name>/
		$country = new UtilityRestHandler();
		$country->getAllCountry();
		break;
	case "stateByCountryId":
		// to handle REST Url /category/subcategoryname/<name>/
		$country = new UtilityRestHandler();
		$country->getStateByCountryId($_GET["countryId"]);
		break;
	case "cityByStateId":
		// to handle REST Url /category/subcategoryname/<name>/
		$country = new UtilityRestHandler();
		$country->getCityByStateId($_GET["stateId"]);
		break;
	case "cityByName":
		// to handle REST Url /category/subcategoryname/<name>/
		$country = new UtilityRestHandler();
		$country->getCityByName($_GET["cityName"]);
		break;
	case "" :
		//404 - not found;
		break;
}
?>
