<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produtoId = $_POST['produto_id'];

    // Verifique se o ID do produto é válido
    if (isset($produtoId) && is_numeric($produtoId)) {
        $stmt = $pdo->prepare("DELETE FROM produto WHERE id = ?");
        if ($stmt->execute([$produtoId])) {
            echo "sucesso";
        } else {
            echo "Erro ao remover o produto.";
        }
    } else {
        echo "ID do produto inválido.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
