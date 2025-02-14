<?php
session_start();

// Ensure OTP is set
if (!isset($_SESSION['otp'])) {
    die("Session expired or invalid request. Please try again.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = trim($_POST['otp']);

    // Loose comparison (fixing int vs string issue)
    if ($enteredOtp == $_SESSION['otp']) { 
        // OTP is correct, clear it from session
        unset($_SESSION['otp']); 

        // Redirect to next step
        header('Location: signup_info.php');
        exit;
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - NSTU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-96 text-center transform transition-all duration-500 hover:scale-105">
        
        <img src="./nstu logo.jpg" alt="NSTU Logo" class="w-16 h-16 mx-auto mb-4">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Verify OTP</h1>
        <p class="text-gray-500 text-sm mb-6">Enter the **OTP** sent to your **NSTU student email**.</p>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- OTP Form -->
        <form method="POST" action="">
            <div class="mb-4">
                <label for="otp" class="block text-gray-700 font-bold mb-2">Enter OTP:</label>
                <input type="text" id="otp" name="otp" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 text-center text-xl font-bold tracking-widest shadow-sm" placeholder="123456" required>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold px-4 py-3 rounded-lg hover:shadow-lg hover:opacity-90 transition duration-300">
                Verify OTP
            </button>
        </form>

        <p class="mt-4 text-gray-500 text-sm">Didn't receive the OTP? <a href="signup.php" class="text-blue-600 hover:underline">Resend</a></p>

    </div>

</body>
</html>

