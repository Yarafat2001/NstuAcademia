<?php
include 'connection.php';

$studentId = $_POST['studentId'] ?? null;
$amount = $_POST['amount'] ?? 0;
$status = $_POST['status'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        .button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <?php
        if ($status === 'success' && $studentId && $amount) {
            $stmt = $conn->prepare("UPDATE payment SET payment_status = 'completed' WHERE registration_id IN (
                SELECT id FROM registration WHERE profiles_id = (
                    SELECT id FROM profiles WHERE studentId = ?
                )
            )");
            $stmt->bind_param("s", $studentId);
            $stmt->execute();
            $stmt->close();
        ?>
            <h1 class="text-2xl font-bold success">Payment Successful</h1>
            <p class="text-gray-700">Student ID: <span class="font-medium"><?= htmlspecialchars($studentId) ?></span></p>
            <p class="text-gray-700">Amount Paid: <span class="font-medium"><?= htmlspecialchars($amount) ?> BDT</span></p>
            <a href="admit.php?studentId=<?= urlencode($studentId) ?>" class="button">Download Admit Card</a>
        <?php
        } else {
        ?>
            <h1 class="text-2xl font-bold error">Invalid Payment Request</h1>
            <p class="text-gray-700">Please check your payment details and try again.</p>
        <?php
        }
        ?>
    </div>
</body>
</html>
