<?php
private $servername = "facultydb.cdh6zybsklle.us-east-1.rds.amazonaws.com"
private $username = "test"
private $password = "test"
private $dbname = "facultydb"

$database = new Database();

public function insert($roomNum. $roomMap, $roomDesc){
	$database->query('INSERT INTO Room (roomNumber, roomMap, description) values ($roomNum, $roomMap, $roomDesc)');
	$database->bind('$roomNumber', 999);
	$database->bind('$roomMap', 'Map');
	$database->bind('$roomDesc','Descipion');

	$database->execute();
	echo $database->lastInsertId();//test successfully inserted
}

public function update($roomNum. $roomMap, $roomDesc){
	$database->query('UPDATE Room (roomNumber, roomMap, description) values ($roomNum, $roomMap, $roomDesc)');
	$database->bind('$roomNumber', 999);
	$database->bind('$roomMap', 'Map');
	$database->bind('$roomDesc','Descipion');

	$database->execute();
}
>