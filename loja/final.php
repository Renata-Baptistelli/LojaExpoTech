<?php
// Inclui funcionalidades de autenticação e base de dados
require 'api/auth.php';
require 'api/db.php';
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION["utilizador_id"])) {
    header("Location: views/login.php");
    exit();
}

// Remove todos os itens do carrinho do utilizador após o pagamento
$sql = $con->prepare("DELETE FROM Carrinho WHERE userId = ?");
$sql->bind_param("i", $_SESSION["utilizador_id"]);
$sql->execute();
$sql->close();

// Verifica se o utilizador tem perfil de administrador
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "ADMIN";
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Estilo com Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        body, html {
            height: 100%;
        }
        .layout {
            display: flex;
            height: 100%;
        }
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
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-verde {
            background-color: #55A388;
            color: white;
        }
        .btn-verde:hover {
            background-color: #478b75;
            color: white;
        }
    </style>
</head>
<body class="bg-light">

<!-- Barra de navegação -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #55A388;">
  <div class="container-fluid justify-content-between">
    <div class="d-flex align-items-center" style="margin-left: 140px;">
      <a class="navbar-brand fw-bold me-3" href="index.php">Loja</a>

      <!-- Link para administração se for admin -->
      <?php if ($isAdmin): ?>
        <a href="views/admin_produtos.php" class="nav-link text-white d-flex align-items-center">
          <span style="filter: brightness(200%);">🛠️</span> Área de administração
        </a>
      <?php endif; ?>
    </div>

    <!-- Carrinho e logout -->
    <div class="d-flex" style="margin-right: 60px;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="carrinho.php"><span style="filter: brightness(200%)">🛒</span> Carrinho</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Estrutura de layout principal -->
<div class="layout">
    <!-- Sidebar com logotipo -->
    <div class="sidebar-logo">
        <img src="img/logo.png" alt="Logo ExpoTech">
    </div>

    <!-- Mensagem central de agradecimento -->
    <div class="main-content">
        <div class="card shadow p-4 text-center" style="max-width: 500px;">
            <h2 class="mb-3">✅ Compra realizada com sucesso!</h2>
            <p class="mb-4">Agradecemos a sua preferência. O seu pedido será processado em breve.</p>
            <a href="index.php" class="btn btn-verde">Voltar à loja</a>
        </div>
    </div>
</div>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


