<?php
// Autenticação e base de dados
require 'api/auth.php';
require 'api/db.php';
session_start();

// Redireciona para login se o utilizador não estiver autenticado
if (!isset($_SESSION["utilizador_id"])) {
    header("Location: views/login.php");
    exit();
}

// Captura e trata o termo de pesquisa (evita SQL Injection com real_escape_string)
$search = isset($_GET['search']) ? $con->real_escape_string($_GET['search']) : '';

// Consulta os produtos, com ou sem filtro de busca
$sql = "SELECT id, nome, descricao, preco, imagem FROM produtos";
if ($search !== '') {
    $sql .= " WHERE nome LIKE '%$search%' OR descricao LIKE '%$search%'";
}
$result = $con->query($sql);

// Prepara os produtos para exibir
$produtos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}

// Verifica se o utilizador é administrador
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "ADMIN";
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>ExpoTech Loja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        body, html { height: 100%; }
        .layout { display: flex; height: 100%; }
        .sidebar-logo {
            width: 140px;
            background-color: #fff;
            padding: 0 10px 10px 10px;
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
        .btn-verde {
            background-color: #55A388;
            color: white;
        }
        .btn-verde:hover {
            background-color: #478b75;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar superior -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
  <div class="container-fluid justify-content-between">
    <div class="d-flex align-items-center" style="margin-left: 140px;">
      <a class="navbar-brand fw-bold me-3" href="#">Loja</a>
      <!-- Se for administrador, mostra link de administração -->
      <?php if ($isAdmin): ?>
        <a href="views/admin_produtos.php" class="nav-link text-white d-flex align-items-center">
          <span style="filter: brightness(200%);">🛠️</span> Área de administração
        </a>
      <?php endif; ?>
    </div>

    <div class="d-flex" style="margin-right: 60px;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="carrinho.php">
            <span style="filter: brightness(200%);">🛒</span> Carrinho
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Layout com logo lateral e conteúdo -->
<div class="layout">
    <!-- Logo lateral esquerda -->
    <div class="sidebar-logo">
        <img src="img/logo.png" alt="Logo ExpoTech">
    </div>

    <!-- Conteúdo principal -->
    <div class="main-content">
        <!-- Formulário de busca -->
        <form class="row mb-4" method="get" action="">
            <div class="col-md-10">
                <input type="text" class="form-control" name="search" placeholder="Buscar produto..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-verde w-100">Buscar</button>
            </div>
        </form>

        <!-- Lista de produtos -->
        <div class="row g-4">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm" style="font-size: 0.9rem;">
                        <?php
                        // Define imagem do produto ou placeholder
                        $src = !empty($produto['imagem'])
                            ? 'data:image/jpeg;base64,' . base64_encode($produto['imagem'])
                            : 'https://via.placeholder.com/180x140?text=Sem+Imagem';
                        ?>
                        <img src="<?= $src ?>" class="card-img-top p-2" style="height: 140px; object-fit: contain;" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <div class="card-body d-flex flex-column p-2">
                            <h6 class="card-title mb-1 text-truncate"><?= htmlspecialchars($produto['nome']) ?></h6>
                            <p class="card-text small mb-2" style="max-height: 2.6em; overflow: hidden;">
                                <?= htmlspecialchars($produto['descricao']) ?>
                            </p>
                            <strong class="text-success mb-2">€<?= number_format($produto['preco'], 2, ',', '.') ?></strong>

                            <!-- Formulário para adicionar ao carrinho -->
                            <form method="post" action="api/add_to_cart.php" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                                <input type="number" name="quantidade" value="1" min="1" class="form-control form-control-sm" style="width: 50px;">
                                <button type="submit" class="btn btn-sm btn-verde">+</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
