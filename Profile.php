<?php

include './S_navbar.php'; // Assuming this file contains your navigation bar HTML
include 'connection.php'; // Assuming this file contains your database connection setup

$error_message = "";
$student = null;

// Check if session variable 'id' is set
if (isset($_SESSION['student_id'])) {
    $studentId = $_SESSION['student_id'];

    // Prepare SQL statement to fetch student details
    $stmt = $conn->prepare("SELECT id, studentId, studentName, email FROM students WHERE studentId = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $studentId); // Assuming studentId is a string, adjust if necessary (e.g., "s" for string)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch student data
            $student = $result->fetch_assoc();
        } else {
            // Student not found, handle error (though it should not occur if session is correctly managed)
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-center mb-4">Student Profile</h2>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 text-red-600 p-4 rounded mb-6">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <div>
                        <span class="font-semibold">Student ID:</span> <?= htmlspecialchars($student['studentId']) ?>
                    </div>
                    <div>
                        <span class="font-semibold">Name:</span> <?= htmlspecialchars($student['studentName']) ?>
                    </div>
                    <div>
                        <span class="font-semibold">Email:</span> <?= htmlspecialchars($student['email']) ?>
                    </div>
                    <div>
                        <!-- <span class="font-semibold">Phone:</span> <?= htmlspecialchars($student['phone']) ?> -->
                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-6 text-center">
                <a href="logout.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold">Log Out</a>
            </div>
        </div>
    </div>
</body>

</html>
