<?php
require_once('class/Conexao.php');
require_once('includes/authorization.php');

?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $tituloDaPagina ?></title>


  <script src="js/jquery.min.js"></script><!---------biblioteca java script jQuery---->

  <script src="js/bootstrap.min.js"></script><!----biblioteca java script bootstrap (responsavel pela modal e outros efeitos)-->

  <link rel="stylesheet" href="cssTema/bootstrap.min.css"><!-----biblioteca CSS bootstrap (responsável pelo designe da página)--->


</head>

<body>

<div id="acolhimentoBioUBS" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
  </div>

  <!------------------menu inicial para testes---- modelo Bootstrap------------------>

  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">BioUBS</a>
      </div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.php">Home</a></li>

        <li class="dropdown">
          <a href="#" data-toggle="dropdown" class="dropdown-toggle">
            Cadastros <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">

            <!-------CADASTRO DE PACIENTES--->
            <li>
              <a href="cadastroDePacientes.php" class="dropdown">
                Pacientes
              </a>
            </li>



            <!-------CADASTRO DE Profissionais --->
            <li>
              <a href="cadastroDeProfissionais.php" class="dropdown">
                Profissionais
              </a>
            </li>
            <!------------------------------->

            <li>
              <!-------CADASTRO DE UNIDADES --->
              <a href="cadastroDeUnidades.php" class="dropdown">
                Unidade
              </a>
            </li>


          </ul>

        </li>

        <li><a href="#">Documentos</a></li>
        <li><a href="#">Relatórios</a></li>
        <li class="acolhimento-action">
          <a href="modal/fluxos/modalAcolhimento.php"
            data-toggle="modal"
            data-target="#acolhimentoBioUBS">
            <span class="glyphicon glyphicon-plus-sign"></span> Novo Acolhimento
          </a>
        </li>

      </ul>
    </div>
  </nav>
  <script src="js/custom.js"></script>


  <!------------------------------------------------------------------------------------>

  
  <!-----------------fim para modal----------------------------------->

  <!------------------fim do menu inicial para testes------------------>