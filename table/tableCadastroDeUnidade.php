<?php
// require_once __DIR__ . '/../includes/authorization.php'; 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader

use BioUBS\UbsCrudAll;
?>


<div class="table-responsive">
    <table id="tableBioUBS" class="table table-striped table-hover table-bordered caption-top">
        <caption class="text-muted small">Lista de Unidades Cadastradas</caption>
        <thead class="table-light">
            <tr>
                <th>Nome UBS</th>
                <th>CNES</th>
                <th>Município</th>
                <th class="text-center" style="width: 120px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tabela = "cadastro_unidade";
            try {
                $cadUbs = new UbsCrudAll($tabela);
                $UbsQuery = $cadUbs->listarTodos();

                foreach ($UbsQuery as $registrosUbs) {
                    $id = $registrosUbs['ID'];
                    $nome = $registrosUbs['NOME'];
                    $cnes = $registrosUbs['CNES'];
                    $municipio = $registrosUbs['MUNICIPIO'];
            ?>
                    <tr>
                        <td><?= htmlspecialchars($nome ?? '') ?></td>
                        <td><?= htmlspecialchars($cnes ?? '') ?></td>
                        <td><?= htmlspecialchars($municipio ?? '') ?></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Imprimir" onclick="alert('Função Imprimir não implementada');">
                                <i class="bi bi-printer"></i>
                            </button>

                            <?php if ($nivelAcesso == 1): ?>

                                <a href="#"
                                    class="btn btn-sm btn-outline-primary me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateBioUBS"
                                    data-url="../modal/edicao/modalEdCadastroDeUnidades.php?id=<?= $id ?>"
                                    title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="#"
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteBioUBS"
                                    data-url="../modal/exclusao/modalExCadastroDeUnidades.php?id=<?= $id ?>"
                                    title="Excluir">
                                    <i class="bi bi-trash3"></i>
                                </a>


                            <?php endif; ?>
                        </td>
                    </tr>
            <?php
                } // Fim foreach 
            } catch (Exception $e) {
                echo '<tr><td colspan="4" class="text-danger text-center">Erro ao buscar unidades: ' . $e->getMessage() . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>