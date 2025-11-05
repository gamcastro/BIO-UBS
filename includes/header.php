<?php
/**
 * includes/header.php
 * * Carrega dependências, autorização.
 * * Abre o layout principal (com sidebar) e a tag <main>.
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
include_once( __DIR__ . '/authorization.php'); // Controla o login e "Lembrar-me"

// Pega o nome do usuário da sessão para usar na sidebar
$user_nome_completo = $_SESSION['user_nome'] ?? 'Usuário';
$user_perfil = $_SESSION['user_perfil'] ?? 'Perfil';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title><?= $tituloDaPagina ?></title>
    
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS (Bootstrap, Ícones, DataTables, Custom) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/css/custom.css">

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- MUDANÇA: 'min-vh-100' garante que o layout ocupe a tela toda verticalmente -->
    <div class="d-flex min-vh-100">

        <?php 
        // 1. Inclui a Barra Lateral (Sidebar)
        // (Este arquivo 'sidebar.php' foi o que criamos na etapa anterior)
        include_once('sidebar.php'); 
        ?>
        
        <!-- 
          2. Abre o Conteúdo Principal (Main)
          MUDANÇA: 'd-flex flex-column' faz o <main> ser um container 
          flexível em coluna. Isso é ESSENCIAL para que o rodapé 
          (com 'mt-auto') grude no final desta área.
        -->
        <main class="flex-grow-1 d-flex flex-column p-4 main-content">
            <!-- 
              A partir daqui, o conteúdo da página (ex: index.php) é carregado.
              O <footer> e o </body> são fechados pelo 'footer.php'.
            -->

