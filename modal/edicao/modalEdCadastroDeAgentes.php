

<?php
require_once('../../class/Conexao.php');

if(isset($_GET['id'])): //----só sugirá o conteúdo de vier um ID
  
          $id = $_GET['id'];

        //-----------criterios de consulta--------------
          $camposIdBio = "*";
          $tabelaIdBio = "cadastro_agente";
        //----------------------------------------------

        //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
          require_once('../../querys/ConsultaPorId.php');
            
            //-------buscando dados na tabela------------------   
            while($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)){

                $nomeAgente = $rowsId['NOME'];
               
                $cpf = $rowsId['CPF'];               
                    

            }    
 ?> 



<!------------------janela modal-------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">

              <a href="" class="close" data-dismissB="modal">&times;</a><!------botao fechar------>
              
              <h4 class="modal-title">Editando cadastro de Agente</h4>

            </div>
            <!-------------------------------------------------------->

            
            <form id="ed" name="ed" action="" method="post"><!----formulario-------->   
                  
                  <!----------IMPORTANTE!!!!!!!!!!--------> 
                  <!-----------------IMPUT COM ID DO REGISTRO A SER ALTERADO----------> 
                      <input type="hidden" name="id" value="<?=$id?>">
                  <!------------------------------------------------------------------>
                  
            <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body">


                <table class="table table-bordered">
                  

                  <tr>
                    <td colspan="3">
                      <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome completo" onkeyup="alteraNome()" value="<?=$nomeAgente?>">
                    </td>                    
                  </tr>

                  <tr>
                    <td>CPF:</td>
                    
                  </tr>

                  <tr>

                    <td>
                      <input class="form-control" type="" name="cpf" placeholder="ex: 000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');" value="<?=$cpf?>">
                    </td>

                   
                    

                    
                  </tr>

                </table>
                
  
            </div>
            <!--------------------------------------------------------->


      

            
            <!---------------RODAPÉ DA JANELA---------------------->
            <div class="modal-footer">
              <a href="" class="btn btn-default" data-dismissB="modal">Cancelar</a>
              <button type="submit" name="editar" class="btn btn-success" >Salvar</button>
            </div>
      
      </form>
            <!----------------------------------------------------->

    
<!----------------------------fim da da janela modal----------------------------->


<?php 
endif;
?>
