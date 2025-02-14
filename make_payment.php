<?php
// session_start();
include 'connection.php';
include 'S_navbar.php';

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login1.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Retrieve the registration payment record for the logged-in student.
// We join registration_payment with registration to ensure the student owns the record
// and only select records where the payment status is 'incomplete'.
$stmt = $conn->prepare("SELECT rp.*, r.id AS registration_id, r.Year, r.Semester 
                        FROM registration_payment rp 
                        JOIN registration r ON rp.r_id = r.id 
                        WHERE r.student_id = ? AND rp.pay = 'incomplete'
                        LIMIT 1");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mx-auto mt-10 text-center'>
        <h2 class='text-3xl font-bold text-gray-800'>No Pending Payment Due</h2>
        <p class='text-lg text-gray-600'>You have no incomplete payments.</p>

        <!-- Button to download Admit Card with Icon -->
        <a href='admit_pdf.php' class='mt-6 inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300 transform hover:scale-105'>
            <!-- Font Awesome Download Icon -->
            <i class='fas fa-download mr-2'></i> 
            <span>Download Admit Card</span>
        </a>
      </div>";

    exit;
}

$payment = $result->fetch_assoc();
$stmt->close();

$error = "";
$message = "";

// Process the payment form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['make_payment'])) {
    $selected_method = trim($_POST['payment_method']);
    $transaction_id  = trim($_POST['transaction_id']);

    if (empty($selected_method) || empty($transaction_id)) {
        $error = "Please select a payment method and enter a transaction ID.";
    } else {
        // In a real-world scenario, you would integrate with the mobile banking API
        // to verify the transaction. For demonstration, we simulate a successful payment update.
        $stmt_update = $conn->prepare("UPDATE registration_payment SET payment_method = ?, pay = 'complete' WHERE payment_id = ?");
        $stmt_update->bind_param("si", $selected_method, $payment['payment_id']);
        if ($stmt_update->execute()) {
            $message = "Payment successful using " . htmlspecialchars($selected_method) . ". Transaction ID: " . htmlspecialchars($transaction_id);
        } else {
            $error = "Error updating payment record: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Payment</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for an attractive payment interface */
        .payment-card {
            max-width: 500px;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .payment-header {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .payment-option img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .payment-option:hover img {
            transform: scale(1.1);
        }
        .payment-option {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-option:hover {
            border-color: #3b82f6;
            background-color: #f0f4ff;
        }
        .payment-option input[type="radio"] {
            display: none;
        }
        .payment-option input[type="radio"]:checked + img {
            border: 2px solid #3b82f6;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <div class="payment-card bg-white">
            <div class="payment-header">
                <h1 class="text-3xl font-bold">Mobile Banking Payment</h1>
                <p class="mt-2">
                    Payment Due for 
                    Year: <strong><?= htmlspecialchars($payment['Year']) ?></strong>, 
                    Semester: <strong><?= htmlspecialchars($payment['Semester']) ?></strong>
                </p>
            </div>
            <div class="p-6">
                <?php if (!empty($error)): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($message)): ?>
                    <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <!-- Payment Form -->
                <form action="" method="POST">
                    <div class="mb-6">
                        <label for="payment_method" class="block text-gray-700 font-bold mb-2">
                            Payment Method
                        </label>
                        <!-- Radio buttons with icons for payment method selection -->
                        <div class="grid grid-cols-3 gap-4">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="Bkash" class="form-radio">
                                <img src="./bkash.png" alt="Bkash Logo">
                                <span class="mt-2 text-sm">Bkash</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="Nagad" class="form-radio">
                                <img src="./1679248787Nagad-Logo.webp" alt="Nagad Logo">
                                <span class="mt-2 text-sm">Nagad</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="Rocket" class="form-radio">
                                <img src="./Rocket_mobile_banking_logo.svg.png" alt="Rocket Logo">
                                <span class="mt-2 text-sm">Rocket</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="transaction_id" class="block text-gray-700 font-bold mb-2">
                            Transaction ID
                        </label>
                        <input type="text" name="transaction_id" id="transaction_id" placeholder="Enter Transaction ID" class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" name="make_payment" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                        Make Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>