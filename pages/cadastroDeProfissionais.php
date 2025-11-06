<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Profissionais";
//---------------------------
require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader
include_once(__DIR__ . '/../includes/header.php'); 

//-----------classes que serão usadas-----
// (A página principal precisa conhecer as classes)
use BioUBS\UbsCrudAll;
use BioUBS\Idade;
//----------------------------------------

/*autorização*/
if ($nivelAcesso == 1):
/* verificando o nível de acesso para Cadastrar, Editar e Excluir*/
  
  if (isset($_POST['salvar'])):

    //-----------------salvando o cadastro-----
    include(__DIR__ . '/../querys/inserts/insertProfissionais.php');
    //********************************************************

  elseif (isset($_POST['editar'])):

    //-----------------editando o cadastro-----
    include(__DIR__ . '/../querys/updates/updateProfissionais.php');
    //********************************************************

  elseif (isset($_POST['excluir'])):

    //-----------------excluindo o cadastro-----
    include(__DIR__ . '/../querys/deletes/deleteProfissionais.php');
    //********************************************************

  endif;

endif;//---fim para controle de acesso
?>

<h1 class="display-5 text-center text-muted mb-4">Cadastro de Profissionais</h1>
<hr class="mb-4">

<?php
if ($nivelAcesso == 1):
/* verificando o nível de acesso para o Botão Cadastrar*/
?>


<!---------------botão para acionar a modal------------------>
  <div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#insertProfissional"> <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
    </button>
  </div>
<!------------------------------------------------------------------>


<?php endif; ?>


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
///-----------------JANELA MODAL----------------------
//-------------incluindo janela modal cadastro-----------------------------
include(__DIR__ . '/../modal/cadastro/modalCadastroDeProfissional.php');
?>




<?php




//---------------------IMPORTANTE!!!!!!!!!!!!
//-------------tabela principal--------------
include(__DIR__ . '/../table/tableCadastroDeProfissional.php');
//-------------------------------------------




//-----------incluindo o rodapé 
include_once(__DIR__ . '/../includes/footer.php');
?>