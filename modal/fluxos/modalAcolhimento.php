
<!----------------------------janela modal--------------------------------------------------------->

  <!-------------CABEÇALHO DA JANELA------------------------->
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button> <!------botao fechar------>
  <h4 class="modal-title">Acolhimento à Demanda Espontânea</h4>
</div>

<div class="modal-body">
  <form action="processa_acolhimento.php" method="POST"><!----formulario-------->   
    
    <div class="form-group">
      <label for="buscaPaciente">1. Identificar Paciente</label>
      <div class="input-group">
        
        <input type="text" class="form-control" id="buscaPaciente" name="busca_paciente" placeholder="Digite o CPF ou CNS..." required>

        <span class="input-group-btn">
          <button class="btn btn-default" type="button" id="btnBuscar">
            <span class="glyphicon glyphicon-search"></span> Buscar
          </button> <!------botao buscar------> 
        </span>
      </div>
    </div>

    <!-- Painel para exibir os dados do paciente encontrado . Inicialmente oculto -->
    <div id="dadosPaciente" class="well well-sm" style="display: none;">
      <h5 id="nomePaciente" style="margin-top: 0;"><strong>Paciente:</strong> <span></span></h5>
      <p class="mb-0"><strong>Data de Nasc.:</strong> <span id="nascimentoPaciente"></span></p>

       <!----------IMPORTANTE!!!!!!!!!!--------> 
                  <!-----------------IMPUT COM ID DO REGISTRO A SER EXIBIDO----------> 
      <input type="hidden" id="pacienteId" name="paciente_id">
    </div>
  <!-- -------------------------- Fim Painel ------------------------------>

     <!-- TextArea para inserir a caixa do paciente ----------------------->
    <div class="form-group">
      <label for="queixaPrincipal">2. Registrar Queixa Principal</label>
      <textarea class="form-control" id="queixaPrincipal" name="queixa_principal" rows="3" required></textarea>
    </div>
<!-- -------------------------------------------------------------------->

    <!-- Botão Encaminhar para Triagem -->
    <button type="submit" class="btn btn-success btn-block" id="btnEncaminhar" disabled>
      <span class="glyphicon glyphicon-arrow-right"></span> Encaminhar para Triagem
    </button>
<!-- ---------------------------------------------------------------------------->
  </form> <!----fim do formulario-------->
</div>

<!---------------RODAPÉ DA JANELA---------------------->
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
</div>
<!----------------------------------------------------->