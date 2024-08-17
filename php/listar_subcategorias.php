<?php
include 'db.php';
    $categoria_id = $_GET['categoria_id'];
    $stmt = $conn->prepare("SELECT * FROM subcategorias WHERE categoria_id = ?");
    $stmt->execute([$categoria_id]);
    $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subcategorias);
?>
