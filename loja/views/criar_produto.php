<?php
// Conexão com a base de dados
$conn = new mysqli("localhost", "root", "", "24198_loja");

// Verifica erro de conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recebe os dados enviados pelo formulário
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$preco = floatval($_POST['preco']); // Converte o preço para número com ponto flutuante

// Verifica se foi enviada uma imagem e se não houve erro
$imagem = null;
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    // Lê o conteúdo da imagem para inserir no banco de dados como BLOB
    $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
}

// Prepara a query para inserção dos dados do novo produto
$stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssdb", $nome, $descricao, $preco, $imagem);
$stmt->send_long_data(3, $imagem); // Envia o conteúdo binário da imagem para o campo BLOB
$stmt->execute();

// Redireciona de volta para a área de administração após criar o produto
header("Location: admin_produtos.php");
exit;
