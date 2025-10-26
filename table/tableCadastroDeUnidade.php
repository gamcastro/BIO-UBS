<?php require_once('includes/authorization.php'); ?>

<div class="table-responsive">
    <table id="tableBioUBS" class="table table-striped table-hover table-bordered caption-top" style="width:100%"> 
        <caption class="text-muted small">Lista de Unidades Cadastradas</caption>
        <thead class="table-light"> <tr>
                <th>Nome UBS</th>
                <th>CNES</th>
                <th>Município</th>
                <th class="text-center" style="width: 120px;">Ações</th> </tr>
        </thead>
        <tbody>
        <?php
        $tabela = "cadastro_unidade";
        try {
            $cadUbs = new UbsCrudAll($tabela);
            $UbsQuery = $cadUbs->listarTodos();

            if ($UbsQuery) { 
                foreach ($UbsQuery as $registrosUbs) {
                    $id = $registrosUbs['ID'];
                    $nome = $registrosUbs['NOME'];
                    $cnes = $registrosUbs['CNES'];
                    $municipio = $registrosUbs['MUNICIPIO'];
            ?>
                    <tr>
                        <td><?= htmlspecialchars($nome ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($cnes ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($municipio ?? 'N/A') ?></td>
                        <td class="text-center"> <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Imprimir ID: <?= $id ?>" onclick="alert('Imprimir ID: <?= $id ?>');">
                                <i class="bi bi-printer"></i> </button>

                            <?php if($nivelAcesso == 1): ?>
                                <a href="modal/edicao/modalEdCadastroDeUnidades.php?id=<?= $id ?>"
                                   class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" 
                                   data-bs-target="#updateBioUBS" title="Editar Unidade ID: <?= $id ?>">
                                     <i class="bi bi-pencil-square"></i> </a>
                                <a href="modal/exclusao/modalExCadastroDeUnidades.php?id=<?= $id ?>"
                                   class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" 
                                   data-bs-target="#deleteBioUBS" title="Excluir Unidade ID: <?= $id ?>">
                                     <i class="bi bi-trash3"></i> </a>
                            <?php endif; ?>
                        </td>
                    </tr>
            <?php
                } // Fim foreach
            } else {
                echo '<tr><td colspan="4" class="text-center text-muted">Nenhuma unidade cadastrada.</td></tr>';
            }
        } catch (Exception $e) {
            error_log('Erro ao buscar unidades: ' . $e->getMessage()); // Logar erro
            echo '<tr><td colspan="4" class="text-danger text-center">Erro ao carregar dados.</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

