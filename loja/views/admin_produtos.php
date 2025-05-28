<?php
session_start();

if (!isset($_SESSION['utilizador_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "24198_loja");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Administração de Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap e Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- CSS personalizado -->
    <style>
    body, html { height: 100%; margin: 0; padding: 0; }

    .layout {
        display: flex;
        min-height: 100vh;
    }

    .sidebar-logo {
        width: 140px;
        background-color: #fff;
        padding: 10px;
        border-right: 1px solid #ddd;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .sidebar-logo img {
        max-width: 100%;
        height: 100px;
        object-fit: contain;
    }

    .main-content {
        flex: 1;
        padding: 20px;
    }

    .navbar-custom {
        background-color: #55A388;
    }

    .navbar-custom .nav-link,
    .navbar-custom .navbar-brand {
        color: #fff;
    }

    .navbar-custom .nav-link:hover,
    .navbar-custom .navbar-brand:hover {
        color: #e6f2ef;
    }

    .navbar-custom {
    background-color: #55A388;
    padding-left: 160px; /* Compensa a sidebar de 140px + espaçamento */
}
</style>
</head>
<body class="bg-light">

<!-- Barra Superior -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand me-3" href="../index.php">Loja</a>

        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link text-white" href="admin_produtos.php">
                    <span style="filter: brightness(200%);">🛠️</span> Área de administração
                </a>
            </li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="../carrinho.php">
                    <span style="filter: brightness(200%);">🛒</span> Carrinho
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../views/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Layout com sidebar e conteúdo -->
<div class="layout">
    <!-- Sidebar com logotipo -->
    <div class="sidebar-logo">
        <img src="../img/logo.png" alt="Logo ExpoTech">
    </div>

    <!-- Conteúdo principal -->
    <div class="main-content">
        <div class="container my-3 bg-white p-4 shadow rounded">
            <h3 class="mb-4">Administração de Produtos</h3>

            <!-- Botão para abrir modal de criação de produto -->
            <button class="btn btn-sm text-white mb-3" style="background-color: #55A388; border: none;" data-bs-toggle="modal" data-bs-target="#modalCriar">
                <i class="bi bi-plus-circle me-1"></i> Adicionar Produto
            </button>

            <!-- Tabela de produtos -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Imagem</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM produtos";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['descricao']) ?></td>
                            <td>€<?= number_format($row['preco'], 2, ',', '.') ?></td>
                            <td>
                                <?php if ($row['imagem']): ?>
                                    <img src="mostrar_imagem.php?id=<?= $row['id'] ?>" alt="Imagem" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="editar_produto.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm me-1 px-2 py-1" title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="eliminar_produto.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm px-2 py-1" title="Eliminar" onclick="return confirm('Tem certeza que deseja eliminar este produto?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div> <!-- fecha .container -->
    </div> <!-- fecha .main-content -->
</div> <!-- fecha .layout -->

<!-- Modal para adicionar novo produto -->
<div class="modal fade" id="modalCriar" tabindex="-1" aria-labelledby="modalCriarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="criar_produto.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCriarLabel">Novo Produto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" name="preco" id="preco" class="form-control" step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="imagem" class="form-label">Imagem</label>
            <input type="file" name="imagem" id="imagem" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
