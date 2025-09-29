<!----------------------------janela modal--------------------------------------------------------->

<div id="insertProfissional" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg"> <div class="modal-content">
<!----------------------------janela modal--------------------------------------------------------->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button> <!------botao fechar------>
                 <h4 class="modal-title">Cadastrando Novo Profissional</h4>
            </div>

                <!-------------------------------------------------------->

            <form id="cad" name="cad" action="" method="post"> <!--------------formulário------->

                <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body">
                    <table class="table table-bordered">
                        <tr class="info">
                            <td colspan="4"><strong>DADOS PESSOAIS</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3">Nome Completo:</td>
                            <td>Data de Nascimento:</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input class="form-control" type="text" id="nome_completo" name="nome_completo" required="required" placeholder="Nome completo do profissional">
                            </td>
                            <td>
                                <input class="form-control" type="date" id="data_nascimento" name="data_nascimento" required="required">
                            </td>
                        </tr>
                        <tr>
                            <td>CPF:</td>
                            <td>CNS do Profissional:</td>
                            <td colspan="2">Sexo:</td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="cpf" name="cpf" placeholder="000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="cns_profissional" name="cns_profissional" placeholder="Nº do Cartão Nacional de Saúde">
                            </td>
                            <td colspan="2">
                                <select id="sexo" name="sexo" class="form-control" required="required">
                                    <option value="">Selecione...</option>
                                    <option value="Feminino">Feminino</option>
                                    <option value="Masculino">Masculino</option>
                                    
                                </select>
                            </td>
                        </tr>

                        <tr class="info">
                            <td colspan="4"><strong>CONTATO</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">E-mail:</td>
                            <td colspan="2">Telefone:</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input class="form-control" type="email" id="email" name="email" placeholder="exemplo@email.com">
                            </td>
                            <td colspan="2">
                                <input class="form-control" type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999">
                            </td>
                        </tr>
                        
                        <tr class="info">
                            <td colspan="4"><strong>CONSELHO DE CLASSE</strong></td>
                        </tr>
                        <tr>
                            <td>Conselho:</td>
                            <td>Nº do Registro:</td>
                            <td colspan="2">Estado Emissor:</td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="conselho_classe" name="conselho_classe" placeholder="Ex: CRM, COREN, etc.">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="registro_conselho" name="registro_conselho" placeholder="Ex: 123456">
                            </td>
                            <td colspan="2">
                                <select name="estado_emissor_conselho" id="estado_emissor_conselho" class="form-control">
                                    <option value="">UF</option>
                                    <?php
                                      require('querys/ConsultaUnidadeFederativaSelect.php');
                                    ?>
                                </select>
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
                                <input class="form-control" type="text" id="complemento" name="complemento" placeholder="Apto, Bloco, Casa, etc.">
                            </td>
                        </tr>
                        <tr>
                            <td>Município:</td>
                            <td>Estado (UF):</td>
                            <td colspan="2">Ponto de Referência:</td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="municipio" name="municipio">
                            </td>
                            <td colspan="2">
                               <select name="estado_endereco" id="estado_endereco" class="form-control">
                                    <option value="">UF</option>
                                    <?php
                                      require('querys/ConsultaUnidadeFederativaSelect.php');
                                    ?>
                                </select>
                            </td>
                            <td colspan="2">
                                <textarea class="form-control" id="ponto_referencia" name="ponto_referencia" rows="1"></textarea>
                            </td>
                        </tr>

                        <tr class="info">
                            <td colspan="4"><strong>DADOS DE ACESSO</strong></td>
                        </tr>
                        <tr>
                           <td colspan="2">Senha:</td>
                           <td colspan="2">Confirmar Senha:</td>
                        </tr>
                        <tr>
                           <td colspan="2">
                               <input class="form-control" type="password" id="senha_hash" name="senha_hash" required="required">
                           </td>
                           <td colspan="2">
                               <input class="form-control" type="password" id="confirmar_senha" name="confirmar_senha" required="required">
                           </td>
                        </tr>
                    </table>
                </div>

                    <!--------------------------------------------------------->


                 <!---------------RODAPÉ DA JANELA---------------------->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
                </div>
            </form> <!----fim formulario--->

            <!----------------------------------------------------->
          
          </div>
    </div>
    <!----------------------------fim da da janela modal----------------------------->
</div>