<?php
	require_once("util.php");
	require_once("commonAuth.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$allowed = true;

	if(!$allowed) {
			header("Location: ../public/notAuthorized.html");
			die("Redirecting to notAuthorized.html");
		}

	class floorPlan {

		private $fpId = "";
		private $imagePath = "";
		private $name = "";
		private $conn;

			function __construct($conn,$fpId) {
				$this->conn = $conn;
				$this->fpId = $fpId;
			}
			
			
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

		public function getfpId() {
			return $this->fpId;
		} 

		public function setfpId($fpId) {
			$this->fpId = $fpId;
		}

		public function getImagePath() {
			return $this->imagePath;
		} 

		public function setImagePath($imagePath) {
			$this->imagePath = $imagePath;
		}

		public function getName() {
			return $this->name;
		}

		public function setName($name) {
			$this->name = $name;
		}

		public function putParams($name, $imagePath) {
			//update
			$this->conn->setData("UPDATE floorPlan SET name=:name, imagePath = :imagePath WHERE fpId=:fpId;",array(
			":name"=>$name,
			":imagePath"=> $imagePath,
			":fpId"=>$this->fpId
			));
		}

		public function put(){
			//update
			$this->conn->setData("UPDATE floorPlan SET name=:name, imagePath = :imagePath WHERE fpId=:fpId;",array(
			":name"=>$this->name,
			":imagePath"=> $this->imagePath,
			":fpId"=>$this->fpId
			));
		}

		public function postParams($imagePath, $name){
			$this->conn->setData("INSERT into floorPlan (fpId, imagePath, name) values (DEFAULT, :imagePath, :name)",array(
			":imagePath"=>$imagePath,
			":name"=>$name,
			));
		}

		public function post(){
			$this->conn->setData("INSERT into floorPlan (fpId, imagePath, name) values (DEFAULT, :imagePath, :name);",array(
			":imagePath"=>$this->imagePath,
			":name"=>$this->name,
			));
		}

		public function delete(){
			//delete
			$this->conn->setData("DELETE FROM floorPlan where fpId = :fpId",array(
			":fpId"=> $this->fpId
			));
		}

	}
?>