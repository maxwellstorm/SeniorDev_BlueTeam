<?php

class room{

private $roomNumber = "";
private $roomMap = "";
private $description = "";
private $conn;

	function __construct($conn,$deptId){
		$this->conn = $conn;
		$this->deptId = $deptId;
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

public function put(){
	//update
}

public function post(){
	//insert
}

public function delete(){
	//delete
}

}
?>