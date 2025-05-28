<?php
// Inicia sessão para verificar permissões
session_start();

// Inclui a conexão com a base de dados
require_once '../api/db.php';

// Verifica se o utilizador está autenticado como ADMIN
if (!isset($_SESSION['utilizador_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login.php"); // Redireciona para login se não for admin
    exit();
}

// Obtém o ID do produto via URL
$id = $_GET['id'] ?? null;
if (!$id) {
    // Se o ID não for fornecido, redireciona para a administração
    header("Location: admin_produtos.php");
    exit();
}

// Busca o produto no banco de dados
$sql = $con->prepare("SELECT * FROM produtos WHERE id = ?");
$sql->bind_param('i', $id); // 'i' = integer
$sql->execute();
$result = $sql->get_result();
$produto = $result->fetch_assoc();

// Se não encontrar o produto, exibe mensagem e encerra
if (!$produto) {
    echo "Produto não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR PADRONIZADA -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #55A388;">
    <div class="container">
        <a class="navbar-brand" href="../index.php">Loja</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
            <!-- Botão de acesso rápido à administração -->
            <a href="admin_produtos.php" class="btn btn-sm text-white ms-2" style="background-color: rgba(0,0,0,0.15); border: none;">
                🛠️ Administração
            </a>
        <?php endif; ?>

        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item me-3">
                    <a class="nav-link text-white" href="../carrinho.php">🛒 Carrinho</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- FORMULÁRIO DE EDIÇÃO DE PRODUTO -->
<div class="container d-flex justify-content-center my-5">
    <div class="card shadow-sm p-4 w-100" style="max-width: 600px;">
        <h4 class="mb-4">Editar Produto</h4>

        <!-- Formulário envia para guardar_edicao.php -->
        <form method="post" action="guardar_edicao.php" enctype="multipart/form-data">
            <!-- ID oculto para identificar o produto a ser atualizado -->
            <input type="hidden" name="id" value="<?= $produto['id'] ?>">

            <!-- Nome -->
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>

            <!-- Preço -->
            <div class="mb-3">
                <label class="form-label">Preço (€)</label>
                <input type="text" name="preco" class="form-control" value="<?= number_format($produto['preco'], 2, ',', '.') ?>" required>
            </div>

            <!-- Nova imagem (opcional) -->
            <div class="mb-3">
                <label class="form-label">Nova Imagem (opcional)</label>
                <input type="file" name="imagem" class="form-control">
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-between">
                <a href="admin_produtos.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn text-white" style="background-color: #55A388;">Guardar Alterações</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
