<?php
$dsn = 'mysql:host=localhost;dbname=dbestoque;charset=utf8mb4';
$username = 'root';
$password = '1812.Dudu';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
}
?>
