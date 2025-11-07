<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Pacientes";
//---------------------------
require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader
include_once(__DIR__ . '/../includes/header.php'); 

//-----------classes que serão usadas-----
use BioUBS\UbsCrudAll;
use BioUBS\Idade;
//----------------------------------------



/*autorização*/
if ($nivelAcesso == 1):
  /* verificando o nível de acesso para Cadastrar, Editar e Excluir*/

  if (isset($_POST['salvar'])):

    //-----------------salvando o cadastro-----
    include(__DIR__ . '/../querys/inserts/insertPaciente.php');
    //********************************************************

  elseif (isset($_POST['editar'])):

    //-----------------editando o cadastro-----
    include(__DIR__ . '/../querys/updates/updatePaciente.php');
    //********************************************************

  elseif (isset($_POST['excluir'])):

    //-----------------excluindo o cadastro-----
    include(__DIR__ . '/../querys/deletes/deletePaciente.php');
    //********************************************************

  endif;

endif;//---fim para controle de acesso


//-------------------FUNCOES PHP --------------------------

//---------------------------------------------------------


//--------------------classe PHP---------------------------
    /*classe para calculo de idade
//require_once('class/Idade.php');
//$idade = new Idade();*/
//---------------------------------------------------------
?> 




<h1 class="display-5 text-center text-muted mb-4">Cadastro de Pacientes</h1>
<hr class="mb-4">

<!-- Funções de máscara/utilidades (Title Case para nome e máscara de CPF) -->
<script type="text/javascript" src="<?= BASE_URL ?>/js/mask/funcaoLetrasMaiusculas.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/js/mask/funcaoMascaraGeralNumeros.js"></script>

<?php
if ($nivelAcesso == 1):
/* verificando o nível de acesso para o Botão Cadastrar*/
?>


<!---------------botão para acionar a modal------------------>
  <div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#insertPaciente"> 
      <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
    </button>
  </div>
<!------------------------------------------------------------------>


<?php endif; ?>






<?php 
///-----------------JANELA MODAL----------------------
//-------------incluindo janela modal cadastro-----------------------------
include(__DIR__ . '/../modal/cadastro/modalCadastroDePacientes.php');
?>

<!-----------casca da modal de edicao------------->
<div class="modal fade" id="updateBioUBS" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <!-- contener da janela-->
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-----------casca da modal de exclusão------------->
<div class="modal fade" id="deleteBioUBS" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <!-- contener da janela-->
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>
    </div>
  </div>
</div>
<!-----------------fim para modal----------------------------------->



<?php

// Inclui a TABELA principal




//---------------------IMPORTANTE!!!!!!!!!!!!
//-------------tabela principal--------------
include(__DIR__ . '/../table/tableCadastroDePacientes.php');
//-------------------------------------------




//-----------incluindo o rodapé 
include_once(__DIR__ . '/../includes/footer.php');

?>


