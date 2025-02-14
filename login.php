<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Common Login - NSTU Academia</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-blue-500 to-blue-700 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 mx-4 md:mx-auto">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login Portal</h2>
        <form id="commonLoginForm" class="space-y-4">
            <div>
                <label for="userType" class="block text-sm font-medium text-gray-700 mb-1">Select User Type</label>
                <select id="userType" name="userType" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="" disabled selected>-- Select User Type --</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="hall_admins">Hall Administration</option>
                </select>
            </div>
            <button type="button" onclick="redirectToLogin()"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300 focus:outline-none">Go
                to Login</button>
        </form>
    </div>

    <script>
        function redirectToLogin() {
            const userType = document.getElementById('userType').value;
            if (!userType) {
                alert("Please select a user type to continue.");
                return;
            }

            // Redirect based on the selected user type
            if (userType === 'student') {
                window.location.href = 'login(student).php';
            } else if (userType === 'teacher') {
                window.location.href = 'login(teacher).php';
            } else if (userType === 'hall_admins') {
                window.location.href = 'login(hall_admins).php';
            }
        }
    </script>
</body>

</html>
