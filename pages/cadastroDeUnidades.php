<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Unidades";
//---------------------------
require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader
include_once(__DIR__ . '/../includes/header.php');


//-----------classes que serão usadas-----
use BioUBS\UbsCrudAll;
use BioUBS\Idade;

// require_once('class/Idade.php'); // Comentado
//----------------------------------------

/* Lógica PHP de salvar/editar/excluir (Mantida como está) */

if ($nivelAcesso == 1) {
    if (isset($_POST['salvar'])) {
        include(__DIR__ . '/../querys/inserts/insertUnidades.php');
    } elseif (isset($_POST['editar'])) {
        include(__DIR__ . '/../querys/updates/updateUnidades.php');
    } elseif (isset($_POST['excluir'])) {
        include(__DIR__ . '/../querys/deletes/deleteUnidades.php');
    }
}
?>

<script type="text/javascript" src="../js/mask/funcaoMascaraGeralNumeros.js"></script>
<script type="text/javascript" src="../js/mask/funcaoLetrasMaiusculas.js"></script>



<h1 class="display-5 text-center text-muted mb-4">Cadastro da Unidade</h1>
<hr class="mb-4">

<?php if ($nivelAcesso == 1): ?>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#insertUnidade">
            <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
        </button>
    </div>
<?php endif; ?>


<!-----------casca da modal de edicao------------->
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

<!-----------casca da modal de exclusão------------->
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
// Include da Tabela Principal (será migrada abaixo)
include(__DIR__ . '/../table/tableCadastroDeUnidade.php');
?>





<?php
// Include do Modal de Cadastro (será migrado abaixo)
// O Modal completo está aqui, não apenas a casca
include(__DIR__ . '/../modal/cadastro/modalCadastroDeUnidade.php');

include(__DIR__ . '/../includes/footer.php');
?>

<?php
// Footer já está migrado e é incluído no final

?>