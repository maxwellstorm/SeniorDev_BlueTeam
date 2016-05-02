<?php
//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
$allowed = true;

require_once("util.php");

if(!$allowed) { //Authentication - Users must exist in the system to access this page
	header("Location: ../public/notAuthorized.html");
    die("Redirecting to notAuthorized.html");
}

/**
  * A Data Layer Object Class to represent a Department object in the database
 */
class department {

	//Department Class Attributes
	private $deptId = "";
	private $deptName = "";
	private $deptAbbr = "";
	private $conn;

	/**
	 * A constructor for the department Data Layer Object 
	 * @param $conn The connection to the database
	 * @param $deptId The Department ID (used to fetch/create the Object)
	 */
	function __construct($conn,$deptId) {
		$this->conn = $conn;
		$this->deptId = $deptId;
	}
		
	/**
	 * A function to get information from the database and set the attributes of the Data Object based on those results
	 * @return true/false Return whether or not the fetch successfully occured
	 */	
	public function fetch() {
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

	/**
	 * Accessor for the Department's ID Number
	 * @return $deptId The department's ID Number
	 */
	public function getDeptId() {
		return $this->deptId;
	} 

	/**
	 * Mutator for the Department's ID Number
	 * @param $id The department's ID Number
	 */
	public function setDeptId($id) {
		$this->deptId = $id;
	}

	/**
	 * Accessor for the Department's Name
	 * @return $deptName The department's name
	 */
	public function getDeptName() {
		return $this->deptName;
	} 

	/**
	 * Mutator for the Department's Name
	 * @param $name The department's name
	 */
	public function setDeptName($name) {
		$this->deptName = $name;
	}

	/**
	 * Accessor for the Department's Abbreviation
	 * @return $deptAbbr The department's abbreviation
	 */
	public function getDeptAbbr() {
		return $this->deptAbbr;
	}

	/**
	 * Mutator for the Department's Abbreviation
	 * @param $abbr The department's abbreviation
	 */
	public function setDeptAbbr($abbr) {
		$this->deptAbbr = $abbr;
	}

	/**
	 * Method to accept parameters and update a department in the database with those values
	 * @param $name The department's name
	 * @param $abbr The department's abbreviation
	 */
	public function putParams($name, $abbr) {
		//update
		$this->conn->setData("UPDATE department SET departmentName=:name, departmentAbbr=:abbr WHERE departmentId = :id",array(
		":name"=>$name,
		":abbr"=>$abbr,
		":id"=> $this->deptId //The department ID is set on creation of the Object & doesn't need to be passed in
		));
	}

	/**
	 * A function to update a department in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are changed within the Data Layer Object class using the class mutators
	 */ 
	public function put(){
		$this->conn->setData("UPDATE department SET departmentName=:name, departmentAbbr=:abbr WHERE departmentId = :id",array(
		":name"=>$this->deptName,
		":abbr"=>$this->deptAbbr,
		":id"=> $this->deptId
		));
	}

	/**
	 * Method to accept parameters and create a department in the database with those values
	 * @param $name The department's name
	 * @param $abbr The department's abbreviation
	 */
	public function postParams($name, $abbr) {
		//insert - Department is set to use an auto_incrementing id, so the DEFAULT will call the next highest number for a new value
		$this->conn->setData("INSERT into department (departmentId,departmentName,departmentAbbr) values (DEFAULT,:name,:abbr)",array(
		":name"=>$name,
		":abbr"=>$abbr
		));
	}

	/**
	 * A function to create a department in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are set within the Data Layer Object class using the class mutators
	 */ 
	public function post(){
		$this->conn->setData("INSERT into department (departmentId,departmentName,departmentAbbr) values (DEFAULT,:name,:abbr)",array(
		":name"=>$this->deptName,
		":abbr"=>$this->deptAbbr
		));
	}

	/**
	 * A function to delete a department's information from the database
	 */
	public function delete(){
		//delete
		$this->conn->setData("DELETE from department where departmentId = :id",array(
		":id"=> $this->deptId
		));
	}
}
?>