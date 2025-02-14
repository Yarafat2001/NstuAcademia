<?php
// Database connection details
$host = '127.0.0.1';
$db = 'nstu_academia';
$user = 'root'; // Update with your MySQL username
$pass = ''; // Update with your MySQL password
$charset = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    // Create a PDO connection
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: Please try again later.");
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $eduMail = htmlspecialchars(trim($_POST['eduMail']));
    $password = htmlspecialchars(trim($_POST['password']));

    try {
        // Query to check if the teacher exists
        $sql = "SELECT * FROM teacher WHERE Edu_Mail = :eduMail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':eduMail', $eduMail);
        $stmt->execute();

        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($teacher && password_verify($password, $teacher['password'])) {
            // Start session and store teacher details
            session_start();
            $_SESSION['teacher_id'] = $teacher['ID'];
            $_SESSION['teacher_name'] = $teacher['Name'];

            // Redirect to dashboard
            header("Location: Teacher(Dashboard).php");
            exit;
        } else {
            $error = "Invalid Edu Mail or Password.";
        }
    } catch (PDOException $e) {
        $error = "An error occurred. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">Teacher Login</h2>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form class="space-y-6" method="POST" action="">
                <div>
                    <label for="eduMail" class="block text-sm font-medium text-gray-700 mb-1">Edu Mail</label>
                    <input type="email" id="eduMail" name="eduMail" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your Edu Mail" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your Password" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg font-medium hover:bg-blue-600 transition duration-200">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>
