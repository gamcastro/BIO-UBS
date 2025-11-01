<?php
// Caminho corrigido (subindo 2 níveis)
require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['id'])): //----só surgirá o conteúdo se vier um ID
    $id = $_GET['id'];

    //-----------criterios de consulta--------------
    $camposIdBio = "*";
    $tabelaIdBio = "cadastro_profissional"; // <-- MUDANÇA: Tabela de profissionais
    //----------------------------------------------

    //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
    // Este script genérico busca na $tabelaIdBio pelo $id
    require_once(__DIR__ . '/../../querys/ConsultaPorId.php');

    //-------buscando dados na tabela------------------
    // Inicializa as variáveis para evitar erros caso a consulta não retorne nada
    $nome = $cpf = $rg = $dataNascimento = $tipoProfissional = $documentoConselho = $especialidade = '';
    $telefone = $cep = $estado = $municipio = $bairro = $logradouro = $numero = $complemento = '';

    while ($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)) {
        // Dados Pessoais
        $nome = $rowsId['NOME'];
        $cpf = $rowsId['CPF'];
        $rg = $rowsId['RG'];
        $dataNascimento = $rowsId['DATA_NASCIMENTO']; // Assumindo nome da coluna
        $telefone = $rowsId['TELEFONE']; // Assumindo que você adicionará este campo

        // Dados Profissionais
        $tipoProfissional = $rowsId['TIPO_PROFISSIONAL']; // Assumindo
        $documentoConselho = $rowsId['DOCUMENTO_CONSELHO']; // Assumindo
        $especialidade = $rowsId['ESPECIALIDADE']; // Assumindo

        // Endereço (vamos supor que os nomes das colunas são os mesmos da Unidade)
        $cep = $rowsId['CEP'];
        $estado = $rowsId['ESTADO'];
        $municipio = $rowsId['MUNICIPIO'];
        $bairro = $rowsId['BAIRRO'];
        $logradouro = $rowsId['LOGRADOURO'];
        $numero = $rowsId['NUMERO'];
        $complemento = $rowsId['COMPLEMENTO'];
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
                    <td colspan="4"><strong>DADOS DO PROFISSIONAL</strong></td>
                </tr>

                <tr>
                    <td colspan="4">Nome Completo:</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome completo do profissional" value="<?= htmlspecialchars($nome ?? '') ?>">
                    </td>
                </tr>

                <tr>
                    <td>CPF:</td>
                    <td>RG:</td>
                    <td colspan="2">Data de Nascimento:</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="cpf" name="cpf" required="required" placeholder="Somente números" value="<?= htmlspecialchars($cpf ?? '') ?>">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="rg" name="rg" placeholder="Somente números" value="<?= htmlspecialchars($rg ?? '') ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($dataNascimento ?? '') ?>">
                    </td>
                </tr>

                <tr>
                    <td>Telefone / Celular:</td>
                    <td colspan="3">Tipo de Profissional:</td>
                </tr>
                <tr>
                    <td>
                         <input class="form-control" type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" value="<?= htmlspecialchars($telefone ?? '') ?>">
                    </td>
                    <td colspan="3">
                        <select name="tipo_profissional" id="tipo_profissional" class="form-control" required>
                            <option value="">Selecione o tipo</option>
                            <option value="Atendente" <?= ($tipoProfissional == 'Atendente') ? 'selected' : '' ?>>Atendente</option>
                            <option value="Técnico de Enfermagem" <?= ($tipoProfissional == 'Técnico de Enfermagem') ? 'selected' : '' ?>>Técnico de Enfermagem</option>
                            <option value="Enfermeiro" <?= ($tipoProfissional == 'Enfermeiro') ? 'selected' : '' ?>>Enfermeiro(a)</option>
                            <option value="Médico" <?= ($tipoProfissional == 'Médico') ? 'selected' : '' ?>>Médico(a)</option>
                            <option value="Administrador" <?= ($tipoProfissional == 'Administrador') ? 'selected' : '' ?>>Administrador(a)</option>
                        </select>
                    </td>
                </tr>

                 <tr>
                    <td>Documento do Conselho (CRM, COREN, etc.):</td>
                    <td colspan="3">Especialidade (se aplicável):</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="documento_conselho" name="documento_conselho" placeholder="CRM/COREN/etc." value="<?= htmlspecialchars($documentoConselho ?? '') ?>">
                    </td>
                    <td colspan="3">
                        <input class="form-control" type="text" id="especialidade" name="especialidade" placeholder="Ex: Clínico Geral, Pediatra" value="<?= htmlspecialchars($especialidade ?? '') ?>">
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
                        <input class="form-control" type="text" id="cep" name="cep" placeholder="00000-000" value="<?= htmlspecialchars($cep ?? '') ?>">
                    </td>
                    <td colspan="3">
                        <input class="form-control" type="text" id="logradouro" name="logradouro" value="<?= htmlspecialchars($logradouro ?? '') ?>">
                    </td>
                </tr>
                <tr>
                    <td>Número:</td>
                    <td>Bairro:</td>
                    <td colspan="2">Complemento:</td>

                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="numero" name="numero" value="<?= htmlspecialchars($numero ?? '') ?>">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($bairro ?? '') ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="text" id="complemento" name="complemento" placeholder="Apto, Bloco, Casa, etc." value="<?= htmlspecialchars($complemento ?? '') ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">Município:</td>
                    <td>Estado (UF):</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="form-control" type="text" id="municipio" name="municipio" value="<?= htmlspecialchars($municipio ?? '') ?>">
                    </td>
                    <td>
                        <select name="estado_endereco" id="estado_endereco" class="form-control">
                            <option value="">UF</option>
                            <?php
                            // Reutiliza a consulta de estados
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>

                        <script>
                            // Script corrigido (sem o 'DOMContentLoaded')
                            // para executar imediatamente após a injeção do AJAX
                            var select = document.getElementById('estado_endereco');
                            if (select) {
                                // Define o valor selecionado com base no que veio do banco
                                select.value = "<?= $estado ?>"; 
                            }
                        </script>
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