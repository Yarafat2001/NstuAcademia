<?php
include 'connection.php';

$message = ""; // Variable to hold error messages

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($conn) {
        // Retrieve form data
        $studentId = trim($_POST['studentId']);
        $password = trim($_POST['password']);

        // Prepare SQL statement to fetch user
        $stmt = $conn->prepare("SELECT id, studentId, studentName, password FROM students WHERE studentId = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['student_id'] = $user['studentId']; // Store student ID in session
                    $_SESSION['student_name'] = $user['studentName']; // Store user name
                    // Redirect to dashboard
                    header("Location: Home(student).php");
                    exit();
                } else {
                    $message = "Invalid credentials! Incorrect password.";
                }
            } else {
                $message = "Invalid credentials! User not found.";
            }

            $stmt->close();
        } else {
            $message = "Failed to prepare the SQL statement.";
        }

        $conn->close();
    } else {
        $message = "Database connection failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - NSTU Academia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-blue-600">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center mb-4">Student Login</h2>
        <?php if (!empty($message)): ?>
            <div class="bg-red-100 text-red-600 p-4 rounded mb-6">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-6">
            <div>
                <label for="studentId" class="block text-sm font-medium text-gray-700">Student ID</label>
                <input type="text" id="studentId" name="studentId" required placeholder="Enter your student ID"
                       class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required placeholder="Enter your password"
                           class="w-full mt-1 p-3 border border-gray-300 rounded-lg pr-10">
                    <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility()">
                        <i id="togglePasswordIcon" class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600">Log In
            </button>
        </form>
    </div>
</body>
</html>
