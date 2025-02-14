<?php
session_start();

include 'connection.php';

$error = "";

// Process form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim form inputs.
    $userType    = $_POST['userType'] ?? "";
    $identifier  = trim($_POST['userIdentifier'] ?? "");
    $password    = trim($_POST['password'] ?? "");

    // Basic validation.
    if (empty($userType) || empty($identifier) || empty($password)) {
        $error = "All fields are required.";
    } else {
        if ($userType === 'teacher') {
            // For teachers, we assume the identifier is the Edu Mail.
            $stmt = $conn->prepare("SELECT ID, Name, Edu_Mail, password FROM teacher WHERE Edu_Mail = ? LIMIT 1");
            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['teacher_id']   = $user['ID'];
                    $_SESSION['teacher_name'] = $user['Name'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid Edu Mail or Password.";
                }
            } else {
                $error = "Invalid Edu Mail or Password.";
            }
            $stmt->close();
        } elseif ($userType === 'student') {
            // For students, we assume the identifier is the Student ID.
            $stmt = $conn->prepare("SELECT id, studentId, studentName, password FROM students WHERE studentId = ? LIMIT 1");
            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['student_id']   = $user['studentId'];
                    $_SESSION['student_name'] = $user['studentName'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid Student ID or Password.";
                }
            } else {
                $error = "Invalid Student ID or Password.";
            }
            $stmt->close();
        } elseif ($userType === 'hall_admin') {
            // For hall administrators, we assume the identifier is the Admin ID.
            $stmt = $conn->prepare("SELECT id, admin_id, hall_name, password FROM hall_admins WHERE admin_id = ? LIMIT 1");
            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id']    = $user['admin_id'];
                    $_SESSION['user_type']  = 'hall_admin';
                    $_SESSION['hall_name']  = $user['hall_name'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid Admin ID or Password.";
                }
            } else {
                $error = "Invalid Admin ID or Password.";
            }
            $stmt->close();
        } elseif ($userType === 'department_head') {
            // For Department Head, we assume the identifier is the Department Name.
            $stmt = $conn->prepare("SELECT id, department_name, password FROM dept_head WHERE department_name = ? LIMIT 1");
            $stmt->bind_param("s", $identifier);  // Assuming the department name is passed as identifier
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['department_head_id'] = $user['id'];
                    $_SESSION['user_type'] = 'department_head';
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid Department Name or Password.";
                }
            } else {
                $error = "Invalid Department Name or Password.";
            }
            $stmt->close();
        } else {
            $error = "Invalid user type selected.";
        }
    }
}

// Query to fetch department names for Department Head dropdown
$stmt = $conn->prepare("SELECT Department_id, Department_Name FROM department");
$stmt->execute();
$departments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - NSTU Academia</title>

    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />

    <style>
        /* Apply a gradient background to the body */
        body {
            background-image: url('./nstupic.jpeg');
            background-size: cover;
            background-position: center;
        }

        /* Create a semi-transparent container for the form */
        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        /* Add a subtle animation to the form */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>

    <script>
        // Update the identifier field label and placeholder based on the user type.
        function updateIdentifierLabel() {
            const userType = document.getElementById('userType').value;
            const identifierLabel = document.getElementById('identifierLabel');
            const identifierInput = document.getElementById('userIdentifier');
            if (userType === 'teacher') {
                identifierLabel.textContent = "Edu Mail";
                identifierInput.placeholder = "Enter your Edu Mail";
            } else if (userType === 'student') {
                identifierLabel.textContent = "Student ID";
                identifierInput.placeholder = "Enter your Student ID";
            } else if (userType === 'hall_admin') {
                identifierLabel.textContent = "Admin ID";
                identifierInput.placeholder = "Enter your Admin ID";
            } else if (userType === 'department_head') {
                identifierLabel.textContent = "Department Name";
                identifierInput.placeholder = "Select your Department";
            } else {
                identifierLabel.textContent = "Identifier";
                identifierInput.placeholder = "Enter your Identifier";
            }
        }
    </script>

</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 mx-4 form-container">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">Login Portal</h2>

        <!-- Display any error messages -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-600 p-4 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6" id="loginForm">

            <!-- User Type Selection -->
            <div>
                <label for="userType" class="block text-sm font-medium text-gray-700 mb-2">Select User Type</label>
                <select id="userType" name="userType" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200" onchange="updateIdentifierLabel()">
                    <option value="" disabled <?= empty($_POST['userType']) ? 'selected' : '' ?>>-- Select User Type --</option>
                    <option value="student" <?= (isset($_POST['userType']) && $_POST['userType'] === 'student') ? 'selected' : '' ?>>Student</option>
                    <option value="teacher" <?= (isset($_POST['userType']) && $_POST['userType'] === 'teacher') ? 'selected' : '' ?>>Teacher</option>
                    <option value="hall_admin" <?= (isset($_POST['userType']) && $_POST['userType'] === 'hall_admin') ? 'selected' : '' ?>>Hall Administration</option>
                    <option value="department_head" <?= (isset($_POST['userType']) && $_POST['userType'] === 'department_head') ? 'selected' : '' ?>>Department Head</option>
                </select>
            </div>

            <!-- Dynamic Identifier Input -->
            <div>
                <label for="userIdentifier" id="identifierLabel" class="block text-sm font-medium text-gray-700 mb-2">
                    Identifier
                </label>
                <input type="text" id="userIdentifier" name="userIdentifier" required placeholder="Enter your Identifier"
                    value="<?= isset($_POST['userIdentifier']) ? htmlspecialchars($_POST['userIdentifier']) : '' ?>"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200" />
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-200">
                Log In
            </button>
        </form>
    </div>
</body>

</html>

<?php
// Close the database connection.
$conn->close();
?>
