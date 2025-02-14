<?php
session_start();

// Determine the user role based on available session variables.
$role = '';

if (isset($_SESSION['student_id'])) {
    $role = 'student';
} elseif (isset($_SESSION['teacher_id'])) {
    $role = 'teacher';
} elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'hall_admin') {
    $role = 'hall_admin';
} elseif (isset($_SESSION['department_head_id'])) {
    $role = 'department_head';
}

$isLoggedIn = !empty($role);
$name = $_SESSION['name'] ?? 'User'; // Use a proper name if available
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* Global and utility styles */
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #000;
        }

        /* Navbar container */
        .bg-university-blue {
            background-color: #003366; /* Dark blue color */
        }

        .text-university-yellow {
            color: #FFD700; /* Yellow text */
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            z-index: 10;
            position: relative;
            font-size: 14px; /* Smaller font size */
        }

        /* Navbar links */
        ul {
            display: flex;
            gap: 15px;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
            color: #fff;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: flex;
            align-items: center;
        }

        a:hover,
        a:focus {
            background-color: #00fffc;
            color: #000;
        }

        /* Active link styles */
        .active {
            font-weight: bold;
            color: #00fffc;
            background-color: #003366;
        }

        .nav-link {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #00fffc;
            color: #000;
        }

        .selection-area {
            position: absolute;
            background-color: #00fffc;
            height: 5px;
            width: 0;
            border-radius: 2px;
            transition: all 0.3s ease-in-out;
            z-index: -1;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                text-align: center;
            }

            ul {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }

            a {
                padding: 10px 0;
                font-size: 16px;
                width: 100%;
            }
        }

        /* Header section styles */
        .running-text {
            animation: runningText 15s linear infinite;
            white-space: nowrap;
        }

        @keyframes runningText {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Header Section -->
    <header class="bg-white shadow-lg w-full backdrop-blur-md bg-opacity-90">
        <div class="container mx-auto py-8 px-6 flex justify-between items-center">

            <!-- Logo and Title -->
            <div class="flex items-center space-x-4 transform hover:scale-105 transition-transform duration-300">
                <img src="nstu logo.jpg" alt="University Logo" class="w-16 h-auto rounded-lg shadow-md border-2 border-gray-300 border-opacity-20">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        NstuAcademia
                    </h1>
                    <p class="text-sm text-gray-700 mt-1 running-text">
                        A Hassle-Free System for University Students
                    </p>
                </div>
            </div>

            <!-- Contact Info (visible on larger screens) -->
            <div class="text-gray-700 text-right hidden md:block space-y-2">
                <p class="font-semibold hover:text-gray-900 transition-colors duration-300 flex items-center justify-end space-x-2">
                    <span class="inline-block animate-bounce">📞</span>
                    <span>Phone: 02334496522</span>
                </p>
                <p class="font-semibold hover:text-gray-900 transition-colors duration-300 flex items-center justify-end space-x-2">
                    <span class="inline-block animate-bounce">📠</span>
                    <span>Fax: 02334496523</span>
                </p>
                <p class="font-semibold hover:text-gray-900 transition-colors duration-300 flex items-center justify-end space-x-2">
                    <span class="inline-block animate-bounce">✉️</span>
                    <span>Email: registrar@office.nstu.edu.bd</span>
                </p>
            </div>

        </div>
    </header>

    <!-- Navbar Section -->
    <nav class="bg-university-blue w-full" id="nav">
        <div class="container mx-auto flex justify-between items-center py-3 px-6">
            <ul class="flex items-center space-x-4 text-white">
                <?php if ($isLoggedIn): ?>
                    <?php if ($role === 'student'): ?>
                        <li><a href="dashboard.php" class="nav-link" data-id="home"><i class="fas fa-home mr-2"></i>Home</a></li>
                        <li><a href="Profile.php" class="nav-link" data-id="profile"><i class="fas fa-user mr-2"></i>Profile</a></li>
                        <li><a href="course_registration.php" class="nav-link" data-id="course-registration"><i class="fas fa-book mr-2"></i>Course Registrations</a></li>
                        <li><a href="fetch_attendence.php" class="nav-link" data-id="attendance"><i class="fas fa-check-circle mr-2"></i>See Attendance</a></li>
                        <li><a href="HallSeatNotice.php" class="nav-link" data-id="seat-notice"><i class="fas fa-chair mr-2"></i>Hall Seat Notice</a></li>
                        <li><a href="make_payment.php" class="nav-link" data-id="payment"><i class="fas fa-credit-card mr-2"></i>Registration Payment</a></li>
                        <li><a href="Events.php" class="nav-link" data-id="events"><i class="fas fa-calendar-alt mr-2"></i>Events</a></li>
                    <?php elseif ($role === 'teacher'): ?>
                        <li><a href="dashboard.php" class="nav-link" data-id="dashboard"><i class="fas fa-chalkboard-teacher mr-2"></i>Dashboard</a></li>
                        <li><a href="teacher_profile.php" class="nav-link" data-id="teacher-profile"><i class="fas fa-user-graduate mr-2"></i>View Profile</a></li>
                        <li><a href="mark_attendence.php" class="nav-link" data-id="mark-attendance"><i class="fas fa-check-circle mr-2"></i>Attendance</a></li>
                        <li><a href="manage_registration.php" class="nav-link" data-id="manage-registration"><i class="fas fa-user-plus mr-2"></i>Verify Registration</a></li>
                        <li><a href="Contact.php" class="nav-link" data-id="contact"><i class="fas fa-envelope mr-2"></i>Contact</a></li>
                    <?php elseif ($role === 'hall_admin'): ?>
                        <li><a href="dashboard.php" class="nav-link" data-id="home"><i class="fas fa-home mr-2"></i>Home</a></li>
                        <li><a href="HallSeatManagement.php" class="nav-link" data-id="hall-seat-management"><i class="fas fa-cogs mr-2"></i>Hall Seat Management</a></li>
                        <li><a href="BlankSeatStatus.php" class="nav-link" data-id="blank-seat-status"><i class="fas fa-chair mr-2"></i>View Blank Seat Status</a></li>
                        <li><a href="ManageApplication.php" class="nav-link" data-id="manage-applications"><i class="fas fa-tasks mr-2"></i>Manage Applications</a></li>
                        <li><a href="IssueBoardingCard.php" class="nav-link" data-id="issue-boarding-card"><i class="fas fa-id-card mr-2"></i>Issue Boarding Card</a></li>
                        <li><a href="About.php" class="nav-link" data-id="about"><i class="fas fa-info-circle mr-2"></i>About</a></li>
                    <?php elseif ($role === 'department_head'): ?>
                        <li><a href="director_dashboard.php" class="nav-link" data-id="dashboard"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                        <li><a href="manage_registration.php" class="nav-link" data-id="verify-registrations"><i class="fas fa-check-circle mr-2"></i>Verify Registrations</a></li>
                        <li><a href="mark_attendance.php" class="nav-link" data-id="mark-attendance"><i class="fas fa-check-circle mr-2"></i>Attendance</a></li>
                        <li><a href="departments.php" class="nav-link" data-id="departments"><i class="fas fa-building mr-2"></i>Departments</a></li>
                    <?php endif; ?>
                    <li>
                        <a href="logout.php" class="nav-link bg-red-600 px-4 py-2 rounded hover:bg-red-700"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                    </li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-link" data-id="login"><i class="fas fa-sign-in-alt mr-2"></i>Login</a></li>
                    <li><a href="register.php" class="nav-link" data-id="register"><i class="fas fa-user-plus mr-2"></i>Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <script>
        // Get all nav links
        const nav = document.querySelector("#nav");
        const navLinks = document.querySelectorAll(".nav-link");

        // Check if there's an active link stored in localStorage
        const activeLinkId = localStorage.getItem("activeLink");

        // If there is an active link saved in localStorage, apply it
        if (activeLinkId) {
            const activeLink = document.querySelector(`[data-id="${activeLinkId}"]`);
            if (activeLink) {
                activeLink.classList.add("active");
            }
        }

        // Update the active link and store it in localStorage
        nav.addEventListener("click", (e) => {
            const link = e.target.closest(".nav-link");
            if (!link) return;

            // Remove 'active' class from all links
            navLinks.forEach((item) => item.classList.remove("active"));
            link.classList.add("active");

            // Store the active link's ID in localStorage
            localStorage.setItem("activeLink", link.getAttribute("data-id"));
        });
    </script>

</body>

</html>
