<?php 

session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/auth_helper.php';
use BioUBS\Conexao ;


$db = Conexao::getConn();

// Se não houver sessão, tenta autenticar via remember-me
if (empty($_SESSION['user_id'])) {
    
    // 1. Chama a função (que agora retorna o array completo)
    $user = try_remember_login($db);
    
    if ($user) {
        // 2. SUCESSO! Cria a sessão COMPLETA
        session_regenerate_id(true);
        
        // *** CORREÇÃO PRINCIPAL ***
        // Usamos os nomes das colunas que a função retorna
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['username'] = $user['MATRICULA']; // (Matrícula)
        $_SESSION['user_nome'] = $user['NOME_COMPLETO']; // (Para o header)
        $_SESSION['user_perfil'] = $user['PERFIL']; // (Para permissões)
        
    } else {
        // 3. FALHA: O cookie é inválido ou não existe, manda para o login.
        header("Location: login.php");
        exit;
    }
}

$tituloDaPagina = "BIO-UBS" ;
include_once('includes/header.php');
?>


<?php
// Define o título que será usado no <title> dentro do header.php

// Inclui o header.php (que agora abre a tag <main class="flex-grow-1 container py-4">)

?>

<div class="text-center">
    <h1 class="display-4 mb-3">Bem-vindo ao BIO-UBS</h1>
    <p class="lead text-muted">Selecione uma opção no menu acima para começar.</p>
</div>

<?php
// Inclui o footer.php (que agora fecha a tag </main> e adiciona o <footer>)
include_once('includes/footer.php');
?>