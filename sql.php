<?php
$host = "localhost";
$user = "vjoshi6";
$pass = "vjoshi6";
$dbname = "vjoshi6";

//Create connection

$conn = new mysqli($host, $user, $pass, $dbname);

//Check connection
if($conn->connect_error) {
    echo "Could not connect to server\n";
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "CREATE TABLE Properties (
    propertyID INT AUTO_INCREMENT PRIMARY KEY,
    userID VARCHAR(50),
    location VARCHAR(100),
    age INT,
    squareFootage INT,
    bedrooms INT,
    bathrooms INT,
    hasGarden BOOLEAN,
    parkingCapacity INT,
    propertyPrice DECIMAL(10, 2),
    FOREIGN KEY (userID) REFERENCES UserTable(ID)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Properties created successfully";
}
else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>