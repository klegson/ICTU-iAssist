<?php
session_start();
require 'db.php';

require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

if (!isset($_GET['ref'])) {
    die("No reference number provided.");
}

$refNumber = $_GET['ref'];

$sql = "SELECT * FROM starlink WHERE reference_number = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$refNumber]);
$event = $stmt->fetch();

if (!$event) {
    die("Event not found.");
}

$currentDate = date("F d, Y");
$eventDateFormatted = date("F d, Y", strtotime($event['event_date']));

$pdf = new Fpdi();

$pdf->AddPage();
$pdf->setSourceFile('Links/ICT-STARLINK-AGREEMENT-FORM.pdf');

$tplIdx1 = $pdf->importPage(1);
$pdf->useTemplate($tplIdx1);

$pdf->SetFont('Arial', '', 12);

$pdf->SetXY(60, 56);
$pdf->Write(0, $event['reference_number']);

$pdf->SetXY(45, 65);
$pdf->Write(0, $currentDate);

$pdf->AddPage();
$tplIdx2 = $pdf->importPage(2);
$pdf->useTemplate($tplIdx2);

$pdf->SetFont('Arial', '', 12);

$pdf->SetXY(90, 104);
$pdf->Write(0, $event['event_name']);

$pdf->SetXY(86, 113);
$pdf->Write(0, $eventDateFormatted);

$pdf->SetXY(83, 122);
$pdf->Write(0, $event['location']);

$pdf->Output('D', 'Starlink_Agreement_' . $event['reference_number'] . '.pdf');
