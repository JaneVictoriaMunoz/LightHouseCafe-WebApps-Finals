<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <link rel="stylesheet" href="BB.css">
    <script src="https://kit.fontawesome.com/ec1a397272.js" crossorigin="anonymous"></script>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="Signup-Login.js"></script>
    <header>
        <nav>
            <div class="row1">
                <a href="Landingpage.php"><img src="Image/lighthouselogo.png" alt="Coffeshop" class="logo"></a>
                <ul class="nav-bar">
                    <li><a href="Landingpage.php">Home</a></li>
                    <li><a href="Menu.php">Menu</a></li>
                    <li><a href="About Us.php">About Cafe</a></li>
                    <li><a href="Contact Us.php">Contact Us</a></li>
                    <li><a href="Signup-Login.php">Login/Signup</a></li>
                    <li><a href="Feedback Form.php">Reviews</a></li>
                </ul>
            </div>
            <div class="nav-right">
                <a href="ATC.php" class="cart-icon">
                    <img src="/Image/finalcart.png" alt="Cart Logo">
                </a>
            </div>
        </nav>
        <div class="veen">
            <div class="login-btn splits">
                <p>Already a user?</p>
                <button class="active">Login</button><br><br>
                <button onclick="window.location.href='Landingpage.php'">Home</button>
            </div>
            <div class="rgstr-btn splits">
                <p>Don't have an account?</p>
                <button>Register</button><br><br>
                <button onclick="window.location.href='Landingpage.php'">Home</button>
            </div>
            <div class="wrapper">
                <form id="login" method="POST" action="login.php" tabindex="500">
                    <h3>Login</h3>
                    <div class="mail">
                        <input type="text" name="login_email_username" required>
                        <label>Mail or Username</label>
                    </div>
                    <div class="passwd">
                        <input type="password" name="login_password" required>
                        <label>Password</label>
                    </div>
                    <div class="submit">
                        <button class="dark" type="submit">Login</button>
                    </div>
                    <div class="error-message">
                        <?php
                        if (isset($_GET['error'])) {
                            echo '<p style="color:red;">' . htmlspecialchars($_GET['error']) . '</p>';
                        }
                        ?>
                    </div>
                </form>
                <form id="register" method="POST" action="register.php" tabindex="502">
                    <h3>Register</h3>
                    <div class="name">
                        <input type="text" name="fullname" required>
                        <label>Full Name</label>
                    </div>
                    <div class="mail">
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="uid">
                        <input type="text" name="username" required>
                        <label>Username</label>
                    </div>
                    <div class="passwd">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <div class="address">
                        <input type="text" name="address" required>
                        <label>Address</label>
                    </div>
                    <div class="phone">
                        <input type="text" name="phone_number" required>
                        <label>Phone Number</label>
                    </div>
                    <div class="submit">
                        <button class="dark" type="submit">Register</button>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <style type="text/css">
            .site-link {
                padding: 5px 15px;
                position: fixed;
                z-index: 99999;
                background: #fff;
                box-shadow: 0 0 4px rgba(0, 0, 0, .14), 0 4px 8px rgba(0, 0, 0, .28);
                right: 30px;
                bottom: 30px;
                border-radius: 10px;
            }

            .site-link img {
                width: 30px;
                height: 30px;
            }
        </style>
</body>
</header>
</body>

</html>