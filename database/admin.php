<?php
private $servername = "facultydb.cdh6zybsklle.us-east-1.rds.amazonaws.com"
private $username = "test"
private $password = "test"
private $dbname = "facultydb"

$database = new Database();

public function insert($adminId, $fName, $lName, $user, $pass, $salt, $accessLevel, $deptID){
	$database->query('INSERT INTO ADMIN (adminID,fName,lName,username,password,salt,accessLevel,departmentID) values ($adminId,$fName,$lName,$user,$pass,$salt,$accessLevel,$deptID)');
	$database->bind('$adminId',999);
	$database->bind('$fName','John');
	$database->bind('$lName','Smith');
	$database->bind('$user','jsmith');
	$database->bind('$pass','test');
	$database->bind('$salt','abc');
	$database->bind('$accesslevel',999);
	$database->bind('$deptid',999)

	$database->execute();
	echo $database->lastInsertId();//test successfully inserted
}

public function update($adminId, $fName, $lName, $user, $pass, $salt, $accessLevel, $deptID){
	$database->query('UPDATE ADMIN (adminID,fName,lName,username,password,salt,accessLevel,departmentID) values ($adminId,$fName,$lName,$user,$pass,$salt,$accessLevel,$deptID)');
	$database->bind('$adminId',999);
	$database->bind('$fName','John');
	$database->bind('$lName','Smith');
	$database->bind('$user','jsmith');
	$database->bind('$pass','test');
	$database->bind('$salt','abc');
	$database->bind('$accesslevel',999);
	$database->bind('$deptID',999)

	$database->execute();
}
>