<?php

include './S_navbar.php';
include 'connection.php'; // Database connection
require 'PHPMailer.php'; // Include PHPMailer manually
require 'SMTP.php'; // Include SMTP class
require 'Exception.php'; // Include Exception class
require 'vendor/autoload.php'; // Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch all approved applications
$sql = "SELECT ha.*, s.studentName AS student_name 
        FROM hall_applications ha
        INNER JOIN students s 
        ON ha.student_id = s.studentId COLLATE utf8mb4_unicode_ci
        WHERE ha.status = 'approved'";

$result = $conn->query($sql);

// Handle form submission for classification and email sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    $review_notes = $_POST['review_notes']; // review notes can be empty for qualified students
    // Fetch student data
    $stmt = $conn->prepare("SELECT s.studentName, s.studentId, s.email, s.stu_img, s.department, ha.expiry_date 
 FROM students s
 INNER JOIN hall_applications ha ON s.studentId = ha.student_id
 WHERE ha.id = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->bind_result($student_name, $student_id, $student_email, $stu_img, $department, $expiry_date);
    if (!$stmt->fetch()) {
        die("Fetch failed: No data found for Application ID $application_id.");
    }
    $stmt->close();

    // Validate email
    if (!filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='bg-red-100 text-red-700 p-4 rounded'>Invalid email address: $student_email</p>";
        exit;
    }

    // Set file paths
    $student_image = __DIR__ . '/' . $stu_img; // Path to student's image
    $university_logo = __DIR__ . '/nstu logo.jpg'; // Path to the university logo 

    // Update application status and review notes
    $stmt = $conn->prepare("UPDATE hall_applications SET status = ?, review_notes = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $review_notes, $application_id);

    if ($stmt->execute()) {
        if (!empty($review_notes)) {
            // Send email with review notes
            sendMail(
                $student_email,
                "Application Update - Review Notes",
                "Dear $student_name,<br><br>Your application has been reviewed. Please note the following:<br>$review_notes.<br><br>Regards,<br>Hall Administration, NSTU"
            );
        }

        if ($status === 'qualified') {
            // Generate boarding card and send it as an attachment
            $pdfPath = generateBoardingCard($student_name, $student_id, $student_image, $department, $university_logo, $expiry_date);
            sendMailWithBoardingCard(
                $student_email,
                "Congratulations! Boarding Card Approved",
                "Dear $student_name,<br><br>Congratulations! Your application has been approved. Please find your boarding card attached.<br><br>Regards,<br>Hall Administration, NSTU",
                $pdfPath
            );
        }

        echo "<p class='bg-green-100 text-green-700 p-4 rounded'>Application status updated and email sent successfully.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-700 p-4 rounded'>Error updating application: " . $stmt->error . "</p>";
    }
}

// Function to send an email using PHPMailer
function sendMail($to, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your institution's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'yeasin2516@student.nstu.edu.bd'; // Your email
        $mail->Password = 'qugh fedl wfhs tqmp'; // Your email password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yeasin2516@student.nstu.edu.bd', 'Hall Administration, NSTU');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}

// Function to send an email with a boarding card
function sendMailWithBoardingCard($to, $subject, $body, $pdfPath)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yeasin2516@student.nstu.edu.bd';
        $mail->Password = 'qugh fedl wfhs tqmp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('yeasin2516@student.nstu.edu.bd', 'Hall Administration, NSTU');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->addAttachment($pdfPath, 'Hall_Boarding_Card.pdf');

        $mail->send();
    } catch (Exception $e) {
        echo "Error sending email with boarding card: {$mail->ErrorInfo}";
    }
}
// Function to convert a file to a Base64-encoded string
function toBase64($filePath)
{
    if (!file_exists($filePath)) {
        die("File not found: $filePath");
    }
    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileData = file_get_contents($filePath);
    return 'data:image/' . $fileType . ';base64,' . base64_encode($fileData);
}

// Function to generate a boarding card (PDF)
function generateBoardingCard($student_name, $student_id, $student_image, $department, $university_logo, $expiry_date)
{
    $options = new Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    // Convert images to Base64
    $studentImage = toBase64($student_image);

    $universityLogo = toBase64($university_logo);

    // HTML Content
    $html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Hall Boarding Card</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to bottom right, #f3f4f6, #eff6ff);
        }

        .card {
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 24px;
            width: 384px;
            text-align: center;
            border: 2px solid #2563eb; /* Border around the card */
        }

        .title {
            text-align: center;
            margin-bottom: 16px;
        }

        .title h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .logo-section img {
            width: 80px;
            height: 80px;
            margin-right: 12px;
        }

        .logo-section h1 {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1f2937;
            text-align: left;
        }

        .divider {
            border-top: 2px solid #e5e7eb;
            margin: 16px 0;
        }

        .student-image {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
        }

        .student-image img {
            width: 112px;
            height: 112px;
            border-radius: 50%;
            border: 4px solid #3b82f6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .student-info {
            text-align: center;
        }

        .student-info h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
        }

        .student-info p {
            font-size: 1rem;
            color: rgb(46, 37, 37);
            margin-top: 4px;
        }

        .expiry-section {
            margin-top: 24px;
            background: #f87171;
            color: white;
            text-align: center;
            border-radius: 8px;
            padding: 12px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .expiry-section p {
            margin: 0;
        }

        .expiry-section .label {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .expiry-section .date {
            font-size: 1.125rem;
            font-weight: 800;
        }
    </style>
</head>
<body>
    <div class='card'>
        <!-- Title -->
        <div class='title'>
            <h1>Hall Boarding Card</h1>
        </div>

        <!-- University Logo and Name -->
        <div class='logo-section'>
            <img src='{$universityLogo}' alt='University Logo'>
            <h1>Noakhali Science and Technology University</h1>
        </div>

        <!-- Divider -->
        <div class='divider'></div>

        <!-- Student Photo -->
        <div class='student-image'>
            <img src='{$studentImage}' alt='Student Photo'>
        </div>

        <!-- Student Information -->
        <div class='student-info'>
            <h1>{$student_name}</h1>
            <p>ID: {$student_id}</p>
            <p>Department: {$department}</p>
        </div>

        <!-- Expiry Date Section -->
        <div class='expiry-section'>
            <p class='label'>Valid Until:</p>
            <p class='date'>{$expiry_date}</p>
        </div>
    </div>
</body>
</html>
";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $uploadDir = __DIR__ . "/uploads/$student_id";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = "$uploadDir/Hall_Boarding_Card.pdf";
    file_put_contents($filePath, $dompdf->output());

    return $filePath;
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
                                        <select id="status_<?= $row['id'] ?>" name="status" required class="w-full border rounded-md p-2" onchange="toggleReviewNotes(this, <?= $row['id'] ?>)">
                                            <option value="rejected">Not Right Document</option>
                                            <option value="viva">Send for Viva</option>
                                            <option value="qualified" selected>Qualified</option>
                                        </select>
                                    </div>

                                    <div id="review_notes_container_<?= $row['id'] ?>" class="mb-4" style="display: none;">
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
    <script>
        function toggleReviewNotes(selectElement, id) {
            const reviewNotesContainer = document.getElementById(`review_notes_container_${id}`);
            // Show review notes only if status is not "qualified"
            if (selectElement.value === 'qualified') {
                reviewNotesContainer.style.display = 'none';
            } else {
                reviewNotesContainer.style.display = 'block';
            }
        }

        // Initialize visibility based on the default dropdown value
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select[name="status"]').forEach(function(select) {
                const id = select.id.split('_')[1]; // Extract the ID from the select element's ID
                toggleReviewNotes(select, id);
            });
        });
    </script>


</body>

</html>