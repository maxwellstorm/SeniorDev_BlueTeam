<?php

class department{

private $deptId = "";
private $deptName = "";
private $deptAbbr = "";
private $conn;

	function __construct($conn,$deptId){
		$this->conn = $conn;
		$this->deptId = $deptId;
	}
	
	
public function fetch(){
	$resultsArr = $this->conn->getData("SELECT * from department where departmentID = :id",array(
		":id" => $this->deptId
		)
	);
	try{
		$results = $resultsArr[0];
		$this->deptName = $results['departmentName'];
		$this->deptAbbr = $results['departmentAbbr'];
		return true;
	}
	catch(Exception $e){
		return false;
	}
	
}

public function getDeptId(){
	return $this->deptId;
} 

public function setDeptId($id){
	$this->deptId = $id;
}

public function getDeptName(){
	return $this->deptName;
} 

public function setDeptName($name){
	$this->deptName = $name;
}

public function getDeptAbbr(){
	return $this->deptAbbr;
}

public function setDeptAbbr($abbr){
	$this->deptAbbr = $abbr;
}

public function put($name,$abbr){
	//update
	$this->conn->setData("UPDATE department SET departmentName=:name, departmentAbbr=:abbr WHERE departmentId = :id",array(
	":name"=>$name,
	":abbr"=>$abbr,
	":id"=> $this->deptId
	));
}

public function post($id,$name,$abbr){
	//insert
	$this->conn->setData("INSERT into department (departmentId,departmentName,departmentAbbr) values (:id,:name,:abbr)",array(
	":id"=>$id,
	":name"=>$name,
	":abbr"=>$abbr
	));
}

public function delete(){
	//delete
	$this->conn->setData("DELETE from department where departmentId = :id",array(
	":id"=> $this->deptId
	));
}

}
?>