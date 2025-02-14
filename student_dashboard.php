<?php
session_start();
include 'connection.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT studentId, studentName, email FROM students WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("User details not found.");
    }
    $stmt->close();
} else {
    die("Failed to fetch user details.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Welcome, <?= htmlspecialchars($user['studentName']) ?></h1>
    </header>
    <main class="p-6">
        <p><strong>Student ID:</strong> <?= htmlspecialchars($user['studentId']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    </main>
</body>
</html>
