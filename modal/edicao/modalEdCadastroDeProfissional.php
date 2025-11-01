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

    <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Editando cadastro de Profissional</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <form id="ed" name="ed" action="" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

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
                        <input class="form-control" type="text" id="NOME_COMPLETO" name="NOME_COMPLETO" required="required" placeholder="Nome completo do profissional" value="<?= htmlspecialchars($nomeCompleto ?? '') ?>">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="MATRICULA" name="MATRICULA" placeholder="Matrícula" value="<?= htmlspecialchars($matricula ?? '') ?>">
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
                        <input class="form-control" type="text" id="CPF" name="CPF" required="required" placeholder="Somente números" value="<?= htmlspecialchars($cpf ?? '') ?>">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="CNS_PROFISSIONAL" name="CNS_PROFISSIONAL" placeholder="Nº CNS" value="<?= htmlspecialchars($cnsProfissional ?? '') ?>">
                    </td>
                    <td>
                        <input class="form-control" type="date" id="DATA_NASCIMENTO" name="DATA_NASCIMENTO" value="<?= htmlspecialchars($dataNascimento ?? '') ?>">
                    </td>
                    <td>
                        <select name="SEXO" id="SEXO" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Feminino" <?= ($sexo == 'Feminino') ? 'selected' : '' ?>>Feminino</option>
                            <option value="Masculino" <?= ($sexo == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                            <option value="Outro" <?= ($sexo == 'Outro') ? 'selected' : '' ?>>Outro</option>
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
                    </td>
                    <td colspan="2">
                         <input class="form-control" type="tel" id="TELEFONE" name="TELEFONE" placeholder="(99) 99999-9999" value="<?= htmlspecialchars($telefone ?? '') ?>">
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
                            <option value="MÉDICO" <?= ($perfil == 'MÉDICO') ? 'selected' : '' ?>>MÉDICO</option>
                            <option value="ENFERMEIRO" <?= ($perfil == 'ENFERMEIRO') ? 'selected' : '' ?>>ENFERMEIRO</option>
                            <option value="AUXILIAR/TÉCNICO ENFERMAGEM" <?= ($perfil == 'AUXILIAR/TÉCNICO ENFERMAGEM') ? 'selected' : '' ?>>AUXILIAR/TÉCNICO ENFERMAGEM</option>
                            <option value="CIRURGIÃO DENTISTA" <?= ($perfil == 'CIRURGIÃO DENTISTA') ? 'selected' : '' ?>>CIRURGIÃO DENTISTA</option>
                            <option value="ASB - AUXILIAR SAÚDE BUCAL" <?= ($perfil == 'ASB - AUXILIAR SAÚDE BUCAL') ? 'selected' : '' ?>>ASB - AUXILIAR SAÚDE BUCAL</option>
                            <option value="TSB - TÉCNICO SAÚDE BUCAL" <?= ($perfil == 'TSB - TÉCNICO SAÚDE BUCAL') ? 'selected' : '' ?>>TSB - TÉCNICO SAÚDE BUCAL</option>
                            <option value="ACS - AGENTE COMUNITÁRIO SAÚDE" <?= ($perfil == 'ACS - AGENTE COMUNITÁRIO SAÚDE') ? 'selected' : '' ?>>ACS - AGENTE COMUNITÁRIO SAÚDE</option>
                            <option value="ACE - AGENTE COMBATE ENDEMIAS" <?= ($perfil == 'ACE - AGENTE COMBATE ENDEMIAS') ? 'selected' : '' ?>>ACE - AGENTE COMBATE ENDEMIAS</option>
                            <option value="COORDENADOR UBS" <?= ($perfil == 'COORDENADOR UBS') ? 'selected' : '' ?>>COORDENADOR UBS</option>
                            <option value="RECEPÇÃO" <?= ($perfil == 'RECEPÇÃO') ? 'selected' : '' ?>>RECEPÇÃO</option>
                            <option value="OUTRO PROF. NÍVEL SUPERIOR" <?= ($perfil == 'OUTRO PROF. NÍVEL SUPERIOR') ? 'selected' : '' ?>>OUTRO PROF. NÍVEL SUPERIOR</option>
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
                        <input class="form-control" type="text" id="REGISTRO_CONSELHO" name="REGISTRO_CONSELHO" placeholder="Nº 12345" value="<?= htmlspecialchars($registroConselho ?? '') ?>">
                    </td>
                    <td colspan="2">
                        <select name="ESTADO_EMISSOR_CONSELHO" id="ESTADO_EMISSOR_CONSELHO" class="form-control">
                            <option value="">UF</option>
                            <?php
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                        <script>
                            var selectConselho = document.getElementById('ESTADO_EMISSOR_CONSELHO');
                            if (selectConselho) {
                                selectConselho.value = "<?= $estadoEmissorConselho ?>"; 
                            }
                        </script>
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
                        <input class="form-control" type="text" id="CEP" name="CEP" placeholder="00000-000" value="<?= htmlspecialchars($cep ?? '') ?>">
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
                        <input class="form-control" type="text" id="NUMERO" name="NUMERO" value="<?= htmlspecialchars($numero ?? '') ?>">
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
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>

                        <script>
                            var selectEndereco = document.getElementById('ESTADO_ENDERECO');
                            if (selectEndereco) {
                                selectEndereco.value = "<?= $estadoEndereco ?>"; 
                            }
                        </script>
                    </td>
                    <td>
                         <input class="form-control" type="text" id="PONTO_REFERENCIA" name="PONTO_REFERENCIA" value="<?= htmlspecialchars($pontoReferencia ?? '') ?>">
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" name="editar" class="btn btn-success">Salvar</button>
        </div>
    </form>
<?php
endif;
?>