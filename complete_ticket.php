<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['ticket_id'] ?? null;

if ($ticketId) {
    $stmt = $pdo->prepare("UPDATE ticket SET status = 'Completed', completedAt = NOW() WHERE ticketId = ? AND userId = ? AND status = 'Resolved'");
    $stmt->execute([$ticketId, $_SESSION['user_id']]);
}

header("Location: db_user.php");
exit;