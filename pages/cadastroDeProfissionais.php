<?php 
//----titulo da página------
  $tituloDaPagina = "Cadastro de Profissionais";

//---------------------------

require_once __DIR__ . '/../vendor/autoload.php';  //-- Autoload do Composer - Carregamentos das Classes
include_once(__DIR__ . '/../includes/header.php');

use BioUBS\UbsCrudAll ;
use BioUBS\Idade ;

//----------------------------------------



/*autorização*/
if($nivelAcesso == 1):
/* verificando o nível de acesso para Cadastrar, Editar e Excluir*/
      
    if(isset($_POST['salvar'])):

      //-----------------salvando o cadastro-----
      include(__DIR__ . '/../querys/inserts/insertProfissionais.php');
      //********************************************************

    elseif(isset($_POST['editar'])):

      //-----------------editando o cadastro-----
      include(__DIR__ . '/..querys/updates/updateProfissionais.php');
      //********************************************************

    elseif(isset($_POST['excluir'])):

      //-----------------excluindo o cadastro-----
      include(__DIR__ . '/../querys/deletes/deleteProfissionais.php');
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



<!--------área para SCRIPTS---------------------------------->

<!----------------chamando as funcoes de mascaras-------------------->
<script type="text/javascript" src="../js/mask/funcaoMascaraGeralNumeros.js"></script>
<script type="text/javascript" src="../js/mask/funcaoLetrasMaiusculas.js"></script>
<!------------------------------------------------------------------->


<!------incluir esse arquivo onde for usar tabela dinamica-------------->

<!---------------------------------------------------------------------->


<!--------caso tabela seja do tipo export--------------------------------->
    <?php //include('tableScript/conjuntoScriptsTableExport.php');?>
<!------------------------------------------------------------------------>

<!-----------------------FIM SCRIPTS------------------------------------------>



<!--------área para CSS---------------------------------->
<!------incluir esse arquivo onde for usar tabela dinamica-------------->

<!--------------------------------------------------------------->
<!------------------------fim css------------------------------>



   <h1 class="display-5 text-center text-muted mb-4">Cadastro da Profissiohal</h1>
<hr class="mb-4">     



<?php if($nivelAcesso == 1): ?>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary"
           data-bs-toggle="modal"
           data-bs-target="#insertProfissional">
             <i class="bi bi-plus-circle me-1"></i> Novo Cadastro
        </button>
    </div>
<?php endif; ?>




<!-----------casca da modal de edicao------------->
<div id="updateBioUBS" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- contener da janela-->
    <div class="modal-content">


    </div>
  </div>
</div>

<!-----------casca da modal de exclusão------------->
<div id="deleteBioUBS" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- contener da janela-->
    <div class="modal-content">



    </div>
  </div>
</div>      
<!-----------------fim para modal----------------------------------->



<?php



//---------------------IMPORTANTE!!!!!!!!!!!!
//-------------tabela principal--------------
include(__DIR__ . '/../table/tableCadastroDeProfissional.php');
//-------------------------------------------




//-----------incluindo o rodapé 
//include_once('includes/footer.php');

?>

<?php 
///-----------------JANELA MODAL----------------------
//-------------incluindo janela modal cadastro-----------------------------
include(__DIR__ .'/../modal/cadastro/modalCadastroDeProfissional.php');

include(__DIR__ . '/../includes/footer.php');
?>
