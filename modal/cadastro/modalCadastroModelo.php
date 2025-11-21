
<!----------------------------janela modal--------------------------------------------------------->

<div id="insertPaciente" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-sm">

      <!-------------CABEÇALHO DA JANELA------------------------->
      <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title mb-0">Novo registro</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <!-------------------------------------------------------->










      <form id="cad" name="cad" action="" method="post">

        <!----------------CORPO DA JANELA------------------------->
        <div class="modal-body p-3">

          <div class="p-3 mb-3 rounded bg-light">
            <strong class="d-block mb-2 text-primary">Dados</strong>

            <div class="row g-3">
              <div class="col-md-9">
                <label for="nome" class="form-label small">Nome</label>
                <input class="form-control" type="text" id="nome" name="nome" required placeholder="Nome completo" data-callback="alteraNome" data-titlecase="true">
              </div>
              <div class="col-md-3">
                <label for="data_nascimento" class="form-label small">Dt. Nasc.</label>
                <input class="form-control" type="date" name="data_nascimento" required>
              </div>

              <div class="col-md-3">
                <label for="cpf" class="form-label small">CPF</label>
                <input class="form-control" type="text" name="cpf" placeholder="ex: 000.000.000-00" data-mask="###.###.###-##">
              </div>

              <div class="col-md-3">
                <label for="rg" class="form-label small">Reg</label>
                <input class="form-control" type="text" name="rg" placeholder="ex: 0000000000-0" inputmode="numeric" data-numeric="true">
              </div>

              <div class="col-md-3">
                <label for="uf_rg" class="form-label small">UF</label>
                <select name="uf_rg" class="form-select" required>
                  <option value="" disabled selected>UF</option>
                  <?php include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php'); ?>
                </select>
                <div class="invalid-feedback">Selecione a UF do RG.</div>
              </div>

              <div class="col-md-3">
                <label for="ssp" class="form-label small">Orgão</label>
                <input class="form-control" type="text" id="ssp" name="ssp" placeholder="ex: SSP/MA" data-callback="alteraSSP">
              </div>
            </div>
          </div>

        </div>
        <!--------------------------------------------------------->


      












            
        <!---------------RODAPÉ DA JANELA---------------------->
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
        </div>

      </form>
      <!----------------------------------------------------->

    </div>

  </div>
</div>
<!----------------------------fim da da janela modal----------------------------->