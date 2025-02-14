<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hall Boarding Card</title>

    <style>
        body {
            background: linear-gradient(to bottom right, #f3f4f6, #eff6ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 24px;
            width: 384px;
        }

        .title {
            text-align: center;
            margin-bottom: 16px;
        }

        .title h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .logo-section img {
            width: 80px;
            height: 80px;
            margin-right: 12px;
        }

        .logo-section h1 {
            font-size: 1.5rem; /* Increased font size */
            font-weight: bold;
            color: #1f2937;
            text-align: center;
        }

        .divider {
            border-top: 2px solid #e5e7eb;
            margin: 16px 0;
        }

        .student-image {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
        }

        .student-image img {
            width: 112px;
            height: 112px;
            border-radius: 50%;
            border: 4px solid #3b82f6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .student-info {
            text-align: center;
        }

        .student-info h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
        }

        .student-info p {
            font-size: 1rem;
            color:rgb(46, 37, 37);
            margin-top: 4px;
        }

        .expiry-section {
            margin-top: 24px;
            background: linear-gradient(to right, #f87171, #dc2626);
            color: white;
            text-align: center;
            border-radius: 8px;
            padding: 12px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .expiry-section p {
            margin: 0;
        }

        .expiry-section .label {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .expiry-section .date {
            font-size: 1.125rem;
            font-weight: 800;
        }
    </style>

</head>

<body>
    <?php

    // Example student data

    $student = [

        'image' => './Yeasin_vai.jpg',

        'name' => 'Yeasin Arafat',

        'id' => 'ASH2125013M',

        'department' => 'Software Engineering',

        'expiry_date' => '2025-12-31',

    ];

    // University Logo URL

    $universityLogo = './nstu logo.jpg'; // Replace with actual logo URL

    ?>

    <div class="card">
        <!-- Title -->
        <div class="title">
            <h1>Hall Boarding Card</h1>
        </div>

        <!-- University Logo and Name -->
        <div class="logo-section">
            <img src="<?= $universityLogo; ?>" alt="University Logo">
            <h1>Noakhali Science and Technology University</h1>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Student Image -->
        <div class="student-image">
            <img src="<?= $student['image']; ?>" alt="Student Image">
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <h1><?= $student['name']; ?></h1>
            <p>ID: <?= $student['id']; ?></p>
            <p>Department: <?= $student['department']; ?></p>
        </div>

        <!-- Expiry Date Section -->
        <div class="expiry-section">
            <p class="label">Valid Until:</p>
            <p class="date"><?= $student['expiry_date']; ?></p>
        </div>
    </div>
</body>

</html>
