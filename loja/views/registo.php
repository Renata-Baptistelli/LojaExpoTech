<?php
// Inclui a base de dados e funções de autenticação
require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/../api/auth.php';

// Variáveis para feedback ao utilizador
$mensagem = '';
$erro = '';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $telemovel = $_POST['telemovel'] ?? '';
    $nif = $_POST['nif'] ?? '';

    // Chama a função de registo (definida em auth.php)
    if (registo($email, $username, $password, $telemovel, $nif)) {
        // Mensagem de sucesso
        $mensagem = 'Conta criada com sucesso! Verifique seu email.';
    } else {
        // Mensagem de erro
        $erro = 'Erro ao criar conta. Verifique os dados.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar simples -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #55A388;">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Loja</a>
        </div>
    </nav>

    <!-- Formulário de Registo -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 bg-white p-4 shadow rounded">
                <h3 class="text-center mb-4">Criar Conta</h3>

                <!-- Mensagens de feedback -->
                <?php if ($mensagem): ?>
                    <div class="alert alert-success text-center"><?= $mensagem ?></div>
                <?php elseif ($erro): ?>
                    <div class="alert alert-danger text-center"><?= $erro ?></div>
                <?php endif; ?>

                <!-- Formulário POST -->
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Palavra-passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telemóvel</label>
                        <input type="text" name="telemovel" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIF</label>
                        <input type="text" name="nif" class="form-control">
                    </div>
                    <button type="submit" class="btn w-100" style="background-color: #55A388; color: white;">Registar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
