<?php

class room{

private $roomNumber = "";
private $roomMap = "";
private $description = "";
private $conn;

	function __construct($conn,$roomNum){
		$this->conn = $conn;
		$this->roomNumber = $roomNum;
	}
	
	
public function fetch(){
	$resultsArr = $this->conn->getData("select * from room where roomNumber = :number",array(
		":number" => $this->roomNumber
		)
	);
	try{
		$results = $resultsArr[0];
		$this->roomMap = $results['roomMap'];
		$this->description = $results['description'];
		return true;
	}
	catch(Exception $e){
		return false;
	}
	
}

public function getRoomNum(){
	return $this->roomNumber;
} 

public function setRoomNum($num){
	$this->roomNumber = $num;
}

public function getRoomMap(){
	return $this->roomMap;
}

public function setRoomMap($map){
	$this->roomMap = $map;
}

public function getDescription(){
	return $this->description;
}

public function setDescription($desc){
	$this->description = $desc;
}

public function put($map,$desc){
	//update
	$this->conn->setData("UPDATE room SET roomMap=:map, description=:roomDesc WHERE roomNumber = :num",array(
	":map"=>$map,
	":roomDesc"=>$desc,
	":num"=> $this->roomNumber
	));
}

public function post($num,$map,$desc){
	//insert
	$this->conn->setData("INSERT into room (roomNumber,roomMap,description) values (:num,:map,:rDesc)",array(
	":num"=>$num,
	":map"=>$map,
	":rDesc"=>$desc
	));
}

public function delete(){
	//delete
	$this->conn->setData("DELETE from room where roomNumber = :num",array(
	":num"=> $this->roomNumber
	));
}

}
?>