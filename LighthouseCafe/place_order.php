<?php
session_start();

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

// Insert new order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $userID = $_POST['userID'];
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $product_price = $_POST['product_price'];
    $status = "pending";

    // $sql = "INSERT INTO orderlist (userID, product_id, product_name, quantity, product_price, status) 
    //         VALUES (?, ?, ?, ?, ?, ?)";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("iisids", $userID, $product_id, $product_name, $quantity, $product_price, $status);
    // if ($stmt->execute()) {
    //     echo "Order placed successfully!";
    // } else {
    //     echo "Error: " . $stmt->error;
    // }
    // $stmt->close();


    $sql = "INSERT INTO orderlist (userID, product_id, product_name, quantity, product_price, status) 
            VALUES ('$userID', '$product_id', '$product_name', '$quantity', '$product_price', '$status')";
    mysqli_query($conn, $sql);
    echo "Order placed successfully!";

}

$conn->close();
?>
