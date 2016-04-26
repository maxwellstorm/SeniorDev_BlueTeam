<?php
//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
$allowed = true;

require_once("commonAuth.php");

if(!$allowed) {
	header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
}

require_once("util.php");
class employees{
	

	public $title;
	public $secDeptId;
	public $fname = null;
	public $lname;
	public $email;
	public $roomNumber;
	public $departmentId;
	public $isActive;
	public $isFaculty;
	public $phone;
	public $about;
	public $education;
	public $highlights;
	public $imageName;
    private $facultyId;

	



	function __construct($conn,$facId = null){
		$this->conn = $conn;
		$this->facultyId = $facId;
	}

public function fetch(){
	$resultsArr = $this->conn->getData("SELECT * FROM Employees WHERE facultyId = :id",array(
		":id" => $this->facultyId
		)
	);
	try{
		$results = $resultsArr[0];
		$this->fname = $results['fName'];
		$this->lname = $results['lName'];
		$this->roomNumber = $results['roomNumber'];
		$this->email = $results['email'];
		$this->departmentId = $result['departmentId'];
		$this->isActive = $results['isActive'];
		$this->isFaculty = $results['isFaculty'];
		$this->phone = $results['phone'];
		$this->about = $results['about'];
		$this->education = $results['education'];
		$this->highlights = $results['highlights'];
		$this->imageName = $results['imageName'];
		$this->secDeptId = $results['secondaryDepartmentID'];
		$this->title = $results['title'];
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

public function getImageName() {
	return $this->imageName;
}

public function setImageName($imageName) {
	$this->imageName = $imageName;
}

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

public function getTitle(){
	return $this->title;
}

public function setTitle($title){
	$this->title = $title;
}

public function getSecDeptId(){
	return $this->secondaryDepartmentId;
}

public function setSecDeptId($secDeptId){
	$this->secondaryDepartmentId = $secDeptId;
}

public function putParams($fname,$lname,$email,$active,$faculty,$phone,$about,$edu,$highlights,$deptId,$roomNum,$title,$secDeptId, $imageName){
	//update
	util::checkRoom($roomNum);
	util::checkName($fname);
	util::checkName($lname);
	util::checkEmail($email);
	$this->conn->setData("UPDATE Employees SET fName=:fname, lName=:lname, email=:email, isActive=:active, isFaculty=:faculty, phone=:phone, about=:about, education=:edu, highlights=:highlights, departmentId=:deptId, roomNumber=:roomNum, title=:title, secondaryDepartmentId=:secDeptId, imageName=:imageName  WHERE facultyId = :id",array(
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
	":id"=> $this->departmentId,
	":roomNum"=>$roomNum,
	":title"=>$title,
	":secDeptId"=>$secDeptId,
	":imageName"=>$imageName,
	":id"=>$this->getEmployeeId()
	));
}

public function put(){
	util::checkRoom($this->roomNumber);
	util::checkName($this->fname);
	util::checkName($this->lname);
	util::checkEmail($this->email);
	$this->conn->setData("UPDATE Employees SET fName=:fname, lName=:lname, email=:email, isActive=:active, isFaculty=:faculty, phone=:phone, about=:about, education=:edu, highlights=:highlights, departmentId=:deptId, roomNumber=:roomNum, title=:title, secondaryDepartmentId=:secDeptId, imageName=:imageName  WHERE facultyId = :id",array(
	":fname"=>$this->fname,
	":lname"=>$this->lname,
	":email"=>$this->email,
	":deptId"=>$this->departmentId,
	":active"=>$this->isActive,
	":faculty"=>$this->isFaculty,
	":phone"=>$this->phone,
	":about"=>$this->about,
	":edu"=>$this->education,
	":highlights"=>$this->highlights,
	":id"=> $this->departmentId,
	":roomNum"=>$this->roomNumber,
	":title"=>$this->title,
	":secDeptId"=>$this->secDeptId,
	":imageName"=>$this->imageName,
	":id"=>$this->getEmployeeId()
	));
}

public function postParams($fname,$lname,$email,$active,$faculty,$phone,$about,$edu,$highlights,$deptId,$roomNum,$title,$secDeptId, $imageName){
	//insert
	util::checkName($fname);
	util::checkName($lname);
	util::checkEmail($email);
	util::checkRoom($roomNum);
	$this->conn->setData("INSERT into Employees (facultyId,fName,lName,email,isActive,isFaculty,phone,about,education,highlights,departmentId,roomNumber,title,secondaryDepartmentId, imageName) values (DEFAULT,:fname,:lname,:email,:active,:faculty,:phone,:about,:edu,:highlights,:deptId,:roomNum,:title,:secDeptId, :imageName)",array(
	":fname"=>$fname,
	":lname"=>$lname,
	":email"=>$email,
	":active"=>$active,
	":faculty"=>$faculty,
	":phone"=>$phone,
	":about"=>$about,
	":edu"=>$edu,
	":highlights"=>$highlights,
	":deptId"=> $deptId,
	":roomNum"=>$roomNum,
	":title"=>$title,
	":secDeptId"=>$secDeptId,
	":imageName"=>$imageName
	));
}

public function post(){
	util::checkRoom($this->roomNumber);
	util::checkName($this->fname);
	util::checkName($this->lname);
	util::checkEmail($this->email);
	$this->conn->setData("INSERT into Employees (facultyId,fName,lName,email,isActive,isFaculty,phone,about,education,highlights,departmentId,roomNumber,title,secondaryDepartmentId, imageName) values (DEFAULT, :fname,:lname,:email,:active,:faculty,:phone,:about,:edu,:highlights,:deptId,:roomNum,:title,:secDeptId, :imageName)",array(
	":fname"=>$this->fname,
	":lname"=>$this->lname,
	":email"=>$this->email,
	":active"=>$this->isActive,
	":faculty"=>$this->isFaculty,
	":phone"=>$this->phone,
	":about"=>$this->about,
	":edu"=>$this->education,
	":highlights"=>$this->highlights,
	":deptId"=>$this->departmentId,
	":roomNum"=>$this->roomNumber,
	":title"=>$this->title,
	":secDeptId"=>$this->secDeptId,
	":imageName"=>$this->imageName
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