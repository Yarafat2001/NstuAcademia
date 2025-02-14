<?php
include 'connection.php'; // Include the database connection

// Initialize variables
$message = "";
$totalAmount = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $academicFees = $_POST['academicFees'] ?? 0;
    $hallFees = $_POST['hallFees'] ?? 0;
    $examFees = $_POST['examFees'] ?? 0;
    $studentId = $_POST['studentId'] ?? null;

    $totalAmount = (float)$academicFees + (float)$hallFees + (float)$examFees;

    if ($totalAmount > 0 && $studentId) {
        try {
            // Fetch profiles_id based on studentId
            $stmt = $conn->prepare("SELECT id FROM profiles WHERE studentId = ?");
            if (!$stmt) {
                throw new Exception("SQL Error: " . $conn->error);
            }

            $stmt->bind_param("s", $studentId);
            $stmt->execute();
            $stmt->bind_result($profilesId);
            $stmt->fetch();
            $stmt->close();

            if ($profilesId) {
                // Check if registrations exist for the profiles_id
                $stmt = $conn->prepare("SELECT id FROM registration WHERE profiles_id = ?");
                if (!$stmt) {
                    throw new Exception("SQL Error: " . $conn->error);
                }

                $stmt->bind_param("i", $profilesId);
                $stmt->execute();
                $result = $stmt->get_result();
                $registrationIds = [];

                while ($row = $result->fetch_assoc()) {
                    $registrationIds[] = $row['id'];
                }
                $stmt->close();

                if (!empty($registrationIds)) {
                    foreach ($registrationIds as $registrationId) {
                        $stmt = $conn->prepare("
                            INSERT INTO payment (registration_id, amount, payment_status) 
                            VALUES (?, ?, 'pending') 
                            ON DUPLICATE KEY UPDATE 
                                amount = VALUES(amount), 
                                payment_status = VALUES(payment_status)
                        ");
                        if (!$stmt) {
                            throw new Exception("SQL Error: " . $conn->error);
                        }

                        $stmt->bind_param("id", $registrationId, $totalAmount);
                        $stmt->execute();
                        $stmt->close();
                    }

                    // Redirect to dummy payment gateway
                    header("Location: dummy_payment_gateway.php?studentId=$studentId&amount=$totalAmount");
                    exit();
                } else {
                    $message = "No registrations found for the provided Student ID.";
                }
            } else {
                $message = "Invalid Student ID. Profile record not found.";
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please provide valid fee details and Student ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function calculateTotal() {
            const academicFees = parseFloat(document.getElementById('academicFees').value) || 0;
            const hallFees = parseFloat(document.getElementById('hallFees').value) || 0;
            const examFees = parseFloat(document.getElementById('examFees').value) || 0;
            const totalAmount = academicFees + hallFees + examFees;
            document.getElementById('totalAmountDisplay').textContent = `${totalAmount.toFixed(2)} BDT`;
        }
    </script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="navbar">
    <ul class="text-white font-bold">
        <li><a href="Home.html" class="hover:text-gray-300">Home</a></li>
        <li><a href="AboutUs.html" class="hover:text-gray-300">About Us</a></li>
        <li><a href="CourseRegistrationNotice.html" class="hover:text-gray-300">Course Registration Notice</a></li>
        <li><a href="HallSeatNotice.html" class="hover:text-gray-300">Hall Seat Notice</a></li>
        <li><a href="department.html" class="hover:text-gray-300">Departments</a></li>
        <li><a href="Event.html" class="hover:text-gray-300">Events</a></li>
        <li><a href="Contactpage.html" class="hover:text-gray-300">Contact</a></li>
    </ul>
</nav>

<!-- Payment Form -->
<div class="form-container max-w-lg mx-auto mt-10 bg-white shadow-md rounded p-6">
    <h1 class="text-2xl font-bold mb-4 text-center">Course Registration Payment</h1>

    <?php if ($message): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-4">
        <div>
            <label for="studentId" class="block font-semibold">Student ID:</label>
            <input type="text" id="studentId" name="studentId" placeholder="Enter Student ID" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label for="academicFees" class="block font-semibold">Academic Fees:</label>
            <input type="number" id="academicFees" name="academicFees" placeholder="Enter academic fees" class="w-full p-2 border rounded" oninput="calculateTotal()">
        </div>
        <div>
            <label for="hallFees" class="block font-semibold">Hall Fees:</label>
            <input type="number" id="hallFees" name="hallFees" placeholder="Enter hall fees" class="w-full p-2 border rounded" oninput="calculateTotal()">
        </div>
        <div>
            <label for="examFees" class="block font-semibold">Exam Fees:</label>
            <input type="number" id="examFees" name="examFees" placeholder="Enter exam fees" class="w-full p-2 border rounded" oninput="calculateTotal()">
        </div>
        <div class="text-lg font-semibold">
            <p>Total Amount:</p>
            <span id="totalAmountDisplay" class="text-blue-600">0.00 BDT</span>
        </div>
        <button type="submit" class="w-full bg-green-500 text-white p-2 mt-4 rounded hover:bg-green-700">
            Pay to Proceed
        </button>
    </form>
</div>
</body>
</html>
