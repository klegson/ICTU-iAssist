<?php
session_start();

require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

if (!isset($_GET['ref']) || !isset($_GET['name']) || !isset($_GET['date']) || !isset($_GET['location'])) {
    die("Error: Missing required event information to generate the PDF.");
}

$refNumber = $_GET['ref'];
$eventName = $_GET['name'];
$eventDateRaw = $_GET['date'];
$location = $_GET['location'];

$currentDate = date("F d, Y");
$eventDateFormatted = date("F d, Y", strtotime($eventDateRaw));

$pdf = new Fpdi();

$pdf->AddPage();
$pdf->setSourceFile('Links/ICT-STARLINK-AGREEMENT-FORM.pdf');

$tplIdx1 = $pdf->importPage(1);
$pdf->useTemplate($tplIdx1);

$pdf->SetFont('Arial', '', 12);

$pdf->SetXY(60, 56);
$pdf->Write(0, $refNumber);

$pdf->SetXY(45, 65);
$pdf->Write(0, $currentDate);

// --- PAGE 2 ---
$pdf->AddPage();
$tplIdx2 = $pdf->importPage(2);
$pdf->useTemplate($tplIdx2);

$pdf->SetFont('Arial', '', 12);

$pdf->SetXY(90, 104);
$pdf->Write(0, $eventName);

$pdf->SetXY(86, 113);
$pdf->Write(0, $eventDateFormatted);

$pdf->SetXY(83, 122);
$pdf->Write(0, $location);

$pdf->Output('D', 'Starlink_Agreement_' . $refNumber . '.pdf');
