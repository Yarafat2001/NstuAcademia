<?php

include './S_navbar.php';
include './connection.php';

// Function to interpret file upload errors
function fileUploadError($errorCode)
{
    $errors = [
        0 => 'No error, the file uploaded successfully.',
        1 => 'The file exceeds the upload_max_filesize directive in php.ini.',
        2 => 'The file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        3 => 'The file was only partially uploaded.',
        4 => 'No file was uploaded.',
        6 => 'Missing a temporary folder.',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];
    return $errors[$errorCode] ?? 'Unknown error.';
}

$message = ""; // Message to display feedback to the user

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get student_id from session
    if (!isset($_SESSION['student_id'])) {
        die("Session student_id not set. Please log in.");
    }
    $studentID = $_SESSION['student_id'];

    // Get other form values
    $semester = $_POST['semester'] ?? '';
    $hallPreference = $_POST['hallPreference'] ?? '';
    $specialNote = $_POST['specialNote'] ?? '';

    // Validate required fields
    if (empty($semester) || empty($hallPreference)) {
        $message = "Semester and Hall Preference are required.";
    } else {
        // Calculate expiry_date based on semester
        $currentDate = new DateTime(); // Current date
        $semesterNumber = (int) filter_var($semester, FILTER_SANITIZE_NUMBER_INT); // Extract semester number
        $expiryYears = (8 - $semesterNumber) / 2; // Calculate remaining years
        $expiryDate = $currentDate->add(new DateInterval('P' . ($expiryYears * 12) . 'M'))->format('Y-m-d'); // Add months

        // File uploads
        $documents = [
            'academicTranscript',
            'disabilityCertificate',
            'incomeCertificate',
            'orphanCertificate',
            'minorityCertificate',
            'bnccCertificate',
            'nidOrBirthCertificate',
            'mastersAdmissionReceipt'
        ];

        $uploadedFiles = [];
        $uploadDir = "uploads/$studentID/"; // Directory based on studentID

        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Process each file upload
        foreach ($documents as $doc) {
            if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] === UPLOAD_ERR_OK) {
                $filePath = $uploadDir . basename($_FILES[$doc]['name']);
                if (move_uploaded_file($_FILES[$doc]['tmp_name'], $filePath)) {
                    $uploadedFiles[$doc] = $filePath;
                } else {
                    $message .= "Error moving file: " . $_FILES[$doc]['name'] . "<br>";
                }
            }
        }

        // Assign file paths or NULL for each upload
        $academicTranscript = $uploadedFiles['academicTranscript'] ?? NULL;
        $disabilityCertificate = $uploadedFiles['disabilityCertificate'] ?? NULL;
        $incomeCertificate = $uploadedFiles['incomeCertificate'] ?? NULL;
        $orphanCertificate = $uploadedFiles['orphanCertificate'] ?? NULL;
        $minorityCertificate = $uploadedFiles['minorityCertificate'] ?? NULL;
        $bnccCertificate = $uploadedFiles['bnccCertificate'] ?? NULL;
        $nidBirthCertificate = $uploadedFiles['nidOrBirthCertificate'] ?? NULL;
        $mastersAdmissionReceipt = $uploadedFiles['mastersAdmissionReceipt'] ?? NULL;

        // Insert into the database if required files are uploaded
        if (!empty($academicTranscript) && !empty($nidBirthCertificate)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO hall_applications 
                (student_id, semester, hall_preference, special_note, expiry_date, 
                academic_transcript_file, disability_certificate_file, 
                income_certificate_file, orphan_certificate_file, 
                minority_certificate_file, bncc_certificate_file, 
                nid_birth_certificate_file, masters_admission_receipt_file) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Check if the statement was prepared successfully
            if (!$stmt) {
                die("SQL error: " . $conn->error);
            }

            // Bind parameters
            $stmt->bind_param(
                "sssssssssssss",
                $studentID,
                $semester,
                $hallPreference,
                $specialNote,
                $expiryDate,
                $academicTranscript,
                $disabilityCertificate,
                $incomeCertificate,
                $orphanCertificate,
                $minorityCertificate,
                $bnccCertificate,
                $nidBirthCertificate,
                $mastersAdmissionReceipt
            );

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Application submitted successfully.";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message .= "Required files (Academic Transcript and NID/Birth Certificate) are missing.";
        }
    }

    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia - Submit Boarding Card</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            margin: 40px auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .submit-button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        // Client-side validation for educational email
        function validateForm() {
            const eduEmail = document.getElementById('eduEmail').value;
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[-a-zA-Z0-9]+\.nstu\.edu\.bd$/;
            if (!emailPattern.test(eduEmail)) {
                alert("Invalid educational email. Please use an email ending with -nstu.edu.bd.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <section class="container mx-auto py-12 px-4 md:px-0">
        <h2 class="text-3xl font-bold text-blue-900 text-center mb-8">Hall Seat Application</h2>

        <?php if (!empty($message)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form id="hallApplicationForm" class="form-container" action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">

            <div class="form-group">
                <label for="semester">Semester</label>
                <select id="semester" name="semester" required>
                    <option value="">Select Semester</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="3rd Semester">3rd Semester</option>
                    <option value="4th Semester">4th Semester</option>
                    <option value="5th Semester">5th Semester</option>
                    <option value="6th Semester">6th Semester</option>
                    <option value="7th Semester">7th Semester</option>
                    <option value="8th Semester">8th Semester</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hallPreference">Hall Preference *</label>
                <select id="hallPreference" name="hallPreference" required>
                    <option value="">Select Hall</option>
                    <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall">Bangamata Sheikh Fazilatunnesa Mujib Hall</option>
                    <option value="Bangabandhu Sheikh Mujibur Rahman Hall">Bangabandhu Sheikh Mujibur Rahman Hall</option>
                    <option value="Bibi Khadiza Hall">Bibi Khadiza Hall</option>
                    <option value="Abdus Salam Hall">Abdus Salam Hall</option>
                    <option value="Abdul Malek Ukil Hall">Abdul Malek Ukil Hall</option>
                </select>
            </div>


            <!-- Document Upload Section -->
            <div class="form-group">
                <label for="nidOrBirthCertificate">Upload Photocopy of National ID or Birth Registration Certificate *</label>
                <input type="file" id="nidOrBirthCertificate" name="nidOrBirthCertificate" required>
            </div>
            <div class="form-group">
                <label for="academicTranscript">Upload Transcript of Latest Academic Examination *</label>
                <input type="file" id="academicTranscript" name="academicTranscript" required>
            </div>

            <div class="form-group">
                <label for="disabilityCertificate">Upload Certificate of Physical Disability (if applicable)</label>
                <input type="file" id="disabilityCertificate" name="disabilityCertificate">
            </div>

            <div class="form-group">
                <label for="incomeCertificate">Upload Annual Income Certificate or Proof of Insolvency</label>
                <input type="file" id="incomeCertificate" name="incomeCertificate">
            </div>

            <div class="form-group">
                <label for="orphanCertificate">Upload Certificate in Case of Father/Motherless Applicant</label>
                <input type="file" id="orphanCertificate" name="orphanCertificate">
            </div>

            <div class="form-group">
                <label for="minorityCertificate">Upload Certificate for Minority Applicants</label>
                <input type="file" id="minorityCertificate" name="minorityCertificate">
            </div>

            <div class="form-group">
                <label for="bnccCertificate">Upload BNCC or Rover Scout Certificate</label>
                <input type="file" id="bnccCertificate" name="bnccCertificate">
            </div>



            <div class="form-group">
                <label for="mastersAdmissionReceipt">Upload Receipt of Admission to Master's Program (if applicable)</label>
                <input type="file" id="mastersAdmissionReceipt" name="mastersAdmissionReceipt">
            </div>

            <div class="form-group">
                <label for="specialNote">Special Note</label>
                <textarea id="specialNote" name="specialNote" rows="4" placeholder="Enter any special notes or requirements"></textarea>
            </div>

            <button type="submit" class="submit-button">Submit Application</button>
        </form>
    </section>
</body>

</html>