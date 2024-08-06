<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    // Validate user session
    if (!isset($_SESSION['usuario_id'])) {
        die("Você deve estar logado para fazer um pedido.");
    }

    // Start a new order
    $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, 0)");
    $stmt->execute([$_SESSION['usuario_id']]);
    $pedido_id = $conn->lastInsertId();

    // Get product price
    $stmt = $conn->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($produto) {
        $preco = $produto['preco'];
        $total = $quantidade * $preco;

        // Insert order item
        $stmt = $conn->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco) VALUES (?, ?, ?, ?)");
        $stmt->execute([$pedido_id, $produto_id, $quantidade, $preco]);

        // Update total in the order
        $stmt = $conn->prepare("UPDATE pedidos SET total = total + ? WHERE id = ?");
        $stmt->execute([$total, $pedido_id]);

        echo "Pedido realizado com sucesso!";
    } else {
        echo "Produto não encontrado!";
    }
}
?>

<?php include 'views/header.php'; ?>

<h2>Fazer Pedido</h2>
<form method="post">
    <label for="produto_id">ID do Produto:</label>
    <input type="number" id="produto_id" name="produto_id" required>
    <label for="quantidade">Quantidade:</label>
    <input type="number" id="quantidade" name="quantidade" required>
    <button type="submit">Adicionar ao Pedido</button>
</form>

<?php include 'views/footer.php'; ?>
