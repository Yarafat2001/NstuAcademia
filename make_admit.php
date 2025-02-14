<?php
session_start();
include 'connection.php'; // This file should set up the $conn connection

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admit Card</title>
    <!-- Include Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Additional custom styling (optional) */
        .admit-card {
            max-width: 800px;
            margin: 2rem auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        .header-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .footer-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .instruction-card {
            background: #f9fafb;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        .instruction-card ul {
            padding-left: 1.5rem;
        }
        .instruction-card li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="admit-card bg-white">
        <!-- Header Section -->
        <div class="header-bg text-white p-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold">Admit Card</h1>
                <p class="mt-1 text-lg">Examination 2025</p>
            </div>
            <div>
                <?php if (!empty($student['stu_img'])): ?>
                    <img src="<?php echo htmlspecialchars($student['stu_img']); ?>" alt="Student Photo" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                <?php else: ?>
                    <img src="default_student.png" alt="Default Student Photo" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                <?php endif; ?>
            </div>
        </div>
        <!-- Student & Exam Details -->
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800">Student Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <p><span class="font-bold text-gray-700">Name:</span> <span class="text-gray-600"><?php echo htmlspecialchars($student['studentName']); ?></span></p>
                        <p><span class="font-bold text-gray-700">Student ID:</span> <span class="text-gray-600"><?php echo htmlspecialchars($student['studentId']); ?></span></p>
                        <p><span class="font-bold text-gray-700">Department:</span> <span class="text-gray-600"><?php echo htmlspecialchars($student['department']); ?></span></p>
                    </div>
                    <div class="space-y-3">
                        <p><span class="font-bold text-gray-700">Session:</span> <span class="text-gray-600"><?php echo htmlspecialchars($student['session_year']); ?></span></p>
                        <p><span class="font-bold text-gray-700">Exam Date:</span> <span class="text-gray-600">October 15, 2025</span></p>
                        <p><span class="font-bold text-gray-700">Exam Center:</span> <span class="text-gray-600">Main Campus, Hall 3</span></p>
                    </div>
                </div>
            </div>
            <!-- Instructions -->
            <div class="instruction-card">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Instructions:</h3>
                <ul class="list-disc list-inside text-gray-700">
                    <li>Bring a valid photo ID along with this admit card.</li>
                    <li>Arrive at the exam center at least 30 minutes before the scheduled time.</li>
                    <li>Switch off all mobile phones and electronic devices.</li>
                    <li>Follow all instructions provided by the exam invigilators.</li>
                </ul>
            </div>
        </div>
        <!-- Footer (Optional) -->
        <div class="footer-bg p-4 text-center text-sm text-white">
            <p>Powered by Your Institution Name</p>
        </div>
    </div>
</body>
</html>