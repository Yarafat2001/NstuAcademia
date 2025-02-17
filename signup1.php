<?php
session_start();
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Validate email format and domain
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_contains($email, '@student.nstu.edu.bd')) {
        $error = "Invalid email address. Please provide your NSTU student email.";
    } else {
        // Generate OTP
        $otp = random_int(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP to the provided email
        if (sendOtp($email, $otp)) {
            header('Location: verify_otp.php');
            exit;
        } else {
            $error = "Failed to send OTP. Please try again.";
        }
    }
}

// Function to send OTP via email
function sendOtp($to, $otp)
{
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your institution's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'yeasin2516@student.nstu.edu.bd'; // Your email
        $mail->Password = 'qugh fedl wfhs tqmp';  // Replace with your password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('yeasin2516@student.nstu.edu.bd', 'NSTU Signup');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for NSTU Signup';
        $mail->Body = "Dear Student,<br><br>Your OTP for signup is: <strong>$otp</strong>.<br><br>Regards,<br>NSTU Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - NSTU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">

    <div class="bg-white shadow-lg rounded-2xl p-8 w-96 text-center transform transition-all duration-500 hover:scale-105">
        
        <img src="./nstu logo.jpg" alt="NSTU Logo" class="w-16 h-16 mx-auto mb-4">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Student Signup</h1>
        <p class="text-gray-500 text-sm mb-6">Enter your **NSTU student email** to get started.</p>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Signup Form -->
        <form method="POST" action="">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Education Email:</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 shadow-sm" placeholder="your-email@student.nstu.edu.bd" required>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold px-4 py-3 rounded-lg hover:shadow-lg hover:opacity-90 transition duration-300">
                Send OTP
            </button>
        </form>

        <p class="mt-4 text-gray-500 text-sm">Already have an account? <a href="login1.php" class="text-blue-600 hover:underline">Login here</a></p>

    </div>

</body>
</html>
