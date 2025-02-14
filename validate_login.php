<?php
session_start();

function getDatabaseConnection() {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'];
    $password = $_POST['password'];
    $conn = getDatabaseConnection();

    if ($user_type === "student" || $user_type === "teacher") {
        $email = $_POST['email'];

        $table = $user_type === "student" ? "students" : "teachers";
        $sql = "SELECT * FROM $table WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);

    } else if ($user_type === "hall_admin") {
        $hall_id = $_POST['hall_id'];

        $sql = "SELECT * FROM hall_admins WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hall_id, $password);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['user_type'] = $user_type;
        if ($user_type === "student") {
            header("Location: dashboard.php");
        } else if ($user_type === "teacher") {
            header("Location: teacher_profile.php");
        } else if ($user_type === "hall_admin") {
            header("Location: DashboardHallAdmin.php");
        }
        exit;
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href = 'login.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit;
}
?>
