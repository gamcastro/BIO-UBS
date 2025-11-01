 <!----- INÍCIO MODAL CADASTRO PROFISSIONA -->
<div class="modal fade" id="insertProfissional" tabindex="-1" aria-labelledby="insertProfissionalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <!--- --- INÍCIO FORMULÁRIO --- --->
      <form id="cadProfissionalForm" name="cadProfissional" action="cadastroDeProfissionais.php" method="post"> 
        
        <div class="modal-header">
          <h5 class="modal-title" id="insertProfissionalLabel">Cadastrando Novo Profissional</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
          <!--- --- Seção: Dados Pessoais --- --->
          <h6 class="text-primary mb-3 border-bottom pb-2">DADOS PESSOAIS</h6>
          <div class="row g-3 mb-3">
             <div class="col-md-8">
              <label for="cad-nome_completo" class="form-label">Nome Completo <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="cad-nome_completo" name="nome_completo" required>
            </div>
             <div class="col-md-4">
              <label for="cad-data_nascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="cad-data_nascimento" name="data_nascimento" required>
            </div>
             <div class="col-md-4">
               <label for="cad-cpf" class="form-label">CPF</label>
               <input type="text" class="form-control" id="cad-cpf" name="cpf" placeholder="000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');">
             </div>
             <div class="col-md-4">
               <label for="cad-cns_profissional" class="form-label">CNS</label>
               <input type="text" class="form-control" id="cad-cns_profissional" name="cns_profissional" placeholder="Nº Cartão Nacional Saúde">
             </div>
              <div class="col-md-4">
               <label for="cad-sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
               <select id="cad-sexo" name="sexo" class="form-select" required>
                 <option value="" selected>Selecione...</option>
                 <option value="Feminino">Feminino</option>
                 <option value="Masculino">Masculino</option>
               </select>
             </div>
          </div> 

          <!--- --- Seção: Acesso e Perfil --- --->
          <h6 class="text-primary mb-3 border-bottom pb-2 pt-3">ACESSO E PERFIL</h6>
           <div class="row g-3 mb-3">
               <!--- Campo Matrícula (Login) --->
               <div class="col-md-4">
                 <label for="cad-matricula" class="form-label">Matrícula (Login) <span class="text-danger">*</span></label>
                 <input type="text" class="form-control" id="cad-matricula" name="matricula" required>
                 <div class="form-text">Identificador único para login.</div>
               </div>
               
               <div class="col-md-4">
                 <label for="cad-perfil" class="form-label">Perfil <span class="text-danger">*</span></label>
                 <select id="cad-perfil" name="perfil" class="form-select" required>
                    <option value="" selected>Selecione um perfil...</option>
                    <option value="MEDICO">MÉDICO</option>
                    <option value="ENFERMEIRO">ENFERMEIRO</option>
                    <option value="TECNICO_ENFERMAGEM">AUXILIAR/TÉCNICO ENFERMAGEM</option>
                    <option value="CIRURGIAO_DENTISTA">CIRURGIÃO DENTISTA</option>
                    <option value="ASB">ASB - AUXILIAR SAÚDE BUCAL</option>
                    <option value="TSB">TSB - TÉCNICO SAÚDE BUCAL</option>
                    <option value="ACS">ACS - AGENTE COMUNITÁRIO SAÚDE</option>
                    <option value="ACE">ACE - AGENTE COMBATE ENDEMIAS</option>
                    <option value="COORDENADOR">COORDENADOR UBS</option>
                    <option value="RECEPCAO">RECEPÇÃO</option>
                    <option value="OUTRO_NIVEL_SUPERIOR">OUTRO PROF. NÍVEL SUPERIOR</option>
                 </select>
               </div>
               <div class="col-md-4">
                   <label for="cad-email" class="form-label">E-mail <span class="text-danger">*</span></label> 
                   <input type="email" class="form-control" id="cad-email" name="email" required placeholder="exemplo@email.com">
               </div>
                <!--- Campos Senha (Hash a ser feito no backend) --->
                <div class="col-md-6">
                   <label for="cad-senha_hash" class="form-label">Senha <span class="text-danger">*</span></label>
                   <input type="password" class="form-control" id="cad-senha_hash" name="senha_hash" required>
               </div>
               <div class="col-md-6">
                   <label for="cad-confirmar_senha" class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                   <input type="password" class="form-control" id="cad-confirmar_senha" name="confirmar_senha" required>
               </div>
           </div> 

           <!--- --- Seção: Contato --- --->
           <h6 class="text-primary mb-3 border-bottom pb-2 pt-3">CONTATO</h6>
           <div class="row g-3 mb-3">
               <div class="col-md-6">
                   <label for="cad-telefone" class="form-label">Telefone</label>
                   <input type="tel" class="form-control" id="cad-telefone" name="telefone" placeholder="(99) 99999-9999">
               </div>
           </div> 

          <!--- --- Seção: Conselho de Classe --- --->
          <h6 class="text-primary mb-3 border-bottom pb-2 pt-3">CONSELHO DE CLASSE</h6>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="cad-conselho_classe" class="form-label">Conselho</label>
              <input type="text" class="form-control" id="cad-conselho_classe" name="conselho_classe" placeholder="Ex: CRM, COREN">
            </div>
            <div class="col-md-4">
              <label for="cad-registro_conselho" class="form-label">Nº Registro</label>
              <input type="text" class="form-control" id="cad-registro_conselho" name="registro_conselho" placeholder="Ex: 123456">
            </div>
            <div class="col-md-4">
              <label for="cad-estado_emissor_conselho" class="form-label">UF Emissor</label>
              <select id="cad-estado_emissor_conselho" name="estado_emissor_conselho" class="form-select">
                <option value="" selected>Selecione...</option>
                <?php try { require __DIR__ .'/../../querys/ConsultaUnidadeFederativaSelect.php'; } catch(Exception $e) { echo '<option value="" disabled>Erro</option>'; } ?>
              </select>
            </div>
          </div> 

          <!--- --- Seção: Endereço --- --->
          <h6 class="text-primary mb-3 border-bottom pb-2 pt-3">ENDEREÇO</h6>
          <div class="row g-3">
             <div class="col-md-4"> <label for="cad-cep-prof" class="form-label">CEP</label> <input type="text" class="form-control" id="cad-cep-prof" name="cep" placeholder="00000-000"> </div>
             <div class="col-md-8"> <label for="cad-logradouro-prof" class="form-label">Logradouro</label> <input type="text" class="form-control" id="cad-logradouro-prof" name="logradouro"> </div>
             <div class="col-md-3"> <label for="cad-numero-prof" class="form-label">Número</label> <input type="text" class="form-control" id="cad-numero-prof" name="numero"> </div>
             <div class="col-md-5"> <label for="cad-bairro-prof" class="form-label">Bairro</label> <input type="text" class="form-control" id="cad-bairro-prof" name="bairro"> </div>
             <div class="col-md-4"> <label for="cad-complemento-prof" class="form-label">Complemento</label> <input type="text" class="form-control" id="cad-complemento-prof" name="complemento"> </div>
             <div class="col-md-8"> <label for="cad-municipio-prof" class="form-label">Município</label> <input type="text" class="form-control" id="cad-municipio-prof" name="municipio"> </div>
             <div class="col-md-4">
              <label for="cad-estado_endereco-prof" class="form-label">Estado (UF)</label>
              <select id="cad-estado_endereco-prof" name="estado_endereco" class="form-select">
                <option value="" selected>Selecione...</option>
                 <?php try { require __DIR__ .'/../../querys/ConsultaUnidadeFederativaSelect.php'; } catch(Exception $e) { echo '<option value="" disabled>Erro</option>'; } ?>
              </select>
            </div>
             <div class="col-12">
               <label for="cad-ponto_referencia" class="form-label">Ponto de Referência</label>
               <textarea class="form-control" id="cad-ponto_referencia" name="ponto_referencia" rows="2"></textarea>
             </div>
          </div> 

        </div> <!--- --- FIM DO CORPO DO MODAL --- --->

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="salvar" class="btn btn-success">Salvar Profissional</button>
        </div>

      </form> <!--- --- FIM DO FORMULÁRIO --- --->
    </div> 
  </div> 
</div> 
<!--------- FIM MODAL CADASTRO PROFISSIONAL --->