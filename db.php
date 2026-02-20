<?php
$host = '127.0.0.1';
$db   = 'helpdesk';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

function timeAgo($datetime)
{
    $time_ago = strtotime($datetime);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60);
    $hours   = round($seconds / 3600);
    $days    = round($seconds / 86400);
    $weeks   = round($seconds / 604800);
    $months  = round($seconds / 2629440);
    $years   = round($seconds / 31553280);
    if ($seconds <= 60) {
        return "Just now";
    } else if ($minutes <= 60) {
        return "$minutes mins ago";
    } else if ($hours <= 24) {
        return "$hours hrs ago";
    } else if ($days <= 7) {
        return "$days days ago";
    } else if ($weeks <= 4.3) {
        return "$weeks weeks ago";
    } else if ($months <= 12) {
        return "$months months ago";
    } else {
        return "$years years ago";
    }
}
