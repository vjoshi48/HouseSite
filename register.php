<?php

$host = "localhost";
$user = "vjoshi6";
$pass = "vjoshi6";
$dbname = "vjoshi6";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$registrationMessage = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user input
    $id = validateInput($_POST["id"]);
    $firstname = validateInput($_POST["firstname"]);
    $lastname = validateInput($_POST["lastname"]);
    $email = validateInput($_POST["email"]);
    $password = validateInput($_POST["password"]);
    $userType = validateInput($_POST["userType"]);

    // Check if username is already taken
    if (isUsernameTaken($conn, $id)) {
        $errors[] = "Username is already taken. Please choose a different one.";
    }

    // If no errors, insert data into UserTable
    if (empty($errors)) {
        $sql = "INSERT INTO UserTable (ID, firstname, lastname, email, password, userType) VALUES ('$id', '$firstname', '$lastname', '$email', '$password', '$userType')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to login.php
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $registrationMessage = implode("<br>", $errors);
    }
}

$conn->close();

function validateInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isUsernameTaken($conn, $username)
{
    $sql = "SELECT ID FROM UserTable WHERE ID = '$username'";
    $result = $conn->query($sql);

    return $result->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    ID (Username): <input type="text" name="id" required><br>
    First Name: <input type="text" name="firstname" required><br>
    Last Name: <input type="text" name="lastname" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    User Type:
    <select name="userType" required>
        <option value="Admin">Admin</option>
        <option value="Seller">Seller</option>
        <option value="Buyer">Buyer</option>
    </select><br>
    <input type="submit" value="Register">
</form>

<!-- Display registration message below the form -->
<div class="registration-message"><?php echo $registrationMessage; ?></div>

</body>
</html>
