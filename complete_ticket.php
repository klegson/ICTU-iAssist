<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: login.php");
    exit;
}

$ticketId = isset($_GET['ticket_id']) ? (int)$_GET['ticket_id'] : 0;
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT ticketId, status FROM ticket WHERE ticketId = ? AND userId = ?");
$stmt->execute([$ticketId, $userId]);
$ticket = $stmt->fetch();

if ($ticket && $ticket['status'] === 'Resolved') {
    $update = $pdo->prepare("UPDATE ticket SET status = 'Completed', completedAt = NOW() WHERE ticketId = ?");
    $update->execute([$ticketId]);
}

header("Location: view_ticket.php?id=" . $ticketId . "&completed=1");
exit;
