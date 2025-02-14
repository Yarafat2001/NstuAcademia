<?php

include './S_navbar.php';
include 'connection.php'; // Ensure the database connection is included

// Fetch all pending applications with student names
$sql = "
    SELECT ha.id, ha.student_id, s.studentName 
    FROM hall_applications ha
    INNER JOIN students s 
    ON ha.student_id = s.studentId COLLATE utf8mb4_general_ci
    WHERE ha.status = 'pending'
";


$result = $conn->query($sql);

// Check if query execution was successful
if (!$result) {
    die("Error fetching applications: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Pending Applications</h1>
        <?php if ($result->num_rows > 0): ?>
            <table class="table-auto w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Student Name</th>
                        <th class="px-4 py-2">Student ID</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['studentName']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['student_id']) ?></td>
                            <td class="px-4 py-2">
                                <a href="review_application.php?id=<?= urlencode($row['id']) ?>" 
                                   class="text-blue-500 hover:underline">Review</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-600">No pending applications found.</p>
        <?php endif; ?>
    </div>
</body>

</html>
