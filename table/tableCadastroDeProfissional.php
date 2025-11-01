<?php 
// O authorization.php já deve ter sido incluído pela página principal
// (ex: cadastroDeProfissionais.php), mas o autoload é bom garantir.
require_once __DIR__ . '/../vendor/autoload.php'; 

use BioUBS\UbsCrudAll;
use BioUBS\Idade;
?>

<div class="table-responsive">
    <table id="tableBioUBS" class="table table-striped table-hover table-bordered caption-top"> 
        <caption class="text-muted small">Lista de Profissionais Cadastrados</caption>
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Perfil</th>
                <th>CNS</th>
                <th class="text-center" style="width: 120px;">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        
        $tabela = "cadastro_profissional";
        
        try {
            $cadProfissional = new UbsCrudAll($tabela);
            // A classe Idade não está sendo usada aqui, mas mantive a instância
            $dataBR = new Idade(); 
            $UbsQuery = $cadProfissional->listarTodos(); 

            foreach ($UbsQuery as $registrosUbs) {
                $id = $registrosUbs['ID'];
                $nome = $registrosUbs['NOME_COMPLETO'];
                $cns = $registrosUbs['CNS_PROFISSIONAL'];
                $perfil = $registrosUbs['PERFIL'];
        ?>
            <tr>
                <td><?= htmlspecialchars($nome ?? '') ?></td>
                <td><?= htmlspecialchars($perfil ?? '') ?></td>
                <td><?= htmlspecialchars($cns ?? '') ?></td>
                
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Imprimir" onclick="alert('Função Imprimir não implementada');">
                        <i class="bi bi-printer"></i>
                    </button>

                    <?php if($nivelAcesso == 1): ?>
                        
                        <a href="#" 
                           class="btn btn-sm btn-outline-primary me-1" 
                           data-bs-toggle="modal" 
                           data-bs-target="#updateBioUBS"
                           data-url="../modal/edicao/modalEdCadastroDeProfissional.php?id=<?= $id ?>"
                           title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        
                        <a href="#" 
                           class="btn btn-sm btn-outline-danger" 
                           data-bs-toggle="modal" 
                           data-bs-target="#deleteBioUBS"
                           data-url="../modal/exclusao/modalExCadastroDeProfissional.php?id=<?= $id ?>"
                           title="Excluir">
                            <i class="bi bi-trash3"></i>
                        </a>
                    <?php endif; //---fim controle de acesso?>
                </td>
            </tr>
        <?php 
            } // Fim foreach 
        
        // 5. Bloco Catch para erros de banco
        } catch (Exception $e) {
            echo '<tr><td colspan="4" class="text-danger text-center">Erro ao buscar profissionais: ' . $e->getMessage() . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<?php
// 6. REMOVIDO: O <script src="tableScript/tableSimples.js"></script>
//    Este script já deve estar no seu 'footer.php' e não deve ser
//    incluído aqui neste arquivo parcial.
?>