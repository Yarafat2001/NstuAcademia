<?php
// Include the connection file
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_id = $_POST['registration_id'];
    $status = $_POST['status'];

    // Update registration status
    $sql = "UPDATE registration SET status = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $registration_id);

    if ($stmt->execute()) {
        header("Location: manage_registration.php?message=success");
    } else {
        echo "Error updating status: " . $conn->error;
    }
}
?>
