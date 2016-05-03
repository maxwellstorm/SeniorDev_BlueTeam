<?php

require_once("util.php");

$id = $_SERVER["uid"];	
$allowed = isAllowed($id);
	
if(!$allowed) { //Authentication - Users must exist in the system to access this page
	header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
}

/**
 * A Data Layer Object Class to represent an Employee in the Database
 */
class employees{
	//Employee class attributes
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

    /**
	 * A constructor for the Employee Data Layer Object 
	 * @param $conn The connection to the database
	 * @param $facId The Faculty ID (used to fetch/create the Object)
	 */
	function __construct($conn,$facId = null){
		$this->conn = $conn;
		$this->facultyId = $facId;
	}

	/**
	 * A function to get information from the database and set the attributes of the Data Object based on those results
	 * @return true/false Return whether or not the fetch successfully occured
	 */	
	public function fetch() {
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

	/**
	 * Accessor for the Employee ID
	 * @return $facultyID The Employee's ID (called faculty ID because this class was created when the scope of the project encompassed only faculty)
	 */
	public function getEmployeeId(){
		return $this->facultyId;
	} 

	/**
	 * Mutator for the Employee ID
	 * @param $id The Employee's ID
	 */
	public function setEmployeeId($id){
		$this->facultyId = $id;
	}

	/**
	 * Accessor for the Employee's First Name
	 * @return $fName The employee's first name
	 */
	public function getEmpFName(){
		return $this->fName;
	}

	/**
	 * Mutator for the Employee's First Name
	 * @param $fName The employee's first name
	 */
	public function setEmpFName($fname){
		$this->fName = $fname;
	}

	/**
	 * Accessor for the Employee's Last Name
	 * @return $lName The employee's last name
	 */
	public function getEmpLName(){
		return $this->lName;
	}

	/**
	 * Mutator for the Employee's Last Name
	 * @param $lName The employee's last name
	 */
	public function setEmpLName($lname){
		$this->lName = $lname;
	}

	/**
	 * Accessor for the Employee's Room Number
	 * @return $roomNumber The employee's room number
	 */
	public function getRoomNum(){
		return $this->roomNumber;
	}

	/**
	 * Mutator for the Employee's Room Number
	 * @param $roomNum The employee's room number
	 */
	public function setRoomNum($roomNum){
		$this->roomNum = $roomNum;
	}

	/**
	 * Accessor for the Employee's Email
	 * @return $email The employee's email
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * Mutator for the Employee's Email
	 * @param $email The employee's email
	 */
	public function setEmail($email){
		$this->email = $email;
	}

	/**
	 * Accessor for the Employee's Headshot image
	 * @return $imageName The employee's headship image file path
	 */
	public function getImageName() {
		return $this->imageName;
	}

	/**
	 * Mutator for the Employee's Headshot image
	 * @param $imageName The employee's headship image file path
	 */
	public function setImageName($imageName) {
		$this->imageName = $imageName;
	}

	/**
	 * Accessor for the Employee's primary department ID
	 * @return $departmentId The employee's primary department ID
	 */
	public function getDepartmentId(){
		return $this->departmentId;
	}

	/**
	 * Mutator for the Employee's primary department ID
	 * @param $deptId The employee's primary department ID
	 */
	public function setDepartmentId($deptId){
		$this->deptId = $deptId;
	}

	/**
	 * Accessor for the Employee's Active Status
	 * The possible values are 1 (Active) and 0 (not Active)
	 * @return $isActive The employee's active status
	 */
	public function getActive(){
		return $this->isActive;
	}

	/**
	 * Mutator for the Employee's Active Status
	 * The possible values are 1 (Active) and 0 (not Active)
	 * @param $active The employee's active status
	 */
	public function setActive($active){
		$this->isActive = $active;
	}

	/**
	 * Accessor for the Employee's faculty status
	 * The possible values are 1 (faculty) and 0 (staff)
	 * @return $isFaculty The employee's faculty status
	 */
	public function getFaculty(){
		return $this->isFaculty;
	}

	/**
	 * Mutator for the Employee's faculty status
	 * The possible values are 1 (faculty) and 0 (staff)
	 * @param $faculty The employee's faculty status
	 */
	public function setFaculty($faculty){
		$this->isFaculty = $faculty;
	}

	/**
	 * Accessor for the Employee's Phone Number
	 * @return $phone The employee's phone Number
	 */
	public function getPhone(){
		return $this->phone;
	}

	/**
	 * Mutator for the Employee's Phone Number
	 * @param $phone The employee's phone Number
	 */
	public function setPhone($phone){
		$this->phone = $phone;
	}

	/**
	 * Accessor for the Employee's "About" Information
	 * @return $about The employee's "About" Information
	 */
	public function getAbout(){
		return $this->about;
	}

	/**
	 * Mutator for the Employee's "About" Information
	 * @param $about The employee's "About" Information
	 */
	public function setAbout($about){
		$this->about = $about;
	}

	/**
	 * Accessor for the Employee's Education Information
	 * @return $education The employee's Education Information
	 */
	public function getEducation(){
		return $this->education;
	}

	/**
	 * Mutator for the Employee's "About" Information
	 * @param $about The employee's "About" Information
	 */
	public function setEducation($education){
		$this->education = $education;
	}

	/**
	 * Accessor for the Employee's Highlights Information
	 * @return $highlights The employee's highlights Information
	 */
	public function getHighlights(){
		return $this->highlights;
	}

	/**
	 * Mutator for the Employee's Highlights Information
	 * @param $highlights The employee's highlights Information
	 */
	public function setHighlights($highlights){
		$this->highlights = $highlights;
	}

	/**
	 * Accessor for the Employee's Title
	 * @return $title The employee's title
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 * Mutator for the Employee's Title
	 * @param $title The employee's title
	 */
	public function setTitle($title){
		$this->title = $title;
	}

	/**
	 * Accessor for the Employee's Secondary Department ID
	 * @return $secondaryDepartmentId The employee's secondary department ID
	 */
	public function getSecDeptId(){
		return $this->secondaryDepartmentId;
	}

	/**
	 * Mutator for the Employee's Secondary Department ID
	 * @param $secDeptId The employee's secondary department ID
	 */
	public function setSecDeptId($secDeptId){
		$this->secondaryDepartmentId = $secDeptId;
	}

	/**
	 * Method to accept parameters and update an Employee in the database with those values
	 * @param $fName The Employee's first name
	 * @param $lName The Employee's last name
	 * @param $email The Employee's email address
	 * @param $active The Employee's active/not active status
	 * @param $faculty The Employee's faculty/staff status
	 * @param $phone The Employee's phone number
	 * @param $about The Employee's "About" information
	 * @param $edu The Employee's "Education" information 
	 * @param $highlights The Employee's "Highlights" information
	 * @param $deptId The Employee's primary department ID
	 * @param $roomNum The Employee's office room number
	 * @param $title The Employee's title
	 * @param $secDeptId The Employee's secondary department ID
	 * @param $imageName The Employee's headshot image path
	 */
	public function putParams($fname,$lname,$email,$active,$faculty,$phone,$about,$edu,$highlights,$deptId,$roomNum,$title,$secDeptId, $imageName){
		//update
		checkRoom($roomNum);
		checkName($fname);
		checkName($lname);
		checkEmail($email);

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
		":id"=>$this->getEmployeeId() //Employee ID Passed in on creation
		));
	}

	/**
	 * A function to update an employee in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are changed within the Data Layer Object class using the class mutators
	 */
	public function put(){
		checkRoom($this->roomNumber);
		checkName($this->fname);
		checkName($this->lname);
		checkEmail($this->email);
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

	/**
	 * Function to accept parameters and create a new Employee in the database with those values
	 * @param $fName The Employee's first name
	 * @param $lName The Employee's last name
	 * @param $email The Employee's email address
	 * @param $active The Employee's active/not active status
	 * @param $faculty The Employee's faculty/staff status
	 * @param $phone The Employee's phone number
	 * @param $about The Employee's "About" information
	 * @param $edu The Employee's "Education" information 
	 * @param $highlights The Employee's "Highlights" information
	 * @param $deptId The Employee's primary department ID
	 * @param $roomNum The Employee's office room number
	 * @param $title The Employee's title
	 * @param $secDeptId The Employee's secondary department ID
	 * @param $imageName The Employee's headshot image path
	 */
	public function postParams($fname,$lname,$email,$active,$faculty,$phone,$about,$edu,$highlights,$deptId,$roomNum,$title,$secDeptId, $imageName){
		//insert
		checkName($fname);
		checkName($lname);
		checkEmail($email);
		checkRoom($roomNum);
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

	/**
	 * A function to create an employee in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are set within the Data Layer Object class using the class mutators
	 */
	public function post(){
		checkRoom($this->roomNumber);
		checkName($this->fname);
		checkName($this->lname);
		checkEmail($this->email);
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

	/**
	 * A function to delete an Employee's information from the database
	 */
	public function delete(){
		//delete
		$this->conn->setData("DELETE from Employees where facultyId = :id",array(
		":id"=> $this->facultyId
		));
	}
}
?>