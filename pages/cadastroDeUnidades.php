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

/*autorização*/
if ($nivelAcesso == 1):
/* verificando o nível de acesso para Cadastrar, Editar e Excluir*/
    
    if (isset($_POST['salvar'])):

        //-----------------salvando o cadastro-----
        include(__DIR__ . '/../querys/inserts/insertUnidades.php');
        //********************************************************

    elseif (isset($_POST['editar'])):

        //-----------------editando o cadastro-----
        include(__DIR__ . '/../querys/updates/updateUnidades.php');
        //********************************************************

    elseif (isset($_POST['excluir'])):

        //-----------------excluindo o cadastro-----
        include(__DIR__ . '/../querys/deletes/deleteUnidades.php');
        //********************************************************

    endif;

endif;//---fim para controle de acesso
?>

<!--------área para SCRIPTS---------------------------------->

<!----------------chamando as funcoes de mascaras-------------------->
<script type="text/javascript" src="../js/mask/funcaoMascaraGeralNumeros.js"></script>
<script type="text/javascript" src="../js/mask/funcaoLetrasMaiusculas.js"></script>
<!------------------------------------------------------------------->

<!-----------------------FIM SCRIPTS------------------------------------------>



<h1 class="display-5 text-center text-muted mb-4">Cadastro da Unidade</h1>
<hr class="mb-4">

<?php
if ($nivelAcesso == 1):
/* verificando o nível de acesso para o Botão Cadastrar*/
?>


<!---------------botão para acionar a modal------------------>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#insertUnidade">
            <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
        </button>
    </div>
<!------------------------------------------------------------------>


<?php endif; ?>


<?php 
///-----------------JANELA MODAL----------------------
//-------------incluindo janela modal cadastro-----------------------------
include(__DIR__ . '/../modal/cadastro/modalCadastroDeUnidade.php');
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




//---------------------IMPORTANTE!!!!!!!!!!!!
//-------------tabela principal--------------
include(__DIR__ . '/../table/tableCadastroDeUnidade.php');
//-------------------------------------------








//-----------incluindo o rodapé 
include(__DIR__ . '/../includes/footer.php');
?>

