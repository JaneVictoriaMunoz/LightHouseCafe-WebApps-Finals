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
    $login_email_username = $_POST['login_email_username'];
    $login_password = $_POST['login_password'];

    $sql = "SELECT * FROM user_account WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login_email_username, $login_email_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the user is an admin
        $isAdmin = false;
        $adminCredentials = [
            'dietherlighthouse@admin.com' => '010203',
            'janelighthouse@admin.com' => '112503'
        ];

        if (array_key_exists($user['email'], $adminCredentials) && $login_password == $adminCredentials[$user['email']]) {
            $isAdmin = true;
        }

        // Check if the user is an employee
        $isEmployee = false;
        $employeeCredentials = [
            'francelighthouse@employee.com' => '080603',
            'lorindalighthouse@employee.com' => '070809',
            'andreilighthouse@employee.com' => '040506'
        ];

        if (array_key_exists($user['email'], $employeeCredentials) && $login_password == $employeeCredentials[$user['email']]) {
            $isEmployee = true;
        }

        if ($isAdmin || $isEmployee || password_verify($login_password, $user['password'])) {
            // Password is correct, start a session and redirect to the appropriate page
            session_start();
            $_SESSION['user_id'] = $user['customerID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            if ($isAdmin) {
                header("Location: admindashboard.php");
            } elseif ($isEmployee) {
                header("Location: employeedashboard.php");
            } else {
                header("Location: landingpage.php");
            }
        } else {
            // Password is incorrect
            header("Location: signup-login.php?error=Incorrect password");
        }
    } else {
        // Username or email is incorrect
        header("Location: signup-login.php?error=Incorrect username or email");
    }

    $stmt->close();
}

$conn->close();
?>
