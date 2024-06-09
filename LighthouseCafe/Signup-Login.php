<?php
include('config.php');
session_start();

$registrationSuccessful = false;
$loginSuccessful = false;
$message = '';
$welcomeMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fullname'])) {
        // Registration logic
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];

        // Check if email contains @admin or @employee
        if (strpos($email, '@admin') !== false || strpos($email, '@employee') !== false) {
            $message = "You cannot register with an admin or employee email.";
        } else {
            $dbConn = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBNAME);

            if (!$dbConn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM user_account WHERE email = '$email'";
            $result = mysqli_query($dbConn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $message = "Email already registered.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO user_account (fullname, email, password, address, phone_number) VALUES ('$fullname', '$email', '$hashedPassword', '$address', '$phone_number')";

                if (mysqli_query($dbConn, $sql)) {
                    $registrationSuccessful = true;
                } else {
                    $message = "Error: " . $sql . "<br>" . mysqli_error($dbConn);
                }
            }

            mysqli_close($dbConn);
        }
    } elseif (isset($_POST['login_email'])) {
        // Login logic
        $email = $_POST['login_email'];
        $password = $_POST['login_password'];

        $dbConn = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBNAME);

        if (!$dbConn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM user_account WHERE email = '$email'";
        $result = mysqli_query($dbConn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Check if the user is admin or employee and verify the password
            if (($email == 'janeLH@admin.com' || $email == 'janeLH@employee.com') && $password == 'lighthouse') {
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fullname'] = $user['fullname'];

                // Redirect based on user role
                if ($email == 'janeLH@admin.com') {
                    header("Location: admindashboard.php");
                } elseif ($email == 'janeLH@employee.com') {
                    header("Location: employeedashboard.php");
                }
                exit();
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fullname'] = $user['fullname'];

                // Redirect to the landing page for regular users
                header("Location: Landingpage.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "No user found with that email.";
        }

        mysqli_close($dbConn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <link rel="stylesheet" href="SignupLogIn.css">
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
                    <?php if (!isset($_SESSION['userID'])): ?>
                        <li><a href="Signup-Login.php">Login/Signup</a></li>
                    <?php endif; ?>
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
                <p>Already a user?</p><br>
                <button class="active">Login</button><br><br>
            </div>
            <div class="rgstr-btn splits">
                <p>Don't have an account?</p><br>
                <button>Register</button><br><br>
            </div>
            <div class="wrapper">
                <form id="login" method="POST" action="Signup-Login.php" tabindex="500">
                    <h3>Login</h3>
                    <div class="mail">
                        <input type="email" name="login_email" required>
                        <label>Email</label>
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
                        if ($message && !$loginSuccessful) {
                            echo '<p style="color:red;">' . htmlspecialchars($message) . '</p>';
                        }
                        ?>
                    </div>
                </form>
                <form id="register" method="POST" action="Signup-Login.php" tabindex="502">
                    <h3>Register</h3>
                    <div class="name">
                        <input type="text" name="fullname" required>
                        <label>Full Name</label>
                    </div>
                    <div class="mail">
                        <input type="email" name="email" required>
                        <label>Email</label>
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
                    <div class="error-message">
                        <?php
                        if ($message && !$registrationSuccessful) {
                            echo '<p style="color:red;">' . htmlspecialchars($message) . '</p>';
                        }
                        ?>
                    </div>
                    <div class="success-message">
                        <?php
                        if ($registrationSuccessful) {
                            echo '<p style="color:green;">Thank you! You are now registered.</p>';
                            echo '<a href="Signup-Login.php" class="button">Login</a>';
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </header>
</body>

</html>
