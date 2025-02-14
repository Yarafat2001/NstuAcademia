<?php include 'navbar(teacher).php';
include 'connection.php';
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - NSTU Academia</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['teacher_name']); ?>!</h1>
    <p>You are successfully logged in as a teacher.</p>
</body>
</html>
