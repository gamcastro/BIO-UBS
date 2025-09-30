<!----------------------------janela modal--------------------------------------------------------->

<div id="insertUnidade" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg"><!------aqui consigo mudar o tamanho da modal para modal-lg modal-sm------>
        <!-- contener da janela-->
        <div class="modal-content">

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button><!------botao fechar------>

                <h4 class="modal-title">Cadastrando Nova Unidade</h4>
            </div>

            <!-------------------------------------------------------->
            <form id="cad" name="cad" action="" method="post"> <!--------------formulário------->
                <!----------------CORPO DA JANELA------------------------->

                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr class="info">
                            <td colspan="4"><strong>DADOS DA UNIDADE</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">Nome :</td>
                            <td colspan="2">CNPJ:</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome  da unidade">
                            </td>
                            <td colspan="2">
                                <input class="form-control" type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00"> 
                            </td>

                        </tr>
                        <tr>

                            <td>CNES:</td>
                            <td colspan="2">Telefone:</td>
                        </tr>
                        <tr>

                            <td>
                                <input class="form-control" type="text" id="cnes" name="cnes" required="required" placeholder="Cadastro Nacional de Estabelecimentos de Saúde">
                            </td>
                            <td colspan="2">
                                <input class="form-control" type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999">
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
                                <input class="form-control" type="text" id="cep" name="cep" placeholder="00000-000">
                            </td>
                            <td colspan="3">
                                <input class="form-control" type="text" id="logradouro" name="logradouro">
                            </td>
                        </tr>
                        <tr>
                            <td>Número:</td>
                            <td>Bairro:</td>
                            <td colspan="2">Complemento:</td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="numero" name="numero">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="bairro" name="bairro">
                            </td>
                            <td colspan="2">
                                <input class="form-control" type="text" id="complemento" name="complemento" placeholder="Apto, Bloco, Sala, etc.">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Município:</td>
                            <td colspan="2">Estado (UF):</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input class="form-control" type="text" id="municipio" name="municipio">
                            </td>
                            <td colspan="2">
                                <select name="uf" class="form-control">
                                    <option value="">UF</option>
                                    <?php
                                    require_once('querys/ConsultaUnidadeFederativaSelect.php');
                                    ?>
                                </select>
                            </td>
                        </tr>

                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--------------------------------------------------------->