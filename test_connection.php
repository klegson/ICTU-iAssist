<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

echo "<h1>Database Diagnostic</h1>";

try {
    if ($pdo) {
        echo "<p style='color:green'>✅ Database Connection Successful.</p>";
    } else {
        echo "<p style='color:red'>❌ Database Connection FAILED.</p>";
        exit;
    }

    echo "<p>Attempting to fetch categories...</p>";
    $stmt = $pdo->query("SELECT * FROM category");

    if (!$stmt) {
        $errorInfo = $pdo->errorInfo();
        echo "<p style='color:red'>❌ Query FAILED. Error: " . $errorInfo[2] . "</p>";
        echo "<p><strong>Hint:</strong> Check if your table name is exactly <code>category</code> in phpMyAdmin.</p>";
    } else {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p style='color:green'>✅ Query Successful.</p>";
        echo "<p>Found <strong>" . count($rows) . "</strong> categories.</p>";

        echo "<pre>";
        print_r($rows); // Show the raw data
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ CRITICAL ERROR: " . $e->getMessage() . "</p>";
}
