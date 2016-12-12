<?php

include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "GET") {
        getAllCategory();
}
mysql_close($connection);

function getAllCategory(){
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
        echo json_encode($categoryArray);
    }else{
        $response = array("status" => "0", "msg" => "No Match Found");
        echo json_encode($response);
    }
}