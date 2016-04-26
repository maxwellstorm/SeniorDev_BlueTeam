<?php

require_once("util.php");
require_once("commonAuth.php");

//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
$allowed = true;

if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

class room{

private $roomNumber = "";
private $roomMap = "";
private $description = "";
private $conn;

	function __construct($conn,$roomNum = null){
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

public function putParams($map,$desc){
	//update
	util::checkRoom($this->roomNumber);
	$this->conn->setData("UPDATE room SET roomMap=:map, description=:roomDesc WHERE roomNumber = :num",array(
	":map"=>$map,
	":roomDesc"=>$desc,
	":num"=> $this->roomNumber
	));
}

public function put(){
	//update
	util::checkRoom($this->roomNumber);
	$this->conn->setData("UPDATE room SET roomMap=:map, description=:roomDesc WHERE roomNumber = :num",array(
	":map"=>$this->roomMap, 
	":roomDesc"=>$this->description,
	":num"=>$this->roomNumber
	));
}

public function postParams($num,$map,$desc){
	//insert
	util::checkRoom($num);
	$this->conn->setData("INSERT into room (roomNumber,roomMap,description) values (:num,:map,:rDesc)",array(
	":num"=>$num,
	":map"=>$map,
	":rDesc"=>$desc
	));
}

public function post(){
	//insert
	util::checkRoom($this->roomNumber);
	$this->conn->setData("INSERT into room (roomNumber,roomMap,description) values (:num,:map,:rDesc)",array(
	":num"=>$this->roomNumber,
	":map"=>$this->roomMap,
	":rDesc"=>$this->description
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