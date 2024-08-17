<?php
include 'db.php'; // Certifique-se de que o caminho está correto

if ($conn) {
    try {
        $query = "SELECT id, nome FROM categorias"; // Ajuste o nome da tabela se necessário
        $stmt = $conn->query($query);
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categorias as $categoria) {
            echo "<option value='{$categoria['id']}'>{$categoria['nome']}</option>";
        }
    } catch (PDOException $e) {
        echo "Erro ao carregar categorias: " . $e->getMessage();
    }
} else {
    echo "Erro na conexão com o banco de dados.";
}
?>
