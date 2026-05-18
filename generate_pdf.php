<?php
session_start();
require_once 'db.php';
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

$userId = $_SESSION['user_id'] ?? null;
$sql = "SELECT u.firstName, u.middleName, u.lastName, u.signature, d.departmentName, d.section_unit 
        FROM users u 
        LEFT JOIN department d ON u.departmentId = d.departmentId 
        WHERE u.userId = ?";
$userStmt = $pdo->prepare($sql);
$userStmt->execute([$userId]);
$user = $userStmt->fetch();

$borrowerName = $user ? formatName($user['firstName'], $user['middleName'] ?? '', $user['lastName']) : 'N/A';
$signature = $user['signature'] ?? null;
$divisionUnit = $user ? trim(($user['departmentName'] ?? '') . ($user['section_unit'] ? ' / ' . $user['section_unit'] : ''), ' /') : 'N/A';

$currentDate = date("F d, Y");
$eventDateFormatted = date("F d, Y", strtotime($eventDateRaw));

$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile('Links/ICT-STARLINK-AGREEMENT-FORM.pdf');

// --- PAGE 1 ---
$pdf->AddPage();
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

// (Bottom of Page 2) ---

$pdf->SetXY(123, 251);
$pdf->Write(0, $borrowerName);

$pdf->SetXY(66, 260);
$pdf->Write(0, $divisionUnit);

$pdf->SetXY(47, 268);
$pdf->Write(0, $currentDate);

$pdf->Output('I', 'Starlink_Agreement_' . $refNumber . '.pdf');
