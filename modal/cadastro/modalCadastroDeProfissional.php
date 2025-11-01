<?php
// Carrega o autoloader do Composer (para a query de UFs)
require_once __DIR__ . '/../../vendor/autoload.php';
?>

<div id="insertProfissional" class="modal fade" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

 <form id="cad" name="cad" action="cadastroDeProfissionais.php" method="post">

            <div class="modal-header">
                
                <h5 class="modal-title" id="cadastroModalLabel">Novo cadastro de Profissional</h5>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
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
                        <input class="form-control" type="text" id="NOME_COMPLETO" name="NOME_COMPLETO" required="required" placeholder="Nome completo do profissional">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="MATRICULA" name="MATRICULA" placeholder="Matrícula">
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
                        <input class="form-control" type="text" id="CPF" name="CPF" required="required" placeholder="Somente números">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="CNS_PROFISSIONAL" name="CNS_PROFISSIONAL" placeholder="Nº CNS">
                    </td>
                    <td>
                        <input class="form-control" type="date" id="DATA_NASCIMENTO" name="DATA_NASCIMENTO">
                    </td>
                    <td>
                        <select name="SEXO" id="SEXO" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Feminino">Feminino</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Outro">Outro</option>
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
                         <input class="form-control" type="email" id="EMAIL" name="EMAIL" placeholder="email@exemplo.com">
                    </td>
                    <td colspan="2">
                         <input class="form-control" type="tel" id="TELEFONE" name="TELEFONE" placeholder="(99) 99999-9999">
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
                            <option value="MÉDICO">MÉDICO</option>
                            <option value="ENFERMEIRO">ENFERMEIRO</option>
                            <option value="AUXILIAR/TÉCNICO ENFERMAGEM">AUXILIAR/TÉCNICO ENFERMAGEM</option>
                            <option value="CIRURGIÃO DENTISTA">CIRURGIÃO DENTISTA</option>
                            <option value="ASB - AUXILIAR SAÚDE BUCAL">ASB - AUXILIAR SAÚDE BUCAL</option>
                            <option value="TSB - TÉCNICO SAÚDE BUCAL">TSB - TÉCNICO SAÚDE BUCAL</option>
                            <option value="ACS - AGENTE COMUNITÁRIO SAÚDE">ACS - AGENTE COMUNITÁRIO SAÚDE</option>
                            <option value="ACE - AGENTE COMBATE ENDEMIAS">ACE - AGENTE COMBATE ENDEMIAS</option>
                            <option value="COORDENADOR UBS">COORDENADOR UBS</option>
                            <option value="RECEPÇÃO">RECEPÇÃO</option>
                            <option value="OUTRO PROF. NÍVEL SUPERIOR">OUTRO PROF. NÍVEL SUPERIOR</option>
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
                        <input class="form-control" type="text" id="REGISTRO_CONSELHO" name="REGISTRO_CONSELHO" placeholder="Nº 12345">
                    </td>
                    <td colspan="2">
                        <select name="ESTADO_EMISSOR_CONSELHO" id="ESTADO_EMISSOR_CONSELHO" class="form-control">
                            <option value="">UF</option>
                            <?php
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
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
                        <input class="form-control" type="text" id="CEP" name="CEP" placeholder="00000-000">
                    </td>
                    <td colspan="3">
                        <input class="form-control" type="text" id="LOGRADOURO" name="LOGRADOURO">
                    </td>
                </tr>
                <tr>
                    <td>Número:</td>
                    <td>Bairro:</td>
                    <td colspan="2">Complemento:</td>

                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="NUMERO" name="NUMERO">
                    </td>
                    <td>
                        <input class="form-control" type="text" id="BAIRRO" name="BAIRRO">
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
                        <input class="form-control" type="text" id="MUNICIPIO" name="MUNICIPIO">
                    </td>
                    <td>
                        <select name="ESTADO_ENDERECO" id="ESTADO_ENDERECO" class="form-control">
                            <option value="">UF</option>
                            <?php
                            require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                    </td>
                    <td>
                         <input class="form-control" type="text" id="PONTO_REFERENCIA" name="PONTO_REFERENCIA">
                    </td>
                </tr>
            </table>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              
              <button type="submit" name="salvar" class="btn btn-success" >Cadastrar</button>
            </div>
      
      </form></div>

  </div>
</div>