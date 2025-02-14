<?php
include 'config.php';
include 'connection.php';

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDatabaseConnection();

    // Retrieve form data
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    // Query for the 'hall_admins' table
    $stmt = $conn->prepare("SELECT id, admin_id, hall_name, password FROM hall_admins WHERE admin_id = ? LIMIT 1");
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Set session variables
            $_SESSION['user_id'] = $user['admin_id']; // Match session_check expectation
            $_SESSION['user_type'] = 'hall_admin'; // Match session_check expectation
            $_SESSION['hall_name'] = $user['hall_name']; // Optional for dashboard use

            header("Location: DashboardHallAdmin.php");
            exit();
        } else {
            $message = "Invalid credentials! Incorrect password.";
        }
    } else {
        $message = "Invalid credentials! Admin ID not found.";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Admin Login - NSTU Academia</title>
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
        <h2 class="text-3xl font-bold text-center mb-4">Hall Admin Login</h2>
        <?php if (!empty($message)): ?>
            <div class="bg-red-100 text-red-600 p-4 rounded mb-6">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-6">
            <div>
                <label for="admin_id" class="block text-sm font-medium text-gray-700">Admin ID</label>
                <input type="text" id="admin_id" name="admin_id" required placeholder="Enter your Admin ID"
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