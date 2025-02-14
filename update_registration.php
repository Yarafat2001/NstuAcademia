<?php
header('Content-Type: application/json');
include('./connection.php'); // This file should initialize $conn

// Retrieve and decode the JSON payload from the request body
$data = json_decode(file_get_contents("php://input"), true);

// Validate that both reg_id and status are provided
if (!isset($data['reg_id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters.']);
    exit;
}

$reg_id = intval($data['reg_id']);
$status = $data['status'];

// Allowed statuses after your ALTER TABLE: 'active', 'approve', 'reject'
$allowedStatuses = ['active', 'approve', 'reject'];
if (!in_array($status, $allowedStatuses)) {
    echo json_encode(['success' => false, 'error' => 'Invalid status value provided.']);
    exit;
}

// Prepare the update statement
$stmt = $conn->prepare("UPDATE registration SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $reg_id);

// Execute the query and return a JSON response
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
?>
