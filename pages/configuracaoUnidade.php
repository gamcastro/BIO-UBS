<?php
// 1. INCLUDES E AUTORIZAÇÃO
// =============================================
include_once(__DIR__ . '/../includes/header.php'); 
use BioUBS\Conexao; 
use BioUBS\UbsCrudAll; 

// 2. VERIFICAÇÃO DE PERFIL (Segurança)
// =============================================
$userProfile = $_SESSION['user_perfil'] ?? '';
$perfis_admin = ['Coordenador UBS', 'Administrador Município', 'Administrador do Sistema'];
$isAdmin = in_array($userProfile, $perfis_admin);

if (!$isAdmin) {
    echo '<div class="alert alert-danger" role="alert">';
    echo '  <h4 class="alert-heading">Acesso Negado</h4>';
    echo '  <p>Você não tem permissão para acessar esta página.</p>';
    echo '</div>';
    include_once(__DIR__ . '/../includes/footer.php');
    exit; 
}

$mensagem = null; 
$tipoMensagem = null; 

try {
    $db = Conexao::getConn();
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Erro fatal de conexão com o banco.</div>';
    include_once(__DIR__ . '/../includes/footer.php');
    exit;
}

$tabela = 'cadastro_unidade';

// 3. LÓGICA DE UPDATE ou INSERT (Processa o formulário)
// =============================================
if (isset($_POST['salvar'])) {
    
    // Nomes das colunas do banco que podem ser atualizadas/criadas
    $colunasPermitidas = [
        'NOME', 'CNES', 'CNPJ', 'TELEFONE', 'CEP', 'ESTADO',
        'ID_MUNICIPIO', 'BAIRRO', 'LOGRADOURO', 'NUMERO', 'COMPLEMENTO'
    ];
    
    // Prepara o array de dados para o update/insert
    // Sanitiza CNES, TELEFONE e CEP para armazenar apenas dígitos
    $telefone_raw = $_POST['telefone'] ?? null;
    $cnes_raw = $_POST['cnes'] ?? null;
    $cep_raw = $_POST['cep'] ?? null;
    $telefone_digits = $telefone_raw !== null ? preg_replace('/\D+/', '', (string)$telefone_raw) : null;
    $cnes_digits = $cnes_raw !== null ? preg_replace('/\D+/', '', (string)$cnes_raw) : null;
    $cep_digits = $cep_raw !== null ? preg_replace('/\D+/', '', (string)$cep_raw) : null;

    $dados = [
        'NOME' => $_POST['nome'] ?? null,
        'CNES' => $cnes_digits !== '' ? $cnes_digits : null,
        'CNPJ' => $_POST['cnpj'] ?? null,
        'TELEFONE' => $telefone_digits !== '' ? $telefone_digits : null,
        'CEP' => $cep_digits !== '' ? $cep_digits : null,        
        'ESTADO' => $_POST['uf'] ?? null, 
        'ID_MUNICIPIO' => $_POST['municipio'] ?? null,
        'BAIRRO' => $_POST['bairro'] ?? null,
        'LOGRADOURO' => $_POST['logradouro'] ?? null,
        'NUMERO' => $_POST['numero'] ?? null,
        'COMPLEMENTO' => $_POST['complemento'] ?? null
    ];

    // Normalizar ID_MUNICIPIO: aceitar apenas inteiros positivos ou null
    if (isset($dados['ID_MUNICIPIO'])) {
        $raw = $dados['ID_MUNICIPIO'];
        if ($raw === '' || $raw === null) {
            $dados['ID_MUNICIPIO'] = null;
        } else {
            // retirar quaisquer caracteres não-dígitos e converter
            $onlyDigits = preg_replace('/\D+/', '', (string)$raw);
            $idVal = $onlyDigits !== '' ? (int)$onlyDigits : 0;
            $dados['ID_MUNICIPIO'] = $idVal > 0 ? $idVal : null;
        }
    }

    // *** LÓGICA DE DECISÃO (INSERT vs UPDATE) ***
    
    $objetoOperacao = new UbsCrudAll($tabela, $colunasPermitidas);
    $objetoOperacao->chavePrimaria = 'ID'; // Define a PK para 'atualizar()'

    // --- Verifica a *primeira linha* em vez de um ID fixo ---
    $resultadoBusca = $objetoOperacao->buscaLivreParams("LIMIT 1");
    $unidadeExistente = !empty($resultadoBusca) ? $resultadoBusca[0] : null;

    if ($unidadeExistente) {
        // --- MODO UPDATE (A unidade já existe) ---
        $id_real_da_unidade = $unidadeExistente['ID']; 
        $sucesso = $objetoOperacao->atualizar($id_real_da_unidade, $dados);
        
        if ($sucesso) {
            $mensagem = "Dados da unidade atualizados com sucesso!";
            $tipoMensagem = "success";
        } else {
            $mensagem = "Dados salvos. Nenhuma alteração foi detectada.";
            $tipoMensagem = "info"; 
        }

    } else {
        // --- MODO INSERT (A unidade NÃO existe) ---
        $novoId = $objetoOperacao->inserir($dados);
        
        if ($novoId && $novoId !== '0') {
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
$objetoSelect = new UbsCrudAll($tabela); 

// --- Busca a *primeira linha* da tabela, não um ID fixo ---
$resultadoBusca = $objetoSelect->buscaLivreParams("LIMIT 1");
$dadosUnidade = !empty($resultadoBusca) ? $resultadoBusca[0] : null;

// --- Usando sintaxe de ARRAY (['NOME']) ---
$nome = $dadosUnidade['NOME'] ?? '';
$cnes = $dadosUnidade['CNES'] ?? '';
$cnpj = $dadosUnidade['CNPJ'] ?? '';
$telefone = $dadosUnidade['TELEFONE'] ?? '';
$cep = $dadosUnidade['CEP'] ?? '';
// Garantir que o valor do ESTADO seja um número válido
$uf = $dadosUnidade['ESTADO'] ?? '';
// Se não for um número válido, define como 21 (Maranhão)
if (!is_numeric($uf) || $uf <= 0) {
    $uf = '21'; // Maranhão
}
$municipio = $dadosUnidade['ID_MUNICIPIO'] ?? '';
$bairro = $dadosUnidade['BAIRRO'] ?? '';
$logradouro = $dadosUnidade['LOGRADOURO'] ?? '';
$numero = $dadosUnidade['NUMERO'] ?? '';
$complemento = $dadosUnidade['COMPLEMENTO'] ?? '';

// Se o valor de MUNICIPIO for um ID (numérico), tentar resolver o nome
$municipioLabel = '';
$municipioValue = '';
if ($municipio !== null && $municipio !== '') {
    if (is_numeric($municipio)) {
        try {
            $pdoM = Conexao::getConn();
            $stmtM = $pdoM->prepare('SELECT MUNICIPIO FROM ibge_municipios WHERE CD_MUNICIPIO = :id LIMIT 1');
            $stmtM->bindValue(':id', (int)$municipio, PDO::PARAM_INT);
            $stmtM->execute();
            $rowM = $stmtM->fetch(PDO::FETCH_ASSOC);
            if ($rowM) {
                $municipioLabel = $rowM['MUNICIPIO'];
                $municipioValue = (string)(int)$municipio; // mantém o ID como value
            } else {
                // valor numérico mas não encontrado: tratar como vazio
                $municipioLabel = '';
                $municipioValue = '';
            }
        } catch (PDOException $e) {
            $municipioLabel = '';
            $municipioValue = '';
        }
    } else {
        // Não é numérico: assume que o campo armazenava o nome
        $municipioLabel = $municipio;
        $municipioValue = '';
    }
}

// Preparar exibição do telefone: formata apenas para visualização
$telefone_digits_for_display = preg_replace('/\D+/', '', (string)$telefone);
if (function_exists('format_telefone')) {
    $telefone_display = $telefone_digits_for_display ? format_telefone($telefone_digits_for_display) : '';
} else {
    if (strlen($telefone_digits_for_display) === 10) {
        $telefone_display = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1)$2-$3', $telefone_digits_for_display);
    } elseif (strlen($telefone_digits_for_display) === 11) {
        $telefone_display = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1)$2-$3', $telefone_digits_for_display);
    } else {
        $telefone_display = $telefone_digits_for_display;
    }
}

// Preparar exibição do CNES (apenas dígitos)
$cnes_display = preg_replace('/\D+/', '', (string)$cnes);

// Preparar exibição do CEP no formato ##.###-### (ex: 65.047-240)
$cep_digits_for_display = preg_replace('/\D+/', '', (string)$cep);
if ($cep_digits_for_display && strlen($cep_digits_for_display) === 8) {
    $cep_display = preg_replace('/(\d{2})(\d{3})(\d{3})/', '$1.$2-$3', $cep_digits_for_display);
} else {
    $cep_display = $cep_digits_for_display;
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
                               value="<?= htmlspecialchars($nome); ?>" oninput="if(typeof capitalizeNameWithPrepositions === 'function'){ this.value = capitalizeNameWithPrepositions(this.value); }">
                    </div>
                    <div class="col-md-6">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00"
                               value="<?= htmlspecialchars($cnpj); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="cnes" class="form-label">CNES <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cnes" name="cnes" required placeholder="Apenas números" inputmode="numeric" maxlength="7"
                               onkeypress="return mascaras(event, this, '#######');" value="<?= htmlspecialchars($cnes_display); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(99) 99999-9999"
                               value="<?= htmlspecialchars($telefone_display); ?>" onkeypress="return mascaras(event, this, '(##)#####-####');" inputmode="numeric" maxlength="15">
                    </div>
                </div>

                <h6 class="text-primary mb-3 border-bottom pb-2 pt-3">Endereço</h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="cep" class="form-label">CEP</label>
                           <input type="text" class="form-control" id="cep" name="cep" placeholder="00.000-000" inputmode="numeric" maxlength="10"
                               onkeypress="return mascaras(event, this, '##.###-###');" value="<?= htmlspecialchars($cep_display); ?>">
                    </div>
                    <div class="col-md-8">
                        <label for="logradouro" class="form-label">Logradouro</label>
                           <input type="text" class="form-control" id="logradouro" name="logradouro"
                               value="<?= htmlspecialchars($logradouro); ?>" oninput="if(typeof capitalizeNameWithPrepositions === 'function'){ this.value = capitalizeNameWithPrepositions(this.value); }">
                    </div>
                    <div class="col-md-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero"
                               value="<?= htmlspecialchars($numero); ?>">
                    </div>
                    <div class="col-md-5">
                        <label for="bairro" class="form-label">Bairro</label>
                           <input type="text" class="form-control" id="bairro" name="bairro"
                               value="<?= htmlspecialchars($bairro); ?>" oninput="if(typeof capitalizeNameWithPrepositions === 'function'){ this.value = capitalizeNameWithPrepositions(this.value); }">
                    </div>
                    <div class="col-md-4">
                        <label for="complemento" class="form-label">Complemento</label>
                           <input type="text" class="form-control" id="complemento" name="complemento"
                               value="<?= htmlspecialchars($complemento); ?>" oninput="if(typeof capitalizeNameWithPrepositions === 'function'){ this.value = capitalizeNameWithPrepositions(this.value); }">
                    </div>
                    <div class="col-md-4">
                        <label for="uf" class="form-label">Estado (UF)</label>
                        <!-- O name é 'uf' (que envia o ID, ex: 21) -->
                        <select id="uf" name="uf" class="form-select"> 
                            <option value="" disabled>Selecione...</option>
                            <?php 
                            // Define o option selecionado (server-side) antes de incluir o script
                            $selectedUf = $uf ?? '';
                            // Este script gera <option value="21">MA - Maranhão</option>
                            require(__DIR__ . '/../querys/ConsultaUnidadeFederativaSelect.php');
                            ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label for="MUNICIPIO" class="form-label">Município</label>
                        <!-- Campo usado pelo TomSelect genérico de custom.js (ID maiúsculo + data-pref-label) -->
                        <input type="text" class="form-control" id="MUNICIPIO" name="municipio" 
                               placeholder="Selecione o município" 
                               value="<?= htmlspecialchars($municipioValue); ?>" 
                               data-pref-label="<?= htmlspecialchars($municipioLabel); ?>">
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
include_once(__DIR__ . '/../includes/footer.php');
?>

<script>
// TomSelect para município inicializado via custom.js (função initTomSelectForModal adaptada para páginas standalone)
document.addEventListener('DOMContentLoaded', function(){
    // Reutiliza a função genérica de custom.js se estiver disponível
    if (typeof window.initTomSelectForModal === 'function') {
        // Simula estrutura de modal para reutilizar a lógica existente
        var pageContainer = document.querySelector('form#configUnidadeForm') || document.body;
        try {
            window.initTomSelectForModal(pageContainer);
            console.log('[configuracaoUnidade] TomSelect inicializado via custom.js');
        } catch(e) {
            console.error('[configuracaoUnidade] Erro ao inicializar TomSelect', e);
        }
    } else {
        console.warn('[configuracaoUnidade] initTomSelectForModal não disponível em custom.js');
    }
});
</script>