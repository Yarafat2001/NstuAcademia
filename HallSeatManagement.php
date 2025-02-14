<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './connection.php';
include './S_navbar.php';


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Insert Initial Data for All Halls (Initialization Script)
// Check if data already exists to prevent duplicate insertions
$check_sql = "SELECT COUNT(*) AS count FROM seats";
$result = $conn->query($check_sql);
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    // Define the data for all halls, floors, and rooms
    $halls = [
        "Bangamata Sheikh Fazilatunnesa Mujib Hall" => [
            "1" => ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
            "2" => ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
            "3" => ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
            "4" => ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
            "5" => ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
        ],
        "Bangabandhu Sheikh Mujibur Rahman Hall" => [
            "1" => ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
            "2" => ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
            "3" => ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
            "4" => ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
            "5" => ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
        ],
        "Bibi Khadiza Hall" => [
            "1" => ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
            "2" => ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
            "3" => ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
            "4" => ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
            "5" => ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
        ],
        "Abdus Salam Hall" => [
            "1" => ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
            "2" => ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
            "3" => ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
            "4" => ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
            "5" => ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
        ],
        "Abdul Malek Ukil Hall" => [
            "1" => ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
            "2" => ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
            "3" => ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
            "4" => ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
            "5" => ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
        ]
    ];

    // Prepare the SQL insert statement
    $insert_sql = "INSERT INTO seats (hall, floor, room, bed_number, is_available) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);

    if ($stmt === false) {
        die("Error preparing insert statement: " . $conn->error);
    }

    // Iterate through the data and insert into the database
    foreach ($halls as $hall => $floors) {
        foreach ($floors as $floor => $rooms) {
            foreach ($rooms as $room) {
                for ($bedNumber = 1; $bedNumber <= 6; $bedNumber++) {
                    $is_available = 1; // Initially all beds are available
                    $stmt->bind_param("sisis", $hall, $floor, $room, $bedNumber, $is_available);
                    if (!$stmt->execute()) {
                        die("Error executing insert statement: " . $stmt->error);
                    }
                }
            }
        }
    }

    // Close the statement
    $stmt->close();

    echo "Initial data inserted successfully.<br>";
}

// Step 2: Read and Update Bed Availability Data (CRUD Operations)
// Fetch bed availability data from the database
$bed_availability_data = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['hall']) && isset($_GET['floor']) && isset($_GET['room'])) {
    $hall = $_GET['hall'];
    $floor = $_GET['floor'];
    $room = $_GET['room'];

    $fetch_sql = "SELECT * FROM seats WHERE hall = ? AND floor = ? AND room = ?";
    $stmt = $conn->prepare($fetch_sql);
    if ($stmt) {
        $stmt->bind_param("sss", $hall, $floor, $room);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $bed_availability_data[] = $row;
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $hall = $_POST['hall'];
    $floor = $_POST['floor'];
    $room = $_POST['room'];
    $beds = $_POST['beds']; // Comma-separated string of bed availability (0 or 1)

    // Validate form data
    if (empty($hall) || empty($floor) || empty($room) || empty($beds)) {
        die("All fields are required.");
    }

    // Prepare bed availability data
    $bed_status = explode(',', $beds);

    // Delete existing records for the room to update with the latest data
    $delete_sql = "DELETE FROM seats WHERE hall = ? AND floor = ? AND room = ?";
    $stmt = $conn->prepare($delete_sql);
    if ($stmt === false) {
        die("Error preparing delete statement: " . $conn->error);
    }
    $stmt->bind_param("sss", $hall, $floor, $room);
    if (!$stmt->execute()) {
        die("Error executing delete statement: " . $stmt->error);
    }
    $stmt->close();

    // Insert new bed availability data
    $insert_sql = "INSERT INTO seats (hall, floor, room, bed_number, is_available) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    if ($stmt === false) {
        die("Error preparing insert statement: " . $conn->error);
    }

    foreach ($bed_status as $index => $is_available) {
        $bed_number = $index + 1; // Bed numbers start from 1
        $stmt->bind_param("sssis", $hall, $floor, $room, $bed_number, $is_available);
        if (!$stmt->execute()) {
            die("Error executing insert statement: " . $stmt->error);
        }
    }

    $stmt->close();
    $conn->close();

    echo "Seat status updated successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Seat Management - NstuAcademia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }

        .admin-panel {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 900px;
            margin: 2rem auto;
        }

        .form-group label {
            font-weight: bold;
            color: #0033A0;
            margin-bottom: 8px;
            display: block;
        }

        .form-group select {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            background-color: #f8fafc;
        }

        .update-button {
            background-color: #0033A0;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 6px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .update-button:hover {
            background-color: #00267a;
            transform: scale(1.05);
        }

        .room-layout {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .bed {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .bed.unavailable {
            background-color: #f1948a;
        }

        .bed.available {
            background-color: #4caf50;
        }

        @media (max-width: 768px) {
            .bed {
                width: 70px;
                height: 70px;
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-panel">
        <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Hall Seat Management</h2>
        <form id="hallSeatForm" method="GET" action="">
            <div class="form-group">
                <label for="hallSelect">Select Hall</label>
                <select id="hallSelect" name="hall" onchange="updateFloors()">
                    <option value="">Select Hall</option>
                    <?php
                    $halls = ["Bangamata Sheikh Fazilatunnesa Mujib Hall", "Bangabandhu Sheikh Mujibur Rahman Hall", "Bibi Khadiza Hall", "Abdus Salam Hall", "Abdul Malek Ukil Hall"];
                    foreach ($halls as $hall) {
                        $selected = (isset($_GET['hall']) && $_GET['hall'] == $hall) ? 'selected' : '';
                        echo "<option value=\"$hall\" $selected>$hall</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="floorSelect">Select Floor</label>
                <select id="floorSelect" name="floor" onchange="updateRooms()">
                    <option value="">Select Floor</option>
                    <?php if (isset($_GET['hall']) && !empty($floorRooms[$_GET['hall']])): ?>
                        <?php foreach (array_keys($floorRooms[$_GET['hall']]) as $floor): ?>
                            <option value="<?php echo $floor; ?>" <?php echo (isset($_GET['floor']) && $_GET['floor'] == $floor) ? 'selected' : ''; ?>>Floor <?php echo $floor; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="roomSelect">Select Room</label>
                <select id="roomSelect" name="room" onchange="this.form.submit()">
                    <option value="">Select Room</option>
                    <?php if (isset($_GET['floor']) && isset($floorRooms[$_GET['hall']][$_GET['floor']])): ?>
                        <?php foreach ($floorRooms[$_GET['hall']][$_GET['floor']] as $room): ?>
                            <option value="<?php echo $room; ?>" <?php echo (isset($_GET['room']) && $_GET['room'] == $room) ? 'selected' : ''; ?>>Room <?php echo $room; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </form>

        <?php if (!empty($bed_availability_data)): ?>
        <form id="hallSeatFormUpdate" method="POST" action="">
            <input type="hidden" name="hall" value="<?php echo isset($_GET['hall']) ? $_GET['hall'] : ''; ?>">
            <input type="hidden" name="floor" value="<?php echo isset($_GET['floor']) ? $_GET['floor'] : ''; ?>">
            <input type="hidden" name="room" value="<?php echo isset($_GET['room']) ? $_GET['room'] : ''; ?>">

            <div id="roomLayout" class="room-layout">
                <?php foreach ($bed_availability_data as $bed) : ?>
                    <div class="bed <?php echo $bed['is_available'] == 0 ? 'unavailable' : 'available'; ?>" data-bed-number="<?php echo $bed['bed_number']; ?>">
                        Bed <?php echo $bed['bed_number']; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" id="bedsInput" name="beds">

            <div class="text-center mt-6">
                <button type="submit" onclick="prepareSeatData()" class="update-button">Update Seat Status</button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script>
        const floorRooms = {
            "Bangamata Sheikh Fazilatunnesa Mujib Hall": {
                "1": ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
                "2": ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
                "3": ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
                "4": ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
                "5": ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
            },
            "Bangabandhu Sheikh Mujibur Rahman Hall": {
                "1": ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
                "2": ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
                "3": ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
                "4": ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
                "5": ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
            },
            "Bibi Khadiza Hall": {
                "1": ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
                "2": ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
                "3": ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
                "4": ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
                "5": ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
            },
            "Abdus Salam Hall": {
                "1": ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
                "2": ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
                "3": ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
                "4": ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
                "5": ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
            },
            "Abdul Malek Ukil Hall": {
                "1": ["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"],
                "2": ["201", "202", "203", "204", "205", "206", "207", "208", "209", "210"],
                "3": ["301", "302", "303", "304", "305", "306", "307", "308", "309", "310"],
                "4": ["401", "402", "403", "404", "405", "406", "407", "408", "409", "410"],
                "5": ["501", "502", "503", "504", "505", "506", "507", "508", "509", "510"]
            }
        };

        function updateFloors() {
            const hallSelect = document.getElementById("hallSelect");
            const floorSelect = document.getElementById("floorSelect");
            const selectedHall = hallSelect.value;
            floorSelect.innerHTML = "<option value=''>Select Floor</option>";

            if (selectedHall && floorRooms[selectedHall]) {
                Object.keys(floorRooms[selectedHall]).forEach(floor => {
                    const option = document.createElement("option");
                    option.value = floor;
                    option.textContent = 'Floor ' + floor;
                    floorSelect.appendChild(option);
                });
            }
        }

        function updateRooms() {
            const hallSelect = document.getElementById("hallSelect");
            const floorSelect = document.getElementById("floorSelect");
            const roomSelect = document.getElementById("roomSelect");
            const selectedHall = hallSelect.value;
            const selectedFloor = floorSelect.value;
            roomSelect.innerHTML = "<option value=''>Select Room</option>";

            if (selectedHall && selectedFloor && floorRooms[selectedHall]) {
                floorRooms[selectedHall][selectedFloor].forEach(room => {
                    const option = document.createElement("option");
                    option.value = room;
                    option.textContent = 'Room ' + room;
                    roomSelect.appendChild(option);
                });
            }
        }

        function prepareSeatData() {
            const bedElements = document.querySelectorAll(".bed");
            const beds = Array.from(bedElements).map(bed => bed.classList.contains("unavailable") ? 0 : 1);
            document.getElementById("bedsInput").value = beds.join(",");
        }

        document.addEventListener("DOMContentLoaded", () => {
            const bedElements = document.querySelectorAll(".bed");
            bedElements.forEach(bed => {
                bed.addEventListener("click", function () {
                    if (this.classList.contains("unavailable")) {
                        this.classList.remove("unavailable");
                        this.classList.add("available");
                    } else {
                        this.classList.remove("available");
                        this.classList.add("unavailable");
                    }
                });
            });
        });
    </script>
</body>

</html>
