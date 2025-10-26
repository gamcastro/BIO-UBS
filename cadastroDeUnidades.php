<?php
//----titulo da página------
$tituloDaPagina = "Cadastro de Unidades";
//---------------------------

// Inclui o header (Carrega CSS globais: BS5, DT BS5, Buttons BS5, abre <main>)
include_once('includes/header.php'); 

//-----------classes que serão usadas-----
require_once('class/UbsCrudAll.php');
// require_once('class/Idade.php'); // Comentado
//----------------------------------------

/* Lógica PHP de salvar/editar/excluir (Mantida como está) */
if($nivelAcesso == 1) {
    if(isset($_POST['salvar'])) { include('querys/inserts/insertUnidades.php'); }
    elseif(isset($_POST['editar'])) { include('querys/updates/updateUnidades.php'); }
    elseif(isset($_POST['excluir'])) { include('querys/deletes/deleteUnidades.php'); }
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
           data-bs-target="#insertUnidade"> <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
        </button>
    </div>
<?php endif; ?>

<?php
// Include da Tabela Principal (HTML da tabela + script tableSimples.js no final dela)
include('table/tableCadastroDeUnidade.php'); 
?>

<?php
// Include do Modal de Cadastro (HTML completo do modal - precisa estar migrado para BS5)
include('modal/cadastro/modalCadastroDeUnidade.php');
?>

<?php
// *** ESSENCIAL: Inclui o footer.php ***
// Ele fecha </main>, adiciona <footer>, carrega TODOS os JS necessários (jQuery, BS5 Bundle, Axios, DT Core, DT BS5, DT Buttons, custom.js) e fecha </body></html>
include_once('includes/footer.php'); 
?>