<?php
// Conteúdo do modal de Acolhimento (Bootstrap 5)
?>
<div class="modal-header">
  <h5 class="modal-title" id="acolhimentoModalLabel">Acolhimento à Demanda Espontânea</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
  <form action="processa_acolhimento.php" method="POST">

    <div class="mb-3"> 
      <label for="buscaPaciente" class="form-label fw-bold">1. Identificar Paciente</label> 
      <div class="input-group"> 
         <input type="text" class="form-control" id="buscaPaciente" name="busca_paciente" placeholder="Digite o CPF ou CNS..." required aria-label="CPF ou CNS do Paciente" aria-describedby="btnBuscar">
         <button class="btn btn-outline-secondary" type="button" id="btnBuscar">
           <i class="bi bi-search"></i> Buscar
         </button> 
       </div>
    </div>

    <div id="dadosPaciente" class="p-3 mb-3 bg-light border rounded" style="display: none;">
      <h6 id="nomePaciente" class="mb-1"><strong>Paciente:</strong> <span></span></h6>
      <p class="mb-0 small"><strong>Data de Nasc.:</strong> <span id="nascimentoPaciente"></span></p> 
      <input type="hidden" id="pacienteId" name="paciente_id"> 
    </div> 
    <div class="mb-3"> <label for="queixaPrincipal" class="form-label fw-bold">2. Registrar Queixa Principal</label> 
      <textarea class="form-control" id="queixaPrincipal" name="queixa_principal" rows="3" required></textarea>
    </div>

    <div class="d-grid"> <button type="submit" class="btn btn-success btn-lg" id="btnEncaminhar" disabled> <i class="bi bi-arrow-right-circle-fill"></i> Encaminhar para Triagem </button>
    </div>
    </form> </div> <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
</div>