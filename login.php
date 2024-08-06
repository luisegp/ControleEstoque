<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Check if email and password are correct
    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['permissao_id'] = 2; // Default permission
        header("Location: index.php");
        exit();
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>

<?php include 'views/header.php'; ?>

<h2>Login</h2>
<?php if (isset($erro)): ?>
    <p><?php echo $erro; ?></p>
<?php endif; ?>
<form method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>
    <button type="submit">Login</button>
</form>

<?php include 'views/footer.php'; ?>
