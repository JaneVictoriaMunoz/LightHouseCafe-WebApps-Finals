<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="feedbacks.css">
  <title>Feedback Form</title>
  <style>
    .message-container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
    }
    .message {
      font-size: 24px;
      color: green;
    }
    .back-button {
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 16px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }
    .back-button:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body class="feed-back-body">
  <?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL & ~E_NOTICE);

  // Import PHPMailer classes into the global namespace
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  // Load Composer's autoloader
  require 'vendor/autoload.php';

  session_start();

  // Check if user is logged in
  if (!isset($_SESSION['userID'])) {
    echo "<div class='message-container'><p class='message' style='color: red;'>You need to be logged in to submit feedback. <a href='Signup-Login.php' class='button'>Login</a></p></div>";
    exit;
  }

  // Retrieve user details from session
  $fullname = $_SESSION['fullname'];
  $email = $_SESSION['email'];

  $feedbackSubmitted = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_lighthouse";

    // Create connection
    $con = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
    }

    $pageRating = isset($_POST['page_rating']) ? $_POST['page_rating'] : null;
    $devices = isset($_POST['devices']) ? implode(", ", $_POST['devices']) : '';
    $otherDevice = isset($_POST['otherDevice']) ? $_POST['otherDevice'] : '';
    $comment = $_POST['comment'];

    if (!empty($otherDevice)) {
      $devices .= ", " . $otherDevice;
    }

    $sql = "INSERT INTO feedback (fullname, email, page_rating, devices, comment) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssiss", $fullname, $email, $pageRating, $devices, $comment);

    if ($stmt->execute()) {
      $feedbackSubmitted = true;

      // Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'cabreraart1367@gmail.com';                     // SMTP username
        $mail->Password   = 'eahm wenk ynme dbnt';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // Enable implicit TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // Recipients
        $mail->setFrom($email, $fullname); // Set from the logged-in user's email and name
        $mail->addAddress('cabreraart1367@gmail.com', 'Lighthouse Cafe'); // Add a recipient                  
        $mail->addReplyTo($email, $fullname);

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'New Feedback Submission';
        $mail->Body    = "<h1>New Feedback Submission</h1>
                          <p><strong>Full Name:</strong> $fullname</p>
                          <p><strong>Email:</strong> $email</p>
                          <p><strong>Page Rating:</strong> $pageRating</p>
                          <p><strong>Devices:</strong> $devices</p>
                          <p><strong>Comment:</strong> $comment</p>";
        $mail->AltBody = "New Feedback Submission\n
                          Full Name: $fullname\n
                          Email: $email\n
                          Page Rating: $pageRating\n
                          Devices: $devices\n
                          Comment: $comment";

        $mail->send();
      } catch (Exception $e) {
        // Handle email sending error if needed
      }
    } else {
      echo "<div class='message-container'><p class='message' style='color: red;'>Error: " . $sql . "<br>" . $con->error . "</p></div>";
    }

    $stmt->close();
    $con->close();
  }

  if ($feedbackSubmitted) {
    echo "<div class='message-container'>
            <p class='message'>Feedback submitted successfully!</p>
            <a href='Landingpage.php' class='back-button'>Back to Home</a>
          </div>";
  } else {
  ?>

      <nav>
          <div class="row1">
              <a href="Landingpage.php"><img src="Image/lighthouselogo.png" alt="Coffeeshop" class="logo"></a>
              <ul class="nav-bar">
                  <li><a href="Landingpage.php">Home</a></li>
                  <li><a href="Menu.php">Menu</a></li>
                  <li><a href="About Us.php">About Cafe</a></li>
                  <li><a href="Contact Us.php">Contact Us</a></li>
                  <li><a href="Feedback Form.php">Reviews</a></li>
                  <?php if (!isset($_SESSION['userID'])): ?>
                      <li><a href="Signup-Login.php">Login/Signup</a></li>
                  <?php endif; ?>
              </ul>
              <div class="nav-right">
                  <?php if (isset($_SESSION['userID'])): ?>
                      <div class="dropdown">
                          <button class="dropbtn"><?php echo ucwords(strtolower($_SESSION['fullname'])); ?></button>
                          <div class="dropdown-content">
                              <a href="logout.php">Logout</a>
                          </div>
                      </div>
                  <?php endif; ?>
                  <a href="ATC.php" class="cart-icon">
                      <img src="Image/finalcart.png" alt="Cart Logo">
                  </a>
              </div>
          </div>
      </nav>

  
    <div id="feedback-container">
      <div id="feedback-title">Tell us what you think!</div>
      <form method="POST" action="">
        <label for="rbPgRating">
          <div class="feedbackLabel">How would you rate our products and services?</div>
        </label>
        <fieldset class="selections">
          <label class="feedbackLabel">Needs Work</label>
          <input type="radio" name="page_rating" value="0">
          <input type="radio" name="page_rating" value="1">
          <input type="radio" name="page_rating" value="2">
          <input type="radio" name="page_rating" value="3">
          <input type="radio" name="page_rating" value="4">
          <input type="radio" name="page_rating" value="5">
          <input type="radio" name="page_rating" value="6">
          <input type="radio" name="page_rating" value="7">
          <input type="radio" name="page_rating" value="8">
          <input type="radio" name="page_rating" value="9">
          <input type="radio" name="page_rating" value="10">
          <label class="feedbackLabel">Great</label>
        </fieldset>
        <label for="rbPgRating">
          <div class="feedbackLabel">What devices do you use to access our site?</div>
        </label>
        <fieldset class="selections">
          <span class="feedbackLabel">
            <input type="checkbox" class="userDevice" id="Tablet" name="devices[]" value="Tablet"><label for="Tablet">  Tablet</label>
          </span>
          <span class="feedbackLabel">
            <input type="checkbox" class="userDevice" id="SmartPhone" name="devices[]" value="SmartPhone"><label for="SmartPhone">  Smart Phone</label>
          </span>
          <span class="feedbackLabel">
            <input type="checkbox" class="userDevice" id="Desktop" name="devices[]" value="Desktop"><label for="Desktop">  Desktop</label>
          </span>
          <br><br>
          <span class="feedbackLabel">
            <input type="checkbox" class="userDevice" id="Other" name="devices[]" value="Other"><label for="Other"><input type="text" class="userDevice" name="otherDevice" placeholder="Other Device" maxlength="25"></label>
          </span>
        </fieldset>
        <label for="comment">
          <div class="feedbackLabel">Additional Comments:</div>
        </label>
        <br>
        <textarea id="comment" class="feedbackInfo comment" name="comment" rows="5" maxlength="700" placeholder="What is your feedback to us?"></textarea>
        <input type="submit" class="btnSubmit" id="btnSubmit">
      </form>
    </div>
  <?php
  }
  ?>
</body>

</html>
