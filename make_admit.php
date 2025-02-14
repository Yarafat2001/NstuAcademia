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
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .admit-card {
            max-width: 800px;
            margin: 2rem auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            background-color: white;
        }

        .header-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            text-align: center;
            padding: 1rem;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

        /* Student & Exam Details */
        .content {
            padding: 2rem;
        }

        .student-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .student-info div {
            font-size: 1rem;
            color: #4b5563;
        }

        .student-info span {
            font-weight: 600;
            color: #1e293b;
        }

        .student-info p {
            margin-bottom: 0.5rem;
        }

        .header {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .subheader {
            font-size: 1.2rem;
            color: #6b7280;
        }

        /* Instructions */
        .instructions h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: bold;
            color: #1e293b;
        }

        .instructions ul {
            padding-left: 1.5rem;
        }

        .instructions li {
            color: #4b5563;
            margin-bottom: 0.5rem;
        }

        /* Button */
        .logout-btn {
            background-color: #00fffc;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: inline-block;
            margin-top: 1rem;
            text-align: center;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #00b8b8;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <div class="admit-card">

        <!-- Header Section -->
        <div class="header-bg">
            <div>
                <h1 class="header">Admit Card</h1>
                <p class="subheader">Examination 2025</p>
            </div>

            <div>
                <?php if (!empty($student['stu_img'])): ?>
                    <img src="<?php echo htmlspecialchars($student['stu_img']); ?>" alt="Student Photo" class="profile-image">
                <?php else: ?>
                    <img src="default_student.png" alt="Default Student Photo" class="profile-image">
                <?php endif; ?>
            </div>
        </div>

        <!-- Student & Exam Details -->
        <div class="content">
            <div class="student-info">
                <div>
                    <p><span>Name:</span> <?php echo htmlspecialchars($student['studentName']); ?></p>
                    <p><span>Student ID:</span> <?php echo htmlspecialchars($student['studentId']); ?></p>
                    <p><span>Department:</span> <?php echo htmlspecialchars($student['department']); ?></p>
                </div>

                <div>
                    <p><span>Session:</span> <?php echo htmlspecialchars($student['session_year']); ?></p>
                    <p><span>Exam Date:</span> October 15, 2025</p>
                    <p><span>Exam Center:</span> Main Campus, Hall 3</p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="instruction-card instructions">
                <h3>Instructions:</h3>
                <ul>
                    <li>Bring a valid photo ID along with this admit card.</li>
                    <li>Arrive at the exam center at least 30 minutes before the scheduled time.</li>
                    <li>Switch off all mobile phones and electronic devices.</li>
                    <li>Follow all instructions provided by the exam invigilators.</li>
                </ul>
            </div>
        </div>

        <!-- Footer (Optional) -->
        <div class="footer-bg">
            <p>Powered by NSTU Exam Controller</p>
        </div>

    </div>

</body>

</html>
