<?php
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

    <!----------------------------janela modal--------------------------------------------------------->

    <!-------------CABEÇALHO DA JANELA------------------------->
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
                </tr>
                <tr class="table-info">
                    <td colspan="4"><strong>ENDEREÇO</strong></td>
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
                            document.addEventListener('DOMContentLoaded', function() {
                                        var select = document.getElementById('estado_endereco');
                                        if (select) select.value = "<?= $estado ?>";
                            });
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