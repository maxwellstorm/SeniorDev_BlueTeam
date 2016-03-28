<?php

class employees{

private $facultyId = "";
private $fname = "";
private $lname = "";
private $roomNumber = "";
private $email = "";
private $departmentId = "";
private $isActive = "";
private $isFaculty = "";
private $phone = "";
private $about = "";
private $education = "";
private $highlights = "";
private $title = "";
private $secDeptId = "";
private $conn;

	function __construct($conn,$facId){
		$this->conn = $conn;
		$this->facultyId = $facId;
	}

public function fetch(){
	$resultsArr = $this->conn->getData("select * from employees where facultyId = :id",array(
		":id" => $this->facultyId
		)
	);
	try{
		$results = $resultsArr[0];
		$this->fname = $results['fName'];
		$this->lname = $results['lName'];
		$this->roomNumber = $results['roomNumber'];
		$this->email = $results['email'];
		//$this->officeHours = $results['officeHours'];
		$this->departmentId = $result['departmentId'];
		$this->isActive = $results['isActive'];
		$this->isFaculty = $results['isFaculty'];
		$this->phone = $results['phone'];
		$this->about = $results['about'];
		$this->education = $results['education'];
		$this->highlights = $results['highlights'];
		return true;
	}
	catch(Exception $e){
		return false;
	}
}	

public function getEmployeeId(){
	return $this->facultyId;
} 

public function setEmployeeId($id){
	$this->facultyId = $id;
}

public function getEmpFName(){
	return $this->fName;
}

public function setEmpFName($fname){
	$this->fName = $fname;
}

public function getEmpLName(){
	return $this->lName;
}

public function setEmpLName($lname){
	$this->lName = $lname;
}

public function getRoomNum(){
	return $this->roomNumber;
}

public function setRoomNum($roomNum){
	$this->roomNum = $roomNum;
}

public function getEmail(){
	return $this->email;
}

public function setEmail($email){
	$this->email = $email;
}

/*
public function getOfficeHours(){
	return $this->officeHours;
}

public function setOfficeHous($officeHours){
	$this->officeHours = $officeHours;
}
*/
public function getDepartmentId(){
	return $this->departmentId;
}

public function setDepartmentId($deptId){
	$this->deptId = $deptId;
}

public function getActive(){
	return $this->isActive;
}

public function setActive($active){
	$this->isActive = $active;
}

public function getFaculty(){
	return $this->isFaculty;
}

public function setFaculty($faculty){
	$this->isFaculty = $faculty;
}

public function getPhone(){
	return $this->phone;
}

public function setPhone($phone){
	$this->phone = $phone;
}

public function getAbout(){
	return $this->about;
}

public function setAbout($about){
	$this->about = $about;
}

public function getEducation(){
	return $this->education;
}

public function setEducation($education){
	$this->education = $education;
}

public function getHighlights(){
	return $this->highlights;
}

public function setHighlights($highlights){
	$this->highlights = $highlights;
}

public function put($fname,$lname,$email,$active,$faculty,$phone,$about,$edu,$highlights,$deptId,$roomNum,$title,$secDeptId){
	//update
	$this->conn->setData("UPDATE Employees SET fName=:fname, lName=:lname, email=:email, isActive=:active, isFaculty=:faculty, phone=:phone, about=:about, education=:edu, highlights=:highlights, departmentId=:deptId, roomNumber=:roomNum, title=:title, secondaryDepartmentId=:secDeptId  WHERE departmentId = :id",array(
	":fname"=>$fname,
	":lname"=>$lname,
	":email"=>$email,
	":deptId"=>$deptId,
	":active"=>$active,
	":faculty"=>$faculty,
	":phone"=>$phone,
	":about"=>$about,
	":edu"=>$edu,
	":highlights"=>$highlights,
	":id"=> $this->deptId,
	":roomNum"=>$roomNum,
	":title"=>$title,
	":secDeptId"=>$secDeptId,
	":id"=>$this->facultyId
	));
}

public function post($id,$fname,$lname,$email,$active,$faculty,$phone,$about,$edu,$highlights,$deptId,$roomNum,$title,$secDeptId){
	//insert
	$this->conn->setData("INSERT into Employees (facultyId,fName,lName,email,isActive,isFaculty,phone,about,education,highlights,departmentId,roomNumber,title,secondaryDepartmentId) values (:id,:fname,:lname,:email,:active,:faculty,:phone,:about,:edu,:highlights,:deptId,:roomNum,:title,:secDeptId)",array(
	":id"=>$id,
	":fname"=>$fname,
	":lname"=>$lname,
	":email"=>$email,
	":deptId"=>$deptId,
	":active"=>$active,
	":faculty"=>$faculty,
	":phone"=>$phone,
	":about"=>$about,
	":edu"=>$edu,
	":highlights"=>$highlights,
	":deptId"=> $deptId,
	":roomNum"=>$roomNum,
	":title"=>$title,
	"secDeptId"=>$secDeptId
	));
}

public function delete(){
	//delete
	$this->conn->setData("DELETE from Employees where facultyId = :id",array(
	":id"=> $this->facultyId
	));
}

}
?>