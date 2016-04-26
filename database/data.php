<?php
require_once("commonAuth.php");

//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
$allowed = true;

if(!$allowed) {
	header("Location: ../public/notAuthorized.html");
    die("Redirecting to notAuthorized.html");
}

class data{
	
	private $server = 'facultydb.cdh6zybsklle.us-east-1.rds.amazonaws.com';
	private $username = 'maxwellsweikert';
	private $password = 'M3312140m';
	private $DBName = 'facultyDb';
	private $conn;
	
	function __construct(){
			$this->connect();
		
	}
	
	function connect(){
		try {
			$conn = new PDO('mysql:host='.$this->server.';dbname='.$this->DBName, $this->username, $this->password);
	
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "Connected successfully <br/>"; 
			$this->conn = $conn;
			}
		catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
		
	}
	
	function close(){
		$this->conn = null;
	}
	
	//return back data used for select statments only
	function getData($sql,$params){
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	function setData($sql,$params){
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($params);
	}
	

	
	function validateLogin($userame,$pass){
		//implement this if no shibo
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

