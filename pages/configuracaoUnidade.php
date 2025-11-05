<?php
/**
 * pages/configuracaoUnidade.php
 * * Página para GERENCIAR a única unidade do sistema (ID = 1).
 * * Esta versão CORRIGIDA lida tanto com a CRIAÇÃO (INSERT) 
 * * quanto com a EDIÇÃO (UPDATE) da unidade.
 */

$tituloDaPagina = "Dados da Unidade - BIO-UBS";

// 1. INCLUDES E AUTORIZAÇÃO
// =============================================
include_once('../includes/header.php'); 
use BioUBS\Conexao; 
use BioUBS\UbsCrudAll; 

// 2. VERIFICAÇÃO DE PERFIL (Segurança)
// =============================================
$userProfile = $_SESSION['user_perfil'] ?? '';
$perfis_admin = ['COORDENADOR UBS', 'Administrador Município', 'Administrador do Sistema'];
$isAdmin = in_array($userProfile, $perfis_admin);

if (!$isAdmin) {
    echo '<div class="alert alert-danger" role="alert">';
    echo '  <h4 class="alert-heading">Acesso Negado</h4>';
    echo '  <p>Você não tem permissão para acessar esta página.</p>';
    echo '</div>';
    include_once('../includes/footer.php');
    exit; 
}

// Define o ID fixo da nossa unidade.
$id_unidade_fixo = 1;

$mensagem = null; 
$tipoMensagem = null; 

try {
    $db = Conexao::getConn();
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Erro fatal de conexão com o banco.</div>';
    include_once('../includes/footer.php');
    exit;
}

// 3. LÓGICA DE UPDATE ou INSERT (Processa o formulário)
// =============================================
if (isset($_POST['salvar'])) {
    
    $tabela = 'cadastro_unidade';
    
    // Nomes das colunas do banco que podem ser atualizadas/criadas
    $colunasPermitidas = [
        'NOME', 'CNES', 'CNPJ', 'TELEFONE', 'CEP', 'ESTADO',
        'MUNICIPIO', 'BAIRRO', 'LOGRADOURO', 'NUMERO', 'COMPLEMENTO'
    ];
    
    // Prepara o array de dados para o update/insert
    $dados = [
        'NOME' => $_POST['nome'] ?? null,
        'CNES' => $_POST['cnes'] ?? null,
        'CNPJ' => $_POST['cnpj'] ?? null,
        'TELEFONE' => $_POST['telefone'] ?? null,
        'CEP' => $_POST['cep'] ?? null,
        'ESTADO' => $_POST['uf'] ?? null, 
        'MUNICIPIO' => $_POST['municipio'] ?? null,
        'BAIRRO' => $_POST['bairro'] ?? null,
        'LOGRADOURO' => $_POST['logradouro'] ?? null,
        'NUMERO' => $_POST['numero'] ?? null,
        'COMPLEMENTO' => $_POST['complemento'] ?? null
    ];

    // *** LÓGICA DE DECISÃO (INSERT vs UPDATE) ***
    
    // 1. Verifica se a unidade ID=1 já existe
    // NOTA: É importante que o construtor da sua UbsCrudAll
    // possa receber a conexão $db ou ela falhará aqui.
    // Assumindo: public function __construct(string $tabela, array $permitidas = [], PDO $db = null)
    // E que Conexao::getConn() seja estático.
    $checkObj = new UbsCrudAll($tabela, ['ID']); // Removido $db se o construtor não o pega
    $unidadeExistente = $checkObj->buscarPorId($id_unidade_fixo);

    if ($unidadeExistente) {
        // --- MODO UPDATE (A unidade já existe) ---
        $objetoUpdate = new UbsCrudAll($tabela, $colunasPermitidas);
        
        // --- INÍCIO DA CORREÇÃO ---
        // "Avisamos" à superclasse que a nossa chave primária é 'ID' (maiúsculo),
        // corrigindo o bug do 'id' (minúsculo) na função 'atualizar'.
        $objetoUpdate->chavePrimaria = 'ID';
        // --- FIM DA CORREÇÃO ---
        
        if ($objetoUpdate->atualizar($id_unidade_fixo, $dados)) {
            $mensagem = "Dados da unidade atualizados com sucesso!";
            $tipoMensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar os dados. Tente novamente.";
            $tipoMensagem = "danger";
        }
    } else {
        // --- MODO INSERT (A unidade NÃO existe) ---
        
        // Adiciona o ID fixo aos dados, já que é uma inserção
        $dados['ID'] = $id_unidade_fixo; 
        // Adiciona 'ID' às colunas permitidas para a inserção
        $colunasPermitidas[] = 'ID'; 

        $objetoInsert = new UbsCrudAll($tabela, $colunasPermitidas);
        if ($objetoInsert->inserir($dados)) {
            $mensagem = "Dados da unidade criados com sucesso!";
            $tipoMensagem = "success";
        } else {
            $mensagem = "Erro ao criar os dados da unidade.";
            $tipoMensagem = "danger";
        }
    }
}


// 4. LÓGICA DE SELECT (Busca dados para preencher o formulário)
// =============================================
// Esta lógica roda *depois* do update/insert,
// então ela sempre pega os dados mais recentes.
$tabela = 'cadastro_unidade';
$colunasPermitidas = ['*'];
$objetoSelect = new UbsCrudAll($tabela, $colunasPermitidas);

// Busca sempre pelo ID fixo (função corrigida por você)
$dadosUnidade = $objetoSelect->buscarPorId($id_unidade_fixo);

if (!$dadosUnidade) {
    // Se não houver dados (mesmo após uma tentativa de INSERT),
    // a página carregará com o formulário vazio.
    $nome = $cnes = $cnpj = $telefone = $cep = $uf = $municipio = $bairro = $logradouro = $numero = $complemento = '';
} else {
    // *** A CORREÇÃO ESTÁ AQUI ***
    // Mudamos de $dadosUnidade['NOME'] para $dadosUnidade->NOME
    // (Linha 126 original)
    $nome = $dadosUnidade->NOME;
    $cnes = $dadosUnidade->CNES;
    $cnpj = $dadosUnidade->CNPJ;
    $telefone = $dadosUnidade->TELEFONE;
    $cep = $dadosUnidade->CEP;
    $uf = $dadosUnidade->ESTADO;
    $municipio = $dadosUnidade->MUNICIPIO;
    $bairro = $dadosUnidade->BAIRRO;
    $logradouro = $dadosUnidade->LOGRADOURO;
    $numero = $dadosUnidade->NUMERO;
    $complemento = $dadosUnidade->COMPLEMENTO;
}

?>

<!-- 
==================================================================
INÍCIO: Conteúdo HTML da Página
==================================================================
-->

<div class="container-fluid px-4 py-3">

    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Configurar Dados da Unidade</h1>
            <p class="text-muted lead fs-6">Edite as informações principais da UBS.</p>
        </div>
        <a href="<?= BASE_URL ?>/pages/administracao.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Voltar para Administração
        </a>
    </div>

    <!-- Alerta de Feedback (Sucesso ou Erro) -->
    <?php if ($mensagem): ?>
    <div class="alert alert-<?= $tipoMensagem ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($mensagem); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <!-- Mensagem de Alerta se a Unidade NÃO ESTIVER CRIADA -->
    <?php if (!$dadosUnidade && !isset($_POST['salvar'])): ?>
    <div class="alert alert-warning" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        Nenhum dado da unidade encontrado. Preencha o formulário abaixo para configurar o sistema pela primeira vez.
    </div>
    <?php endif; ?>


    <!-- Formulário de Edição/Criação -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
            
            <!-- O formulário posta para esta própria página -->
            <form id="configUnidadeForm" name="configUnidade" action="configuracaoUnidade.php" method="post"> 
                
                <h6 class="text-primary mb-3 border-bottom pb-2">DADOS DA UNIDADE</h6>
                
                <div class="row g-3 mb-3"> 
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               value="<?= htmlspecialchars($nome); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00"
                               value="<?= htmlspecialchars($cnpj); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="cnes" class="form-label">CNES <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cnes" name="cnes" required placeholder="Apenas números"
                               value="<?= htmlspecialchars($cnes); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(99) 99999-9999"
                               value="<?= htmlspecialchars($telefone); ?>">
                    </div>
                </div>

                <h6 class="text-primary mb-3 border-bottom pb-2 pt-3">ENDEREÇO</h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000"
                               value="<?= htmlspecialchars($cep); ?>">
                    </div>
                    <div class="col-md-8">
                        <label for="logradouro" class="form-label">Logradouro</label>
                        <input type="text" class="form-control" id="logradouro" name="logradouro"
                               value="<?= htmlspecialchars($logradouro); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero"
                               value="<?= htmlspecialchars($numero); ?>">
                    </div>
                    <div class="col-md-5">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="bairro" name="bairro"
                               value="<?= htmlspecialchars($bairro); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complemento" name="complemento"
                               value="<?= htmlspecialchars($complemento); ?>">
                    </div>
                    <div class="col-md-8">
                        <label for="municipio" class="form-label">Município</label>
                        <input type="text" class="form-control" id="municipio" name="municipio"
                               value="<?= htmlspecialchars($municipio); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="uf" class="form-label">Estado (UF)</label>
                        <select id="uf" name="uf" class="form-select"> 
                            <option value="" selected>Selecione...</option>
                            <?php 
                            require('../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                        
                        <script>
                            // Este script roda DEPOIS que o HTML é carregado
                            document.addEventListener('DOMContentLoaded', function() {
                                var selectUF = document.getElementById('uf');
                                if (selectUF) {
                                    // Define o valor do <select> com base no que veio do banco
                                    selectUF.value = "<?= htmlspecialchars($uf); ?>";
                                }
                            });
                        </script>
                    </div>
                </div>

                <!-- Botão de Salvar (Contextual) -->
                <div class="text-end mt-4 pt-4 border-top">
                    <button type="submit" name="salvar" class="btn btn-primary btn-lg px-5">
                        <?php if ($dadosUnidade): ?>
                            Salvar Alterações
                        <?php else: ?>
                            Criar Unidade
                        <?php endif; ?>
                    </button>
                </div>

            </form> 
        </div> <!-- fim .card-body -->
    </div> <!-- fim .card -->

</div> <!-- fim .container-fluid -->


<?php
// Inclui o footer.php (do seu Canvas)
include_once('../includes/footer.php');
?>