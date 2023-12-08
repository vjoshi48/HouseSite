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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM UserTable WHERE ID = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row["password"]) {
            // Determine user type and redirect accordingly
            session_start();
            $_SESSION['username'] = $row['username'];

            switch ($row["userType"]) {
                case 'Admin':
                    header("Location: admin.html");
                    break;
                case 'Seller':
                    header("Location: seller.php?username=$username");
                    break;
                case 'Buyer':
                    header("Location: buyer.html");
                    break;
                default:
                    // Handle other user types if needed
                    break;
            }
            exit();
        } else {
            // Wrong password
            $error_message = "Incorrect password";
        }
    } else {
        // Wrong username
        $error_message = "Username not found";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error_message)) : ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
