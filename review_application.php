<?php

include './S_navbar.php';
include 'connection.php';

// Check if the application ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Application ID is required.");
}

$applicationID = intval($_GET['id']); // Get application ID from URL and sanitize

// Fetch application details
$sql = "
    SELECT ha.*, s.studentName 
    FROM hall_applications ha
    INNER JOIN students s 
    ON ha.student_id = s.studentId COLLATE utf8mb4_unicode_ci
    WHERE ha.id = ?
";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("i", $applicationID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Application not found.");
}

$application = $result->fetch_assoc();

// Handle form submission (approval/rejection)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status']; // 'approved' or 'rejected'
    $reviewNotes = $_POST['reviewNotes'];

    $updateSQL = "UPDATE hall_applications SET status = ?, review_notes = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSQL);
    if (!$updateStmt) {
        die("SQL error: " . $conn->error);
    }

    $updateStmt->bind_param("ssi", $status, $reviewNotes, $applicationID);

    if ($updateStmt->execute()) {
        $message = "Application has been " . htmlspecialchars($status) . " successfully.";
    } else {
        $message = "Error updating application: " . $updateStmt->error;
    }

    $updateStmt->close();

    // Refresh the page to show updated data
    // header("Location: review_application.php?id=$applicationID");
    exit();
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Application</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #edf2f7, #e2e8f0);
            min-height: 100vh;
            /* display: flex; */
            align-items: center;
            justify-content: center;
        }

        .card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: #ffffff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: #ffffff;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .file-list li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container mx-auto px-4 py-8">
        <div class="card mx-auto">
            <h1 class="text-3xl font-bold text-center text-gray-700 mb-6">Review Application</h1>

            <?php if (!empty($message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Application Details</h2>
                <div class="text-gray-600 space-y-2">
                    <!-- <p><strong>Application ID:</strong> <?= htmlspecialchars($application['id']) ?></p> -->
                    <p><strong>Student Name:</strong> <?= htmlspecialchars($application['studentName']) ?></p>
                    <p><strong>Student ID:</strong> <?= htmlspecialchars($application['student_id']) ?></p>
                    <p><strong>Semester:</strong> <?= htmlspecialchars($application['semester']) ?></p>
                    <p><strong>Hall Preference:</strong> <?= htmlspecialchars($application['hall_preference']) ?></p>
                    <p><strong>Special Note:</strong> <?= htmlspecialchars($application['special_note'] ?? 'None') ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($application['status']) ?></p>
                    <p><strong>Review Notes:</strong> <?= htmlspecialchars($application['review_notes'] ?? 'None') ?></p>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Uploaded Files</h2>
                <ul class="file-list text-blue-500">
                    <?php foreach (['academic_transcript_file', 'disability_certificate_file', 'income_certificate_file', 'orphan_certificate_file', 'minority_certificate_file', 'bncc_certificate_file', 'nid_birth_certificate_file', 'masters_admission_receipt_file'] as $fileKey): ?>
                        <?php if (!empty($application[$fileKey])): ?>
                            <li>
                                <a href="<?= htmlspecialchars($application[$fileKey]) ?>" target="_blank" class="hover:underline">
                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $fileKey))) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="status" class="block font-semibold text-gray-700 mb-2">Application Status</label>
                    <select id="status" name="status" required class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                </div>

                <div>
                    <label for="reviewNotes" class="block font-semibold text-gray-700 mb-2">Review Notes</label>
                    <textarea id="reviewNotes" name="reviewNotes" rows="4" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter review notes..."></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit" class="btn-primary px-4 py-2 rounded-lg font-semibold">Submit</button>
                    <a href="ManageApplication.php" class="btn-secondary px-4 py-2 rounded-lg font-semibold">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

