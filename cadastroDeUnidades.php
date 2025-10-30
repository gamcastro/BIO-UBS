<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Unidades";
//---------------------------

include_once('includes/header.php'); // Já migrado para BS5

//-----------classes que serão usadas-----
require_once __DIR__ . '../vendor/autoload.php'; //-- Autoload do Composer - Carregamentos das Classes
require_once('class/UbsCrudAll.php');
// require_once('class/Idade.php'); // Comentado
//----------------------------------------

/* Lógica PHP de salvar/editar/excluir (Mantida como está) */
if($nivelAcesso == 1) {
    if(isset($_POST['salvar'])) {
        include('querys/inserts/insertUnidades.php');
    } elseif(isset($_POST['editar'])) {
        include('querys/updates/updateUnidades.php');
    } elseif(isset($_POST['excluir'])) {
        include('querys/deletes/deleteUnidades.php');
    }
}
?>

<script type="text/javascript" src="js/mask/funcaoMascaraGeralNumeros.js"></script>
<script type="text/javascript" src="js/mask/funcaoLetrasMaiusculas.js"></script>



<h1 class="display-5 text-center text-muted mb-4">Cadastro da Unidade</h1>
<hr class="mb-4">

<?php if($nivelAcesso == 1): ?>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary"
           data-bs-toggle="modal"
           data-bs-target="#insertUnidade">
             <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
        </button>
    </div>
<?php endif; ?>

<?php
// Include da Tabela Principal (será migrada abaixo)
include('table/tableCadastroDeUnidade.php');
?>





<?php
// Include do Modal de Cadastro (será migrado abaixo)
// O Modal completo está aqui, não apenas a casca
include('modal/cadastro/modalCadastroDeUnidade.php');
?>

<?php
// Footer já está migrado e é incluído no final
include_once('includes/footer.php');
?>