<?php
session_start();

// Ensure the user has verified their email
if (empty($_SESSION['email'])) {
    header('Location: signup.php');
    exit;
}

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - NSTU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex justify-center items-center bg-gray-100">

    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-2xl text-center">

        <img src="./nstu logo.jpg" alt="NSTU Logo" class="w-16 h-16 mx-auto mb-4">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-4">Complete Your Registration</h1>

        <form method="POST" action="process_signup.php" enctype="multipart/form-data">

            <!-- Two Column Grid Layout -->
            <div class="grid grid-cols-2 gap-6 text-left">

                <!-- Student ID -->
                <div>
                    <label for="student_id" class="block text-gray-700 font-semibold mb-1">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 transition duration-300 shadow-sm" placeholder="NSTU123456" required>
                </div>

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-1">Full Name:</label>
                    <input type="text" id="name" name="name" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 transition duration-300 shadow-sm" placeholder="Your Full Name" required>
                </div>

                <!-- Upload Image -->
                <div class="col-span-2">
                    <label for="profile_image" class="block text-gray-700 font-semibold mb-1">Profile Picture:</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" class="w-full border border-gray-300 rounded-lg p-2">
                </div>

                <!-- Session Year -->
                <div>
                    <label for="session_year" class="block text-gray-700 font-semibold mb-1">Session Year:</label>
                    <input type="text" id="session_year" name="session_year" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 transition duration-300 shadow-sm" placeholder="e.g., 2023-2024" required>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-gray-700 font-semibold mb-1">Department:</label>
                    <select id="department" name="department" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 transition duration-300 shadow-sm">
                        <option value="IIT">Institute Of Information Technology</option>
                        <option value="CSE">Computer Science and Engineering</option>
                        <option value="EEE">Electrical & Electronic Engineering</option>
                        <option value="ME">Mechanical Engineering</option>
                        <option value="Civil">Civil Engineering</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Physics">Physics</option>
                        <option value="Chemistry">Chemistry</option>
                        <option value="BMB">Biochemistry and Molecular Biology</option>
                        <option value="Pharmacy">Pharmacy</option>
                        <option value="BBA">Business Administration</option>
                        <option value="Economics">Economics</option>
                        <option value="AIS">Accounting and Information Systems</option>
                        <option value="English">English Language and Literature</option>
                        <option value="ESDM">Environmental Science and Disaster Management</option>
                        <option value="Statistics">Applied Statistics</option>
                        <option value="FMS">Fisheries and Marine Science</option>
                        <option value="Microbiology">Microbiology</option>
                        <option value="GEB">Genetic Engineering and Biotechnology</option>
                        <option value="ICT">Information and Communication Technology</option>
                        <option value="THM">Tourism and Hospitality Management</option>
                        <option value="Law">Law and Justice</option>
                        <option value="Other">Other</option>
                    </select>

                </div>

                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-gray-700 font-semibold mb-1">Password:</label>
                    <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-lg p-3 pr-10 focus:ring-2 focus:ring-blue-400 transition duration-300 shadow-sm" placeholder="Create a password" required>
                    <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-600" onclick="togglePassword('password')">👁️</span>
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <label for="confirm_password" class="block text-gray-700 font-semibold mb-1">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full border border-gray-300 rounded-lg p-3 pr-10 focus:ring-2 focus:ring-blue-400 transition duration-300 shadow-sm" placeholder="Confirm your password" required>
                    <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-600" onclick="togglePassword('confirm_password')">👁️</span>
                </div>

            </div>

            <!-- Hidden Email Field -->
            <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>">

            <!-- Submit Button -->
            <button type="submit" class="mt-6 w-full bg-blue-500 text-white font-semibold px-4 py-3 rounded-lg hover:bg-blue-600 transition duration-300 shadow-md">
                Complete Registration
            </button>
        </form>

        <p class="mt-4 text-gray-600 text-sm">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a></p>
    </div>

    <script>
        function togglePassword(id) {
            let input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>

</body>

</html>