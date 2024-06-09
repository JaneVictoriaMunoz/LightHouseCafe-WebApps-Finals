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

// Update product status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $product_id = $_POST['product_id'];
    $status = $_POST['status'];
    $sql = "UPDATE products SET status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shriram | DashBoard</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="apple-touch-icon-114x114.png" />
    <link rel="icon" type="images/svg+xml" href="images/logo.svg" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800" rel="stylesheet">
    <link href="css/custom-admin.css" rel="stylesheet">
    <link href="css/font-awesome-admin.css" rel="stylesheet">
    <link href="css/bootstrap-admin.css" rel="stylesheet">
    <link href="css/export-chart-admin.css" rel="stylesheet">
    <link href="css/custom-admin.css" rel="stylesheet">
</head>
<link rel="stylesheet" href="Admindashboard.css">

<body class="blur-theme data-container-body">
    <div id="projectmaster" class="content">
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

        <main>
            <section>
                <div class="menu-search-block">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h3>ADMIN DASHBOARD</h3>
                        </div>
                    </div>
                </div>
                <div class="container-dashboard">
                    <div class="container-dashboard-inner">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Product Price</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product) { ?>
                                        <tr>
                                            <td><?= $product['product_name'] ?></td>
                                            <td>₱<?= number_format($product['product_price'], 2) ?></td>
                                            <td class="text-center"><?= ucfirst($product['status']) ?></td>
                                            <td class="text-center">
                                                <form method="post" action="">
                                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                                    <select name="status">
                                                        <option value="available" <?= $product['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                                                        <option value="unavailable" <?= $product['status'] == 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                                    </select>
                                                    <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <form method="post" action="Menu.php">
                                <button type="submit" class="btn btn-primary">Update Menu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
