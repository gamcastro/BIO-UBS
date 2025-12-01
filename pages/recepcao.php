<?php
//----titulo da página------
$tituloDaPagina = "Recepção - Check-in";
//---------------------------
require_once __DIR__ . '/../vendor/autoload.php'; // Autoloader
include_once(__DIR__ . '/../includes/header.php'); 

//-----------classes que serão usadas-----
use BioUBS\UbsCrudAll;
use BioUBS\Idade;
//----------------------------------------

// Processamento do formulário de cadastro de paciente
if ($nivelAcesso == 1 && isset($_POST['salvar']) && isset($_POST['origem_recepcao']) && $_POST['origem_recepcao'] == '1') {
    // Cadastro vindo da página de recepção
    include(__DIR__ . '/../querys/inserts/insertPaciente.php');
}

// Pega o ID da unidade da sessão
$id_unidade = $_SESSION['ubs_id'] ?? null;

if (!$id_unidade) {
    try {
        $pdo = BioUBS\Conexao::getConn();

        // 1. Se temos o nome da UBS na sessão, tenta resolver pelo nome
        if (!empty($_SESSION['ubs_nome'])) {
            $stmt = $pdo->prepare("SELECT ID FROM cadastro_unidade WHERE NOME = :nome LIMIT 1");
            $stmt->execute([':nome' => $_SESSION['ubs_nome']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['ID'])) {
                $id_unidade = (int)$row['ID'];
                $_SESSION['ubs_id'] = $id_unidade;
            }
        }

        // 2. Se ainda não achou, pega a primeira unidade cadastrada
        if (!$id_unidade) {
            $stmt = $pdo->query("SELECT ID, NOME FROM cadastro_unidade ORDER BY ID ASC LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['ID'])) {
                $id_unidade = (int)$row['ID'];
                $_SESSION['ubs_id'] = $id_unidade;
                if (empty($_SESSION['ubs_nome'])) {
                    $_SESSION['ubs_nome'] = $row['NOME'];
                }
            }
        }
    } catch (Exception $e) {
        // Silencia erro e segue para mensagem amigável
    }
}

if (!$id_unidade) {
    echo '<div class="alert alert-danger">Erro: Unidade não identificada. Faça login novamente ou contate o administrador.</div>';
    echo '<div class="mt-3"><a href="../logout.php" class="btn btn-outline-secondary btn-sm">Sair e Fazer Login Novamente</a></div>';
    exit;
}
?> 

<h1 class="display-5 text-center text-muted mb-4">
    <i class="bi bi-door-open me-2"></i>Recepção - Check-in de Pacientes
</h1>
<hr class="mb-4">

<!-- Container Principal -->
<div class="container-fluid">
    <div class="row">
        <!-- Coluna Esquerda: Busca e Resultado -->
        <div class="col-lg-6 mb-4">
            
            <!-- Card de Busca -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-search me-2"></i>Buscar Paciente
                    </h5>
                    
                    <form id="formBuscaPaciente" autocomplete="off">
                        <div class="input-group input-group-lg">
                            <input type="text"
                                   class="form-control"
                                   id="termo_busca"
                                   name="termo_busca"
                                   placeholder="Digite Nome, CPF ou CNS"
                                   autocomplete="off"
                                   required>
                        </div>
                        <small class="form-text text-muted">
                            Digite ao menos 2 letras ou números. Use o menu de sugestões.
                        </small>
                    </form>
                </div>
            </div>

            <!-- Alerta de Cadastro Realizado com Sucesso (exibido quando volta do cadastro) -->
            <?php if (isset($_GET['cadastro_sucesso']) && $_GET['cadastro_sucesso'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Paciente cadastrado com sucesso!</strong> Você pode fazer a busca novamente para realizar o check-in.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php 
                // Se vier com CPF no parâmetro, preenche automaticamente
                if (isset($_GET['cpf'])):
                    $cpfAutomatico = $_GET['cpf'];
                    echo "<script>
                        $(document).ready(function() {
                            $('#termo_busca').val('{$cpfAutomatico}');
                            // Após cadastro, usuário pode selecionar no autocomplete
                        });
                    </script>";
                endif;
            ?>
            <?php endif; ?>

            <!-- Card do Paciente (Oculto inicialmente) -->
            <div id="cardPaciente" class="card shadow-sm" style="display: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-check-fill text-success me-2"></i>Paciente Encontrado
                        </h5>
                        <button type="button" class="btn-close" id="btnFecharCard"></button>
                    </div>
                    
                    <!-- Avatar e Informações do Paciente -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-paciente me-3">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1" id="pacienteNome">-</h4>
                            <div class="text-muted">
                                <small><i class="bi bi-calendar3 me-1"></i><strong>Idade:</strong> <span id="pacienteIdade">-</span> anos</small><br>
                                <small><i class="bi bi-person me-1"></i><strong>Nome da Mãe:</strong> <span id="pacienteMae">-</span></small><br>
                                <small><i class="bi bi-card-text me-1"></i><strong>CPF:</strong> <span id="pacienteCPF">-</span></small><br>
                                <small><i class="bi bi-credit-card me-1"></i><strong>CNS:</strong> <span id="pacienteCNS">-</span></small>
                            </div>
                        </div>
                    </div>

                    <!-- Queixa Principal -->
                    <div class="mb-3">
                        <label for="queixaPrincipal" class="form-label fw-bold">
                            <i class="bi bi-chat-left-text me-1"></i>Queixa/Motivo da Visita
                        </label>
                        <textarea class="form-control" 
                                  id="queixaPrincipal" 
                                  rows="3" 
                                  placeholder="Descreva brevemente o motivo da visita (opcional)"></textarea>
                        <small class="form-text text-muted">Campo opcional - pode ser preenchido posteriormente na triagem</small>
                    </div>

                    <!-- Botão de Check-in -->
                    <div class="d-grid">
                        <button type="button" class="btn btn-success btn-lg" id="btnCheckin">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Confirmar Chegada - Enviar para Triagem
                        </button>
                    </div>

                    <input type="hidden" id="pacienteID" value="">
                </div>
            </div>

        </div>

        <!-- Coluna Direita: Fila de Espera -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-people-fill me-2"></i>Fila de Espera - Aguardando Triagem
                        <span class="badge bg-primary" id="contadorFila">0</span>
                    </h5>

                    <!-- Lista de Espera -->
                    <div id="listaEspera" class="list-group">
                        <!-- Será preenchido via JavaScript -->
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-hourglass-split" style="font-size: 2rem;"></i>
                            <p class="mt-2">Carregando fila de espera...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Cadastro de Paciente -->
<?php include(__DIR__ . '/../modal/cadastro/modalCadastroDePacientes.php'); ?>

<!-- Scripts -->
<script>window.BASE_URL='<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>/js/jquery-3.3.1.js"></script>
<script src="<?= BASE_URL ?>/js/mask/funcaoMascaraGeralNumeros.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/recepcao.css">
<script>
// Garante que o formulário de cadastro submeta para a página correta
$(document).ready(function(){
    $('#cad').attr('action', '<?= BASE_URL ?>/pages/recepcao.php');
});
</script>
<script src="<?= BASE_URL ?>/js/recepcao.js"></script>

<?php
//-----------incluindo o rodapé 
include_once(__DIR__ . '/../includes/footer.php');
?>
