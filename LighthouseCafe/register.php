<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_lighthouse";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password
    $address = htmlspecialchars(trim($_POST['address']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));

    // Prepare and bind
    $sql = "INSERT INTO user_account (fullname, email, username, password, address, phone_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $fullname, $email, $username, $password, $address, $phone_number);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: Signup-Login.php");
        exit(); // Ensure script termination after redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
