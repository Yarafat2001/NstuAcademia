<?php
// Include the database connection file
include "connection.php";

// Set student_id to 15 directly
$student_id = 15;

// Fetch data with corrected query
$sql = "
    SELECT 
        p.studentName, p.hallName, p.profileImage, p.session, p.department, 
        r.Year, r.Semester 
    FROM 
        profiles AS p
    INNER JOIN 
        registration AS r ON p.id = r.profiles_id
    WHERE 
        p.studentId = ?
";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Check if the query was prepared successfully
if ($stmt === false) {
    die("Error preparing the SQL query: " . $conn->error);
}

// Bind the student_id parameter
$stmt->bind_param("i", $student_id);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if data was found
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No data found for student ID 15.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admit Card</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div id="admitCard" class="bg-white rounded-lg shadow-lg p-8 w-full max-w-lg">
    <div class="flex justify-between items-center border-b pb-4">
      <img src="nstu-logo.png" alt="NSTU Logo" class="h-16">
      <h2 class="text-center font-bold text-lg flex-1">Noakhali Science and Technology University</h2>
      <img src="<?php echo $data['profileImage']; ?>" alt="Profile" class="h-16 w-16 rounded-full border">
    </div>

    <div class="my-6 text-center">
      <p class="text-lg font-semibold">Year: <span class="font-normal"><?php echo $data['Year']; ?></span></p>
      <p class="text-lg font-semibold">Session: <span class="font-normal"><?php echo $data['session']; ?></span></p>
      <p class="text-lg font-semibold">Semester: <span class="font-normal"><?php echo $data['Semester']; ?></span></p>
      <h3 class="text-xl font-bold mt-4">Final Examination-2023</h3>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg mb-6">
      <p class="text-md"><strong>Name:</strong> <?php echo $data['studentName']; ?></p>
      <p class="text-md"><strong>Department:</strong> <?php echo $data['department']; ?></p>
      <p class="text-md"><strong>Hall:</strong> <?php echo $data['hallName']; ?></p>
    </div>

    <h4 class="text-lg font-semibold mb-2">Instructions to the Examinees</h4>
    <ul class="list-disc pl-6 text-sm space-y-1">
      <li>The examination will be held on the fixed date and time according to the program announced earlier by the Controller of Examination.</li>
      <li>The doors of the examination halls will be opened half an hour before the commencement of the examination.</li>
      <li>Examinees must enter the examination hall at least 15 minutes before examination time.</li>
      <li>Mobile phones are strictly prohibited in the examination hall.</li>
    </ul>

    <p class="text-right text-sm font-semibold mt-4">Controller of Examinations, NSTU</p>

    <div class="mt-6 flex justify-center">
      <button onclick="downloadPDF()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Download as PDF</button>
    </div>
  </div>

  <script>
    function downloadPDF() {
        const element = document.getElementById('admitCard');
        html2pdf().from(element).save('admit_card.pdf');
    }
  </script>
</body>
</html>
