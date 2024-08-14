<?php
include 'db.php';

$subcategoria_id = $_GET['subcategoria_id'];
$stmt = $conn->prepare("SELECT * FROM produtos WHERE subcategoria_id = ?");
$stmt->execute([$subcategoria_id]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($produtos);
?>
