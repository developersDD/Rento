<?php
/* 
	Category Class RESTful web services
*/
Class Category {

	public function getAllCategoryAndSubCategories(){
		$categoryArray = array();
		$query = "select * from categories";
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)){
			$categoryId = $row["id"];
			$subCategoryArray = array();
			$subCategegoryQuery = "select id,name from sub_categories where category_id = '$categoryId'";
			$subCategegoryResult = mysql_query($subCategegoryQuery);
			while($subCategegoryResultRow = mysql_fetch_assoc($subCategegoryResult)){
				array_push($subCategoryArray,$subCategegoryResultRow);
			}
			$row['subcategory'] = $subCategoryArray;
			array_push($categoryArray,$row);
		}
		if(!empty($categoryArray)){
			return $categoryArray;
		}else{
			$response = array("status" => "0", "msg" => "No Match Found");
			return $response;
		}
	}
	
	public function getAllCategories(){
		$categoryArray = array();
		$query = "select * from categories";
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)){
			array_push($categoryArray,$row);
		}
		if(!empty($categoryArray)){
			return $categoryArray;
		}else{
			$response = array("status" => "0", "msg" => "No Match Found");
			return $response;
		}
	}

	public function getSubCategoriesByCategoryId($categoryId){
		$query = "select * from sub_categories where category_id = '$categoryId'";
		$result = mysql_query($query);
		$categoryArray = array();
		while($row = mysql_fetch_assoc($result)){
			array_push($categoryArray,$row);
		}
		if(!empty($categoryArray)){
			return $categoryArray;
		}else{
			$response = array("status" => "0", "msg" => "No Match Found");
			return $response;
		}
	}

	public function getSubCategoriesByName($name){
		$dataArray = array();
		$query = "select s.name from sub_categories s where (s.name LIKE '%$name%');";
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)){
			array_push($dataArray,$row);
		}
		$query1 = "select c.name from categories c where (c.name LIKE '%$name%');";
		$result1 = mysql_query($query1);
		while($row = mysql_fetch_assoc($result1)){
			array_push($dataArray,$row);
		}
		$query2 = "select p.name from product p where (p.name LIKE '%$name%') OR (p.description LIKE '%$name%');";
		$result2 = mysql_query($query2);
		while($row = mysql_fetch_assoc($result2)){
			array_push($dataArray,$row);
		}
		if(!empty($dataArray)){
			return $dataArray;
		}else{
			$response = array("status" => "0", "msg" => "No Match Found");
			return $response;
		}
	}
}
?>