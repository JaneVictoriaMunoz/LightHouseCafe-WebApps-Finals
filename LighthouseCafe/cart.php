<?php
session_start();

// Function to fetch item price from database (dummy function)
function get_item_price($item) {
    // Replace this with your actual logic to fetch the price from the database
    $prices = [
        "item1" => 10.00,
        "item2" => 15.00,
        "item3" => 20.00,
        // Add more items and their prices as needed
    ];

    // Return the price of the item if found, otherwise return 0
    return isset($prices[$item]) ? $prices[$item] : 0;
}

// Initialize the cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_item'])) {
        $item = htmlspecialchars($_POST['item']);
        $price = get_item_price($item);

        // Check if the item is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['item'] == $item) {
                $cart_item['quantity'] += 1;
                $found = true;
                break;
            }
        }

        // If the item is not found, add it to the cart
        if (!$found) {
            $id = uniqid(); // Unique ID for each cart item
            $_SESSION['cart'][] = ['id' => $id, 'item' => $item, 'price' => $price, 'quantity' => 1];
        }
    } elseif (isset($_POST['update_quantity'])) {
        $id = $_POST['id'];
        $action = $_POST['update_quantity'];

        // Update the quantity based on the action (increase or decrease)
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['id'] == $id) {
                if ($action == 'increase') {
                    $cart_item['quantity'] += 1;
                } elseif ($action == 'decrease' && $cart_item['quantity'] > 1) {
                    $cart_item['quantity'] -= 1;
                }
                break;
            }
        }
    }
}

// Handle removing items from the cart
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Calculate the total cost
$totalCost = 0;
foreach ($_SESSION['cart'] as $cart_item) {
    $totalCost += $cart_item['price'] * $cart_item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
    <style>
        /* Additional CSS styling can be added here */
    </style>
    <script>
        function updateQuantity(id, action) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('update_quantity', action);

            fetch('cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.open();
                document.write(data);
                document.close();
            });
        }
    </script>
</head>
<body>
    <h1>Shopping Cart</h1><br>

    <?php if (!empty($_SESSION['cart'])): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $cart_item): 
                $itemTotal = $cart_item['price'] * $cart_item['quantity'];
            ?>
            <tr>
                <td><?php echo htmlspecialchars($cart_item['id']); ?></td>
                <td><?php echo htmlspecialchars($cart_item['item']); ?></td>
                <td><?php echo number_format($cart_item['price'], 2); ?></td>
                <td>
                    <button onclick="updateQuantity('<?php echo htmlspecialchars($cart_item['id']); ?>', 'decrease')">-</button>
                    <?php echo $cart_item['quantity']; ?>
                    <button onclick="updateQuantity('<?php echo htmlspecialchars($cart_item['id']); ?>', 'increase')">+</button>
                </td>
                <td><?php echo number_format($itemTotal, 2); ?></td>
                <td><a href="cart.php?remove=<?php echo htmlspecialchars($cart_item['id']); ?>">Remove</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Total Cost: <?php echo number_format($totalCost, 2); ?></p>
    <?php else: ?>
    <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="Menu.php" class="back-to-menu">Back to Menu</a>
    <button <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?> class="btn btn-primary">Proceed to Checkout</button>
</body>
</html>
