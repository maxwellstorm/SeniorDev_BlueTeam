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
private $posX;
private $posY;
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
		$this->posX = $results['posX'];
		$this->posY = $results['posY'];
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

public function getPosX() {
	return $this->posX;
}

public function setPosX($posX) {
	$this->posX = $posX;
}

public function getPosY() {
	return $this->posY;
}

public function setPosY($posY) {
	$this->posY = $posY;
}

public function putParams($map, $desc, $posX, $posY){
	//update
	util::checkRoom($this->roomNumber);
	$this->conn->setData("UPDATE room SET roomMap=:map, description=:roomDesc, posX=:posX, posY=:posY WHERE roomNumber = :num",array(
	":map"=>$map,
	":roomDesc"=>$desc,
	":posX"=>$posX,
	":posY"=>$posY,
	":num"=> $this->roomNumber
	));
}

public function put(){
	//update
	util::checkRoom($this->roomNumber);
	$this->conn->setData("UPDATE room SET roomMap=:map, description=:roomDesc, posX=:posX, posY=:posY WHERE roomNumber = :num",array(
	":map"=>$this->roomMap, 
	":roomDesc"=>$this->description,
	":posX"=>$this->posX,
	":posY"=>$this->posY,
	":num"=>$this->roomNumber
	));
}

public function postParams($num, $map, $desc, $posX, $posY){
	//insert
	util::checkRoom($num);
	$this->conn->setData("INSERT into room (roomNumber,roomMap,description, posX, posY) values (:num,:map,:rDesc, :posX, :posY)",array(
	":num"=>$num,
	":map"=>$map,
	":rDesc"=>$desc,
	":posX"=>$posx,
	":posY"=>$posY
	));
}

public function post(){
	//insert
	util::checkRoom($this->roomNumber);
	$this->conn->setData("INSERT into room (roomNumber, roomMap, description, posX, posY) values (:num, :map, :rDesc, :posX, :posY);",array(
	":num"=>$this->roomNumber,
	":map"=>$this->roomMap,
	":rDesc"=>$this->description,
	":posX"=>$this->posX,
	":posY"=>$this->posY
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