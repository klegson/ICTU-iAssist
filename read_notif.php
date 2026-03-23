<?php
session_start();
require 'db.php';

if (isset($_GET['id']) && isset($_GET['url'])) {
    $notifId = $_GET['id'];
    $targetUrl = urldecode($_GET['url']);

    $stmt = $pdo->prepare("UPDATE notification SET isRead = 1 WHERE notifId = ?");
    $stmt->execute([$notifId]);

    header("Location: " . $targetUrl);
    exit;
} else {
    header("Location: login.php");
    exit;
}
