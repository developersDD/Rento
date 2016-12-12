<?php
/* 
	Address Class RESTful web services
*/
Class Address {

    public function getAllCountries(){
        $countryArray = array();
        $query = "select id,countryname from countries";
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)){
            array_push($countryArray,$row);
        }
        if(!empty($countryArray)){
            return $countryArray;
        }else{
            $response = array("status" => "0", "msg" => "No Match Found");
            return $response;
        }
    }

    public function getCitiesByStateId($stateId){
        $query = "select id,cityname from cities where state_id = '$stateId'";
        $result = mysql_query($query);
        $cityArray = array();
        while($row = mysql_fetch_assoc($result)){
            array_push($cityArray,$row);
        }
        if(!empty($cityArray)){
            return $cityArray;
        }else{
            $response = array("status" => "0", "msg" => "No Match Found");
            return $response;
        }
    }

    public function getCitiesByName($city){
        $query = "select c.cityname, s.statename from cities c left join states s on c.state_id = s.id where (c.cityname LIKE '$city%');";
        $result = mysql_query($query);
        $cityArray = array();
        while($row = mysql_fetch_assoc($result)){
            array_push($cityArray,$row);
        }
        if(!empty($cityArray)){
            return $cityArray;
        }else{
            $response = array("status" => "0", "msg" => "No Match Found");
            return $response;
        }
    }

    public function getStatesByCountryId($countryId){
        $query = "select id,statename from states where country_id = '$countryId'";
        $result = mysql_query($query);
        $stateArray = array();
        while($row = mysql_fetch_assoc($result)){
            array_push($stateArray,$row);
        }
        if(!empty($stateArray)){
            return $stateArray;
        }else{
            $response = array("status" => "0", "msg" => "No Match Found");
            return $response;
        }
    }
}
?>