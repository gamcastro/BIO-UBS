<?php 
/**
 * index.php
 * * Página principal (Dashboard) do sistema.
 * 1. Verifica a sessão (ou tenta login via cookie 'Lembrar-me').
 * 2. Se logado, exibe o header (que inclui a nova sidebar).
 * 3. Exibe o Dashboard de "Acesso Rápido" contextual,
 * complementar à sidebar.
 * 4. Exibe o footer.
 */

session_start();
require_once __DIR__ . '/vendor/autoload.php'; //Autoload do Composer
require_once __DIR__ . '/includes/auth_helper.php'; 
use BioUBS\Conexao; // Importa a classe de conexão

// Tenta obter a conexão com o banco de dados
try {
    $db = Conexao::getConn();
} catch (PDOException $e) {
    die("Erro fatal de conexão com o banco de dados. " . $e->getMessage());
}

// Se não houver sessão, tenta autenticar via remember-me
if (empty($_SESSION['user_id'])) {
    
    $user = try_remember_login($db);
    
    if ($user) {
        // 2. SUCESSO! Cria a sessão COMPLETA
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['username'] = $user['MATRICULA']; // (Matrícula)
        $_SESSION['user_nome'] = $user['NOME_COMPLETO']; // (Para o header)
        $_SESSION['user_perfil'] = $user['PERFIL']; // (Para permissões)
        
    } else {
        // 3. FALHA: O cookie é inválido ou não existe, manda para o login.
        header("Location: login.php");
        exit;
    }
}

// --- Se chegou aqui, o usuário está logado ---

// Define o título e inclui o header
// O header.php agora carrega o sidebar.php e abre o <main>
$tituloDaPagina = "Dashboard - BIO-UBS" ;
include_once(__DIR__ . '/includes/header.php');

// Pega o perfil e o nome da sessão para usar no dashboard
$user_perfil = $_SESSION['user_perfil'] ?? '';
$user_nome = $_SESSION['user_nome'] ?? 'Usuário';

?>

<!--
==================================================================
INÍCIO: Conteúdo do Dashboard (Acesso Rápido)
Este layout é COMPLEMENTAR à sidebar, não redundante.
==================================================================
-->

<div class="container-fluid px-4 py-3">

    <!-- Cabeçalho de Boas-Vindas Pessoal -->
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-1">Bem-vindo(a), <?= htmlspecialchars(explode(' ', $user_nome)[0]) ?>!</h1>
        <p class="text-muted lead fs-6">Seu acesso rápido para o dia a dia.</p>
    </div>

    <!-- 
      Grid de Botões de Ação (Cards) 
      Estes cards são contextuais ao perfil do usuário.
    -->
    <div class="row g-4">

        <?php
        // --- Lógica de Permissão para os Cards ---       

        // Perfis de Atendimento Clínico
        $perfis_clinicos = ['Médico', 'Enfermeiro', 'Auxiliar/Técnico enfermagem', 'Cirurgião dentista', 'ASB - Auxiliar de saúde bucal', 'TSB - Técnico de saúde bucal'];
        
        // Perfis Administrativos/Recepção
        $perfis_recepcao = ['Recepção'];

        // Perfis de Gestão
        $perfis_gestao = ['Coordenador UBS', 'Administrador Município', 'Administrador do Sistema'];
        
        ?>

        
        <?php 
        // --- CARDS PARA PERFIS CLÍNICOS (Médico, Enfermeiro, etc.) ---
        if (in_array($user_perfil, $perfis_clinicos)): 
        ?>
            <!-- Card 1: Fila de Atendimento -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <a href="<?= BASE_URL ?>/pages/painelAtendimento.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                            <i class="bi bi-clipboard2-pulse-fill text-primary" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold mt-3 mb-2">Fila de Atendimento</h5>
                            <p class="card-text small text-muted">Ver pacientes aguardando triagem e consulta.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Card 2: Minha Agenda -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <a href="<?= BASE_URL ?>/pages/agenda.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                            <i class="bi bi-calendar-week text-success" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold mt-3 mb-2">Minha Agenda</h5>
                            <p class="card-text small text-muted">Visualizar seus agendamentos do dia.</p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>


        <?php 
        // --- CARDS PARA RECEPÇÃO (Atendente, ACS, ACE) ---
        if (in_array($user_perfil, $perfis_recepcao)): 
        ?>
            <!-- Card 1: Novo Acolhimento -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <!-- Aciona a mesma modal do botão da sidebar -->
                          <a href="#" data-bs-toggle="modal" data-bs-target="#acolhimentoBioUBS" data-url="<?= BASE_URL ?>/modal/fluxos/modalAcolhimento.php" 
                              class="text-decoration-none text-dark d-flex flex-column h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                            <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold mt-3 mb-2">Novo Acolhimento</h5>
                            <p class="card-text small text-muted">Iniciar um novo acolhimento ou check-in de paciente.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Card 2: Buscar Paciente -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <a href="<?= BASE_URL ?>/pages/cadastroDePacientes.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                            <i class="bi bi-people-fill text-info" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold mt-3 mb-2">Buscar Paciente</h5>
                            <p class="card-text small text-muted">Localizar prontuário ou cadastrar novo paciente.</p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>


        <?php 
        // --- CARDS PARA GESTÃO (Coordenador, Admin) ---
        if (in_array($user_perfil, $perfis_gestao)): 
        ?>
            <!-- Card 1: Gerenciar Profissionais -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <a href="<?= BASE_URL ?>/pages/cadastroDeProfissionais.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                            <i class="bi bi-person-badge text-warning" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold mt-3 mb-2">Gerenciar Profissionais</h5>
                            <p class="card-text small text-muted">Adicionar, editar ou desativar usuários do sistema.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Card 2: Relatórios Rápidos -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <a href="<?= BASE_URL ?>/pages/relatorios.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                            <i class="bi bi-graph-up-arrow text-danger" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold mt-3 mb-2">Relatórios Rápidos</h5>
                            <p class="card-text small text-muted">Acessar relatórios de produtividade e atendimentos.</p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php 
        // --- CARD COMUM A TODOS  ---
       
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <a href="<?= BASE_URL ?>/pages/meuPerfil.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                    <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                        <i class="bi bi-person-fill-gear text-muted" style="font-size: 3rem;"></i>
                        <h5 class="card-title fw-bold mt-3 mb-2">Meu Perfil</h5>
                        <p class="card-text small text-muted">Visualizar seus dados e alterar sua senha.</p>
                    </div>
                </a>
            </div>
        </div>

    </div> <!-- fim .row -->

</div> <!-- fim .container-fluid -->

<!--
==================================================================
FIM: Conteúdo do Dashboard
==================================================================
-->

<?php
// Inclui o footer.php (que fecha a tag <main> e adiciona o <footer>)
include_once(__DIR__ . '/includes/footer.php');
?>

