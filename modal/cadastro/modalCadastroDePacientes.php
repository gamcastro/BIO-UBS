
<!----------------------------janela modal--------------------------------------------------------->

<div id="insertPaciente" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-sm">

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title mb-0">Novo paciente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <!-------------------------------------------------------->










            <form id="cad" name="cad" action="" method="post">
                <!----------------CORPO DA JANELA------------------------->
                <div class="modal-body">

                    <!-- DADOS PESSOAIS -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Dados pessoais</strong>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="CPF" class="form-label small">CPF</label>
                                <input class="form-control" type="text" name="cpf" id="CPF" placeholder="000.000.000-00" data-mask="###.###.###-##" minlength="14" maxlength="14">
                            </div>

                            <div class="col-md-6">
                                <label for="CNS" class="form-label small">CNS</label>
                                <input class="form-control" type="text" name="cns" id="CNS" placeholder="000 0000 0000 0000" minlength="15" maxlength="18" data-mask="### #### #### ####" inputmode="numeric">
                            </div>

                            <div class="col-12">
                                <label for="nome" class="form-label small">Nome completo *</label>
                                <input class="form-control" type="text" id="nome" name="nome" required placeholder="Nome completo" data-titlecase="true" minlength="3">
                                <div class="invalid-feedback">Por favor, informe o nome completo (mínimo 3 caracteres).</div>
                            </div>

                            <div class="col-md-4">
                                <label for="data_nascimento" class="form-label small">Data de nascimento *</label>
                                <input class="form-control" type="date" name="data_nascimento" required>
                            </div>

                            <div class="col-md-4">
                                <label for="sexo" class="form-label small">Sexo *</label>
                                <select name="sexo" class="form-select" required>
                                    <option value="" disabled selected>Selecione</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Feminino</option>
                                </select>
                                <div class="invalid-feedback">Por favor, selecione o sexo.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="raca_cor" class="form-label small">Raça/Cor *</label>
                                <select name="raca_cor" class="form-select" required>
                                    <option value="" disabled selected>Selecione</option>
                                    <option value="BRANCA">Branca</option>
                                    <option value="PRETA">Preta</option>
                                    <option value="PARDA">Parda</option>
                                    <option value="AMARELA">Amarela</option>
                                    <option value="INDIGENA">Indígena</option>
                                </select>
                                <div class="invalid-feedback">Por favor, selecione a raça/cor.</div>
                            </div>

                            <div class="col-12">
                                <label for="nome_mae" class="form-label small">Nome da mãe *</label>
                                <input class="form-control" type="text" name="nome_mae" required placeholder="Nome completo da mãe" minlength="3" data-titlecase="true">
                                <div class="invalid-feedback">Por favor, informe o nome completo da mãe.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="RG" class="form-label small">RG</label>
                                <input class="form-control" type="text" name="rg" id="RG" placeholder="0000000000-0" inputmode="numeric" data-numeric="true">
                            </div>

                            <div class="col-md-6">
                                <label for="uf_rg" class="form-label small">UF do RG</label>
                                <select name="uf_rg" class="form-select">
                                    <option value="" disabled selected>UF</option>
                                    <?php include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php'); ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="ssp" class="form-label small">Órgão Expedidor</label>
                                <input class="form-control" type="text" id="ssp" name="ssp" placeholder="ex: SSP/MA" data-callback="alteraSSP">
                            </div>
                        </div>
                    </div>

                    <!-- CONTATOS -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Contatos</strong>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="TELEFONE_CEL" class="form-label small">Telefone celular</label>
                                <input class="form-control" type="text" name="telefone_celular" id="TELEFONE_CEL" placeholder="(00)90000-0000" data-mask="(##)#####-####">
                            </div>

                            <div class="col-md-4">
                                <label for="TELEFONE_RES" class="form-label small">Telefone residencial</label>
                                <input class="form-control" type="text" name="telefone_residencial" id="TELEFONE_RES" placeholder="(00) 0000-0000" data-mask="(##) ####-####">
                            </div>

                            <div class="col-md-4">
                                <label for="TELEFONE_CONT" class="form-label small">Telefone de contato</label>
                                <input class="form-control" type="text" name="telefone_contato" id="TELEFONE_CONT" placeholder="(00)90000-0000" data-mask="(##)#####-####">
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label small">E-mail</label>
                                <input class="form-control" type="email" name="email" placeholder="email@exemplo.com">
                            </div>
                        </div>
                    </div>

                    <!-- RESIDÊNCIA -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Residência</strong>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="estado" class="form-label small">UF</label>
                                <select name="estado" class="form-select">
                                    <option value="" disabled selected>UF</option>
                                    <?php
                                        $selectedUf = isset($estado) ? (string)$estado : '';
                                        include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                                        unset($selectedUf);
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label for="MUNICIPIO" class="form-label small">Município</label>
                                <input class="form-control" type="text" name="municipio" id="MUNICIPIO" placeholder="Nome do município" data-titlecase="true">
                            </div>

                            <div class="col-md-3">
                                <label for="CEP" class="form-label small">CEP</label>
                                <input class="form-control" type="text" name="cep" id="CEP" placeholder="00000-000" data-mask="#####-###" inputmode="numeric">
                            </div>

                            <div class="col-md-9">
                                <label for="BAIRRO" class="form-label small">Bairro</label>
                                <input class="form-control" type="text" name="bairro" id="BAIRRO" placeholder="Nome do bairro" data-titlecase="true">
                            </div>

                            <div class="col-12">
                                <label for="LOGRADOURO" class="form-label small">Logradouro</label>
                                <input class="form-control" type="text" name="endereco" id="LOGRADOURO" placeholder="Nome do logradouro" data-titlecase="true">
                            </div>

                            <div class="col-md-4">
                                <label for="NUMERO" class="form-label small">Número</label>
                                <input class="form-control" type="text" name="numero" id="NUMERO" placeholder="Número" inputmode="numeric" data-numeric="true">
                            </div>

                            <div class="col-md-8">
                                <label for="complemento" class="form-label small">Complemento</label>
                                <input class="form-control" type="text" name="complemento" placeholder="Apt, casa, etc.">
                            </div>

                            <div class="col-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="lgpd_consent" id="lgpd_consent" required>
                                    <label class="form-check-label small" for="lgpd_consent">Consentimento LGPD *</label>
                                    <div class="form-text">Concordo com o tratamento dos meus dados pessoais conforme a LGPD.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--------------------------------------------------------->


      












            
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
                </div>

            </form>

        </div>
    </div>
</div>
<!----------------------------fim da da janela modal----------------------------->
