<?php 
// Inicia a sessão para acessar os dados do utilizador
session_start();

// Conexão com a base de dados
require 'api/db.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION["utilizador_id"])) {
    header("Location: views/login.php"); // Redireciona para o login se não estiver logado
    exit();
}

// Obtém o ID do utilizador logado
$userId = $_SESSION["utilizador_id"];

// Percorre os itens enviados via POST (formulário do carrinho)
foreach ($_POST['quantidade'] as $itemId => $quantidade) {
    // Garante que a quantidade mínima seja 1
    $quantidade = max(1, intval($quantidade));

    // Atualiza a quantidade do item no carrinho apenas para o utilizador logado
    $stmt = $con->prepare("UPDATE Carrinho SET quantidade = ? WHERE id = ? AND userId = ?");
    $stmt->bind_param("iii", $quantidade, $itemId, $userId);
    $stmt->execute();
}

// Redireciona de volta ao carrinho após a atualização
header("Location: carrinho.php");
exit();
