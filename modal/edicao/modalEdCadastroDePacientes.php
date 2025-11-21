

<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use BioUBS\Conexao;

if(isset($_GET['id'])): //----só sugirá o conteúdo se vier um ID
  
          $id = $_GET['id'];

        //-----------criterios de consulta--------------
          $camposIdBio = "*";
          $tabelaIdBio = "cadastro_paciente";
        //----------------------------------------------

        //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
          require_once('../../querys/ConsultaPorId.php');
            
            //-------buscando dados na tabela------------------   
            while($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)){

                $nomePaciente = $rowsId['NOME'];
                $data_nascimento = $rowsId['DATA_NASCIMENTO'];
                $cpf = $rowsId['CPF'];
                $cns = $rowsId['CNS'];
                $nome_mae = $rowsId['NOME_MAE'];
                $telefone_celular = $rowsId['TELEFONE_CELULAR'];
                $telefone_contato = isset($rowsId['TELEFONE_CONTATO']) ? $rowsId['TELEFONE_CONTATO'] : null;
                $cep = $rowsId['CEP'];
                $municipio = $rowsId['MUNICIPIO'];
                $estado = $rowsId['ESTADO'];
                $endereco = $rowsId['ENDERECO'];
                $numero = $rowsId['NUMERO'];
                $complemento = $rowsId['COMPLEMENTO'];
                $bairro = $rowsId['BAIRRO'];
                $sexo = $rowsId['SEXO'];
                $raca_cor = $rowsId['RACA_COR'];
                $telefone_residencial = $rowsId['TELEFONE_RESIDENCIAL'];
                $email = $rowsId['EMAIL'];
                $rg = $rowsId['RG'];
                $ssp = $rowsId['SSP'];
                $lgpd_consent = $rowsId['LGPD_CONSENT'];
                      
                      //---------uf do RG salva no cadastro----------------------
                        $cd_uf_rg = $rowsId['UF_RG'];

                          if($cd_uf_rg !== null && $cd_uf_rg !== '') {
                              require_once __DIR__ . '/../../querys/ConsultaUnidadeFederativaPorID.php';

                              while($rowsUfRg = $buscaUfId->fetch(PDO::FETCH_ASSOC)){
                                $uf_rg = $rowsUfRg['DS_UF_SIGLA'];
                                $cd_uf_rg = $rowsUfRg['CD_UF'];
                                $uf_nome_rg = $rowsUfRg['DS_UF_NOME'];
                              }
                          }
                      //---------------------------------------------

            }    
 ?> 



<!------------------janela modal-------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">
              <h4 class="modal-title">Editando cadastro de Paciente</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-------------------------------------------------------->

            
            <form id="ed" name="ed" action="" method="post"><!----formulario-------->   
                  
                  <!----------IMPORTANTE!!!!!!!!!!--------> 
                  <!-----------------IMPUT COM ID DO REGISTRO A SER ALTERADO----------> 
                      <input type="hidden" name="id" value="<?=$id?>">
                  <!------------------------------------------------------------------>
                  
            <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body">

                <!-- DADOS PESSOAIS -->
                <div style="background-color: #d1ecf1; color: #0c5460;" class="border p-2 mb-3 text-start fw-bold">
                    Dados pessoais
                </div>
                
                <!-- CPF e CNS -->
                <div class="row mb-3">
                    <div class="col-md-6">
                           <label for="cpf" class="form-label">CPF</label>
                           <input class="form-control" type="text" name="cpf" id="CPF" placeholder="000.000.000-00" 
                               data-mask="###.###.###-##" value="<?= htmlspecialchars(function_exists('format_cpf') ? format_cpf($cpf ?? '') : $cpf) ?>" 
                               minlength="14" maxlength="14">
                    </div>
                    <div class="col-md-6">
                           <label for="cns" class="form-label">CNS</label>
                           <input class="form-control" type="text" name="cns" id="CNS" placeholder="000 0000 0000 0000" 
                               minlength="15" maxlength="18" data-mask="### #### #### ####" inputmode="numeric" data-numeric="true"
                               value="<?=$cns?>">
                    </div>
                </div>

                <!-- Nome completo -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nome" class="form-label">Nome completo *</label>
                           <input class="form-control" type="text" id="nome" name="nome" required="required" 
                               placeholder="Nome completo" data-titlecase="true" 
                               value="<?=$nomePaciente?>" minlength="3">
                        <div class="invalid-feedback">
                            Por favor, informe o nome completo (mínimo 3 caracteres).
                        </div>
                    </div>
                </div>

                <!-- Data de Nascimento, Sexo e Raça/Cor -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="data_nascimento" class="form-label">Data de Nascimento *</label>
                        <input class="form-control" type="date" name="data_nascimento" required="required" value="<?=$data_nascimento?>">
                    </div>
                    <div class="col-md-4">
                        <label for="sexo" class="form-label">Sexo *</label>
                        <select name="sexo" class="form-select" required="required">
                            <option value="" disabled>Selecione</option>
                            <option value="M" <?= $sexo == 'M' ? 'selected' : '' ?>>Masculino</option>
                            <option value="F" <?= $sexo == 'F' ? 'selected' : '' ?>>Feminino</option>
                        </select>
                        <div class="invalid-feedback">Por favor, selecione o sexo.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="raca_cor" class="form-label">Raça/Cor *</label>
                        <select name="raca_cor" class="form-select" required="required">
                            <option value="" disabled>Selecione</option>
                            <option value="BRANCA" <?= $raca_cor == 'BRANCA' ? 'selected' : '' ?>>Branca</option>
                            <option value="PRETA" <?= $raca_cor == 'PRETA' ? 'selected' : '' ?>>Preta</option>
                            <option value="PARDA" <?= $raca_cor == 'PARDA' ? 'selected' : '' ?>>Parda</option>
                            <option value="AMARELA" <?= $raca_cor == 'AMARELA' ? 'selected' : '' ?>>Amarela</option>
                            <option value="INDIGENA" <?= $raca_cor == 'INDIGENA' ? 'selected' : '' ?>>Indígena</option>
                        </select>
                        <div class="invalid-feedback">Por favor, selecione a raça/cor.</div>
                    </div>
                </div>

                <!-- Nome da Mãe -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nome_mae" class="form-label">Nome da mãe *</label>
                           <input class="form-control" type="text" name="nome_mae" required="required" 
                               placeholder="Nome completo da mãe" minlength="3" data-titlecase="true"
                               value="<?=$nome_mae?>">
                        <div class="invalid-feedback">
                            Por favor, informe o nome completo da mãe.
                        </div>
                    </div>
                </div>

                <!-- RG e UF do RG -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="rg" class="form-label">RG</label>
                           <input class="form-control" type="text" name="rg" id="RG" placeholder="0000000000-0" inputmode="numeric" data-numeric="true" value="<?=$rg?>">
                    </div>
                    <div class="col-md-6">
                        <label for="uf_rg" class="form-label">UF do RG</label>
                        <select name="uf_rg" class="form-select">
                            <?php if (!isset($cd_uf_rg) || $cd_uf_rg === null || $cd_uf_rg === ''): ?>
                              <option value="" selected>UF</option>
                            <?php else: ?>
                              <option value="">UF</option>
                            <?php endif; ?>
                            <?php 
                              $selectedUf = (isset($cd_uf_rg) && $cd_uf_rg !== null && $cd_uf_rg !== '' && is_numeric($cd_uf_rg)) ? (string)$cd_uf_rg : null;
                              include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php'); 
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Órgão Expedidor -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="ssp" class="form-label">Órgão Expedidor</label>
                           <input class="form-control" type="text" id="ssp" name="ssp" 
                               placeholder="ex: SSP/MA" data-callback="alteraSSP" value="<?=$ssp?>">
                    </div>
                </div>

                <!-- CONTATOS -->
                <div style="background-color: #d1ecf1; color: #0c5460;" class="border p-2 mb-3 text-start fw-bold">
                    Contatos
                </div>

                <!-- Telefones -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="telefone_celular" class="form-label">Telefone celular</label>
                           <input class="form-control" type="text" name="telefone_celular" id="TELEFONE_CEL" 
                               placeholder="(00)90000-0000" data-mask="(##)#####-####"
                               value="<?= htmlspecialchars(function_exists('format_telefone') ? format_telefone($telefone_celular ?? '') : $telefone_celular) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="telefone_residencial" class="form-label">Telefone residencial</label>
                           <input class="form-control" type="text" name="telefone_residencial" id="TELEFONE_RES" 
                               placeholder="(00)0000-0000" data-mask="(##)####-####"
                               value="<?= htmlspecialchars(function_exists('format_telefone') ? format_telefone($telefone_residencial ?? '') : $telefone_residencial) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="telefone_contato" class="form-label">Telefone de contato</label>
                           <input class="form-control" type="text" name="telefone_contato" id="TELEFONE_CONT" 
                               placeholder="(00)90000-0000" data-mask="(##)#####-####"
                               value="<?= isset($telefone_contato) ? htmlspecialchars(function_exists('format_telefone') ? format_telefone($telefone_contato) : $telefone_contato) : '' ?>">
                    </div>
                </div>

                <!-- Email -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="email" name="email" placeholder="email@exemplo.com"
                               value="<?=$email?>">
                    </div>
                </div>

                <!-- RESIDÊNCIA -->
                <div style="background-color: #d1ecf1; color: #0c5460;" class="border p-2 mb-3 text-start fw-bold">
                    Residência
                </div>

                <!-- UF e Município -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="estado" class="form-label">UF</label>
                                                <select name="estado" class="form-select">
                                                        <option value="" disabled>UF</option>
                                                        <?php
                                                            // Trocar require_once por include para permitir segunda carga do script
                                                            $selectedUf = isset($estado) ? (string)$estado : '';
                                                            include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                                                            unset($selectedUf);
                                                        ?>
                                                </select>
                    </div>
                    <div class="col-md-8">
                        <label for="municipio" class="form-label">Município</label>
                           <input class="form-control" type="text" name="municipio" id="MUNICIPIO" 
                               placeholder="Nome do município" data-titlecase="true"
                               value="<?=$municipio?>">
                    </div>
                </div>

                <!-- CEP e Bairro -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="cep" class="form-label">CEP</label>
                           <input class="form-control" type="text" name="cep" id="CEP" placeholder="00000-000" data-mask="#####-###" inputmode="numeric" value="<?=$cep?>">
                    </div>
                    <div class="col-md-9">
                        <label for="bairro" class="form-label">Bairro</label>
                           <input class="form-control" type="text" name="bairro" id="BAIRRO" placeholder="Nome do bairro" data-titlecase="true" value="<?=$bairro?>">
                    </div>
                </div>

                <!-- Logradouro -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="endereco" class="form-label">Logradouro</label>
                           <input class="form-control" type="text" name="endereco" id="LOGRADOURO" 
                               placeholder="Nome do logradouro" data-titlecase="true"
                               value="<?=$endereco?>">
                    </div>
                </div>

                <!-- Número e Complemento -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="numero" class="form-label">Número</label>
                           <input class="form-control" type="text" name="numero" id="NUMERO" placeholder="Número" inputmode="numeric" data-numeric="true" value="<?=$numero?>">
                    </div>
                    <div class="col-md-8">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input class="form-control" type="text" name="complemento" placeholder="Apt, casa, etc."
                               value="<?=$complemento?>">
                    </div>
                </div>

                <!-- Consentimento LGPD -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lgpd_consent" id="lgpd_consent" 
                                   <?= $lgpd_consent ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="lgpd_consent">
                                Consentimento LGPD *
                            </label>
                            <div class="form-text">
                                Concordo com o tratamento dos meus dados pessoais conforme a LGPD.
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!--------------------------------------------------------->


      

            
            <!---------------RODAPÉ DA JANELA---------------------->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="editar" class="btn btn-success">Salvar</button>
            </div>
      
      </form>
            <!----------------------------------------------------->

    
<!----------------------------fim da da janela modal----------------------------->


<?php 
endif;
?>
