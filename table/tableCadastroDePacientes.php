<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader


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
        
        //-----------criterios de consulta--------------
          
          $tabela = "cadastro_paciente";//--------tabela como parametro 

          $cadPaciente = new UbsCrudAll($tabela);//----objeto classe UbsCrudAll(parametro)

          $dataBR = new Idade(); //---------------objeto classe Idade   

        //----------------------------------------------

        //----------CONSULTA BÁSICA SEM CRITÉRIOS
        
        try {
            $UbsQuery = $cadPaciente->listarTodos(); 

            foreach ($UbsQuery as $registrosUbs) {
                $id = $registrosUbs['ID'];
                $nome = $registrosUbs['NOME'];
                $data_nascimento = $registrosUbs['DATA_NASCIMENTO'];
                $cpf = $registrosUbs['CPF'];

                $dataBR->setIdade($data_nascimento);//---setando data
        ?>


            <tr>
                <td><?= htmlspecialchars($nome ?? '') ?></td>

                <td>
                  <?php 
                      //-------------formatando data
                       echo $dataBR->dataBr();
                  ?>    
                </td>

                <td>
                  <?php 

                      //----------usando a funcao idade Anos
                       echo $dataBR->IdadeAnos();
                       
                       //-----exemplo de idade completa
                       echo " ou (".$dataBR->IdadeCompleta().")";
                  ?>    
                </td>

                <td><?= htmlspecialchars(function_exists('format_cpf') ? format_cpf($cpf ?? '') : $cpf) ?></td>
                
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
                           data-url="../modal/edicao/modalEdCadastroDePacientes.php?id=<?= $id ?>"
                           title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                <!-------------------------->
                        
                <!-------botão excluir------->
                        <a href="#" 
                           class="btn btn-sm btn-outline-danger" 
                           data-bs-toggle="modal" 
                           data-bs-target="#deleteBioUBS"
                           data-url="../modal/exclusao/modalExCadastroDePacientes.php?id=<?= $id ?>"
                           title="Excluir">
                            <i class="bi bi-trash3"></i>
                        </a>
                <!-------------------------->

      <?php endif;//---fim controle de acesso?>
                </td>
            </tr>
        <?php 
            }//while
            
        } catch (Exception $e) {
            echo '<tr><td colspan="5" class="text-danger text-center">Erro ao buscar pacientes: ' . $e->getMessage() . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>