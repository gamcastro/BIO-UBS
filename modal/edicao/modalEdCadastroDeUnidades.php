<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader



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
        <button type="button" class="close" data-dismiss="modal">&times;</button> <!------botao fechar------>
        <h4 class="modal-title">Editando cadastro de Unidade</h4>
    </div>
    <!-------------------------------------------------------->

    <form id="ed" name="ed" action="" method="post"> <!--------------formulário------->
        <input type="hidden" name="id" value="<?= $id ?>">
        <!----------------CORPO DA JANELA------------------------->
        <div class="modal-body">
            <table class="table table-bordered">
                <tr class="info">
                    <td colspan="4"><strong>DADOS DA UNIDADE</strong></td>
                </tr>
                <tr>
                    <td colspan="2">Nome :</td>
                    <td>CNPJ :</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome da unidade" value="<?= $nomeUnidade ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="text" id="cnpj" name="cnpj" required="required" value="<?= $cnpj ?>">
                    </td>
                </tr>
                <tr>
                    <td>CNES :</td>
                    <td colspan="2">Telefone:</td>

                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="cnes" name="cnes" placeholder="Cadastro Nacional de Estabelecimentos de Saúde" value="<?= $cnes ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" value="<?= $telefone ?>">
                    </td>
                </tr>
                <tr class="info">
                    <td colspan="4"><strong>ENDEREÇO</strong></td>     
                </tr>                   

                <tr>
                    <td>CEP:</td>
                    <td colspan="3">Logradouro (Rua, Av, etc.):</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="cep" name="cep" placeholder="00000-000" value="<?= $cep ?>">
                    </td>
                    <td colspan="3">
                        <input class="form-control" type="text" id="logradouro" name="logradouro" value="<?= $logradouro ?>">
                    </td>
                </tr>
                <tr>
                    <td>Número:</td>
                    <td>Bairro:</td>
                    <td colspan="2">Complemento:</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="numero" name="numero" value="<?= $numero ?>">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="bairro" name="bairro" value="<?= $bairro ?>">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="text" id="complemento" name="complemento" placeholder="Apto, Bloco, Casa, etc." value="<?= $complemento ?>">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Município:</td>
                    <td>Estado (UF):</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="form-control" type="text" id="municipio" name="municipio" value="<?= $municipio ?>">
                    </td>
                    <td>
                        <select name="estado_endereco" id="estado_endereco" class="form-control">
                            <option value="">UF</option>
                            <?php
                            require('../../querys/ConsultaUnidadeFederativaSelect.php');
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
        <!--------------------------------------------------------->

        <!---------------RODAPÉ DA JANELA---------------------->
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" name="editar" class="btn btn-success">Salvar</button>
        </div>
    </form> <!----fim formulario--->

    <!----------------------------------------------------->
    </div>
    </div>
    <!----------------------------fim da da janela modal----------------------------->
    </div>
<?php
endif;
?>