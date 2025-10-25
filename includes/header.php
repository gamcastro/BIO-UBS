<?php
require_once('class/Conexao.php');
require_once('includes/authorization.php');

?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $tituloDaPagina ?></title>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </script><!----biblioteca java script bootstrap (responsavel pela modal e outros efeitos)-->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"><!-----biblioteca CSS bootstrap (responsável pelo designe da página)--->

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><!-----biblioteca de ícones bootstrap--->

  <link rel="stylesheet" type="text/css" href="css/style.css"><!---arquivo de estilo personalizado-->

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>

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
      <a class="navbar-brand" href="#">BioUBS</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Cadastros
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="cadastroDePacientes.php">Pacientes</a></li>
              <li><a class="dropdown-item" href="cadastroDeProfissionais.php">Profissionais</a></li>
              <li><a class="dropdown-item" href="cadastroDeUnidades.php">Unidade</a></li>
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
            <a class="nav-link" href="#" data-url="modal/fluxos/modalAcolhimento.php"
              data-bs-toggle="modal"
              data-bs-target="#acolhimentoBioUBS">
              <i class="bi bi-plus-circle-fill me-1"></i> Novo Acolhimento
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <script src="js/custom.js"></script>


  <!------------------------------------------------------------------------------------>




  <!------------------fim do menu inicial para testes------------------>