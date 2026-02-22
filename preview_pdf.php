<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Please fill out the form and click Download first.");
}

require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

$refNumber = $_POST['ref_number'] ?? 'N/A';
$eventName = $_POST['event_name'] ?? '';
$eventDate = $_POST['event_date'] ?? '';
$location = $_POST['location'] ?? '';

$currentDate = date("F d, Y");
$eventDateFormatted = $eventDate ? date("F d, Y", strtotime($eventDate)) : '';

$pdf = new Fpdi();

$pdf->AddPage();
$pdf->setSourceFile('Links/ICT-STARLINK-AGREEMENT-FORM.pdf');
$tplIdx1 = $pdf->importPage(1);
$pdf->useTemplate($tplIdx1);

$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(65, 55);
$pdf->Write(0, $refNumber);
$pdf->SetXY(35, 62);
$pdf->Write(0, $currentDate);

$pdf->AddPage();
$tplIdx2 = $pdf->importPage(2);
$pdf->useTemplate($tplIdx2);

$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(60, 102);
$pdf->Write(0, $eventName);
$pdf->SetXY(45, 112);
$pdf->Write(0, $eventDateFormatted);
$pdf->SetXY(55, 122);
$pdf->Write(0, $location);

$pdf->Output('D', 'Starlink_Agreement_' . $refNumber . '.pdf');
