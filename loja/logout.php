<?php
// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Remove todas as variáveis de sessão
session_unset();

// Destroi a sessão por completo (logout efetivo)
session_destroy();

// Redireciona o utilizador para a página de login
header("Location: views/login.php");
exit();
?>