<?php
/**
 * logout.php
 * Destroi a sessão e remove (e invalida) o cookie remember-me no banco.
 */
session_start();

// 1. Carrega o autoload para ter acesso às classes (Conexao) e funções (logout_user)
require_once __DIR__ . '/vendor/autoload.php';

// 2. Carrega a configuração para ter acesso à BASE_URL
require_once __DIR__ . '/config.php'; 

use BioUBS\Conexao;

// 3. Executa a lógica de logout (limpar cookie, token, etc.)
try {
    $db = Conexao::getConn();
    // Verificamos se a função existe antes de chamar
    if (function_exists('logout_user')) {
        logout_user($db); // Remove o token do banco e cookie
    }
} catch (Exception $e) {
    // Se a conexão falhar, não impede o logout, mas é bom registrar o erro
    // error_log('Falha ao limpar token de remember-me: ' . $e->getMessage());
}

// 4. Destrói a sessão PHP
$_SESSION = [];
session_destroy();

// 5. Redireciona para a URL de login (o jeito correto, usando a URL base)
header('Location: ' . BASE_URL . '/login.php');
exit;
?>