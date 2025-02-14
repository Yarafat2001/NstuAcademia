<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "student") {
    header("Location: login.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getDatabaseConnection()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "nstu_academia";

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDatabaseConnection();

    // Retrieve form data
    $studentId = $_SESSION['user_id'];
    $fatherName = trim($_POST['fatherName']);
    $courseCoordinator = trim($_POST['courseCoordinator']);
    $session = trim($_POST['session']);
    $department = trim($_POST['department']);
    $hallName = trim($_POST['hallName']);
    $seatNumber = trim($_POST['seatNumber']);
    $profileImage = null;

    // Debug POST data
    var_dump($_POST);

    // Handle file upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Ensure the uploads directory exists
        }

        $fileName = uniqid() . "_" . basename($_FILES['profileImage']['name']);
        $profileImage = $uploadDir . $fileName;

        // Check if upload was successful
        if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $profileImage)) {
            $message = "Error uploading profile image.";
        }
    } elseif ($_FILES['profileImage']['error'] !== UPLOAD_ERR_NO_FILE) {
        $message = "File upload error: " . $_FILES['profileImage']['error'];
    }

    // Insert data into the `profiles` table
    if (empty($message)) {
        $stmt = $conn->prepare("
            INSERT INTO profiles (student_id, father_name, course_coordinator, session, department, hall_name, seat_number, profile_image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("isssssss", $studentId, $fatherName, $courseCoordinator, $session, $department, $hallName, $seatNumber, $profileImage);
        
        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center mb-6">Complete Your Profile</h2>
        <?php if (!empty($message)): ?>
            <div class="bg-<?php echo strpos($message, 'successfully') ? 'green' : 'red'; ?>-100 text-<?php echo strpos($message, 'successfully') ? 'green' : 'red'; ?>-600 p-4 rounded mb-6">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label for="fatherName" class="block text-sm font-medium text-gray-700">Father's Name</label>
                <input type="text" id="fatherName" name="fatherName" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="courseCoordinator" class="block text-sm font-medium text-gray-700">Course Coordinator</label>
                <input type="text" id="courseCoordinator" name="courseCoordinator" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
                <input type="text" id="session" name="session" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <input type="text" id="department" name="department" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="hallName" class="block text-sm font-medium text-gray-700">Hall Name</label>
                <input type="text" id="hallName" name="hallName" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="seatNumber" class="block text-sm font-medium text-gray-700">Seat Number</label>
                <input type="number" id="seatNumber" name="seatNumber" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="profileImage" class="block text-sm font-medium text-gray-700">Profile Image</label>
                <input type="file" id="profileImage" name="profileImage" accept=".jpg, .jpeg, .png" class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg mt-6">Submit</button>
        </form>
    </div>
</body>
</html>
