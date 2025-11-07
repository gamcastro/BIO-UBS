<?php 
// O authorization.php já deve ter sido incluído pela página principal
// (ex: cadastroDeProfissionais.php), mas o autoload é bom garantir.
require_once __DIR__ . '/../vendor/autoload.php'; 
require_once __DIR__ . '/../includes/authorization.php';

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
        
        //-----------criterios de consulta--------------
        
        $tabela = "cadastro_profissional";//--------tabela como parametro
        
        //----------------------------------------------
        
        //----------CONSULTA BÁSICA SEM CRITÉRIOS
        
        try {
            $cadProfissional = new UbsCrudAll($tabela);//----objeto classe UbsCrudAll(parametro)
            // A classe Idade não está sendo usada aqui, mas mantive a instância
            $dataBR = new Idade(); //---------------objeto classe Idade
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
                  <!-------botão iimprimir------->
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Imprimir" onclick="alert('Função Imprimir não implementada');">
                        <i class="bi bi-printer"></i>
                    </button>

      <?php 
        /* verificando o nível de acesso para os botões Editar e Excluir*/ 
        if($nivelAcesso == 1):
      ?>
                        
                <!-------botão editar------->
                        <a href="#" 
                           class="btn btn-sm btn-outline-primary me-1" 
                           data-bs-toggle="modal" 
                           data-bs-target="#updateBioUBS"
                           data-url="../modal/edicao/modalEdCadastroDeProfissional.php?id=<?= $id ?>"
                           title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                <!-------------------------->
                        
                <!-------botão excluir------->
                        <a href="#" 
                           class="btn btn-sm btn-outline-danger" 
                           data-bs-toggle="modal" 
                           data-bs-target="#deleteBioUBS"
                           data-url="../modal/exclusao/modalExCadastroDeProfissional.php?id=<?= $id ?>"
                           title="Excluir">
                            <i class="bi bi-trash3"></i>
                        </a>
                <!-------------------------->

      <?php endif; //---fim controle de acesso?>
                </td>
            </tr>
        <?php 
            }//while
        
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