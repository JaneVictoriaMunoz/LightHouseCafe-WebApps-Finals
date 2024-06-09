<?php
session_start();

// Check if order was placed successfully
$order_success = isset($_SESSION['order_success']) && $_SESSION['order_success'];
unset($_SESSION['order_success']); // Clear the session variable

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            font-size: 2em;
            color: #000;
        }
        p {
            font-size: 1.2em;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #000;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Thank You for Ordering!</h1>
        <p>Your order has been placed successfully.</p>
        <a href="landingpage.php" class="btn">Go to Home</a>
    </div>
</body>

</html>
