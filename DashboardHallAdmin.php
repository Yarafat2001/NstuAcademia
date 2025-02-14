<?php
include 'session_check(hall_admin).php'; // Ensure the user is logged in and session is active
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Running text animation */
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

        /* Global styles */
        .bg-university-blue {
            background-color: #0033A0; /* Primary blue */
        }

        .text-university-yellow {
            color: #FFD700; /* Yellow for better visibility */
        }

        /* Navigation bar */
        .nav-link {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease-in-out;
            white-space: nowrap;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
        }

        /* Button styles */
        .button-container a {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: transform 0.3s ease-in-out;
            font-size: 1rem;
        }

        .button-container a:hover {
            transform: scale(1.05);
        }

        /* Enhanced Welcome Section */
        .welcome-section img {
            max-height: 400px;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-university-blue shadow w-full">
        <div class="container mx-auto py-4 px-6 flex justify-between items-center">
            <!-- Logo and title -->
            <div class="flex items-center space-x-4">
                <img src="./nstu logo.jpg" alt="University Logo" class="w-16 h-auto">
                <div>
                    <h1 class="text-2xl font-bold text-white">NstuAcademia</h1>
                    <p class="text-sm text-university-yellow running-text">
                        A Hassle-Free System for University Students
                    </p>
                </div>
            </div>
            <!-- Contact information -->
            <div class="text-white text-right">
                <p class="font-semibold">📞 Phone: 02334496522</p>
                <p class="font-semibold">📠 Fax: 02334496523</p>
                <p class="font-semibold">✉️ Email: registrar@office.nstu.edu.bd</p>
            </div>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="DashboardHallAdmin.php" class="text-2xl font-bold text-university-yellow">Hall Administration</a>
            <ul class="flex space-x-6">
                <li><a href="HallSeatManagement.php" class="hover:text-university-yellow nav-link">Hall Seat Management</a></li>
                <li><a href="BlankSeatStatus.php" class="hover:text-university-yellow nav-link">View Blank Seat Status</a></li>
                <li><a href="ManageApplication.php" class="hover:text-university-yellow nav-link">Manage Applications</a></li>
                <li><a href="IssueBoardingCard.php" class="hover:text-university-yellow nav-link">Issue Boarding Card</a></li>
                <li><a href="About.php" class="hover:text-university-yellow nav-link">About</a></li>
                <li><a href="logout.php" class="hover:text-university-yellow nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section class="relative w-full h-screen">
    <!-- Image -->
    <img src="./drone_view_of_nstu.jpg" 
         alt="University Campus" 
         class="absolute inset-0 w-full h-full object-cover">

    <!-- Text Overlay -->
    <div class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-50 text-white text-center px-4">
        <h2 class="text-4xl md:text-6xl font-extrabold mb-4">Welcome to NstuAcademia</h2>
        <p class="text-lg md:text-2xl max-w-2xl">
            Simplify your university life with our comprehensive system for students, teachers, and administrators.
        </p>
    </div>
</section>
</body>
</html>
<?php include 'footer.php'; ?>