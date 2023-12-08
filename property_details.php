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

if (!isset($_GET['propertyID'])) {
    echo "Property ID not set.";
    exit();
}

$propertyID = $_GET['propertyID'];
$propertyID = intval($propertyID); // Convert to integer for safety
if ($propertyID <= 0) {
    echo "Invalid Property ID.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission for updating property details
    $newLocation = $_POST['newLocation'];
    $newAge = $_POST['newAge'];
    $newSquareFootage = $_POST['newSquareFootage'];
    $newBedrooms = $_POST['newBedrooms'];
    $newBathrooms = $_POST['newBathrooms'];
    $newHasGarden = isset($_POST['newHasGarden']) ? 1 : 0;
    $newParkingCapacity = $_POST['newParkingCapacity'];
    $newPropertyPrice = $_POST['newPropertyPrice'];

    $updateSql = "UPDATE Properties 
                  SET location = '$newLocation', age = $newAge, squareFootage = $newSquareFootage,
                      bedrooms = $newBedrooms, bathrooms = $newBathrooms, hasGarden = $newHasGarden,
                      parkingCapacity = $newParkingCapacity, propertyPrice = $newPropertyPrice
                  WHERE propertyID = $propertyID";

    if ($conn->query($updateSql) === TRUE) {
        echo "<div class='check'>Property details updated successfully</div>";
    } else {
        echo "<div class='check'>Error updating property details: " . $conn->error . "</div>";
    }
}

// Retrieve property details
$sql = "SELECT * FROM Properties WHERE propertyID = $propertyID";

$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Property Details</title>
    <link rel="stylesheet" href="sellerstyle.css">
    <style>
        /* Add your styles for property details here */
        .property-details {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
        }

        .update-form {
            display: none; /* Hide the form initially */
        }
    </style>
    <script>
        function toggleForm() {
            var form = document.getElementById('updateForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>Property Details</h1>

    <div class="property-details">
        <?php
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $location = $row['location'];
            $age = $row['age'];
            $squareFootage = $row['squareFootage'];
            $bedrooms = $row['bedrooms'];
            $bathrooms = $row['bathrooms'];
            $hasGarden = $row['hasGarden'] ? 'Yes' : 'No';
            $parkingCapacity = $row['parkingCapacity'];
            $propertyPrice = $row['propertyPrice'];
            $userID = $row['userID']; // Retrieve userID from the Property table
        ?>

<p>Location: <?php echo $location; ?></p>
            <p>Age: <?php echo $age; ?></p>
            <p>Square Footage: <?php echo $squareFootage; ?></p>
            <p>Bedrooms: <?php echo $bedrooms; ?></p>
            <p>Bathrooms: <?php echo $bathrooms; ?></p>
            <p>Has Garden: <?php echo $hasGarden; ?></p>
            <p>Parking Capacity: <?php echo $parkingCapacity; ?></p>
            <p>Property Price: $<?php echo $propertyPrice; ?></p>

            <button onclick="toggleForm()">Edit Details</button>

            <div id="updateForm" class="update-form">
            <form action="" method="post">
                <label for="newLocation">New Location:</label>
                <input type="text" id="newLocation" name="newLocation" value="<?php echo $location; ?>" required><br>

                <label for="newAge">New Age:</label>
                <input type="number" id="newAge" name="newAge" value="<?php echo $age; ?>"><br>

                <label for="newSquareFootage">New Square Footage:</label>
                <input type="number" id="newSquareFootage" name="newSquareFootage" value="<?php echo $squareFootage; ?>"><br>

                <label for="newBedrooms">New Number of Bedrooms:</label>
                <input type="number" id="newBedrooms" name="newBedrooms" value="<?php echo $bedrooms; ?>"><br>

                <label for="newBathrooms">New Number of Bathrooms:</label>
                <input type="number" id="newBathrooms" name="newBathrooms" value="<?php echo $bathrooms; ?>"><br>

                <label for="newHasGarden">New Has Garden:</label>
                <input type="checkbox" id="newHasGarden" name="newHasGarden" <?php echo $row['hasGarden'] ? 'checked' : ''; ?>><br>

                <label for="newParkingCapacity">New Parking Capacity:</label>
                <input type="number" id="newParkingCapacity" name="newParkingCapacity" value="<?php echo $parkingCapacity; ?>"><br>

                <label for="newPropertyPrice">New Property Price:</label>
                <input type="number" step="0.01" id="newPropertyPrice" name="newPropertyPrice" value="<?php echo $propertyPrice; ?>" required><br>

                <input type="submit" value="Update Property Details">
            </form>
            </div>
        <?php
        } else {
            echo "Property not found.";
        }
        ?>
    </div>
    <form action="seller.php" method="get" style="display:inline;">
                <input type="hidden" name="username" value="<?php echo $userID; ?>">
                <input type="submit" value="Go Back">
            </form>
</body>
</html>

