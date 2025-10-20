<?php
// Se precisar de alguma conexão ou require no futuro, coloque aqui.
// Por enquanto, é apenas HTML.
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Acolhimento à Demanda Espontânea</h4>
</div>
<div class="modal-body">

  <form action="processa_acolhimento.php" method="POST">
    <div class="form-group">
      <label for="buscaPaciente">1. Identificar Paciente</label>
      <div class="input-group">
        <input type="text" class="form-control" name="busca_paciente" placeholder="Digite o CPF ou CNS..." required>
        <span class="input-group-btn">
          <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
        </span>
      </div>
    </div>

    <div id="dadosPaciente" class="well well-sm" style="display: none;">
      <h5 id="nomePaciente" style="margin-top: 0;"><strong>Paciente:</strong> <span></span></h5>
      <p class="mb-0"><strong>Data de Nasc.:</strong> <span id="nascimentoPaciente"></span></p>

      <input type="hidden" id="pacienteId" name="paciente_id">
    </div>

    <div class="form-group">
      <label for="queixaPrincipal">2. Registrar Queixa Principal</label>
      <textarea class="form-control" name="queixa_principal" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-success btn-block">
      <span class="glyphicon glyphicon-arrow-right"></span> Encaminhar para Triagem
    </button>
  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
</div>