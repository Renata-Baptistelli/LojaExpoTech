<?php
// Inicia a sessão para acessar os dados do utilizador
session_start();

// Inclui a conexão com o banco de dados
require 'api/db.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION["utilizador_id"])) {
    header("Location: views/login.php"); // Redireciona se não estiver logado
    exit();
}

// Captura o ID do utilizador atual e verifica se é administrador
$userId = $_SESSION["utilizador_id"];
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "ADMIN";

// Prepara a consulta para obter os itens do carrinho
$sql = "SELECT c.id, p.nome, p.preco, p.imagem, c.quantidade 
        FROM Carrinho c 
        JOIN produtos p ON c.produtoId = p.id 
        WHERE c.userId = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Inicializa lista de itens e total da compra
$itens = [];
$total = 0;

// Processa os resultados e calcula subtotal e total
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['quantidade'] * $row['preco'];
    $total += $subtotal;
    $itens[] = array_merge($row, ["subtotal" => $subtotal]);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>ExpoTech - Carrinho</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap para estilos e responsividade -->
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
            max-width: 900px;
            margin: 0 auto;
        }
        .btn-verde {
            background-color: #55A388;
            color: white;
        }
        .btn-verde:hover {
            background-color: #478b75;
            color: white;
        }
        .badge-total {
            background-color: #55A388;
            color: white;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-light">

<!-- Barra de navegação -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #55A388;">
  <div class="container-fluid justify-content-between">
    <div class="d-flex align-items-center" style="margin-left: 140px;">
      <a class="navbar-brand fw-bold me-3" href="index.php">Loja</a>
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

<!-- Layout com sidebar e conteúdo -->
<div class="layout">
    <!-- Sidebar com logotipo -->
    <div class="sidebar-logo">
        <img src="img/logo.png" alt="Logo ExpoTech">
    </div>

    <!-- Conteúdo principal -->
    <div class="main-content">
        <h3 class="mb-4">🛒 Meu Carrinho</h3>

        <!-- Barra de busca -->
        <form class="mb-4" method="get" action="buscar.php">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Buscar produto...">
                <button class="btn btn-verde" type="submit">Buscar</button>
            </div>
        </form>

        <!-- Lista de itens do carrinho -->
        <form method="post" action="atualizar_carrinho.php">
            <?php if (count($itens) > 0): ?>
                <?php foreach ($itens as $item): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center flex-wrap" style="gap: 1rem;">
                                <?php if ($item['imagem']): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($item['imagem']) ?>" width="80" height="80" style="object-fit: cover; border-radius: 6px;">
                                <?php endif; ?>
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($item['nome']) ?></h5>
                                    <small class="text-muted">Preço unitário: <strong>€<?= number_format($item['preco'], 2, ',', '.') ?></strong></small><br>
                                    <div class="d-flex align-items-center mt-2" style="gap: 0.5rem;">
                                        <input type="number" name="quantidade[<?= $item['id'] ?>]" value="<?= $item['quantidade'] ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                                        <button type="submit" class="btn btn-sm btn-verde">Atualizar</button>
                                        <a href="remover_item.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remover este item do carrinho?')">Remover</a>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-secondary fs-6">Subtotal: €<?= number_format($item['subtotal'], 2, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Total da compra e ação -->
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                    <div class="mb-3 mb-md-0">
                        <a href="index.php" class="btn btn-secondary">Continuar comprando</a>
                    </div>
                    <h5>Total do Pedido: <span class="badge badge-total fs-6">€<?= number_format($total, 2, ',', '.') ?></span></h5>
                </div>
            <?php else: ?>
                <div class="alert alert-info">O seu carrinho está vazio. <a href="index.php" class="alert-link">Voltar à loja</a></div>
            <?php endif; ?>
        </form>

        <!-- Botão de pagamento PayPal -->
        <?php if ($total > 0): ?>
        <div class="d-flex justify-content-center mt-4">
            <div id="paypal-button-container" class="w-50"></div>
        </div>

        <!-- SDK do PayPal (client-id removido para subir ao GitHub) -->
        <script src="https://www.paypal.com/sdk/js?client-id=&currency=EUR"></script>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?= number_format($total, 2, '.', '') ?>'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        window.location.href = "final.php";
                    });
                },
                onError: function(err) {
                    console.error('Erro no pagamento:', err);
                    alert('Ocorreu um erro durante o pagamento. Tente novamente.');
                }
            }).render('#paypal-button-container');
        </script>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
