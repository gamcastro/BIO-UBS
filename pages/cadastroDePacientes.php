<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Pacientes";
//---------------------------
require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader
include_once(__DIR__ . '/../includes/header.php'); // Carrega o Header (Menu, CSS, custom.js)

//-----------classes que serão usadas-----
// (A página principal precisa conhecer as classes)
use BioUBS\UbsCrudAll;
use BioUBS\Idade;
//----------------------------------------



/* * Lógica PHP de salvar/editar/excluir
 * Esta lógica é executada QUANDO um formulário (novo, edição ou exclusão)
 * é submetido para esta mesma página.
 */
// A variável $nivelAcesso é definida dentro do 'authorization.php',
// que é incluído pelo 'header.php'
if ($nivelAcesso == 1) {
  if (isset($_POST['salvar'])) {
    // Script para inserir novo paciente
    include(__DIR__ . '/../querys/inserts/insertPaciente.php');
  } elseif (isset($_POST['editar'])) {
    // Script para atualizar paciente
    include(__DIR__ . '/../querys/updates/updatePaciente.php');
  } elseif (isset($_POST['excluir'])) {
    // Script para deletar paciente
    include(__DIR__ . '/../querys/deletes/deletePaciente.php');
  }
}


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

<?php if ($nivelAcesso == 1): ?>
  <div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#insertPaciente"> 
      <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
    </button>
  </div>
<?php endif; ?>






<div class="modal fade" id="updateBioUBS" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
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

<div class="modal fade" id="deleteBioUBS" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>
    </div>
  </div>
</div>



<?php
// Inclui a TABELA principal
include(__DIR__ . '/../table/tableCadastroDePacientes.php');
?>

<?php
// Inclui o CONTEÚDO do modal de "Novo Cadastro" (Estático)
// O ID deste modal deve ser 'insertPaciente'
include(__DIR__ . '/../modal/cadastro/modalCadastroDePacientes.php');
?>

<?php
// DEBUG: verificar se o fluxo chega até aqui e se o arquivo footer existe
echo "<!-- DEBUG: before footer include -->\n";
echo "<!-- DEBUG: footer exists? " . (file_exists(__DIR__ . '/../includes/footer.php') ? 'yes' : 'no') . " -->\n";

// Inclui o FOOTER
// Isso vai carregar o footer, o custom.js e o tableSimples.js,
// que finalmente inicializará o seu DataTable.
include_once(__DIR__ . '/../includes/footer.php');
?>


