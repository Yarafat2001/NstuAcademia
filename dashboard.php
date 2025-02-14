
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

   <?php
   include'./S_navbar.php';
   ?>

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