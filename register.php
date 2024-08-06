<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $erro = "Email já está registrado!";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, permissao_id) VALUES (?, ?, ?, 2)");
        if ($stmt->execute([$nome, $email, $senha])) {
            $_SESSION['usuario_id'] = $conn->lastInsertId();
            $_SESSION['nome'] = $nome;
            $_SESSION['permissao_id'] = 2;
            header("Location: index.php");
            exit();
        } else {
            $erro = "Erro ao registrar usuário!";
        }
    }
}
?>

<?php include 'views/header.php'; ?>

<h2>Registrar</h2>
<?php if (isset($erro)): ?>
    <p><?php echo $erro; ?></p>
<?php endif; ?>
<form method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>
    <button type="submit">Registrar</button>
</form>

<?php include 'views/footer.php'; ?>
