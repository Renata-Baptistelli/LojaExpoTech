<?php
// Inicia a sessão para acessar os dados do utilizador logado
session_start();

// Inclui a conexão com a base de dados
require 'api/db.php';

// Verifica se o utilizador está autenticado e se foi fornecido um ID do item
if (!isset($_SESSION["utilizador_id"]) || !isset($_GET["id"])) {
    // Se não estiver logado ou sem ID, redireciona para o login
    header("Location: views/login.php");
    exit();
}

// Obtém o ID do utilizador logado e o ID do item a ser removido
$userId = $_SESSION["utilizador_id"];
$itemId = intval($_GET["id"]); // Converte para inteiro por segurança

// Prepara a query para remover o item do carrinho, garantindo que pertence ao utilizador
$stmt = $con->prepare("DELETE FROM Carrinho WHERE id = ? AND userId = ?");
$stmt->bind_param("ii", $itemId, $userId);
$stmt->execute();

// Após remover, redireciona de volta ao carrinho
header("Location: carrinho.php");
exit();
?>
