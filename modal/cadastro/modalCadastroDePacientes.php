
<!----------------------------janela modal--------------------------------------------------------->

<div id="insertPaciente" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"> <!------aqui consigo mudar o tamanho da modal para modal-lg modal-sm------>
    <!-- contener da janela-->
    <div class="modal-content">

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">
              <h4 class="modal-title">Cadastrando novo Paciente</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-------------------------------------------------------->










     <form id="cad" name="cad" action="" method="post"><!--------------formulário------->


            <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body">

                <!-- DADOS PESSOAIS -->
                <div style="background-color: #d1ecf1; color: #0c5460;" class="border p-2 mb-3 text-start fw-bold">
                    Dados pessoais
                </div>
                
                <!-- CPF e CNS -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <input class="form-control" type="text" name="cpf" placeholder="000.000.000-00" 
                               onkeypress="return mascaras(event, this, '###.###.###-##');" 
                               minlength="14" maxlength="14">
                    </div>
                    <div class="col-md-6">
                        <label for="cns" class="form-label">CNS</label>
                        <input class="form-control" type="text" name="cns" placeholder="000 0000 0000 0000" 
                               minlength="15" maxlength="18"
                               onkeypress="return mascaras(event, this, '### #### #### ####');"
                               onkeydown="return event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Tab' || /[0-9]/.test(event.key)">
                    </div>
                </div>

                <!-- Nome completo -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nome" class="form-label">Nome completo *</label>
                        <input class="form-control" type="text" id="nome" name="nome" required="required" 
                               placeholder="Nome completo" 
                               oninput="this.value = capitalizeNameWithPrepositions(this.value)" minlength="3">
                        <div class="invalid-feedback">
                            Por favor, informe o nome completo (mínimo 3 caracteres).
                        </div>
                    </div>
                </div>

                <!-- Data de Nascimento, Sexo e Raça/Cor -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="data_nascimento" class="form-label">Data de nascimento *</label>
                        <input class="form-control" type="date" name="data_nascimento" required="required">
                    </div>
                    <div class="col-md-4">
                        <label for="sexo" class="form-label">Sexo *</label>
                        <select name="sexo" class="form-select" required="required">
                            <option value="" disabled selected>Selecione</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                        <div class="invalid-feedback">Por favor, selecione o sexo.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="raca_cor" class="form-label">Raça/Cor *</label>
                        <select name="raca_cor" class="form-select" required="required">
                            <option value="" disabled selected>Selecione</option>
                            <option value="BRANCA">Branca</option>
                            <option value="PRETA">Preta</option>
                            <option value="PARDA">Parda</option>
                            <option value="AMARELA">Amarela</option>
                            <option value="INDIGENA">Indígena</option>
                        </select>
                        <div class="invalid-feedback">Por favor, selecione a raça/cor.</div>
                    </div>
                </div>

                <!-- Nome da Mãe -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nome_mae" class="form-label">Nome da mãe *</label>
                        <input class="form-control" type="text" name="nome_mae" required="required" 
                               placeholder="Nome completo da mãe" minlength="3"
                               oninput="this.value = capitalizeNameWithPrepositions(this.value)">
                        <div class="invalid-feedback">
                            Por favor, informe o nome completo da mãe.
                        </div>
                    </div>
                </div>

                <!-- RG e UF do RG -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="rg" class="form-label">RG</label>
                        <input class="form-control" type="text" name="rg" placeholder="0000000000-0"
                               onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                    <div class="col-md-6">
                        <label for="uf_rg" class="form-label">UF do RG</label>
                        <select name="uf_rg" class="form-select">
                            <option value="" disabled selected>UF</option>
                            <?php
                              include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Órgão Expedidor -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="ssp" class="form-label">Órgão Expedidor</label>
                        <input class="form-control" type="text" id="ssp" name="ssp" 
                               placeholder="ex: SSP/MA" onkeyup="alteraSSP()">
                    </div>
                </div>

                <!-- CONTATOS -->
                <div style="background-color: #d1ecf1; color: #0c5460;" class="border p-2 mb-3 text-start fw-bold">
                    Contatos
                </div>

                <!-- Telefones -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="telefone_residencial" class="form-label">Telefone residencial</label>
                        <input class="form-control" type="text" name="telefone_residencial" 
                               placeholder="(00) 0000-0000"
                               onkeypress="return mascaras(event, this, '(##) ####-####');">
                    </div>
                    <div class="col-md-4">
                        <label for="telefone_celular" class="form-label">Telefone celular</label>
                        <input class="form-control" type="text" name="telefone_celular" 
                               placeholder="(00)90000-0000"
                               onkeypress="return mascaras(event, this, '(##)#####-####');">
                    </div>
                    <div class="col-md-4">
                        <label for="telefone_contato" class="form-label">Telefone de contato</label>
                        <input class="form-control" type="text" name="telefone_contato" 
                               placeholder="(00)90000-0000"
                               onkeypress="return mascaras(event, this, '(##)#####-####');">
                    </div>
                </div>

                <!-- Email -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="email" name="email" placeholder="email@exemplo.com">
                    </div>
                </div>

                <!-- RESIDÊNCIA -->
                <div style="background-color: #d1ecf1; color: #0c5460;" class="border p-2 mb-3 text-start fw-bold">
                    Residência
                </div>

                <!-- UF e Município -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="estado" class="form-label">UF</label>
                                                <select name="estado" class="form-select">
                                                        <option value="" disabled selected>UF</option>
                                                        <?php
                                                            // Usar include para garantir que as opções sejam exibidas
                                                            // mesmo que o script já tenha sido carregado anteriormente.
                                                            $selectedUf = isset($estado) ? (string)$estado : '';
                                                            include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                                                            unset($selectedUf); // evita interferência em outros selects
                                                        ?>
                                                </select>
                    </div>
                    <div class="col-md-8">
                        <label for="municipio" class="form-label">Município</label>
                        <input class="form-control" type="text" name="municipio" 
                               placeholder="Nome do município"
                               oninput="this.value = capitalizeNameWithPrepositions(this.value)">
                    </div>
                </div>

                <!-- CEP e Bairro -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input class="form-control" type="text" name="cep" placeholder="00000-000" 
                               onkeypress="return mascaras(event, this, '#####-###');">
                    </div>
                    <div class="col-md-9">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input class="form-control" type="text" name="bairro" placeholder="Nome do bairro"
                               oninput="this.value = capitalizeNameWithPrepositions(this.value)">
                    </div>
                </div>

                <!-- Tipo de logradouro e Logradouro -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="tipo_logradouro" class="form-label">Tipo de logradouro</label>
                        <select name="tipo_logradouro" class="form-select">
                            <option value="" disabled selected>Selecione</option>
                            <option value="RUA">Rua</option>
                            <option value="AVENIDA">Avenida</option>
                            <option value="TRAVESSA">Travessa</option>
                            <option value="ALAMEDA">Alameda</option>
                            <option value="ESTRADA">Estrada</option>
                            <option value="RODOVIA">Rodovia</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label for="endereco" class="form-label">Logradouro</label>
                        <input class="form-control" type="text" name="endereco" 
                               placeholder="Nome do logradouro"
                               oninput="this.value = capitalizeNameWithPrepositions(this.value)">
                    </div>
                </div>

                <!-- Número e Complemento -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="numero" class="form-label">Número</label>
                        <input class="form-control" type="text" name="numero" placeholder="Número"
                               onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                    <div class="col-md-8">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input class="form-control" type="text" name="complemento" placeholder="Apt, casa, etc.">
                    </div>
                </div>

                <!-- Consentimento LGPD -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lgpd_consent" id="lgpd_consent" required>
                            <label class="form-check-label" for="lgpd_consent">
                                Consentimento LGPD *
                            </label>
                            <div class="form-text">
                                Concordo com o tratamento dos meus dados pessoais conforme a LGPD.
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!--------------------------------------------------------->


      












            
            <!---------------RODAPÉ DA JANELA---------------------->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
            </div>
      
      </form><!----fim formulario--->
            <!----------------------------------------------------->

    </div>

  </div>
</div>
<!----------------------------fim da da janela modal----------------------------->