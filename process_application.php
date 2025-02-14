<?php
include 'connection.php'; // Ensure the database connection is included

// Check if form data is submitted
if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    // Debug: Check what is being received
    var_dump($_POST); // This will show the received action and ID

    // Validate the action (approve or reject)
    if ($action === 'approve') {
        $status = 'approved'; // Set status to 'approved'
    } elseif ($action === 'reject') {
        $status = 'rejected'; // Set status to 'rejected'
    } else {
        echo "Invalid action.";
        exit;
    }

    // Update the application status based on the action
    $sql = "UPDATE hall_applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind the parameters (status and id)
    $stmt->bind_param("si", $status, $id); 

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p>Application has been " . htmlspecialchars($status) . " successfully.</p>";
        echo "<a href='admin_dashboard.php'>Go back to dashboard</a>"; // Redirect to the dashboard
    } else {
        echo "Error updating application: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
