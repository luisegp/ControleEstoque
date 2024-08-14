<?php
include '../db.php';  // Ajuste o caminho conforme necessário

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];

    if ($conn) {
        try {
            $stmt = $conn->prepare("INSERT INTO categorias (nome) VALUES (:nome)");
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->execute();
            echo "Categoria adicionada com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao adicionar categoria: " . $e->getMessage();
        }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
}
?>
