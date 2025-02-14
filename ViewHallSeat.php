<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nstu_academia";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$seats = [];
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hall_name = $_POST['hall_name'] ?? '';
    $floor_number = intval($_POST['floor_number'] ?? 0);
    $room_number = $_POST['room_number'] ?? '';

    if (empty($hall_name) || empty($floor_number) || empty($room_number)) {
        $error = "Please select Hall, Floor, and Room.";
    } else {
        // Fetch seat data for the selected hall, floor, and room
        $stmt = $conn->prepare("
            SELECT hall, floor, room, bed_number, is_available 
            FROM seats 
            WHERE hall = ? AND floor = ? AND room = ?
        ");
        $stmt->bind_param("sis", $hall_name, $floor_number, $room_number);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $seats[] = $row;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<?php include 'navbarhome.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Seat Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .available {
            background-color: #d4edda; /* Green for available seats */
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .booked {
            background-color: #f8d7da; /* Red for booked seats */
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body class="p-6 bg-gray-100">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-800">Hall Seat Status</h1>

        <!-- Form Section -->
        <form method="POST" action="" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Hall Name -->
                <div>
                    <label for="hall_name" class="block text-gray-700 font-semibold mb-2">Hall Name</label>
                    <select id="hall_name" name="hall_name" required class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Hall</option>
                        <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall">Bangamata Sheikh Fazilatunnesa Mujib Hall</option>
                        <option value="Bangabandhu Sheikh Mujibur Rahman Hall">Bangabandhu Sheikh Mujibur Rahman Hall</option>
                        <option value="Bibi Khadiza Hall">Bibi Khadiza Hall</option>
                        <option value="Abdus Salam Hall">Abdus Salam Hall</option>
                        <option value="Abdul Malek Ukil Hall">Abdul Malek Ukil Hall</option>
                    </select>
                </div>

                <!-- Floor Number -->
                <div>
                    <label for="floor_number" class="block text-gray-700 font-semibold mb-2">Floor Number</label>
                    <input type="number" id="floor_number" name="floor_number" min="1" max="5" required placeholder="Enter Floor Number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>

                <!-- Room Number -->
                <div>
                    <label for="room_number" class="block text-gray-700 font-semibold mb-2">Room Number</label>
                    <input type="text" id="room_number" name="room_number" required placeholder="Enter Room Number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <button type="submit" class="mt-6 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Show Seat Status
            </button>
        </form>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 text-red-600 font-semibold text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Seat Status Table -->
        <?php if (!empty($seats)): ?>
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Seat Status</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <?php foreach ($seats as $seat): ?>
                    <div class="<?= $seat['is_available'] ? 'available' : 'booked'; ?> flex items-center justify-center p-4 rounded-md">
                        Bed <?= htmlspecialchars($seat['bed_number']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div class="text-gray-600 text-center font-semibold">No seats found for the selected criteria.</div>
        <?php endif; ?>
    </div>
</body>
</html>
