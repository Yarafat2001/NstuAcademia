<?php
require_once 'vendor/autoload.php'; // Composer autoloader
use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Your HTML content
$html = '<html><body><h1>Test PDF using Dompdf</h1><p>This is a sample PDF.</p></body></html>';

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size
$dompdf->setPaper('A4', 'portrait');

// Render PDF (first pass - without stream)
$dompdf->render();

// Output PDF to browser
$dompdf->stream('generated_pdf.pdf');
?>
