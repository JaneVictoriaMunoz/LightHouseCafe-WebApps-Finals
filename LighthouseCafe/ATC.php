<?php
include('header.php');

// Initialize selectedItems session variable if not set
$_SESSION['selectedItems'] = $_SESSION['selectedItems'] ?? array();

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

if (isset($_POST['add_to_cart'])) {
    if (isset($_SESSION['cart'])) {
        $session_array_id = array_column($_SESSION['cart'], "id");

        if (!in_array($_GET['id'], $session_array_id)) {
            array_push($_SESSION['selectedItems'], $_GET['id']);
            $session_array = array(
                'id' => $_GET['id'],
                "name" => $_POST['name'],
                "price" => $_POST['price'],
                "quantity" => $_POST['quantity'] ?? 1
            );
            $_SESSION['cart'][] = $session_array;
        }
    } else {
        $session_array = array(
            'id' => $_GET['id'],
            "name" => $_POST['name'],
            "price" => $_POST['price'],
            "quantity" => $_POST['quantity'] ?? 1
        );
        $_SESSION['cart'][] = $session_array;
        $_SESSION['selectedItems'][] = $_GET['id'];
    }
}

if (isset($_POST['update_cart'])) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $_POST['id']) {
            $item['quantity'] = $_POST['quantity'];
        }
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'remove') {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['id'] == $_GET['id']) {
                foreach ($_SESSION['selectedItems'] as $index => $selectedId) {
                    if ($selectedId == $_GET['id']) {
                        unset($_SESSION['selectedItems'][$index]);
                    }
                }
                unset($_SESSION['cart'][$key]);
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    if ($_GET['action'] == 'clearall') {
        unset($_SESSION['cart']);
        $_SESSION['selectedItems'] = array();
    }

    if ($_GET['action'] == 'checkout') {
        if (isset($_SESSION['userID'])) {
            header("Location: orders.php");
            exit();
        } else {
            echo "<script>document.getElementById('login-prompt').style.display = 'block';</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="AddCart.css">
    <script>
        function updatePrice(id, price) {
            const quantity = document.getElementById('quantity-' + id).value;
            const totalElement = document.getElementById('total-' + id);
            const newTotal = (price * quantity).toFixed(2);
            totalElement.innerText = '₱' + newTotal;

            // Update the cart total
            let cartTotal = 0;
            const itemTotals = document.querySelectorAll('[id^="total-"]');
            itemTotals.forEach(itemTotal => {
                cartTotal += parseFloat(itemTotal.innerText.replace('₱', ''));
            });
            document.getElementById('cart-total').innerText = '₱' + cartTotal.toFixed(2);

            // Save the updated quantity to the session
            const formData = new FormData();
            formData.append('id', id);
            formData.append('quantity', quantity);
            formData.append('update_cart', true);

            fetch('ATC.php', {
                method: 'POST',
                body: formData
            });
        }

        function proceedToCheckout() {
            <?php if (isset($_SESSION['userID'])): ?>
                // User is logged in, proceed to checkout
                window.location.href = "ordesr.php";
            <?php else: ?>
                // User is not logged in, show prompt
                document.getElementById('login-prompt').style.display = 'block';
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center my-4">Item Selected</h2>
                    <table class='table table-bordered table-striped table-custom'>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Item Price</th>
                            <th>Item Quantity</th>
                            <th>Item Total</th>
                            <th>Action</th>
                        </tr>
                        <?php 
                        $total = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $key => $value) {
                                echo "
                                <tr>
                                    <td>{$value['id']}</td>
                                    <td>{$value['name']}</td>
                                    <td style='text-align: right;' class='item-price' data-price='{$value['price']}'>₱{$value['price']}</td>
                                    <td style='text-align: center; vertical-align: middle; width: 150px;'>
                                    <input 
                                      type='number' 
                                      name='quantity' 
                                      value='{$value['quantity']}' 
                                      min='1' 
                                      class='item-quantity' 
                                      id='quantity-{$value['id']}' 
                                      style='width: 50px; text-align: center;' 
                                      onchange='updatePrice({$value['id']}, {$value['price']})'
                                    >
                                  </td>
                                    <td style='text-align: right;' id='total-{$value['id']}'>₱" . number_format($value['price'] * $value['quantity'], 2) . "</td>
                                    <td>
                                        <a href='ATC.php?action=remove&id={$value['id']}'>
                                            <button class='btn btn-danger btn-block btn-custom'>Remove</button>
                                        </a>
                                    </td>
                                </tr>
                                ";
                                $total += $value['quantity'] * $value['price'];
                            }
                            echo "
                            <tr>
                                <td colspan='4' class='text-right'><b>Total</b></td>
                                <td style='text-align: right;' id='cart-total'>₱" . number_format($total, 2) . "</td>
                                <td>
                                    <a href='ATC.php?action=clearall'>
                                        <button class='btn btn-danger btn-block btn-custom'>Clear Cart</button>
                                    </a>
                                </td>
                            </tr>
                            ";
                        } else {
                            echo "
                            <tr>
                                <td colspan='6' class='text-center'>Your cart is empty.</td>
                            </tr>
                            ";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <button class="btn btn-success btn-block btn-custom" onclick="proceedToCheckout()">Proceed to Checkout</button>
                    <?php endif; ?>
                </div><br><br>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="Menu.php" class="btn btn-success btn-block btn-custom"><?php echo empty($_SESSION['cart']) ? 'Continue Browsing' : 'Go to Menu'; ?></a>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <div id="login-prompt" style="display: none; text-align: center; margin-top: 20px;">
        <p>You need to <a href="Signup-Login.php">log in</a> or <a href="Signup-Login.php">sign up</a> for an account to proceed to checkout.</p>
    </div>
</body>
</html>
