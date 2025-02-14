<?php
include('./connection.php'); // This file should initialize $conn

if (!isset($_GET['reg_id'])) {
    echo "<div class='text-red-500'>No registration specified.</div>";
    exit;
}

$reg_id = intval($_GET['reg_id']);

// Fetch registration details along with student's name and image
$stmt = $conn->prepare("SELECT r.*, s.studentName, s.stu_img 
                        FROM registration r
                        JOIN students s ON r.student_id = s.studentId
                        WHERE r.id = ?");
$stmt->bind_param("i", $reg_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $registration = $result->fetch_assoc();
    ?>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row">
            <!-- Left Column: Registration Details -->
            <div class="md:w-2/3">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Registration Details</h2>
                <p class="mb-2">
                    <span class="font-semibold">Student ID:</span>
                    <?php echo htmlspecialchars($registration['student_id']); ?>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">Student Name:</span>
                    <?php echo htmlspecialchars($registration['studentName']); ?>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">Year:</span>
                    <?php echo htmlspecialchars($registration['Year']); ?>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">Semester:</span>
                    <?php echo htmlspecialchars($registration['Semester']); ?>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">Status:</span>
                    <?php echo htmlspecialchars($registration['status']); ?>
                </p>
            </div>
            <!-- Right Column: Student Image -->
            <div class="md:w-1/3 flex justify-center items-center mt-4 md:mt-0">
                <?php if (!empty($registration['stu_img'])): ?>
                    <img src="<?php echo htmlspecialchars($registration['stu_img']); ?>" alt="Student Image" class="rounded shadow-md max-h-48 object-cover">
                <?php else: ?>
                    <img src="default_student.png" alt="Default Student Image" class="rounded shadow-md max-h-48 object-cover">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
} else {
    echo "<div class='text-red-500'>Registration not found.</div>";
}
?>
