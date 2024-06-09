<?php
session_start();

$delivery_fee = 38;
$total_price = 0;
$cart_items = $_SESSION['cart'] ?? [];

foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
$total_price += $delivery_fee;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="orders.css">
</head>

<body>
    <div class="container">
        <h1>LIGHTHOUSE CAFE</h1>
        <p>Delivered by GrabFood</p>
        <h3>ORDER SUMMARY</h3>
        <h5 class="line">_____________________________________</h5>
        <h2>Product</h2>
        <?php if (!empty($cart_items)) { ?>
            <?php foreach ($cart_items as $item) { ?>
                <div class="product">
                    <h2 class="product-name"><?= $item['name'] ?></h2>
                    <h4>QTY: <?= $item['quantity'] ?></h4>
                    <h2 class="product-price">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></h2>
                </div>
            <?php } ?>
            <div class="summary">
                <h5 class="delivery-fee">Delivery Fee</h5>
                <h5 class="delivery-fee-price">₱<?= number_format($delivery_fee, 2) ?></h5>
                <h6>TOTAL</h6>
                <h5 class="line">_____________________________________</h5>
                <h5 class="total-product">₱<?= number_format($total_price, 2) ?></h5>
            </div>
            <h5 class="method">METHOD</h5>
            <form id="deliveryForm">
                <label>
                    <input type="radio" name="deliveryOption" value="Delivery" onclick="toggleLayout('delivery')"> Delivery
                </label><br>
                <label>
                    <input type="radio" name="deliveryOption" value="Pick Up" onclick="toggleLayout('pickup')"> Pick Up
                </label>
            </form>
            <h5 class="line">_____________________________________</h5>
            <div class="pickup-section">
                <h5 class="pickup-time">PICK UP TIME</h5>
                <h5 class="standard-time">Standard time 20 - 25 minutes</h5>
                <h5 class="line">_____________________________________</h5>
                <h5 class="pickup-address">PICKUP ADDRESS</h5>
                <h5 class="store-address">STORE ADDRESS:</h5>
                <h5 class="address">Ground floor, Lighthouse Bible Baptist Church, 89 ROTC Hunters St., Barangay Tatalon, Quezon City, Philippines</h5>
            </div>
            <h5 class="payment-method">PAYMENT METHOD</h5>
            <form class="paymentForm">
                <label>
                    <input type="radio" name="paymentOption" value="Cash On Delivery"> Cash On Delivery
                </label><br>
                <label>
                    <input type="radio" name="paymentOption" value="Gcash"> Gcash
                </label>
            </form>
            <h5 class="line">_____________________________________</h5>
            <div class="buttons">
                <a href="than_you.php"><button class="btn-submit" onclick="placeOrder('delivery')">PLACE ORDER</button></a>
                <a href="ATC.php" class="btn-back">Back</a>
            </div>
        <?php } else { ?>
            <h2>Your cart is empty.</h2>
        <?php } ?>
        <div class="image">
            <img src="/Image/lighthouselogo.png" alt="" class="logo">
        </div>
    </div>

    <div id="thankYouModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Thank you for ordering!</h2>
            <button onclick="redirectToHome()">Go to Home</button>
        </div>
    </div>

    <script>
        function toggleLayout(option) {
            const pickupSection = document.querySelector('.pickup-section');
            const deliveryFee = document.querySelector('.delivery-fee');
            const deliveryFeePrice = document.querySelector('.delivery-fee-price');
            const paymentMethod = document.querySelector('.payment-method');
            const paymentForm = document.querySelector('.paymentForm');
            const btnSubmit = document.querySelector('.btn-submit');
            const btnSubmit2 = document.querySelector('.btn-submit2');

            if (option === 'pickup') {
                pickupSection.classList.remove('hidden');
                deliveryFee.classList.add('hidden');
                deliveryFeePrice.classList.add('hidden');
                paymentMethod.classList.add('hidden');
                paymentForm.classList.add('hidden');
                btnSubmit.classList.add('hidden');
                btnSubmit2.classList.remove('hidden');
            } else {
                pickupSection.classList.add('hidden');
                deliveryFee.classList.remove('hidden');
                deliveryFeePrice.classList.remove('hidden');
                paymentMethod.classList.remove('hidden');
                paymentForm.classList.remove('hidden');
                btnSubmit.classList.remove('hidden');
                btnSubmit2.classList.add('hidden');
            }
        }

        function placeOrder(method) {
            const formData = new FormData();
            formData.append('method', method);

            fetch('place_order.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modal = document.getElementById('thankYouModal');
                        modal.style.display = "block";
                        document.querySelector('.close').onclick = function() {
                            modal.style.display = "none";
                        };
                    } else {
                        alert('Failed to place order. Please try again.');
                    }
                });
        }

        function redirectToHome() {
            window.location.href = 'index.php';
        }
    </script>

    <style>
       
        
    </style>
</body>

</html>
