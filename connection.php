<?php
// connection.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nstu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set and collation for the connection:
$conn->set_charset("utf8mb4");
$conn->query("SET collation_connection = 'utf8mb4_unicode_ci'");