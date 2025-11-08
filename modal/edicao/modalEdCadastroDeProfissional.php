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
        $municipio = $rowsId['MUNICIPIO'];
        $bairro = $rowsId['BAIRRO'];
        $logradouro = $rowsId['LOGRADOURO'];
        $numero = $rowsId['NUMERO'];
        $complemento = $rowsId['COMPLEMENTO'];
        $pontoReferencia = $rowsId['PONTO_REFERENCIA'];
    }
?>
 
    <!------------------janela modal-------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
    <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Editando cadastro de Profissional</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
            <!-------------------------------------------------------->

    <!-- Script: preenche selects de UF quando o modal for exibido (tentativas repetidas) -->
    <script>
    (function(){
        function setUFValueIfPossible(selectId, ufValue) {
            var selectUF = document.getElementById(selectId);
            if (!selectUF || !ufValue) return false;
            // Garante que o valor seja numérico
            ufValue = String(ufValue).replace(/\D/g, '');
            if (ufValue === '') ufValue = '21';
            // Adiciona zero à esquerda se necessário
            ufValue = ufValue.padStart(2, '0');
            // Verifica se a opção existe e seta o valor
            var optionExists = Array.from(selectUF.options).some(function(opt){ return opt.value === ufValue; });
            if (optionExists) {
                selectUF.value = ufValue;
                selectUF.dispatchEvent(new Event('change'));
                return true;
            }
            return false;
        }

        var estadoEmissorConselho = "<?= htmlspecialchars($estadoEmissorConselho) ?>";
        var estadoEndereco = "<?= htmlspecialchars($estadoEndereco) ?>";

        function trySetAll() {
            var a = setUFValueIfPossible('ESTADO_EMISSOR_CONSELHO', estadoEmissorConselho);
            var b = setUFValueIfPossible('ESTADO_ENDERECO', estadoEndereco);
            return a && b;
        }

        function startAttempts(limit, interval) {
            limit = limit || 20; // número de tentativas
            interval = interval || 100; // ms
            var tries = 0;
            if (trySetAll()) return; // já setado
            var id = setInterval(function(){
                tries++;
                if (trySetAll() || tries >= limit) {
                    clearInterval(id);
                }
            }, interval);
        }

        // Tenta quando o modal for exibido
        document.addEventListener('shown.bs.modal', function(ev){
            try {
                // se o modal contém nossos selects, começa as tentativas
                if (ev.target && ev.target.querySelector && (ev.target.querySelector('#ESTADO_EMISSOR_CONSELHO') || ev.target.querySelector('#ESTADO_ENDERECO'))) {
                    startAttempts(20, 100);
                }
            } catch (e) {
                // silencioso
            }
        }, true);

        // Também tenta imediatamente (caso o modal já esteja no DOM e visível)
        startAttempts(20, 100);
    })();
    </script>

    <form id="ed" name="ed" action="" method="post"><!----formulario-------->   
        <input type="hidden" name="id" value="<?= $id ?>">

        <!----------------CORPO DA JANELA------------------------->
        <div class="modal-body">
            <table class="table table-bordered">
                
                <tr class="table-info">
                    <td colspan="4"><strong>DADOS PESSOAIS</strong></td>
                </tr>

                <tr>
                    <td colspan="3">Nome Completo:</td>
                    <td>Matrícula:</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input class="form-control" type="text" id="NOME_COMPLETO" name="NOME_COMPLETO" required="required" placeholder="Nome completo do profissional" value="<?= htmlspecialchars($nomeCompleto ?? '') ?>" onkeyup="alteraNomeProfissional()" minlength="3">
                        <div class="invalid-feedback">
                            Por favor, informe o nome completo (mínimo 3 caracteres).
                        </div>
                    </td>
                    <td>
                        <input class="form-control" type="text" id="MATRICULA" name="MATRICULA" placeholder="Matrícula" value="<?= htmlspecialchars($matricula ?? '') ?>" required="required" pattern="[0-9]+" title="Apenas números são permitidos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <div class="invalid-feedback">
                            Informe a matrícula (apenas números).
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>CPF:</td>
                    <td>CNS:</td>
                    <td>Data de Nascimento:</td>
                    <td>Sexo:</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="CPF" name="CPF" required="required" placeholder="000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');" value="<?= htmlspecialchars(function_exists('format_cpf') ? format_cpf($cpf ?? '') : $cpf) ?>" minlength="14" maxlength="14">
                        <div class="invalid-feedback">
                            Por favor, informe um CPF válido (11 dígitos).
                        </div>
                    </td>
                    <td>
                        <input class="form-control" type="text" id="CNS_PROFISSIONAL" name="CNS_PROFISSIONAL" placeholder="Nº CNS" value="<?= htmlspecialchars($cnsProfissional ?? '') ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15">
                    </td>
                    <td>
                        <input class="form-control" type="date" id="DATA_NASCIMENTO" name="DATA_NASCIMENTO" value="<?= htmlspecialchars($dataNascimento ?? '') ?>">
                    </td>
                    <td>
                        <select name="SEXO" id="SEXO" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Feminino" <?= ($sexo == 'Feminino') ? 'selected' : '' ?>>Feminino</option>
                            <option value="Masculino" <?= ($sexo == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                        </select>
                    </td>
                </tr>

                <tr class="table-info">
                    <td colspan="4"><strong>DADOS DE CONTATO</strong></td>
                </tr>

                <tr>
                    <td colspan="2">Email:</td>
                    <td colspan="2">Telefone / Celular:</td>
                </tr>
                <tr>
                    <td colspan="2">
                         <input class="form-control" type="email" id="EMAIL" name="EMAIL" placeholder="email@exemplo.com" value="<?= htmlspecialchars($email ?? '') ?>">
                         <div class="invalid-feedback">
                             Por favor, informe um e-mail válido (ex: nome@exemplo.com).
                         </div>
                    </td>
                <td colspan="2">
                    <input class="form-control" type="tel" id="TELEFONE" name="TELEFONE" placeholder="(99) 99999-9999" value="<?= htmlspecialchars($telefone ?? '') ?>" onkeypress="return mascaras(event, this, '(##) #####-####');" inputmode="numeric">
                </td>
                </tr>

                <tr class="table-info">
                    <td colspan="4"><strong>DADOS PROFISSIONAIS E ACESSO</strong></td>
                </tr>
                
                <tr>
                    <td colspan="4">Perfil de Acesso:</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <select name="PERFIL" id="PERFIL" class="form-control" required>
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
                    </td>
                </tr>

                 <tr>
                    <td>Conselho (CRM, COREN, etc.):</td>
                    <td>Nº do Registro:</td>
                    <td colspan="2">UF do Conselho:</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="CONSELHO_CLASSE" name="CONSELHO_CLASSE" placeholder="Ex: CRM, COREN" value="<?= htmlspecialchars($conselhoClasse ?? '') ?>">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="REGISTRO_CONSELHO" name="REGISTRO_CONSELHO" placeholder="Nº 12345" value="<?= htmlspecialchars($registroConselho ?? '') ?>" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </td>
                    <td colspan="2">
                        <select name="ESTADO_EMISSOR_CONSELHO" id="ESTADO_EMISSOR_CONSELHO" class="form-control">
                            <option value="">UF</option>
                            <?php
                            // Define o valor selecionado antes de incluir o script
                            $selectedUf = $estadoEmissorConselho ?? '';
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                    </td>
                </tr>

                <tr class="table-info">
                    <td colspan="4"><strong>ENDEREÇO</strong></td>
                </tr>

                <tr>
                    <td>CEP:</td>
                    <td colspan="3">Logradouro (Rua, Av, etc.):</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="CEP" name="CEP" placeholder="00000-000" value="<?= htmlspecialchars($cep ?? '') ?>" onkeypress="return mascaras(event, this, '#####-###');" inputmode="numeric" maxlength="9">
                    </td>
                    <td colspan="3">
                        <input class="form-control" type="text" id="LOGRADOURO" name="LOGRADOURO" value="<?= htmlspecialchars($logradouro ?? '') ?>">
                    </td>
                </tr>
                <tr>
                    <td>Número:</td>
                    <td>Bairro:</td>
                    <td colspan="2">Complemento:</td>

                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="NUMERO" name="NUMERO" value="<?= htmlspecialchars($numero ?? '') ?>" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="BAIRRO" name="BAIRRO" value="<?= htmlspecialchars($bairro ?? '') ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="text" id="COMPLEMENTO" name="COMPLEMENTO" placeholder="Apto, Bloco, Casa, etc." value="<?= htmlspecialchars($complemento ?? '') ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">Município:</td>
                    <td>Estado (UF):</td>
                    <td>Ponto de Referência:</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="form-control" type="text" id="MUNICIPIO" name="MUNICIPIO" value="<?= htmlspecialchars($municipio ?? '') ?>">
                    </td>
                    <td>
                        <select name="ESTADO_ENDERECO" id="ESTADO_ENDERECO" class="form-control">
                            <option value="">UF</option>
                            <?php
                            // Define o valor selecionado antes de incluir o script
                            $selectedUf = $estadoEndereco ?? '';
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                    </td>
                    <td>
                         <input class="form-control" type="text" id="PONTO_REFERENCIA" name="PONTO_REFERENCIA" value="<?= htmlspecialchars($pontoReferencia ?? '') ?>">
                </tr>
            </table>
        </div>
        <!--------------------------------------------------------->

        <!---------------RODAPÉ DA JANELA---------------------->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" name="editar" class="btn btn-success">Salvar</button>
        </div>
    </form>
            <!----------------------------------------------------->
    
<!----------------------------fim da da janela modal----------------------------->
<?php
endif;
?>