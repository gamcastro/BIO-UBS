<?php
$tituloDaPagina = "Administração - BIO-UBS";

// O header.php (do seu Canvas) já inclui config.php, autoload.php, 
// e o authorization.php (que garante que o usuário está logado).
include_once('../includes/header.php'); 

// --- AUTORIZAÇÃO DA PÁGINA ---
// O header.php carrega a sessão. Agora, verificamos o perfil.
// Esta é uma SEGUNDA camada de segurança, caso um usuário
// não-admin tente acessar a URL diretamente.

$userProfile = $_SESSION['user_perfil'] ?? '';

// Usamos os mesmos perfis que definimos na sidebar.php
$perfis_admin = ['COORDENADOR UBS', 'Administrador Município', 'Administrador do Sistema'];
$isAdmin = in_array($userProfile, $perfis_admin);

if (!$isAdmin) {
    // Se não for admin, exibe mensagem de erro e para.
    echo '<div class="alert alert-danger" role="alert">';
    echo '  <h4 class="alert-heading">Acesso Negado</h4>';
    echo '  <p>Você não tem permissão para acessar esta página.</p>';
    echo '</div>';
    
    // Inclui o rodapé e encerra o script.
    include_once('../includes/footer.php');
    exit; 
}

// --- Se chegou aqui, o usuário É ADMIN ---
?>

<!-- Conteúdo da Página de Administração -->
<div class="container-fluid px-4 py-3">

    <!-- Cabeçalho -->
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-1">Painel de Administração</h1>
        <p class="text-muted lead fs-6">Gestão do sistema, cadastros-base e configurações.</p>
    </div>

    <!-- Grid de Cards de Ação -->
    <div class="row g-4">

        <!-- Card 1: Configurar Unidade -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- Este link aponta para a nova página que substitui o 'cadastroDeUnidades.php' -->
                <a href="<?= BASE_URL ?>/pages/configuracaoUnidade.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                    <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                        <i class="bi bi-building-gear text-primary" style="font-size: 3rem;"></i>
                        <h5 class="card-title fw-bold mt-3 mb-2">Dados da Unidade</h5>
                        <p class="card-text small text-muted">Editar o nome, CNES, endereço e informações da UBS.</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Card 2: Gerenciar Profissionais -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- Este link aponta para o cadastro de profissionais que já fizemos -->
                <a href="<?= BASE_URL ?>/pages/cadastroDeProfissionais.php" class="text-decoration-none text-dark d-flex flex-column h-100">
                    <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                        <i class="bi bi-people-fill text-success" style="font-size: 3rem;"></i>
                        <!-- MUDANÇA: Título mais claro -->
                        <h5 class="card-title fw-bold mt-3 mb-2">Gestão de Profissionais</h5>
                        <!-- MUDANÇA: Descrição mais precisa -->
                        <p class="card-text small text-muted">Adicionar, editar e gerenciar os profissionais do sistema.</p>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Card 3: Gerenciar Campanhas (Exemplo Futuro) -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 bg-light">
                <!-- O 'disabled' e 'tabindex' fazem o link não ser clicável -->
                <a href="#" class="text-decoration-none text-muted d-flex flex-column h-100 disabled" tabindex="-1" aria-disabled="true">
                    <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                        <i class="bi bi-megaphone-fill" style="font-size: 3rem;"></i>
                        <h5 class="card-title fw-bold mt-3 mb-2">Campanhas (Em breve)</h5>
                        <p class="card-text small">Gerenciar campanhas de vacinação e comunicados.</p>
                    </div>
                </a>
            </div>
        </div>

    </div> <!-- fim .row -->

</div> <!-- fim .container-fluid -->


<?php
// Inclui o footer.php (do seu Canvas)
include_once('../includes/footer.php');
?>