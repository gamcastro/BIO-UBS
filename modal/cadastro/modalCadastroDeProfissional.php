<?php
// Carrega o autoloader do Composer 
require_once __DIR__ . '/../../vendor/autoload.php';
?>

<!----------------------------janela modal--------------------------------------------------------->

<div id="insertProfissional" class="modal fade" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-sm">

            <form id="cad" name="cad" action="<?= BASE_URL ?>/pages/cadastroDeProfissionais.php" method="post">

                <!-------------CABEÇALHO DA JANELA------------------------->
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title mb-0" id="cadastroModalLabel">Novo profissional</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <!-------------------------------------------------------->



                <!----------------CORPO DA JANELA------------------------->
                <div class="modal-body p-3">

                    <!-- DADOS PESSOAIS -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Dados pessoais</strong>

                        <div class="row g-3">
                            <div class="col-md-9">
                                <label for="NOME_COMPLETO" class="form-label small">Nome Completo *</label>
                                <input class="form-control" type="text" id="NOME_COMPLETO" name="NOME_COMPLETO" required placeholder="Nome completo do profissional" data-altera-nome-profissional="true" minlength="3">
                                <div class="invalid-feedback">Por favor, informe o nome completo (mínimo 3 caracteres).</div>
                            </div>
                            <div class="col-md-3">
                                <label for="MATRICULA" class="form-label small">Matrícula</label>
                                <input class="form-control" type="text" id="MATRICULA" name="MATRICULA" placeholder="Matrícula" required pattern="[0-9]+" title="Apenas números são permitidos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <div class="invalid-feedback">Informe a matrícula (apenas números).</div>
                            </div>

                            <div class="col-md-3">
                                <label for="CPF" class="form-label small">CPF</label>
                                <input class="form-control" type="text" id="CPF" name="CPF" required placeholder="000.000.000-00" data-mask="###.###.###-##" minlength="14" maxlength="14">
                                <div class="invalid-feedback">Por favor, informe um CPF válido (11 dígitos).</div>
                            </div>

                            <div class="col-md-3">
                                <label for="CNS_PROFISSIONAL" class="form-label small">CNS</label>
                                <input class="form-control" type="text" id="CNS_PROFISSIONAL" name="CNS_PROFISSIONAL" placeholder="000 0000 0000 0000" minlength="15" maxlength="18" data-mask="### #### #### ####">
                            </div>

                            <div class="col-md-3">
                                <label for="DATA_NASCIMENTO" class="form-label small">Data de Nascimento</label>
                                <input class="form-control" type="date" id="DATA_NASCIMENTO" name="DATA_NASCIMENTO" required>
                            </div>

                            <div class="col-md-3">
                                <label for="SEXO" class="form-label small">Sexo</label>
                                <select name="SEXO" id="SEXO" class="form-select" required data-sync="sexo">
                                    <option value="" disabled selected>Selecione</option>
                                    <option value="Feminino">Feminino</option>
                                    <option value="Masculino">Masculino</option>
                                </select>
                                <input type="hidden" name="sexo" value="">
                                <div class="invalid-feedback">Por favor, selecione o sexo.</div>
                            </div>
                        </div>
                    </div>

                    <!-- CONTATOS -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Contatos</strong>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="EMAIL" class="form-label small">E-mail</label>
                                <input class="form-control" type="email" id="EMAIL" name="EMAIL" placeholder="email@exemplo.com">
                                <div class="invalid-feedback">Por favor, informe um e-mail válido (ex: nome@exemplo.com).</div>
                            </div>
                            <div class="col-md-6">
                                <label for="TELEFONE" class="form-label small">Telefone / Celular</label>
                                <input class="form-control" type="tel" id="TELEFONE" name="TELEFONE" placeholder="(99) 99999-9999" data-mask="(##)#####-####" inputmode="numeric">
                            </div>
                        </div>
                    </div>

                    <!-- DADOS PROFISSIONAIS -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Dados profissionais e acesso</strong>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="PERFIL" class="form-label small">Perfil de Acesso</label>
                                <select name="PERFIL" id="PERFIL" class="form-select" required>
                                    <option value="">Selecione um perfil...</option>
                                    <option value="Médico">Médico</option>
                                    <option value="Enfermeiro">Enfermeiro</option>
                                    <option value="Auxiliar/Técnico Enfermagem">Auxiliar/Técnico Enfermagem</option>
                                    <option value="Cirurgião Dentista">Cirurgião Dentista</option>
                                    <option value="ASB - Auxiliar Saúde Bucal">ASB - Auxiliar Saúde Bucal</option>
                                    <option value="TSB - Técnico Saúde Bucal">TSB - Técnico Saúde Bucal</option>
                                    <option value="ACS - Agente Comunitário Saúde">ACS - Agente Comunitário Saúde</option>
                                    <option value="ACE - Agente Combate Endemias">ACE - Agente Combate Endemias</option>
                                    <option value="Coordenador UBS">Coordenador UBS</option>
                                    <option value="Recepção">Recepção</option>
                                    <option value="Outro Prof. Nível Superior">Outro Prof. Nível Superior</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="CONSELHO_CLASSE" class="form-label small">Conselho (CRM, COREN, etc.)</label>
                                <input class="form-control" type="text" id="CONSELHO_CLASSE" name="CONSELHO_CLASSE" placeholder="Ex: CRM, COREN">
                            </div>
                            <div class="col-md-4">
                                <label for="REGISTRO_CONSELHO" class="form-label small">Nº do Registro</label>
                                <input class="form-control" type="text" id="REGISTRO_CONSELHO" name="REGISTRO_CONSELHO" placeholder="Nº 12345" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div class="col-md-4">
                                <label for="ESTADO_EMISSOR_CONSELHO" class="form-label small">UF do Conselho</label>
                                <select name="ESTADO_EMISSOR_CONSELHO" id="ESTADO_EMISSOR_CONSELHO" class="form-select">
                                    <option value="" disabled selected>UF</option>
                                    <?php
                                    require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                                    ?>
                                </select>
                                <div class="invalid-feedback">Selecione a UF emissora do conselho.</div>
                            </div>
                        </div>
                    </div>

                    <!-- ENDEREÇO -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Endereço</strong>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="CEP" class="form-label small">CEP</label>
                                <input class="form-control" type="text" id="CEP" name="CEP" placeholder="00000-000" data-mask="#####-###" inputmode="numeric" maxlength="9">
                            </div>
                            <div class="col-md-9">
                                <label for="LOGRADOURO" class="form-label small">Logradouro</label>
                                <input class="form-control" type="text" id="LOGRADOURO" name="LOGRADOURO" placeholder="Logradouro (Rua, Av, etc.)" data-titlecase="true">
                            </div>

                            <div class="col-md-2">
                                <label for="NUMERO" class="form-label small">Número</label>
                                <input class="form-control" type="text" id="NUMERO" name="NUMERO" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div class="col-md-4">
                                <label for="BAIRRO" class="form-label small">Bairro</label>
                                <input class="form-control" type="text" id="BAIRRO" name="BAIRRO" data-titlecase="true">
                            </div>
                            <div class="col-md-6">
                                <label for="COMPLEMENTO" class="form-label small">Complemento</label>
                                <input class="form-control" type="text" id="COMPLEMENTO" name="COMPLEMENTO" placeholder="Apto, Bloco, Casa, etc.">
                            </div>

                            <div class="col-md-3">
                                <label for="ESTADO_ENDERECO" class="form-label small">Estado (UF)</label>
                                <select name="ESTADO_ENDERECO" id="ESTADO_ENDERECO" class="form-select">
                                    <option value="" disabled selected>UF</option>
                                    <?php
                                    require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                                    ?>
                                </select>
                                <div class="invalid-feedback">Selecione a UF do endereço.</div>
                            </div>
                            <div class="col-md-9">
                                <label for="MUNICIPIO" class="form-label small">Município</label>
                                <input class="form-control" type="text" id="MUNICIPIO" name="municipio" placeholder="Selecione o município" />
                            </div>
                        </div>
                    </div>

                </div>
            <!--------------------------------------------------------->



                                <!---------------RODAPÉ DA JANELA---------------------->
                                <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="salvar" class="btn btn-success">Cadastrar</button>
                                </div>

                        </form>
                        <!----------------------------------------------------->
    </div>

  </div>
</div>
<!----------------------------fim da da janela modal----------------------------->