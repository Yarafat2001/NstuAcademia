<?php include 'navbar(hallAdmin).php'; ?>
<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "nstu_academia"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch blank seat status with pagination
$sql = "SELECT student_id, student_name, hall_name, room_number, seat_number, file_path 
        FROM boarding_cards
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Prepare data for rendering
$hallsData = [
    'Bangamata Sheikh Fazilatunnesa Mujib Hall' => [],
    'Bangabandhu Sheikh Mujibur Rahman Hall' => [],
    'Bibi Khadiza Hall' => [],
    'Abdus Salam Hall' => [],
    'Abdul Malek Ukil Hall' => []
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (array_key_exists($row['hall_name'], $hallsData)) {
            $hallsData[$row['hall_name']][] = [
                'studentID' => $row['student_id'],
                'studentName' => $row['student_name'],
                'roomNumber' => $row['room_number'],
                'seatNumber' => $row['seat_number'],
                'boardingCardURL' => $row['file_path']
            ];
        }
    }
}

// Fetch total pages for pagination
$totalQuery = "SELECT COUNT(*) FROM boarding_cards";
$totalResult = $conn->query($totalQuery);
$totalRows = $totalResult->fetch_row()[0];
$totalPages = ceil($totalRows / $limit);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blank Seat Status - Hall Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-semibold text-center text-gray-900 mb-6">Blank Seat Status - Hall Admin</h2>

        <div id="hallsContainer">
            <?php foreach ($hallsData as $hallName => $data): ?>
                <div class="hall-section mb-10 bg-white rounded-lg shadow-lg p-6">
                    <div class="hall-title text-xl font-bold text-blue-600 mb-4"><?= htmlspecialchars($hallName) ?></div>
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead>
                            <tr class="bg-gray-100 text-left text-sm text-gray-600">
                                <th class="px-4 py-2">Student ID</th>
                                <th class="px-4 py-2">Student Name</th>
                                <th class="px-4 py-2">Room Number</th>
                                <th class="px-4 py-2">Seat Number</th>
                                <th class="px-4 py-2">Boarding Card</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($data) > 0): ?>
                                <?php foreach ($data as $item): ?>
                                    <tr class="text-sm text-gray-700 border-b">
                                        <td class="px-4 py-2"><?= htmlspecialchars($item['studentID']) ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($item['studentName']) ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($item['roomNumber']) ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($item['seatNumber']) ?></td>
                                        <td class="px-4 py-2">
                                            <a href="<?= htmlspecialchars($item['boardingCardURL']) ?>" target="_blank" class="text-blue-500 hover:underline">View Boarding Card</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No data available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="text-center mt-6">
                        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="downloadHallListPDF('<?= htmlspecialchars($hallName) ?>', <?= htmlspecialchars(json_encode($data)) ?>)">Download Hall List (PDF)</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination flex justify-center mt-8 space-x-4">
            <a href="?page=1" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">First</a>
            <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 <?= ($page <= 1) ? 'opacity-50 cursor-not-allowed' : '' ?>">Prev</a>
            <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 <?= ($page >= $totalPages) ? 'opacity-50 cursor-not-allowed' : '' ?>">Next</a>
            <a href="?page=<?= $totalPages ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Last</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script>
        // Function to download hall list as PDF
        function downloadHallListPDF(hallName, hallData) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(18);
            doc.text(`${hallName} - Blank Seat Status`, 14, 20);
            doc.setFontSize(12);
            doc.text('Student Details:', 14, 30);

            let y = 40;
            if (hallData.length > 0) {
                hallData.forEach((item) => {
                    doc.text(`Student ID: ${item.studentID}, Student Name: ${item.studentName}, Room Number: ${item.roomNumber}, Seat Number: ${item.seatNumber}`, 14, y);
                    y += 10;
                });
            } else {
                doc.text('No data available', 14, y);
            }

            doc.save(`${hallName}_Blank_Seat_Status.pdf`);
        }
    </script>
</body>
</html>
