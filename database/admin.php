<?php
require_once("util.php");
//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT 
$allowed = true;

if(!$allowed) { //Authentication - Users must exist in the system to access this page
	header("Location: ../public/notAuthorized.html");
       die("Redirecting to notAuthorized.html");
}

/**
 * A Data Layer Object Class to represent an Admin user object in the database
 */
class admin {

	//Admin class attibutes
	private $adminId = "";
	private $fname = "";
	private $lname = "";
	private $user = "";
	private $accessLvl = "";
	private $deptId = "";
	private $conn;

	/**
	 * A constructor for the admin Data Layer Object 
	 * @param $conn The connection to the database
	 * @param $adminId The administative ID (used to fetch/create the Object)
	 */
	function __construct($conn,$adminId = null){
		$this->conn = $conn;
		$this->adminId = $adminId;
	}
	
	/**
	 * A function to get information from the database and set the attributes of the Data Object based on those results
	 * @return true/false Return whether or not the fetch successfully occured
	 */	
	public function fetch(){
		$resultsArr = $this->conn->getData("SELECT * FROM Admin WHERE adminId = :id",array(
			":id" => $this->adminId
			)
		);
		try{
			$results = $resultsArr[0];
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

	/**
	 * Accessor for the Admin ID
	 * @return $adminID The Admin User's ID
	 */
	public function getAdminId(){
		return $this->adminId;
	}

	/**
	 * Mutator for the Admin ID
	 * @param $adminID The Admin User's ID
	 */
	public function setAdminId($aId){
		$this->adminId = $aId;
	}

	/**
	 * Accessor for the Admin First Name
	 * @return $fname The admin's first name
	 */
	public function getAdminFName(){
		return $this->fname;
	}

	/**
	 * Mutator for the Admin First Name
	 * @param $fname The admin's first name
	 */
	public function setAdminFName($fname){
		$this->fname = $fname;
	}

	/**
	 * Accessor for the Admin's Last name
	 * @return $lname The Admin User's Last name
	 */
	public function getAdminLName(){
		return $this->lname;
	}

	/**
	 * Mutator for the Admin's Last name
	 * @param $lname The Admin User's Last name
	 */
	public function setAdminLName($lname){
		$this->lname = $lname;
	}

	/**
	 * Accessor for the Admin's username
	 * @return $user The Admin User's username
	 */
	public function getUsername(){
		return $this->user;
	}

	/**
	 * Mutator for the Admin's username
	 * @param $user The Admin User's username
	 */
	public function setUsername($user){
		$this->user = $user;
	}

	/**
	 * Accessor for the Admin's Access Level
	 * @return $accessLvl The Admin User's access level
	 */
	public function getAccessLevel(){
		return $this->accessLvl;
	}

	/**
	 * Mutator for the Admin's Access Level
	 * @param $accessLvl The Admin User's access level
	 */
	public function setAccessLevel($accessLvl){
		$this->accessLvl = $accessLvl;
	}

	/**
	 * Accessor for the Admin's Department ID
	 * @return $deptId The Admin User's Department ID
	 */
	public function getDeptId(){
		return $this->deptId;
	} 

	/**
	 * Mutator for the Admin's Department ID
	 * @param $dId The Admin User's Department ID
	 */
	public function setDeptId($dId){
		$this->deptId = $dId;
	}

	/**
	 * Method to accept parameters and update an admin user in the database with those values
	 * @param $fname The Administrative User's first name
	 * @param $lname The Administrative User's last name
	 * @param $user The Administrative User's RIT username
	 * @param $accessLevel The Administrative User's Access Level
	 * @param $deptId The Administrative User's Deaprtment ID
	 */
	function putParams($fname, $lname, $user, $accessLvl, $deptId){
		//update
		checkName($fname);
		checkName($lname);
		$this->conn->setData("UPDATE Admin SET fName=:fname, lName=:lname, username=:user, accessLevel=:accessLvl, departmentId=:deptId WHERE adminId = :id;",array(
		":fname"=>$fname,
		":lname"=>$lname,
		":user"=>$user,
		":accessLvl"=>$accessLvl,
		":deptId"=>$deptId,
		":id"=>$this->adminId //The admin ID is set upon creation of the Data Layer object, and doesn't need to be passed in
		));
	}

	/**
	 * A function to update an admin user in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are changed within the Data Layer Object class using the class mutators
	 */ 
	function put(){
		//update
		checkName($this->fname);
		checkName($this->lname);
		$this->conn->setData("UPDATE Admin SET fName=:fname, lName=:lname, username=:user, accessLevel=:accessLvl, departmentId=:deptId WHERE adminId = :id;",array(
		":fname"=>$this->fname,
		":lname"=>$this->lname,
		":user"=>$this->user,
		":accessLvl"=>$this->accessLvl,
		":deptId"=>$this->deptId,
		":id"=>$this->adminId
		));
	}

	/**
	 * Method to accept parameters and create a new admin user in the database with those values
	 * @param $fname The Administrative User's first name
	 * @param $lname The Administrative User's last name
	 * @param $user The Administrative User's RIT username
	 * @param $accessLevel The Administrative User's Access Level
	 * @param $deptId The Administrative User's Deaprtment ID
	 */
	public function postParams($fname, $lname, $user, $accessLvl, $deptId){
		//insert - The adminID is set to auto-increment, so DEFAULT will call the next number in the incrementing
		checkName($fname);
		checkName($lname);
		$this->conn->setData("INSERT into Admin (adminId,fName,lName,username,accessLevel,departmentId) values (DEFAULT,:fname,:lname,:user,:accessLvl,:deptId)",array(
		":fname"=>$fname,
		":lname"=>$lname,
		":user"=>$user,
		":accessLvl"=>$accessLvl,
		":deptId"=>$deptId
		));
	}

	/**
	 * A function to create an admin user in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are set within the Data Layer Object class using the class mutators
	 */ 
	public function post(){
		//insert
		checkName($this->fname);
		checkName($this->lname);
		$this->conn->setData("INSERT into Admin (adminId,fName,lName,username,accessLevel,departmentId) values (DEFAULT,:fname,:lname,:user,:accessLvl,:deptId)",array(
		":fname"=>$this->fname,
		":lname"=>$this->lname,
		":user"=>$this->user,
		":accessLvl"=>$this->accessLvl,
		":deptId"=>$this->deptId
		));
	}

	/**
	 * A function to delete an Admin user's information from the database
	 */
	public function delete(){
		//delete
		$this->conn->setData("DELETE from Admin where adminId = :id",array(
		":id"=> $this->adminId //The id is inputted upon creation of the Data Layer object, and doesn't need to be passed in
		));
	}

}
?>