<?php 
// O authorization.php já deve ter sido incluído pela página principal
// (ex: cadastroDePacientes.php), mas o autoload é bom garantir.
require_once __DIR__ . '/../vendor/autoload.php'; 

use BioUBS\UbsCrudAll;
use BioUBS\Idade;
?>

<div class="table-responsive">
    <table id="tableBioUBS" class="table table-striped table-hover table-bordered caption-top">
        <caption class="text-muted small">Lista de Pacientes Cadastrados</caption>
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Data de Nascimento</th>
                <th>Idade</th>
                <th>CPF</th>
                <th class="text-center" style="width: 120px;">Ações</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $tabela = "cadastro_paciente";
        
        try {
            $cadPaciente = new UbsCrudAll($tabela);
            $dataBR = new Idade();
            $UbsQuery = $cadPaciente->listarTodos(); 

            foreach ($UbsQuery as $registrosUbs) {
                $id = $registrosUbs['ID'];
                $nome = $registrosUbs['NOME'];
                $data_nascimento = $registrosUbs['DATA_NASCIMENTO'];
                $cpf = $registrosUbs['CPF'];

                $dataBR->setIdade($data_nascimento);
        ?>


            <tr>
                <td><?= htmlspecialchars($nome ?? '') ?></td>
                <td><?= $dataBR->dataBr() ?></td>
                <td><?= $dataBR->IdadeAnos() ?></td>
                <td><?= htmlspecialchars($cpf ?? '') ?></td>
                
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Imprimir" onclick="alert('Função Imprimir não implementada');">
                        <i class="bi bi-printer"></i>
                    </button>

                    <?php if($nivelAcesso == 1): ?>
                        <a href="#" 
                           class="btn btn-sm btn-outline-primary me-1" 
                           data-bs-toggle="modal" 
                           data-bs-target="#updateBioUBS"
                           data-url="../modal/edicao/modalEdCadastroDePacientes.php?id=<?= $id ?>"
                           title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        
                        <a href="#" 
                           class="btn btn-sm btn-outline-danger" 
                           data-bs-toggle="modal" 
                           data-bs-target="#deleteBioUBS"
                           data-url="../modal/exclusao/modalExCadastroDePacientes.php?id=<?= $id ?>"
                           title="Excluir">
                            <i class="bi bi-trash3"></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php 
            }
            
        } catch (Exception $e) {
            echo '<tr><td colspan="5" class="text-danger text-center">Erro ao buscar pacientes: ' . $e->getMessage() . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>