<?php

class department{

private $deptId = "";
private $deptName = "";
private $deptAbbr = "";
private $conn;



public function getDeptAbbr(){
	return $this->deptAbbr;
}

public function setDeptId($id){
	$this->deptId = $id;
}


	function __construct($conn,$deptId){
		$this->conn = $conn;
		$this->deptId = $deptId;
	}
	
	
public function fetch(){
	$resultsArr = $this->conn->getData("select * from department where departmentID = :id",array(
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



public function put(){
	//update
}



public function post(){
	//insert
}

public function delete(){
	//delete
}




/*public function insert($deptID. $deptName, $deptAbbr){
	$database->query('INSERT INTO Department (departmentID, departmentName, departmentAbbr) values ($deptID, $deptName, $deptAbbr)');
	$database->bind('$deptID', 999);
	$database->bind('$deptName', 'Dept');
	$database->bind('$deptAbbr','Abbr');

	$database->execute();
	echo $database->lastInsertId();//test successfully inserted
}

public function update($deptID. $deptName, $deptAbbr){
	$database->query('UPDATE Department (departmentID, departmentName, departmentAbbr) values ($deptID, $deptName, $deptAbbr)');
	$database->bind('$deptID', 999);
	$database->bind('$deptName', 'Dept');
	$database->bind('$deptAbbr','Abbr');

	$database->execute();
}

*/
}
?>