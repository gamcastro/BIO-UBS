<?php
// Carrega o autoloader do Composer (para a query de UFs)
require_once __DIR__ . '/../../vendor/autoload.php';
?>

<!----------------------------janela modal--------------------------------------------------------->

<div class="modal fade" id="insertUnidade" tabindex="-1" aria-labelledby="insertUnidadeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-sm">
            <form id="cadUnidadeForm" name="cadUnidade" action="<?= BASE_URL ?>/pages/cadastroDeUnidades.php" method="post">

                <!-------------CABEÇALHO DA JANELA------------------------->
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title mb-0" id="insertUnidadeLabel">Nova unidade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <!-------------------------------------------------------->

                <!----------------CORPO DA JANELA------------------------->
                <div class="modal-body p-3">

                    <!-- DADOS DA UNIDADE -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Dados da unidade</strong>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cad-nome" class="form-label small">Nome da Unidade</label>
                                <input type="text" class="form-control" id="cad-nome" name="nome" required>
                            </div>
                            <div class="col-md-3">
                                <label for="cad-cnpj" class="form-label small">CNPJ</label>
                                <input type="text" class="form-control" id="cad-cnpj" name="cnpj" placeholder="00.000.000/0000-00" data-mask="##.###.###/####-##">
                            </div>
                            <div class="col-md-3">
                                <label for="cad-cnes" class="form-label small">CNES</label>
                                <input type="text" class="form-control" id="cad-cnes" name="cnes" required placeholder="Apenas números" inputmode="numeric" data-numeric="true">
                            </div>

                            <div class="col-md-6">
                                <label for="cad-telefone" class="form-label small">Telefone</label>
                                <input type="tel" class="form-control" id="cad-telefone" name="telefone" placeholder="(99) 99999-9999" data-mask="(##)#####-####">
                            </div>
                        </div>
                    </div>

                    <!-- ENDEREÇO -->
                    <div class="p-3 mb-3 rounded bg-light">
                        <strong class="d-block mb-2 text-primary">Endereço</strong>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="cad-cep" class="form-label small">CEP</label>
                                <input type="text" class="form-control" id="cad-cep" name="cep" placeholder="00000-000" data-mask="#####-###" inputmode="numeric">
                            </div>
                            <div class="col-md-9">
                                <label for="cad-logradouro" class="form-label small">Logradouro</label>
                                <input type="text" class="form-control" id="cad-logradouro" name="logradouro" data-titlecase="true">
                            </div>

                            <div class="col-md-2">
                                <label for="cad-numero" class="form-label small">Número</label>
                                <input type="text" class="form-control" id="cad-numero" name="numero" inputmode="numeric" data-numeric="true">
                            </div>
                            <div class="col-md-4">
                                <label for="cad-bairro" class="form-label small">Bairro</label>
                                <input type="text" class="form-control" id="cad-bairro" name="bairro" data-titlecase="true">
                            </div>
                            <div class="col-md-6">
                                <label for="cad-complemento" class="form-label small">Complemento</label>
                                <input type="text" class="form-control" id="cad-complemento" name="complemento" placeholder="Apto, Bloco, Casa, etc.">
                            </div>

                            <div class="col-md-6">
                                <label for="cad-municipio" class="form-label small">Município</label>
                                <input type="text" class="form-control" id="cad-municipio" name="municipio" placeholder="Selecione o município" />
                            </div>
                            <div class="col-md-6">
                                <label for="cad-uf" class="form-label small">Estado (UF)</label>
                                <select id="cad-uf" name="uf" class="form-select">
                                    <option value="" disabled selected>Selecione...</option>
                                    <?php
                                    try {
                                        require(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                                    } catch(Exception $e) {
                                        echo '<option value="" disabled>Erro ao carregar UFs</option>';
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Selecione a UF da unidade.</div>
                            </div>
                        </div>
                    </div>

                </div>
                    <!--------------------------------------------------------->

                    <!---------------RODAPÉ DA JANELA---------------------->
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="salvar" class="btn btn-success">Salvar Unidade</button>
                    </div>
                </form>
                <!----------------------------------------------------->
        </div>
    </div>
</div>
<!----------------------------fim da da janela modal----------------------------->