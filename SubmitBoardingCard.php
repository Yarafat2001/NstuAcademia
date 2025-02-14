<?php

include 'navbarhome.php'; // Include Navbar
include './connection.php'; // Database Connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if student_id and student_name are set in session
    if (!isset($_SESSION['student_id']) || !isset($_SESSION['student_name'])) {
        die("Session data not found. Please log in.");
    }

    // Collect form data
    $studentID = $_SESSION['student_id'];
    $studentName = $_SESSION['student_name'];
    // echo  $studentID;
    // echo  $studentName;
    $hallName = $_POST['hallName'];
    $roomNumber = $_POST['roomNumber'];
    $seatNumber = $_POST['seatNumber'];

    // File upload handling
    $boardingCard = $_FILES['boardingCard'];
    $targetDir = __DIR__ . "/uploads/$studentID/";

    // Create directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory recursively
    }

    // Generate unique file name
    $targetFile = $targetDir . date('Y-m-d-H-i-s') . '-' . basename($boardingCard["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file type
    $allowedTypes = ['jpg', 'png', 'pdf'];
    if (!in_array($fileType, $allowedTypes)) {
        die("Only JPG, PNG, and PDF files are allowed.");
    }

    // Validate file size (2MB limit)
    if ($boardingCard["size"] > 2 * 1024 * 1024) {
        die("File size should not exceed 2MB.");
    }

    // Move uploaded file to target directory
    if (move_uploaded_file($boardingCard["tmp_name"], $targetFile)) {
        // Insert data into the database
        $sql = "INSERT INTO boarding_cards (student_id, student_name, hall_name, room_number, seat_number, file_path)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssss", $studentID, $studentName, $hallName, $roomNumber, $seatNumber, $targetFile);
            if ($stmt->execute()) {
                echo "Boarding card submitted successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Error uploading the file.";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Boarding Card</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Optional: You can add custom styles here */
    </style>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6 max-w-md w-full">
            <h2 class="text-2xl font-bold text-center mb-6">Submit Boarding Card</h2>
            <form id="boardingCardForm" action="SubmitBoardingCard.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="hallName" class="block text-sm font-medium text-gray-700">Select Hall</label>
                    <select id="hallName" name="hallName" required
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-50 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Select Hall</option>
                        <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall">Bangamata Sheikh Fazilatunnesa Mujib Hall
                        </option>
                        <option value="Bangabandhu Sheikh Mujibur Rahman Hall">Bangabandhu Sheikh Mujibur Rahman Hall
                        </option>
                        <option value="Bibi Khadiza Hall">Bibi Khadiza Hall</option>
                        <option value="Abdus Salam Hall">Abdus Salam Hall</option>
                        <option value="Abdul Malek Ukil Hall">Abdul Malek Ukil Hall</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="roomNumber" class="block text-sm font-medium text-gray-700">Room Number</label>
                    <input type="text" id="roomNumber" name="roomNumber"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-50 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Enter your room number" required>
                </div>
                <div class="mb-4">
                    <label for="seatNumber" class="block text-sm font-medium text-gray-700">Seat Number</label>
                    <select id="seatNumber" name="seatNumber" required
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-50 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Select Seat</option>
                        <option value="1">Seat 1</option>
                        <option value="2">Seat 2</option>
                        <option value="3">Seat 3</option>
                        <option value="4">Seat 4</option>
                        <option value="5">Seat 5</option>
                        <option value="6">Seat 6</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="boardingCard" class="block text-sm font-medium text-gray-700">Upload Boarding
                        Card</label>
                    <div class="flex items-center justify-between">
                        <label for="boardingCard"
                            class="flex-1 mt-2 py-2 px-3 border border-gray-300 bg-gray-50 rounded-md shadow-sm text-center cursor-pointer hover:bg-gray-100 hover:border-gray-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500">
                            <span class="text-gray-500">Choose a file...</span>
                            <input type="file" id="boardingCard" name="boardingCard" accept=".pdf,.jpg,.png" required
                                class="sr-only">
                        </label>
                    </div>
                </div>
                <button type="submit"
                    class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                    Submit Boarding Card
                </button>
            </form>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>

</body>

</html>


