<?php
include 'db.php';  // Caminho correto se db.php está na mesma pasta

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

    // Aguarda 2 segundos e redireciona
    sleep(2);
    header("Location: ../estoque.php");
    exit(); // Assegura que o script termina após o redirecionamento
}
?>
