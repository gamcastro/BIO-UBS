<?php
require_once('../../class/Conexao.php');

if(isset($_GET['id'])): //----só sugirá o conteúdo se vier um ID
  
    $id = $_GET['id'];

    //-----------criterios de consulta--------------
    $camposIdBio = "*";
    $tabelaIdBio = "cadastro_profissional";
    //----------------------------------------------

    //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
    require_once('../../querys/ConsultaPorId.php');
        
    //-------buscando dados na tabela------------------   
    while($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)){
        $nomeProfissional = $rowsId['NOME_COMPLETO'];
        $cpf = $rowsId['CPF'];
        $cns_profissional = $rowsId['CNS_PROFISSIONAL'];
        $data_nascimento = $rowsId['DATA_NASCIMENTO'];
        $sexo = $rowsId['SEXO'];
        $email = $rowsId['EMAIL'];
        $telefone = $rowsId['TELEFONE'];
        $conselho = $rowsId['CONSELHO_CLASSE'];
        $registro_conselho = $rowsId['REGISTRO_CONSELHO'];
        $estado_conselho = $rowsId['ESTADO_EMISSOR_CONSELHO'];
        $cep = $rowsId['CEP'];
        $estado_endereco = $rowsId['ESTADO_ENDERECO'];
        $municipio = $rowsId['MUNICIPIO'];
        $bairro = $rowsId['BAIRRO'];
        $logradouro = $rowsId['LOGRADOURO'];
        $numero = $rowsId['NUMERO'];
        $complemento = $rowsId['COMPLEMENTO'];
        $ponto_referencia = $rowsId['PONTO_REFERENCIA'];
        $senha = $rowsId['SENHA_HASH'];
    }    
?> 

<!----------------------------janela modal--------------------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button> <!------botao fechar------>
                <h4 class="modal-title">Editando cadastro de Profissional</h4>
            </div>
            <!-------------------------------------------------------->

            <form id="ed" name="ed" action="" method="post"> <!--------------formulário------->
                <input type="hidden" name="id" value="<?=$id?>">
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
                                <input class="form-control" type="text" id="nome_completo" name="nome_completo" required="required" placeholder="Nome completo do profissional" value="<?=$nomeProfissional?>">
                            </td>
                            <td>
                                <input class="form-control" type="date" id="data_nascimento" name="data_nascimento" required="required" value="<?=$data_nascimento?>">
                            </td>
                        </tr>
                        <tr>
                            <td>CPF:</td>
                            <td>CNS do Profissional:</td>
                            <td colspan="2">Sexo:</td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="cpf" name="cpf" placeholder="000.000.000-00" value="<?=$cpf?>">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="cns_profissional" name="cns_profissional" placeholder="Nº do Cartão Nacional de Saúde" value="<?=$cns_profissional?>">
                            </td>
                            <td colspan="2">
                                <select id="sexo" name="sexo" class="form-control" required="required">
                                    <option value="">Selecione...</option>
                                    <option value="Feminino" <?=($sexo=='Feminino'?'selected':'')?>>Feminino</option>
                                    <option value="Masculino" <?=($sexo=='Masculino'?'selected':'')?>>Masculino</option>
                                    <option value="Outro" <?=($sexo=='Outro'?'selected':'')?>>Outro</option>
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
                                <input class="form-control" type="email" id="email" name="email" placeholder="exemplo@email.com" value="<?=$email?>">
                            </td>
                            <td colspan="2">
                                <input class="form-control" type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" value="<?=$telefone?>">
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
                                <input class="form-control" type="text" id="conselho_classe" name="conselho_classe" placeholder="Ex: CRM, COREN, etc." value="<?=$conselho?>">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="registro_conselho" name="registro_conselho" placeholder="Ex: 123456" value="<?=$registro_conselho?>">
                            </td>
                            <td colspan="2">
                                <select name="estado_emissor_conselho" id="estado_emissor_conselho" class="form-control">
                                    <option value="">UF</option>
                                    <?php
                                      require('../../querys/ConsultaUnidadeFederativaSelect.php');
                                    ?>
                                </select>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var select = document.getElementById('estado_emissor_conselho');
                                        if(select) select.value = "<?=$estado_conselho?>";
                                    });
                                </script>
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
                                <input class="form-control" type="text" id="cep" name="cep" placeholder="00000-000" value="<?=$cep?>">
                            </td>
                            <td colspan="3">
                                <input class="form-control" type="text" id="logradouro" name="logradouro" value="<?=$logradouro?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Número:</td>
                            <td>Bairro:</td>
                            <td colspan="2">Complemento:</td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="numero" name="numero" value="<?=$numero?>">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="bairro" name="bairro" value="<?=$bairro?>">
                            </td>
                            <td colspan="2">
                                <input class="form-control" type="text" id="complemento" name="complemento" placeholder="Apto, Bloco, Casa, etc." value="<?=$complemento?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Município:</td>
                            <td>Estado (UF):</td>
                            <td colspan="2">Ponto de Referência:</td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="municipio" name="municipio" value="<?=$municipio?>">
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
                                        if(select) select.value = "<?=$estado_endereco?>";
                                    });
                                </script>
                            </td>
                            <td colspan="2">
                                <textarea class="form-control" id="ponto_referencia" name="ponto_referencia" rows="1"><?=$ponto_referencia?></textarea>
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
                               <input class="form-control" type="password" id="senha_hash" name="senha_hash" required="required" value="<?=$senha?>">
                           </td>
                           <td colspan="2">
                               <input class="form-control" type="password" id="confirmar_senha" name="confirmar_senha" required="required" value="<?=$senha?>">
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
