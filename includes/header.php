<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../config.php';

// O authorization.php é o "portão" que verifica se o usuário está logado
// (seja por sessão ou cookie 'lembrar-me') antes de carregar qualquer HTML.
include_once( __DIR__ . '/authorization.php'); 

// Pega o nome do usuário da sessão (criada pelo auth.php ou authorization.php)
$username = $_SESSION['username'] ?? 'Usuário';

?>

<!DOCTYPE html>
<html lang="pt-BR"> <!-- Adicionado lang="pt-BR" -->

<head>
  <title><?= $tituloDaPagina ?? 'BIO-UBS' ?></title> <!-- Adicionado fallback de título -->

  <!-- Meta Tags Essenciais -->
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Sistema de Gerenciamento de Unidade Básica de Saúde">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> <!-- Versão atualizada -->

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css"> <!-- Versão atualizada -->

  <!-- Google Fonts (Inter) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- CSS Customizado -->
<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/css/custom.css">

  <style>
    /* Define a fonte principal do sistema */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa; /* Um cinza claro de fundo */
    }
  </style>

</head>

<!-- O body não precisa mais do d-flex flex-column -->
<body>

<!--
    Casca do Modal Novo Acolhimento
    Mantido aqui para que o botão "Novo Acolhimento" na sidebar funcione
  -->
 <div class="modal fade" id="acolhimentoBioUBS" tabindex="-1" aria-labelledby="acolhimentoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
   <div class="modal-content">
    <div class="modal-body text-center p-5">
     <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Carregando...</span>
     </div>
          <p class="mt-3 text-muted">Carregando dados do acolhimento...</p>
    </div>
   </div>
  </div>
 </div>
 <!-----------------Fim da Casca do Modal------------------------------->


  <!--
    INÍCIO DO NOVO LAYOUT DE DASHBOARD
    Usamos 'd-flex' para criar o layout de sidebar + conteúdo
  -->
  <div class="d-flex">

      <!-- 1. A BARRA LATERAL (Sidebar) -->
      <?php include_once(__DIR__ . '/sidebar.php'); ?>

      <!-- 
        2. O CONTEÚDO PRINCIPAL 
        'flex-grow-1' faz esta div ocupar todo o espaço restante
        'p-4' (padding) substitui o 'container' antigo
      -->
      <main class="flex-grow-1 p-4">
        
        <!-- O 'index.php' (ou outra página) será renderizado aqui dentro -->
        <!-- O 'footer.php' será responsável por fechar esta tag <main> -->
