<?php
// session_start();

include './connection.php'; // Assumes $conn is a MySQLi connection
include './S_navbar.php';

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher data using the teacher ID from the session
$sql = "SELECT * FROM teacher WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if (!$teacher) {
    echo "<p class='text-center text-red-500'>Teacher not found.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .running-text {
            animation: runningText 15s linear infinite;
        }

        @keyframes runningText {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .bg-university-blue {
            background-color: #0033A0;
        }

        .bg-university-blue:hover {
            background-color: #00267a;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Main Content -->
    <main class="container mx-auto mt-10 px-10">
        <!-- Profile Card -->
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Profile Image -->
            <div class="flex justify-center bg-blue-100 py-6">
                <img src="<?= htmlspecialchars($teacher['Picture']) ?>" alt="Profile Picture" class="w-32 h-32 rounded-full border-4 border-white shadow-lg">
            </div>
            <!-- Profile Information -->
            <div class="p-6 text-center">
                <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($teacher['Name']) ?></h1>
                <p class="text-lg text-gray-500 mb-4"><?= htmlspecialchars($teacher['Designation']) ?></p>
                <div class="text-left space-y-4">
                    <p><i class="fas fa-id-card"></i> <span class="font-semibold">NID:</span> <?= htmlspecialchars($teacher['NID']) ?></p>
                    <?php if (isset($teacher['Department'])): ?>
                        <p><i class="fas fa-building"></i> <span class="font-semibold">Department:</span> <?= htmlspecialchars($teacher['Department']) ?></p>
                    <?php endif; ?>
                    <?php if (isset($teacher['Address'])): ?>
                        <p><i class="fas fa-map-marker-alt"></i> <span class="font-semibold">Address:</span> <?= htmlspecialchars($teacher['Address']) ?></p>
                    <?php endif; ?>
                    <p><i class="fas fa-envelope"></i> <span class="font-semibold">Email:</span> <?= htmlspecialchars($teacher['Edu_Mail']) ?></p>
                    <p><i class="fas fa-calendar-alt"></i> <span class="font-semibold">Created At:</span> <?= htmlspecialchars($teacher['created_at']) ?></p>
                    <p><i class="fas fa-tint"></i> <span class="font-semibold">Blood Group:</span> <?= htmlspecialchars($teacher['Blood_Group']) ?></p>
                </div>
            </div>

        </div>

        <!-- Attendance Button -->
        <div class="mt-6 flex justify-center">
            <form action="mark_attendence.php" method="GET">
                <button type="submit" class="px-5 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-700 transition shadow-md">
                    <i class="fas fa-calendar-check"></i> Mark Attendance
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-university-blue text-white py-6 mt-10">
        <div class="container mx-auto text-center">
            <p class="mb-4">&copy; 2024 NstuAcademia. All rights reserved.</p>
            <ul class="flex justify-center space-x-6">
                <li><a href="#" class="hover:text-gray-300">Privacy Policy</a></li>
                <li><a href="#" class="hover:text-gray-300">Terms of Service</a></li>
                <li><a href="#" class="hover:text-gray-300">Contact</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>