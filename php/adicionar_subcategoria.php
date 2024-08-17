<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $categoria_id = $_POST['categoria_id'];

    $stmt = $conn->prepare("INSERT INTO subcategorias (nome, categoria_id) VALUES (?, ?)");
    if ($stmt->execute([$nome, $categoria_id])) {
        echo "sub-categoria adicionada com sucesso!";
    } else {
        echo "Erro ao adicionar categoria: " . $e->getMessage();
    }
    sleep(2);
    header("Location: ../estoque.php");
    exit();
}
