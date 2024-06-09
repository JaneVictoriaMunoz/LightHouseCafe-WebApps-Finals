<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="ContactUs.css">
    <script src="https://kit.fontawesome.com/ec1a397272.js" crossorigin="anonymous"></script>
    <title>Contact Form</title>
    <style>
        /* CONTACT US */
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 3%;
        }
        .message-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100%;
        }
        .message {
            font-size: 24px;
            color: green;
        }
        .form-container {
            width: 100%;
        }
        .send-another-form {
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
        .send-another-form:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);

    // Import PHPMailer classes into the global namespace
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    // Check if user is logged in
    if (!isset($_SESSION['userID'])) {
        echo "<div class='message-container'><p class='message' style='color: red;'>You need to be logged in to send a message.</p></div>";
        exit;
    }

    // Retrieve user details from session
    $fullname = $_SESSION['fullname'];
    $email = $_SESSION['email'];

    $formSubmitted = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $message = $_POST['message'];

        // Set the form submitted flag to true
        $formSubmitted = true;

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
            $mail->Subject = 'New Contact Us Submission';
            $mail->Body    = "<h1>New Contact Us Submission</h1>
                              <p><strong>Name:</strong> $fullname</p>
                              <p><strong>Email:</strong> $email</p>
                              <p><strong>Message:</strong> $message</p>";
            $mail->AltBody = "New Contact Us Submission\n
                              Name: $fullname\n
                              Email: $email\n
                              Message: $message";

            $mail->send();
        } catch (Exception $e) {
            // Handle email sending error if needed
        }
    }
    ?>

    <div class="container">
        <div class="contact-form">
            <h1 class="contact-head">CONTACT US</h1>
            <p class="contact-text">Please use the form below to send us a message and we will reply within one business day.<br>
                You can also drop us an email anytime or feel free to give us a call. We’d love to hear from you!</p>
            <div class="form-container">
                <?php if ($formSubmitted): ?>
                    <div class="message-container">
                        <p class="message">Thank you for submitting your form, we will reply within one business day.</p>
                        <a href="Contact Us.php" class="send-another-form">Send another form?</a>
                    </div>
                <?php else: ?>
                    <form method="post" action="">
                        <textarea id="message" name="message" placeholder="Your Message or Comments" required></textarea><br><br>
                        <input type="submit" value="SEND">
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="location-info">
            <h1 class="located-head">WE ARE LOCATED AT</h1>
            <div class="info-rows">
                <div class="address">
                    <div class="info-item">
                        <img src="Image/maps-and-flags.png" alt="Address" width="70" height="70">
                        <div>
                            <h2 class="address-head">Our address:</h2>
                            <p>Ground floor, Lighthouse Bible Baptist Church, 89 ROTC Hunters St.,<br>
                                Barangay Tatalon, Quezon City, Philippines</p>
                        </div>
                    </div>
                </div>
                <div class="contact-details">
                    <div class="info-item">
                        <img src="Image/call.png" alt="Phone" width="70" height="70">
                        <div>
                            <h2 class="phone-head">Phone:</h2>
                            <p>0995 925 1083</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <img src="Image/mail.png" alt="Email" width="70" height="70">
                        <div>
                            <h2 class="email-head">Email:</h2>
                            <p>lighthousecafe@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div><br><br><br><br><br><br>
            <div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d28027.93712454293!2d120.97824511802457!3d14.626641901160038!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b640d2a084bb%3A0xe3e29be396a91d2f!2sLighthouse%20Bible%20Baptist%20Church!5e0!3m2!1sen!2sph!4v1717541764582!5m2!1sen!2sph" width="720" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</body>

</html>
