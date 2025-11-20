<?php
// Carrega o autoloader do Composer 
require_once __DIR__ . '/../../vendor/autoload.php';
?>

<!----------------------------janela modal--------------------------------------------------------->

<div id="insertProfissional" class="modal fade" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!------aqui consigo mudar o tamanho da modal para modal-lg modal-sm------>
        <!-- contener da janela-->
        <div class="modal-content">

 <form id="cad" name="cad" action="<?= BASE_URL ?>/pages/cadastroDeProfissionais.php" method="post"><!--------------formulário------->

                        <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">
                
                <h5 class="modal-title" id="cadastroModalLabel">Novo cadastro de Profissional</h5>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
                        <!-------------------------------------------------------->








                     <!----------------CORPO DA JANELA------------------------->
                     <div class="modal-body">
            
            <table class="table table-bordered">
                
                <tr class="table-info">
                    <td colspan="4"><strong>Dados pessoais</strong></td>
                </tr>

                <tr>
                    <td colspan="3">Nome Completo:</td>
                    <td>Matrícula:</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input class="form-control" type="text" id="NOME_COMPLETO" name="NOME_COMPLETO" required="required" placeholder="Nome completo do profissional" onkeyup="alteraNomeProfissional()" minlength="3">
                        <div class="invalid-feedback">
                            Por favor, informe o nome completo (mínimo 3 caracteres).
                        </div>
                    </td>
                    <td>
                        <input class="form-control" type="text" id="MATRICULA" name="MATRICULA" placeholder="Matrícula" required="required" pattern="[0-9]+" title="Apenas números são permitidos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                        <input class="form-control" type="text" id="CPF" name="CPF" required="required" placeholder="000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');" minlength="14" maxlength="14">
                        <div class="invalid-feedback">
                            Por favor, informe um CPF válido (11 dígitos).
                        </div>
                    </td>
                    <td>
                        <input class="form-control" type="text" id="CNS_PROFISSIONAL" name="CNS_PROFISSIONAL" placeholder="000 0000 0000 0000" minlength="15" maxlength="18"
                               onkeypress="return mascaras(event, this, '### #### #### ####');"
                               onkeydown="return event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Tab' || /[0-9]/.test(event.key)">
                    </td>
                    <td>
                        <input class="form-control" type="date" id="DATA_NASCIMENTO" name="DATA_NASCIMENTO">
                    </td>
                    <td>
                        <select name="SEXO" id="SEXO" class="form-select" required onchange="if(document.getElementsByName('sexo')[0]){document.getElementsByName('sexo')[0].value=this.value}">
                            <option value="" disabled selected>Selecione</option>
                            <option value="Feminino">Feminino</option>
                            <option value="Masculino">Masculino</option>
                        </select>
                        <input type="hidden" name="sexo" value="">
                        <div class="invalid-feedback">Por favor, selecione o sexo.</div>
                    </td>
                </tr>

                <tr class="table-info">
                    <td colspan="4"><strong>Contatos</strong></td>
                </tr>

                <tr>
                    <td colspan="2">Email:</td>
                    <td colspan="2">Telefone / Celular:</td>
                </tr>
                <tr>
                    <td colspan="2">
                         <input class="form-control" type="email" id="EMAIL" name="EMAIL" placeholder="email@exemplo.com">
                         <div class="invalid-feedback">
                             Por favor, informe um e-mail válido (ex: nome@exemplo.com).
                         </div>
                    </td>
                <td colspan="2">
                    <input class="form-control" type="tel" id="TELEFONE" name="TELEFONE" placeholder="(99) 99999-9999" onkeypress="return mascaras(event, this, '(##)#####-####');" inputmode="numeric">
                </td>
                </tr>

                <tr class="table-info">
                    <td colspan="4"><strong>Dados profissionais e acesso</strong></td>
                </tr>
                
                <tr>
                    <td colspan="4">Perfil de Acesso:</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <select name="PERFIL" id="PERFIL" class="form-control" required>
                            <option value="">Selecione um perfil...</option>
                            <option value="Médico">Médico</option>
                            <option value="Enfermeiro">Enfermeiro</option>
                            <option value="Auxiliar/Técnico Enfermagem">Auxiliar/Técnico Enfermagem</option>
                            <option value="Cirurgião Dentista">Cirurgião Dentista</option>
                            <option value="ASB - Auxiliar Saúde Bucal">ASB - Auxiliar Saúde Bucal</option>
                            <option value="TSB - Técnico Saúde Bucal">TSB - Técnico Saúde Bucal</option>
                            <option value="ACS - Agente Comunitário Saúde">ACS - Agente Comunitário Saúde</option>
                            <option value="ACE - Agente Combate Endemias">ACE - Agente Combate Endemias</option>
                            <option value="Coordenador UBS">Coordenador UBS</option>
                            <option value="Recepção">Recepção</option>
                            <option value="Outro Prof. Nível Superior">Outro Prof. Nível Superior</option>
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
                        <input class="form-control" type="text" id="CONSELHO_CLASSE" name="CONSELHO_CLASSE" placeholder="Ex: CRM, COREN">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="REGISTRO_CONSELHO" name="REGISTRO_CONSELHO" placeholder="Nº 12345" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </td>
                    <td colspan="2">
                        <select name="ESTADO_EMISSOR_CONSELHO" id="ESTADO_EMISSOR_CONSELHO" class="form-control">
                            <option value="" disabled selected>UF</option>
                            <?php
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                        <div class="invalid-feedback">Selecione a UF emissora do conselho.</div>
                    </td>
                </tr>

                <tr class="table-info">
                    <td colspan="4"><strong>Endereço</strong></td>
                </tr>

                <tr>
                    <td>CEP:</td>
                    <td colspan="3">Logradouro (Rua, Av, etc.):</td>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="CEP" name="CEP" placeholder="00000-000" onkeypress="return mascaras(event, this, '#####-###');" inputmode="numeric" maxlength="9">
                    </td>
                    <td colspan="3">
                        <input class="form-control" type="text" id="LOGRADOURO" name="LOGRADOURO" placeholder="Logradouro (Rua, Av, etc.)" oninput="this.value = capitalizeNameWithPrepositions(this.value)">
                    </td>
                </tr>
                <tr>
                    <td>Número:</td>
                    <td>Bairro:</td>
                    <td colspan="2">Complemento:</td>

                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="NUMERO" name="NUMERO" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="BAIRRO" name="BAIRRO" oninput="this.value = capitalizeNameWithPrepositions(this.value)">
                    </td>
                    <td colspan="2">
                        <input class="form-control" type="text" id="COMPLEMENTO" name="COMPLEMENTO" placeholder="Apto, Bloco, Casa, etc.">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">Município:</td>
                    <td>Estado (UF):</td>
                    <td>Ponto de Referência:</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="form-control" type="text" id="MUNICIPIO" name="MUNICIPIO" oninput="this.value = capitalizeNameWithPrepositions(this.value)">
                    </td>
                    <td>
                        <select name="ESTADO_ENDERECO" id="ESTADO_ENDERECO" class="form-control">
                            <option value="" disabled selected>UF</option>
                            <?php
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                        <div class="invalid-feedback">Selecione a UF do endereço.</div>
                    </td>
                    <td>
                         <input class="form-control" type="text" id="PONTO_REFERENCIA" name="PONTO_REFERENCIA">
                    </td>
                </tr>
            </table>

            </div>
        <!--------------------------------------------------------->







        <!---------------RODAPÉ DA JANELA---------------------->
        <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              
              <button type="submit" name="salvar" class="btn btn-success" >Cadastrar</button>
            </div>
      
    </form><!----fim formulario--->
        <!----------------------------------------------------->
    </div>

  </div>
</div>
<!----------------------------fim da da janela modal----------------------------->