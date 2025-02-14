<?php
include './S_navbar.php'; // Assuming this file contains your navigation bar HTML
include 'connection.php'; // Assuming this file contains your database connection setup

$error_message = "";
$student = null;

// Check if session variable 'id' is set
if (isset($_SESSION['student_id'])) {
    $studentId = $_SESSION['student_id'];

    // Prepare SQL statement to fetch student details
    $stmt = $conn->prepare("SELECT id, studentId, studentName, email, stu_img, department, session_year FROM students WHERE studentId = ? LIMIT 1");

    if ($stmt) {
        $stmt->bind_param("s", $studentId); // Assuming studentId is a string
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch student data
            $student = $result->fetch_assoc();
        } else {
            // Student not found
            $error_message = "Student not found.";
        }

        $stmt->close();
    } else {
        // Failed to prepare SQL statement
        $error_message = "Failed to prepare SQL statement.";
    }
} else {
    // Handle case where session 'student_id' is not set (e.g., user not logged in)
    $error_message = "Session student_id not set. Please log in.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - NSTU Academia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
           
        }

        /* Container for the profile */
        .profile-container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: auto;
        }

        /* Profile image */
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #00fffc;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        /* Hover effect for profile image */
        .profile-image:hover {
            transform: scale(1.05);
        }

        /* Profile header */
        .profile-header {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Profile info section */
        .profile-info {
            margin-bottom: 2rem;
            text-align: left;
            padding: 0 1.5rem;
            width: 100%;
        }

        /* Profile info details */
        .profile-info div {
            font-size: 1.1rem;
            color: #4b5563;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Profile info labels */
        .profile-info span {
            font-weight: 600;
            color: #1e293b;
        }

        /* Logout button */
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
        }

        /* Hover effect for logout button */
        .logout-btn:hover {
            background-color: #00b8b8;
            transform: translateY(-2px);
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="profile">

    <div class="profile-container">

        <h2 class="profile-header"><?= htmlspecialchars($student['studentName']) ?></h2>

        <!-- Error Message -->
        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 text-red-600 p-4 rounded mb-6 animate__animated animate__shakeX">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php else: ?>

            <!-- Student Image and Name -->
            <img src="<?= !empty($student['stu_img']) ? htmlspecialchars($student['stu_img']) : 'default-avatar.png' ?>" alt="Student Image" class="profile-image">

            <div class="profile-info">
                <div><span>Student ID:</span> <?= htmlspecialchars($student['studentId']) ?></div>
                <div><span>Name:</span> <?= htmlspecialchars($student['studentName']) ?></div>
                <div><span>Email:</span> <?= htmlspecialchars($student['email']) ?></div>
                <div><span>Department:</span> <?= htmlspecialchars($student['department']) ?></div>
                <div><span>Session Year:</span> <?= htmlspecialchars($student['session_year']) ?></div>
            </div>

        <?php endif; ?>

        <div>
            <a href="logout.php" class="logout-btn">Log Out</a>
        </div>

    </div>

</body>

</html>

<?php
// Close the database connection.
$conn->close();
?>