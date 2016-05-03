<?php
require_once("dbException.php");

	


/**
 * A class that serves as the base data layer - this provides methods to access the database to get & set information
 */
class data{
	//Database connection information
	private $server = "localhost";
	private $username = "root";
	private $password = "D@wMD014Zd0g";
	private $DBName = "facultyDb";
	private $conn;
	
	/**
	 * A constructor to instantiate the class
	 */
	function __construct(){
			$this->connect();
		
	}
	
	/**
	 * A method to connect to the database using the given connection variables
	 */
	function connect(){
		try { //Connect & set the connection to use PDO Objet Mapping
			$conn = new PDO('mysql:host='.$this->server.';dbname='.$this->DBName, $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			$this->conn = $conn;
			}
		catch(PDOException $e) { //Log an error message if the database connection fails
			throw new dbException("Connection failed: " . $e->getMessage(),0);
		}
		catch(Exception $e){ //Log an error message if a general exception occurs
			throw new dbException("An unexpected error occured while connecting to the datbase" . $e->getMessage(),0);
		}
		
	}
	
	/**
	 * A function to close a connection to the Database
	 */
	function close(){
		$this->conn = null;
	}
	
	/**
	 * A function to allow the user to provide an SQL Query (SELECT queries) and set of parameters to get Information from the DB
	 * @param $sql The SQL SELECT statement
	 * @param $params An array of paremeters to be bound to the query 
	 */
	function getData($sql,$params){
		try{
			//Prepare, execute, and return the query
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($params);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(Exception $e){ //Catch general exceptions
			throw new dbException("Get Data Failed " . $e->getMessage(),0);
		}
	}
	
	/**
	 * A function to allow the suer to provide an SQL Query and set parameters to update information in the database
	 * @param $sql The SQL Query to be executed
	 * @param $params The parmeters to be buond to the query
	 */
	function setData($sql,$params){
		try{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($params);
		}
		catch(Exception $e){ //Catch general exceptions
			throw new dbException("Set Data Failed " . $e->getMessage(),0);
		}
	}
	
	/**
	 * A function to start a transaction
	 */	
	function startTransaction(){
		$this->conn->beginTransaction();
	}
	
	/**
	 * A function to commit & end a transaction
	 */
	function commit(){
		$this->conn->commit();
	}
	
	/**
	 * A function to rollback a transaction to the state before the start
	 */
	function rollBack(){
		//this ends transactions as well
		$this->conn->rollBack();
	}	
}
?>