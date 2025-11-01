<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Profissionais";
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
    // Script para inserir novo profissional
    include(__DIR__ . '/../querys/inserts/insertProfissionais.php');
  } elseif (isset($_POST['editar'])) {
    // Script para atualizar profissional (que já fizemos)
    include(__DIR__ . '/../querys/updates/updateProfissionais.php');
  } elseif (isset($_POST['excluir'])) {
    // Script para deletar profissional (que já fizemos)
    include(__DIR__ . '/../querys/deletes/deleteProfissionais.php');
  }
}
?>

<h1 class="display-5 text-center text-muted mb-4">Cadastro de Profissionais</h1>
<hr class="mb-4">

<?php if ($nivelAcesso == 1): ?>
  <div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#insertProfissional"> <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
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
// 3. Inclui a TABELA que acabamos de refatorar
include(__DIR__ . '/../table/tableCadastroDeProfissional.php');
?>


<?php
// 4. Inclui o CONTEÚDO do modal de "Novo Cadastro" (Estático)
//    O ID deste modal deve ser 'insertProfissional'
include(__DIR__ . '/../modal/cadastro/modalCadastroDeProfissional.php');
?>


<?php
// 5. Inclui o FOOTER (A CORREÇÃO DO SEU PROBLEMA)
//    Isso vai carregar o footer, o custom.js e o tableSimples.js,
//    que finalmente inicializará o seu DataTable.
include_once(__DIR__ . '/../includes/footer.php');
?>