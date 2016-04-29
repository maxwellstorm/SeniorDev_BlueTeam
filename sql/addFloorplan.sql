CREATE TABLE floorPlan (fpId int NOT NULL AUTO_INCREMENT, imagePath varchar(50) NOT NULL, name varchar(100), PRIMARY KEY (fpId));

//Still need to do foreign key
ALTER TABLE room ADD FOREIGN KEY(roomMap) REFERENCES floorPlan(imagePath);
