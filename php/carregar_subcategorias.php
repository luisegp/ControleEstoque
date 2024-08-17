<?php
include 'db.php'; // Ajuste o caminho conforme necessário

// Se houver um parâmetro de categoria, use-o para filtrar as subcategorias
$categoria_id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : 0;

$query = 'SELECT id, nome FROM subcategorias WHERE categoria_id = :categoria_id ORDER BY nome';

try {
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt->execute();
    $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro ao carregar subcategorias: ' . $e->getMessage();
}

foreach ($subcategorias as $subcategoria) {
    echo '<option value="' . htmlspecialchars($subcategoria['id']) . '">' . htmlspecialchars($subcategoria['nome']) . '</option>';
}

$conn = null;
?>
