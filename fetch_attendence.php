<?php
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';
include 'S_navbar.php';

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch distinct years and semesters from the course table for the filter form
$yearSemQuery = "SELECT DISTINCT Year, Semester FROM course";
$yearSemResult = $conn->query($yearSemQuery);
if (!$yearSemResult) {
    die("Error fetching course data: " . $conn->error);
}
$yearSemester = [];
while ($row = $yearSemResult->fetch_assoc()) {
    $yearSemester[] = $row;
}

// Initialize attendance data and overall percentage
$attendance_data = [];
$overall_percentage = 0;

// Process filter form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter_attendance'])) {
    $selected_year = $_POST['year'];
    $selected_semester = $_POST['semester'];
    
    // Prepare query to fetch attendance records for the logged-in student for the selected Year and Semester.
    $attendanceQuery = "
        SELECT 
            a.student_id, 
            c.Course_Code, 
            c.Course_Name, 
            c.Year, 
            c.Semester, 
            a.stipulated_class, 
            a.no_of_classes_held, 
            a.attendance 
        FROM attendance a
        JOIN course c ON a.Course_id = c.Course_id
        WHERE a.student_id = ? AND c.Year = ? AND c.Semester = ?
    ";
    $stmt = $conn->prepare($attendanceQuery);
    $stmt->bind_param("iss", $student_id, $selected_year, $selected_semester);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance_data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Calculate overall attendance percentage
    $total_classes_held = array_sum(array_column($attendance_data, 'no_of_classes_held'));
    $total_attended = array_sum(array_column($attendance_data, 'attendance'));
    if ($total_classes_held > 0) {
        $overall_percentage = ($total_attended / $total_classes_held) * 100;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
        }
    </style>
</head>
<body class="bg-gray-100">
    
    <!-- Main Content -->
    <div class="container mx-auto mt-8 px-6">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto border border-gray-300">
            <h1 class="text-3xl font-bold mb-6 text-center">My Attendance</h1>
            
            <!-- Filter Form -->
            <form method="POST" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 mb-6">
                <div>
                    <label for="year" class="block text-gray-700 font-medium">Year</label>
                    <select name="year" id="year" class="border rounded p-2" required>
                        <option value="" disabled selected>--Select Year--</option>
                        <?php 
                        $years = array_unique(array_column($yearSemester, 'Year'));
                        sort($years);
                        foreach ($years as $yearOption): ?>
                            <option value="<?= htmlspecialchars($yearOption) ?>"><?= htmlspecialchars($yearOption) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="semester" class="block text-gray-700 font-medium">Semester</label>
                    <select name="semester" id="semester" class="border rounded p-2" required>
                        <option value="" disabled selected>--Select Semester--</option>
                        <?php 
                        $semesters = array_unique(array_column($yearSemester, 'Semester'));
                        sort($semesters);
                        foreach ($semesters as $semOption): ?>
                            <option value="<?= htmlspecialchars($semOption) ?>"><?= htmlspecialchars($semOption) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" name="filter_attendance" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Show Attendance
                    </button>
                </div>
            </form>
            
            <!-- Attendance Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Course Code</th>
                            <th class="border px-4 py-2">Course Name</th>
                            <th class="border px-4 py-2">Year</th>
                            <th class="border px-4 py-2">Semester</th>
                            <th class="border px-4 py-2">Stipulated Classes</th>
                            <th class="border px-4 py-2">Classes Held</th>
                            <th class="border px-4 py-2">Classes Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($attendance_data) > 0): ?>
                            <?php foreach ($attendance_data as $row): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['Course_Code']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['Course_Name']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['Year']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['Semester']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['stipulated_class']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['no_of_classes_held']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['attendance']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="border px-4 py-2 text-center" colspan="7">No attendance data found for the selected Year and Semester.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Overall Attendance Percentage -->
            <?php if ($overall_percentage > 0): ?>
                <div class="mt-6 text-center">
                    <p class="text-lg font-semibold">
                        Overall Attendance: 
                        <span class="<?= $overall_percentage >= 75 ? 'text-green-500' : 'text-red-500' ?>">
                            <?= number_format($overall_percentage, 2) ?>%
                        </span>
                    </p>
                    <?php if ($overall_percentage >= 75): ?>
                        <a href="payment.php" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Proceed to Pay
                        </a>
                    <?php else: ?>
                        <p class="mt-4 text-red-500 font-bold">Your attendance is below 75%. Please contact your course coordinator.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <footer class="bg-gray-200 text-center py-4 mt-10">
        <p>&copy; 2024 NstuAcademia. All rights reserved.</p>
    </footer>
</body>
</html>
