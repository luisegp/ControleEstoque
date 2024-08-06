<?php
$host = 'localhost'; // or the appropriate host
$dbname = 'dbestoque'; // replace with your database name
$username = 'root'; // replace with your MySQL username
$password = '1812.Dudu'; // replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
