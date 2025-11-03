<?php
// Carrega o autoloader do Composer (para a query de UFs)
require_once __DIR__ . '/../../vendor/autoload.php';
?>

<div class="modal fade" id="insertUnidade" tabindex="-1" aria-labelledby="insertUnidadeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="cadUnidadeForm" name="cadUnidade" action="cadastroDeUnidades.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertUnidadeLabel">Cadastrando Nova Unidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr class="table-info">
                            <td colspan="4"><strong>DADOS DA UNIDADE</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">Nome da Unidade:</td>
                            <td>CNPJ:</td>
                            <td>CNES:</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="text" class="form-control" id="cad-nome" name="nome" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="cad-cnpj" name="cnpj" placeholder="00.000.000/0000-00">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="cad-cnes" name="cnes" required placeholder="Apenas números">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">Telefone:</td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="tel" class="form-control" id="cad-telefone" name="telefone" placeholder="(99) 99999-9999">
                            </td>
                            <td colspan="2"></td>
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
                                <input type="text" class="form-control" id="cad-cep" name="cep" placeholder="00000-000">
                            </td>
                            <td colspan="3">
                                <input type="text" class="form-control" id="cad-logradouro" name="logradouro">
                            </td>
                        </tr>

                        <tr>
                            <td>Número:</td>
                            <td>Bairro:</td>
                            <td colspan="2">Complemento:</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="form-control" id="cad-numero" name="numero">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="cad-bairro" name="bairro">
                            </td>
                            <td colspan="2">
                                <input type="text" class="form-control" id="cad-complemento" name="complemento" placeholder="Apto, Bloco, Casa, etc.">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">Município:</td>
                            <td colspan="2">Estado (UF):</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="text" class="form-control" id="cad-municipio" name="municipio">
                            </td>
                            <td colspan="2">
                                <select id="cad-uf" name="uf" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php
                                    try {
                                        require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                                    } catch(Exception $e) {
                                        echo '<option value="" disabled>Erro ao carregar UFs</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="salvar" class="btn btn-success">Salvar Unidade</button>
                </div>
            </form>
        </div>
    </div>
</div>