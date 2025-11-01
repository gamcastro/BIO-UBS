<?php 

session_start();
require_once __DIR__ . '/vendor/autoload.php';

$db = db_conn();

// Se não houver sessão, tenta autenticar via remember-me
if (empty($_SESSION['user_id'])) {
    $user = try_remember_login($db);
    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
    } else {
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