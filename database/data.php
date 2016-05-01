<?php
require_once("dbException.php");
require_once("commonAuth.php");




//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
//$allowed = true;



class data{


	private $server = "facultydb.cdh6zybsklle.us-east-1.rds.amazonaws.com";
	private $username = "maxwellsweikert";
	private $password = "M3312140m";
	private $DBName = "facultyDb";
	private $conn;
	
	function __construct(){
			$this->connect();
		
	}
	
	function connect(){
		try {
			$conn = new PDO('mysql:host='.$this->server.';dbname='.$this->DBName, $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			$this->conn = $conn;
			}
		catch(PDOException $e) {
			throw new dbException("Connection failed: " . $e->getMessage(),0);
		}
		catch(Exception $e){
			throw new dbException("An unexpected error occured while connecting to the datbase" . $e->getMessage(),0);
		}
		
	}
	
	function close(){
		$this->conn = null;
	}
	
	//return back data used for select statments only
	function getData($sql,$params){
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($params);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(Exception $e){
			throw new dbException("Get Data Failed " . $e->getMessage(),0);
		}
	}
	
	
	function setData($sql,$params){
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($params);
		}
		catch(Exception $e){
			throw new dbException("Set Data Failed " . $e->getMessage(),0);
		}
	}
	

	

	
	function startTransaction(){
		$this->conn->beginTransaction();
	}
	
	function commit(){
		$this->conn->commit();
	}
	
	function rollBack(){
		//this ends transactions as well
		$this->conn->rollBack();
	}
	
	
	
	
}

?>

