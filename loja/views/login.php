<?php
// Inicia a sessão para armazenar dados do utilizador autenticado
session_start();

// Inclui conexão com a base de dados e funcionalidades de email (caso precise futuramente)
require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/../api/email.php';

// Inicializa a variável de erro (exibida caso o login falhe)
$erro = '';

// Verifica se o formulário foi submetido via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userinput = $_POST['user'] ?? '';    // Pode ser email ou username
    $password = $_POST['pass'] ?? '';     // Palavra-passe digitada

    // Consulta o utilizador pelo username ou email
    $query = "SELECT * FROM Utilizador WHERE username = :username OR email = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $userinput]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o utilizador existe e a palavra-passe está correta
    if ($user && password_verify($password, $user['password'])) {
        // Guarda dados essenciais na sessão
        $_SESSION['utilizador_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['RoleID'] == 1 ? 'ADMIN' : 'CLIENT';

        // Redireciona para a página principal
        header("Location: ../index.php");
        exit;
    } else {
        // Mensagem de erro se o login falhar
        $erro = "Credenciais inválidas.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Estilos Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- NAVBAR com link para página principal -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #55A388;">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Loja</a>
        </div>
    </nav>

    <!-- Formulário de Login -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4 bg-white p-4 shadow rounded">
                <h3 class="text-center mb-4">Entrar</h3>

                <!-- Exibe erro se houver -->
                <?php if ($erro): ?>
                    <div class="alert alert-danger text-center"><?= $erro ?></div>
                <?php endif; ?>

                <!-- Formulário de autenticação -->
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email ou Username</label>
                        <input type="text" name="user" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Palavra-passe</label>
                        <input type="password" name="pass" class="form-control" required>
                    </div>
                    <button type="submit" class="btn w-100" style="background-color: #55A388; color: white;">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
