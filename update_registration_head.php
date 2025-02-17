<?php
// Include the connection file
include('./connection.php');

// Ensure that the request is a POST request with proper data
$data = json_decode(file_get_contents("php://input"), true);

// Check if required fields are present
if (isset($data['reg_id']) && isset($data['director_status'])) {
    $reg_id = $data['reg_id'];
    $director_status = $data['director_status'];

    // Validate the director status to avoid invalid values
    if ($director_status !== 'approve' && $director_status !== 'reject') {
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit;
    }

    // Prepare the update query to set the director status
    $stmt = $conn->prepare("UPDATE registration SET director_status = ? WHERE id = ?");
    $stmt->bind_param("si", $director_status, $reg_id);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update registration']);
    }
} else {
    // If the required parameters are not provided
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
}

?>
