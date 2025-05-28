<?php
session_start();

// Verifica se o utilizador está autenticado
require_once '../api/auth.php';
require_once '../api/db.php';

if (!isset($_SESSION['utilizador_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Recebe o POST com produto_id e quantidade
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['produto_id']) && isset($_POST['quantidade'])) {
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);
    $user_id = $_SESSION['utilizador_id'];

    // Verifica se já existe no carrinho
    $sql = $con->prepare("SELECT quantidade FROM Carrinho WHERE produtoId = ? AND userId = ?");
    $sql->bind_param("ii", $produto_id, $user_id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nova_quantidade = $row['quantidade'] + $quantidade;
        $update_sql = $con->prepare("UPDATE Carrinho SET quantidade = ? WHERE produtoId = ? AND userId = ?");
        $update_sql->bind_param("iii", $nova_quantidade, $produto_id, $user_id);
        $update_sql->execute();
    } else {
        $insert_sql = $con->prepare("INSERT INTO Carrinho (produtoId, userId, quantidade) VALUES (?, ?, ?)");
        $insert_sql->bind_param("iii", $produto_id, $user_id, $quantidade);
        $insert_sql->execute();
    }

    header("Location: ../index.php");
    exit();
}
?>
