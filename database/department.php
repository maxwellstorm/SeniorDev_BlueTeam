<?php
private $servername = "facultydb.cdh6zybsklle.us-east-1.rds.amazonaws.com"
private $username = "test"
private $password = "test"
private $dbname = "facultydb"

$database = new Database();

public function insert($deptID. $deptName, $deptAbbr){
	$database->query('INSERT INTO Department (departmentID, departmentName, departmentAbbr) values ($deptID, $deptName, $deptAbbr)');
	$database->bind('$deptID', 999);
	$database->bind('$deptName', 'Dept');
	$database->bind('$deptAbbr','Abbr');

	$database->execute();
	echo $database->lastInsertId();//test successfully inserted
}

public function update($deptID. $deptName, $deptAbbr){
	$database->query('UPDATE Department (departmentID, departmentName, departmentAbbr) values ($deptID, $deptName, $deptAbbr)');
	$database->bind('$deptID', 999);
	$database->bind('$deptName', 'Dept');
	$database->bind('$deptAbbr','Abbr');

	$database->execute();
}
>