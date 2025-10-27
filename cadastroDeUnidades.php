<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Unidades";
//---------------------------

include_once('includes/header.php'); // Já migrado para BS5

//-----------classes que serão usadas-----
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

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">

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

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataTable = new simpleDatatables.DataTable("#tableBioUBS", {
            searchable: true,   // Habilita busca
            perPageSelect: [5, 10, 15, 20], // Opções de itens por página
            labels: { // Tradução para Português (opcional)
                placeholder: "Buscar...",
                perPage: "{select} registros por página",
                noRows: "Nenhum registro encontrado",
                info: "Mostrando {start} a {end} de {rows} registros",
            }
        });
    });
</script>


<?php
// Include do Modal de Cadastro (será migrado abaixo)
// O Modal completo está aqui, não apenas a casca
include('modal/cadastro/modalCadastroDeUnidade.php');
?>

<?php
// Footer já está migrado e é incluído no final
include_once('includes/footer.php');
?>