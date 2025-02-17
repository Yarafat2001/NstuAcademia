<?php

session_start();
include 'connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $session_year = trim($_POST['session_year']);
    $department = trim($_POST['department']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Handle Profile Image Upload
    $profile_image = null;
    if (!empty($_FILES['profile_image']['name'])) {
        $upload_dir = "uploads/$student_id/"; 
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); 
        }

        $profile_image = $upload_dir . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image);
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO students (studentId, studentName, stu_img, email, department, session_year, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $student_id, $name, $profile_image, $email, $department, $session_year, $hashed_password);

    if ($stmt->execute()) {
        echo "Signup Successful!";
        header('Location: login1.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
