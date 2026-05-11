<?php
require 'config/config.php';
try {
    $pdo = Database::getInstance();
    echo 'Database connected successfully.';
} catch (Exception $e) {
    echo 'Database connection error: ' . $e->getMessage();
}
?>