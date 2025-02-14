<?php
include './vendor/setasign/fpdf/fpdf.php';

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage(); // Add a new page

// Set font
$pdf->SetFont('Arial', 'B', 16);

// Add a title
$pdf->Cell(0, 10, 'My First PDF with PHP', 0, 1, 'C');

// Add some text
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10); // Add a line break
$pdf->MultiCell(0, 10, "This is a sample PDF generated using FPDF in PHP. You can customize the content and layout to fit your needs.");

// Add another section
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'R');

// Output the PDF
$pdf->Output('I', 'MyPDF.pdf'); // 'I' sends it to the browser
?>
