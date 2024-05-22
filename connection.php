<?php
$host = 'localhost';
$dbname = 'rental_system';
$username = 'imsberi';
$password = 'September30';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If connection fails, display error message
    echo "Connection failed: " . $e->getMessage();
}

?>