<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f0f0f5;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold text-gray-700 text-center mb-6">Sign Up to NstuAcademia</h2>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <!-- Sign-Up Form -->
        <form id="signupForm" method="POST" class="space-y-4" onsubmit="redirectToLogin(event)">
            <input type="hidden" name="signup" value="1">
            <label for="role" class="block text-gray-600 font-medium mb-2">Select Role</label>
            <select id="signupRole" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300" required>
                <option value="" disabled selected>Select Role</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
                <option value="hall_admin">Hall Administration</option>
            </select>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Proceed to Sign Up</button>
        </form>
    </div>

    <script>
        function redirectToLogin(event) {
            event.preventDefault(); // Prevent the form from submitting

            const userType = document.getElementById('signupRole').value;
            if (!userType) {
                alert("Please select a role to continue.");
                return;
            }

            // Redirect based on the selected user type
            if (userType === 'student') {
                window.location.href = 'signup1.php';
            } else if (userType === 'teacher') {
                window.location.href = 'SignUp(teacher).php';
            } else if (userType === 'hall_admin') {
                window.location.href = 'SignUp(hall_admin).php';
            }
        }
    </script>
</body>
</html>
