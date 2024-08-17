<?php
// Inclua a conexão com o banco de dados
include 'db.php';

// Ajuste a consulta SQL para usar o nome correto da tabela e dos campos
$query = 'SELECT p.nome AS nome, p.preco AS preco, p.estoque AS estoque, c.nome AS categoria 
        FROM produto p 
        LEFT JOIN categorias c ON p.subcategoria_id = c.id';

// Execute a consulta e obtenha os resultados
try {
    $stmt = $conn->query($query);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro ao carregar produtos: ' . $e->getMessage();
}

// Feche a conexão
$conn = null;
?>

<!-- Aqui você pode exibir os dados dos produtos em uma tabela HTML -->
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Categoria</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                <td><?php echo htmlspecialchars($produto['preco']); ?></td>
                <td><?php echo htmlspecialchars($produto['estoque']); ?></td>
                <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
