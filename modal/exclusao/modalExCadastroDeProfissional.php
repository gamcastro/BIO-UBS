<?php
// 1. Caminho corrigido para o autoload (subindo 2 níveis)
require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['id'])): //----só sugirá o conteúdo se vier um ID
  $id = $_GET['id'];

  //-----------criterios de consulta--------------
  // 2. Tabela e campos corretos para Profissional
  $camposIdBio = "NOME_COMPLETO, CPF"; // Só precisamos do Nome e CPF
  $tabelaIdBio = "cadastro_profissional";
  //----------------------------------------------

  //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
  // 3. Caminho corrigido para a consulta
  require_once(__DIR__ . '/../../querys/ConsultaPorId.php');

  // 4. Variáveis de Profissional (com valores padrão)
  $nomeProfissional = "Profissional não encontrado";
  $cpf = "N/D";

  //-------buscando dados na tabela------------------  
  if ($buscaId->rowCount() > 0) {
    $rowsId = $buscaId->fetch(PDO::FETCH_ASSOC);
    $nomeProfissional = $rowsId['NOME_COMPLETO'];
    $cpf = $rowsId['CPF'];
  }
?>
  <div class="modal-header bg-danger text-white">
    <h5 class="modal-title" id="deleteModalLabel">Excluir Cadastro de Profissional</h5>
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
        <i class="bi bi-person-fill"></i> <?= htmlspecialchars($nomeProfissional) ?><br>
        <small class="text-muted"><strong>CPF:</strong> <?= htmlspecialchars($cpf) ?></small>
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