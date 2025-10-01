<?php  
  require_once('includes/authorization.php');  



?>

<table id="tableBioUBS" class="display" style="width:100%">

        <thead>

            <tr>
                
                <th>Nome</th>
                <th>Perfil</th>
                <th>CNS</th>               
                <th>Ações</th>

            </tr>

        </thead>

        <tbody>

        <?php
        
        //-----------criterios de consulta--------------
          
          $tabela = "cadastro_profissional";//--------tabela como parametro 

          $cadUbs = new UbsCrudAll($tabela);//----objeto classe UbsCrudAll(parametro)

          $dataBR = new Idade(); //---------------objeto classe Idade   

        //----------------------------------------------

        //----------CONSULTA BÁSICA SEM CRITÉRIOS

        $UbsQuery = $cadUbs->listarTodos(); 

      

        foreach ($UbsQuery as $registrosUbs) {

            $id =               $registrosUbs['ID'];

            $nome =             $registrosUbs['NOME_COMPLETO'];
            $cns =  $registrosUbs['CNS_PROFISSIONAL'];
            $perfil =              $registrosUbs['PERFIL'];

            
        ?>


            <tr>
                <td><?=$nome?></td>

                <td><?=$perfil?></td>

                <td>
                <?=$cns?>
                </td>

            

                
                
                <td>
                  <!-------botão iimprimir------->
                  <a 
                    href="#"
                    id="btnImpPaciente"
                  >
                    <span class="glyphicon glyphicon-print"></span>
                  </a>
                  .

      <?php 
        /* verificando o nível de acesso para os botões Editar e Excluir*/ 
        if($nivelAcesso == 1):
      ?>
                <!-------botão editar------->
                  <a 
                    href="modal/edicao/modalEdCadastroDeProfissional.php?id=<?=$id?>" 
                    data-toggle="modal" data-target="#updateBioUBS" 
                    data-backdrop="static" data-keyboard="false"
                    id="btnEdPaciente"
                  >
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                <!-------------------------->

                  .
                <!-------botão excluir------->
                  <a 
                    href="modal/exclusao/modalExCadastroDeProfissional.php?id=<?=$id?>" 
                    data-toggle="modal" data-target="#deleteBioUBS" 
                    data-backdrop="static" data-keyboard="false"
                    id="btnExPaciente"
                  >
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                <!-------------------------->

      <?php endif;//---fim controle de acesso?>

                </td>
                
            </tr>

        <?php
        
        }//while 
        
        ?>

        </tbody>
       
    </table>

<!-----function da table---->
<script src="tableScript/tableSimples.js"></script>