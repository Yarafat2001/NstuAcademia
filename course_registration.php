<?php

// session_start();
include 'connection.php'; 
include 'S_navbar.php';

// Enable detailed error reporting for MySQLi (optional)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$error = "";
$message = "";
$courses = [];

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login1.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// 1. Retrieve the student’s department, name, and session year from the "students" table.
$stmt = $conn->prepare("SELECT department, studentName, session_year FROM students WHERE studentId = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $studentRow   = $result->fetch_assoc();
    $student_dept = $studentRow['department'];
    $student_name = $studentRow['studentName'];
    $session_year = $studentRow['session_year'];
} else {
    $error = "Student record not found.";
}
$stmt->close();

// 2. Using the student's department, fetch the Department_Id from the "department" table.
$dept_id = "";
if (!empty($student_dept)) {
    $stmt = $conn->prepare("SELECT Department_Id FROM department WHERE Department_Name = ?");
    $stmt->bind_param("s", $student_dept);
    $stmt->execute();
    $dept_result = $stmt->get_result();
    if ($dept_result->num_rows > 0) {
        $deptRow = $dept_result->fetch_assoc();
        $dept_id = $deptRow['Department_Id'];
    } else {
        $error = "Department not found for student.";
    }
    $stmt->close();
}

// Variables for year and semester (will come from the form)
$year = "";
$semester = "";

// 3. When the "Fetch Courses" button is clicked, use the department id, year, and semester to fetch courses.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch_courses'])) {
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    if (empty($year) || empty($semester)) {
        $error = "Please fill out all fields.";
    } else {
        // Format semester as needed – e.g., '1' becomes '1st' and '2' becomes '2nd'
        $formatted_semester = ($semester == 1) ? '1st' : '2nd';

        // Fetch courses from the "course" table using the determined department id, year, and semester.
        $stmt = $conn->prepare("SELECT Course_id, Course_Code, Course_Name, Credit_Hours FROM course WHERE Department_Id = ? AND Year = ? AND Semester = ?");
        $stmt->bind_param("iis", $dept_id, $year, $formatted_semester);
        $stmt->execute();
        $course_result = $stmt->get_result();
        if ($course_result->num_rows > 0) {
            $courses = $course_result->fetch_all(MYSQLI_ASSOC);
            $message = "Courses fetched successfully.";
        } else {
            $error = "No courses found for the selected criteria.";
        }
        $stmt->close();
    }
}

// 4. Handle semester registration – insert a record into the "registration" table and then insert a payment record.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_semester'])) {
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    
    if (empty($year) || empty($semester)) {
        $error = "Year and Semester must be provided.";
    } else {
        // Check if a registration record already exists for this student, year, and semester.
        $stmt = $conn->prepare("SELECT 1 FROM registration WHERE student_id = ? AND Year = ? AND Semester = ?");
        $stmt->bind_param("sii", $student_id, $year, $semester);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "You have already registered for this semester.";
        } else {
            $stmt->close();
            // Insert the registration record.
            $stmt_insert = $conn->prepare("INSERT INTO registration (student_id, Year, Semester, status) VALUES (?, ?, ?, 'active')");
            $stmt_insert->bind_param("sii", $student_id, $year, $semester);
            $stmt_insert->execute();
            
            // Get the newly inserted registration id.
            $registration_id = $conn->insert_id;
            
            $stmt_insert->close();
            
            // Insert a corresponding payment record into registration_payment.
            // For demonstration, we use a default payment method and amount.
            $payment_method = "online";   // This value can be dynamic or come from a payment form.
            $amount = 500.00;             // Set the registration fee amount as needed.
            
            $stmt_payment = $conn->prepare("INSERT INTO registration_payment (r_id, payment_method, amount, pay) VALUES (?, ?, ?, 'incomplete')");
            $stmt_payment->bind_param("isd", $registration_id, $payment_method, $amount);
            $stmt_payment->execute();
            $stmt_payment->close();
            
            $message = "Semester registered successfully! Payment record created.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS for additional styling -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #6b73ff 0%, #000dff 100%);
        }
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: #4f46e5;
            color: white;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #4338ca;
        }
        .btn-secondary {
            background: #10b981;
            color: white;
            transition: background 0.3s ease;
        }
        .btn-secondary:hover {
            background: #059669;
        }
    </style>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto mt-10 px-4">
        <div class="gradient-bg text-white p-6 rounded-t-lg">
            <h1 class="text-3xl font-bold">Course Registration</h1>
            <p class="mt-2">Welcome, <?= htmlspecialchars($student_name) ?>!</p>
        </div>
        <div class="card p-6 rounded-b-lg">
            <!-- Display error or success messages -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mb-6"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($message)): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- Display student information -->
            <div class="mb-6">
                <p><strong>Student ID:</strong> <?= htmlspecialchars($student_id) ?></p>
                <p><strong>Student Name:</strong> <?= htmlspecialchars($student_name) ?></p>
                <p><strong>Department:</strong> <?= htmlspecialchars($student_dept) ?></p>
            </div>

            <!-- Registration Form: Only Year and Semester are required to fetch courses -->
            <form method="POST" action="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block mb-2 font-medium">Year:</label>
                        <select name="year" class="w-full border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">--Select Year--</option>
                            <option value="1">Year 1</option>
                            <option value="2">Year 2</option>
                            <option value="3">Year 3</option>
                            <option value="4">Year 4</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Semester:</label>
                        <select name="semester" class="w-full border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">--Select Semester--</option>
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="fetch_courses" class="btn-primary px-6 py-2 rounded-lg">
                    Fetch Courses
                </button>
            </form>

            <!-- If courses are fetched, display them in a table -->
            <?php if (!empty($courses)): ?>
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">Available Courses</h2>
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 px-4 py-2">Course Code</th>
                                <th class="border border-gray-300 px-4 py-2">Course Name</th>
                                <th class="border border-gray-300 px-4 py-2">Credit Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($course['Course_Code']) ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($course['Course_Name']) ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($course['Credit_Hours']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Registration form to register the semester (no course selection required) -->
                <form method="POST" action="" class="mt-6">
                    <!-- Pass along the selected year and semester -->
                    <input type="hidden" name="year" value="<?= htmlspecialchars($year) ?>">
                    <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">
                    <button type="submit" name="register_semester" class="btn-secondary px-6 py-2 rounded-lg">
                        Register Semester
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
