<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../config.php';

include_once( __DIR__ . '/authorization.php');


?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $tituloDaPagina ?></title>
  

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">  <!-----biblioteca de ícones bootstrap--->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">

  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?> /css/custom.css"><!---arquivo de estilo personalizado-->

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body class="d-flex flex-column">

  <!-----------------Casca do Modal Novo Acolhimento----------------------------------->
  <div class="modal fade" id="acolhimentoBioUBS" tabindex="-1" aria-labelledby="acolhimentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body text-center">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Carregando...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-----------------Fim da Casca do Modal Novo Acolhimento------------------------------->


  <!------------------menu inicial para testes---- modelo Bootstrap------------------>
 <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= BASE_URL ; ?>\index.php">BioUBS</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
         
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Cadastros
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/cadastroDePacientes.php">Pacientes</a></li>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/cadastroDeProfissionais.php">Profissionais</a></li>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/cadastroDeUnidades.php">Unidade</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Documentos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Relatórios</a>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item acolhimento-action">
            <a class="nav-link" href="#" data-url="../modal/fluxos/modalAcolhimento.php"
              data-bs-toggle="modal"
              data-bs-target="#acolhimentoBioUBS">
              <i class="bi bi-plus-circle-fill me-1"></i> Novo Acolhimento
            </a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                
                <span class="d-none d-lg-inline ms-1">
                    <?= htmlspecialchars($_SESSION['username'] ?? 'Usuário') ?>
                </span>
            </a>
            
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                <li><a class="dropdown-item" href="#">Meu Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?= BASE_URL ?>/logout.php">
                        <i class="bi bi-box-arrow-right me-1"></i>Sair
                    </a>
                </li>
            </ul>
          </li>











        </ul>
      </div>
    </div>
  </nav>
  <main class="flex-grow-1 container py-4">



  <!------------------------------------------------------------------------------------>




  <!------------------fim do menu inicial para testes------------------>