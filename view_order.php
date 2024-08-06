<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Você deve estar logado para ver os pedidos.");
}

// Fetch user orders
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE usuario_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'views/header.php'; ?>

<h2>Meus Pedidos</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Total</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                <td><?php echo htmlspecialchars($pedido['total']); ?></td>
                <td><?php echo htmlspecialchars($pedido['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/footer.php'; ?>
