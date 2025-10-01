<?php
require_once('../../class/Conexao.php');

if(isset($_GET['id'])): //----só sugirá o conteúdo de vier um ID
  
          $id = $_GET['id'];

        //-----------criterios de consulta--------------
          $camposIdBio = "*";
          $tabelaIdBio = "cadastro_profissional";
        //----------------------------------------------

        //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
          require_once('../../querys/ConsultaPorId.php');
            
            //-------buscando dados na tabela------------------   
            while($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)){

                $nomeProfissional = $rowsId['NOME_COMPLETO'];
                $cpf = $rowsId['CPF'];
               

            }    
 ?> 


<!----------------------------janela modal--------------------------------------------------------->
        


            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">

              <a href="" type="button" class="close" data-dismissB="modal">&times;</a><!------botao fechar------>
              
              <h4 class="modal-title">Excluir cadastro de CNS_PROFISSIONAL</h4>

            </div>
            <!-------------------------------------------------------->

        <form id="ed" name="ed" action="" method="post"><!----formulario-------->   
                  
                  <!----------IMPORTANTE!!!!!!!!!!--------> 
                  <!-----------------IMPUT COM ID DO REGISTRO A SER ALTERADO----------> 
                      <input type="hidden" name="id" value="<?=$id?>">
                  <!------------------------------------------------------------------>
            
            <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body">

                <p style="text-align: justify; color: #FF0000">Atenção! Você está prestes a EXCLUIR um registro do Banco de Dados. Esta operação não poderá ser desfeita.</p>
                <hr>
                Registro a ser excluído:<br>
                <b>
                    <?=$nomeProfissional?><br>
                    CPF: <?=$cpf?>
                </b>
            </div>
            <!--------------------------------------------------------->


            <!---------------RODAPÉ DA JANELA---------------------->
            <div class="modal-footer">
              <a href="" type="button" class="btn btn-primary" data-dismiss="modalB">Cancelar</a>
              <button type="submit" name="excluir" class="btn btn-danger" >Excluir</button>
            </div>
            <!----------------------------------------------------->

        </form>
<!----------------------------fim da da janela modal----------------------------->


<?php 
endif;
?>