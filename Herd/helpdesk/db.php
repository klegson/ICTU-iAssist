<?php
// Database configuration for local OJT server
$host = "127.0.0.1";
$user = "root";
$pass = ""; 
$dbname = "helpdesk"; // Change to your actual DB name in phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>