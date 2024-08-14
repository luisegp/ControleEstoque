<?php
include 'db.php';

$stmt = $conn->prepare("SELECT c.*, p.nome FROM comandas c JOIN produtos p ON c.produto_id = p.id ORDER BY c.data DESC");
$stmt->execute();
$comandas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($comandas);
?>
