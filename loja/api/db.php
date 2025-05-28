<?php
// Ativa o modo de relatório de erros do MySQLi para lançar exceções em vez de warnings
mysqli_report(MYSQLI_REPORT_ERROR);

// Conexão com a base de dados usando MySQLi
$con = new mysqli("localhost", "root", "", "24198_Loja");

// Verifica se houve erro na conexão com MySQLi
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Conexão alternativa com a base de dados usando PDO (mais segura e flexível)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=24198_Loja", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Lança exceções em caso de erro
} catch (PDOException $e) {
    // Em caso de falha na conexão, exibe a mensagem de erro
    die("Erro na conexão PDO: " . $e->getMessage());
}
?>

