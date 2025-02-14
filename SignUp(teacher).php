<?php
include './connection.php'; // Assumes $conn is a MySQLi connection

// Fetch available departments from the department table.
$deptSql = "SELECT Department_id, Department_Name FROM department";
$deptStmt = $conn->prepare($deptSql);
$deptStmt->execute();
$deptResult = $deptStmt->get_result();
$departments = $deptResult->fetch_all(MYSQLI_ASSOC);
$deptStmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim input values
    $name            = trim($_POST['name']);
    $department      = trim($_POST['department']); // Teacher department from dropdown
    $eduMail         = trim($_POST['eduMail']);
    $nid             = trim($_POST['nid']);
    $bloodGroup      = trim($_POST['bloodGroup']);
    $designation     = trim($_POST['designation']);
    $isProvost       = trim($_POST['isProvost']);
    $hallName        = ($isProvost === "Yes") ? trim($_POST['hallName']) : null;
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Server-side password confirmation
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validate and process image upload
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == 0) {
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File is not an image.'); window.history.back();</script>";
            exit;
        }
        if ($_FILES["picture"]["size"] > 5000000) { // Limit to 5MB
            echo "<script>alert('Sorry, your file is too large.'); window.history.back();</script>";
            exit;
        }
        $imageFileName = basename($_FILES["picture"]["name"]);
        $imageFileType = strtolower(pathinfo($imageFileName, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('No image was uploaded or there was an error uploading your image.'); window.history.back();</script>";
        exit;
    }

    // Insert teacher record first (set Picture as an empty string initially)
    $sql = "INSERT INTO teacher (Name, Department, Edu_Mail, NID, Blood_Group, Designation, Is_Provost, Hall_Name, Picture, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, '', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $name, $department, $eduMail, $nid, $bloodGroup, $designation, $isProvost, $hallName, $hashedPassword);
    if (!$stmt->execute()) {
        echo "<script>alert('Error inserting teacher record.'); window.history.back();</script>";
        exit;
    }
    // Get the last inserted teacher id
    $teacher_id = $conn->insert_id;
    $stmt->close();

    // Create target directory: ./uploads/teacher/$teacher_id/
    $targetDir = "uploads/teacher/" . $teacher_id . "/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFilePath = $targetDir . $imageFileName;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFilePath)) {
        // Update teacher record with the correct picture path
        $updateSql = "UPDATE teacher SET Picture = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $targetFilePath, $teacher_id);
        if (!$updateStmt->execute()) {
            echo "<script>alert('Error updating picture path.'); window.history.back();</script>";
            exit;
        }
        $updateStmt->close();
        echo "<script>alert('Teacher registered successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error uploading file.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher SignUp Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .form-container {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-3xl form-container">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">Teacher SignUp Form</h2>
        <form class="space-y-6" method="POST" action="" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your name" required>
                </div>
                <div>
                    <label for="department" class="block text-lg font-medium text-gray-700 mb-2">Department</label>
                    <select id="department" name="department" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Department</option>
                        <?php foreach($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['Department_Name']) ?>">
                                <?= htmlspecialchars($dept['Department_Name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label for="eduMail" class="block text-lg font-medium text-gray-700 mb-2">Edu Mail</label>
                <input type="email" id="eduMail" name="eduMail" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your edu mail" required>
            </div>
            <div>
                <label for="nid" class="block text-lg font-medium text-gray-700 mb-2">NID</label>
                <input type="text" id="nid" name="nid" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your NID" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bloodGroup" class="block text-lg font-medium text-gray-700 mb-2">Blood Group</label>
                    <select id="bloodGroup" name="bloodGroup" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div>
                    <label for="designation" class="block text-lg font-medium text-gray-700 mb-2">Designation</label>
                    <select id="designation" name="designation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Designation</option>
                        <option value="Lecturer">Lecturer</option>
                        <option value="Assistant Professor">Assistant Professor</option>
                        <option value="Director">Director</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="isProvost" class="block text-lg font-medium text-gray-700 mb-2">Are you a provost of any hall?</label>
                <select id="isProvost" name="isProvost" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" onchange="toggleHallField()" required>
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div id="hallField" style="display: none;">
                <label for="hallName" class="block text-lg font-medium text-gray-700 mb-2">Hall Name</label>
                <input type="text" id="hallName" name="hallName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter the hall name">
            </div>
            <div>
                <label for="picture" class="block text-lg font-medium text-gray-700 mb-2">Upload Picture</label>
                <input type="file" id="picture" name="picture" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" accept="image/*" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-lg font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="confirm_password" class="block text-lg font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="show_password" class="mr-2">
                <label for="show_password" class="text-base">Show Password</label>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition duration-200">SignUp</button>
        </form>
    </div>
    <script>
        function toggleHallField() {
            const isProvost = document.getElementById("isProvost").value;
            const hallField = document.getElementById("hallField");
            hallField.style.display = isProvost === "Yes" ? "block" : "none";
        }
        document.getElementById("show_password").addEventListener("change", function() {
            const passwordField = document.getElementById("password");
            const confirmPasswordField = document.getElementById("confirm_password");
            const type = this.checked ? "text" : "password";
            passwordField.type = type;
            confirmPasswordField.type = type;
        });
    </script>
</body>
</html>
