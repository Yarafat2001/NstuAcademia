<?php include './S_navbar.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "fetch_hall_details") {
    header('Content-Type: application/json');

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nstu_database";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
    }

    $sql = "SELECT * FROM hall_allocation";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $hallData = [];
        while ($row = $result->fetch_assoc()) {
            $hallData[] = $row;
        }
        echo json_encode($hallData);
    } else {
        echo json_encode([]);
    }

    $conn->close();
    exit();
}
?>
  <!-- Main Content -->
    <main class="container mx-auto p-6 mt-10 bg-white rounded-lg shadow-lg border-t-8 border-blue-500">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Hall Seat Notice</h1>
        <p class="text-gray-700 mb-6 text-lg">
            The university has announced the allocation of hall seats for the upcoming academic session. Please check the hall allocation and confirm your hall assignment.
        </p>
        <p class="text-gray-700 mb-6 text-lg">
            Students are advised to follow the hall rules and report to the hall authorities if they encounter any issues with their assigned rooms.
        </p>
        <a href="ViewHallSeat.html" class="text-blue-500 hover:text-blue-700 underline font-semibold">View Hall Allocation Details</a>

        <div class="mt-8 flex space-x-4">
    <a href="ViewHallSeat.php" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-bold shadow hover:bg-blue-700 transition duration-300">View Hall Allocation</a>
    <a href="SubmitBoardingCard.php" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-bold shadow hover:bg-green-700 transition duration-300">Submit Boarding Card</a>
    <a href="HallApplication.php" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-bold shadow hover:bg-blue-700 transition duration-300">Apply for Hall Seat</a>
    </div>

    </main>

    <!-- Footer -->
    <footer class="bg-university-blue text-white mt-16">
        <div class="container mx-auto py-8 text-center">
            <p class="font-semibold">&copy; 2024 NstuAcademia. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>

