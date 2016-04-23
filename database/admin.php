<?php
require_once("util.php");
class admin{

	private $adminId = "";
	private $fname = "";
	private $lname = "";
	private $user = "";
	private $accessLvl = "";
	private $deptId = "";
	private $conn;

	function __construct($conn,$adminId = null){
		$this->conn = $conn;
		$this->adminId = $adminId;
	}
	
	
public function fetch(){
	$resultsArr = $this->conn->getData("select * from Admin where adminId = :id",array(
		":id" => $this->adminId
		)
	);
	try{
		$results = $resultsArr[0];
		$this->adminId = $results['adminId'];
		$this->fname = $results['fName'];
		$this->lname = $results['lName'];
		$this->user = $results['username'];
		$this->accessLvl = $results['accessLevel'];
		$this->deptId = $results['departmentId'];
		return true;
	}
	catch(Exception $e){
		return false;
	}
	
}

public function getAdminId(){
	return $this->adminId;
}

public function setAdminId($aId){
	$this->adminId = $aId;
}

public function getAdminFName(){
	return $this->fname;
}

public function setAdminFName($fname){
	$this->fname = $fname;
}

public function getAdminLName(){
	return $this->lname;
}

public function setAdminLName($lname){
	$this->lname = $lname;
}

public function getUsername(){
	return $this->user;
}

public function setUsername($user){
	$this->user = $user;
}

public function getAccessLevel(){
	return $this->accessLvl;
}

public function setAccessLevel($accessLvl){
	$this->accessLvl = $accessLvl;
}

public function getDeptId(){
	return $this->deptId;
} 

public function setDeptId($dId){
	$this->deptId = $dId;
}

function putParams($fname,$lname,$user,$accessLvl,$deptId){
	//update
	//util::checkName($fname);
	//util::checkName($lname);
	$this->conn->setData("UPDATE Admin SET fName=:fname, lName=:lname, username=:user, accessLevel=:accessLvl, departmentId=:deptId WHERE adminId = :id;",array(
	":fname"=>$fname,
	":lname"=>$lname,
	":user"=>$user,
	":accessLvl"=>$accessLvl,
	":deptId"=>$deptId,
	":id"=>$this->adminId
	));
}

function put(){
	//update
	util::checkName($this->fname);
	util::checkName($this->lname);
	$this->conn->setData("UPDATE Admin SET fName=:fname, lName=:lname, username=:user, accessLevel=:accessLvl, departmentId=:deptId WHERE adminId = :id;",array(
	":fname"=>$this->fname,
	":lname"=>$this->lname,
	":user"=>$this->user,
	":accessLvl"=>$this->accessLvl,
	":deptId"=>$this->deptId,
	":id"=>$this->adminId
	));
}

public function postParams($fname,$lname,$user,$accessLvl,$deptId){
	//insert
	util::checkName($fname);
	util::checkName($lname);
	$this->conn->setData("INSERT into Admin (adminId,fName,lName,username,accessLevel,departmentId) values (DEFAULT,:fname,:lname,:user,:accessLvl,:deptId)",array(
	":fname"=>$fname,
	":lname"=>$lname,
	":user"=>$user,
	":accessLvl"=>$accessLvl,
	":deptId"=>$deptId
	));
}

public function post(){
	//insert
	util::checkName($this->fname);
	util::checkName($this->lname);
	$this->conn->setData("INSERT into Admin (adminId,fName,lName,username,accessLevel,departmentId) values (DEFAULT,:fname,:lname,:user,:accessLvl,:deptId)",array(
	":fname"=>$this->fname,
	":lname"=>$this->lname,
	":user"=>$this->user,
	":accessLvl"=>$this->accessLvl,
	":deptId"=>$this->deptId
	));
}

public function delete(){
	//delete
	$this->conn->setData("DELETE from Admin where adminId = :id",array(
	":id"=> $this->adminId
	));
}

}
?>