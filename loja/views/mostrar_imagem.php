<?php
// Conexão com a base de dados
$conn = new mysqli("localhost", "root", "", "24198_loja");

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Obtém o ID do produto da URL e converte para inteiro
$id = intval($_GET['id']);

// Busca a imagem correspondente ao produto
$sql = "SELECT imagem FROM produtos WHERE id = $id";
$result = $conn->query($sql);

// Se encontrar o produto com imagem
if ($row = $result->fetch_assoc()) {
    // Define o tipo de conteúdo como imagem JPEG (padrão esperado)
    header("Content-Type: image/jpeg");

    // Exibe a imagem diretamente (conteúdo binário)
    echo $row['imagem'];
} else {
    // Se não encontrar, retorna erro 404
    http_response_code(404);
    echo "Imagem não encontrada.";
}
