<?php
require_once("ProductRestHandler.php");

$view = "";
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$myPostData = json_decode($HTTP_RAW_POST_DATA);
	if(isset($myPostData->productDetails->id)){
		// to handle REST Url product/updateProduct/
		$product = new ProductRestHandler();
		$product->updateExistingProduct($myPostData->productDetails);
	}elseif(isset($myPostData->productDetails)){
		// to handle REST Url product/addproduct/
		$product = new ProductRestHandler();
		$product->addNewProduct($myPostData->productDetails);
}
}elseif($_SERVER['REQUEST_METHOD'] == "GET"){
	if(isset($_GET["product"])){
		$view = $_GET["product"];
	}
	/*
    controls the RESTful services
    URL mapping
    */
	switch($view){

		case "deleteProduct" :
			// to handle REST Url user/remove/<id>
			$product = new ProductRestHandler();
			$product->deleteProductById($_GET['ownerId'],$_GET['productId']);
			break;
	}
}


?>
