<?php
	require_once("util.php");

	
	$id = $_SERVER["uid"];	
	$allowed = isAllowed($id);

	if(!$allowed) { //Authentication - Users must exist in the system to access this page
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	/**
  	 * A Data Layer Object Class to represent a floor plan object in the database
 	 */
	class floorPlan {

		//floorPlan class Attributes
		private $fpId = "";
		private $imagePath = "";
		private $name = "";
		private $conn;

		/**
		 * A constructor for the floor plan Data Layer Object 
		 * @param $conn The connection to the database
		 * @param $fpId The Floor Plan ID (used to fetch/create the Object)
		 */
		function __construct($conn,$fpId) {
			$this->conn = $conn;
			$this->fpId = $fpId;
		}
			
		/**
		 * A function to get information from the database and set the attributes of the Data Object based on those results
		 * @return true/false Return whether or not the fetch successfully occured
	 	 */	
		public function fetch() {
			$resultsArr = $this->conn->getData("select * from floorPlan where fpId = :fpId",array(
				":fpId" => $this->fpId
				)
			);
			try {
				$results = $resultsArr[0];
				$this->imagePath = $results['imagePath'];
				$this->name = $results['name'];
				return true;
			}
			catch(Exception $e) {
				return false;
			}
			
		}

		/**
		 * Access for the floor plan ID
		 * @return $fpId The floor plan's ID number
		 */
		public function getfpId() {
			return $this->fpId;
		} 

		/**
		 * Mutator for the floor plan ID
		 * @param $fpId The floor plan's ID number
		 */
		public function setfpId($fpId) {
			$this->fpId = $fpId;
		}

		/**
		 * Access for the floor plan's Image path
		 * @return $imagePath The floor plan's image path
		 */
		public function getImagePath() {
			return $this->imagePath;
		} 

		/**
		 * Mutator for the floor plan's Image path
		 * @param $imagePath The floor plan's image path
		 */
		public function setImagePath($imagePath) {
			$this->imagePath = $imagePath;
		}

		/**
		 * Access for the floor plan's name
		 * @return $name The floor plan's name
		 */
		public function getName() {
			return $this->name;
		}

		/**
		 * Mutator for the floor plan's name
		 * @param $name The floor plan's name
		 */
		public function setName($name) {
			$this->name = $name;
		}

		/**
		 * Method to accept parameters and update a floor plan in the database with those values
		 * @param $name The floor plan's name
		 * @param $imagePath The floor plan's image path
		 */
		public function putParams($name, $imagePath) {
			//update
			$this->conn->setData("UPDATE floorPlan SET name=:name, imagePath = :imagePath WHERE fpId=:fpId;",array(
			":name"=>$name,
			":imagePath"=> $imagePath,
			":fpId"=>$this->fpId //ID is provided upon DL Object creation
			));
		}

		/**
		 * A function to update a floor plan in the database with values from the Data Layer Object Attributes
		 * This should be called if individual elements are changed within the Data Layer Object class using the class mutators
		 */ 
		public function put(){
			//update
			$this->conn->setData("UPDATE floorPlan SET name=:name, imagePath = :imagePath WHERE fpId=:fpId;",array(
			":name"=>$this->name,
			":imagePath"=> $this->imagePath,
			":fpId"=>$this->fpId
			));
		}

		/**
		 * Method to accept parameters and create a new floor plan in the database with those values
		 * @param $imagePath The floor plan's image path
		 * @param $name The floor plan's name
		 */
		public function postParams($imagePath, $name){
			$this->conn->setData("INSERT into floorPlan (fpId, imagePath, name) values (DEFAULT, :imagePath, :name)",array(
			":imagePath"=>$imagePath,
			":name"=>$name,
			));
		}

		/**
		 * A function to create a department in the database with values from the Data Layer Object Attributes
		 * This should be called if individual elements are set within the Data Layer Object class using the class mutators
		 */ 
		public function post(){
			$this->conn->setData("INSERT into floorPlan (fpId, imagePath, name) values (DEFAULT, :imagePath, :name);",array(
			":imagePath"=>$this->imagePath,
			":name"=>$this->name,
			));
		}

		/**
		 * A function to delete a floor plan's information from the database
		 */
		public function delete(){
			//delete
			$this->conn->setData("DELETE FROM floorPlan where fpId = :fpId",array(
			":fpId"=> $this->fpId
			));
		}

	}
?>