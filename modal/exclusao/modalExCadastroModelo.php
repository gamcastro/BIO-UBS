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
                $cpf = $rowsId['CPF'];
               

            }    
 ?> 


<!----------------------------janela modal--------------------------------------------------------->
        


            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title mb-0">Excluir cadastro</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <!-------------------------------------------------------->

          <form id="ed" name="ed" action="" method="post">
                <input type="hidden" name="id" value="<?=$id?>">

            <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body">
              <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Atenção!</h4>
                <p>Você está prestes a <strong>EXCLUIR</strong> um registro do Banco de Dados. Esta operação não poderá ser desfeita.</p>
              </div>
              <hr>
              <p>Registro a ser excluído:</p>
              <h5>
                <i class="bi bi-person-fill"></i> <?= htmlspecialchars($nomePaciente) ?><br>
                <small class="text-muted"><strong>CPF:</strong> <?= htmlspecialchars($cpf) ?></small>
              </h5>
            </div>
            <!--------------------------------------------------------->

            <!---------------RODAPÉ DA JANELA---------------------->
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="excluir" class="btn btn-danger">Excluir</button>
            </div>

          </form>
<!----------------------------fim da da janela modal----------------------------->


<?php 
endif;
?>