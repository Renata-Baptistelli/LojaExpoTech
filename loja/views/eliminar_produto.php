<?php
// Inicia a sessão para garantir controle de acesso (se necessário futuramente)
session_start();

// Conexão com a base de dados
require_once '../api/db.php';

// Verifica se foi passado um ID pela URL
if (!isset($_GET['id'])) {
    // Se não houver ID, redireciona de volta à administração
    header("Location: admin_produtos.php");
    exit();
}

// Converte o ID para inteiro para segurança
$id = intval($_GET['id']);

// ⚠️ Primeiro remove o produto do carrinho, para evitar violação de chave estrangeira
$con->query("DELETE FROM Carrinho WHERE produtoId = $id");

// Depois remove o produto da tabela principal
$con->query("DELETE FROM produtos WHERE id = $id");

// Redireciona de volta à página de administração
header("Location: admin_produtos.php");
exit();
?>

