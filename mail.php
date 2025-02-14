<?php
// Define the email details
$to = "rrajibkd@gmail.com"; // Replace with recipient's email address
$subject = "Test Email from PHP";
$message = "Hello, this is a test email sent from a PHP script.";
$headers = "From: sender@example.com" . "\r\n" .
           "Reply-To: sender@example.com" . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Send the email
if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully to $to.";
} else {
    echo "Failed to send email.";
}
?>
