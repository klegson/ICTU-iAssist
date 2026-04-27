<?php
require 'db.php';

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN profilePicture VARCHAR(255) DEFAULT NULL");
    echo "Column 'profilePicture' added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate co  lumn') !== false) {
        echo "Column 'profilePicture' already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
