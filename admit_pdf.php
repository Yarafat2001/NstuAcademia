<?php

session_start();
include 'connection.php';

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login1.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Retrieve the student's details from the "students" table.
$stmt = $conn->prepare("SELECT studentId, studentName, stu_img, department, session_year FROM students WHERE studentId = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Student record not found.";
    exit();
}

$student = $result->fetch_assoc();
$stmt->close();

// Function to convert image to base64
function toBase64($filePath)
{
    if (!file_exists($filePath)) {
        die("File not found: $filePath");
    }
    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileData = file_get_contents($filePath);
    return 'data:image/' . $fileType . ';base64,' . base64_encode($fileData);
}

// Convert student image to Base64 (or use default image if not available)
$studentImage = !empty($student['stu_img']) ? toBase64($student['stu_img']) : toBase64('default_student.png');

// Include Dompdf
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Create Dompdf options
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

// Initialize Dompdf
$dompdf = new Dompdf($options);

// Generate HTML content
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admit Card</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f9fafb, #e0e0e0);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .admit-card {
            width: 100%;
            max-width: 800px;
            margin: 10px;
            padding: 15px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            position: relative;
            overflow: hidden;
        }
        .admit-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, #3498db, #8e44ad);
        }
        .header {
            text-align: center;
            background-color: #3498db;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 36px;
            font-weight: bold;
            margin: 0;
        }
        .header p {
            font-size: 16px;
            margin: 5px 0;
            color:rgb(216, 241, 243);
        }
        .profile-section {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
    text-align: center; /* Added for centering text and image */
}

.profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid #3498db;
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    margin-bottom: 15px;
    object-fit: cover; /* Ensures the image fits nicely within the circular border */
}

        .student-name {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
            color: #2C3E50;
            text-align: center;
        }
        .student-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 15px;
            border: 1px solid #ddd;
        }
        .student-info p {
            font-size: 14px;
            color: #333;
            margin: 8px 0;
            display: flex;
            align-items: center;
        }
        .student-info p strong {
            color: #2C3E50;
            min-width: 120px;
            font-weight: 600;
        }
        .student-info p i {
            margin-right: 8px;
            color: #3498db;
        }
        .instructions {
            margin: 20px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 15px;
            border: 1px solid #ddd;
        }
        .instructions h3 {
            font-size: 18px;
            color: #2C3E50;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .instructions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .instructions ul li {
            font-size: 12px;
            color: #555;
            margin: 8px 0;
            padding-left: 20px;
            position: relative;
        }
        .instructions ul li::before {
            content: "•";
            color: #3498db;
            font-size: 14px;
            position: absolute;
            left: 0;
            top: -2px;
        }
        .barcode {
            text-align: center;
            margin-top: 30px;
        }
        .barcode img {
            width: 240px;
            height: auto;
            border: 1px solid #e0e0e0;
            padding: 10px;
            border-radius: 10px;
            background: #fff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #7f8c8d;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="admit-card">
        <div class="header">
            <h1>Admit Card</h1>
            <p>Examination 2025</p>
        </div>

        <div class="profile-section">
            <img src="' . $studentImage . '" alt="Student Photo" class="profile-image">
            <div class="student-name">' . htmlspecialchars($student['studentName']) . '</div>
        </div>

        <div class="student-info">
            <div>
                <p><strong><i class="fas fa-id-card"></i> Student ID:</strong> ' . htmlspecialchars($student['studentId']) . '</p>
                <p><strong><i class="fas fa-building"></i> Department:</strong> ' . htmlspecialchars($student['department']) . '</p>
                <p><strong><i class="fas fa-calendar-alt"></i> Session:</strong> ' . htmlspecialchars($student['session_year']) . '</p>
                <p><strong><i class="fas fa-calendar-day"></i> Exam Date:</strong> October 15, 2025</p>
                <p><strong><i class="fas fa-map-marker-alt"></i> Exam Center:</strong> Main Campus, Hall 3</p>
            </div>
        </div>

        <div class="instructions">
            <h3>Instructions:</h3>
            <ul>
                <li>Bring a valid photo ID along with this admit card.</li>
                <li>Arrive at the exam center at least 30 minutes before the scheduled time.</li>
                <li>Switch off all mobile phones and electronic devices.</li>
                <li>Follow all instructions provided by the exam invigilators.</li>
            </ul>
        </div>

        <div class="barcode">
            <img src="https://barcode.tec-it.com/barcode.ashx?data=' . htmlspecialchars($student['studentId']) . '&code=Code128&dpi=96" alt="Barcode">
        </div>

        <div class="footer">
            Powered by <a href="#">NSTU Exam Controller</a>
        </div>
    </div>
</body>
</html>';

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (inline or download)
$dompdf->stream("admit_card.pdf", array("Attachment" => true));
