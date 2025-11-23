<?php
/**
 * includes/header.php
 * * Carrega dependências, autorização.
 * * Abre o layout principal (com sidebar) e a tag <main>.
 */

//--------------------carregando dependências do Composer----------------
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload do Composer

//--------------------carregando configurações---------------------------
require_once __DIR__ . '/../config.php'; // Carrega as constantes de configuração

//--------------------carregando autorização-----------------------------
include_once( __DIR__ . '/authorization.php'); 

//--------------------variáveis da sessão--------------------------------
// Pega o nome do usuário da sessão para usar na sidebar
$user_nome_completo = $_SESSION['user_nome'] ?? 'Usuário';
$user_perfil = $_SESSION['user_perfil'] ?? 'Perfil';
// Pega o nome da UBS para exibir no topo
$ubsNome = htmlspecialchars($_SESSION['ubs_nome'] ?? 'UBS - Central');
//-----------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title><?= $tituloDaPagina ?></title>     
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--------biblioteca CSS bootstrap (responsável pelo designe da página)------->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    
    <!--------biblioteca CSS bootstrap icons (ícones)--------------------------->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!--------biblioteca CSS DataTables (tabelas dinâmicas)--------------------->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    
    <!--------CSS customizado do sistema---------------------------------------->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/css/custom.css">
    <!-- TomSelect CSS global -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">

    <!--------Google Fonts (Inter)---------------------------------------------->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-light">

    <!--------Layout principal: 'min-vh-100' garante que o layout ocupe a tela toda verticalmente------>
    <div class="d-flex min-vh-100">

        <?php 
        //--------------------incluindo a Barra Lateral (Sidebar)------------------
        include_once('sidebar.php'); 
        //--------------------------------------------------------------------------
        ?>
        
        <!--------Abre o Conteúdo Principal (Main)------>
        
    <main class="flex-grow-1 d-flex flex-column p-4 main-content">
        <?php
        //--------------------incluindo topbar com dados da UBS (nome + data/hora)------------------
        include_once __DIR__ . '/unit-topbar.php';
        //-------------------------------------------------------------------------------------------
        ?>
            <!--------A partir daqui, o conteúdo da página (ex: index.php) é carregado.----> 
            <!--------O <footer> e o </body> são fechados pelo 'footer.php'.---------------->

