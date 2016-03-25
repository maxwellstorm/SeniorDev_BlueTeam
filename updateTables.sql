ALTER TABLE Employees ADD title varchar(100);
ALTER TABLE Employees ALTER COLUMN roomNumber varchar(25);
ALTER TABLE Employees DROP officeHours;
ALTER TABLE Employees ADD secondaryDepartmentID int;
ALTER TABLE room ALTER COLUMN roomNumber varchar(25);