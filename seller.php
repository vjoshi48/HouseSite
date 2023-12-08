<?php

$host = "localhost";
$user = "vjoshi6";
$pass = "vjoshi6";
$dbname = "vjoshi6";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo "Could not connect to server\n";
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_GET['username'])) {
    echo "Username not set.";
    exit();
}

$username = $_GET['username'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteProperty'])) {
        $propertyID = $_POST['deleteProperty'];

        // Delete the property from the database
        $deleteSql = "DELETE FROM Properties WHERE userID = '$username' AND propertyID = $propertyID";
        if ($conn->query($deleteSql) === TRUE) {
            //echo "<div class='check'>Property deleted successfully</div>";
        } else {
            //echo "<div class='check'>Error deleting property: " . $conn->error"</div>";
        }
    } else {
        // Add property to the database
        $location = $_POST['location'];
        $age = $_POST['age'];
        $squareFootage = $_POST['squareFootage'];
        $bedrooms = $_POST['bedrooms'];
        $bathrooms = $_POST['bathrooms'];
        $hasGarden = isset($_POST['hasGarden']) ? 1 : 0;
        $parkingCapacity = $_POST['parkingCapacity'];
        $propertyPrice = $_POST['propertyPrice'];

        $sql = "INSERT INTO Properties (userID, location, age, squareFootage, bedrooms, bathrooms, hasGarden, parkingCapacity, propertyPrice)
                VALUES ('$username', '$location', $age, $squareFootage, $bedrooms, $bathrooms, $hasGarden, $parkingCapacity, $propertyPrice)";

        if ($conn->query($sql) === TRUE) {
            //echo "<div class='check'>Property added successfully</div>";
        } else {
            //echo "<div class='check'>Error adding property: " . $conn->error"</div>";
        }
    }
}

// Retrieve existing properties for display
$sql = "SELECT * FROM Properties WHERE userID = '$username'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seller page</title>
    <link rel="stylesheet" href="sellerstyle.css">

    <script>
        function showPropertyDetails(propertyID) {
    // Navigate to property_details.php with the propertyID as a query parameter
    window.location.href = 'property_details.php?propertyID=' + propertyID;
}
</script>
</head>
<body>
    <h1>SELLER PAGE</h1>
    <a href="index.html">HomePage</a>

    <h1>Welcome, <?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?></h1>
    <h2>Your Properties</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Display each property as a card

            echo "<div class='property-card'>";
            echo "<p>Location: " . $row['location'] . "</p>";
            echo "<p>Age: " . $row['age'] . "</p>";
            echo "<p>Square Footage: " . $row['squareFootage'] . "</p>";
            echo "<p>Bedrooms: " . $row['bedrooms'] . "</p>";
            echo "<p>Bathrooms: " . $row['bathrooms'] . "</p>";
            echo "<p>Has Garden: " . ($row['hasGarden'] ? 'Yes' : 'No') . "</p>";
            echo "<p>Parking Capacity: " . $row['parkingCapacity'] . "</p>";
            echo "<p>Property Price: $" . $row['propertyPrice'] . "</p>";

            echo "<button onclick='showPropertyDetails(" . $row['propertyID'] . ")'>View Details</button>";

            // Add delete button
            echo "<form method='post' style='display:inline;'>";
            echo "<input type='hidden' name='deleteProperty' value='" . $row['propertyID'] . "'>";
            echo "<input type='submit' class='delete-button' value='Delete'>";
            echo "</form>";

            echo "</div>";
        }
    } else {
        echo "<p>No properties found.</p>";
    }

    $conn->close();
    ?>

    <div class="add-property-card" onclick="document.getElementById('propertyForm').style.display = 'block';">+</div>

    <div id="propertyForm" style="display: none;">
        <h2>Add Property</h2>
        <form action="" method="post">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required><br>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age"><br>

            <label for="squareFootage">Square Footage:</label>
            <input type="number" id="squareFootage" name="squareFootage"><br>

            <label for="bedrooms">Number of Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms"><br>

            <label for="bathrooms">Number of Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms"><br>

            <label for="hasGarden">Has Garden:</label>
            <input type="checkbox" id="hasGarden" name="hasGarden"><br>

            <label for="parkingCapacity">Parking Capacity:</label>
            <input type="number" id="parkingCapacity" name="parkingCapacity"><br>

            <label for="propertyPrice">Property Price:</label>
            <input type="number" step="0.01" id="propertyPrice" name="propertyPrice" required><br>

            <input type="submit" value="Add Property">
        </form>
    </div>
</body>
</html>
