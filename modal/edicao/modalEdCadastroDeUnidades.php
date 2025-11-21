<?php
// Caminho corrigido (subindo 2 níveis)
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
    // Inicializa as variáveis para evitar erros caso a consulta não retorne nada
    $nomeUnidade = $cnes = $cnpj = $telefone = $cep = $estado = $municipio = $bairro = $logradouro = $numero = $complemento = '';

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
 
    <!------------------janela modal-------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
    <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title mb-0" id="updateModalLabel">Editar unidade</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
    </div>
            <!-------------------------------------------------------->

    <form id="ed" name="ed" action="" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <!----------------CORPO DA JANELA------------------------->
        <div class="modal-body p-3">

            <div class="p-3 mb-3 rounded bg-light">
                <strong class="d-block mb-2 text-primary">Dados da unidade</strong>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="nome" class="form-label small">Nome</label>
                        <input class="form-control" type="text" id="nome" name="nome" required placeholder="Nome da unidade" value="<?= htmlspecialchars($nomeUnidade ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="cnpj" class="form-label small">CNPJ</label>
                        <input class="form-control" type="text" id="cnpj" name="cnpj" required data-mask="##.###.###/####-##" value="<?= htmlspecialchars($cnpj ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="cnes" class="form-label small">CNES</label>
                        <input class="form-control" type="text" id="cnes" name="cnes" placeholder="Cadastro Nacional de Estabelecimentos de Saúde" value="<?= htmlspecialchars($cnes ?? '') ?>">
                    </div>
                    <div class="col-md-8">
                        <label for="telefone" class="form-label small">Telefone</label>
                        <input class="form-control" type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" data-mask="(##)#####-####" value="<?= htmlspecialchars($telefone ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="p-3 mb-3 rounded bg-light">
                <strong class="d-block mb-2 text-primary">Endereço</strong>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="cep" class="form-label small">CEP</label>
                        <input class="form-control" type="text" id="cep" name="cep" placeholder="00000-000" data-mask="#####-###" inputmode="numeric" value="<?= htmlspecialchars($cep ?? '') ?>">
                    </div>
                    <div class="col-md-9">
                        <label for="logradouro" class="form-label small">Logradouro</label>
                        <input class="form-control" type="text" id="logradouro" name="logradouro" data-titlecase="true" value="<?= htmlspecialchars($logradouro ?? '') ?>">
                    </div>

                    <div class="col-md-2">
                        <label for="numero" class="form-label small">Número</label>
                        <input class="form-control" type="text" id="numero" name="numero" inputmode="numeric" data-numeric="true" value="<?= htmlspecialchars($numero ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="bairro" class="form-label small">Bairro</label>
                        <input class="form-control" type="text" id="bairro" name="bairro" data-titlecase="true" value="<?= htmlspecialchars($bairro ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="complemento" class="form-label small">Complemento</label>
                        <input class="form-control" type="text" id="complemento" name="complemento" placeholder="Apto, Bloco, Casa, etc." value="<?= htmlspecialchars($complemento ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="municipio" class="form-label small">Município</label>
                        <input class="form-control" type="text" id="municipio" name="municipio" data-titlecase="true" value="<?= htmlspecialchars($municipio ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="estado_endereco" class="form-label small">Estado (UF)</label>
                        <select name="estado_endereco" id="estado_endereco" class="form-select">
                            <?php $selectedUf = ($estado !== null && $estado !== '' && is_numeric($estado)) ? (string)$estado : null; ?>
                            <?php if ($selectedUf === null): ?>
                                <option value="" disabled selected>UF</option>
                            <?php else: ?>
                                <option value="" disabled>UF</option>
                            <?php endif; ?>
                            <?php require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php'); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------------------------------->

        <!---------------RODAPÉ DA JANELA---------------------->
        <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" name="editar" class="btn btn-success">Salvar</button>
        </div>
    </form>
            <!----------------------------------------------------->
    
<!----------------------------fim da da janela modal----------------------------->
<?php
endif;
?>