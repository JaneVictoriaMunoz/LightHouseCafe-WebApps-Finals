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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_order'])) {
    $userID = $_POST['userID'];
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $product_price = $_POST['product_price'];
    $status = $_POST['status'];

    $sql = "INSERT INTO orderlist (userID, product_id, product_name, quantity, product_price, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisids", $userID, $product_id, $product_name, $quantity, $product_price, $status);
    $stmt->execute();
    $stmt->close();
}

// Update order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $sql = "UPDATE orderlist SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Delete order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    $sql = "DELETE FROM orderlist WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch orders
$sql = "SELECT * FROM orderlist";
$result = $conn->query($sql);
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee DashBoard</title>
    <link rel="stylesheet" href="employeedashboard.css">
</head>

<body>
    <div id="projectmaster" class="content">
        <!-- HEADER: START -->
        <header>
            <div class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="clearfix">
                        <div class="navbar-left">
                            <!-- Add your header content here -->
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- HEADER: END -->

        <!-- MAIN: START -->
        <main>
            <section>
                <div class="menu-search-block">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h3>EMPLOYEE'S DASHBOARD</h3>
                        </div>
                    </div>
                </div>
                <div class="container-dashboard">
                    <div class="container-dashboard-inner">
                        <div class="">
                            <form class="form-inline">
                                <div class="clearfix">
                                    <div class="pull-left">
                                        <div class="form-group">
                                            <label for="three">
                                                Customer Name
                                            </label>
                                            <input type="text" class="form-control" placeholder="Name" id="three">
                                            <i class="fa fa-search search-box" aria-hidden="true"></i>
                                        </div><br>
                                        <div>
                                            <a href="employeedashboard.php" class="btn btn-primary"> <i class="fa fa-check-circle fa-fw" aria-hidden="true"></i> Refresh</a>
                                            <a href="logout.php" class="btn btn-primary"> <i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>Logout</a>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer ID</th>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Product Price</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order) { ?>
                                            <tr>
                                                <td><?= $order['order_id'] ?></td>
                                                <td><?= $order['userID'] ?></td>
                                                <td><?= $order['product_id'] ?></td>
                                                <td><?= $order['product_name'] ?></td>
                                                <td><?= $order['quantity'] ?></td>
                                                <td>₱<?= number_format($order['product_price'], 2) ?></td>
                                                <td><?= ucfirst($order['status']) ?></td>
                                                <td>
                                                    <form method="post" action="">
                                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                                        <select name="status">
                                                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                            <option value="done" <?= $order['status'] == 'done' ? 'selected' : '' ?>>Done</option>
                                                        </select>
                                                        <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form method="post" action="">
                                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                                        <button type="submit" name="delete_order" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <!-- Add New Order Form -->
                            <div class="add-order-form">
                                <h3>Add New Order</h3>
                                <form method="post" action="">
                                    <div class="form-group">
                                        <label for="userID">Customer ID</label>
                                        <input type="number" class="form-control" id="userID" name="userID" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_id">Product ID</label>
                                        <input type="number" class="form-control" id="product_id" name="product_id" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_price">Product Price</label>
                                        <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="pending">Pending</option>
                                            <option value="done">Done</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="add_order" class="btn btn-success">Add Order</button>
                                </form>
                            </div>
                            <!-- End Add New Order Form -->
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- MAIN: END -->
    </div>
</body>

</html>
