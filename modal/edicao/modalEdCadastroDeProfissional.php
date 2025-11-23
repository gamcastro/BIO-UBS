<?php
// Caminho corrigido (subindo 2 níveis)
require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['id'])): //----só surgirá o conteúdo se vier um ID
    $id = $_GET['id'];

    //-----------criterios de consulta--------------
    $camposIdBio = "*";
    $tabelaIdBio = "cadastro_profissional"; // Tabela correta
    //----------------------------------------------

    //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
    require_once(__DIR__ . '/../../querys/ConsultaPorId.php');

    //-------buscando dados na tabela------------------
    // Inicializa as variáveis
    $matricula = $nomeCompleto = $cpf = $cnsProfissional = $dataNascimento = $sexo = $perfil = '';
    $email = $telefone = $conselhoClasse = $registroConselho = $estadoEmissorConselho = '';
    $cep = $estadoEndereco = $municipio = $bairro = $logradouro = $numero = $complemento = $pontoReferencia = '';

    while ($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)) {
        // Dados Pessoais
        $matricula = $rowsId['MATRICULA'];
        $nomeCompleto = $rowsId['NOME_COMPLETO'];
        $cpf = $rowsId['CPF'];
        $cnsProfissional = $rowsId['CNS_PROFISSIONAL'];
        $dataNascimento = $rowsId['DATA_NASCIMENTO'];
        $sexo = $rowsId['SEXO'];
        
        // Dados de Contato
        $email = $rowsId['EMAIL'];
        $telefone = $rowsId['TELEFONE'];

        // Dados Profissionais
        $perfil = $rowsId['PERFIL']; // <-- Esta variável será usada no select
        $conselhoClasse = $rowsId['CONSELHO_CLASSE'];
        $registroConselho = $rowsId['REGISTRO_CONSELHO'];
        $estadoEmissorConselho = $rowsId['ESTADO_EMISSOR_CONSELHO'];

        // Endereço
        $cep = $rowsId['CEP'];
        $estadoEndereco = $rowsId['ESTADO_ENDERECO'];
        $id_municipio = $rowsId['ID_MUNICIPIO'] ?? null;
        $municipioLabel = '';
        if ($id_municipio) {
            try {
                $pdo = BioUBS\Conexao::getConn();
                $stmtMun = $pdo->prepare('SELECT MUNICIPIO FROM ibge_municipios WHERE CD_MUNICIPIO = :id LIMIT 1');
                $stmtMun->bindValue(':id', $id_municipio, PDO::PARAM_INT);
                $stmtMun->execute();
                $municipioLabel = $stmtMun->fetchColumn() ?: '';
            } catch (Exception $e) {
                $municipioLabel = '';
            }
        }
        $bairro = $rowsId['BAIRRO'];
        $logradouro = $rowsId['LOGRADOURO'];
        $numero = $rowsId['NUMERO'];
        $complemento = $rowsId['COMPLEMENTO'];
        $pontoReferencia = $rowsId['PONTO_REFERENCIA'];
    }
?>
 
    <!------------------janela modal-------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
    <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title mb-0" id="updateModalLabel">Editar profissional</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
    </div>
            <!-------------------------------------------------------->


    <form id="ed" name="ed" action="" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <!----------------CORPO DA JANELA------------------------->
        <div class="modal-body p-3">

            <div class="p-3 mb-3 rounded bg-light">
                <strong class="d-block mb-2 text-primary">Dados pessoais</strong>

                <div class="row g-3">
                    <div class="col-md-9">
                        <label for="NOME_COMPLETO" class="form-label small">Nome completo</label>
                        <input class="form-control" type="text" id="NOME_COMPLETO" name="NOME_COMPLETO" required placeholder="Nome completo do profissional" value="<?= htmlspecialchars($nomeCompleto ?? '') ?>" data-altera-nome-profissional="true" minlength="3">
                        <div class="invalid-feedback">Por favor, informe o nome completo (mínimo 3 caracteres).</div>
                    </div>
                    <div class="col-md-3">
                        <label for="MATRICULA" class="form-label small">Matrícula</label>
                        <input class="form-control" type="text" id="MATRICULA" name="MATRICULA" placeholder="Matrícula" value="<?= htmlspecialchars($matricula ?? '') ?>" required pattern="[0-9]+" title="Apenas números são permitidos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <div class="invalid-feedback">Informe a matrícula (apenas números).</div>
                    </div>

                    <div class="col-md-3">
                        <label for="CPF" class="form-label small">CPF</label>
                        <input class="form-control" type="text" id="CPF" name="CPF" required placeholder="000.000.000-00" data-mask="###.###.###-##" value="<?= htmlspecialchars(function_exists('format_cpf') ? format_cpf($cpf ?? '') : $cpf) ?>" minlength="14" maxlength="14">
                        <div class="invalid-feedback">Por favor, informe um CPF válido (11 dígitos).</div>
                    </div>
                    <div class="col-md-3">
                        <label for="CNS_PROFISSIONAL" class="form-label small">CNS</label>
                        <input class="form-control" type="text" id="CNS_PROFISSIONAL" name="CNS_PROFISSIONAL" placeholder="000 0000 0000 0000" minlength="15" maxlength="18" data-mask="### #### #### ####" inputmode="numeric" value="<?= htmlspecialchars($cnsProfissional ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="DATA_NASCIMENTO" class="form-label small">Data de nascimento</label>
                        <input class="form-control" type="date" id="DATA_NASCIMENTO" name="DATA_NASCIMENTO" value="<?= htmlspecialchars($dataNascimento ?? '') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="SEXO" class="form-label small">Sexo</label>
                        <?php
                        $sexoLabel = '';
                        if (isset($sexo)) {
                            $sLower = mb_strtolower(trim((string)$sexo), 'UTF-8');
                            if ($sLower === 'm' || mb_stripos($sLower, 'mascul') !== false) { $sexoLabel = 'Masculino'; }
                            elseif ($sLower === 'f' || mb_stripos($sLower, 'femin') !== false) { $sexoLabel = 'Feminino'; }
                        }
                        ?>
                        <select name="SEXO" id="SEXO" class="form-select" data-sync="sexo">
                            <option value="" <?= $sexoLabel === '' ? 'selected' : '' ?>>Selecione</option>
                            <option value="Feminino" <?= $sexoLabel === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                            <option value="Masculino" <?= $sexoLabel === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                        </select>
                        <input type="hidden" name="sexo" value="<?= htmlspecialchars($sexoLabel) ?>">
                    </div>
                </div>
            </div>

            <div class="p-3 mb-3 rounded bg-light">
                <strong class="d-block mb-2 text-primary">Contatos</strong>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="EMAIL" class="form-label small">Email</label>
                        <input class="form-control" type="email" id="EMAIL" name="EMAIL" placeholder="email@exemplo.com" value="<?= htmlspecialchars($email ?? '') ?>">
                        <div class="invalid-feedback">Por favor, informe um e-mail válido (ex: nome@exemplo.com).</div>
                    </div>
                    <div class="col-md-6">
                        <label for="TELEFONE" class="form-label small">Telefone / Celular</label>
                        <?php
                        $telefone_display = $telefone ?? '';
                        $digits = preg_replace('/\D+/', '', (string)$telefone_display);
                        if (function_exists('format_telefone')) {
                            $telefone_display = format_telefone($digits);
                        } else {
                            if (strlen($digits) === 10) {
                                $telefone_display = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1)$2-$3', $digits);
                            } elseif (strlen($digits) === 11) {
                                $telefone_display = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1)$2-$3', $digits);
                            } else {
                                $telefone_display = $digits;
                            }
                        }
                        ?>
                        <input class="form-control" type="tel" id="TELEFONE" name="TELEFONE" placeholder="(99) 99999-9999" value="<?= htmlspecialchars($telefone_display ?? '') ?>" data-mask="(##)#####-####" inputmode="numeric">
                    </div>
                </div>
            </div>

            <div class="p-3 mb-3 rounded bg-light">
                <strong class="d-block mb-2 text-primary">Dados profissionais e acesso</strong>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="PERFIL" class="form-label small">Perfil de Acesso</label>
                        <select name="PERFIL" id="PERFIL" class="form-select" required>
                            <option value="">Selecione um perfil...</option>
                            <option value="Médico" <?= (strcasecmp($perfil, 'Médico') === 0 || strcasecmp($perfil, 'MÉDICO') === 0) ? 'selected' : '' ?>>Médico</option>
                            <option value="Enfermeiro" <?= (strcasecmp($perfil, 'Enfermeiro') === 0 || strcasecmp($perfil, 'ENFERMEIRO') === 0) ? 'selected' : '' ?>>Enfermeiro</option>
                            <option value="Auxiliar/Técnico Enfermagem" <?= (strcasecmp($perfil, 'Auxiliar/Técnico Enfermagem') === 0 || strcasecmp($perfil, 'AUXILIAR/TÉCNICO ENFERMAGEM') === 0) ? 'selected' : '' ?>>Auxiliar/Técnico Enfermagem</option>
                            <option value="Cirurgião Dentista" <?= (strcasecmp($perfil, 'Cirurgião Dentista') === 0 || strcasecmp($perfil, 'CIRURGIÃO DENTISTA') === 0) ? 'selected' : '' ?>>Cirurgião Dentista</option>
                            <option value="ASB - Auxiliar Saúde Bucal" <?= (strcasecmp($perfil, 'ASB - Auxiliar Saúde Bucal') === 0 || strcasecmp($perfil, 'ASB - AUXILIAR SAÚDE BUCAL') === 0) ? 'selected' : '' ?>>ASB - Auxiliar Saúde Bucal</option>
                            <option value="TSB - Técnico Saúde Bucal" <?= (strcasecmp($perfil, 'TSB - Técnico Saúde Bucal') === 0 || strcasecmp($perfil, 'TSB - TÉCNICO SAÚDE BUCAL') === 0) ? 'selected' : '' ?>>TSB - Técnico Saúde Bucal</option>
                            <option value="ACS - Agente Comunitário Saúde" <?= (strcasecmp($perfil, 'ACS - Agente Comunitário Saúde') === 0 || strcasecmp($perfil, 'ACS - AGENTE COMUNITÁRIO SAÚDE') === 0) ? 'selected' : '' ?>>ACS - Agente Comunitário Saúde</option>
                            <option value="ACE - Agente Combate Endemias" <?= (strcasecmp($perfil, 'ACE - Agente Combate Endemias') === 0 || strcasecmp($perfil, 'ACE - AGENTE COMBATE ENDEMIAS') === 0) ? 'selected' : '' ?>>ACE - Agente Combate Endemias</option>
                            <option value="Coordenador UBS" <?= (strcasecmp($perfil, 'Coordenador UBS') === 0 || strcasecmp($perfil, 'COORDENADOR UBS') === 0) ? 'selected' : '' ?>>Coordenador UBS</option>
                            <option value="Recepção" <?= (strcasecmp($perfil, 'Recepção') === 0 || strcasecmp($perfil, 'RECEPÇÃO') === 0) ? 'selected' : '' ?>>Recepção</option>
                            <option value="Outro Prof. Nível Superior" <?= (strcasecmp($perfil, 'Outro Prof. Nível Superior') === 0 || strcasecmp($perfil, 'OUTRO PROF. NÍVEL SUPERIOR') === 0) ? 'selected' : '' ?>>Outro Prof. Nível Superior</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="CONSELHO_CLASSE" class="form-label small">Conselho</label>
                        <input class="form-control" type="text" id="CONSELHO_CLASSE" name="CONSELHO_CLASSE" placeholder="Ex: CRM, COREN" value="<?= htmlspecialchars($conselhoClasse ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="REGISTRO_CONSELHO" class="form-label small">Nº do Registro</label>
                        <input class="form-control" type="text" id="REGISTRO_CONSELHO" name="REGISTRO_CONSELHO" placeholder="Nº 12345" value="<?= htmlspecialchars($registroConselho ?? '') ?>" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="col-md-4">
                        <label for="ESTADO_EMISSOR_CONSELHO" class="form-label small">UF do Conselho</label>
                        <select name="ESTADO_EMISSOR_CONSELHO" id="ESTADO_EMISSOR_CONSELHO" class="form-select">
                            <?php $selectedUf = ($estadoEmissorConselho !== null && $estadoEmissorConselho !== '' && is_numeric($estadoEmissorConselho)) ? (string)$estadoEmissorConselho : null; ?>
                            <?php if ($selectedUf === null): ?>
                                <option value="" disabled selected>UF</option>
                            <?php else: ?>
                                <option value="" disabled>UF</option>
                            <?php endif; ?>
                            <?php require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php'); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-3 mb-3 rounded bg-light">
                <strong class="d-block mb-2 text-primary">Endereço</strong>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="CEP" class="form-label small">CEP</label>
                        <input class="form-control" type="text" id="CEP" name="CEP" placeholder="00000-000" value="<?= htmlspecialchars($cep ?? '') ?>" data-mask="#####-###" inputmode="numeric" maxlength="9">
                    </div>
                    <div class="col-md-9">
                        <label for="LOGRADOURO" class="form-label small">Logradouro</label>
                        <input class="form-control" type="text" id="LOGRADOURO" name="LOGRADOURO" value="<?= htmlspecialchars($logradouro ?? '') ?>" data-titlecase="true">
                    </div>

                    <div class="col-md-2">
                        <label for="NUMERO" class="form-label small">Número</label>
                        <input class="form-control" type="text" id="NUMERO" name="NUMERO" value="<?= htmlspecialchars($numero ?? '') ?>" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="col-md-4">
                        <label for="BAIRRO" class="form-label small">Bairro</label>
                        <input class="form-control" type="text" id="BAIRRO" name="BAIRRO" value="<?= htmlspecialchars($bairro ?? '') ?>" data-titlecase="true">
                    </div>
                    <div class="col-md-6">
                        <label for="COMPLEMENTO" class="form-label small">Complemento</label>
                        <input class="form-control" type="text" id="COMPLEMENTO" name="COMPLEMENTO" placeholder="Apto, Bloco, Casa, etc." value="<?= htmlspecialchars($complemento ?? '') ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="ESTADO_ENDERECO" class="form-label small">Estado (UF)</label>
                        <select name="ESTADO_ENDERECO" id="ESTADO_ENDERECO" class="form-select">
                            <?php $selectedUf = ($estadoEndereco !== null && $estadoEndereco !== '' && is_numeric($estadoEndereco)) ? (string)$estadoEndereco : null; ?>
                            <?php if ($selectedUf === null): ?>
                                <option value="" disabled selected>UF</option>
                            <?php else: ?>
                                <option value="" disabled>UF</option>
                            <?php endif; ?>
                            <?php require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php'); ?>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label for="MUNICIPIO" class="form-label small">Município</label>
                        <input class="form-control" type="text" id="MUNICIPIO" name="municipio" placeholder="Nome do município" value="<?= htmlspecialchars($id_municipio ?? '') ?>" data-pref-label="<?= htmlspecialchars($municipioLabel) ?>" />
                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------------------------------->

        <!---------------RODAPÉ DA JANELA---------------------->
        <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" name="editar" class="btn btn-success">Salvar</button>
        </div>
    </form>
            <!----------------------------------------------------->
    
<!----------------------------fim da da janela modal----------------------------->
<?php
endif;
?>