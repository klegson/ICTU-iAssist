<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $ticketId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT status FROM ticket WHERE ticketId = ? AND userId = ?");
    $stmt->execute([$ticketId, $userId]);
    $ticket = $stmt->fetch();

    if ($ticket && $ticket['status'] == 'Pending') {
        $delStmt = $pdo->prepare("DELETE FROM ticket WHERE ticketId = ?");
        $delStmt->execute([$ticketId]);

        header("Location: db_user.php?msg=deleted");
    } else {
        header("Location: db_user.php?error=cannot_delete");
    }
} else {
    header("Location: db_user.php");
}
