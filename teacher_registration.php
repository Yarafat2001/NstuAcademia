<?php
session_start();

// Database connection
function getDatabaseConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nstu_academia";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

$conn = getDatabaseConnection();
$error_message = "";
$success_message = "";

// Handle Sign-Up
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $role = $_POST['role'];
    $name = trim($_POST['name']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    if ($role === 'teacher') {
        $email = trim($_POST['email']);
        $query = "INSERT INTO teacher (Name, Edu_Mail, NID) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $password);
    } elseif ($role === 'student') {
        $studentId = trim($_POST['studentId']);
        $fatherName = trim($_POST['fatherName']);
        $query = "INSERT INTO profiles (studentId, studentName, fatherName) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $studentId, $name, $fatherName);
    } elseif ($role === 'hall_admin') {
        $adminId = trim($_POST['adminId']);
        $hallName = trim($_POST['hallName']);
        
        // First, insert into the `users` table
        $userInsertQuery = "INSERT INTO users (name, role, password) VALUES (?, 'hall_admin', ?)";
        $stmt = $conn->prepare($userInsertQuery);
        $stmt->bind_param("ss", $name, $password);

        if ($stmt->execute()) {
            $newUserId = $stmt->insert_id; // Get the newly inserted user's ID

            // Now insert into the `hall_admins` table
            $hallAdminInsertQuery = "INSERT INTO hall_admins (admin_id, name, hall_name) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($hallAdminInsertQuery);
            $stmt->bind_param("iss", $newUserId, $name, $hallName);

            if ($stmt->execute()) {
                $success_message = "Hall Admin account created successfully! You can now log in.";
            } else {
                $error_message = "Error creating hall admin account: " . $conn->error;
            }
        } else {
            $error_message = "Error creating user: " . $conn->error;
        }
    }

    if ($role !== 'hall_admin') {
        if ($stmt->execute()) {
            $success_message = "Account created successfully! You can now log in.";
        } else {
            $error_message = "Error creating account: " . $conn->error;
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f0f0f5;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold text-gray-700 text-center mb-6">Sign Up to NstuAcademia</h2>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <!-- Sign-Up Form -->
        <form id="signupForm" method="POST" class="space-y-4">
            <input type="hidden" name="signup" value="1">
            <label for="role" class="block text-gray-600 font-medium mb-2">Select Role</label>
            <select id="signupRole" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300" required>
                <option value="" disabled selected>Select Role</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
                <option value="hall_admin">Hall Administration</option>
            </select>

            <div>
                <label for="name" class="block text-gray-600 font-medium mb-2">Full Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Enter your name" required>
            </div>

            <div id="signupStudentFields" class="hidden">
                <label for="studentIdSignup" class="block text-gray-600 font-medium mb-2">Student ID</label>
                <input type="text" id="studentIdSignup" name="studentId" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Enter your Student ID">
                <label for="fatherName" class="block text-gray-600 font-medium mb-2">Father's Name</label>
                <input type="text" id="fatherName" name="fatherName" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Enter your Father's Name">
            </div>

            <div id="signupTeacherFields" class="hidden">
                <label for="emailSignup" class="block text-gray-600 font-medium mb-2">Email</label>
                <input type="email" id="emailSignup" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Enter your email">
            </div>

            <div id="signupAdminFields" class="hidden">
                <label for="adminIdSignup" class="block text-gray-600 font-medium mb-2">Admin ID</label>
                <input type="text" id="adminIdSignup" name="adminId" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Enter Admin ID">
                <label for="hallName" class="block text-gray-600 font-medium mb-2">Hall Name</label>
                <input type="text" id="hallName" name="hallName" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Enter Hall Name">
            </div>

            <div>
                <label for="signupPassword" class="block text-gray-600 font-medium mb-2">Password</label>
                <input type="password" id="signupPassword" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">Sign Up</button>
        </form>
    </div>

    <script>
        const signupRole = document.getElementById("signupRole");
        const signupStudentFields = document.getElementById("signupStudentFields");
        const signupTeacherFields = document.getElementById("signupTeacherFields");
        const signupAdminFields = document.getElementById("signupAdminFields");

        signupRole.addEventListener("change", () => {
            signupStudentFields.classList.add("hidden");
            signupTeacherFields.classList.add("hidden");
            signupAdminFields.classList.add("hidden");

            if (signupRole.value === "student") signupStudentFields.classList.remove("hidden");
            else if (signupRole.value === "teacher") signupTeacherFields.classList.remove("hidden");
            else if (signupRole.value === "hall_admin") signupAdminFields.classList.remove("hidden");
        });
    </script>
</body>
</html>
