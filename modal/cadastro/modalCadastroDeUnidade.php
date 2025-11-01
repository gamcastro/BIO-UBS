<div class="modal fade" id="insertUnidade" tabindex="-1" aria-labelledby="insertUnidadeLabel" aria-hidden="true"> 
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <form id="cadUnidadeForm" name="cadUnidade" action="cadastroDeUnidades.php" method="post"> 
        
        <div class="modal-header">
          <h5 class="modal-title" id="insertUnidadeLabel">Cadastrando Nova Unidade</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <h6 class="text-primary mb-3 border-bottom pb-2">DADOS DA UNIDADE</h6>
          
          <div class="row g-3 mb-3"> 
            <div class="col-md-6">
              <label for="cad-nome" class="form-label">Nome <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="cad-nome" name="nome" required>
            </div>
            <div class="col-md-6">
              <label for="cad-cnpj" class="form-label">CNPJ</label>
              <input type="text" class="form-control" id="cad-cnpj" name="cnpj" placeholder="00.000.000/0000-00">
            </div>
            <div class="col-md-6">
              <label for="cad-cnes" class="form-label">CNES <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="cad-cnes" name="cnes" required placeholder="Apenas números">
            </div>
            <div class="col-md-6">
              <label for="cad-telefone" class="form-label">Telefone</label>
              <input type="tel" class="form-control" id="cad-telefone" name="telefone" placeholder="(99) 99999-9999">
            </div>
          </div>

          <h6 class="text-primary mb-3 border-bottom pb-2 pt-3">ENDEREÇO</h6>

          <div class="row g-3">
            <div class="col-md-4">
              <label for="cad-cep" class="form-label">CEP</label>
              <input type="text" class="form-control" id="cad-cep" name="cep" placeholder="00000-000">
            </div>
            <div class="col-md-8">
              <label for="cad-logradouro" class="form-label">Logradouro</label>
              <input type="text" class="form-control" id="cad-logradouro" name="logradouro">
            </div>
            <div class="col-md-3">
              <label for="cad-numero" class="form-label">Número</label>
              <input type="text" class="form-control" id="cad-numero" name="numero">
            </div>
            <div class="col-md-5">
              <label for="cad-bairro" class="form-label">Bairro</label>
              <input type="text" class="form-control" id="cad-bairro" name="bairro">
            </div>
             <div class="col-md-4">
              <label for="cad-complemento" class="form-label">Complemento</label>
              <input type="text" class="form-control" id="cad-complemento" name="complemento">
            </div>
             <div class="col-md-8">
              <label for="cad-municipio" class="form-label">Município</label>
              <input type="text" class="form-control" id="cad-municipio" name="municipio">
            </div>
             <div class="col-md-4">
              <label for="cad-uf" class="form-label">Estado (UF)</label>
              <select id="cad-uf" name="uf" class="form-select"> 
                <option value="" selected>Selecione...</option>
                <?php 
                // É melhor encapsular essa lógica em uma função ou buscar os dados antes
                // para não ter 'require' dentro do HTML do modal.
                // Mas mantendo a estrutura original por enquanto:
                try {
                    require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                } catch(Exception $e) {
                    echo '<option value="" disabled>Erro ao carregar UFs</option>';
                }
                // ?>
              </select>
            </div>
          </div>

        </div> 

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="salvar" class="btn btn-success">Salvar Unidade</button>
        </div>

      </form> 
    </div> 
  </div> 
</div>