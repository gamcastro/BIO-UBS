

<?php
require_once('../../class/Conexao.php');

if(isset($_GET['id'])): //----só sugirá o conteúdo de vier um ID
  
          $id = $_GET['id'];

        //-----------criterios de consulta--------------
          $camposIdBio = "*";
          $tabelaIdBio = "cadastro_paciente";
        //----------------------------------------------

        //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
          require_once('../../querys/ConsultaPorId.php');
            
            //-------buscando dados na tabela------------------   
            while($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)){

                $nomePaciente = $rowsId['NOME'];
                $data_nascimento = $rowsId['DATA_NASCIMENTO'];
                $cpf = $rowsId['CPF'];
                $rg = $rowsId['RG'];
                      
                      //---------uf do RG salva no cadastro----------------------
                        $cd_uf_rg = $rowsId['UF_RG'];

                          require_once('../../querys/ConsultaUnidadeFederativaPorID.php');

                          while($rowsUfRg = $buscaUfId->fetch(PDO::FETCH_ASSOC)){
                            $uf_rg = $rowsUfRg['DS_UF_SIGLA'];
                            $cd_uf_rg = $rowsUfRg['CD_UF'];
                            $uf_nome_rg = $rowsUfRg['DS_UF_NOME'];
                          }  
                      //---------------------------------------------

                $ssp = $rowsId['SSP'];

            }    
 ?> 



<!------------------janela modal-------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">

              <a href="" class="close" data-dismissB="modal">&times;</a><!------botao fechar------>
              
              <h4 class="modal-title">Editando cadastro de Paciente</h4>

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
                    <td colspan="3" style="width: 500px">Nome:</td>
                    <td>Data_nascimento:</td>
                  </tr>

                  <tr>
                    <td colspan="3">
                      <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome completo" onkeyup="alteraNome()" value="<?=$nomePaciente?>">
                    </td>
                    <td>
                      <input class="form-control" type="date" name="data_nascimento" required="required" value="<?=$data_nascimento?>">
                    </td>
                  </tr>

                  <tr>
                    <td>CPF:</td>
                    <td>Reg:</td>
                    <td>UF:</td>
                    <td>Orgão:</td>
                  </tr>

                  <tr>

                    <td>
                      <input class="form-control" type="" name="cpf" placeholder="ex: 000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');" value="<?=$cpf?>">
                    </td>

                    <td>
                      <input class="form-control" type="" name="rg" placeholder="ex: 0000000000-0" value="<?=$rg?>">
                    </td>

                    <td>
                      <select name="uf_rg" class="form-control">
                        <option value="<?=$cd_uf_rg?>"><?=$uf_rg . " - " . $uf_nome_rg?></option>
                          <?php
                            require_once('../../querys/ConsultaUnidadeFederativaSelect.php');
                          ?>
                      </select>  
                    </td>

                    <td>
                      <input class="form-control" type="" id="ssp" name="ssp" placeholder="ex: SSP/MA" onkeyup="alteraSSP()" value="<?=$ssp?>">
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
