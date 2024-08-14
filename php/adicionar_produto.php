<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $subcategoria_id = $_POST['subcategoria_id'];

    $stmt = $conn->prepare("INSERT INTO produtos (nome, quantidade, preco, subcategoria_id) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$nome, $quantidade, $preco, $subcategoria_id])) {
        echo "Produto adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar produto!";
    }
}
?>
