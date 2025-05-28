<?php

// Inclusão dos arquivos necessários para conexão com o banco de dados e envio de e-mails
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/email.php';

/**
 * Função de login
 * Verifica se o username ou email existe e se a password está correta
 *
 * @param string $userinput Username ou email
 * @param string $password  Palavra-passe em texto simples
 * @return bool             Verdadeiro se o login for bem-sucedido, falso caso contrário
 */
function login($userinput, $password){
    global $pdo;

    // Consulta ao utilizador por username ou email
    $query = "SELECT * FROM Utilizador WHERE username = :username OR email = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $userinput]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o utilizador existe e se a palavra-passe está correta
    if ($user && password_verify($password, $user['password'])) {
        return true;
    }

    return false;
}

/**
 * Função de registo de novo utilizador
 * Cria um novo utilizador e envia um email de ativação
 *
 * @param string $email
 * @param string $username
 * @param string $password
 * @param string $telemovel
 * @param string $nif
 * @return bool  Verdadeiro se o registo for bem-sucedido
 */
function registo($email, $username, $password, $telemovel, $nif)
{
    global $con;

    $roleId = 2; // CLIENTE por padrão
    $token = bin2hex(random_bytes(16)); // Geração de token de ativação
    $password = password_hash($password, PASSWORD_DEFAULT); // Hash seguro da palavra-passe

    // Inserção do novo utilizador na base de dados
    $sql = $con->prepare('INSERT INTO Utilizador(email, username, password, telemovel, nif, token, RoleID) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $sql->bind_param('ssssssi', $email, $username, $password, $telemovel, $nif, $token, $roleId);
    $sql->execute();

    // Envia email de ativação se o registo for bem-sucedido
    if ($sql->affected_rows > 0) {
        send_email($email, 'Ativar a conta', $token);
        return true;
    }

    return false;
}

/**
 * Função para ativação de conta
 * Verifica se o email e o token coincidem
 *
 * @param string $email
 * @param string $token
 * @return bool  Verdadeiro se a ativação for válida
 */
function ativarConta($email, $token)
{
    global $con;

    // Verifica se existe um utilizador com o email e token fornecidos
    $sql = $con->prepare("SELECT * FROM Utilizador WHERE email = ? AND token = ?");
    $sql->bind_param('ss', $email, $token);
    $sql->execute();
    $result = $sql->get_result();

    // Retorna verdadeiro se houver pelo menos um resultado
    if ($result->num_rows > 0) {
        return true;
    }

    return false;
}

// Funções futuras ou placeholders
function logout() {}

function apagarConta() {}

?>
