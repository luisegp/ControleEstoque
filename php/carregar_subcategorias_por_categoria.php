<?php
header('Content-Type: application/json');

// Inclua o arquivo de conexão com o banco de dados
include '../db.php';

// Verifique se a categoria_id foi passada
if (isset($_GET['categoria_id'])) {
    $categoriaId = $_GET['categoria_id'];
    
    // Prepare a consulta para buscar as subcategorias
    $stmt = $conn->prepare("SELECT id, nome FROM subcategorias WHERE categoria_id = :categoria_id");
    $stmt->bindParam(':categoria_id', $categoriaId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch os resultados
    $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorne os resultados como JSON
    echo json_encode($subcategorias);
} else {
    // Retorne um array vazio se nenhum ID for fornecido
    echo json_encode([]);
}
?>
