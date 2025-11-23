
<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use BioUBS\Conexao;

if (isset($_GET['id'])): //----só surgirá o conteúdo se vier um ID
        $id = $_GET['id'];

        //-----------criterios de consulta--------------
        $camposIdBio = "*";
        $tabelaIdBio = "cadastro_paciente";
        //----------------------------------------------

        //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
        require_once(__DIR__ . '/../../querys/ConsultaPorId.php');

        //-------buscando dados na tabela------------------
        while ($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)) {
                $nomePaciente = $rowsId['NOME'];
                $data_nascimento = $rowsId['DATA_NASCIMENTO'];
                $cpf = $rowsId['CPF'];
                $cns = $rowsId['CNS'];
                $nome_mae = $rowsId['NOME_MAE'];
                $telefone_celular = $rowsId['TELEFONE_CELULAR'];
                $telefone_contato = $rowsId['TELEFONE_CONTATO'] ?? null;
                $cep = $rowsId['CEP'];
                $id_municipio = $rowsId['ID_MUNICIPIO'];
                $municipio = '';
                if ($id_municipio) {
                    $pdo = BioUBS\Conexao::getConn();
                    $stmtMun = $pdo->prepare('SELECT MUNICIPIO FROM ibge_municipios WHERE CD_MUNICIPIO = :id LIMIT 1');
                    $stmtMun->bindValue(':id', $id_municipio, PDO::PARAM_INT);
                    $stmtMun->execute();
                    $municipio = $stmtMun->fetchColumn() ?: '';
                }
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

                if ($cd_uf_rg !== null && $cd_uf_rg !== '') {
                        require_once __DIR__ . '/../../querys/ConsultaUnidadeFederativaPorID.php';

                        while ($rowsUfRg = $buscaUfId->fetch(PDO::FETCH_ASSOC)) {
                                $uf_rg = $rowsUfRg['DS_UF_SIGLA'];
                                $cd_uf_rg = $rowsUfRg['CD_UF'];
                                $uf_nome_rg = $rowsUfRg['DS_UF_NOME'];
                        }
                }
                //---------------------------------------------
        }
?>

<!------------------janela modal-------------------------------------------->

<div class="modal-header bg-primary text-white border-0">
    <h5 class="modal-title mb-0">Editar paciente</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>

<form id="ed" name="ed" action="" method="post">
    <input type="hidden" name="id" value="<?=$id?>">

    <!----------------CORPO DA JANELA------------------------->
    <div class="modal-body p-3">

        <!-- DADOS PESSOAIS -->
        <div class="p-3 mb-3 rounded bg-light">
            <strong class="d-block mb-2 text-primary">Dados pessoais</strong>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="CPF" class="form-label small">CPF</label>
                    <input class="form-control" type="text" name="cpf" id="CPF" placeholder="000.000.000-00" data-mask="###.###.###-##" value="<?= htmlspecialchars(function_exists('format_cpf') ? format_cpf($cpf ?? '') : $cpf) ?>" minlength="14" maxlength="14">
                </div>

                <div class="col-md-6">
                    <label for="CNS" class="form-label small">CNS</label>
                    <input class="form-control" type="text" name="cns" id="CNS" placeholder="000 0000 0000 0000" minlength="15" maxlength="18" data-mask="### #### #### ####" inputmode="numeric" data-numeric="true" value="<?=$cns?>">
                </div>

                <div class="col-12">
                    <label for="nome" class="form-label small">Nome completo *</label>
                    <input class="form-control" type="text" id="nome" name="nome" required placeholder="Nome completo" data-titlecase="true" value="<?=$nomePaciente?>" minlength="3">
                    <div class="invalid-feedback">Por favor, informe o nome completo (mínimo 3 caracteres).</div>
                </div>

                <div class="col-md-4">
                    <label for="data_nascimento" class="form-label small">Data de Nascimento *</label>
                    <input class="form-control" type="date" name="data_nascimento" required value="<?=$data_nascimento?>">
                </div>

                <div class="col-md-4">
                    <label for="sexo" class="form-label small">Sexo *</label>
                    <select name="sexo" class="form-select" required>
                        <option value="" disabled>Selecione</option>
                        <option value="M" <?= $sexo == 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= $sexo == 'F' ? 'selected' : '' ?>>Feminino</option>
                    </select>
                    <div class="invalid-feedback">Por favor, selecione o sexo.</div>
                </div>

                <div class="col-md-4">
                    <label for="raca_cor" class="form-label small">Raça/Cor *</label>
                    <select name="raca_cor" class="form-select" required>
                        <option value="" disabled>Selecione</option>
                        <option value="BRANCA" <?= $raca_cor == 'BRANCA' ? 'selected' : '' ?>>Branca</option>
                        <option value="PRETA" <?= $raca_cor == 'PRETA' ? 'selected' : '' ?>>Preta</option>
                        <option value="PARDA" <?= $raca_cor == 'PARDA' ? 'selected' : '' ?>>Parda</option>
                        <option value="AMARELA" <?= $raca_cor == 'AMARELA' ? 'selected' : '' ?>>Amarela</option>
                        <option value="INDIGENA" <?= $raca_cor == 'INDIGENA' ? 'selected' : '' ?>>Indígena</option>
                    </select>
                    <div class="invalid-feedback">Por favor, selecione a raça/cor.</div>
                </div>

                <div class="col-12">
                    <label for="nome_mae" class="form-label small">Nome da mãe *</label>
                    <input class="form-control" type="text" name="nome_mae" required placeholder="Nome completo da mãe" minlength="3" data-titlecase="true" value="<?=$nome_mae?>">
                    <div class="invalid-feedback">Por favor, informe o nome completo da mãe.</div>
                </div>

                <div class="col-md-6">
                    <label for="RG" class="form-label small">RG</label>
                    <input class="form-control" type="text" name="rg" id="RG" placeholder="0000000000-0" inputmode="numeric" data-numeric="true" value="<?=$rg?>">
                </div>

                <div class="col-md-6">
                    <label for="uf_rg" class="form-label small">UF do RG</label>
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

                <div class="col-12">
                    <label for="ssp" class="form-label small">Órgão Expedidor</label>
                    <input class="form-control" type="text" id="ssp" name="ssp" placeholder="ex: SSP/MA" data-callback="alteraSSP" value="<?=$ssp?>">
                </div>

            </div>
        </div>

        <!-- CONTATOS -->
        <div class="p-3 mb-3 rounded bg-light">
            <strong class="d-block mb-2 text-primary">Contatos</strong>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="TELEFONE_CEL" class="form-label small">Telefone celular</label>
                    <input class="form-control" type="text" name="telefone_celular" id="TELEFONE_CEL" placeholder="(00)90000-0000" data-mask="(##)#####-####" value="<?= htmlspecialchars(function_exists('format_telefone') ? format_telefone($telefone_celular ?? '') : $telefone_celular) ?>">
                </div>

                <div class="col-md-4">
                    <label for="TELEFONE_RES" class="form-label small">Telefone residencial</label>
                    <input class="form-control" type="text" name="telefone_residencial" id="TELEFONE_RES" placeholder="(00)0000-0000" data-mask="(##)####-####" value="<?= htmlspecialchars(function_exists('format_telefone') ? format_telefone($telefone_residencial ?? '') : $telefone_residencial) ?>">
                </div>

                <div class="col-md-4">
                    <label for="TELEFONE_CONT" class="form-label small">Telefone de contato</label>
                    <input class="form-control" type="text" name="telefone_contato" id="TELEFONE_CONT" placeholder="(00)90000-0000" data-mask="(##)#####-####" value="<?= isset($telefone_contato) ? htmlspecialchars(function_exists('format_telefone') ? format_telefone($telefone_contato) : $telefone_contato) : '' ?>">
                </div>

                <div class="col-12">
                    <label for="email" class="form-label small">E-mail</label>
                    <input class="form-control" type="email" name="email" placeholder="email@exemplo.com" value="<?=$email?>">
                </div>
            </div>
        </div>

        <!-- RESIDÊNCIA -->
        <div class="p-3 mb-3 rounded bg-light">
            <strong class="d-block mb-2 text-primary">Residência</strong>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="estado" class="form-label small">UF</label>
                    <select name="estado" class="form-select">
                        <option value="" disabled>UF</option>
                        <?php
                            $selectedUf = isset($estado) ? (string)$estado : '';
                            include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                            unset($selectedUf);
                        ?>
                    </select>
                </div>

                <div class="col-md-8">
                    <label for="MUNICIPIO" class="form-label small">Município</label>
                    <input class="form-control" type="text" name="municipio" id="MUNICIPIO" placeholder="Nome do município" data-titlecase="true" value="<?=$municipio?>">
                </div>

                <div class="col-md-3">
                    <label for="CEP" class="form-label small">CEP</label>
                    <input class="form-control" type="text" name="cep" id="CEP" placeholder="00000-000" data-mask="#####-###" inputmode="numeric" value="<?=$cep?>">
                </div>

                <div class="col-md-9">
                    <label for="BAIRRO" class="form-label small">Bairro</label>
                    <input class="form-control" type="text" name="bairro" id="BAIRRO" placeholder="Nome do bairro" data-titlecase="true" value="<?=$bairro?>">
                </div>

                <div class="col-12">
                    <label for="LOGRADOURO" class="form-label small">Logradouro</label>
                    <input class="form-control" type="text" name="endereco" id="LOGRADOURO" placeholder="Nome do logradouro" data-titlecase="true" value="<?=$endereco?>">
                </div>

                <div class="col-md-4">
                    <label for="NUMERO" class="form-label small">Número</label>
                    <input class="form-control" type="text" name="numero" id="NUMERO" placeholder="Número" inputmode="numeric" data-numeric="true" value="<?=$numero?>">
                </div>

                <div class="col-md-8">
                    <label for="complemento" class="form-label small">Complemento</label>
                    <input class="form-control" type="text" name="complemento" placeholder="Apt, casa, etc." value="<?=$complemento?>">
                </div>

                <div class="col-12">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="lgpd_consent" id="lgpd_consent" <?= $lgpd_consent ? 'checked' : '' ?> required>
                        <label class="form-check-label small" for="lgpd_consent">Consentimento LGPD *</label>
                        <div class="form-text">Concordo com o tratamento dos meus dados pessoais conforme a LGPD.</div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!--------------------------------------------------------->

    <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" name="editar" class="btn btn-success">Salvar</button>
    </div>

</form>

<!----------------------------fim da da janela modal----------------------------->


<?php 
endif;
?>
