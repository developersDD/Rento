<?php
include_once('../commonServices.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {

    $myPostData = json_decode($HTTP_RAW_POST_DATA);
    if(isset($myPostData->productDetails)){
        $addressId = addAddress($myPostData->productDetails->address);
        if($addressId){
            addProduct($myPostData->productDetails,$addressId);

        }else{
            $response = array("status" => "0","msg" => "Invalid address");
            echo json_encode($response);
        }
    }
}
mysql_close($connection);

function addProduct($productData,$addressId){
    $query = "INSERT INTO `rento_trial`.`product`  (`id`, `name`,`description`,`category`,`sub_category`,`rate_per_day`,`owner_id`,`from_date`,`to_date`,`product_address_id`,`ad_type`,`terms_condition`,`deposit`,`product_type`) VALUES
(NULL, '$productData->name','$productData->description','$productData->category','$productData->sub_category','$productData->rate_per_day',
'$productData->owner_id','$productData->from_date','$productData->to_date','$addressId','$productData->ad_type','$productData->terms_condition','$productData->deposit','$productData->product_type');";
    $result = mysql_query($query);
    if(mysql_affected_rows()> 0){
        $id = mysql_insert_id();
        saveimage($id,$productData->owner_id,$productData->product_images);
        $response = array("status" => "1","msg" => "Product added successfully");
    }else{
        $response = array("status" => "0","msg" => "Failed");
    }
    echo json_encode($response);
}

function addAddress($address){
    if(isset($address->area) && isset($address->city_id) && isset($address->state_id) && isset($address->country_id)){
        $query = "INSERT INTO `address`  (`id`, `area`,`city_id`,`state_id`,`country_id`) VALUES
(NULL, '$address->area','$address->city_id','$address->state_id','$address->country_id')";
        $result = mysql_query($query);
        if(mysql_affected_rows()> 0){
            $id = mysql_insert_id();
            $response = $id;
        }else{
            $response = 0;
        }
    }else{
        $response = 0;
    }
    return $response;
}

function saveimage($product_id,$owner_id,$images){
    foreach($images as $image){
        $img = explode(',', $image);
        $ini =substr($img[0], 11);
        $type = explode(';', $ini);
        $data1 = str_replace('data:image/'.$type[0].';base64,', '', $image);
        $data2 = str_replace(' ', '+', $data1);
        $data3 = base64_decode($data2);
        $imagename = uniqid();
        $dir = '../../images/user/'.$owner_id.'/product/'.$product_id.'/';
        $path = 'user/'.$owner_id.'/product/'.$product_id.'/'. $imagename . '.'.$type[0];
        if(!file_exists($dir)){
            mkdir($dir,0777,true);
        }
        $file = $dir. $imagename . '.'.$type[0];
        $query = "INSERT INTO `product_images`  (`id`, `image_name`,`product_id`) VALUES (NULL, '$path','$product_id');";
        $result = mysql_query($query);
        if(mysql_affected_rows()> 0){
            file_put_contents($file, $data3);
        }
    }
}