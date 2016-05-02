<?php
require_once("util.php");

//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
$allowed = true;

if(!$allowed) { //Authentication - Users must exist in the system to access this page
	header("Location: ../public/notAuthorized.html");
	die("Redirecting to notAuthorized.html");
}

/**
  * A Data Layer Object Class to represent a Room object in the database
 */
class room{
	//Room class attributes
	private $roomNumber = "";
	private $roomMap = "";
	private $description = "";
	private $posX;
	private $posY;
	private $conn;

	/**
	 * A constructor for the room Data Layer Object 
	 * @param $conn The connection to the database
	 * @param $roomNum The Room Number (used to fetch/create the Object)
	 */
	function __construct($conn,$roomNum = null){
		$this->conn = $conn;
		$this->roomNumber = $roomNum;
	}
		
	/**
	 * A function to get information from the database and set the attributes of the Data Object based on those results
	 * @return true/false Return whether or not the fetch successfully occured
	 */	
	public function fetch(){
		$resultsArr = $this->conn->getData("select * from room where roomNumber = :number",array(
			":number" => $this->roomNumber
			)
		);
		try{
			$results = $resultsArr[0];
			$this->roomMap = $results['roomMap'];
			$this->description = $results['description'];
			$this->posX = $results['posX'];
			$this->posY = $results['posY'];
			return true;
		}
		catch(Exception $e){
			return false;
		}
		
	}

	/**
	 * Accessor the for Room's Room Number
	 * @return $roomNumber The Room Number
	 */
	public function getRoomNum(){
		return $this->roomNumber;
	} 

	/**
	 * Mutator the for Room's Room Number
	 * @param $num The Room Number
	 */
	public function setRoomNum($num){
		$this->roomNumber = $num;
	}

	/**
	 * Accessor for the Room's Map
	 * @return $roomMap The Room's Map Image
	 */
	public function getRoomMap(){
		return $this->roomMap;
	}

	/**
	 * Mutator the for Room's Map Image
	 * @param $map The Room Number
	 */
	public function setRoomMap($map){
		$this->roomMap = $map;
	}

	/**
	 * Accessor the for Room's Description
	 * @return $description The Room's Description
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * Mutator the for Room's Description
	 * @param $desc The Room's description
	 */
	public function setDescription($desc){
		$this->description = $desc;
	}

	/**
	 * Accessor the for Room's X coordinate relative to it's map image
	 * @return $posX The x-coordinate
	 */
	public function getPosX() {
		return $this->posX;
	}

	/**
	 * Mutator the for Room's X coordinate relative to it's map image
	 * @param $posX The x-coordinate
	 */
	public function setPosX($posX) {
		$this->posX = $posX;
	}

	/**
	 * Accessor the for Room's Y coordinate relative to it's map image
	 * @return $posY The y-coordinate
	 */
	public function getPosY() {
		return $this->posY;
	}

	/**
	 * Mutator the for Room's Y coordinate relative to it's map image
	 * @param $posY The y-coordinate
	 */
	public function setPosY($posY) {
		$this->posY = $posY;
	}

	/**
	 * Method to accept parameters and update a room in the database with those values
	 * @param $map The room's image path for the associated map image
	 * @param $desc The room's description
	 * @param $posX The room's X-Coordinate relative to the associated map image
	 * @param $poxY The room's Y-Coordinate relative to the associated map image
	 */
	public function putParams($map, $desc, $posX, $posY){
		//update
		checkRoom($this->roomNumber);
		$this->conn->setData("UPDATE room SET roomMap=:map, description=:roomDesc, posX=:posX, posY=:posY WHERE roomNumber = :num",array(
		":map"=>$map,
		":roomDesc"=>$desc,
		":posX"=>$posX,
		":posY"=>$posY,
		":num"=> $this->roomNumber //Room number is set on Data Layer object creation
		));
	}

	/**
	 * A function to update a room in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are changed within the Data Layer Object class using the class mutators
	 */
	public function put(){
		//update
		checkRoom($this->roomNumber);
		$this->conn->setData("UPDATE room SET roomMap=:map, description=:roomDesc, posX=:posX, posY=:posY WHERE roomNumber = :num",array(
		":map"=>$this->roomMap, 
		":roomDesc"=>$this->description,
		":posX"=>$this->posX,
		":posY"=>$this->posY,
		":num"=>$this->roomNumber
		));
	}

	/**
	 * Method to accept parameters and create a room in the database with those values
	 * @param $num The room number
	 * @param $map The room's image path for the associated map image
	 * @param $desc The room's description
	 * @param $posX The room's X-Coordinate relative to the associated map image
	 * @param $poxY The room's Y-Coordinate relative to the associated map image
	 */
	public function postParams($num, $map, $desc, $posX, $posY){
		//insert
		checkRoom($num);
		$this->conn->setData("INSERT into room (roomNumber,roomMap,description, posX, posY) values (:num,:map,:rDesc, :posX, :posY)",array(
		":num"=>$num,
		":map"=>$map,
		":rDesc"=>$desc,
		":posX"=>$posx,
		":posY"=>$posY
		));
	}

	/**
	 * A function to create a room in the database with values from the Data Layer Object Attributes
	 * This should be called if individual elements are set within the Data Layer Object class using the class mutators
	 */
	public function post(){
		//insert
		checkRoom($this->roomNumber);
		$this->conn->setData("INSERT into room (roomNumber, roomMap, description, posX, posY) values (:num, :map, :rDesc, :posX, :posY);",array(
		":num"=>$this->roomNumber,
		":map"=>$this->roomMap,
		":rDesc"=>$this->description,
		":posX"=>$this->posX,
		":posY"=>$this->posY
		));
	}

	/**
	 * A function to delete a room's information from the database
	 */
	public function delete(){
		//delete
		$this->conn->setData("DELETE from room where roomNumber = :num",array(
		":num"=> $this->roomNumber
		));
	}
}
?>