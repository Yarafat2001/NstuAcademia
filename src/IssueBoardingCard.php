<?php
include 'navbar(hallAdmin).php';
include 'connection.php'; // Database connection
require 'PHPMailer/PHPMailer.php'; // Include PHPMailer manually
require 'PHPMailer/SMTP.php'; // Include SMTP class
require 'PHPMailer/Exception.php'; // Include Exception class

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch all approved applications
$sql = "SELECT * FROM hall_applications WHERE status = 'approved'";
$result = $conn->query($sql);

// Handle form submission for classification and email sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    $review_notes = $_POST['review_notes'];

    // Fetch student email and name from the database
    $stmt = $conn->prepare("SELECT edu_email, student_name FROM hall_applications WHERE id = ?");
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->bind_result($student_email, $student_name);
    $stmt->fetch();
    $stmt->close();

    // Validate email
    if (!filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='bg-red-100 text-red-700 p-4 rounded'>Invalid email address: $student_email</p>";
        exit;
    }

    // Update application status and review notes
    $stmt = $conn->prepare("UPDATE hall_applications SET status = ?, review_notes = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $review_notes, $application_id);

    if ($stmt->execute()) {
        // Send appropriate email based on classification
        if ($status === 'rejected') {
            sendMail(
                $student_email,
                "Application Rejected",
                "Dear $student_name,<br><br>Your application has been rejected due to the following reason:<br>$review_notes.<br><br>Please contact the administration for further details."
            );
        } elseif ($status === 'viva') {
            sendMail(
                $student_email,
                "Viva Invitation",
                "Dear $student_name,<br><br>Your application requires further verification. Please attend the viva on the scheduled date.<br><br>Regards,<br>Hall Administration"
            );
        } elseif ($status === 'qualified') {
            sendMailWithBoardingCard(
                $student_email,
                "Congratulations! Boarding Card Approved",
                "Dear $student_name,<br><br>Congratulations! Your application has been approved. Please find your boarding card attached.<br><br>Regards,<br>Hall Administration"
            );
        }
        echo "<p class='bg-green-100 text-green-700 p-4 rounded'>Application status updated and email sent successfully.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-700 p-4 rounded'>Error updating application: " . $stmt->error . "</p>";
    }
}

// Function to send an email using PHPMailer
function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.nstu.edu.bd'; // Replace with your institution's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@nstu.edu.bd'; // Your full NSTU email address
        $mail->Password = 'your-email-password'; // Your email password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or PHPMailer::ENCRYPTION_SMTPS for SSL
        $mail->Port = 587; // or 465 if using SSL

        // Sender and recipient
        $mail->setFrom('your-email@nstu.edu.bd', 'Hall Administration');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}

// Function to send an email with a boarding card
function sendMailWithBoardingCard($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.nstu.edu.bd'; // Replace with your institution's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@nstu.edu.bd'; // Your full NSTU email address
        $mail->Password = 'your-email-password'; // Your email password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or PHPMailer::ENCRYPTION_SMTPS for SSL
        $mail->Port = 587; // or 465 if using SSL

        // Sender and recipient
        $mail->setFrom('your-email@nstu.edu.bd', 'Hall Administration');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Attach the boarding card (example file path)
        $mail->addAttachment('path/to/boarding_card.pdf', 'BoardingCard.pdf');

        $mail->send();
    } catch (Exception $e) {
        echo "Error sending email with boarding card: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classify Approved Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Classify Approved Applications</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table-auto w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Student Name</th>
                        <th class="px-4 py-2">Student ID</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['student_name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['student_id']) ?></td>
                            <td class="px-4 py-2">
                                <form method="POST" action="">
                                    <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                                    
                                    <div class="mb-4">
                                        <label for="status_<?= $row['id'] ?>" class="block font-bold">Classify</label>
                                        <select id="status_<?= $row['id'] ?>" name="status" required class="w-full border rounded-md p-2">
                                            <option value="rejected">Not Right Document</option>
                                            <option value="viva">Send for Viva</option>
                                            <option value="qualified">Qualified</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="review_notes_<?= $row['id'] ?>" class="block font-bold">Review Notes</label>
                                        <textarea id="review_notes_<?= $row['id'] ?>" name="review_notes" rows="2" class="w-full border rounded-md p-2" placeholder="Add notes"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-600">No approved applications to classify.</p>
        <?php endif; ?>
    </div>
</body>
</html>
