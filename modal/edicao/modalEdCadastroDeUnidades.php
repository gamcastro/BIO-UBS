<?php
// Caminho corrigido (subindo 2 níveis)
require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['id'])): //----só sugirá o conteúdo se vier um ID
    $id = $_GET['id'];

    //-----------criterios de consulta--------------
    $camposIdBio = "*";
    $tabelaIdBio = "cadastro_unidade";
    //----------------------------------------------

    //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
    require_once(__DIR__ . '/../../querys/ConsultaPorId.php');

    //-------buscando dados na tabela------------------  
    // Inicializa as variáveis para evitar erros caso a consulta não retorne nada
    $nomeUnidade = $cnes = $cnpj = $telefone = $cep = $estado = $municipio = $bairro = $logradouro = $numero = $complemento = '';

    while ($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)) {
        $nomeUnidade = $rowsId['NOME'];
        $cnes = $rowsId['CNES'];
        $cnpj = $rowsId['CNPJ'];
        $telefone = $rowsId['TELEFONE'];
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
        <h5 class="modal-title" id="updateModalLabel">Editando cadastro de Unidade</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <form id="ed" name="ed" action="" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="modal-body">
            <table class="table table-bordered">
                <tr class="table-info">
                    <td colspan="4"><strong>DADOS DA UNIDADE</strong></td>
                </tr>

                <tr>
                    <td colspan="2">Nome :</td>
                    <td>CNPJ :</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome da unidade" value="<?= htmlspecialchars($nomeUnidade ?? '') ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="text" id="cnpj" name="cnpj" required="required" value="<?= htmlspecialchars($cnpj ?? '') ?>">
                    </td>
                </tr>
                <tr>
                    <td>CNES :</td>
                    <td colspan="2">Telefone:</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="cnes" name="cnes" placeholder="Cadastro Nacional de Estabelecimentos de Saúde" value="<?= htmlspecialchars($cnes ?? '') ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" value="<?= htmlspecialchars($telefone ?? '') ?>">
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
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>

                        <script>
                            // Script corrigido (sem o 'DOMContentLoaded')
                            // para executar imediatamente após a injeção do AJAX
                            var select = document.getElementById('estado_endereco');
                            if (select) {
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