<?php

// session_start();  // Uncomment if you need to start the session here

include('./connection.php'); // Your file that sets up $conn

include('./S_navbar.php');

// Your file that sets up the navbar

// Make sure the department head is logged in
if (!isset($_SESSION['department_head_id'])) {
    header("Location: login.php");
    exit;
}

$department_head_id = $_SESSION['department_head_id'];

// Get department name from the dept_head table
$stmt = $conn->prepare("SELECT department_name FROM dept_head WHERE id = ?");
$stmt->bind_param("i", $department_head_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    die("Department Head not found.");
}

$dept_head = $res->fetch_assoc();
$department_name = $dept_head['department_name'];

// Get registration details for students from the department, where status is 'approve'
$stmt = $conn->prepare("
    SELECT 
        r.id AS reg_id, 
        r.student_id, 
        r.Year, 
        r.Semester, 
        r.status,
        r.director_status, 
        s.studentName
    FROM registration r
    JOIN students s ON r.student_id = s.studentId
    JOIN registration_payment rp ON r.id = rp.r_id
    WHERE s.department = ? 
      AND r.status = 'approve' 
      AND rp.pay = 'complete'
");
$stmt->bind_param("s", $department_name);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Registration</title>
    <!-- Include Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-6">Manage Registration</h1>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Serial</th>
                    <th class="py-2 px-4 border">Student ID</th>
                    <th class="py-2 px-4 border">Student Name</th>
                    <th class="py-2 px-4 border">Year</th>
                    <th class="py-2 px-4 border">Semester</th>
                    <th class="py-2 px-4 border">Status</th>
                    <th class="py-2 px-4 border">View</th>
                    <th class="py-2 px-4 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $serial = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr class="text-center">
                        <td class="py-2 px-4 border"><?php echo $serial++; ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['studentName']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['Year']); ?></td>
                        <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['Semester']); ?></td>
                        <td class="py-2 px-4 border" id="status-<?php echo $row['reg_id']; ?>">
                            <?php echo htmlspecialchars($row['director_status']); ?>
                        </td>
                        <td class="py-2 px-4 border">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded"
                                    onclick="viewRegistration(<?php echo $row['reg_id']; ?>)">
                                View
                            </button>
                        </td>
                        <td class="py-2 px-4 border">
                            <!-- Approve button -->
                            <button class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded mr-2"
                                    onclick="updateRegistration(<?php echo $row['reg_id']; ?>, 'approve')">
                                Approve
                            </button>

                            <!-- Reject button -->
                            <button class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded"
                                    onclick="updateRegistration(<?php echo $row['reg_id']; ?>, 'reject')">
                                Reject
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Modal for viewing registration details -->
        <div id="registrationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center" style="z-index: 9999;">
            <div class="bg-white rounded shadow-lg w-11/12 md:w-1/2 p-6">
                <div class="flex justify-end">
                    <button class="text-red-500" onclick="closeModal()">Close</button>
                </div>
                <div id="registrationDetails" class="mt-4"></div>
            </div>
        </div>
    </div>

    <script>
        // Function to update the registration status using AJAX
        function updateRegistration(reg_id, newStatus) {
            fetch('update_registration_head.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    reg_id: reg_id,
                    director_status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the displayed status on success
                    document.getElementById('status-' + reg_id).innerText = newStatus;
                    alert('Registration updated successfully.');
                } else {
                    alert('Error updating registration: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating registration.');
            });
        }

        // Function to view registration details using AJAX
        function viewRegistration(reg_id) {
            fetch('view_registration.php?reg_id=' + reg_id)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('registrationDetails').innerHTML = html;
                    document.getElementById('registrationModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching registration details.');
                });
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('registrationModal').classList.add('hidden');
        }
    </script>
</body>
</html>
