<?php
// 1. Caminho corrigido para o autoload (subindo 2 níveis)
require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['id'])): //----só sugirá o conteúdo se vier um ID
 
  $id = $_GET['id'];

  //-----------criterios de consulta--------------
    // 2. Tabela corrigida para 'cadastro_unidade'
  $camposIdBio = "NOME, CNES"; // Só precisamos do Nome e CNES
  $tabelaIdBio = "cadastro_unidade";
  //----------------------------------------------

  //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
    // 3. Caminho corrigido para a consulta
  require_once(__DIR__ . '/../../querys/ConsultaPorId.php');
  
    $nomeUnidade = "Unidade não encontrada";
    $cnes = "N/D";
  
  //-------buscando dados na tabela------------------  
  if ($buscaId->rowCount() > 0) {
        $rowsId = $buscaId->fetch(PDO::FETCH_ASSOC);
        // 4. Variáveis corrigidas para Unidade
    $nomeUnidade = $rowsId['NOME'];
    $cnes = $rowsId['CNES'];
  }  
?> 

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Excluir Cadastro de Unidade</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
    <form id="ex" name="ex" action="" method="post">
    
        <input type="hidden" name="id" value="<?= $id ?>">
      
        <div class="modal-body">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Atenção!</h4>
                <p>Você está prestes a **EXCLUIR** um registro do Banco de Dados. Esta operação não poderá ser desfeita.</p>
            </div>
      <hr>
      <p>Registro a ser excluído:</p>
            <h5>
                <i class="bi bi-hospital"></i> <?= htmlspecialchars($nomeUnidade) ?><br>
                <small class="text-muted"><strong>CNES:</strong> <?= htmlspecialchars($cnes) ?></small>
            </h5>
    </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            
                  <button type="submit" name="excluir" class="btn btn-danger">Confirmar Exclusão</button>
    </div>
      </form>
    <?php 
endif;
?>