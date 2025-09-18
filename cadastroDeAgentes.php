<?php 
//----titulo da página------
  $tituloDaPagina = "Cadastro de Agentes";
//---------------------------

include_once('includes/header.php');

//-----------classes que serão usadas-----
require_once('class/UbsCrudAll.php');
require_once('class/Idade.php');
//----------------------------------------



/*autorização*/
if($nivelAcesso == 1):
/* verificando o nível de acesso para Cadastrar, Editar e Excluir*/
      
    if(isset($_POST['salvar'])):

      //-----------------salvando o cadastro-----
      include('querys/inserts/insertAgentes.php');
      //********************************************************

    elseif(isset($_POST['editar'])):

      //-----------------editando o cadastro-----
      include('querys/updates/updateAgentes.php');
      //********************************************************

    elseif(isset($_POST['excluir'])):

      //-----------------excluindo o cadastro-----
      include('querys/deletes/deleteAgentes.php');
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
<script type="text/javascript" src="js/mask/funcaoMascaraGeralNumeros.js"></script>
<script type="text/javascript" src="js/mask/funcaoLetrasMaiusculas.js"></script>
<!------------------------------------------------------------------->


<!------incluir esse arquivo onde for usar tabela dinamica-------------->
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<!---------------------------------------------------------------------->


<!--------caso tabela seja do tipo export--------------------------------->
    <?php //include('tableScript/conjuntoScriptsTableExport.php');?>
<!------------------------------------------------------------------------>

<!-----------------------FIM SCRIPTS------------------------------------------>



<!--------área para CSS---------------------------------->
<!------incluir esse arquivo onde for usar tabela dinamica-------------->
<link rel="stylesheet" href="css/jquery.dataTables.min.css" />
<!--------------------------------------------------------------->
<!------------------------fim css------------------------------>



        <h1 style="color:#CCCCCC; text-align:center">Cadastro de Agentes</h1>

<hr>


<?php
if($nivelAcesso == 1):
/* verificando o nível de acesso para o Botão Cadastrar*/
?>


<!---------------botão para acionar a modal------------------>
    <a
      class="btn btn-primary pull-right" 
      data-toggle="modal" data-target="#insertAgente" 
      id="btnCadPaciente" style="margin-top: -40px"
    >
      <span class="glyphicon glyphicon-plus-sign"></span> Novo Cadastro
    </a>
<!------------------------------------------------------------------>


<?php endif;?>    




<?php 
///-----------------JANELA MODAL----------------------
//-------------incluindo janela modal cadastro-----------------------------
include('modal/cadastro/modalCadastroDeAgentes.php');
?>

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
include('table/tableCadastroDeAgentes.php');
//-------------------------------------------




//-----------incluindo o rodapé 
//include_once('includes/footer.php');

?>


