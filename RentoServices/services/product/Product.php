<?php

Class Product{
    /*
     * Function to add product.
     */
    public function addAddress($address){
        if(isset($address->area) && isset($address->city_id) && isset($address->state_id) && isset($address->country_id)){
            $query = "INSERT INTO `address`  (`id`, `area`, `pincode`,`city_id`,`state_id`,`country_id`) VALUES
            (NULL, '$address->area','$address->pincode','$address->city_id','$address->state_id','$address->country_id')";
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

    public function addProduct($productData,$addressId){
        $query = "INSERT INTO  `product`  (`id`, `name`,`description`,`category`,`sub_category`,`rate_per_day`,`owner_id`,`from_date`,`to_date`,`product_address_id`,`ad_type`,`terms_condition`,`deposit`,`product_type`) VALUES
        (NULL, '$productData->name','$productData->description','$productData->category','$productData->sub_category','$productData->rate_per_day',
        '$productData->owner_id','$productData->from_date','$productData->to_date','$addressId','$productData->ad_type','$productData->terms_condition','$productData->deposit','$productData->product_type');";
        $result = mysql_query($query);
        if(mysql_affected_rows()> 0){
            $id = mysql_insert_id();
            $image = new Product();
            $image->saveimage($id,$productData->owner_id,$productData->product_images);
            $response = array("status" => "1","msg" => "Product added successfully");
        }else{
            $response = array("status" => "0","msg" => "Failed");
        }
        return $response;
    }

    public function saveimage($product_id,$owner_id,$images){
        foreach($images as $image){
            if($image->id == '' && $image->data != ''){
                $img = explode(',', $image->data);
                $ini =substr($img[0], 11);
                $type = explode(';', $ini);
                $data1 = str_replace('data:image/'.$type[0].';base64,', '', $image->data);
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
    }

    public function updateAddress($address)
    {
        if (isset($address->id) && isset($address->area)&& isset($address->pincode) && isset($address->city_id) && isset($address->state_id) && isset($address->country_id)) {
            $querySelect = "select * from `address` where id='$address->id'";
            $result = mysql_query($querySelect);
            $queryData = mysql_fetch_object($result);
            if ($queryData) {
                $query = "update `address` set area='$address->area',pincode='$address->pincode',city_id='$address->city_id',state_id='$address->state_id',country_id='$address->country_id' where id='$address->id'";
                $result = mysql_query($query);
                if ($result > 0) {
                    $response = $address->id;
                } else {
                    $response = 0;
                }
            } else {
                $response = 0;
            }
            return $response;
        }
        return $response = 0;
    }


    public function updateProduct($productData,$addressId){
        $querySelect = "select * from `product` where id='$productData->id'";
        $result = mysql_query($querySelect);
        $queryData = mysql_fetch_object($result);
        if ($queryData) {
            $query = "update `product` set name='$productData->name',description='$productData->description',category='$productData->category',
                      sub_category='$productData->sub_category',rate_per_day='$productData->rate_per_day',owner_id='$productData->owner_id',
                      from_date='$productData->from_date',to_date='$productData->to_date',product_address_id='$addressId',
                      ad_type='$productData->ad_type',terms_condition='$productData->terms_condition',deposit='$productData->deposit',
                      product_type='$productData->product_type' where id='$productData->id'";

            $result1 = mysql_query($query);
            if($result1> 0){
                $id = $productData->id;
                $image = new Product();
                $image->updateimage($id,$productData->owner_id,$productData->product_images);
                $response = array("status" => "1","msg" => "Product updated successfully");
            }else{
                $response = array("status" => "0","msg" => "Product not updated");
            }
            return $response;
        }
        $response = array("status" => "0","msg" => "Failed");
        return $response;
    }

    public function updateimage($product_id,$owner_id,$images){
        foreach($images as $image){
            if($image->id == '' && $image->data != ''){
                $img = explode(',', $image->data);
                $ini =substr($img[0], 11);
                $type = explode(';', $ini);
                $data1 = str_replace('data:image/'.$type[0].';base64,', '', $image->data);
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
            }elseif($image->id != '' && $image->data != ''){
                $querySelect = "SELECT * from `product_images` WHERE `id`= $image->id";
                $result = mysql_query($querySelect);
                $queryData = mysql_fetch_object($result);
                if ($queryData) {
                    $upath = $udir = '../../images/'.$queryData->image_name;
                    unlink($upath);
                    $img = explode(',', $image->data);
                    $ini =substr($img[0], 11);
                    $type = explode(';', $ini);
                    $data1 = str_replace('data:image/'.$type[0].';base64,', '', $image->data);
                    $data2 = str_replace(' ', '+', $data1);
                    $data3 = base64_decode($data2);
                    $imagename = uniqid();
                    $dir = '../../images/user/'.$owner_id.'/product/'.$product_id.'/';
                    $path = 'user/'.$owner_id.'/product/'.$product_id.'/'. $imagename . '.'.$type[0];
                    if(!file_exists($dir)){
                        mkdir($dir,0777,true);
                    }
                    $file = $dir. $imagename . '.'.$type[0];
                    $query = "update `product_images` set `image_name`= '$path' where id='$image->id'";
                    $result = mysql_query($query);
                    if(mysql_affected_rows()> 0){
                        file_put_contents($file, $data3);
                    }
                }
            }
        }
    }

    public function deleteProduct($ownerId,$productId){
        $selectQuery = "SELECT `product_address_id` FROM `product` WHERE `id` = $productId";
        $selectQueryResult = mysql_query($selectQuery);
        $responseArray =array();
        while($row = mysql_fetch_assoc($selectQueryResult)){
            $addressId = $row['product_address_id'];
            $query = "DELETE from `product` WHERE `id` = $productId AND `owner_id` = $ownerId";
            $result = mysql_query($query) or die("Query failed : " . mysql_error());
            if($result > 0){
                $address = $this->deleteProductAddress($addressId);
                if($address){
                    $images = $this->deleteProductImages($productId);
                    if($images){
                        $response = array("status" => "1","msg" => "Product Deleted Successfully");
                    }else{
                        $response = array("status" => "0","msg" => "Failed to delete product images");
                    }
                }else{
                    $response = array("status" => "0","msg" => "Failed to delete product address");
                }
            }else{
                $response = array("status" => "0","msg" => "Failed to delete product");
            }
            array_push($responseArray,$response);
        }
        return $responseArray[0];
    }

    public function deleteProductAddress($addressId){
        $query = "DELETE from `address` WHERE `id` = $addressId";
        $result = mysql_query($query);
        if($result > 0){
            $response = 1;
        }else{
            $response = 0;;
        }
        return $response;
    }

    public function deleteProductImages($productId){
        $querySelect = "SELECT * from `product_images` WHERE `product_id`= $productId";
        $selectQueryResult = mysql_query($querySelect);
        $query = "DELETE from `product_images` WHERE `product_id` = $productId";
        $result = mysql_query($query);
        if($result > 0){
            while($row = mysql_fetch_assoc($selectQueryResult)){
                $path = pathinfo($row['image_name']);
                $this->deleteDir('../../images/'.$path['dirname']);


            }
            $response = 1;
        }else{
            $response = 0;;
        }
        return $response;
    }

    public static function deleteDir($dirPath) {
        if (is_dir($dirPath)) {
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
//                self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }    }
    }
?>