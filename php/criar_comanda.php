<?php
include 'db.php';

session_start();

if (!isset($_SESSION['senha_diaria'])) {
    $_SESSION['senha_diaria'] = 1;
    $_SESSION['data_ultima_senha'] = date('Y-m-d');
} elseif ($_SESSION['data_ultima_senha'] != date('Y-m-d')) {
    $_SESSION['senha_diaria'] = 1;
    $_SESSION['data_ultima_senha'] = date('Y-m-d');
} else {
    $_SESSION['senha_diaria']++;
}

$senha_comanda = $_SESSION['senha_diaria'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    $stmt = $conn->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        $preco = $produto['preco'];
        $total = $preco * $quantidade;

        $stmt = $conn->prepare("INSERT INTO comandas (produto_id, quantidade, preco_total, senha_comanda) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$produto_id, $quantidade, $total, $senha_comanda])) {
            echo "Comanda criada com sucesso!";
        } else {
            echo "Erro ao criar comanda!";
        }
    } else {
        echo "Produto não encontrado!";
    }
}
?>
