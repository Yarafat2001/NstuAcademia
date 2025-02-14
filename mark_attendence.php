<?php
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';
include 'S_navbar.php';

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// --- Get Teacher's Record ---
$sqlTeacher = "SELECT * FROM teacher WHERE id = ?";
$stmtTeacher = $conn->prepare($sqlTeacher);
$stmtTeacher->bind_param("i", $teacher_id);
$stmtTeacher->execute();
$resultTeacher = $stmtTeacher->get_result();
$teacher = $resultTeacher->fetch_assoc();
$stmtTeacher->close();

if (!$teacher) {
    die("<p class='text-center text-red-500'>Teacher not found.</p>");
}

// Get teacher's department (assumes column name is "Department")
$teacher_department = $teacher['Department'];

// --- Get Department ID for teacher's department ---
$dept_id = "";
$sqlDept = "SELECT Department_id FROM department WHERE Department_Name = ?";
$stmtDept = $conn->prepare($sqlDept);
$stmtDept->bind_param("s", $teacher_department);
$stmtDept->execute();
$resultDept = $stmtDept->get_result();
if ($rowDept = $resultDept->fetch_assoc()) {
    $dept_id = $rowDept['Department_id'];
}
$stmtDept->close();

// --- Fetch courses for teacher's department ---
$courses = [];
$sqlCourses = "SELECT Course_id, Course_Code, Course_Name FROM course WHERE Department_Id = ?";
$stmtCourses = $conn->prepare($sqlCourses);
$stmtCourses->bind_param("i", $dept_id);
$stmtCourses->execute();
$resultCourses = $stmtCourses->get_result();
while ($row = $resultCourses->fetch_assoc()) {
    $courses[] = $row;
}
$stmtCourses->close();

// --- Fetch students that belong to teacher's department ---
// Assumes the "students" table has a column "Department" storing the student's department.
$students_data = [];
$sqlStudents = "SELECT studentId, studentName FROM students WHERE Department = ?";
$stmtStudents = $conn->prepare($sqlStudents);
$stmtStudents->bind_param("s", $teacher_department);
$stmtStudents->execute();
$resultStudents = $stmtStudents->get_result();
while ($row = $resultStudents->fetch_assoc()) {
    $students_data[$row['studentId']] = $row['studentName'];
}
$stmtStudents->close();

// --- Process Attendance Form Submission ---
$successMsg = "";
$errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_attendance'])) {
    // Retrieve form data
    $course_id = intval($_POST['course_code']);
    $stipulated_class = intval($_POST['stipulated_class']);
    $no_of_classes_held = intval($_POST['no_of_classes_held']);
    $attendance_val = intval($_POST['attendance']);
    $student_id_selected = trim($_POST['student_id']); // Do NOT use intval() if ID is alphanumeric

    // 🔹 Step 1: Validate Student ID - Ensure the student exists
    $sqlCheckStudent = "SELECT studentId FROM students WHERE studentId = ?";
    $stmtCheckStudent = $conn->prepare($sqlCheckStudent);
    $stmtCheckStudent->bind_param("s", $student_id_selected); // Bind as string
    $stmtCheckStudent->execute();
    $stmtCheckStudent->store_result();

    if ($stmtCheckStudent->num_rows == 0) {
        $errorMsg = "Invalid student selected. Please choose a valid student.";
        $stmtCheckStudent->close();
    } else {
        $stmtCheckStudent->close();

        // 🔹 Step 2: Prevent Duplicate Attendance Entry
        $sqlCheckAttendance = "SELECT 1 FROM attendance WHERE student_id = ? AND Course_id = ?";
        $stmtCheckAttendance = $conn->prepare($sqlCheckAttendance);
        $stmtCheckAttendance->bind_param("si", $student_id_selected, $course_id);
        $stmtCheckAttendance->execute();
        $stmtCheckAttendance->store_result();

        if ($stmtCheckAttendance->num_rows > 0) {
            $errorMsg = "Attendance already provided for this student in this course.";
            $stmtCheckAttendance->close();
        } else {
            $stmtCheckAttendance->close();

            // 🔹 Step 3: Insert Attendance Record
            $sqlInsert = "INSERT INTO attendance (teacherid, Course_id, stipulated_class, no_of_classes_held, attendance, student_id)
                          VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iiiiis", $teacher_id, $course_id, $stipulated_class, $no_of_classes_held, $attendance_val, $student_id_selected);

            if ($stmtInsert->execute()) {
                $successMsg = "Attendance recorded successfully!";
            } else {
                $errorMsg = "Error recording attendance: " . $conn->error;
            }

            $stmtInsert->close();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Marking Portal</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
        }
    </style>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto py-10">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-3xl mx-auto border border-gray-300">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Mark Attendance</h2>
            
            <!-- Display teacher's department -->
            <p class="mb-4 text-center">
                <span class="font-semibold">Department:</span> <?= htmlspecialchars($teacher_department) ?>
            </p>
            
            <!-- Display success or error message -->
            <?php if($successMsg): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6 text-center">
                    <?= htmlspecialchars($successMsg) ?>
                </div>
            <?php elseif($errorMsg): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mb-6 text-center">
                    <?= htmlspecialchars($errorMsg) ?>
                </div>
            <?php endif; ?>
            
            <!-- Attendance Form -->
            <form id="attendanceForm" method="POST" action="">
                <!-- Course Dropdown -->
                <div class="mb-4">
                    <label for="course_code" class="block text-gray-700 font-medium mb-2">Course</label>
                    <select id="course_code" name="course_code" class="w-full border border-gray-300 rounded-md p-2" required>
                        <option value="" disabled selected>Select a course</option>
                        <?php foreach($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course['Course_id']) ?>">
                                <?= htmlspecialchars($course['Course_Code']) ?> - <?= htmlspecialchars($course['Course_Name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Stipulated Classes Input -->
                <div class="mb-4">
                    <label for="stipulated_class" class="block text-gray-700 font-medium mb-2">Stipulated Classes</label>
                    <input type="number" id="stipulated_class" name="stipulated_class" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                
                <!-- Number of Classes Held Input -->
                <div class="mb-4">
                    <label for="no_of_classes_held" class="block text-gray-700 font-medium mb-2">Number of Classes Held</label>
                    <input type="number" id="no_of_classes_held" name="no_of_classes_held" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                
                <!-- Student Dropdown -->
                <div class="mb-4">
                    <label for="student_id" class="block text-gray-700 font-medium mb-2">Student</label>
                    <select id="student_id" name="student_id" class="w-full border border-gray-300 rounded-md p-2" required>
                        <option value="" disabled selected>Select a student</option>
                        <?php foreach($students_data as $studId => $studName): ?>
                            <option value="<?= htmlspecialchars($studId) ?>">
                                <?= htmlspecialchars($studId) ?> - <?= htmlspecialchars($studName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Display Student Details -->
                <div id="studentDetails" class="mb-4 hidden">
                    <label class="block text-gray-700 font-medium">Student Name</label>
                    <p id="studentName" class="text-gray-800 font-semibold"></p>
                </div>
                
                <!-- Attendance Input -->
                <div id="attendanceInput" class="mb-4 hidden">
                    <label for="attendance" class="block text-gray-700 font-medium mb-2">Attendance</label>
                    <input type="number" id="attendance" name="attendance" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-6 text-center">
                    <button type="submit" name="submit_attendance" id="submitBtn" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 hidden">
                        Submit Attendance
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        // Pass student data from PHP to JavaScript
        const studentsData = <?= json_encode($students_data) ?>;
        
        // DOM elements
        const studentIdSelect = document.getElementById('student_id');
        const studentDetailsDiv = document.getElementById('studentDetails');
        const studentNameField = document.getElementById('studentName');
        const attendanceInputDiv = document.getElementById('attendanceInput');
        const submitButton = document.getElementById('submitBtn');
        
        // Event listener for student ID selection
        studentIdSelect.addEventListener('change', (event) => {
            const selectedStudentId = event.target.value;
            if (studentsData[selectedStudentId]) {
                studentDetailsDiv.classList.remove('hidden');
                studentNameField.textContent = studentsData[selectedStudentId];
                attendanceInputDiv.classList.remove('hidden');
                submitButton.classList.remove('hidden');
            } else {
                studentDetailsDiv.classList.add('hidden');
                attendanceInputDiv.classList.add('hidden');
                submitButton.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
